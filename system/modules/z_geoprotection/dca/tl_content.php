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
 * Callbacks 
 */
$GLOBALS['TL_DCA']['tl_content']['config']['onsubmit_callback'][] = array('gp_tl_content', 'updateGroupCountries');

/**
 * Table tl_content
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'gp_protected';
$GLOBALS['TL_DCA']['tl_content']['list']['sorting']['child_record_callback'] = array('gp_tl_content', 'addGpType');

/**
 * Palettes
 */

// replace palettes
foreach ($GLOBALS['TL_DCA']['tl_content']['palettes'] as $palette => $v)
{
    if ($palette == '__selector__')
    {
        continue;
    }
    $GLOBALS['TL_DCA']['tl_content']['palettes'][$palette] = str_replace('{protected_legend:hide}', '{gp_protection_legend:hide},gp_protected;{protected_legend:hide}', $GLOBALS['TL_DCA']['tl_content']['palettes'][$palette]);
}

$GLOBALS['TL_DCA']['tl_content']['subpalettes']['gp_protected'] = 'gp_mode,gp_group_id,gp_fallback,gp_countries';

$GLOBALS['TL_DCA']['tl_content']['fields']['gp_protected'] = array
(
        'label'                   => &$GLOBALS['TL_LANG']['tl_content']['gp_protected'],
        'exclude'                 => true,
        'filter'                  => true,
        'inputType'               => 'checkbox',
        'eval'                    => array('submitOnChange'=>true)
);

