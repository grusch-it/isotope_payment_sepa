<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package   isotope_payment_sepa
 * @author    Michael Gruschwitz <info@grusch-it.de>
 * @license   LGPL
 * @copyright Michael Gruschwitz 2015
 */

namespace Gruschit;

use Isotope\Model\ProductCollection;

/**
 * SEPA Payment Event Handler
 *
 * Adds notification tokens on checkout and clears sepa related session data.
 *
 * @package    isotope_payment_sepa
 * @author     Michael Gruschwitz <info@grusch-it.de>
 * @copyright  Michael Gruschwitz 2015
 * @see        http://stackoverflow.com/questions/20983339/validate-iban-php#20983340
 */
class SepaPaymentEventHandler {

	/**
	 * Adds account holder, IBAN (raw & masked) and BIC to the notification tokens.
	 *
	 * @param ProductCollection $objOrder
	 * @param array             $arrTokens
	 * @return array
	 */
	public function onGetNotificationTokens(ProductCollection $objOrder, $arrTokens)
	{
		if ( ! $objOrder->getPaymentMethod() instanceof SepaPayment)
		{
			return $arrTokens;
		}

		$arrTokens = array_merge($arrTokens, SepaCheckoutForm::retrieveAll());

		// masked IBAN
		$arrTokens['sepa_iban_masked'] = SepaPayment::maskIBAN($arrTokens['sepa_iban']);

		return $arrTokens;
	}

	/**
	 * Removes bank account data from the session.
	 *
	 * @param ProductCollection $objOrder
	 * @param array             $arrTokens
	 */
	public function onPostCheckout(ProductCollection $objOrder, $arrTokens)
	{
		if ( ! $objOrder->getPaymentMethod() instanceof SepaPayment)
		{
			return;
		}

		SepaCheckoutForm::forgetAll();
	}
}
