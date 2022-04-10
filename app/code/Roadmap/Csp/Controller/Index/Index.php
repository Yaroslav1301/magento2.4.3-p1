<?php

namespace Roadmap\Csp\Controller\Index;

use Magento\Csp\Api\CspAwareActionInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Csp\Model\Policy\FetchPolicy;

class Index implements HttpGetActionInterface, CspAwareActionInterface
{
    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param PageFactory $pageFactory
     * @param RequestInterface $request
     */
    public function __construct(
        PageFactory $pageFactory,
        RequestInterface $request
    ) {
        $this->pageFactory = $pageFactory;
        $this->request = $request;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        return $this->pageFactory->create();
    }

    /**
     * @param array $appliedPolicies
     * @return array|\Magento\Csp\Api\Data\PolicyInterface[]
     */
    public function modifyCsp(array $appliedPolicies): array
    {
        $appliedPolicies[] = new FetchPolicy(
            'form-action',
            false,
            ['https://my-site.com'],
            ['https']
        );

        return $appliedPolicies;
    }
}
