<?php
class Magiccart_Shopbrand_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{
	public function initControllerRouters($observer)
	{
		$front = $observer->getEvent()->getFront();
		$front->addRouter('shopbrand', $this); // $this: new Magiccart_Shopbrand_Controller_Router();
	}

    public function match(Zend_Controller_Request_Http $request)
    {
        if (!Mage::isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }

        $identifier = trim($request->getPathInfo(), '/');

        $condition = new Varien_Object(array(
            'identifier' => $identifier,
            'continue'   => true
        ));
        Mage::dispatchEvent('shopbrand_controller_router_match_before', array(
            'router'    => $this,
            'condition' => $condition
        ));
        $identifier = $condition->getIdentifier();

        if ($condition->getRedirectUrl()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect($condition->getRedirectUrl())
                ->sendResponse();
            $request->setDispatched(true);
            return true;
        }

        if (!$condition->getContinue()) {
            return false;
        }

		$brand = Mage::getModel('shopbrand/brand');
		$id = $brand->checkIdentifier($identifier, Mage::app()->getStore()->getId());
		if(!$id) {
			return false;
		}

        $request->setModuleName('shopbrand')
            ->setControllerName('view')
            ->setActionName('view')
            ->setParam('id', $id);
        $request->setAlias(
            Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
            $identifier
        );

        return true;

	}
}