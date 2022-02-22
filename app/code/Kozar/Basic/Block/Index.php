<?php
namespace Kozar\Basic\Block;

use Magento\Framework\App\Http\Context as AuthContext;

class Index extends \Magento\Framework\View\Element\Template
{
    protected $_helper;
    protected $_session;
    private $authContext;

    public function __construct(
        AuthContext $authContext,
        \Magento\Customer\Model\Session $session,
        \Kozar\Basic\Helper\Data $helper,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        $this->authContext = $authContext;
        $this->_session = $session;
        $this->_helper = $helper;
        parent::__construct($context, $data);
    }

    public function getAllowToShowBlock()
    {
        return $this->_helper->needToShowDiscountDate();
    }

    public function showDiscount()
    {
        $isLoggedIn = $this->authContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
        $groupName = $this->authContext->getValue(\Magento\Customer\Model\Context::CONTEXT_GROUP);

        if ($isLoggedIn && $groupName == 4) {
            if ($this->_helper->isCronEnable()) {
                return $this->_helper->showDiscountPrice();
            }
            return "";
        }
    }
}
