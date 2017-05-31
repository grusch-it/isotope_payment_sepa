<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package   isotope_payment_sepa
 * @author    Michael Gruschwitz <info@grusch-it.de>
 * @license   LGPL
 * @copyright Michael Gruschwitz 2015-2017
 */

/**
 * Payment methods
 */
\Isotope\Model\Payment::registerModelType('sepa', 'Gruschit\SepaPayment');

/**
 * Notification Center notification types
 */
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['isotope']['iso_order_status_change']['email_text'][] = 'sepa_holder';
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['isotope']['iso_order_status_change']['email_text'][] = 'sepa_iban';
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['isotope']['iso_order_status_change']['email_text'][] = 'sepa_iban_masked';
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['isotope']['iso_order_status_change']['email_text'][] = 'sepa_bic';

/**
 * Events / Hooks
 */
$GLOBALS['ISO_HOOKS']['getOrderNotificationTokens'][] = array('Gruschit\SepaPaymentEventHandler', 'onGetNotificationTokens');
$GLOBALS['ISO_HOOKS']['postCheckout'][] = array('Gruschit\SepaPaymentEventHandler', 'onPostCheckout');

/**
 * Checkout Form Validator
 */
$GLOBALS['TL_HOOKS']['addCustomRegexp'][] = array('Gruschit\SepaValidator', 'validate');
