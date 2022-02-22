<?php


namespace Kozar\AddCancelOrder\Ui\Component\Columns;

use \Magento\Framework\View\Element\UiComponent\ContextInterface;

use \Magento\Framework\View\Element\UiComponentFactory;

use \Magento\Ui\Component\Listing\Columns\Column;

use \Magento\Catalog\Api\ProductRepositoryInterface;

use Magento\Framework\UrlInterface;

class Productgrid extends Column
{
    const PRODUCT_URL_PATH_EDIT = 'admin/sales/order/view/order_id/';

    protected $ProductRepositoryInterface;

    /**
     * @var \Magento\Sales\Model\OrderRepository
     */
    protected $_orderRepository;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    public function __construct(
        \Magento\Sales\Model\OrderRepository $orderRepository,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        ProductRepositoryInterface $ProductRepositoryInterface,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {

        $this->urlBuilder = $urlBuilder;

        $this->_orderRepository = $orderRepository;

        parent::__construct($context, $uiComponentFactory, $components, $data);

        $this->ProductRepositoryInterface = $ProductRepositoryInterface;
    }

    public function prepareDataSource(array $dataSource)
    {

        if (isset($dataSource["data"]["items"])) {

            $fieldName = $this->getData("name");

            foreach ($dataSource["data"]["items"] as $key => $item) {

                $id = $item['action'];
                $base_url = $this->urlBuilder->getBaseUrl().self::PRODUCT_URL_PATH_EDIT.$id;

                $id = $item['action'];
                $dataSource["data"]["items"][$key][$fieldName] = '<a href="'.$base_url.'">'.'View Order'.'</a>';
            }

        }

        return $dataSource;
    }
}
