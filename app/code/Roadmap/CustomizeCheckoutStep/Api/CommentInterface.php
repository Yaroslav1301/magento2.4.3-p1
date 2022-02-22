<?php

namespace Roadmap\CustomizeCheckoutStep\Api;

interface CommentInterface
{
    /**
     * Get Comment
     * @return string
     */
    public function getComment();

    /**
     * Set Comment
     * @param string $comment
     * @return void
     */
    public function setComment($comment);
}
