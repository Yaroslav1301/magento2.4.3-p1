<?php

declare(strict_types=1);

namespace Roadmap\UiComponent\Controller\Customer;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Roadmap\UiComponent\Model\ImageUploader;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;
use Roadmap\UiComponent\Api\GiftRepositoryInterface;
use Roadmap\UiComponent\Api\Data\GiftInterface;
use Roadmap\UiComponent\Api\Data\GiftInterfaceFactory;
use Psr\Log\LoggerInterface;

class Save implements HttpPostActionInterface
{
    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var ImageUploader
     */
    private $imageFileUploader;

    /**
     * @var GiftRepositoryInterface
     */
    private $giftRepository;

    /**
     * @var GiftInterfaceFactory
     */
    private $giftFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param ResultFactory $resultFactory
     * @param RequestInterface $request
     * @param ManagerInterface $messageManager
     * @param ImageUploader $imageUploader
     * @param GiftRepositoryInterface $giftRepository
     * @param GiftInterfaceFactory $giftFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        ResultFactory $resultFactory,
        RequestInterface $request,
        ManagerInterface $messageManager,
        ImageUploader $imageUploader,
        GiftRepositoryInterface $giftRepository,
        GiftInterfaceFactory $giftFactory,
        LoggerInterface $logger
    ) {
        $this->resultFactory = $resultFactory;
        $this->request = $request;
        $this->messageManager = $messageManager;
        $this->imageFileUploader = $imageUploader;
        $this->giftRepository = $giftRepository;
        $this->giftFactory = $giftFactory;
        $this->logger = $logger;
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $data = $this->request->getPostValue();
        $resultFactory = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        if (!$this->validateData($data)) {
            $this->messageManager->addErrorMessage(__(
                'Please fill and fields!'
            ));
            return $resultFactory->setPath('suggest/customer/gift');

        }

        try {
            /**
             * @var GiftInterface $gift
             */
            $gift = $this->giftFactory->create();
            $gift->setName($data['name']);
            $gift->setDescription('');
            $gift->setIsActive(false);
            $imagePath = $this->imageFileUploader->moveFileFromTmp($data['image-file-path']);
            $gift->setMediaUrl($imagePath);
            $gift->setRelatedProductSku($data['sku']);
            $this->giftRepository->save($gift);
            $this->messageManager->addSuccessMessage(__(
                'Your gift was suggested successfully!'
            ));
        } catch (LocalizedException|
        CouldNotSaveException $exception) {
            $this->logger->critical($exception->getMessage());
            $this->messageManager->addErrorMessage(__(
                'Something went wrong!'
            ));
        }

        return $resultFactory->setPath('home');
    }

    /**
     * @return bool
     */
    protected function validateData($data)
    {
        if (!empty($data['name']) && !empty($data['sku']) && !empty($data['image-file-path'])) {
            return true;
        }
        return false;
    }
}
