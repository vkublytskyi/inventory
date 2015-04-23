<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Tax\Interceptor\Model\PrivateData\Section;

use Magento\Checkout\Model\PrivateData\Section\Cart;

/**
 * Cart interceptor
 */
class CartInterceptor
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Checkout\Helper\Data
     */
    protected $checkoutHelper;

    /**
     * @var \Magento\Quote\Model\Quote|null
     */
    protected $quote = null;

    /**
     * @var array|null
     */
    protected $totals = null;

    /**
     * @var \Magento\Tax\Model\Config
     */
    protected $taxConfig;

    /**
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Checkout\Helper\Data $checkoutHelper
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Checkout\Helper\Data $checkoutHelper,
        \Magento\Tax\Model\Config $taxConfig
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->checkoutHelper = $checkoutHelper;
        $this->taxConfig = $taxConfig;
    }

    /**
     * Add tax data to result
     *
     * @param Cart $subject
     * @param array $result
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetSectionData(Cart $subject, $result)
    {
        $result['subtotal_incl_tax'] = $this->checkoutHelper->formatPrice($this->getSubtotalInclTax());
        $result['subtotal_excl_tax'] = $this->checkoutHelper->formatPrice($this->getSubtotalExclTax());
        $result['display_cart_subtotal_incl_tax'] = $this->taxConfig->displayCartSubtotalInclTax();
        $result['display_cart_subtotal_excl_tax'] = $this->taxConfig->displayCartSubtotalExclTax();
        return $result;
    }

    /**
     * Get subtotal, including tax
     *
     * @return float
     */
    protected function getSubtotalInclTax()
    {
        $subtotal = 0;
        $totals = $this->getTotals();
        if (isset($totals['subtotal'])) {
            $subtotal = $totals['subtotal']->getValueInclTax() ?: $totals['subtotal']->getValue();
        }
        return $subtotal;
    }

    /**
     * Get subtotal, excluding tax
     *
     * @return float
     */
    protected function getSubtotalExclTax()
    {
        $subtotal = 0;
        $totals = $this->getTotals();
        if (isset($totals['subtotal'])) {
            $subtotal = $totals['subtotal']->getValueExclTax() ?: $totals['subtotal']->getValue();
        }
        return $subtotal;
    }

    /**
     * Get totals
     *
     * @return array
     */
    public function getTotals()
    {
        // TODO: TODO: MAGETWO-34824 duplicate \Magento\Checkout\Model\PrivateData\Section\Cart::getSectionData
        if (empty($this->totals)) {
            $this->totals = $this->getQuote()->getTotals();
        }
        return $this->totals;
    }

    /**
     * Get active quote
     *
     * @return \Magento\Quote\Model\Quote
     */
    protected function getQuote()
    {
        if (null === $this->quote) {
            $this->quote = $this->checkoutSession->getQuote();
        }
        return $this->quote;
    }
}
