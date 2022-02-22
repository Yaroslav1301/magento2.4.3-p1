<?php


namespace Kozar\AddDeclaretive\Controller\Index;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder as ApiSearchCriteriaBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Kozar\AddDeclaretive\Model\ResourceModel\Student\CollectionFactory;
use Kozar\AddDeclaretive\Model\StudentFactory;
use Kozar\AddDeclaretive\Model\StudentRepository;

class Index extends Action
{
    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @var StudentFactory
     */
    private $student;

    /**
     * @var CollectionFactory
     */
    private $studentCollection;

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var StudentRepository
     */
    private $studentRepository;
    /**
     * @var \Kozar\AddDeclaretive\Model\ResourceModel\Student
     */
    private $studentResourceModel;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param StudentFactory $student
     * @param CollectionFactory $studentCollection
     * @param StudentRepository $studentRepository
     * @param FilterBuilder $filterBuilder
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Kozar\AddDeclaretive\Model\ResourceModel\Student $studentResourceModel
     */
    public function __construct(
        Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Kozar\AddDeclaretive\Model\StudentFactory $student,
        \Kozar\AddDeclaretive\Model\ResourceModel\Student\CollectionFactory $studentCollection,
        \Kozar\AddDeclaretive\Model\StudentRepository $studentRepository,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Framework\Api\Search\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Kozar\AddDeclaretive\Model\ResourceModel\Student $studentResourceModel
    ) {
        $this->pageFactory = $pageFactory;
        $this->student = $student;
        $this->studentCollection = $studentCollection;
        $this->studentRepository = $studentRepository;
        $this->filterBuilder = $filterBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->studentResourceModel = $studentResourceModel;
        parent::__construct($context);
    }

    public function execute()
    {
        /**
         * Get entity student from repository by ID = 1
         */
        $student = $this->studentRepository->getById(1);

        /**
         * Get student collection
         */
        $studentCollection = $this->studentCollection->create();

        /**
         * Create student using Model and save to table(repository) new_student1
         */
        $new_student1 = $this->student->create()
            ->addData(['name' => 'Vira', 'age' => '30', 'is_graduated' => '1']);
        $this->studentRepository->save($new_student1);

        /**
         * Adding to table(repository) new student2 by using my method
         */
        $new_student2 = $this->student->create()->setName('Viktor')->setAge(20)->setGraduated(1);
        /**
         * Saving using resource model
         */
        $this->studentResourceModel->save($new_student2);

        /**
         * filter collection of students
         */
        $filter = $this->filterBuilder->setField('age')->setConditionType('like')->setValue('25')->create();
        $this->searchCriteriaBuilder->addFilter($filter);
        $searchCriteria = $this->searchCriteriaBuilder->addSortOrder('entity_id', SortOrder::SORT_DESC)->create();
        $searchResult = $this->studentRepository->getList($searchCriteria);

        /**
         * Getting filtred items
         */
        $items = $searchResult->getItems();

        /**
         * showing all data from table in controller url
         */
        $collection  = $this->studentCollection->create()->getItems();
        echo "<table border='1'>";
        echo "<tr><td>Id</td><td>Name</td><td>Age</td><td>Created At</td><td>Updated At</td></tr>";
        foreach ($collection as $value) {
            echo "<tr><td>". $value->getId().
                "</td><td>". $value->getName() .
                "</td><td>" . $value->getAge() .
                "</td><td>" . $value->getCreatedAt() .
                "</td><td>" . $value->getUpdatedAt() .
                "</td></tr>";
        }
        echo "</table>";
    }
}
