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
	 * Retrieve the names of all SEPA checkout form fields.
	 *
	 * @return array
	 */
	public function fields()
	{
		$arrFields = array_filter(SepaCheckoutForm::getFieldConfigurations(), function ($arrField)
		{
			return $arrField['inputType'] != 'submit';
		});

		return array_keys($arrFields);
	}

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

		foreach ($this->fields() as $strName)
		{
			$arrTokens[$strName] = SepaCheckoutForm::retrieve($strName);
			SepaCheckoutForm::forget($strName);
		}

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

		foreach ($this->fields() as $strName)
		{
			SepaCheckoutForm::forget($strName);
		}
	}
}
