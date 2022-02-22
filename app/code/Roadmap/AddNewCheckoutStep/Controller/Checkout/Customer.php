<?php

namespace Roadmap\AddNewCheckoutStep\Controller\Checkout;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\App\RequestInterface;
use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Customer\Api\Data\AddressInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\State\InputMismatchException;

class Customer implements HttpPostActionInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var \Magento\Customer\Api\Data\CustomerInterfaceFactory
     */
    private $customerFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    private $encryptor;

    /**
     * @var \Magento\Customer\Api\Data\AddressInterfaceFactory
     */
    private $dataAddressFactory;

    /**
     * @var AddressRepositoryInterface
     */
    private $addressRepository;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;


    /**
     * @param RequestInterface $request
     * @param \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Framework\Encryption\EncryptorInterface $encryptor
     * @param \Magento\Customer\Api\Data\AddressInterfaceFactory $dataAddressFactory
     * @param AddressRepositoryInterface $addressRepository
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        RequestInterface $request,
        \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        \Magento\Customer\Api\Data\AddressInterfaceFactory $dataAddressFactory,
        AddressRepositoryInterface $addressRepository,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->request = $request;
        $this->customerFactory = $customerFactory;
        $this->storeManager = $storeManager;
        $this->customerRepository = $customerRepository;
        $this->encryptor = $encryptor;
        $this->dataAddressFactory = $dataAddressFactory;
        $this->addressRepository = $addressRepository;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->logger = $logger;
    }

    /**
     * @return ResponseInterface|\Magento\Framework\Controller\Result\Json|ResultInterface
     */
    public function execute()
    {
        $params = $this->getParamsValues();
        $resultJson = $this->resultJsonFactory->create();

        if (!$this->request->isPost() ||
            !$this->checkPasswordConfirmation($params['password'], $params['confirm_password'])) {
            return $resultJson->setData(
                [
                    'result' => false,
                    'comment' => 'Please make sure your passwords match.'
                ]
            );
        }
        if (!$this->passwordValidation($params['password'])) {
            return $resultJson->setData(
                [
                    'result' => false,
                    'comment' => 'Password was not confirmed'
                ]
            );
        }
        try {
            $registeredCustomer = $this->createNewUser($params);
            $this->createNewAddress($params, $registeredCustomer->getId());

            return $resultJson->setData(
                [
                    'result' => true,
                    'comment' => 'You registered account successfully!'
                ]
            );

        } catch (InputException|NoSuchEntityException|InputMismatchException|LocalizedException $e) {
            $this->logger->error($e->getMessage());
            return $resultJson->setData(
                [
                    'result' => false,
                    'comment' => $e->getMessage()
                ]
            );
        }
    }

    protected function checkPasswordConfirmation($password, $confirmation)
    {
        if ($password != $confirmation) {
            return false;
        }
        return true;
    }

    protected function getParamsValues(): array
    {
        $result = [];
        $result['city'] = $this->request->getParam('city');
        $result['company']  = $this->request->getParam('company');
        $result['country_id']  = $this->request->getParam('country_id');
        $result['firstname']  = $this->request->getParam('firstname');
        $result['lastname']  = $this->request->getParam('lastname');
        $result['postcode']  = $this->request->getParam('postcode');
//        $result['region']  = $this->request->getParam('region'); //TODO
//        $result['region_id']  = $this->request->getParam('region_id');
        $result['telephone']  = $this->request->getParam('telephone');
        $result['street']  = $this->request->getParam('street');
        $result['email']  = $this->request->getParam('email');
        $result['password']  = $this->request->getParam('password');
        $result['confirm_password']  = $this->request->getParam('confirm_password');

        return $result;
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function createNewAddress($params, $customerId)
    {
        /**
         * @var $address AddressInterface
         */
        $address = $this->dataAddressFactory->create();

        $address->setFirstname($params['firstname']);
        $address->setLastname($params['lastname']);
        $address->setTelephone($params['telephone']);
        $address->setStreet($params['street']);
        $address->setCity($params['city']);
        $address->setCountryId($params['country_id']);
        $address->setPostcode($params['postcode']);
        $address->setCompany($params['company']);
//        $address->setRegionId($params['region_id']); //TODO
//        $address->setRegion($params['region']);
        $address->setIsDefaultShipping(1);
        $address->setIsDefaultBilling(1);
        $address->setCustomerId($customerId);

        $this->addressRepository->save($address);
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws InputMismatchException
     * @throws InputException
     * @throws LocalizedException
     */
    protected function createNewUser($params)
    {
        /**
         * @var $customer \Magento\Customer\Api\Data\CustomerInterface
         */
        $customer = $this->customerFactory->create();

        $customer->setWebsiteId($this->storeManager->getStore()->getWebsiteId());
        $customer->setEmail($params['email']);
        $customer->setLastname($params['lastname']);
        $customer->setFirstname($params['firstname']);
        $hashedPassword = $this->encryptor->getHash($params['password'], true);

        return $this->customerRepository->save($customer, $hashedPassword);
    }


    /**
     * @param $password
     * @return bool
     */
    protected function passwordValidation($password)
    {
        $length = strlen($password);
        $lowCase = preg_match("#[a-z]#", $password);
        $upperCase = preg_match("#[A-Z]#", $password);
        $number = preg_match("#[0-9]#", $password);

        if ($length && $lowCase && $upperCase && $number) {
            return true;
        }
        return false;
    }
}
