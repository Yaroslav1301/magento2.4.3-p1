<?php


namespace Kozar\UiComponent\Model\Adminhtml;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\RequestInterface;
use StudyPool\ServiceContract\Model\ResourceModel\StudyPoolSc\Collection;
use StudyPool\ServiceContract\Model\ResourceModel\StudyPoolSc\CollectionFactory;
use StudyPool\ServiceContract\Api\StudyPoolScRepositoryInterface;
use StudyPool\ServiceContract\Api\Data\StudyPoolScInterface;
use StudyPool\ServiceContract\Api\Data\StudyPoolScInterfaceFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class DataProvider
 * @package Kozar\UiComponent\Model\Adminhtml
 */
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
     * @var Collection
     */
    protected $collection;

    /**
     * @var StudyPoolScRepositoryInterface
     */
    private $repository;

    /**
     * @var StudyPoolScInterfaceFactory
     */
    private $studyPoolScFactory;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * DataProvider constructor.
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param StudyPoolScRepositoryInterface $repository
     * @param StudyPoolScInterfaceFactory $studyPoolScFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        StudyPoolScRepositoryInterface $repository,
        StudyPoolScInterfaceFactory $studyPoolScFactory,
        DataPersistorInterface $dataPersistor,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->request = $request;
        $this->repository = $repository;
        $this->studyPoolScFactory = $studyPoolScFactory;
        $this->dataPersistor = $dataPersistor;
        $this->storeManager = $storeManager;
    }

    /**
     * Get data
     *
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
                if (isset($itemData['media_url']) && !empty($itemData['media_url'])) {
                    $imageName = explode('/', $itemData['media_url']);
                    $itemData['media_url'] = [
                        [
                            'name' => $imageName[3],
                            'url' => $this->storeManager
                                    ->getStore()
                                    ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)
                                . 'logo/image' . $itemData['media_url']
                            ]
                    ];
                } else {
                    $itemData['media_url'] = null;
                }

                $this->loadedData[$item->getEntityId()] = $itemData;
                break;
            }
        }
        return $this->loadedData;
    }

}
