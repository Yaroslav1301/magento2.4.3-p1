<?php


namespace Kozar\CustomWidget\Block\Widget;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;

class LineSeparator extends \Magento\Backend\Block\Template
{

    public function __construct(Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }

    public function prepareElementHtml(AbstractElement $element)
    {

        $element->setData('after_element_html', $this->_getAfterElementHtml());

        return $element;
    }

    /**
     * @return string
     */
    protected function _getAfterElementHtml()
    {
        $label = $this->getData('label')['tab'];

        return "<h1>".$label."</h1><hr>";
    }
}
