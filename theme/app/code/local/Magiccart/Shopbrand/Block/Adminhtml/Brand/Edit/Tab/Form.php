<?php
/**
 * Magiccart 
 * @category  Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license   http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2015-07-29 09:48:59
 * @@Modify Date: 2015-08-04 14:55:59
 * @@Function:
 */
 
class Magiccart_Shopbrand_Block_Adminhtml_Brand_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
	$form = new Varien_Data_Form();
	$this->setForm($form);
	$fieldset = $form->addFieldset('shopbrand_form', array('legend'=>Mage::helper('shopbrand')->__('Brand information')));

	$fieldset->addField('title', 'text', array(
		'label'   => Mage::helper('adminhtml')->__('Title'),
		'class'   => 'required-entry',
		'required'=> true,
		'name'    => 'title',
	));

	$fieldset->addField('urlkey', 'text', array(
		'label'   => Mage::helper('adminhtml')->__('URL key'),
		'class'     => 'validate-identifier',
		'required'  => true,
		'name'    => 'urlkey',
	));

	$fieldset->addField('meta_key', 'text', array(
		'label'   => Mage::helper('adminhtml')->__('Meta Keywords'),
		'name'    => 'meta_key',
	));

	$fieldset->addField('meta_description', 'text', array(
		'label'   => Mage::helper('adminhtml')->__('Meta Description'),
		'name'    => 'meta_description',
	));

	$fieldset->addField('image', 'image', array(
		'label'     => Mage::helper('adminhtml')->__('Brand Logo'),
		'required'  => true,
		'name'      => 'image',
	));

	if(!Mage::app()->isSingleStoreMode()) {
		$fieldset->addField('stores','multiselect',array(
			'name'      => 'stores[]',
			'label'     => Mage::helper('adminhtml')->__('Store View'),
			'title'     => Mage::helper('adminhtml')->__('Store View'),
			'required'  => true,
			'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true)
		));
	}

	try {
		$config = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
		$config->setData(
			Mage::helper('shopbrand')->recursiveReplace('/shopbrand/', '/' . (string)Mage::app()->getConfig()->getNode('admin/routers/adminhtml/args/frontName') . '/', $config->getData())
		);
	} catch (Exception $ex) {
		$config = null;
	}

	$fieldset->addField('description', 'editor', array(
		'label'     => Mage::helper('adminhtml')->__('Brand description'),
		'title'     => Mage::helper('adminhtml')->__('Brand description'),
		'name'      => 'description',
		'style'     => 'width:300px; height:280px;',
		'sysiwyg'   => true,
		'required'  => false,
		'config'    => $config,
	));

	$fieldset->addField('order', 'text', array(
		'label'   => Mage::helper('adminhtml')->__('Order'),
		'class'   => 'validate-digits',
		'name'    => 'order',
	));    

	$fieldset->addField('status', 'select', array(
		'label'     => Mage::helper('adminhtml')->__('Status'),
		'name'      => 'status',
		'values'    => array(
			array(
				'value'     => '1',
				'label'     => Mage::helper('adminhtml')->__('Enabled'),
			),

			array(
				'value'     => '2',
				'label'     => Mage::helper('adminhtml')->__('Disabled'),
			),
		),
	));

	if ( Mage::getSingleton('adminhtml/session')->getBrandData() )
		{
			$form->setValues(Mage::getSingleton('adminhtml/session')->getBrandData());
			Mage::getSingleton('adminhtml/session')->setBrandData(null);
		} elseif ( Mage::registry('brand_data') ) {
			$data_form_edit = Mage::registry('brand_data')->getData();
			//$data_form_edit['stores'] = explode(",", $data_form_edit['stores']); // convert string stores id to array stores id
			$form->setValues($data_form_edit);
		}
		return parent::_prepareForm();

	}
}

