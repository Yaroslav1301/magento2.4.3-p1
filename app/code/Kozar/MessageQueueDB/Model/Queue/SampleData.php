<?php declare(strict_types=1);

/**
 * @author tjitse (Vendic)
 * Created on 2019-10-21 11:09
 */

namespace Kozar\MessageQueueDB\Model\Queue;

class SampleData implements \Kozar\MessageQueueDB\Api\Data\SampleDataInterface
{

    /**
     * @var string
     */
    protected $data;

    /**
     * @param string $data
     * @return void
     */
    public function setData(string $data): void
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }
}
