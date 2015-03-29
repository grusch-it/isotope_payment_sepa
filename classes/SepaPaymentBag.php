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

use Contao\Encryption;
use Serializable;

class SepaPaymentBag implements Serializable {

	private $arrData = array();

	/**
	 * @param array $arrData
	 */
	public function __construct(array $arrData = array())
	{
		$this->arrData = $arrData;
	}

	/**
	 * Load payment bag from a serialized string.
	 *
	 * @param string $strSerialized
	 * @return static
	 */
	public static function load($strSerialized)
	{
		$static = new static;
		$static->unserialize($strSerialized);

		return $static;
	}

	/**
	 * Save a value of form field to the bag.
	 *
	 * Automatically encrypts the value before saving, if
	 * encryption is enabled for the form field.
	 *
	 * @param string $strKey   The name of the form field
	 * @param string $strValue The value to be saved
	 */
	public function put($strKey, $strValue)
	{
		foreach (SepaCheckoutForm::getFieldConfigurations() as $strName => $arrField)
		{
			// unknown form field
			if ($strKey != $strName)
			{
				continue;
			}

			// do not save submit button values
			if (isset($arrField['inputType']) && $arrField['inputType'] == 'submit')
			{
				continue;
			}

			// encrypted value
			if (isset($arrField['eval']) && isset($arrField['eval']['encrypt']) && $arrField['eval']['encrypt'] == true)
			{
				$this->arrData[$strKey] = Encryption::encrypt($strValue);
				continue;
			}

			$this->arrData[$strKey] = $strValue;
		}
	}

	/**
	 * Retrieve a value.
	 *
	 * Automatically decrypts an encrypted value, if
	 * encryption is enabled for the form field.
	 *
	 * @param string $strKey        The form fields name
	 * @param bool   $blnDecrypt    Automatically decrypt value
	 * @return mixed|null
	 */
	public function get($strKey, $blnDecrypt = true)
	{
		if ( ! isset($this->arrData[$strKey]))
		{
			return null;
		}

		foreach (SepaCheckoutForm::getFieldConfigurations() as $strName => $arrField)
		{
			// unknown form field
			if ($strKey != $strName)
			{
				continue;
			}

			// prevent decryption
			if ($blnDecrypt != true)
			{
				return $this->arrData[$strKey];
			}

			// decrypt value
			if (isset($arrField['eval']) && isset($arrField['eval']['encrypt']) && $arrField['eval']['encrypt'] == true)
			{
				return Encryption::decrypt($this->arrData[$strKey]);
			}

			return $this->arrData[$strKey];
		}

		return null;
	}

	/**
	 * Retrieve all values.
	 *
	 * @param bool $blnDecrypt  Automatically decrypt values
	 * @return array
	 */
	public function all($blnDecrypt = true)
	{
		$arrData = array();
		foreach (SepaCheckoutForm::getFieldConfigurations() as $strName => $arrField)
		{
			$arrData[$strName] = $this->get($strName, $blnDecrypt);
		}

		return $arrData;
	}

	/**
	 * Remove a value from the session.
	 *
	 * @param string $strKey
	 */
	public function remove($strKey)
	{
		if (isset($this->arrData[$strKey]))
		{
			unset($this->arrData[$strKey]);
		}
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * String representation of object
	 *
	 * @link http://php.net/manual/en/serializable.serialize.php
	 * @return string the string representation of the object or null
	 */
	public function serialize()
	{
		return serialize($this->arrData);
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Constructs the object
	 *
	 * @link http://php.net/manual/en/serializable.unserialize.php
	 * @param string $serialized <p>
	 *                           The string representation of the object.
	 *                           </p>
	 * @return void
	 */
	public function unserialize($serialized)
	{
		$this->arrData = deserialize($serialized, true);
	}

}
