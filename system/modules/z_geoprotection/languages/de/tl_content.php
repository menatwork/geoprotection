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
$GLOBALS['TL_LANG']['tl_content']['gp_protected']           = array('Geoprotection aktivieren','Das Inhaltselement nur in bestimmten Ländern anzeigen oder verstecken.');
$GLOBALS['TL_LANG']['tl_content']['gp_mode']                = array('Sichtbarkeit','Bitte wählen Sie ob das Inhaltselement angezeigt oder versteckt werden soll.');
$GLOBALS['TL_LANG']['tl_content']['gp_countries']           = array('Länder','Bitte wählen Sie ein oder mehrere Länder aus.');
$GLOBALS['TL_LANG']['tl_content']['gp_group_id']            = array('Gruppe','Gruppe des Elements.');
$GLOBALS['TL_LANG']['tl_content']['gp_fallback']            = array('Fallback-Element','Dieses Element ist ein Fallback-Element für die Gruppe. Das Fallback greift, wenn das Land des Users nicht in der Gruppe vorhanden ist.');

/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_content']['gp_hide']                = 'Verstecken';
$GLOBALS['TL_LANG']['tl_content']['gp_show']                = 'Anzeigen';
$GLOBALS['TL_LANG']['tl_content']['gp_newGroup']            = 'Neue Gruppe';
$GLOBALS['TL_LANG']['tl_content']['gp_group']               = 'Gruppe';