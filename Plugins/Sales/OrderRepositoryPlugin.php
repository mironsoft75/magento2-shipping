<?php

namespace Salecto\Shipping\Plugins\Sales;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class OrderRepositoryPlugin
{
    /**
     * @param OrderRepositoryInterface $subject
     * @param $ret
     * @return mixed
     */
    public function afterGet(
        OrderRepositoryInterface $subject,
        $ret
    ) {
        $ret->getExtensionAttributes()->setSalectoShippingData($ret->getData('salecto_shipping_data'));
        return $ret;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param $ret
     * @return mixed
     */
    public function afterGetList(
        OrderRepositoryInterface $subject,
        $ret
    ) {
        /** @var OrderInterface $order */
        foreach ($ret->getItems() as $order) {
            $order->getExtensionAttributes()->setSalectoShippingData($order->getData('salecto_shipping_data'));
        }
        return $ret;
    }
}
