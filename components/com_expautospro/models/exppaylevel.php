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

class ExpautosproModelExppaylevel extends JModelList {

    function getExpparams() {
        $expparams = ExpAutosProExpparams::getExpParams('config',1);
        return $expparams;
    }
    
    function getExpgroup() {
        $listusergroupid ='';
        if($this->_item['userid']>0){
        $listusergroups =  implode(',', JAccess::getGroupsByUser($this->_item['userid']));
        //print_r($listusergroups."---huyiiyuyui---");
        $listusergroupid = ExpAutosProExpparams::getExpgroupname($listusergroups);
        }
        return $listusergroupid;
    }
    
    function getExpgroupfields() {
        $listgroupparams ='';
        if($this->_item['userid']>0){
        $listusergroups =  implode(',', JAccess::getGroupsByUser($this->_item['userid']));
        print_r($listusergroups."---huyiiyuyui---");
        $listusergroupid = ExpAutosProExpparams::getExpgroupid($listusergroups);
        $listgroupparams = ExpAutosProExpparams::getExpParams('userlevel',$listusergroupid);
        }
        return $listgroupparams;
    }
    
    function getItem() {
        if (!isset($this->_item)) {
            $cache = JFactory::getCache('com_expautospro', '');
            $cache->clean('com_expautospro');

            $id = $this->getState('expautospro.id');

            $this->_item = $cache->get($id);

            if ($this->_item === false) {
                $db = $this->getDbo();
                $query = $db->getQuery(true);
                $nullDate = $db->quote($db->getNullDate());

                $query->select('a.*');
                $query->from('#__expautos_userlevel as a');
                $query->where('a.state=1');
                $query->select('ug.title AS group_title');
                $query->join('LEFT', '#__usergroups AS ug ON ug.id = a.userlevel');

                $query->order('a.ordering ASC');

                $db->setQuery((string) $query);

                if (!$db->query()) {
                    JError::raiseError(500, $db->getErrorMsg());
                }

                $this->_item = $db->loadObjectList();
                $cache->store($this->_item, $id);
            }
        }

        return $this->_item;
    }
    
    function getItems() {
        if (!isset($this->cache['items'])) {
            $this->cache['items'] = parent::getItems();

            foreach ($this->cache['items'] as &$item) {
                $parameters = new JRegistry;
                $parameters->loadJSON($item->params);
                $item->params = $parameters;
            }
        }
        return $this->cache['items'];
    }

    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');
        // List state information.
        parent::populateState('a.ordering', 'asc');
    }
    

}