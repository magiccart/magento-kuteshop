<?php

class Magiccart_Shopbrand_Block_Adminhtml_Brand_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    
    protected function _prepareLayout() {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'shopbrand';
        $this->_controller = 'adminhtml_brand';
        
        $this->_updateButton('save', 'label', Mage::helper('shopbrand')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('shopbrand')->__('Delete Item'));
        
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('shopbrand_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'shopbrand_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'shopbrand_content');
                }
            }
            var back = $('edit_form').action + 'back/edit/';
            function saveAndContinueEdit(){
                editForm.submit(back);
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('brand_data') && Mage::registry('brand_data')->getId() ) {
            return Mage::helper('shopbrand')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('brand_data')->getTitle()));
        } else {
            return Mage::helper('shopbrand')->__('Add Item');
        }
    }
}

