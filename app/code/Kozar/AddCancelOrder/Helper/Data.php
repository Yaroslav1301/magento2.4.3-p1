<?php

namespace Kozar\AddCancelOrder\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{

    const XML_PATH = 'canceled_order/';

    protected $customerSession;
    protected $groupRepository;
    public function __construct(
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
        \Magento\Customer\Model\Session $customerSession,
        Context $context
    ) {
        $this->customerSession = $customerSession;
        $this->groupRepository = $groupRepository;
        parent::__construct($context);
    }

    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getGeneralConfig($code, $storeId = null)
    {

        return $this->getConfigValue(self::XML_PATH .'general/'. $code, $storeId);
    }

    public function checkStatus($currentStatus): bool
    {
        $arrStatus = explode(",", $this->getGeneralConfig("allow_status"));

        if (in_array($currentStatus, $arrStatus)) {
            return true;
        }
        return false;
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function checkGroup(): bool
    {
        $currentGroup = $this->getGroupName();
        $arrGroup = explode(",", $this->getGeneralConfig("allow_group"));

        if (in_array($currentGroup, $arrGroup)) {
            return true;
        }
        return false;
    }

    public function isEnableCancelOrder(): bool
    {
        if ($this->getGeneralConfig("enable")) {
            return true;
        }
        return false;
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getGroupName(): string
    {

        if ($this->customerSession->isLoggedIn()) {

            $customerGroup = $this->customerSession->getCustomer()->getGroupId();

            return $this->groupRepository->getById($customerGroup)->getCode();
        }
    }
}
