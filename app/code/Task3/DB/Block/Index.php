<?php
namespace Task3\DB\Block;

use Magento\Framework\View\Element\Template;

class Index  extends \Magento\Framework\View\Element\Template
{
    protected $_pageFactory;
    protected $_postFactory;

    public function __construct(
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Task3\DB\Model\PostFactory $postFactory,
        Template\Context $context, array $data = []
    )
    {
        $this->_pageFactory = $pageFactory;
        $this->_postFactory = $postFactory;
        parent::__construct($context, $data);
    }

    public function getAndShow(){
        $post = $this->_postFactory->create();
        $collection = $post->getCollection()->addFieldToFilter('Category',['eq'=>'sweets']);
        $collection->addFieldToSelect(['name', 'url_key', 'tags', 'Category', 'created_at']);
        echo "<table border='1'><tr><th>name</th><th>url_key</th><th>tags</th><th>Category</th><th>created_at</th></tr>";
        foreach ($collection as $item) {
            $name = $item->getData('name');
            $url_key = $item->getData('url_key');
            $tags = $item->getData('tags');
            $Category = $item->getData('Category');
            $created_at = $item->getData('created_at');
           echo "<tr><td>$name</td><td>$url_key</td><td>$tags</td><td>$Category</td><td>$created_at</td></tr>";
        }
        echo "</table>";

    }

}
