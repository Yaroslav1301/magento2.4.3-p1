<?php

namespace Kozar\AddDeclaretive\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface StudentSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \Kozar\AddDeclaretive\Api\Data\StudentInterface[]
     */
    public function getItems();

    /**
     * @param \Kozar\AddDeclaretive\Api\Data\StudentInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
