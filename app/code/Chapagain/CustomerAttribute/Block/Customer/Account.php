<?php
namespace Chapagain\CustomerAttribute\Block\Customer;

use Magento\Backend\Block\Template\Context;
use Magento\Customer\Model\SessionFactory;
use Magento\Framework\App\Http\Context as AuthContext;
use Magento\Framework\UrlInterface;

class Account extends \Magento\Framework\View\Element\Template
{
    private $authContext;
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    protected $storeManager;

    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $customerModel;

    protected $customerRepositoryInterface;
    public function __construct(
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        AuthContext $authContext,
        Context $context,
        UrlInterface $urlBuilder,
        SessionFactory $customerSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Customer $customerModel,
        array $data = []
    ) {
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        $this->authContext = $authContext;
        $this->urlBuilder            = $urlBuilder;
        $this->customerSession       = $customerSession->create();
        $this->storeManager          = $storeManager;
        $this->customerModel         = $customerModel;

        parent::__construct($context, $data);

        $collection = $this->getContracts();
        $this->setCollection($collection);
    }

    public function getBaseUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl();
    }

    public function getMediaUrl()
    {
        return $this->getBaseUrl() . 'pub/media/';
    }

    public function getCustomerLogoUrl($logoPath)
    {
        return $this->getMediaUrl() . 'customer' . $logoPath;
    }

    public function getLogoUrl()
    {
        $customerData = $this->customerModel->load($this->customerSession->getId());
        $logo = $customerData->getData('my_customer_image');
        if (!empty($logo)) {
            return $this->getCustomerLogoUrl($logo);
        }
        return false;
    }

    public function isLoggedIn()
    {
        $isLoggedIn = $this->authContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
        if ($isLoggedIn) {
            return true;
        }
        return false;
    }

    public function getSrc()
    {
        $id = $this->customerSession->start()->getData('customer_id');
        $current_customer = $this->customerRepositoryInterface->getById($id);
        return $current_customer->getCustomAttribute('my_customer_image')->getValue();
    }
}
