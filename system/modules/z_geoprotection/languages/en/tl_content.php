<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2013 
 * @package    geoprotection
 * @license    GNU/LGPL
 * @filesource
 */

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_content']['gp_protection_legend']   = 'Geoprotection';
                                                            
/**                                                         
 * Fields                                                   
 */                                                         
$GLOBALS['TL_LANG']['tl_content']['gp_protected']           = array('Enable Geoprotection','Show or hide the content element in selected countries.');
$GLOBALS['TL_LANG']['tl_content']['gp_mode']                = array('Visibility','Please choose the visibility of the content element.');
$GLOBALS['TL_LANG']['tl_content']['gp_countries']           = array('Countries','Please select one or more countries.');
$GLOBALS['TL_LANG']['tl_content']['gp_group_id']            = array('Group','Group of the elements');
$GLOBALS['TL_LANG']['tl_content']['gp_fallback']            = array('Fallback Element','This element acts as a fallback for this group. The fallback will be used, if the users country is not assigned to one element of the group.');

/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_content']['gp_hide']                = 'Hide';
$GLOBALS['TL_LANG']['tl_content']['gp_show']                = 'Show';
$GLOBALS['TL_LANG']['tl_content']['gp_newGroup']            = 'New group';
$GLOBALS['TL_LANG']['tl_content']['gp_group']               = 'Group';