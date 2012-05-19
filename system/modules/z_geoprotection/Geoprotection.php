<?php
if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * @copyright  MEN AT WORK 2012
 * @package    Geoprotection
 * @license    GNU/LGPL
 * @filesource
 */

/**
 * Class Geoproptection
 *
 * Provide methods for geo protection
 * @copyright  MEN AT WORK 2012
 * @package    Geoprotection
 */
class Geoprotection extends Frontend
{

    /**
     * IP cache array
     * @var array
     */
    private static $arrIPCache = array();

    public function checkPermission($objElement, $strBuffer)
    {
        //check if geoprotection is enabled
        if ($objElement->gp_protected && TL_MODE != 'BE')
        {
            
            $objGeo = Geolocation::getInstance()->getUserGeolocation();
            $country = ($objGeo->getCountryShort() != '') ? $objGeo->getCountryShort() : 'xx';
            
            //the geoContainser has a country and matches one of the group countries
            if (in_array($country, deserialize($objElement->gp_group_countries)))
            {
                if ($objElement->gp_mode == "gp_show")
                {
                    return (in_array($country, deserialize($objElement->gp_countries))) ? $strBuffer : '';
                }
                return (in_array($country, deserialize($objElement->gp_countries))) ? '' : $strBuffer;
            }
            else
            {
                //use Fallback
                return (($objElement->gp_mode == "gp_show" && $objElement->gp_fallback) ||
                        (!$objElement->gp_mode == "gp_show" && !$objElement->gp_fallback))? $strBuffer : '';                
            }
        }

        return $strBuffer;
    }

}

?>