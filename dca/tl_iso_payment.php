<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package   isotope_payment_sepa
 * @author    Michael Gruschwitz <info@grusch-it.de>
 * @license   LGPL
 * @copyright Michael Gruschwitz 2015-2017
 */

$GLOBALS['TL_DCA']['tl_iso_payment']['palettes']['sepa'] = '{type_legend},name,label,type;{note_legend:hide},note;{config_legend},new_order_status,minimum_total,maximum_total,countries,shipping_modules,product_types,product_types_condition,config_ids;{gateway_legend},sepa_persist;{price_legend:hide},price,tax_class;{expert_legend:hide},guests,protected;{enabled_legend},enabled';

$GLOBALS['TL_DCA']['tl_iso_payment']['fields']['sepa_persist'] = array
(
	'label'     => &$GLOBALS['TL_LANG']['tl_iso_payment']['sepa_persist'],
	'exclude'   => true,
	'inputType' => 'checkbox',
	'sql'       => "char(1) NOT NULL default ''",
);
