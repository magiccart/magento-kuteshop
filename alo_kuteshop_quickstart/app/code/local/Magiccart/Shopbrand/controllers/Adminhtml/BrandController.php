<?php
class Magiccart_Shopbrand_Adminhtml_BrandController extends Mage_Adminhtml_Controller_Action
{
	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('magiccart/shopbrand')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Brand Manager'), Mage::helper('adminhtml')->__('Brand Manager'));
		return $this;
	}

	public function indexAction() {
		$this->_initAction()
			->_addContent($this->getLayout()->createBlock('shopbrand/adminhtml_brand'))
			->renderLayout();
	}

	public function productAction(){

		// $this->loadLayout();
		// $this->getLayout()->getBlock('product.grid')
		// ->setProducts($this->getRequest()->getPost('products', null));
		// $this->renderLayout();

		$this->loadLayout();
		$root = $this->getLayout()->getBlock('root');
		$grid = $this->getLayout()->createBlock('shopbrand/adminhtml_brand_edit_tab_product', 'product.grid');
		$serializer = $this->getLayout()->createBlock('adminhtml/widget_grid_serializer', 'grid_serializer');
		$serializer->initSerializerBlock($grid, 'getSelectedProducts', 'links[products]', 'products');
		$serializer->addColumnInputName('position');

		// $root->setChild($grid); // $this->getLayout()->getBlock('product.grid')
		// $root->setChild($serializer); // $this->getLayout()->getBlock('grid_serializer')
		$content = $this->getLayout()->getBlock("content");
		$grid = $grid->setProducts($this->getRequest()->getPost('products', null));
		$content->append($grid);
		$content->append($serializer);
		$this->getResponse()->setBody(
	    	$content->toHtml()
	    );

		// $this->renderLayout();

	}
	
	public function productgridAction(){
		$this->getResponse()->setBody(
	        $this->getLayout()->createBlock('shopbrand/adminhtml_brand_edit_tab_product', 'product.grid')->setProducts($this->getRequest()->getPost('products', null))->toHtml()
	    );
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('shopbrand/brand')->load($id);
		$tmp 	= @unserialize($model->getData('config'));
		if(is_array($tmp)) $model->addData($tmp);
		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('brand_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('shopbrand/brand');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Brand Manager'), Mage::helper('adminhtml')->__('Brand Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Brand News'), Mage::helper('adminhtml')->__('Brand News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('shopbrand/adminhtml_brand_edit'))
			->_addLeft($this->getLayout()->createBlock('shopbrand/adminhtml_brand_edit_tabs'));

			$version = substr(Mage::getVersion(), 0, 3);
   			if (in_array($version, array('1.4','1.5','1.6','1.7')) && Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
    		$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
   			}

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('shopbrand')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}

	public function newAction() {
		$this->_forward('edit');
	}

	public function saveAction() {
		if($data = $this->getRequest()->getPost()) {
			if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
				try {
					$uploader = new Varien_File_Uploader('image');
					$uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
					$uploader->setAllowRenameFiles(true);
					$uploader->setFilesDispersion(false);

					$path = Mage::getBaseDir('media') . DS . 'magiccart' . DS . 'shopbrand' . DS;
					$result = $uploader->save($path, $_FILES['image']['name']);

					$data['image'] = 'magiccart/shopbrand/'.$result['file'];
				} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				}
			} else {
				if(isset($data['image']['delete']) && $data['image']['delete'] == 1) {
					$data['image'] = '';
				} else {
					unset($data['image']);
				}
			}

			if(isset($data['order'])) $data['order'] = intval($data['order']);

			if(isset($data['stores'])) $data['stores'] = implode(',',$data['stores']);

			// $tmp = array('timer', 'slide', 'vertical', 'auto', 'controls', 'pager', 'speed', 'pause',
			// 			 'row', 'marginColumn', 'productDelay', 'widthImages', 'heightImages', 'action',
			// 			 'portrait', 'landscape', 'tablet', 'desktop', 'visibleItems',
			// 		);
			// $config = array();
			// foreach ($tmp as $key) {
			// 	$config[$key] = $data[$key];
			// }
			// $data['config'] = serialize($config);
			
			$model = Mage::getModel('shopbrand/brand');
			
			try {
				if(isset($data['links'])){
					$products = Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['links']['products']); //Save the array to your database
					$product_ids = array_keys($products);
					$data['product_ids'] = implode(',',$product_ids); //convert array product id to string
				}
				$model->setData($data)
					->setId($this->getRequest()->getParam('id'));
				$model->save();

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}

			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setFormData($data);
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
		}

		$this->_redirect('*/*/');
	}

	public function deteteAction() {
		if($this->getRequest()->getParam('id') > 0) {
			try {
				$model = Mage::getModel('shopbrand/brand');
				$model->setId($this->getRequest()->getParam('id'))
					->delete();

				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

	public function massDeleteAction() {
		$shopbrandIds = $this->getRequest()->getParam('shopbrand');
		if(!is_array($shopbrandIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select brand(s)'));
		} else {
			try {
				foreach($shopbrandIds as $shopbrandId) {
					$shopbrand = Mage::getModel('shopbrand/brand')->load($shopbrandId);
					$shopbrand->delete();
				}

				Mage::getSingleton('adminhtml/session')->addSuccess(
					Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($shopbrandIds))
					);
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}

		$this->_redirect('*/*/index');
	}

	public function massStatusAction()
	{
		$shopbrandIds = $this->getRequest()->getParam('shopbrand');
		if(!is_array($shopbrandIds)) {
			Mage::getSingleton('adminhtml/session')->addError($this->__('Please select brand(s)'));
		} else {
			try {
				foreach ($shopbrandIds as $shopbrandId) {
					$shopbrand = Mage::getSingleton('shopbrand/brand')
						->load($shopbrandId)
						->setStatus($this->getRequest()->getParam('status'))
						->setIsMassupdate(true)
						->save();
				}
				$this->_getSession()->addSuccess(
					$this->__('Total of %d record(s) were successfully updated', count($shopbrandIds)));
			} catch (Exception $e) {
				$this->_getSession()->addError($e->getMessage());
			}
		}

		$this->_redirect('*/*/index');
	}

	public function exportCsvAction()
	{
		$fileName = 'shopbrand.csv';
		$content  = $this->getLayout()->createBlock('shopbrand/adminhtml_brand_grid')
			->getCsv();
		$this->_sendUploadResponse($fileName, $content);
	}

	public function exportXmlAction()
	{
		$fileName = 'shopbrand.xml';
		$content  = $this->getLayout()->createBlock('shopbrand/adminhtml_brand_grid')
			->getXml();

		$this->_sendUploadResponse($fileName, $content);
	}

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }

	protected function _isAllowed() {     return true; }

}

