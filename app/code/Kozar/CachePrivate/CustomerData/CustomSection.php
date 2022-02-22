<?php


namespace Kozar\CachePrivate\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;

class CustomSection implements SectionSourceInterface
{
    protected $session;
    public function __construct(
        \Magento\Customer\Model\Session $session
    ) {
        $this->session = $session;

    }
    /**
     * {@inheritdoc }
     */
    public function getSectionData()
    {
        /**
         * Put here some data which would be cached
         */
        if ($this->session->isLoggedIn()) {
            return [
                'msg' => 'We are getting data from custom section'
            ];
        }
        else {
            return [
                'msg' => 'You cannot se this section cause you logged out'
            ];
        }

    }
}
