<?php
/**
 * Magiccart 
 * @category  Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license   http://www.magiccart.net/license-agreement.html
 * @Author: Magiccart<team.magiccart@gmail.com>
 * @@Create Date: 2014-09-10 10:21:05
 * @@Modify Date: 2015-07-12 19:00:32
 * @@Function:
 */
?>
<?php
class Magiccart_Megashop_Block_Adminhtml_Manage_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$model = Mage::registry('megashop_data');	
		
		// if($model->getStores())
		// {		
		// 	//$_model->setPageId(Mage::helper('core')->jsonDecode($_model->getPageId()));
		// 	$model->setStores(explode(',',$model->getStores()));
		// }

		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('megashop_form', array('legend'=>Mage::helper('megashop')->__('General Information')));
		
		$fieldset->addField('title', 'text', array(
			'label'     => Mage::helper('adminhtml')->__('Title'),
			'class'     => 'required-entry',
			'required'  => true,
			'name'      => 'title',
		));

        $fieldset->addField('identifier', 'text', array(
            'name'      => 'identifier',
            'label'     => Mage::helper('adminhtml')->__('Identifier'),
            'title'     => Mage::helper('adminhtml')->__('Identifier'),
            'required'  => true,
            'class'     => 'validate-xml-identifier',
            'after_element_html' => '<p class="nm"><small>For example: slide-home_1, slide-home_2, slide-left,</small></p>'
        ));

		// $fieldset->addField('image', 'image', array(
		// 	'label'     => Mage::helper('adminhtml')->__('Image'),
		// 	'required'  => true,
		// 	'name'      => 'image',
		// ));

		$fieldset->addField('category_id', 'select', array(
			'name'      => 'category_id',
			'label'     => Mage::helper('adminhtml')->__('Category'),
			'title'     => Mage::helper('adminhtml')->__('Category'),
			'required'  => true,			
			'values'    => Mage::getSingleton('megashop/system_config_category')->toOptionArray(),
			'after_element_html' => '<small>Main category filter</small>',
		));

        $fieldset->addField('related_categories', 'note', array(
			'name'		=>'related_categories',
            'label'		=> $this->__('Related categories'),
            'text' 		=> $this->getLayout()->createBlock('megashop/adminhtml_manage_edit_tab_categories')->toHtml(),
            // 'after_element_html' => '<small>Related categories if not set auto get children main category</small>',
    	));

		$fieldset->addField('types', 'multiselect', array(
			'name'      => 'types[]',
			'label'     => Mage::helper('adminhtml')->__('Product Collection'),
			'title'     => Mage::helper('adminhtml')->__('Product Collection'),
			'required'  => true,			
			'values'    => Mage::getSingleton('megashop/system_config_type')->toOptionArray(),
			// 'value'     => $model->getStores()
			//'value'     => array('0'=>'1','1'=>'2'),	
		));

		$fieldset->addField('active', 'select', array(
			'name'      => 'active',
			'label'     => Mage::helper('adminhtml')->__('Tab Active'),
			'title'     => Mage::helper('adminhtml')->__('Tab Active'),
			'required'  => true,			
			'values'    => Mage::getSingleton('megashop/system_config_type')->toOptionArray(),
		));

        $fieldset->addField('limit', 'text', array(
            'name'      => 'limit',
            'label'     => Mage::helper('adminhtml')->__('Limit'),
            'title'     => Mage::helper('adminhtml')->__('Limit'),
            'value'		=> 10,
            'required'  => true,
            'class'     => 'validate-greater-than-zero',
        ));

		
		// if (!Mage::app()->isSingleStoreMode()) {
		// 	$field = $fieldset->addField('stores', 'multiselect', array(
		// 		'name'      => 'stores[]',
		// 		'label'     => Mage::helper('cms')->__('Store View'),
		// 		'title'     => Mage::helper('cms')->__('Store View'),
		// 		'required'  => true,			
		// 		'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
		// 		 'value'     => $model->getStores()
		// 		//'value'     => array('0'=>'1','1'=>'2'),	
		// 	));
		// 	$renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
		// 	$field->setRenderer($renderer);
		// }else {
		// 	$fieldset->addField('stores', 'hidden', array(
		// 		'name'      => 'stores[]',
		// 		'value'     => Mage::app()->getStore(true)->getId()
		// 	));
		// 	$model->setStoreId(Mage::app()->getStore(true)->getId());
		// } 
		
		$fieldset->addField('status', 'select', array(
			'label'     => Mage::helper('adminhtml')->__('Status'),
			'name'      => 'status',
			'values'    => array(
				array(
				'value'     => 1,
				'label'     => Mage::helper('adminhtml')->__('Enabled'),
				),
				array(
				'value'     => 2,
				'label'     => Mage::helper('adminhtml')->__('Disabled'),
				),
			),
		));
		
		// $fieldset->addField('advanced_settings', 'textarea', array(
		// 	'label'     => Mage::helper('adminhtml')->__('Advanced Settings'),
		// 	'required'  => false,
		// 	'name'      => 'advanced_settings',
		// 	'note'   	=> "Default : {numbers_align: 'right',animation:'fade',interval: 1000,dots: true,navigation: false}"
		// )); 
		
		if (Mage::getSingleton('adminhtml/session')->getMegashopData())
		{
			$form->setValues(Mage::getSingleton('adminhtml/session')->getMegashopData());
			Mage::getSingleton('adminhtml/session')->setMegashopData(null);
		} elseif ( Mage::registry('megashop_data') ) {
            $data = Mage::registry('megashop_data')->getData();
            if($data) $form->setValues($data);
		}
		return parent::_prepareForm();
	}
}
