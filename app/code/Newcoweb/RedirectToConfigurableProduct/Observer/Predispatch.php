<?php

namespace Newcoweb\RedirectToConfigurableProduct\Observer;

use Magento\Framework\App\Response\Http as ResponseHttp;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\ProductRepository;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Store\Model\StoreManagerInterface;

class Predispatch implements ObserverInterface {

    /** @var ResponseHttp */
    protected $_responseHttp;

    /** @var Configurable */
    protected $_productTypeConfigurable;

    /** @var ProductRepository */
    protected $_productRepository;

    /** @var StoreManagerInterface */
    protected $_storeManager;

    /**
     * @var RedirectInterface
     */
    protected $_redirect;

    /**
     * @var CookieManagerInterface
     */
    protected $_cookieManager;

    /**
     * @var CookieMetadataFactory
     */
    protected $_cookieMetadataFactory;

    /**
     * @param ResponseHttp $responseHttp
     * @param RedirectInterface $redirect
     * @param Configurable $productTypeConfigurable
     * @param ProductRepository $productRepository
     * @param StoreManagerInterface $storeManager
     * @param Http $request
     * @param CookieManagerInterface $cookieManager
     * @param CookieMetadataFactory $cookieMetadataFactory
     */
    public function __construct (
        ResponseHttp $responseHttp,
        RedirectInterface $redirect,
        Configurable $productTypeConfigurable,
        ProductRepository $productRepository,
        StoreManagerInterface $storeManager,
        Http $request,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory
    ) {
        $this->_responseHttp = $responseHttp;
        $this->_redirect = $redirect;
        $this->_productTypeConfigurable = $productTypeConfigurable;
        $this->_productRepository = $productRepository;
        $this->_storeManager = $storeManager;
        $this->_request = $request;
        $this->_cookieManager = $cookieManager;
        $this->_cookieMetadataFactory = $cookieMetadataFactory;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    public function execute(Observer $observer)
    {
        $cookies = $this->checkCookies();
        $lastPage = $this->getLastUrl();
        if ($lastPage || $cookies) {
            $pathInfo = $observer->getEvent()->getRequest()->getPathInfo();

            if (strpos($pathInfo, 'product') === false) {
                return;
            }
            $request = $observer->getEvent()->getRequest();
            $simpleProductId = $request->getParam('id');
            if (!$simpleProductId) {
                return;
            }
            $simpleProduct = $this->_productRepository->getById($simpleProductId, false, $this->_storeManager->getStore()->getId());
            if (!$simpleProduct || $simpleProduct->getTypeId() != Type::TYPE_SIMPLE) {
                return;
            }
            $configProductId = $this->_productTypeConfigurable->getParentIdsByChild($simpleProductId);
            if (isset($configProductId[0])) {
                $configProduct = $this->_productRepository->getById($configProductId[0]);
                if ($configProduct->getStatus() == 2) {
                    return;
                } elseif ($configProduct->getVisibility() == 1) {
                    return;
                }
                $configType = $configProduct->getTypeInstance();
                $attributes = $configType->getConfigurableAttributesAsArray($configProduct);

                $options = [];
                foreach ($attributes as $attribute) {
                    $id = $attribute['attribute_id'];
                    $value = $simpleProduct->getData($attribute['attribute_code']);
                    $options[$id] = $value;
                }

                $options = http_build_query($options);
                $hash = $options ? '#' . $options : '';
                $configProductUrl = $configProduct->getUrlModel()
                    ->getUrl($configProduct);
                $configProductUrl .= $hash;
                if ($this->_cookieManager->getCookie('search')){
                    $metadata = $this->_cookieMetadataFactory->createPublicCookieMetadata();
                    $metadata->setPath('/');
                    $this->_cookieManager->deleteCookie('search',$metadata);
                }
                $this->_responseHttp->setRedirect($configProductUrl, 301);
            }
        }
    }

    /**
     * @return bool
     */
    protected function getLastUrl()
    {
        $lastPage = $this->_redirect->getRefererUrl();
        $array = explode('/', $lastPage);
        $searchPage = explode('?', $array[3]);
        if ($searchPage[0] == 'catalogsearch') {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Get cookies
     */
    protected function checkCookies()
    {
        if ($this->_cookieManager->getCookie('search')) {
           return true;
        } else {
            return false;
        }
    }
}
