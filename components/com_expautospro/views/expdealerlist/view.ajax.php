<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

// No direct access to this file
defined('_JEXEC') or die;

// import Joomla view library
jimport('joomla.application.component.view');
require_once JPATH_COMPONENT.'/helpers/expfields.php';
require_once JPATH_COMPONENT.'/helpers/expparams.php';

class ExpautosproViewExpdealerlist extends JView {

    public function display($tpl = null) {

        $country = JRequest::getInt('expgdcountry', 0);
        $expstate = JRequest::getInt('expgdstate', 0);
        $city = JRequest::getInt('expgdcity', 0);
        $expuserid = (string) JRequest::getString('expgduserid', 0);
        $expzipcode = (string) JRequest::getString('expgdzipcode', 0);
        if($expzipcode){
            $expzipcode = JFilterOutput::cleanText($expzipcode);
            $expzipcode = str_replace(' ', '', $expzipcode);
        }
        
        $this->ajax_googledealers($country,$expstate,$city,$expuserid,$expzipcode);
        
    }
    
    public function ajax_googledealers($country=0,$expstate=0,$city=0,$expuserid=0,$expzipcode=''){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $expparams = ExpAutosProExpparams::getExpparams('config',1);
        $explevel = implode(',', $expparams->get('c_admanager_dealerlspage_users'));

        $query->select('a.*');
        $query->from('#__expautos_expuser AS a');
        
        // Country Vehicle
        $query->select('cnt.name AS cnt_name');
        $query->join('LEFT', '#__expautos_country AS cnt ON cnt.id = a.country');

        // State Vehicle
        $query->select('st.name AS st_name');
        $query->join('LEFT', '#__expautos_state AS st ON st.id = a.expstate');

        // City Vehicle
        $query->select('ct.city_name AS ct_name');
        $query->join('LEFT', '#__expautos_cities AS ct ON ct.id = a.city');

        
        $query->select('us.id AS value, us.username AS text, us.name AS jname, us.email AS jemail');
        $query->join('LEFT', '#__users AS us ON us.id = a.userid');
        
         /* Dealer by config */
        $query->select('map2.user_id, COUNT(map2.group_id) AS group_count');
        $query->join('LEFT', '#__user_usergroup_map AS map2 ON map2.user_id = a.userid');
        $query->group('a.userid');

        $query->join('LEFT', '#__usergroups AS g2 ON g2.id = map2.group_id');
        $query->where('map2.group_id IN ('.$explevel.') ');
        
        $query->where('a.state = 1');
        $query->where('a.latitude != 0');
        $query->where('a.longitude != 0');
        if((int)$country)
            $query->where('a.country = '.(int)$country);
        if((int)$expstate)
            $query->where('a.expstate = '.(int)$expstate);
        if((int)$city)
            $query->where('a.city = '.(int)$city);
        if((int)$expuserid)
            $query->where('a.userid = '.(int)$expuserid);
        if ($expzipcode>0)
            $query->where('a.zipcode = ' . $db->Quote($expzipcode));
        $query->order('a.id');
        // Get the options.
        $db->setQuery($query);
        $returnval = $db->loadObjectList();
        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }
        echo json_encode($returnval);
    }

}
