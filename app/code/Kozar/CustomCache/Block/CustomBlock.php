<?php


namespace Kozar\CustomCache\Block;

use Magento\Framework\View\Element\Template;

class CustomBlock extends \Magento\Framework\View\Element\Template
{
    const TYPE_IDENTIFIER = 'kozar_cache';

    const CACHE_TAG = 'KOZAR_CACHE';

    private $cache;

    private $serializer;

    public function __construct(
        \Magento\Framework\App\CacheInterface $cache,
        \Magento\Framework\Serialize\SerializerInterface $serializer,
        Template\Context $context,
        array $data = []
    ) {
        $this->cache = $cache;
        $this->serializer = $serializer;
        parent::__construct($context, $data);
    }

    public function getMyDataForBlock()
    {
        /*
         * Check if data saved in cache
         */
        if ($this->cache->load(self::TYPE_IDENTIFIER)) {
            if ($cache = $this->serializer->unserialize($this->cache->load(self::TYPE_IDENTIFIER))) {
                return $cache;
            }
        }


        $data = "yaroslav kozar";

        $this->cache->save(
            $this->serializer->serialize($data),
            self::TYPE_IDENTIFIER,
            [self::CACHE_TAG],
            86400
        );

        return $data;
    }
}
