<?php

namespace Avatar\Avatar\Helper;

use Magento\Store\Model\ScopeInterface;

class Helper extends \Magento\Framework\App\Helper\AbstractHelper
{
    /** @var string[]  */
    const ALLOWED_FORMATS = ['image/png', 'image/jpeg', 'image/pjpeg'];

    /** @var int  */
    const SIZE = 512000;

    /** @var string  */
    const FIELD = 'change_avatar_size';

    /** @var string  */
    const GROUP = 'account_information/';

    /** @var string  */
    const SECTION = 'customer/';

    /** @var \Magento\Customer\Model\CustomerFactory  */
    protected $customerFactory;

    /** @var \Magento\Customer\Model\Session  */
    protected $customerSession;

    /** @var \Magento\Framework\Registry */
    protected $registry;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(\Magento\Framework\App\Helper\Context $context,
                                \Magento\Framework\Registry $registry,
                                \Magento\Customer\Model\CustomerFactory $customerFactory,
                                \Magento\Customer\Model\Session $customerSession)
    {
        $this->registry = $registry;
        $this->customerSession = $customerSession;
        $this->customerFactory = $customerFactory;
        parent::__construct($context);
    }

    /**
     * @param string $field
     * @param null $storeId
     * @return string
     */
    protected function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field, ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    /**
     * @param string
     * @return boolean
     */
    public function validateFileFormat($format){
        foreach (self::ALLOWED_FORMATS as $FORMAT){
            if($format == $FORMAT){
                return true;
            }
        }
        return false;
    }

    /**
     * @param string
     * @return boolean
     */
    public function validateFileSize($size){
        if(self::SIZE >= $size){
            return true;
        }
        return false;
    }

    /**
     * @return integer
     */
    public function getImageHeight(){
        $value = $this->getConfigValue(self::SECTION .self::GROUP. self::FIELD);
        if($value == 1){
            return 25;
        }
        return 40;
    }

    /**
     * @return integer
     */
    public function getCustomerId(){
        $customerId = $this->customerSession->getCustomer()->getId();
        return $customerId;
    }
}