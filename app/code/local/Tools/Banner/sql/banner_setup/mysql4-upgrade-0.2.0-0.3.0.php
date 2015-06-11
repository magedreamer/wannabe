<?php
$installer = $this;

$installer->startSetup();

$this->getConnection()->delete(
        $this->getTable('core_config_data'),
        'path="banner/banner/banner_cache"'
    );

$installer->endSetup(); 