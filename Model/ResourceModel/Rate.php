<?php

namespace Salecto\Shipping\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Rate extends AbstractDb
{
    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init('salecto_shipping_rate', 'entity_id');
    }
}
