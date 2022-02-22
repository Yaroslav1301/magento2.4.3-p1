<?php


namespace Kozar\CachePrivate\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Framework\DataObject;

class TestSection extends DataObject implements SectionSourceInterface
{
    /**
     * {@inheritdoc }
     */
    public function getSectionData()
    {
        /**
         * Put here some data which would be cached
         */

            return [
                'test' => 'We are getting data from test section'
            ];

    }
}
