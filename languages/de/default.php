<?php if (!defined('TL_ROOT')) {
    die('You cannot access this file directly!');
}

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Michael Gruschwitz 2014
 * @author     Michael Gruschwitz <info@grusch-it.de>
 * @package    Isotope
 * @subpackage Payment
 * @license    LGPL
 * @filesource
 */

/**
 * Payment modules
 */
$GLOBALS['ISO_LANG']['PAY']['sepa'] = array
(
    'SEPA-Lastschrift',
    'Wählen Sie diese Zahlungsmethode um die Kontodaten des Kunden bei einer neuen Bestellung abzufragen.'
);

/**
 * SEPA labels
 */
$GLOBALS['TL_LANG']['ISO']['sepa_holder'] = 'Kontoinhaber';
$GLOBALS['TL_LANG']['ISO']['sepa_iban'] = 'IBAN';
$GLOBALS['TL_LANG']['ISO']['sepa_bic'] = 'BIC';

/**
 * Error messages
 */
$GLOBALS['TL_LANG']['ERR']['sepa'] = array
(
    'iban_country' => 'Die eingegebene IBAN enthält kein oder ein ungültiges Länderkürzel',
    'iban_length' => 'Die eingebene IBAN ist zu lang oder zu kurz!',
    'iban_invalid' => 'Bitte geben Sie eine gültige IBAN ein!',
    'bic_invalid' => 'Bitte geben Sie eine gültige BIC ein!'
);