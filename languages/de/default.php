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
	'SEPA-Lastschrift',
	'Fragt bei der Bestellung die Kontodaten (Inhaber, IBAN und BIC) des Kunden ab.'
);

/**
 * Checkout Form Validation Errors
 */
$GLOBALS['TL_LANG']['ERR']['sepa']['iban_country'] = 'Die eingegebene IBAN enthält kein oder ein ungültiges Länderkürzel!';
$GLOBALS['TL_LANG']['ERR']['sepa']['iban_length'] = 'Die eingebene IBAN ist zu lang oder zu kurz!';
$GLOBALS['TL_LANG']['ERR']['sepa']['iban_invalid'] = 'Bitte geben Sie eine gültige IBAN ein!';
$GLOBALS['TL_LANG']['ERR']['sepa']['bic_invalid'] = 'Bitte geben Sie eine gültige BIC ein!';

/**
 * Checkout Form
 */
$GLOBALS['TL_LANG']['tl_iso_payment']['sepa_data'] = 'Kontodaten';
$GLOBALS['TL_LANG']['tl_iso_payment']['sepa_holder'] = 'Kontoinhaber';
$GLOBALS['TL_LANG']['tl_iso_payment']['sepa_iban'] = 'IBAN';
$GLOBALS['TL_LANG']['tl_iso_payment']['sepa_bic'] = 'BIC';
$GLOBALS['TL_LANG']['tl_iso_payment']['sepa_submit'] = 'Absenden';

/**
 * Payment Backend Module Labels
 */
$GLOBALS['TL_LANG']['tl_iso_payment']['sepa_persist'][0] = 'Kontodaten in der Datenbank speichern';
$GLOBALS['TL_LANG']['tl_iso_payment']['sepa_persist'][1] = 'Die Speicherung der IBAN erfolgt verschlüsselt. Die Kontodaten können auch ohne gespeichert zu werden nach Abschluss einer Bestellung per E-Mail verschickt werden.';
