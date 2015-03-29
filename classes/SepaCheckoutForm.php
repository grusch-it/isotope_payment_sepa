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

use Contao\Frontend;
use Contao\Widget;
use Isotope\Module\Checkout;
use Isotope\Template;

class SepaCheckoutForm extends Frontend {

	/**
	 * @var string
	 */
	protected $strTemplate = 'iso_payment_sepa';

	/**
	 * @var string
	 */
	protected $strFormId = 'sepa_checkout_form';

	/**
	 * @var string
	 */
	protected $strClass = 'iso_payment_sepa';

	/**
	 * @var bool
	 */
	protected $blnTableless = true;

	/**
	 * @var Widget[]
	 */
	protected $arrWidgets = array();

	/**
	 * @var Template
	 */
	protected $Template;

	/**
	 * Create new checkout form for sepa payments
	 *
	 * @param bool $blnTableless
	 */
	public function __construct($blnTableless = true)
	{
		parent::__construct();

		$this->Template = new Template($this->strTemplate);
		$this->blnTableless = (bool) $blnTableless;

		$this->Template->action = Checkout::generateUrlForStep('process');
		$this->Template->method = 'post';
		$this->Template->enctype = 'application/x-www-form-urlencoded';

		$this->Template->formId = $this->strFormId;
		$this->Template->formSubmit = $this->strFormId;
		$this->Template->tableless = $this->blnTableless;

		foreach (self::getFieldConfigurations() as $strName => $arrField)
		{
			if (is_null($objWidget = $this->createWidget($strName, $arrField)))
			{
				continue;
			}

			$this->arrWidgets[] = $objWidget;
		}
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->strFormId;
	}

	/**
	 * Parse the template
	 *
	 * @return string
	 */
	public function generate()
	{
		$strFields = '';
		foreach ($this->getWidgets() as $objWidget)
		{
			$strFields .= $objWidget->parse();
		}
		$this->Template->fields = $strFields;

		return $this->Template->parse();
	}

	/**
	 * Validate user input
	 *
	 * @return bool
	 */
	public function validate()
	{
		$blnValid = true;

		foreach ($this->getWidgets() as $objWidget)
		{
			$objWidget->validate();

			$strName = $objWidget->name;
			$strValue = $objWidget->value;

			// make sure that the IBAN form field will only contain a masked value
			if ($strName == 'sepa_iban')
			{
				$objWidget->value = SepaPayment::maskIBAN($strValue);
			}

			if ($objWidget->hasErrors())
			{
				$blnValid = false;
			}

			// Store current value in the session
			elseif ($objWidget->submitInput())
			{
				self::remember($strName, $strValue);
				unset($_POST[$strName]); // see #5474
			}
		}

		return $blnValid;
	}

	/**
	 * Save a value to the session.
	 *
	 * @param string $strKey
	 * @param string $strValue
	 */
	public static function remember($strKey, $strValue)
	{
		$_SESSION['FORM_DATA'][$strKey] = $strValue;
	}

	/**
	 * Retrieve a value from the session.
	 *
	 * @param string $strKey
	 * @return mixed|null
	 */
	public static function retrieve($strKey)
	{
		if ( ! isset($_SESSION['FORM_DATA'][$strKey]))
		{
			return null;
		}

		return $_SESSION['FORM_DATA'][$strKey];
	}

	/**
	 * Remove a value from the session.
	 *
	 * @param string $strKey
	 */
	public static function forget($strKey)
	{
		if (isset($_SESSION['FORM_DATA'][$strKey]))
		{
			unset($_SESSION['FORM_DATA'][$strKey]);
		}
	}

	/**
	 * Create a form field widget
	 *
	 * @param string $strName
	 * @param array  $arrField
	 * @return Widget|null
	 */
	protected function createWidget($strName, $arrField)
	{
		$strClass = $GLOBALS['TL_FFL'][$arrField['inputType']];

		if ( ! class_exists($strClass))
		{
			return null;
		}

		/** @var Widget $objWidget */
		$objWidget = new $strClass($arrField['eval']);
		$objWidget->id = $strName;
		$objWidget->name = $strName;
		$objWidget->tableless = (bool) $this->blnTableless;
		$objWidget->label = $arrField['label'];

		return $objWidget;
	}

	/**
	 * @return Widget[]
	 */
	protected function getWidgets()
	{
		return $this->arrWidgets;
	}

	/**
	 * @return array
	 */
	public static function getFieldConfigurations()
	{
		return array
		(
			'sepa_holder' => array
			(
				'label'     => &$GLOBALS['TL_LANG']['tl_iso_payment']['sepa']['holder'],
				'inputType' => 'text',
				'eval'      => array('mandatory' => true)
			),
			'sepa_iban'   => array
			(
				'label'     => &$GLOBALS['TL_LANG']['tl_iso_payment']['sepa']['iban'],
				'inputType' => 'text',
				'eval'      => array('mandatory' => true, 'rgxp' => 'sepa_iban')
			),
			'sepa_bic'    => array
			(
				'label'     => &$GLOBALS['TL_LANG']['tl_iso_payment']['sepa']['bic'],
				'inputType' => 'text',
				'eval'      => array('mandatory' => false, 'rgxp' => 'sepa_bic')
			),
			'nextStep'    => array
			(
				'label'     => &$GLOBALS['TL_LANG']['tl_iso_payment']['sepa']['submit'],
				'inputType' => 'submit'
			)
		);
	}
}