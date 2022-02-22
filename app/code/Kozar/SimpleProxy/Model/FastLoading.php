<?php

namespace Kozar\SimpleProxy\Model;

use Kozar\SimpleProxy\Model\SlowLoading;

class FastLoading
{
    protected $slowLoading;

    public function __construct(
        SlowLoading $slowLoading
    ) {
        $this->slowLoading = $slowLoading;
    }

    public function getFastValue()
    {
        return 'FastLoading value';
    }

    public function getSlowValue()
    {
        return $this->slowLoading->getValue();
    }
}
