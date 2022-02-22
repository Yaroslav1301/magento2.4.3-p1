<?php


namespace Kozar\CustomWidget\Block\Widget;

use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Block\Product\Widget\Html\Pager;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\CatalogWidget\Model\Rule;
use Magento\Framework\App\Http\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\RendererList;
use Magento\Framework\View\LayoutFactory;
use Magento\Rule\Model\Condition\Sql\Builder;
use Magento\Widget\Block\BlockInterface;
use Magento\Framework\Url\EncoderInterface;
use Magento\Widget\Helper\Conditions;

/**
 * Catalog Products List widget block
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 */
class Posts extends AbstractProduct implements BlockInterface, IdentityInterface
{
    /**
     * Default value for products count that will be shown
     */
    const DEFAULT_PRODUCTS_COUNT = 10;

    /**
     * Name of request parameter for page number value
     *
     * @deprecated
     */
    const PAGE_VAR_NAME = 'np';

    /**
     * Default value for products per page
     */
    const DEFAULT_PRODUCTS_PER_PAGE = 5;

    /**
     * Default value whether show pager or not
     */
    const DEFAULT_SHOW_PAGER = false;

    /**
     * Instance of pager block
     *
     * @var Pager
     */
    protected $pager;

    /**
     * @var Context
     */
    protected $httpContext;

    /**
     * Catalog product visibility
     *
     * @var Visibility
     */
    protected $catalogProductVisibility;

    /**
     * Product collection factory
     *
     * @var CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var Builder
     */
    protected $sqlBuilder;

    /**
     * @var Rule
     */
    protected $rule;

    /**
     * @var Conditions
     */
    protected $conditionsHelper;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * Json Serializer Instance
     *
     * @var Json
     */
    private $json;

    /**
     * @var LayoutFactory
     */
    private $layoutFactory;

    /**
     * @var EncoderInterface|null
     */
    private $urlEncoder;

    /**
     * @var RendererList
     */
    private $rendererListBlock;

    public $numberOfTab;

