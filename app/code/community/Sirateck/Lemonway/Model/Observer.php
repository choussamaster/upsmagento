<?php

class Sirateck_Lemonway_Model_Observer {
	
	
	/**
	 * Cancel orders stayed in pending because customer not validated payment form
	 */
	public function cancelOrdersInPending()
	{
	
		if(!Mage::getStoreConfigFlag('payment/lemonway_webkit/cancel_pending_order'))
			return $this;
				
			//Limited time in minutes
			$limitedTime = 30;
	
			$date = new Zend_Date();
				
			/* @var $collection Mage_Sales_Model_Resource_Order_Collection */
			$collection = Mage::getResourceModel('sales/order_collection');
			$collection->addFieldToSelect(array('entity_id','increment_id','store_id','state'))
			->addFieldToFilter('main_table.state',Mage_Sales_Model_Order::STATE_NEW)
			->addFieldToFilter('op.method',array('eq'=>'lemonway_webkit'))
			->addAttributeToFilter('created_at', array('to' => ($date->subMinute($limitedTime)->toString('Y-MM-dd HH:mm:ss'))))
			->join(array('op' => 'sales/order_payment'), 'main_table.entity_id=op.parent_id', array('method'));
	
			/* @var $order Mage_Sales_Model_Order */
			foreach ($collection as $order)
			{
				if($order->canCancel())
				{
					try {
	
						$order->cancel();
						$order
						->addStatusToHistory($order->getStatus(),// keep order status/state
								Mage::helper('sirateck_lemonway')->__("Order canceled automatically by cron because order is pending since %d minutes",$limitedTime));
	
						$order->save();
	
					} catch (Exception $e) {
						Mage::logException($e);
					}
				}
	
			}
	
			return $this;
	}
	
	
}