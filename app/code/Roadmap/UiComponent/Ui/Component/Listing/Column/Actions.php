<?php

namespace Roadmap\UiComponent\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Actions extends Column
{
    /**
     * Edit route path
     */
    private const URL_PATH_EDIT = 'gift/page/edit';

    /**
     * Delete route path
     */
    private const URL_PATH_DELETE = 'gift/page/delete';

    /**
     * Delete route path
     */
    private const URL_PATH_DUPLICATE = 'gift/page/duplicate';

    /**
     * URL builder
     *
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * ManageActions constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $item[$this->getData('name')] = [
                    'edit' => [
                        'href' => $this->urlBuilder->getUrl(
                            self::URL_PATH_EDIT,
                            [
                                'gift_id' => $item['gift_id']
                            ]
                        ),
                        'label' => __('Edit')
                    ],
                    'delete' => [
                        'href' => $this->urlBuilder->getUrl(
                            self::URL_PATH_DELETE,
                            [
                                'gift_id' => $item['gift_id']
                            ]
                        ),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => 'Confirmation',
                            'message' => 'Are you sure to delete this gift?'
                        ]
                    ],
                    'duplicate' => [
                        'href' => $this->urlBuilder->getUrl(
                            self::URL_PATH_DUPLICATE,
                            [
                                'gift_id' => $item['gift_id']
                            ]
                        ),
                        'label' => __('Duplicate')
                    ],
                ];
            }
        }

        return $dataSource;
    }
}
