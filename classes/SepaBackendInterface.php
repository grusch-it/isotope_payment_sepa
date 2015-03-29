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

use Contao\Environment;
use Contao\Frontend;
use Isotope\Interfaces\IsotopePayment;
use Isotope\Model\Payment;
use Isotope\Template;

class SepaBackendInterface extends Frontend {

	/**
	 * @var string
	 */
	protected $strTemplate = 'be_iso_payment_sepa';

	/**
	 * @var Template
	 */
	protected $Template;

	/**
	 * Create new backend interface.
	 *
	 * @param SepaPaymentBag $objPaymentBag
	 * @param IsotopePayment $objPayment
	 */
	public function __construct(SepaPaymentBag $objPaymentBag, IsotopePayment $objPayment)
	{
		$this->Template = new Template($this->strTemplate);
		$this->Template->back = $GLOBALS['TL_LANG']['MSC']['backBT'];
		$this->Template->backHref = ampersand(str_replace('&key=payment', '', Environment::get('request')));
		$this->Template->data = $objPaymentBag->all();
		$this->Template->name = $objPayment->name;
	}

	/**
	 * Parse the template
	 *
	 * @return string
	 */
	public function generate()
	{
		return $this->Template->parse();
	}

}