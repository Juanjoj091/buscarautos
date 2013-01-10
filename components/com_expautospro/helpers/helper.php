<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

class ExpAutosProHelper {
    

    public static function getExpcount($database, $name='', $val=0,$state=1, $exptable='expautos_') {
        if(!$database){
            $database='admanager';
        }
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('count(id)');
        $query->from('#__'.$exptable.$database);
        if($state){
        $query->where('state = 1');
        }
        if($val && $name){
        $query->where($name . ' = ' . $val);
        }

        $db->setQuery($query);

        $result = $db->loadResult();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        return $result;
    }

    public static function load_module_position($position, $style = 'xhtml',$expitem=0) {
        
        $document = JFactory::getDocument();
        $renderer = $document->loadRenderer('module');
        $modules = JModuleHelper::getModules($position);
        $params = array('name' => $position,'style' => $style,'expitem' => $expitem);
        $contents = '';
        foreach ($modules as $module) {
            if($expitem){
                $contents .= $renderer->render($module, $params,$expitem);
            }else{
                $contents .= $renderer->render($module, $params);
            }
        }
        //print_r($expitem);
        return $contents;
    }
    
    public static function exppricename(){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('cname');
        $query->from('#__expautos_currency');
        $query->where('state = 1');
        $query->where('exchange = 1');
        $db->setQuery($query);
        $result = $db->loadResult();
        
        return $result;
    }
    
    public static function expskin_lang($skin,$skin_name) {
        $lang = JFactory::getLanguage();
        $lang_exists = $lang->exists($lang->getDefault(),JPATH_BASE.'/components/com_expautospro/skins/'.$skin.DS.$skin_name);
        if($lang_exists)
        $lang->load($skin_name, JPATH_BASE.'/components/com_expautospro/skins/'.$skin.DS.$skin_name, $lang->getDefault(), false, false);
    }

    public static function getExpdataImg($expcatid) {
            $table = JTable::getInstance('Expimages', $prefix = 'ExpautosproTable');
            $table->reorder('catid = '.(int)$expcatid);
        
            $imgcount = ExpAutosProHelper::getExpcount('images', 'catid', $expcatid);
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('name');
            $query->from('#__expautos_images');
            $query->where('catid = '.(int)$expcatid);
            $query->where('ordering = 1');
            $db->setQuery($query);
            $mainimage = $db->loadResult();
            if ($db->getErrorNum()) {
                JError::raiseWarning(500, $db->getErrorMsg());
            }
            $mainimage = ($mainimage ? $mainimage : '');
            $obj = new stdClass();
            $obj->id = (int) $expcatid;
            $obj->imgcount = (int)$imgcount;
            $obj->imgmain = $mainimage;
            $db->updateObject('#__expautos_admanager', $obj, 'id');
            
            return $mainimage;
    }

}

?>
