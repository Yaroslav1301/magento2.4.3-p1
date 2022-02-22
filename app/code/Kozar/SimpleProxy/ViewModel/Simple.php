<?php

namespace Kozar\SimpleProxy\ViewModel;

class Simple implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    private $fastLoading;

    private $customerSession;

    public function __construct(
        \Kozar\SimpleProxy\Model\FastLoading $fastLoading,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->fastLoading = $fastLoading;
        $this->customerSession = $customerSession;
    }
    /**
     * @return string
     * This method can make long time queries to the DB ot API
     */
    public function getValue()
    {
        /**
         * if customer is not logged in for rendering block
         * will be used proxy class of SlowLoading
         */
        if ($this->customerSession->isLoggedIn()) {
            $result = $this->fastLoading->getFastValue();
        } else {
            $result = $this->fastLoading->getSlowValue();
        }

        return $result;
    }
}
