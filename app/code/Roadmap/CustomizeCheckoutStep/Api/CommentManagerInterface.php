<?php

namespace Roadmap\CustomizeCheckoutStep\Api;

use Roadmap\CustomizeCheckoutStep\Api\CommentInterface;

interface CommentManagerInterface
{
    /**
     * @param string $cartId
     * @param CommentInterface $comment
     * @return string
     */
    public function saveComment(
        $cartId,
        CommentInterface $comment
    );
}
