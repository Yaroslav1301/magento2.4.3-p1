<?php

namespace Kozar\PriceCustomization\Pricing\Price;

use Magento\Framework\Pricing\Price\BasePriceProviderInterface;
use Magento\Framework\Pricing\Price\AbstractPrice;

class CustomModelPrice extends AbstractPrice implements BasePriceProviderInterface
{
    /**
     * Price type
     */
    const PRICE_CODE = 'custom_price';

    /**
     * Get price value
     *
     * @return float
     */
    public function getValue()
    {
        return '1234';
    }
}
