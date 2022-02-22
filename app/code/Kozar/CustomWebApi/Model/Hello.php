<?php


namespace Kozar\CustomWebApi\Model;

use Kozar\CustomWebApi\Api\HelloInterface;

class Hello implements HelloInterface
{
    /**
     * {@inheritDoc}
     */
    public function name($name) : string
    {
        return "hello" . $name;
    }
}
