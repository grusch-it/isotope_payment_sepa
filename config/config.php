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
 * Checkout Form Validator
 */
$GLOBALS['TL_HOOKS']['addCustomRegexp'][] = array('Gruschit\SepaValidator', 'validate');
