<?php

namespace Roadmap\UiComponent\Controller\Adminhtml\Page;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;
use Roadmap\UiComponent\Api\GiftRepositoryInterface;

class InlineEdit extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Roadmap_UiComponent::product_gifts_show';

    private $jsonFactory;

    /**
     * @var GiftRepositoryInterface
     */
    private $giftRepository;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param GiftRepositoryInterface $giftRepository
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        GiftRepositoryInterface $giftRepository
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->giftRepository = $giftRepository;
        parent::__construct($context);
    }

    /**
     * @return Json
     */
    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $giftId) {
                    try {
                        $gift = $this->giftRepository->getById($giftId);
                        $gift->setData(array_merge($gift->getData(), $postItems[$giftId]));
                        $this->giftRepository->save($gift);
                    } catch (NoSuchEntityException $e) {
                        $messages[] = "[Error : {$giftId}]  {$e->getMessage()}";
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error]);
    }
}
