<?php

namespace Roadmap\CustomizeCheckoutStep\Model;

use Roadmap\CustomizeCheckoutStep\Api\CommentInterface;
use Roadmap\CustomizeCheckoutStep\Api\CommentManagerInterface;
use Roadmap\CustomizeCheckoutStep\Setup\SchemaInformation;
use Exception;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\GuestCartRepositoryInterface;
use Magento\Customer\Model\Session;

class CommentManager implements CommentManagerInterface
{
    /**
     * @var CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var GuestCartRepositoryInterface
     */
    protected $guestCartRepository;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @param CartRepositoryInterface $quoteRepository
     * @param GuestCartRepositoryInterface $guestCartRepository
     * @param Session $customerSession
     */
    public function __construct(
        CartRepositoryInterface $quoteRepository,
        GuestCartRepositoryInterface $guestCartRepository,
        Session $customerSession
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->guestCartRepository = $guestCartRepository;
        $this->customerSession = $customerSession;
    }

    /**
     * @param $cartId
     * @param CommentInterface $comment
     * @return string
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     */
    public function saveComment($cartId, CommentInterface $comment)
    {
        if ($this->customerSession->isLoggedIn()) {
            $quote = $this->quoteRepository->getActive($cartId);
        } else {
            $quote = $this->guestCartRepository->get($cartId);
        }

        if (!$quote->getItemsCount()) {
            throw new NoSuchEntityException(__('Cart %1 doesn\'t contain products', $cartId));
        }

        try {
            $comment = $comment->getComment();
            $quote->setData(SchemaInformation::ATTRIBUTE_CUSTOMER_COMMENT, strip_tags($comment));
            $this->quoteRepository->save($quote);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__('The simple note # number could not be saved'));
        }
        return $comment;
    }
}
