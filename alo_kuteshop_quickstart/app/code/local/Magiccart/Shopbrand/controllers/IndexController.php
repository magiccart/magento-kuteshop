<?php
class Magiccart_Shopbrand_IndexController extends Mage_Core_Controller_Front_Action
{
	
    public function ajaxAction()
    {
    	if ($this->getRequest()->isAjax()) {
	        $this->loadLayout();    
	        $this->renderLayout();
	        return $this;
	    } else {
            $this->_redirectReferer();
        }
    }

	public function indexAction()
	{
		$this->loadLayout();
		$this->renderLayout();
	}
}

