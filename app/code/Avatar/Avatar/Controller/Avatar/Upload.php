<?php
namespace Avatar\Avatar\Controller\Avatar;

class Upload extends \Magento\Framework\App\Action\Action {

    /** @var \Magento\Framework\Controller\Result\JsonFactory  */
    protected $resultJsonFactory;

    /** @var \Magento\Framework\Message\ManagerInterface  */
    protected $messageManager;

    /** @var \Magento\Customer\Model\Session  */
    protected $customerSession;

    /** @var \Avatar\Avatar\Helper\Helper  */
    protected $helper;

    /**
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Avatar\Avatar\Helper\Helper $helper
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Avatar\Avatar\Helper\Helper $helper
    ) {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->messageManager = $messageManager;
        $this->helper = $helper;
    }

    /**
     * Controller for AJAX
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute() {
        $result = $this->resultJsonFactory->create();
        $file = get_object_vars($this->getRequest()->getFiles());
        if (!empty($file['profile_picture']['name']) && !empty($file['profile_picture']['tmp_name'])) {
            if($this->helper->validateFileFormat($file['profile_picture']['type'])) {
                if($this->helper->validateFileSize($file['profile_picture']['size'])) {
                    if ($this->customerSession->isLoggedIn()) {
                        $this->messageManager->addSuccessMessage('Image is correct!');
                        return $result->setData(['success' => 'Image is correct!']);
                    }
                }
                else{
                    $this->messageManager->addErrorMessage('Too big a file!');
                    return $result->setData(['error' => 'Too big a file!']);
                }
            }
            else{
                $this->messageManager->addErrorMessage('File format is not supported!');
                return $result->setData(['error' => 'File format is not supported!']);
            }
        }
    }
}