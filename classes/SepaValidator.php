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

use Contao\Widget;

/**
 * Validator for IBAN and BIC
 *
 * @package    isotope_payment_sepa
 * @author     Michael Gruschwitz <info@grusch-it.de>
 * @copyright  Michael Gruschwitz 2015
 * @see        http://stackoverflow.com/questions/20983339/validate-iban-php#20983340
 */
class SepaValidator {

	/**
	 * @param string $rgxp
	 * @param string $value
	 * @param Widget $objWidget
	 * @return bool|null
	 */
	public function validate($rgxp, $value, Widget $objWidget)
	{
		switch ( strtolower($rgxp) )
		{
			case 'sepa_iban':
				return $this->validateIBAN($value, $objWidget);

			case 'sepa_bic':
				return $this->validateBIC($value, $objWidget);

			default:
				return null;
		}
	}

	/**
	 * @see http://en.wikipedia.org/wiki/International_Bank_Account_Number#Validating_the_IBAN
	 * @param string $value
	 * @param Widget $objWidget
	 * @return bool
	 */
	public function validateIBAN($value, Widget $objWidget)
	{
		$normalized = strtolower(str_replace(' ', '', $value));
		$country = substr($normalized, 0, 2);
		$length = $this->getIBANLength($country);

		// invalid country
		if ($length === null)
		{
			$objWidget->addError($GLOBALS['TL_LANG']['ERR']['sepa']['iban_country']);

			return false;
		}

		// invalid length
		if ($length != strlen($normalized))
		{
			$objWidget->addError($GLOBALS['TL_LANG']['ERR']['sepa']['iban_length']);

			return false;
		}

		// moving the 4 first characters to the end
		$moved = substr($normalized, 4) . substr($normalized, 0, 4);

		// get letter -> 2-digit mappings
		$mappings = $this->getIBANMappings();

		// transform iban
		$transformed = '';
		foreach (str_split($moved) as $char)
		{

			$add = $char;

			// get letter mapping if $char is a not numeric
			if ( ! is_numeric($char) && isset($mappings[$char]))
			{
				$add = $mappings[$char];
				// $char is not numeric nor a letter
			}
			else
			{
				if ( ! is_numeric($char))
				{
					$objWidget->addError($GLOBALS['TL_LANG']['ERR']['sepa']['iban_invalid']);

					return false;
				}
			}

			$transformed .= $add;
		}

		$valid = bcmod($transformed, '97') === '1';

		if ( ! $valid)
		{
			$objWidget->addError($GLOBALS['TL_LANG']['ERR']['sepa']['iban_invalid']);
		}

		return $valid;
	}

	/**
	 * @see http://stackoverflow.com/questions/15920008/regex-for-bic-check#15923871
	 * @param string $value
	 * @param Widget $objWidget
	 * @return bool
	 */
	public function validateBIC($value, Widget $objWidget)
	{
		$valid = (preg_match('/^[a-z]{4}[a-z]{2}[0-9a-z]{2}([0-9a-z]{3})?\z/i', $value) > 0);

		if ( ! $valid)
		{
			$objWidget->addError($GLOBALS['TL_LANG']['ERR']['sepa']['bic_invalid']);
		}

		return $valid;
	}

	/**
	 * Get the iban length for a specific country
	 *
	 * @param string $country Country ISO 2 code
	 * @return int|null
	 */
	public function getIBANLength($country)
	{
		$data = $this->getIBANLengths();

		if ( ! isset($data[$country]))
		{
			return null;
		}

		return $data[$country];
	}

	/**
	 * Returns array with iban character lengths for each country
	 *
	 * Array key: country ISO 2 code
	 * Array value: iban length
	 *
	 * @return array
	 */
	public function getIBANLengths()
	{
		return array
		(
			'al' => 28,
			'ad' => 24,
			'at' => 20,
			'az' => 28,
			'bh' => 22,
			'be' => 26,
			'ba' => 20,
			'br' => 29,
			'bg' => 22,
			'cr' => 21,
			'hr' => 21,
			'cy' => 28,
			'cz' => 24,
			'dk' => 18,
			'do' => 28,
			'ee' => 20,
			'fo' => 18,
			'fi' => 18,
			'fr' => 27,
			'ge' => 22,
			'de' => 22,
			'gi' => 23,
			'gr' => 27,
			'gl' => 18,
			'gt' => 28,
			'hu' => 28,
			'is' => 26,
			'ie' => 22,
			'il' => 23,
			'it' => 27,
			'jo' => 30,
			'kz' => 20,
			'kw' => 30,
			'lv' => 21,
			'lb' => 28,
			'li' => 21,
			'lt' => 20,
			'lu' => 20,
			'mk' => 19,
			'mt' => 31,
			'mr' => 27,
			'mu' => 30,
			'mc' => 27,
			'md' => 24,
			'me' => 22,
			'nl' => 18,
			'no' => 15,
			'pk' => 24,
			'ps' => 29,
			'pl' => 28,
			'pt' => 25,
			'qa' => 29,
			'ro' => 24,
			'sm' => 27,
			'sa' => 24,
			'rs' => 22,
			'sk' => 24,
			'si' => 19,
			'es' => 24,
			'se' => 24,
			'ch' => 21,
			'tn' => 24,
			'tr' => 26,
			'ae' => 23,
			'gb' => 22,
			'vg' => 24
		);
	}

	/**
	 * @param string $letter
	 * @return int|null
	 */
	public function getIBANMapping($letter)
	{
		$data = $this->getIBANMappings();

		// parameter is not a letter
		if ( ! isset($data[$letter]))
		{
			return null;
		}

		return $data[$letter];
	}

	/**
	 * @return array
	 */
	public function getIBANMappings()
	{
		return array(
			'a' => 10,
			'b' => 11,
			'c' => 12,
			'd' => 13,
			'e' => 14,
			'f' => 15,
			'g' => 16,
			'h' => 17,
			'i' => 18,
			'j' => 19,
			'k' => 20,
			'l' => 21,
			'm' => 22,
			'n' => 23,
			'o' => 24,
			'p' => 25,
			'q' => 26,
			'r' => 27,
			's' => 28,
			't' => 29,
			'u' => 30,
			'v' => 31,
			'w' => 32,
			'x' => 33,
			'y' => 34,
			'z' => 35
		);
	}
}
