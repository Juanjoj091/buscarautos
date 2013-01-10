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

class ExpautosproModelExpdealerdetail extends JModelList {

    protected function getStoreId($id = '') {
        $id.= ':' . $this->getState('filter.access');
        $id.= ':' . $this->getState('filter.category_id');

        return parent::getStoreId($id);
    }

    function getExpparams() {
        $expparams = ExpAutosProExpparams::getExpParams('config', 1);
        return $expparams;
    }
    
    function getExpgroup() {
        $listusergroupid = '';
        if($this->_item['userid']>0){
            jimport( 'joomla.user.helper' );
            //$listusergroups =  implode(',', JAccess::getGroupsByUser($this->_item['userid']));
            $listusergroups =  implode(',', JUserHelper::getUserGroups($this->_item['userid']));
            $listusergroupid = ExpAutosProExpparams::getExpgroupname($listusergroups);
        }
        return $listusergroupid;
    }
    
    function getExpgroupfields() {
        $listgroupparams = '';
        if($this->_item['userid']>0){
            //$listusergroups =  implode(',', JAccess::getGroupsByUser($this->_item['userid']));
            $listusergroups =  implode(',', JUserHelper::getUserGroups($this->_item['userid']));
            $listusergroupid = ExpAutosProExpparams::getExpgroupid($listusergroups);
            $listgroupparams = ExpAutosProExpparams::getExpParams('userlevel',$listusergroupid);
        }
        return $listgroupparams;
    }
    
    function getItem() {
        if (!isset($this->_item)) {
            $cache = JFactory::getCache('com_expautospro', '');
            $cache->clean('com_expautospro');

            $id = $this->getState('expdealerdetail.id');
            $postid = JRequest::getInt('id', 0);

            $this->_item = $cache->get($id);

            if ($this->_item === false) {
                $db = $this->getDbo();
                $query = $db->getQuery(true);
                $nullDate = $db->quote($db->getNullDate());

                $postexpuserid = JRequest::getInt('userid');

                $query->select('a.*');
                $query->from('#__expautos_expuser as a');
                $query->where('a.state=1');
                $query->where('a.userid=' . (int) $postexpuserid);

                /* User */
                $query->select('user.name AS user_name, user.username AS user_username, user.email AS user_email');
                $query->join('LEFT', '#__users AS user ON user.id = a.userid');
                
                $query->select('expusercountry.name AS expuser_country, expusercity.city_name AS expuser_city');
                $query->select('expuserstate.name AS expuser_state');
                $query->join('LEFT', '#__expautos_country AS expusercountry ON expusercountry.id = a.country');
                $query->join('LEFT', '#__expautos_state AS expuserstate ON expuserstate.id = a.expstate');
                $query->join('LEFT', '#__expautos_cities AS expusercity ON expusercity.id = a.city');

                /* EXP Configuration */
                $expparams = $this->getExpparams();

                $db->setQuery((string) $query);

                if (!$db->query()) {
                    JError::raiseError(500, $db->getErrorMsg());
                }

                $this->_item = $db->loadAssoc();
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
        
    }

    public function getExpItemid() {
        $expitemid = ExpAutosProExpparams::getExpLinkItemid();
        return $expitemid;
    }

}