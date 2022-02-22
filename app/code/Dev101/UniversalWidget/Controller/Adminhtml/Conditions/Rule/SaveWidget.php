<?php

namespace Dev101\UniversalWidget\Controller\Adminhtml\Conditions\Rule;

class SaveWidget extends \Dev101\UniversalWidget\Controller\Adminhtml\Conditions\Rule
{
    /**
     * @var \Magento\Widget\Helper\Conditions
     */
    private $conditionsHelper;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter
     * @param \Dev101\UniversalWidget\Model\RuleFactory $ruleFactory
     * @param \Magento\Widget\Helper\Conditions $conditionsHelper
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter,
        \Magento\CatalogWidget\Model\RuleFactory $ruleFactory,
        \Magento\Widget\Helper\Conditions $conditionsHelper,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->conditionsHelper = $conditionsHelper;
        parent::__construct($context, $coreRegistry, $fileFactory, $dateFilter, $ruleFactory, $logger);
    }

    /**
     * Rule save action
     *
     * @return void
     */
    public function execute()
    {
        if (!$this->getRequest()->getPostValue()) {
            $this->_redirect('universal/*/');
        }

        try {
            $serializedConditionArray =
                $this->conditionsHelper->decode($this->getRequest()->getParam('serializedArray'));
            $templateArray = [];
            foreach ($serializedConditionArray as $index => $singleArrItem) {
                if ($singleArrItem['name'] == 'form_key') {
                    unset($serializedConditionArray[$index]);
                    continue;
                }
                if ($singleArrItem['name'] == 'select') {
                    unset($serializedConditionArray[$index]);
                    continue;
                }

                $templateArray[str_replace('rule', '', $singleArrItem['name'])] = $singleArrItem['value'];
            }
            $finalArr = [];
            //@codingStandardsIgnoreStart
            foreach ($templateArray as $index => $data) {
                $indexName = $this->get_string_between(
                    str_replace(
                        '[conditions]',
                        '',
                        $index
                    ),
                    '[',
                    ']'
                );
                $paramName = $this->get_string_between(
                    str_replace(
                        '[conditions][' . $indexName . ']',
                        '',
                        $index
                    ),
                    '[',
                    ']'
                );
                if ($paramName == 'value' && strpos($data, ',')) {
                    $finalArr[$indexName][$paramName] = [$data];
                } else {
                    $finalArr[$indexName][$paramName] = $data;
                }
            }
            //@codingStandardsIgnoreEnd
            /** @var $model \Dev101\UniversalWidget\Model\Rule */
            $model = $this->ruleFactory->create();
            $this->_eventManager->dispatch(
                'adminhtml_controller_dev101_universalwidget_prepare_save',
                ['request' => $this->getRequest()]
            );
            $data = $this->getRequest()->getPostValue();

            $id = $this->getRequest()->getParam('rule_id');
            if ($id) {
                $model->load($id);
            }

            $validateResult = $model->validateData(new \Magento\Framework\DataObject($data));
            if ($validateResult !== true) {
                foreach ($validateResult as $errorMessage) {
                    $this->messageManager->addErrorMessage($errorMessage);
                }
                $this->_session->setPageData($data);
                return;
            }

            $data = $this->prepareData($data);
            $model->loadPost($data);

            $this->_session->setPageData($model->getData());

            $model->save();
            $this->messageManager->addSuccessMessage(__('You saved the rule.'));

            return;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $id = (int)$this->getRequest()->getParam('rule_id');
            if (!empty($id)) {
                $this->_redirect('universal/*/edit', ['id' => $id]);
            } else {
                $this->_redirect('universal/*/new');
            }
            return;
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('Something went wrong while saving the rule data. Please review the error log.')
            );
            $this->logger->critical($e);
            $data = !empty($data) ? $data : [];
            $this->_session->setPageData($data);
            $this->_redirect('universal/*/edit', ['id' => $this->getRequest()->getParam('rule_id')]);
            return;
        }
    }

    //@codingStandardsIgnoreLine
    protected function get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return '';
        }
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    /**
     * Prepares specific data
     *
     * @param array $data
     * @return array
     */
    protected function prepareData($data)
    {
        if (isset($data['rule']['conditions'])) {
            $data['conditions'] = $data['rule']['conditions'];
        }

        unset($data['rule']);

        return $data;
    }
}
