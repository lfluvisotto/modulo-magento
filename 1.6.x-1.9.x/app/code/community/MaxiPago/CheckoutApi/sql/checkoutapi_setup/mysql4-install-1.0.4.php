<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to atendimento@saffira.com.br so we can send you a copy immediately.
 *
 * @category   Saffira / maxiPago
 * @package    MaxiPago_CheckoutApi
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$attribute = Mage::getResourceModel('eav/entity_attribute_collection')
	->setCodeFilter('maxipago_customer_id')
	->getFirstItem()
	->getData();

if (empty($attribute)) {
	$setup = Mage::getModel('customer/entity_setup', 'core_setup');
	$setup->addAttribute('customer', 'maxipago_customer_id', array(
			'type' => 'varchar',
			'input' => 'text',
			'label' => 'maxipago_customer_id',
			'global' => 1,
			'visible' => 0,
			'required' => 0,
			'user_defined' => 1,
			'default' => '0',
			'visible_on_front' => 0,
	));
		
	if (version_compare(Mage::getVersion(), '1.6.0', '<='))
	{
		$customer = Mage::getModel('customer/customer');
		$attrSetId = $customer->getResource()->getEntityType()->getDefaultAttributeSetId();
		$setup->addAttributeToSet('customer', $attrSetId, 'General', 'maxipago_customer_id');
	}
	if (version_compare(Mage::getVersion(), '1.4.2', '>='))
	{
		Mage::getSingleton('eav/config')
		->getAttribute('customer', 'maxipago_customer_id')
		->setData('used_in_forms', array('adminhtml_customer','customer_account_create','customer_account_edit','checkout_register'))
		->save();
	}
}

/** @var $installer Mage_Paypal_Model_Resource_Setup */
$installer = $this;

/**
 * Prepare database for install
 */

$installer->startSetup();


$installer->run("
	ALTER TABLE  {$this->getTable('sales_flat_quote_payment')} ADD  `maxipago_transaction_id` VARCHAR( 255 ) NULL DEFAULT NULL;
	ALTER TABLE  {$this->getTable('sales_flat_quote_payment')} ADD  `maxipago_split_number` VARCHAR( 255 ) NULL DEFAULT NULL;
	ALTER TABLE  {$this->getTable('sales_flat_quote_payment')} ADD  `maxipago_split_value` VARCHAR( 255 ) NULL DEFAULT NULL;
	ALTER TABLE  {$this->getTable('sales_flat_quote_payment')} ADD  `maxipago_token_transaction` VARCHAR( 255 ) NULL DEFAULT NULL;
	ALTER TABLE  {$this->getTable('sales_flat_quote_payment')} ADD  `maxipago_url_payment` VARCHAR( 255 ) NULL DEFAULT NULL;
	ALTER TABLE  {$this->getTable('sales_flat_quote_payment')} ADD  `maxipago_typeful_line` VARCHAR( 255 ) NULL DEFAULT NULL;
	ALTER TABLE  {$this->getTable('sales_flat_quote_payment')} ADD  `maxipago_fraud_score` VARCHAR( 255 ) NULL DEFAULT NULL;
	ALTER TABLE  {$this->getTable('sales_flat_quote_payment')} ADD  `maxipago_cc_token` VARCHAR( 255 ) NULL DEFAULT NULL;

	ALTER TABLE  {$this->getTable('sales_flat_order_payment')} ADD  `maxipago_transaction_id` VARCHAR( 255 ) NULL DEFAULT NULL;
	ALTER TABLE  {$this->getTable('sales_flat_order_payment')} ADD  `maxipago_split_number` VARCHAR( 255 ) NULL DEFAULT NULL;
	ALTER TABLE  {$this->getTable('sales_flat_order_payment')} ADD  `maxipago_split_value` VARCHAR( 255 ) NULL DEFAULT NULL;
	ALTER TABLE  {$this->getTable('sales_flat_order_payment')} ADD  `maxipago_token_transaction` VARCHAR( 255 ) NULL DEFAULT NULL;
	ALTER TABLE  {$this->getTable('sales_flat_order_payment')} ADD  `maxipago_url_payment` VARCHAR( 255 ) NULL DEFAULT NULL;
	ALTER TABLE  {$this->getTable('sales_flat_order_payment')} ADD  `maxipago_typeful_line` VARCHAR( 255 ) NULL DEFAULT NULL;
	ALTER TABLE  {$this->getTable('sales_flat_order_payment')} ADD  `maxipago_fraud_score` VARCHAR( 255 ) NULL DEFAULT NULL;
	ALTER TABLE  {$this->getTable('sales_flat_order_payment')} ADD  `maxipago_cc_token` VARCHAR( 255 ) NULL DEFAULT NULL;
	ALTER TABLE  {$this->getTable('sales_flat_order_payment')} ADD  `maxipago_capture_timestamp` VARCHAR( 255 ) NULL DEFAULT NULL;
	ALTER TABLE  {$this->getTable('sales_flat_order_payment')} ADD  `maxipago_processor_type` VARCHAR( 255 ) NULL DEFAULT NULL;
	ALTER TABLE  {$this->getTable('sales_flat_order_payment')} ADD  `maxipago_processor_id` VARCHAR( 255 ) NULL DEFAULT NULL;
	
	ALTER TABLE  {$this->getTable('sales_recurring_profile')} MODIFY  `reference_id` VARCHAR( 128 ) NULL DEFAULT NULL;
");

/**
 * Prepare database after install
 */

$installer->endSetup();
