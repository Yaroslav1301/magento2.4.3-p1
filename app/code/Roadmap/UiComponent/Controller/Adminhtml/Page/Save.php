<?php

namespace Roadmap\UiComponent\Controller\Adminhtml\Page;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Exception\LocalizedException;
use Roadmap\UiComponent\Model\ImageUploader;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Roadmap\UiComponent\Model\GiftFactory;
use Roadmap\UiComponent\Model\Gift;
use Roadmap\UiComponent\Api\GiftRepositoryInterface;

class Save extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Roadmap_UiComponent::product_gifts_show';

    /**
     * @var ImageUploader
     */
    protected $imageUploader;

    /**
     * @var GiftFactory
     */
    protected $giftFactory;

    /**
     * @var GiftRepositoryInterface
     */
    protected $giftRepository;

    /**
     * @param ImageUploader $imageUploader
     * @param GiftFactory $giftFactory
     * @param GiftRepositoryInterface $giftRepository
     * @param Context $context
     */
    public function __construct(
        ImageUploader $imageUploader,
        GiftFactory $giftFactory,
        GiftRepositoryInterface $giftRepository,
        Context $context
    ) {
        $this->imageUploader = $imageUploader;
        $this->giftFactory = $giftFactory;
        $this->giftRepository = $giftRepository;
        parent::__construct($context);
    }

    /**
     * @return ResultInterface
     */
    public function execute()
    {
        $giftId = (int)$this->getRequest()->getParam('gift_id');
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $gift = $this->giftFactory->create();

        try {
            if ($giftId) {
                $gift = $this->giftRepository->getById($giftId);
            }
            $postData = $this->getRequest()->getPostValue();
            $gift = $this->updateData($gift, $postData);
            $this->giftRepository->save($gift);
            $this->messageManager->addSuccessMessage(
                __(
                    'Gift %1 has been saved.',
                    $gift->getName()
                )
            );
        } catch (\Throwable $throwable) {
//            $this->logger->critical($throwable->getMessage());
            $this->messageManager->addErrorMessage(__(
                'An error occurred. Please, try again later.'
            ));
        }

        return $resultRedirect->setPath('*/*/index');
    }

    /**
     * Update gift data
     *
     * @param Gift $gift
     * @param $data
     * @return Gift
     */
    protected function updateData(Gift $gift, $data)
    {
        $name = $data['name'] ?? null;
        $description = $data['description'] ?? null;
        $isActive = $data['is_active'] ?? null;
        $mediaUrl = $this->getMediaUrl($data);
        $relatedSku = $data['product_sku'] ?? null;

        $gift->setName($name);
        $gift->setDescription($description);
        $gift->setIsActive($isActive);
        $gift->setMediaUrl($mediaUrl);
        $gift->setRelatedProductSku($relatedSku);
        return $gift;
    }

    /**
     * Get media url
     *
     * @param $data
     * @return string|null
     */
    protected function getMediaUrl($data)
    {
        if (isset($data['media_url'][0]['name']) && isset($data['media_url'][0]['tmp_name'])) {
            $data['media_url'] = $data['media_url'][0]['name'];
            $this->imageUploader->moveFileFromTmp($data['media_url']);
        } elseif (isset($data['media_url'][0]['name']) && !isset($data['media_url'][0]['tmp_name'])) {
            $data['media_url'] = $data['media_url'][0]['name'];
        } elseif (isset($data['media_url'][0]['media_url'])) {
            $data['media_url'] = $data['media_url'][0]['media_url'];
        } else {
            $data['media_url'] = '';
        }

        return $data['media_url'];
    }
}
