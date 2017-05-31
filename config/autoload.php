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
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'Gruschit',
));

/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Gruschit\SepaPayment'             => 'system/modules/isotope_payment_sepa/classes/SepaPayment.php',
	'Gruschit\SepaCheckoutForm'        => 'system/modules/isotope_payment_sepa/classes/SepaCheckoutForm.php',
	'Gruschit\SepaValidator'           => 'system/modules/isotope_payment_sepa/classes/SepaValidator.php',
	'Gruschit\SepaPaymentEventHandler' => 'system/modules/isotope_payment_sepa/classes/SepaPaymentEventHandler.php',
	'Gruschit\SepaPaymentBag'          => 'system/modules/isotope_payment_sepa/classes/SepaPaymentBag.php',
	'Gruschit\SepaBackendInterface'    => 'system/modules/isotope_payment_sepa/classes/SepaBackendInterface.php',
));

/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'iso_payment_sepa'    => 'system/modules/isotope_payment_sepa/templates/payment',
	'be_iso_payment_sepa' => 'system/modules/isotope_payment_sepa/templates/payment',
));
