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

class ExpAutosProModelExpusers extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'lastname', 'a.lastname',
                'state', 'a.state',
		'utop', 'a.utop',
		'ucommercial', 'a.ucommercial',
		'uspecial', 'a.uspecial',
                'ordering', 'a.ordering',
                'language', 'a.language',
                'catid', 'a.catid',
                'jname', 'jusername', 'jgroup',
            );
        }

        parent::__construct($config);
    }

    protected function getListQuery() {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select(
                $this->getState(
                        'list.select', 'a.id AS id, a.lastname AS lastname, ' .
                        'a.catid AS catid,' .
                        'a.userid AS userid,' .
                        'a.state AS state, a.ordering AS ordering,' .
                        'a.utop AS utop,' .
                        'a.ucommercial AS ucommercial,' .
                        'a.uspecial AS uspecial,' .
                        'a.language'
                )
        );
        $query->from('`#__expautos_expuser` AS a');

        $query->select('l.title AS language_title');
        $query->join('LEFT', '`#__languages` AS l ON l.lang_code = a.language');

        $query->select('us.name AS jname, us.username AS jusername');
        $query->join('LEFT', '#__users AS us ON us.id = a.userid');

        $query->select('map2.user_id, COUNT(map2.group_id) AS group_count');
        $query->join('LEFT', '#__user_usergroup_map AS map2 ON map2.user_id = a.userid');
        $query->group('a.userid');

        $query->select('GROUP_CONCAT(g2.title SEPARATOR ' . $db->Quote("\n") . ') AS group_names');
        $query->join('LEFT', '#__usergroups AS g2 ON g2.id = map2.group_id');

        if ($groupId = $this->getState('filter.group_id')) {
            $query->where('g2.id = ' . (int) $groupId);
        }
        if ($access = $this->getState('filter.access')) {
            $query->where('a.access = ' . (int) $access);
        }

        $published = $this->getState('filter.state');

        if (is_numeric($published)) {
            $query->where('a.state = ' . (int) $published);
        } else if ($published === '') {
            $query->where('(a.state IN (0, 1))');
        }

        $positions = $this->getState('filter.positions');
        if ((string)$positions) {
            $query->where('a.'.$positions.' = 1');
        }

        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->getEscaped($search, true) . '%');
                $searches = array();
                $searches[] = 'a.lastname LIKE ' . $search;
                $searches[] = 'us.name LIKE ' . $search;
                $searches[] = 'us.username LIKE ' . $search;

                $query->where('(' . implode(' OR ', $searches) . ')');
            }
        }

        if ($language = $this->getState('filter.language')) {
            $query->where('a.language = ' . $db->quote($language));
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
        $id.= ':' . $this->getState('filter.state');
        $id.= ':' . $this->getState('filter.language');
        $id.= ':' . $this->getState('filter.positions');

        return parent::getStoreId($id);
    }

    public function getTable($type = 'Expuser', $prefix = 'ExpautosproTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    protected function populateState($ordering = null, $direction = null) {
        $app = JFactory::getApplication('administrator');

        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $groupId = $this->getUserStateFromRequest($this->context . '.filter.group', 'filter_group_id', null, 'int');
        $this->setState('filter.group_id', $groupId);

        $state = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
        $this->setState('filter.state', $state);

        $published = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);

        $language = $this->getUserStateFromRequest($this->context . '.filter.language', 'filter_language', '');
        $this->setState('filter.language', $language);
        
        $positions = $this->getUserStateFromRequest($this->context . '.filter.positions', 'filter_positions', '', 'string');
        $this->setState('filter.positions', $positions);

        $params = JComponentHelper::getParams('com_expautospro');
        $this->setState('params', $params);

        parent::populateState('a.lastname', 'asc');
    }

}