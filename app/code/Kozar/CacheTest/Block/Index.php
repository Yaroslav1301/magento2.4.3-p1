<?php
namespace Kozar\CacheTest\Block;

use Magento\Framework\View\Element\Template;

class Index extends \Magento\Framework\View\Element\Template
{
    private $authContext;
    public function __construct(
        \Magento\Framework\App\Http\Context $authContext,
        Template\Context $context,
        array $data = []
    ) {
        $this->authContext = $authContext;
        parent::__construct($context, $data);
    }

    public function isAdult()
    {
        $this->authContext->getVaryString();
//        $isLoggedIn = $this->authContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
        $isAdult = $this->authContext->getValue('CONTEXT_AGE');
        if ($isAdult) {
            return true;
        } else {
            return false;
        }
    }
}
