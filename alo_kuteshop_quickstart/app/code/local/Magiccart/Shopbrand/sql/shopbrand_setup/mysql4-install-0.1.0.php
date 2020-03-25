<?php
$installer = $this;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();
/*
$setup->addAttributeGroup('catalog_product', 'Default', 'Shop by Brand', 1000);
$installer->addAttribute('catalog_product', 'brand_product', array(
    'group'             => 'Shop by Brand',
    'type'              => Varien_Db_Ddl_Table::TYPE_VARCHAR,
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Shop by Brand',
    'input'             => 'select',
    'class'             => '',
    'source'            => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => true,
    'default'           => '',
    'searchable'        => true,
    'filterable'        => true,
    'comparable'        => true,
    'visible_on_front'  => true,
    'unique'            => false,
    'apply_to'          => 'simple,configurable,virtual,downloadable',
    'is_configurable'   => false
));
*/
$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('shopbrand/brand')};
CREATE TABLE {$this->getTable('shopbrand/brand')} (
  `brand_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `urlkey` varchar(512) NOT NULL DEFAULT '',
  `meta_key` varchar(512) NOT NULL DEFAULT '',
  `meta_description` varchar(512) NOT NULL DEFAULT '',
  -- `attribute_id` varchar(255) default '',
  `product_ids` text default '',
  `stores` varchar(255) NOT NULL DEFAULT '0',
  `image` varchar(512) NOT NULL DEFAULT '',
  `description` text DEFAULT '',
  `order` int(11),
  -- `limit` int(11),
  `status` smallint(6) NOT NULL DEFAULT '0',
  -- `config` text NOT NULL DEFAULT '',
  -- `responsive` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`brand_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

");

$installer->endSetup();
