<?php

namespace Avatar\Avatar\Block;

class Avatar extends \Magento\Framework\View\Element\Template
{
    /** @var \Magento\Framework\UrlInterface  */
    protected $urlBuilder;

    /** @var \Magento\Customer\Model\Session  */
    protected $customerSession;

    /** @var \Magento\Store\Model\StoreManagerInterface  */
    protected $storeManager;

    /** @var \Magento\Customer\Model\Customer  */
    protected $customerModel;

    /** @var \Avatar\Avatar\Helper\Helper  */
    protected $helper;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Customer\Model\SessionFactory $customerSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\Customer $customerModel
     * @param \Avatar\Avatar\Helper\Helper $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Customer $customerModel,
        \Avatar\Avatar\Helper\Helper $helper,
        array $data = []
    ){
        $this->urlBuilder = $urlBuilder;
        $this->customerSession = $customerSession->create();
        $this->storeManager = $storeManager;
        $this->customerModel = $customerModel;
        $this->helper = $helper;

        parent::__construct($context, $data);
    }

    /** @return string */
    public function getBaseUrl(){
        return $this->storeManager->getStore()->getBaseUrl();
    }

    /** @return string */
    public function getMediaUrl(){
        return $this->getBaseUrl() . 'media/';
    }

    /** @return string */
    public function getCustomerImageUrl($filePath){
        return $this->getMediaUrl() . 'customer/' . $filePath;
    }

    /** @return string */
    public function getFileUrl(){
        $customerData = $this->customerModel->load($this->customerSession->getId());
        $url = $customerData->getData('profile_picture');
        if (!empty($url)) {
            return $this->getCustomerImageUrl($url);
        }
        return false;
    }
    /** @return integer */
    public function getImageHeight(){
        return $this->helper->getImageHeight();
    }
}