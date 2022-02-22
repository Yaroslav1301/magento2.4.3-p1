<?php


namespace Kozar\CacheTest\Plugin;

use Magento\Framework\App\Http\Context;

class CustomerAgeContextPlugin
{
    /**
     * @var \Magento\Customer\Model\SessionFactory
     */
    public $customerSessionFactory;

    /**
     * @param \Magento\Customer\Model\SessionFactory $customerSessionFactory
     */
    public function __construct(
        \Magento\Customer\Model\SessionFactory $customerSessionFactory
    ) {
        $this->customerSessionFactory = $customerSessionFactory;
    }

    /**
     * @param \Magento\Framework\App\Http\Context $subject
     */
    public function beforeGetVaryString(\Magento\Framework\App\Http\Context $subject)
    {
        $ageContext = 0;
        $defaultAgeContext = 0;
        if (!empty($subject->getData())) {
            if ($subject->getValue('customer_logged_in')) {
                $customerSession = $this->customerSessionFactory->create();
                $customerData = $customerSession->getCustomerData();
                if (isset($customerData)) {
                    $age = $customerData->getDob();
                    if (isset($age)) {
                        $ageTimeStamp = strtotime($age);
                        $currentTimeStamp = time();
                        $age = ($currentTimeStamp - $ageTimeStamp)/(86400 * 365);
                        $ageContext = $age >= 18 ? 1 : $defaultAgeContext;
                    }
                }

            }
        }
        $subject->setValue('CONTEXT_AGE', $ageContext, $defaultAgeContext);
        return [];
    }
}
