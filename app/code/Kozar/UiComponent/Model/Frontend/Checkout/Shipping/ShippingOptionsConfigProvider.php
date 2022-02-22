<?php


namespace Kozar\UiComponent\Model\Frontend\Checkout\Shipping;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class ShippingOptionsConfigProvider
 * @package Kozar\UiComponent\Model\Frontend\Checkout\Shipping
 */
class ShippingOptionsConfigProvider implements ConfigProviderInterface
{
    /**#@+
     * Constants for scope config
     */
    private const CONFIG_DUAL_CONFIRM_MODULE_ENABLED =
        'shipping_options/shipping_dual_confirmation/shipping_dual_confirmation_enabled';
    private const CONFIG_DUAL_CONFIRM_TITLE =
        'shipping_options/shipping_dual_confirmation/shipping_dual_confirmation_title';
    private const CONFIG_DUAL_CONFIRM_DESCRIPTION =
        'shipping_options/shipping_dual_confirmation/shipping_dual_confirmation_desc';
    /**#@-*/

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $output = [];

    /**
     * PaymentDeliveryOptionsConfigProvider constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): array
    {
        if (!empty($this->output)) {
            return $this->output;
        }
        try {
            $config = [
                'enabled' => $this->isDualConfirmEnabled(),
                'title' => $this->getTitle(),
                'body' => $this->getDescription()
            ];
            $this->output['shipping_options'] = $config;
        } catch (\Throwable $throwable) {
            $this->logger->critical($throwable->getMessage());
        }

        return $this->output;
    }

    /**
     * Check is module enabled
     *
     * @return bool
     */
    private function isDualConfirmEnabled(): bool
    {
        return (bool) $this->scopeConfig->isSetFlag(self::CONFIG_DUAL_CONFIRM_MODULE_ENABLED);
    }

    /**
     * Get title for popup
     *
     * @return string
     * @throws NoSuchEntityException
     */
    private function getTitle(): string
    {
        return (string) $this->scopeConfig->getValue(
            self::CONFIG_DUAL_CONFIRM_TITLE,
            ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }

    /**
     * Get description for popup
     *
     * @return string
     * @throws NoSuchEntityException
     */
    private function getDescription(): string
    {
        return (string) $this->scopeConfig->getValue(
            self::CONFIG_DUAL_CONFIRM_DESCRIPTION,
            ScopeInterface::SCOPE_STORE,
            $this->storeManager->getStore()->getId()
        );
    }
}
