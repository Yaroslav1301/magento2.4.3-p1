<?php

namespace Roadmap\CustomProductLink\Block\Product\ProductList;

use Magento\Catalog\Model\Product\Visibility as ProductVisibility;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Roadmap\CustomProductLink\Model\ProductFactory as CustomLinkProductFactory;
use Magento\Catalog\Model\ResourceModel\Product;
use Magento\Catalog\Block\Product\ImageFactory;

class CustomLink extends Template
{

    /**
     * @var Collection
     */
    protected $itemCollection;

    /**
     * Catalog product visibility
     *
     * @var ProductVisibility
     */
    protected $catalogProductVisibility;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var CustomLinkProductFactory
     */
    protected $customLinkProductModelFac;

    /**
     * @var Product
     */
    protected $resourceModel;

    /**
     * @var ImageFactory
     */
    protected $imageFactory;

    /**
     * @param ProductVisibility $catalogProductVisibility
     * @param ProductRepositoryInterface $productRepository
     * @param CustomLinkProductFactory $customLinkProductModelFac
     * @param Product $resourceModel
     * @param Template\Context $context
     * @param ImageFactory $imageFactory
     * @param array $data
     */
    public function __construct(
        ProductVisibility $catalogProductVisibility,
        ProductRepositoryInterface $productRepository,
        CustomLinkProductFactory $customLinkProductModelFac,
        Product $resourceModel,
        Template\Context $context,
        ImageFactory $imageFactory,
        array $data = []
    ) {
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->productRepository = $productRepository;
        $this->customLinkProductModelFac = $customLinkProductModelFac;
        $this->resourceModel = $resourceModel;
        $this->imageFactory = $imageFactory;
        parent::__construct($context, $data);
    }

    /**
     * Prepare data
     *
     * @return $this
     */
    protected function _prepareData()
    {
        $productId = $this->getRequest()->getParam('id');
        if (isset($productId)) {
            try {
                /**
                 * @var \Roadmap\CustomProductLink\Model\Product $product
                 */
                $product = $this->customLinkProductModelFac->create();
                $this->resourceModel->load($product, $productId);
                if (!$product->isObjectNew()) {
                    $this->itemCollection = $product->getCustomLinkProductCollection()->addAttributeToSelect(
                        [
                            'name', 'url_key', 'small_image', 'base_price',
                            'final_price', 'price', 'price_type', 'required_options'
                        ]
                    )->setPositionOrder()->addStoreFilter();

                    $this->itemCollection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

                    $this->itemCollection->load();

                }

            } catch (NoSuchEntityException $exception) {
                $this->_logger->error($exception->getMessage());
            }

        }
        return $this;
    }

    /**
     * Before to html handler
     *
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->_prepareData();
        return parent::_beforeToHtml();
    }

    /**
     * Get collection items
     *
     * @return Collection
     */
    public function getItems()
    {
        if ($this->itemCollection === null) {
            $this->_prepareData();
        }
        return $this->itemCollection;
    }

    /**
     * @param $product
     * @param $imageId
     * @param $attributes
     * @return \Magento\Catalog\Block\Product\Image
     */
    public function getImage($product, $imageId, $attributes = [])
    {
        return $this->imageFactory->create($product, $imageId, $attributes);
    }

    /**
     * Return HTML block with tier price
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param string $priceType
     * @param string $renderZone
     * @param array $arguments
     * @return string
     */
    public function getProductPriceHtml(
        \Magento\Catalog\Model\Product $product,
        $priceType,
        $renderZone = \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST,
        array $arguments = []
    ) {
        if (!isset($arguments['zone'])) {
            $arguments['zone'] = $renderZone;
        }

        /** @var \Magento\Framework\Pricing\Render $priceRender */
        $priceRender = $this->getLayout()->getBlock('product.price.render.default');
        $price = '';

        if ($priceRender) {
            $price = $priceRender->render($priceType, $product, $arguments);
        }
        return $price;
    }

    /**
     * Return HTML block with price
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getProductPrice(\Magento\Catalog\Model\Product $product)
    {
        return $this->getProductPriceHtml(
            $product,
            \Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE,
            \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST
        );
    }
}
