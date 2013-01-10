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


class ExpautosproModelExpautospro extends JModelList {

    protected function getStoreId($id = '') {
        $id.= ':' . $this->getState('filter.access');
        $id.= ':' . $this->getState('filter.category_id');

        return parent::getStoreId($id);
    }

    function getExpparams() {
        $expparams = ExpAutosProExpparams::getExpParams('config',1);
        return $expparams;
    }

    public function getExpItemid() {
        $expitemid = ExpAutosProExpparams::getExpLinkItemid();
        return $expitemid;
    }

    function getItem() {
        if (!isset($this->_item)) {
            $cache = JFactory::getCache('com_expautospro', '');
            $cache->clean('com_expautospro');
            $user	= JFactory::getUser();
            $groups	= implode(',', $user->getAuthorisedViewLevels());

            $id = $this->getState('expautospro.id');

            $this->_item = $cache->get($id);

            if ($this->_item === false) {
                $db = $this->getDbo();
                $query = $db->getQuery(true);
                $nullDate = $db->quote($db->getNullDate());

                $query->select(
                        'a.id as id,' .
                        'a.name as name,' .
                        'a.alias as alias,' .
                        'a.description as description,' .
                        'a.metakey as metakey,' .
                        'a.metadesc as metadesc,' .
                        'a.metadata as metadata,' .
                        'a.params as params,' .
                        'a.image as image'
                );
                $query->from('#__expautos_categories as a');
                $query->where('a.state=1');
                
                /* Access */
                $query->select('ag.title AS access_level');
                $query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');
                $query->where('a.access IN ('.$groups.')');
                /* EXP Configuration */
                $expparams = $this->getExpparams();
                $expgroup = $expparams->get('c_admanager_fpcat_groupby');
                $expsort = $expparams->get('c_admanager_fpcat_sortby');

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

}