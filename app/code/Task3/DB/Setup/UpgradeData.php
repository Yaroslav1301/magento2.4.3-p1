<?php
namespace Task3\DB\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeData implements UpgradeDataInterface
{
    protected $_postFactory;

    public function __construct(\Task3\DB\Model\PostFactory $postFactory)
    {
        $this->_postFactory = $postFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '3.2.0', '<')) {

            $category = ['apple', 'orange', 'banana'];
            $sweets = ['chocolate', 'chips', 'ice-cream'];

            $data = [];
            for ($i = 0; $i < 3; $i++) {
                $data_temp  = [
                    'name' => "$category[$i]",
                    'post_content' => "This apple will talk about $category[$i].",
                    'url_key' => '/magento-2-module-development/magento-2-events.html',
                    'tags' => 'magento 2,mageplaza helloworld',
                    'status' => 1,
                    'Category' => 'fruits'
                ];
                $data[] = $data_temp;
            }
            for ($i = 0; $i < 3; $i++) {
                $data_temp  = [
                    'name' => "$sweets[$i]",
                    'post_content' => "This apple will talk about $sweets[$i].",
                    'url_key' => '/magento-2-module-development/magento-2-events.html',
                    'tags' => 'magento 2,mageplaza helloworld',
                    'status' => 1,
                    'Category' => 'sweets'
                ];
                $data[] = $data_temp;
            }


            foreach ($data as $datum) {
                $post = $this->_postFactory->create();
                $post->addData($datum);
                $post->save();
            }

            }

        }

}
