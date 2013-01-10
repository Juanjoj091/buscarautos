<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class ExpAutosProModelExpuserlevels extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'userlevel', 'a.userlevel', 'userlevel_name',
                'state', 'a.state',
                'ordering', 'a.ordering',
                'catid', 'a.catid', 'category_title',
            );
        }

        parent::__construct($config);
    }

    protected function getListQuery() {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select(
                $this->getState(
                        'list.select', 'a.id AS id, a.userlevel AS userlevel,' .
                        'a.catid AS catid,' .
                        'a.state AS state, a.ordering AS ordering'
                )
        );
        $query->from('`#__expautos_userlevel` AS a');

        $query->select('ug.title AS userlevel_name');
        $query->join('LEFT', '#__usergroups AS ug ON ug.id = a.userlevel');
 
        if ($groupId = $this->getState('filter.group_id')) {
            $query->where('a.userlevel = ' . (int) $groupId);
        }
 
        $published = $this->getState('filter.state');

        if (is_numeric($published)) {
            $query->where('a.state = ' . (int) $published);
        } else if ($published === '') {
            $query->where('(a.state IN (0, 1))');
        }

        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->getEscaped($search, true) . '%');
                $query->where('(ag.title LIKE ' . $search . ')');
            }
        }

        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol == 'a.ordering') {
            $orderCol = 'a.ordering';
        }
        $query->order($db->getEscaped($orderCol . ' ' . $orderDirn));
        return $query;
    }

    protected function getStoreId($id = '') {
        $id.= ':' . $this->getState('filter.search');
        //$id.= ':' . $this->getState('filter.access');
        $id.= ':' . $this->getState('filter.state');
	$id.= ':' . $this->getState('filter.group_id');

        return parent::getStoreId($id);
    }

    public function getTable($type = 'Expuserlevel', $prefix = 'ExpautosproTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    protected function populateState($ordering = null, $direction = null) {

        $app = JFactory::getApplication('administrator');

        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $state = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
        $this->setState('filter.state', $state);
/*
        $accessId = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access', null, 'int');
        $this->setState('filter.access', $accessId);
 */

        $groupId = $this->getUserStateFromRequest($this->context.'.filter.group', 'filter_group_id', null, 'int');
        $this->setState('filter.group_id', $groupId);

        $published = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);

        $params = JComponentHelper::getParams('com_expautospro');
        $this->setState('params', $params);

        parent::populateState('a.userlevel', 'asc');
    }

}