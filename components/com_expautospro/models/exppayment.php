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
require_once JPATH_COMPONENT . '/helpers/expfields.php';

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'tables');

class ExpautosproModelExppayment extends JModelList {

    protected function getStoreId($id = '') {
        $id.= ':' . $this->getState('filter.access');
        $id.= ':' . $this->getState('filter.category_id');

        return parent::getStoreId($id);
    }
    
    
    public function checkexpuser($expuser){
       $return =  ExpAutosProExpparams::expuser($expuser);
       return $return;
    }
    
    public function checkspec($id,$field){
        $user = JFactory::getUser();
        $userid = (int) $user->id;
        $checklevel=$this->getExpgroupfields();
        if($checklevel->get('p_ppricetop') == 0 && $userid && (int)$id && (string)$field){
            $return = ExpAutosProFields::expaddcheck($id,$userid);
            if($return){
                    $db = $this->getDbo();
                    $query = $db->getQuery(true);
                    $query->update('`#__expautos_admanager`');
                    $query->set('`'.$field.'` = 1');
                    $query->where('id = ' . (int) $id);
                    $db->setQuery((string) $query);
                    $db->query();
                    // Check for a database error.
                    if ($db->getErrorNum()) {
                        JError::raiseWarning(500, $db->getErrorMsg());
                        return false;
                    }
                }
                return true;
        }else{
            return false;
        }
    }

    function getExpparams() {
        $expparams = ExpAutosProExpparams::getExpParams('config',1);
        return $expparams;
    }
    
    function getExpgroupid() {
        $user = JFactory::getUser();
        $UserId = (int) $user->id;
        if ($UserId > 0) {
            $usergroups = implode(',', $user->groups);
            $usergroupid = ExpAutosProExpparams::getExpgroupid($usergroups);
        }
        return $usergroupid;
    }

    function getExpgroupfields() {
        $user = JFactory::getUser();
        $UserId = (int) $user->get('id');
        if ($UserId > 0) {
            $listusergroups = implode(',', JAccess::getGroupsByUser($UserId));
            $listusergroupid = ExpAutosProExpparams::getExpgroupid($listusergroups);
            $listgroupparams = ExpAutosProExpparams::getExpParams('userlevel', $listusergroupid);
        }
        return $listgroupparams;
    }

    function getItem() {
        $user = JFactory::getUser();
        if (!$user->id) {
            JError::raiseWarning(500, JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PERMISSION_DENIED_TEXT'));
        }
        if (!isset($this->_item)) {
            $cache = JFactory::getCache('com_expautospro', '');
            $cache->clean('com_expautospro');

            $id = $this->getState('exppayment.id');

            $this->_item = $cache->get($id);

            if ($this->_item === false) {
                $db = $this->getDbo();
                $query = $db->getQuery(true);
                $nullDate = $db->quote($db->getNullDate());

                $postexpid = (int)JRequest::getInt('id');

                $query->select('a.*, a.imgmain AS img_name');
                $query->from('#__expautos_admanager as a');
                $query->where('a.id=' . (int) $postexpid);

                /* Categories */
                /*
                $query->select('c.name AS category_name');
                $query->join('LEFT', '#__expautos_categories AS c ON c.id = a.catid');
                 */
                $query->select('c.title AS category_name, c.id AS categid');
                $query->join('LEFT', '#__categories AS c ON c.id = a.catid');

                /* Makes */
                $query->select('mk.name AS make_name');
                $query->join('LEFT', '#__expautos_make AS mk ON mk.id = a.make');
                //$query->where('mk.state=1');

                /* Models */
                $query->select('md.name AS model_name');
                $query->join('LEFT', '#__expautos_model AS md ON md.id = a.model');
                
                /* Images */
                /* old version 3.5.1
                $query->select('img.name AS img_name');
                $query->join('LEFT', '#__expautos_images AS img ON a.id = img.catid AND img.ordering = 1');
                 */

                $db->setQuery((string) $query);

                if (!$db->query()) {
                    JError::raiseError(500, $db->getErrorMsg());
                }

                $this->_item = $db->loadObject();
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
        parent::populateState('a.id', 'asc');
    }

}