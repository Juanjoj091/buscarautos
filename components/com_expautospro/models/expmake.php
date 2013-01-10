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

class ExpautosproModelExpmake extends JModelList {

    protected function getStoreId($id = '') {
        $id.= ':' . $this->getState('filter.access');
        $id.= ':' . $this->getState('filter.category_id');

        return parent::getStoreId($id);
    }

    function getExpparams() {
        $expparams = ExpAutosProExpparams::getExpParams('config',1);
        return $expparams;
    }

    function getItem() {
        if (!isset($this->_item)) {
            $cache = JFactory::getCache('com_expautospro', '');
            $cache->clean('com_expautospro');

            $id = $this->getState('expmake.id');

            $this->_item = $cache->get($id);

            if ($this->_item === false) {
                $user	= JFactory::getUser();
                $groups	= implode(',', $user->getAuthorisedViewLevels());
                $db = $this->getDbo();
                $query = $db->getQuery(true);
                $nullDate = $db->quote($db->getNullDate());

                $postexpcat = JRequest::getInt('catid');

                $query->select(
                        'a.id as id,' .
                        'a.catid as catid,' .
                        'a.name as name,' .
                        'a.description as description,' .
                        'a.metakey as metakey,' .
                        'a.metadesc as metadesc,' .
                        'a.image as image'
                );
                $query->from('#__expautos_make as a');
                $query->where('a.state=1');
                $query->where('a.catid=' . (int) $postexpcat);

                /* Categories */
                /*
                $query->select('c.name AS category_name');
                $query->join('LEFT', '#__expautos_categories AS c ON c.id = a.catid');
                */
                
                $query->select('c.title AS category_name');
                $query->join('LEFT', '#__categories AS c ON c.id = a.catid');

                /* Access */
                $query->select('ag.title AS access_level');
                $query->join('LEFT', '#__viewlevels AS ag ON ag.id = c.access');
                $query->where('c.access IN ('.$groups.')');

                /* EXP Configuration */
                $expparams = $this->getExpparams();
                $expgroup = $expparams->get('c_admanager_mkpage_groupby');
                $expsort = $expparams->get('c_admanager_mkpage_sortby');

                $query->order('a.'.$expgroup.' '.$expsort);

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
        parent::populateState('a.name', 'asc');
    }

    public function getExpItemid() {
        $expitemid = ExpAutosProExpparams::getExpLinkItemid();
        return $expitemid;
    }

}