<?php


namespace Kozar\CustomWebApi\Api;


interface HelloInterface
{
    /**
     * @param string $name
     * @return string
     */
    public function name($name): string;
}
