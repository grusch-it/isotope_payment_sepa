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

/**
 * Module Name & Description
 */
$GLOBALS['TL_LANG']['MODEL']['tl_iso_payment']['sepa'] = array(
	'SEPA',
	'Collects bank account data from the customer.'
);

/**
 * Checkout Form Validation Errors
 */
$GLOBALS['TL_LANG']['ERR']['sepa']['iban_country'] = 'Please enter an IBAN with a valid country code!';
$GLOBALS['TL_LANG']['ERR']['sepa']['iban_length'] = 'The entered IBAN is either too short or too long!';
$GLOBALS['TL_LANG']['ERR']['sepa']['iban_invalid'] = 'Please enter a valid IBAN!';
$GLOBALS['TL_LANG']['ERR']['sepa']['bic_invalid'] = 'Please enter a valid BIC!';

/**
 * Checkout Form
 */
$GLOBALS['TL_LANG']['tl_iso_payment']['sepa_data'] = 'Account data';
$GLOBALS['TL_LANG']['tl_iso_payment']['sepa_holder'] = 'Account owner';
$GLOBALS['TL_LANG']['tl_iso_payment']['sepa_iban'] = 'IBAN';
$GLOBALS['TL_LANG']['tl_iso_payment']['sepa_bic'] = 'BIC';
$GLOBALS['TL_LANG']['tl_iso_payment']['sepa_submit'] = 'Submit';

/**
 * Payment Backend Module Labels
 */
$GLOBALS['TL_LANG']['tl_iso_payment']['sepa_persist'][0] = 'Kontodaten in der Datenbank speichern';
$GLOBALS['TL_LANG']['tl_iso_payment']['sepa_persist'][1] = 'Die Speicherung der IBAN erfolgt verschlüsselt. Die Kontodaten können auch ohne gespeichert zu werden nach Abschluss einer Bestellung per E-Mail verschickt werden.';
