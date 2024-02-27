<?php

namespace Salecto\Shipping\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

/**
 * CopyShippingData Data Patch Class to remove old config values
 */
class CopyShippingData implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * UpdateConfiguration Data Patch constructor
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * Apply the data patch.
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();
        $connection = $this->moduleDataSetup->getConnection();
        $this->removeOldConfigValues($connection);
        $this->copyExistingShippingData($connection);
        $this->moduleDataSetup->endSetup();
    }

    /**
     * Revert the data patch.
     */
    public function revert()
    {
        // Not implemented
    }

    /**
     * Retrieve aliases for the data patch.
     *
     * @return array
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * Retrieve dependencies for the data patch.
     *
     * @return array
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * Remove old config values
     *
     * @param \Magento\Framework\DB\Adapter\AdapterInterface $connection
     */
    private function removeOldConfigValues($connection)
    {
        $configTableName = $this->moduleDataSetup->getTable('core_config_data');
        $select = $connection->select()->from($configTableName)->where('path LIKE ?', '%wexo_flatrate%');
        $dataToUpdate = $connection->fetchAll($select);

        foreach ($dataToUpdate as $configRow) {
            $configRow['path'] = str_replace('wexo_flatrate', 'salecto_flatrate', $configRow['path']);
            $configRow['value'] = isset($configRow['value']) ? str_ireplace('Wexo', 'Salecto', $configRow['value']) : null;
            $connection->update($configTableName, $configRow, ['config_id = ?' => $configRow['config_id']]);
        }
        
    }

    /**
     * Copy existing shipping data
     *
     * @param \Magento\Framework\DB\Adapter\AdapterInterface $connection
     */
    private function copyExistingShippingData($connection)
    {
        $wexoTableName = $this->moduleDataSetup->getTable('wexo_shipping_rate');

        if ($connection->isTableExists($wexoTableName)) {
            $salectoTableName = $this->moduleDataSetup->getTable('salecto_shipping_rate');
            $select = $connection->select()->from($wexoTableName);
            $data = $connection->fetchAll($select);

            foreach ($data as $row) {
                $connection->insert($salectoTableName, $row);
            }

            $connection->dropTable($wexoTableName);
        }

        $this->moduleDataSetup->endSetup();
    }
}
