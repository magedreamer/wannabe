<?php
$installer = $this;

$installer->startSetup();

$this->getConnection()->delete(
        $this->getTable('core_config_data'),
        'path="upload/upload/upload_cache"'
    );

$installer->endSetup(); 