$GLOBALS['TL_DCA']['tl_content']['fields']['gp_mode'] = array
(
        'label'                   => &$GLOBALS['TL_LANG']['tl_content']['gp_mode'],
        'default'                 => 'gp_show',
        'exclude'                 => true,
        'inputType'               => 'select',
        'options'                 => array('gp_show', 'gp_hide'),
        'reference'               => &$GLOBALS['TL_LANG']['tl_content'],
        'eval'                    => array('mandatory' => true, 'includeBlankOption' => true, 'tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['gp_fallback'] = array
(
        'label'                   => &$GLOBALS['TL_LANG']['tl_content']['gp_fallback'],
        'exclude'                 => true,
        'filter'                  => true,
        'inputType'               => 'checkbox',
        'eval'                    => array('tl_class' => 'clr')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['gp_countries'] = array
(
        'label'                   => &$GLOBALS['TL_LANG']['tl_content']['gp_countries'],
        'exclude'                 => true,
        'inputType'               => 'checkbox',
        'options_callback'        => array('gp_tl_content','getCountriesByContinent'),
        'eval'                    => array('multiple' => true, 'size' => 8, 'mandatory' => true)
);

$GLOBALS['TL_DCA']['tl_content']['fields']['gp_group_id'] = array
(
        'label'                   => &$GLOBALS['TL_LANG']['tl_content']['gp_group_id'],
        'default'                 => 'show',
        'exclude'                 => true,
        'inputType'               => 'select',
        'options_callback'        => array('gp_tl_content','getGroupIds'),
        'reference'               => &$GLOBALS['TL_LANG']['tl_content'],
        'eval'                    => array('tl_class' => 'w50')
);


/**
 * Class gp_tl_content
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  MEN AT WORK 2012
 * @package    geoprotection
 */
class gp_tl_content extends Controller
{

    /**
        * Add the gp type of the content element
        * @param array
        * @return string
        */
    public function addGpType($arrRow)
    {
            $key = $arrRow['invisible'] ? 'unpublished' : 'published';
            $strGP = '';
            if($arrRow['gp_protected']){
                $strGP = ' ('.$GLOBALS['TL_LANG']['tl_content']['gp_group'].' '.$arrRow['gp_group_id'].', ';
                $strGP .= ($arrRow['gp_mode'] == 'gp_show')? $GLOBALS['TL_LANG']['tl_content']['gp_show'] : $GLOBALS['TL_LANG']['tl_content']['gp_hide'];
                $strGP .= ':';
                $strGP .= ' '.  implode(',', deserialize($arrRow['gp_countries']));
                $strGP .= ')';
                $strGP .= ($arrRow['gp_fallback']) ? ' FALLBACK' : '';
            }

            return '
<div class="cte_type ' . $key . '">' . $GLOBALS['TL_LANG']['CTE'][$arrRow['type']][0] . (($arrRow['type'] == 'alias') ? ' ID ' . $arrRow['cteAlias'] : '') . ($arrRow['protected'] ? ' (' . $GLOBALS['TL_LANG']['MSC']['protected'] . ')' : ($arrRow['guests'] ? ' (' . $GLOBALS['TL_LANG']['MSC']['guests'] . ')' : '')) . $strGP. '</div>
<div class="limit_height' . (!$GLOBALS['TL_CONFIG']['doNotCollapse'] ? ' h64' : '') . ' block">
' . $this->getContentElement($arrRow['id']) . '
</div>' . "\n";
    }

    /**
        * get Country-List
        */
    public function getCountriesByContinent()
    {
        $return = array();
        $countries = array();
        $arrAux = array();
        $arrTmp = array();

        $this->loadLanguageFile('countries');
        $this->loadLanguageFile('continents');
        include(TL_ROOT . '/system/config/countries.php');
        include(TL_ROOT . '/system/config/countriesByContinent.php');

        foreach ($countriesByContinent as $strConKey => $arrCountries){

            $strConKeyTranslated = strlen($GLOBALS['TL_LANG']['CONTINENT'][$strConKey]) ? utf8_romanize($GLOBALS['TL_LANG']['CONTINENT'][$strConKey]) : $strConKey;
            $arrAux[$strConKey] = $strConKeyTranslated;
            foreach ($arrCountries as $key => $strCounntry){
                $arrTmp[$strConKeyTranslated][$key] = strlen($GLOBALS['TL_LANG']['CNT'][$key]) ? utf8_romanize($GLOBALS['TL_LANG']['CNT'][$key]) : $countries[$key];
            }
        }

        ksort($arrTmp);

        foreach ($arrTmp as $strConKey => $arrCountries)
        {	
            asort($arrCountries);
            //get original continent key
            $strOrgKey = array_search($strConKey, $arrAux);
            $strConKeyTranslated = strlen($GLOBALS['TL_LANG']['CONTINENT'][$strOrgKey]) ? ($GLOBALS['TL_LANG']['CONTINENT'][$strOrgKey]) : $strConKey;
            foreach ($arrCountries as $strKey => $strCountry)
            {
                $return[$strConKeyTranslated][$strKey] = strlen($GLOBALS['TL_LANG']['CNT'][$strKey]) ? $GLOBALS['TL_LANG']['CNT'][$strKey] : $countries[$strKey];    
            }

        }
        
        $return[$GLOBALS['TL_LANG']['CONTINENT']['other']]['xx'] = strlen($GLOBALS['TL_LANG']['CNT']['xx']) ? $GLOBALS['TL_LANG']['CNT']['xx'] : 'No Country';
        
        return $return;
    }
        
    public function getGroupIds($dc)
    {
        $this->import('Database');
        $arrGroups = array();
        $objGroups = $this->Database->prepare("SELECT DISTINCT gp_group_id FROM tl_content WHERE pid = (SELECT pid FROM `tl_content` WHERE id = ?) AND gp_protected = 1")
                        ->execute($dc->id);
        while ($objGroups->next())
        {
            if ($objGroups->gp_group_id == 0) continue;
            $arrGroups[$objGroups->gp_group_id] = $GLOBALS['TL_LANG']['tl_content']['gp_group'].' '.$objGroups->gp_group_id;
        }
        
        //add new group option
        $arrGroups['new'] = $GLOBALS['TL_LANG']['tl_content']['gp_newGroup'];
        
        return $arrGroups;
    }
    
    /**
     * @param mixed
     * @param object
     * @return string
     */
    public function updateGroupCountries(DC_Table $dc)
    {
        if ($dc->activeRecord->gp_protected != 1) return;
        $this->import('Database');
        $id = $dc->id;
        $intGroupId = $dc->activeRecord->gp_group_id;
        
        //create new group
        if ($this->Input->post('gp_group_id') == 'new')
        {
            $objGroups = $this->Database->prepare('SELECT DISTINCT gp_group_id FROM tl_content 
                WHERE pid = (SELECT pid FROM `tl_content` WHERE id = ?) 
                AND gp_protected = 1  
                ORDER BY gp_group_id DESC')
                        ->limit(1)
                        ->execute($dc->id);
            
            $intGroupId = ($objGroups->gp_group_id +1);
            $this->Database->prepare('UPDATE tl_content SET gp_group_id = ? WHERE id = ?')
                    ->execute($intGroupId, $dc->id);
        }

        $arrCGroups = array();
        //get group countries
        $objCGroups = $this->Database->prepare('SELECT DISTINCT gp_countries FROM tl_content 
            WHERE pid = (SELECT pid FROM `tl_content` WHERE id = ?) 
            AND gp_protected = 1 AND id != ? 
            ORDER BY gp_group_id DESC')
                        ->execute($dc->id, $dc->id);
        
        //build country array of this group
        while ($objCGroups->next())
        {
            $arrCGroups = array_merge(deserialize($objCGroups->gp_countries),$arrCGroups) ;
        }
        //add countries from current record
        $arrCGroups = array_merge($arrCGroups, deserialize($dc->activeRecord->gp_countries));
        $arrCGroups = array_unique($arrCGroups);
        
        //update gp_group_countries
        $this->Database->prepare('UPDATE tl_content SET gp_group_countries = ? WHERE pid = ? AND gp_group_id = ?')
                    ->execute((serialize($arrCGroups)),$dc->activeRecord->pid , $intGroupId);
    }
        
}

?>