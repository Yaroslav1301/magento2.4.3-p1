<?php
namespace Kozar\SearchCriteria\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;

class Index implements HttpGetActionInterface
{
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var \Magento\Framework\Api\FilterFactory
     */
    private $filterFactory;

    /**
     * @var \Magento\Framework\Api\Search\FilterGroupFactory
     */
    private $filterGroupFactory;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaInterface
     */
    private $searchCriteria;

    protected $pageFactory;

    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Api\FilterFactory $filterFactory,
        \Magento\Framework\Api\Search\FilterGroupFactory $filterGroupFactory,
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->productRepository = $productRepository;
        $this->filterFactory = $filterFactory;
        $this->filterGroupFactory = $filterGroupFactory;
        $this->searchCriteria = $searchCriteria;
        $this->pageFactory = $pageFactory;
    }

    public function execute()
    {
        $filter1 = $this->filterFactory->create();
        $filter1->setField('sku');
        $filter1->setValue('MJ%');
        $filter1->setConditionType('like');

        $filter2 = $this->filterFactory->create();
        $filter2->setField('sku');
        $filter2->setValue('%02');
        $filter2->setConditionType('like');

        $filterGroup1 = $this->filterGroupFactory->create();
        $filterGroup1->setFilters([$filter1, $filter2]);

        $filter3 = $this->filterFactory->create();
        $filter3->setField('price');
        $filter3->setValue('100');
        $filter3->setConditionType('neq');

        $filterGroup2 = $this->filterGroupFactory->create();
        $filterGroup2->setFilters([$filter3]);

        $searchCriteria = $this->searchCriteria->setFilterGroups([$filterGroup1, $filterGroup2]);

        /**
         * Here is returning data from repository filterd by searchCriteria
         */
        $result = $this->productRepository->getList($searchCriteria);


        return $this->pageFactory->create();
    }
}
