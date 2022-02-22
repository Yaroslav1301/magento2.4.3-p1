<?php

namespace  Kozar\Basic\Cron;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;

class ShowDiscount
{
    const PATH = "multi_select_section/general/cron_enable";
    protected $configWriter;
    public function __construct(WriterInterface $configWriter)
    {
        $this->configWriter = $configWriter;
    }
    public function SetData($path, $value)
    {
        $this->configWriter->save($path, $value, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
    }

    public function showBlock()
    {
        $isTrue = date_default_timezone_set("Europe/Kiev");
        $hour_now = (int)date("H", time());
        if ($hour_now >= 8 && $hour_now < 16) {
            $this->configWriter->save(self::PATH, 1, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
        }else {
            $this->configWriter->save(self::PATH, 0, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
        }
    }
}
