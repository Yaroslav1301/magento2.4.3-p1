<?php

namespace Newcoweb\RedirectToConfigurableProduct\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\PageCache\Identifier;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\RedirectInterface;

class IndetifierPlugin
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $context;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    private $serializer;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var RedirectInterface
     */
    protected $redirect;

    public function __construct(
        RequestInterface $request,
        \Magento\Framework\App\Http\Context $context,
        \Magento\Framework\Serialize\SerializerInterface $serializer,
        ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        RedirectInterface $redirect
    ) {
        $this->request = $request;
        $this->context = $context;
        $this->serializer = $serializer;
        $this->productRepository = $productRepository;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->redirect = $redirect;
    }

    /**
     * @param Identifier $subject
     * @param string $result
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function afterGetValue(Identifier $subject, string $result): string
    {
        $requestedPath = $this->request->getPathInfo();
        $additionalValue = null;
        if (str_contains($requestedPath, '.html')) {
            $urlKey = \Safe\preg_replace('#\.html#', '', $requestedPath);
            $urlKey = \Safe\preg_replace('#/#', '', $urlKey);
            $productCollection = $this->productCollectionFactory->create();
            /**
             * @var \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection
             */
            $productCollection->addAttributeToFilter('url_key', $urlKey);
            $product = $productCollection->getFirstItem();
            $productId = $product->getData('entity_id');
        }

        if (str_contains($requestedPath, 'catalog/product/view')) {
            $productId = $this->request->getParam('id');
        }
        if (isset($productId)) {
            $product = $this->productRepository->getById($productId);
            if ($product->getTypeId() == 'simple') {
                $additionalValue = 'simple';
            }
            if ($this->getLastUrl()) {
                $additionalValue = 'search';
            }
        }
        if (isset($additionalValue)) {
            return $this->getValue($additionalValue);
        }

        return $result;
    }

    protected function getValue($additionalValue)
    {
        $data = [
            $this->request->isSecure(),
            $this->request->getUriString(),
            $this->request->get(\Magento\Framework\App\Response\Http::COOKIE_VARY_STRING)
                ?: $this->context->getVaryString(),
            $additionalValue
        ];

        return sha1($this->serializer->serialize($data));
    }


    protected function getLastUrl()
    {
        $lastPage = $this->redirect->getRefererUrl();
        $array = explode('/', $lastPage);
        $searchPage = explode('?', $array[3]);
        if ($searchPage[0] == 'catalogsearch') {
            return true;
        } else {
            return false;
        }
    }
}
