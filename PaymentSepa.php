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
 * Class PaymentSepa
 *
 * Isotope SEPA payment module.
 *
 * @copyright  Michael Gruschwitz 2014
 * @author     Michael Gruschwitz <info@grusch-it.de>
 * @package    Isotope
 * @subpackage Payment
 *
 * @property   int id
 * @property   string label
 */
class PaymentSepa extends IsotopePayment
{
    /**
     * @var string
     */
    protected $strReviewTemplate = 'iso_payment_sepa_review';

    /**
     * @var FrontendTemplate
     */
    protected $reviewTemplate;

    /**
     * Process checkout payment
     *
     * @access public
     * @return boolean
     */
    public function processPayment()
    {
        $objOrder = new IsotopeOrder();

        if (!$objOrder->findBy('cart_id', $this->Isotope->Cart->id)) {
            return false;
        }

        $objOrder->updateOrderStatus($this->new_order_status);

        return true;
    }

    /**
     * Get payment form
     *
     * @param ModuleIsotopeCheckout $objCheckoutModule
     * @return string
     */
    public function paymentForm($objCheckoutModule)
    {
        // payment post input
        $arrPaymentData = $this->Input->post('payment');

        // payment form fields
        $arrFields = $this->getPaymentFormFields();

        // form html string
        $strBuffer = '';

        foreach ($arrFields as $strFieldName => $arrField) {

            // input class
            $strClass = $GLOBALS['TL_FFL'][$arrField['inputType']];

            // input class not found
            if (!$this->classFileExists($strClass)) {
                continue;
            }

            // input name ( payment[<paymentModuleID>][<fieldName>]
            $strFieldFinalName = sprintf('payment[%s][%s]', $this->id, $strFieldName);

            /** @var $objWidget Widget */
            $objWidget = new $strClass($this->prepareForWidget(
                $arrField,
                $strFieldFinalName,
                $_SESSION['CHECKOUT_DATA']['payment'][$this->id][$strFieldName]
            ));

            // validate post input
            if ($this->Input->post(
                    'FORM_SUBMIT'
                ) == 'iso_mod_checkout_payment' && $arrPaymentData['module'] == $this->id
            ) {
                $objWidget->validate();

                if ($objWidget->hasErrors()) {
                    $objCheckoutModule->doNotSubmit = $objCheckoutModule->doNotSubmit || true;
                }
            }

            $strBuffer .= $objWidget->parse();
        }

        // add error message
        if (strlen($_SESSION['CHECKOUT_DATA']['payment'][$this->id]['error'])) {
            // prepend error to form html
            $strError = sprintf('<p class="error">%s</p>', $_SESSION['CHECKOUT_DATA']['payment'][$this->id]['error']);
            $strBuffer = $strError . $strBuffer;

            unset($_SESSION['CHECKOUT_DATA']['payment'][$this->id]['error']);
        }

        return $strBuffer;
    }

    /**
     * Display provided SEPA account data
     *
     * @return string
     */
    public function checkoutReview()
    {
        $holder = $_SESSION['CHECKOUT_DATA']['payment'][$this->id]['sepa_holder'];
        $iban = $_SESSION['CHECKOUT_DATA']['payment'][$this->id]['sepa_iban'];
        $bic = $_SESSION['CHECKOUT_DATA']['payment'][$this->id]['sepa_bic'];

        $this->reviewTemplate = new FrontendTemplate($this->strReviewTemplate);
        $this->reviewTemplate->label = $this->label;
        $this->reviewTemplate->holder = $holder;
        $this->reviewTemplate->iban = $iban;
        $this->reviewTemplate->iban_masked = $this->getMaskedIban($iban);
        $this->reviewTemplate->bic = $bic;

        return $this->reviewTemplate->parse();
    }

    /**
     * Get masked iban code
     *
     * The country code and the first 4 and last 4 digits will be preserved.
     * The rest will be replaced by X letter.
     *
     * @param string $iban
     * @return string
     */
    public function getMaskedIban($iban)
    {
        $normalized = str_replace(' ', '', $iban);
        $cutted = preg_replace('/^([a-z]{2}[0-9]{4})([0-9]+)([0-9]{4})$/i', '\1\3', $normalized);

        $first = substr($cutted, 0, 6);
        $middle = str_repeat('X', strlen($normalized) - 10);
        $last = substr($cutted, 6);

        return $first . $middle . $last;
    }

    /**
     * Get the payment form field definitions
     *
     * @return array
     */
    protected function getPaymentFormFields()
    {
        return array
        (
            'sepa_holder' => array
            (
                'label' => &$GLOBALS['TL_LANG']['ISO']['sepa_holder'],
                'inputType' => 'text',
                'eval' => array('mandatory' => true, 'tableless' => true)
            ),
            'sepa_iban' => array
            (
                'label' => &$GLOBALS['TL_LANG']['ISO']['sepa_iban'],
                'inputType' => 'text',
                'eval' => array('mandatory' => true, 'rgxp' => 'sepa_iban', 'tableless' => true)
            ),
            'sepa_bic' => array
            (
                'label' => &$GLOBALS['TL_LANG']['ISO']['sepa_bic'],
                'inputType' => 'text',
                'eval' => array('mandatory' => false, 'rgxp' => 'sepa_bic', 'tableless' => true)
            )
        );
    }
}
