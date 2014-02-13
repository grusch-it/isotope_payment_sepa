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
 * Class PaymentSepaHelper
 *
 * Provides hook method to inject SEPA payment data to the order confirmation emails.
 *
 * @copyright  Michael Gruschwitz 2014
 * @author     Michael Gruschwitz <info@grusch-it.de>
 * @package    Isotope
 * @subpackage Payment
 */
class PaymentSepaHelper
{
    /**
     * Injects SEPA payment data to the order confirmation emails
     *
     * @param IsotopeOrder $objOrder
     * @param array $arrData
     * @return array
     */
    public function onGetOrderEmailData(IsotopeOrder $objOrder, array $arrData)
    {
        // check if selected payment method is SEPA
        if (!($objOrder->Payment instanceof PaymentSepa)) {
            return;
        }

        // get payment module id
        $intId = $objOrder->Payment->id;

        // get sepa payment data from session
        $holder = $_SESSION['CHECKOUT_DATA']['payment'][$intId]['sepa_holder'];
        $iban = $_SESSION['CHECKOUT_DATA']['payment'][$intId]['sepa_iban'];
        $bic = $_SESSION['CHECKOUT_DATA']['payment'][$intId]['sepa_bic'];

        // build SEPA data array
        $arrSepaData = array(
            'sepa_holder' => $holder,
            'sepa_iban' => $iban,
            'sepa_iban_masked' => $objOrder->Payment->getMaskedIban($iban),
            'sepa_bic' => $bic
        );

        return array_merge($arrData, $arrSepaData);
    }
}