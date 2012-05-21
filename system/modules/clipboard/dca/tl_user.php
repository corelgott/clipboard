<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  MEN AT WORK 2011
 * @package    clipboard
 * @license    GNU/GPL 2 
 * @filesource
 */

/**
 * Palettes
 */
foreach ($GLOBALS['TL_DCA']['tl_user']['palettes'] as $key => $row)
{
    if ($key == '__selector__')
    {    
        continue;
    }

    $arrPalettes = explode(";", $row);
    $arrPalettes[] = '{clipboard_legend},clipboard,clipboard_context';
    
    $GLOBALS['TL_DCA']['tl_user']['palettes'][$key] = implode(";", $arrPalettes);
}

/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_user']['fields']['clipboard'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['clipboard'],
    'inputType' => 'checkbox',
    'eval' => array('tl_class' => 'clr w50')
);

$GLOBALS['TL_DCA']['tl_user']['fields']['clipboard_context'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['clipboard_context'],
    'inputType' => 'checkbox',
    'eval' => array('tl_class' => 'w50')
);
?>