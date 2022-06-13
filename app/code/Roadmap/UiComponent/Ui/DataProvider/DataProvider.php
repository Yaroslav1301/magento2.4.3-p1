<?php

namespace Roadmap\UiComponent\Ui\DataProvider;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\RequestInterface;
use Roadmap\UiComponent\Model\ResourceModel\Gift\Collection;
use Roadmap\UiComponent\Model\ResourceModel\Gift\CollectionFactory;
use Roadmap\UiComponent\Api\Data\GiftInterface;
use Roadmap\UiComponent\Api\Data\GiftInterfaceFactory;
use Roadmap\UiComponent\Model\ResourceModel\Gift;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Store\Model\StoreManagerInterface;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var array
     */
    private $loadedData;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var CollectionFactory
     */
    private $giftCollectionFactory;

    /**
     * @var GiftInterfaceFactory
     */
    private $giftFactory;

    /**
     * @var Gift
     */
    private $giftResourceModel;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @param RequestInterface $request
     * @param CollectionFactory $giftCollectionFactory
     * @param GiftInterfaceFactory $giftInterfaceFactory
     * @param Gift $giftResourceModel
     * @param DataPersistorInterface $dataPersistor
     * @param StoreManagerInterface $storeManager
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        RequestInterface $request,
        CollectionFactory $giftCollectionFactory,
        GiftInterfaceFactory $giftInterfaceFactory,
        Gift $giftResourceModel,
        DataPersistorInterface $dataPersistor,
        StoreManagerInterface $storeManager,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->request = $request;
        $this->collection = $giftCollectionFactory->create();
        $this->giftFactory = $giftInterfaceFactory;
        $this->giftResourceModel = $giftResourceModel;
        $this->dataPersistor = $dataPersistor;
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (!$this->loadedData) {
            $storeId = (int) $this->request->getParam('store');

            $items = $this->collection->getItems();
            foreach ($items as $item) {
                $item->setStoreId($storeId);
                $itemData = $item->getData();

                try {
                    $mediaUrl = $this->storeManager->getStore()
                        ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
                } catch (NoSuchEntityException $exception) {
                    $mediaUrl = 'http://magento.loc/media/'; // TODO for proper execution of issue
                }

                if (isset($itemData['media_url'])) {
                    $name = $itemData['media_url'];
                    unset($itemData['media_url']);
                    $itemData['media_url'][0] = [
                        'media_url' => $name,
                        'url' => $mediaUrl . 'roadmap/gift/' . $name
                    ];
                }
                $this->loadedData[$item->getGiftId()] = $itemData;
                break;
            }
        }
        return $this->loadedData;
    }
}
