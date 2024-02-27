<?php

namespace Salecto\Shipping\Block\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\Serialize\Serializer\Json;

class SalectoShippingDataProcessor implements LayoutProcessorInterface
{
    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var Json
     */
    private $jsonSerializer;

    /**
     * @param Session $checkoutSession
     * @param Json $jsonSerializer
     */
    public function __construct(
        Session $checkoutSession,
        Json $jsonSerializer
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @return array
     */
    public function process($jsLayout)
    {
        if (!isset($jsLayout['components']['checkoutProvider']['salectoShippingData'])) {
            $shippingData = $this->checkoutSession->getQuote()->getData('salecto_shipping_data');
            if ($shippingData) {
                $jsLayout['components']['checkoutProvider']['salectoShippingData'] = $this->jsonSerializer->unserialize(
                    $this->checkoutSession->getQuote()->getData('salecto_shipping_data')
                );
            }
        }
        return $jsLayout;
    }
}
