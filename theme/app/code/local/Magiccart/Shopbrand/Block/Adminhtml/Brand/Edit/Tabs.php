<?php

class Magiccart_Shopbrand_Block_Adminhtml_Brand_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('shopbrand_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('shopbrand')->__('Brand Information'));
	}

	protected function _beforeToHtml()
	{
	$this->addTab('form_section', array(
		'label'     => Mage::helper('shopbrand')->__('Brand Information'),
		'title'     => Mage::helper('shopbrand')->__('Brand Information'),
		'content'   => $this->getLayout()->createBlock('shopbrand/adminhtml_brand_edit_tab_form')->toHtml(),
	));

	$this->addTab('product_section', array(
		'label'     => Mage::helper('shopbrand')->__('Brand Products'),
		'title'     => Mage::helper('shopbrand')->__('Brand Products'),
		'url'       => $this->getUrl('*/*/product', array('_current' => true)),
		'class'     => 'ajax',
	));
	 
		return parent::_beforeToHtml();
	}
}