    /**
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param CollectionFactory $productCollectionFactory
     * @param Visibility $catalogProductVisibility
     * @param Context $httpContext
     * @param Builder $sqlBuilder
     * @param Rule $rule
     * @param Conditions $conditionsHelper
     * @param array $data
     * @param Json|null $json
     * @param LayoutFactory|null $layoutFactory
     * @param EncoderInterface|null $urlEncoder
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        CollectionFactory $productCollectionFactory,
        Visibility $catalogProductVisibility,
        Context $httpContext,
        Builder $sqlBuilder,
        Rule $rule,
        Conditions $conditionsHelper,
        array $data = [],
        Json $json = null,
        LayoutFactory $layoutFactory = null,
        EncoderInterface $urlEncoder = null
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->httpContext = $httpContext;
        $this->sqlBuilder = $sqlBuilder;
        $this->rule = $rule;
        $this->conditionsHelper = $conditionsHelper;
        $this->json = $json ?: ObjectManager::getInstance()->get(Json::class);
        $this->layoutFactory = $layoutFactory ?: ObjectManager::getInstance()->get(LayoutFactory::class);
        $this->urlEncoder = $urlEncoder ?: ObjectManager::getInstance()->get(EncoderInterface::class);
        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * Internal constructor, that is called from real constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->addColumnCountLayoutDepend('empty', 6)
            ->addColumnCountLayoutDepend('1column', 5)
            ->addColumnCountLayoutDepend('2columns-left', 4)
            ->addColumnCountLayoutDepend('2columns-right', 4)
            ->addColumnCountLayoutDepend('3columns', 3);

        $this->addData([
            'cache_lifetime' => 86400,
            'cache_tags' => [\Magento\Catalog\Model\Product::CACHE_TAG,
            ], ]);
    }

    /**
     * Get key pieces for caching block content
     *
     * @return array
     * @SuppressWarnings(PHPMD.RequestAwareBlockMethod)
     */
    public function getCacheKeyInfo()
    {
        $conditions = $this->getData('conditions')
            ? $this->getData('conditions')
            : $this->getData('conditions_encoded');

        return [
            'CATALOG_PRODUCTS_LIST_WIDGET',
            $this->getPriceCurrency()->getCurrency()->getCode(),
            $this->_storeManager->getStore()->getId(),
            $this->_design->getDesignTheme()->getId(),
            $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_GROUP),
            (int) $this->getRequest()->getParam($this->getData('page_var_name'), 1),
            $this->getProductsPerPage(),
            $this->getProductsCount(),
            $conditions,
            $this->json->serialize($this->getRequest()->getParams()),
            $this->getTemplate(),
            $this->getTitle()
        ];
    }

    public function setNumberOfTab($number)
    {
        $this->numberOfTab = $number;
    }

    public function getNumberOfTab()
    {
        if (isset($this->numberOfTab)) {
            return $this->numberOfTab;
        }
        return "";
    }
    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getProductPriceHtml(
        \Magento\Catalog\Model\Product $product,
        $priceType = null,
        $renderZone = \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST,
        array $arguments = []
    ) {
        if (!isset($arguments['zone'])) {
            $arguments['zone'] = $renderZone;
        }
        $arguments['price_id'] = isset($arguments['price_id'])
            ? $arguments['price_id']
            : 'old-price-' . $product->getId() . '-' . $priceType;
        $arguments['include_container'] = isset($arguments['include_container'])
            ? $arguments['include_container']
            : true;
        $arguments['display_minimal_price'] = isset($arguments['display_minimal_price'])
            ? $arguments['display_minimal_price']
            : true;

        /** @var \Magento\Framework\Pricing\Render $priceRender */
        $priceRender = $this->getLayout()->getBlock('product.price.render.default');
        if (!$priceRender) {
            $priceRender = $this->getLayout()->createBlock(
                \Magento\Framework\Pricing\Render::class,
                'product.price.render.default',
                ['data' => ['price_render_handle' => 'catalog_product_prices']]
            );
        }

        $price = $priceRender->render(
            \Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE,
            $product,
            $arguments
        );

        return $price;
    }

    /**
     * @inheritdoc
     */
    protected function getDetailsRendererList()
    {
        if (empty($this->rendererListBlock)) {
            /** @var $layout \Magento\Framework\View\LayoutInterface */
            $layout = $this->layoutFactory->create(['cacheable' => false]);
            $layout->getUpdate()->addHandle('catalog_widget_product_list')->load();
            $layout->generateXml();
            $layout->generateElements();

            $this->rendererListBlock = $layout->getBlock('category.product.type.widget.details.renderers');
        }
        return $this->rendererListBlock;
    }

    /**
     * Get post parameters.
     *
     * @param Product $product
     * @return array
     */
    public function getAddToCartPostParams(Product $product)
    {
        $url = $this->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                ActionInterface::PARAM_NAME_URL_ENCODED => $this->urlEncoder->encode($url),
            ]
        ];
    }

    public function initilizeCollection()
    {
        $this->setProductCollection($this->createCollection());
    }

    /**
     * Prepare and return product collection
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     * @SuppressWarnings(PHPMD.RequestAwareBlockMethod)
     */
    public function createCollection()
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->productCollectionFactory->create();

        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->addAttributeToSort('created_at', 'desc')
            ->setPageSize($this->getPageSize())
            ->setCurPage($this->getRequest()->getParam($this->getData('page_var_name'), 1));

        $conditions = $this->getConditions();
        $conditions->collectValidatedAttributes($collection);
        $this->sqlBuilder->attachConditionToCollection($collection, $conditions);

        return $collection;
    }

    /**
     * Get conditions
     *
     * @return \Magento\Rule\Model\Condition\Combine
     */
    protected function getConditions()
    {
        $code = $this->getNumberOfTab();
        if ($code == "_tab1") {
            $conditions = $this->getData('conditions_encoded')
                ? $this->getData('conditions_encoded')
                : $this->getData('conditions');
        } elseif ($code == "_tab2") {
            $conditions = $this->getData('Conditions2Tab');
        } elseif ($code == "_tab3") {
            $conditions = $this->getData('Conditions3Tab');
        }

        if ($conditions) {
            $conditions = $this->conditionsHelper->decode($conditions);
        }

        foreach ($conditions as $key => $condition) {
            if (!empty($condition['attribute'])
                && in_array($condition['attribute'], ['special_from_date', 'special_to_date'])
            ) {
                $conditions[$key]['value'] = date('Y-m-d H:i:s', strtotime($condition['value']));
            }
        }

        $this->rule->loadPost(['conditions' => $conditions]);
        return $this->rule->getConditions();
    }

    /**
     * Retrieve how many products should be displayed
     *
     * @return int
     */
    public function getProductsCount()
    {
        $code = 'products_count' . $this->getNumberOfTab();
        if ($this->hasData($code)) {
            return $this->getData($code);
        }

        if (null === $this->getData($code)) {
            $this->setData($code, self::DEFAULT_PRODUCTS_COUNT);
        }

        return $this->getData($code);
    }

    /**
     * Retrieve how many products should be displayed
     *
     * @return int
     */
    public function getProductsPerPage()
    {
        $code = 'products_per_page' . $this->getNumberOfTab();
        if (!$this->hasData($code)) {
            $this->setData($code, self::DEFAULT_PRODUCTS_PER_PAGE);
        }
        return $this->getData($code);
    }

    /**
     * Return flag whether pager need to be shown or not
     *
     * @return bool
     */
    public function showPager()
    {
        $code = 'show_pager'.$this->getNumberOfTab();
        if (!$this->hasData($code)) {
            $this->setData($code, self::DEFAULT_SHOW_PAGER);
        }
        return (bool)$this->getData($code);
    }

    /**
     * Retrieve how many products should be displayed on page
     *
     * @return int
     */
    protected function getPageSize()
    {
        return $this->showPager() ? $this->getProductsPerPage() : $this->getProductsCount();
    }

    /**
     * Render pagination HTML
     *
     * @return string
     */
    public function getPagerHtml()
    {
        if ($this->showPager() && $this->getProductCollection()->getSize() > $this->getProductsPerPage()) {
            if ($this->getNumberOfTab() == "_tab1") {
                $this->setData('page_var_name', 'tab1');
            } elseif ($this->getNumberOfTab() == "_tab2") {
                $this->setData('page_var_name', 'tab2');
                $this->pager = null;
            } elseif ($this->getNumberOfTab() == '_tab3') {
                $this->setData('page_var_name', 'tab3');
                $this->pager = null;
            }
            if (!$this->pager) {
                $this->pager = $this->getLayout()->createBlock(
                    Pager::class,
                    $this->getWidgetPagerBlockName()
                );

                $this->pager->setUseContainer(true)
                    ->setShowAmounts(true)
                    ->setShowPerPage(false)
                    ->setPageVarName($this->getData('page_var_name'))
                    ->setLimit($this->getProductsPerPage())
                    ->setTotalLimit($this->getProductsCount())
                    ->setCollection($this->getProductCollection());
            }
            if ($this->pager instanceof \Magento\Framework\View\Element\AbstractBlock) {
                return $this->pager->toHtml();
            }
        }
        return '';
    }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        $identities = [];
        if ($this->getProductCollection()) {
            foreach ($this->getProductCollection() as $product) {
                if ($product instanceof IdentityInterface) {
                    $identities = array_merge($identities, $product->getIdentities());
                }
            }
        }

        return $identities ?: [\Magento\Catalog\Model\Product::CACHE_TAG];
    }

    /**
     * Get value of widgets' title parameter
     *
     * @return mixed|string
     */
    public function getTitle()
    {
        return $this->getData('title'.$this->getNumberOfTab());
    }

    /**
     * Get currency of product
     *
     * @return PriceCurrencyInterface
     * @deprecated 100.2.0
     */
    private function getPriceCurrency()
    {
        if ($this->priceCurrency === null) {
            $this->priceCurrency = \Magento\Framework\App\ObjectManager::getInstance()
                ->get(PriceCurrencyInterface::class);
        }
        return $this->priceCurrency;
    }

    /**
     * @inheritdoc
     */
    public function getAddToCartUrl($product, $additional = [])
    {
        $requestingPageUrl = $this->getRequest()->getParam('requesting_page_url');

        if (!empty($requestingPageUrl)) {
            $additional['useUencPlaceholder'] = true;
            $url = parent::getAddToCartUrl($product, $additional);
            return str_replace('%25uenc%25', $this->urlEncoder->encode($requestingPageUrl), $url);
        }

        return parent::getAddToCartUrl($product, $additional);
    }

    /**
     * Get widget block name
     *
     * @return string
     */
    private function getWidgetPagerBlockName()
    {
        $pageName = $this->getData('page_var_name');
        $pagerBlockName = 'widget.products.list.pager';

        if (!$pageName) {
            return $pagerBlockName;
        }

        return $pagerBlockName . '.' . $pageName;
    }
}
