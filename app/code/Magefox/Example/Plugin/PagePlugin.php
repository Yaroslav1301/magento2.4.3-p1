<?php
namespace Magefox\Example\Plugin;


class PagePlugin {
    public function afterGetContentHeading(\Magento\Cms\Model\Page $page, $contentHeading) {
        return "This is the".$contentHeading;
    }
}
