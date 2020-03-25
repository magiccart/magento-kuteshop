<?php
/**
 * Magiccart 
 * @category  Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license   http://www.magiccart.net/license-agreement.html
 * @Author: Magiccart<team.magiccart@gmail.com>
 * @@Create Date: 2014-07-28 17:49:00
 * @@Modify Date: 2015-09-15 09:33:21
 * @@Function:
 */
 ?>
<?php
class Magiccart_Testimonial_Block_Testimonial_View extends Magiccart_Testimonial_Block_Testimonial
{

	public function _getTestimonial()
	{
	    $id = $this->getRequest()->getParam('id');
	    $store = Mage::app()->getStore()->getStoreId();
	    $testimonials = Mage::getModel('testimonial/testimonial')
	                    ->getCollection()
	                    ->addFieldToFilter('stores',array( array('finset' => 0), array('finset' => $store)))
	                    ->addFieldToFilter('testimonial_id', $id)
	                    ->setOrder('position', 'ASC')
	                    ->addFieldToFilter('status', Mage_Review_Model_Review::STATUS_APPROVED);
	    return $testimonials->getFirstItem();
	}
    
}

