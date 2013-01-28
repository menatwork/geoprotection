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