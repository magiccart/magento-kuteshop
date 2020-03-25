<?php
/**
 * Magiccart 
 * @category  Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license   http://www.magiccart.net/license-agreement.html
 * @Author: Magiccart<team.magiccart@gmail.com>
 * @@Create Date: 2014-09-10 10:21:05
 * @@Modify Date: 2015-07-12 17:44:14
 * @@Function:
 */
?>
<?php
class Magiccart_Megashop_Block_Adminhtml_Manage_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('megashop_tabs');
		$this->setName('megashop_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('adminhtml')->__('Megashop Information'));
	}

	protected function _beforeToHtml()
	{
		$this->addTab('general_section', array(
			'label'     => Mage::helper('adminhtml')->__('General Information'),
			'title'     => Mage::helper('adminhtml')->__('General Information'),
			'content'   => $this->getLayout()->createBlock('megashop/adminhtml_manage_edit_tab_form')->toHtml(),
		));	      

		$gallery = Mage::getSingleton('core/layout')->createBlock('megashop/adminhtml_manage_edit_tab_gallery');
		$gallery->setId($this->getHtmlId() . '_content')->setElement($this);       

		$this->addTab('gallery_section', array(
			'label'     => Mage::helper('adminhtml')->__('Banner Promotion'),
			'title'     => Mage::helper('adminhtml')->__('Banner Promotion'),
			'content'   => $gallery->toHtml(),
		));

		$config = Mage::getSingleton('core/layout')->createBlock('megashop/adminhtml_manage_edit_tab_config');
		$config->setId($this->getHtmlId() . '_content')->setElement($this);   

		$this->addTab('config_section', array(
			'label'     => Mage::helper('adminhtml')->__('Config'),
			'title'     => Mage::helper('adminhtml')->__('Config'),
			'content'   => $config->toHtml(),
		));

		$reponsive = Mage::getSingleton('core/layout')->createBlock('megashop/adminhtml_manage_edit_tab_reponsive');
		$reponsive->setId($this->getHtmlId() . '_content')->setElement($this); 
		$this->addTab('reponsive_section', array(
			'label'     => Mage::helper('adminhtml')->__('Reponsive'),
			'title'     => Mage::helper('adminhtml')->__('Reponsive'),
			'content'   => $reponsive->toHtml(),
		));

		return parent::_beforeToHtml();
	}
}
