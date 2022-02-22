<?php

namespace Roadmap\CustomizeCheckoutStep\Model\Data;

use Roadmap\CustomizeCheckoutStep\Api\CommentInterface;
use Roadmap\CustomizeCheckoutStep\Setup\SchemaInformation;
use Magento\Framework\Api\AbstractSimpleObject;

class Comment extends AbstractSimpleObject implements CommentInterface
{
    /**
     * @inheritdoc
     */
    public function getComment()
    {
        return $this->_get(SchemaInformation::ATTRIBUTE_CUSTOMER_COMMENT);
    }

    /**
     * @inheritdoc
     */
    public function setComment($comment)
    {
        $this->setData(SchemaInformation::ATTRIBUTE_CUSTOMER_COMMENT, $comment);
    }
}
