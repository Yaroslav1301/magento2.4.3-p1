<?php

namespace Magefox\Example\Block;

use Magento\Framework\View\Element\Template;

class Example extends Template {

    /**
     * @var \Magefox\Example\Helper\Data
     */
    protected $_foxyHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magefox\Example\Helper\Data $foxyHelper
     * @param array $data
     */

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magefox\Example\Helper\Data $foxyHelper,
        array $data = [])
    {
        $this->_foxyHelper = $foxyHelper;
        parent::__construct($context, $data);
    }

    public function getHello() {
        return $this->_foxyHelper->upperString("Hello world, it's Magento Fox");
    }
}
