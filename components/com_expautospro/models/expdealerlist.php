<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');
jimport('joomla.application.component.helper');
require_once JPATH_COMPONENT . '/helpers/expparams.php';

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'tables');

class ExpautosproModelExpdealerlist extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'companyname', 'a.companyname',
                'zipcode', 'a.zipcode',
                'cnt_name', 'cnt.name',
                'st_name', 'st.name',
                'ct_name', 'ct.city_name',
            );
        }
        parent::__construct($config);
    }
/*
    protected function populateState() {
        parent::populateState();
    }
 * 
 */
    
    
    protected function getStoreId($id = '') {
        $id.= ':' . $this->getState('filter.access');
        $id.= ':' . $this->getState('filter.category_id');

        return parent::getStoreId($id);
    }

    function getExpparams() {
        $expparams = ExpAutosProExpparams::getExpParams('config', 1);
        return $expparams;
    }

    function getExpcatfields() {
        $postexpcatid = JRequest::getInt('catid');
        $expcatfields='';
        if($postexpcatid)
        $expcatfields = ExpAutosProExpparams::getCatParams($postexpcatid);
        return $expcatfields;
    }


    function getListQuery() {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $nullDate = $db->quote($db->getNullDate());
        /* EXP Configuration */
        $expparams = $this->getExpparams();
        $explevel = implode(',', $expparams->get('c_admanager_dealerlspage_users'));
        //print_r($explevel);
        
        $userid             = (int)JRequest::getInt('userid', 0);
        $country            = (int)JRequest::getInt('country', 0);
        $expstate           = (int)JRequest::getInt('expstate', 0);
        $city               = (int)JRequest::getInt('city', 0);
        $zipcode            = (int)JRequest::getInt('zipcode', 0);
        $companyname        = (string)JRequest::getString('companyname', 0);
        
        $query->select('a.*');
        $query->from('#__expautos_expuser as a');
        
        $query->select('us.name AS jname, us.username AS jusername, us.email AS jemail');
        $query->join('LEFT', '#__users AS us ON us.id = a.userid');

        $query->select('map2.user_id, COUNT(map2.group_id) AS group_count');
        $query->join('LEFT', '#__user_usergroup_map AS map2 ON map2.user_id = a.userid');
        $query->group('a.userid');

        $query->join('LEFT', '#__usergroups AS g2 ON g2.id = map2.group_id');
        $query->where('map2.group_id IN ('.$explevel.') ');


        
        
        // Country Vehicle
        $query->select('cnt.name AS cnt_name');
        $query->join('LEFT', '#__expautos_country AS cnt ON cnt.id = a.country');
        
        // State Vehicle
        $query->select('st.name AS st_name');
        $query->join('LEFT', '#__expautos_state AS st ON st.id = a.expstate');
        
        // City Vehicle
        $query->select('ct.city_name AS ct_name');
        $query->join('LEFT', '#__expautos_cities AS ct ON ct.id = a.city');
        
        /* SQL Where */
        $query->where('a.state=1');
        if($userid)
        $query->where('a.userid = ' . (int)$userid);
        if($country)
            $query->where('a.country = ' . (int)$country);
        if($expstate)
            $query->where('a.expstate = ' . (int)$expstate);
        if($city)
            $query->where('a.city = ' . (int)$city);
        if($zipcode)
            $query->where('a.zipcode = ' . (int)$zipcode);
        if($companyname)
            $query->where('a.companyname = ' . (string)$companyname);

        
        if($expparams->get('c_admanager_dealerlspage_sortby')== "name"){
            $expgroup = "us.".$expparams->get('c_admanager_dealerlspage_sortby');
        }else{
            $expgroup = "a.".$expparams->get('c_admanager_dealerlspage_sortby');   
        }
        $expsort = $expparams->get('c_admanager_dealerlspage_groupby');
        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if($orderCol)
           $expgroup = $orderCol;
        if($orderDirn)
           $expsort = $orderDirn;

        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
        }

        // Add the list ordering clause.
        if(!$orderCol)
        $query->order('a.utop DESC');
        $query->order($expgroup . ' ' . $expsort);
        return $query;
    }

    public function getExpItemid() {
        $expitemid = ExpAutosProExpparams::getExpLinkItemid();
        return $expitemid;
    }

}