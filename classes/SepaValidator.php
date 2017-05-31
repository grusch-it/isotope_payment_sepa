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

namespace Gruschit;

use Contao\Widget;

/**
 * Validator for IBAN and BIC
 *
 * @package    isotope_payment_sepa
 * @author     Michael Gruschwitz <info@grusch-it.de>
 * @copyright  Michael Gruschwitz 2015-2017
 * @see        http://stackoverflow.com/questions/20983339/validate-iban-php#20983340
 */
class SepaValidator
{

	/**
	 * @var array
	 */
	protected $letterMappings = array(
		'A' => 10,
		'B' => 11,
		'C' => 12,
		'D' => 13,
		'E' => 14,
		'F' => 15,
		'G' => 16,
		'H' => 17,
		'I' => 18,
		'J' => 19,
		'K' => 20,
		'L' => 21,
		'M' => 22,
		'N' => 23,
		'O' => 24,
		'P' => 25,
		'Q' => 26,
		'R' => 27,
		'S' => 28,
		'T' => 29,
		'U' => 30,
		'V' => 31,
		'W' => 32,
		'X' => 33,
		'Y' => 34,
		'Z' => 35,
	);

	/**
	 * @var array
	 */
	protected $lengthMappings = array(
		'AD' => 24,
		'AE' => 23,
		'AL' => 28,
		'AT' => 20,
		'AZ' => 28,
		'BA' => 20,
		'BE' => 16,
		'BG' => 22,
		'BH' => 22,
		'BR' => 29,
		'CH' => 21,
		'CR' => 21,
		'CY' => 28,
		'CZ' => 24,
		'DE' => 22,
		'DK' => 18,
		'DO' => 28,
		'EE' => 20,
		'ES' => 24,
		'FI' => 18,
		'FO' => 18,
		'FR' => 27,
		'GB' => 22,
		'GE' => 22,
		'GI' => 23,
		'GL' => 18,
		'GR' => 27,
		'GT' => 28,
		'HR' => 21,
		'HU' => 28,
		'IE' => 22,
		'IL' => 23,
		'IS' => 26,
		'IT' => 27,
		'JO' => 30,
		'KW' => 30,
		'KZ' => 20,
		'LB' => 28,
		'LI' => 21,
		'LT' => 20,
		'LU' => 20,
		'LV' => 21,
		'MC' => 27,
		'MD' => 24,
		'ME' => 22,
		'MK' => 19,
		'MR' => 27,
		'MT' => 31,
		'MU' => 30,
		'NL' => 18,
		'NO' => 15,
		'PK' => 24,
		'PL' => 28,
		'PS' => 29,
		'PT' => 25,
		'QA' => 29,
		'RO' => 24,
		'RS' => 22,
		'SA' => 24,
		'SE' => 24,
		'SI' => 19,
		'SK' => 24,
		'SM' => 27,
		'TN' => 24,
		'TR' => 26,
		'VG' => 24,
	);

	/**
	 * @param string $rgxp
	 * @param string $value
	 * @param Widget $objWidget
	 * @return bool|null
	 */
	public function validate($rgxp, $value, Widget $objWidget)
	{
		switch (strtolower($rgxp))
		{
			case 'sepa_iban':
				return $this->validateIban($value, $objWidget);

			case 'sepa_bic':
				return $this->validateBic($value, $objWidget);

			default:
				return null;
		}
	}

	/**
	 * @see http://en.wikipedia.org/wiki/International_Bank_Account_Number#Validating_the_IBAN
	 * @see http://www.cnb.cz/miranda2/export/sites/www.cnb.cz/cs/platebni_styk/iban/download/EBS204.pdf
	 * @param string $value
	 * @param Widget|null $objWidget
	 * @return bool
	 */
	public function validateIban($value, Widget $objWidget = null)
	{
		$iban = SepaPayment::normalizeIBAN($value);

		// invalid or unkown country code
		if ( ! $this->checkIbanCountryCode($iban))
		{
			$this->addErrorToWidget($GLOBALS['TL_LANG']['ERR']['sepa']['iban_country'], $objWidget);

			return false;
		}

		// invalid length
		if ( ! $this->checkIbanLength($iban))
		{
			$this->addErrorToWidget($GLOBALS['TL_LANG']['ERR']['sepa']['iban_length'], $objWidget);

			return false;
		}

		// invalid check digits
		if ( ! $this->checkIbanDigits($iban))
		{
			$this->addErrorToWidget($GLOBALS['TL_LANG']['ERR']['sepa']['iban_invalid'], $objWidget);

			return false;
		}

		return true;
	}

	/**
	 * @see http://stackoverflow.com/questions/15920008/regex-for-bic-check#15923871
	 * @param string $value
	 * @param Widget $objWidget
	 * @return bool
	 */
	public function validateBic($value, Widget $objWidget)
	{
		$valid = (preg_match('/^[a-z]{4}[a-z]{2}[0-9a-z]{2}([0-9a-z]{3})?\z/i', $value) > 0);

		if ( ! $valid)
		{
			$objWidget->addError($GLOBALS['TL_LANG']['ERR']['sepa']['bic_invalid']);
		}

		return $valid;
	}

	/**
	 * Check if the provided IBAN has a known country code.
	 *
	 * Provide a normalized IBAN using normalizeIBAN().
	 *
	 * @param string $iban
	 * @return bool
	 */
	protected function checkIbanCountryCode($iban)
	{
		if (strlen($iban) < 2)
		{
			return false;
		}

		$country = substr($iban, 0, 2);

		return isset($this->lengthMappings[$country]);
	}

	/**
	 * Check if the given iban has the correct length.
	 *
	 * Provide a normalized IBAN using normalizeIBAN().
	 *
	 * @param string $iban
	 * @return bool
	 */
	protected function checkIbanLength($iban)
	{
		$country = substr($iban, 0, 2);

		if ( ! isset($this->lengthMappings[$country]))
		{
			return false;
		}

		return strlen($iban) === $this->lengthMappings[$country];
	}

	/**
	 * Validate the check digits of an IBAN.
	 *
	 * Provide a normalized IBAN using normalizeIBAN().
	 *
	 * @param string $iban
	 * @return bool
	 */
	protected function checkIbanDigits($iban)
	{
		// move first 4 characters to the right
		$transformed = substr($iban, 4) . substr($iban, 0, 4);

		// replace letters by digits
		$transformed = str_replace(array_keys($this->letterMappings), $this->letterMappings, $transformed);

		// calculate Apply mod 97
		return (int)bcmod($transformed, 97) === 1;
	}

	/**
	 * @param string $strError
	 * @param Widget|null $objWidget
	 */
	protected function addErrorToWidget($strError, $objWidget)
	{
		if ($objWidget !== null)
		{
			$objWidget->addError($strError);
		}
	}
}
