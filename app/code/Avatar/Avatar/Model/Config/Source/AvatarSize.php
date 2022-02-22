<?php


namespace Avatar\Avatar\Model\Config\Source;


class AvatarSize
{
    /**
     * Get source model array
     * @return array
     */
    public function toOptionArray()
    {
        $source = [
            ['value' => 1, 'label' => '25X25'],
            ['value' => 2, 'label' => '25X40']
        ];
        return $source;
    }
}