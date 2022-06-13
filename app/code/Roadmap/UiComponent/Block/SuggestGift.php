<?php

namespace Roadmap\UiComponent\Block;

use Magento\Framework\View\Element\Template;
use Roadmap\UiComponent\Model\Suggest\Customer\Gift\Options;
use Magento\Framework\Serialize\Serializer\Json;

class SuggestGift extends Template
{
    const SAVE_ACTION_URL = 'suggest/customer/save';

    const SAVE_IMAGE_URL = 'suggest/customer/saveimage';

    /**
     * @var Options
     */
    private $options;

    /**
     * @var Json
     */
    private $json;

    /**
     * @param Options $options
     * @param Json $json
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Options $options,
        Json $json,
        Template\Context $context,
        array $data = []
    ) {
        $this->options = $options;
        $this->json = $json;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getActionUrl()
    {
        return $this->getUrl(self::SAVE_ACTION_URL);
    }

    /**
     * @return string
     */
    public function getSaveImageUrl()
    {
        return $this->getUrl(self::SAVE_IMAGE_URL);
    }

    /**
     * @return bool|string
     */
    public function getSkuOption()
    {
        return $this->json->serialize($this->options->toOptionArray());
    }
}
