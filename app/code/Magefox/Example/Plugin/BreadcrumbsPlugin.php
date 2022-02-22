<?php

namespace Magefox\Example\Plugin;

class BreadcrumbsPlugin {
    public function beforeAddCrumb(\Magento\Theme\Block\Html\Breadcrumbs $subject, $crumbName, $crumbInfo) {
        if ($crumbInfo['label'] == "Privacy Policy") {
            $crumbInfo['label'] = "Something else";
        }

        return [$crumbName, $crumbInfo];
    }

    public function aroundAddCrumb(\Magento\Theme\Block\Html\Breadcrumbs $subject, \Closure $procced, $crumbName, $crumbInfo) {
        if ($crumbInfo['label'] == "Privacy Policy") {
            $crumbInfo['label'] = "Something else";
        }

        $result = $procced($crumbName, $crumbInfo);

        return $result;
    }
}
