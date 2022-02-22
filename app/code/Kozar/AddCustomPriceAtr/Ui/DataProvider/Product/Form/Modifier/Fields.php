<?php
namespace Kozar\AddCustomPriceAtr\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Element\Select;
use Magento\Ui\Component\Form\Field;

use Magento\Ui\Component\Form\Fieldset;

class Fields extends AbstractModifier
{
    /**
     * @var LocatorInterface $locator
     * getting Product informatin by $locator->getProduct()
     */
    private $locator;
    private $arrayManager;
    protected $request;
    protected $_productRepository;
    protected $_helper;
    public function __construct(
        \Magento\Catalog\Model\ProductRepository $productRepository,
        RequestInterface $request,
        ArrayManager $arrayManager,
        \Kozar\AddCustomPriceAtr\Helper\Data $_helper,
        LocatorInterface $locator
    ) {
        $this->locator = $locator;
        $this->request = $request;
        $this->arrayManager = $arrayManager;
        $this->_productRepository = $productRepository;
        $this->_helper = $_helper;
    }

    public function modifyData(array $data)
    {
        return $data;
    }

    public function modifyMeta(array $meta)
    {
        $meta = array_replace_recursive(
            $meta,
            [
                'magenest' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __('Kozar Add Custom Price Field'),
                                'collapsible' => true,
                                'componentType' => Fieldset::NAME,
                                'dataScope' => 'data.magenest',
                                'sortOrder' => 10,
                            ],
                        ],
                    ],
                    'children' => $this->getFields()
                ],
            ]
        );

        $product = $this->locator->getProduct();
        $enabled = !boolval($product->getData('allow_modify'));
        $meta['magenest']['children']['textField']['arguments']['data']['config']['default'] = $product->getData('custom_price');
        $meta['magenest']['children']['textField']['arguments']['data']['config']['disabled'] = $enabled;
        return $meta;
    }

    protected function getFields()
    {
        $product = $this->locator->getProduct();
        $enabled = +($product->getData('allow_modify'));
        $options = $this->getOptions($enabled);
        return [
            'status'    => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label'         => __('Status'),
                            'componentType' => Field::NAME,
                            'formElement'   => Select::NAME,
                            'dataScope'     => 'status',
                            'dataType'      => Text::NAME,
                            'sortOrder'     => 10,
                            'options'       => $options,
                            'selected' => '1'
                        ],
                    ],
                ],
            ],
            'textField' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label'         => __('Custom price'),
                            'componentType' => Field::NAME,
                            'formElement'   => Input::NAME,
                            'dataScope'     => 'textField',
                            'dataType'      => Text::NAME,
                            'sortOrder'     => 20,
                            'additionalClasses' => 'admin__field-x-small',
                            'default' => '0'
                        ],
                    ],
                ],
            ]
        ];
    }

    protected function getOptions($enabled)
    {
        $inactive = ['value' => '0', 'label' => __('Inactive')];
        $active = ['value' => '1', 'label' => __('Active')];
        if ($enabled) {
            list($inactive, $active) = [$active, $inactive];
        }
        return [$inactive, $active];
    }
}
