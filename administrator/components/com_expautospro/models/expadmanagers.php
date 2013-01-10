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

class ExpAutosProModelExpadmanagers extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'catid', 'a.catid', 'category_name', 'category_title',
                'state', 'a.state',
		'ftop', 'a.ftop',
		'fcommercial', 'a.fcommercial',
		'special', 'a.special',
		'solid', 'a.solid',
		'expreserved', 'a.expreserved',
                'ordering', 'a.ordering',
                'language', 'a.language',
                'positions',
            );
        }

        parent::__construct($config);
    }

    protected function getListQuery() {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select(
                $this->getState(
                        'list.select', 'a.id AS id,' .
                        'a.catid AS catid,' .
                        'a.stocknum AS stocknum,' .
                        'a.make AS makeid,' .
                        'a.model AS modelid,' .
                        'a.ftop AS ftop,' .
                        'a.fcommercial AS fcommercial,' .
                        'a.special AS special,' .
                        'a.solid AS solid,' .
                        'a.expreserved AS expreserved,' .
                        'a.state AS state, a.ordering AS ordering,' .
                        'a.language , a.imgmain AS img_name, a.imgcount'
                )
        );
        $query->from('`#__expautos_admanager` AS a');
        /*
        // Join over the categories.
        $query->select('c.name AS category_name');
        $query->join('LEFT', '#__expautos_categories AS c ON c.id = a.catid');
         */
        // Join over the categories.
        $query->select('c.title AS category_title');
        $query->join('LEFT', '#__categories AS c ON c.id = a.catid');
        // Join over the makes.
        $query->select('mk.name AS make_name');
        $query->join('LEFT', '#__expautos_make AS mk ON mk.id = a.make');
        // Join over the models.
        $query->select('md.name AS model_name');
        $query->join('LEFT', '#__expautos_model AS md ON md.id = a.model');

        // Join over the images.
        /*
        $query->select('img.name AS img_name');
        $query->join('LEFT', '(SELECT im2.name,im2.ordering,im2.catid 
			  FROM #__expautos_images AS im2 ORDER BY im2.ordering ASC
			  ) AS img ON a.id = img.catid');
        $query->group('a.id');
         * 
         */
        /* old version 3.5.1
        $query->select('img.name AS img_name');
        $query->join('LEFT', '#__expautos_images AS img ON a.id = img.catid AND img.ordering = 1');
         */

        $query->select('l.title AS language_title');
        $query->join('LEFT', '`#__languages` AS l ON l.lang_code = a.language');

        $query->select('ag.title AS access_level');
        $query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

        $query->select('us.username AS username');
        $query->join('LEFT', '#__users AS us ON us.id = a.user');

        // Filter by published state
        $published = $this->getState('filter.state');
        if (is_numeric($published)) {
            $query->where('a.state = ' . (int) $published);
        } else if ($published === '') {
            $query->where('(a.state IN (0, 1))');
        }
        // Filter by user.
        $userId = $this->getState('filter.user_id');
        if (is_numeric($userId) && (int)$userId > 0) {
            $query->where('a.user = ' . (int) $userId);
        }
        // Filter by category.
        $categoryId = $this->getState('filter.category_id');
        if (is_numeric($categoryId)) {
            $query->where('a.catid = ' . (int) $categoryId);
        }
        // Filter by make.
        $makeId = $this->getState('filter.make_id');
        if (is_numeric($makeId)) {
            $query->where('a.make = ' . (int) $makeId);
        }
        // Filter by positions.
        $positions = $this->getState('filter.positions');
        if ((string)$positions) {
            $query->where('a.'.$positions.' = 1');
        }
        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->getEscaped($search, true) . '%');
                $query->where('(a.id LIKE '.$search.' OR a.stocknum LIKE '.$search.')');
            }
        }

        // Filter on the language.
        if ($language = $this->getState('filter.language')) {
            $query->where('a.language = ' . $db->quote($language));
        }

        // Add the list ordering clause.
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
        $id.= ':' . $this->getState('filter.user_id');
        $id.= ':' . $this->getState('filter.category_id');
        $id.= ':' . $this->getState('filter.make_id');
        $id.= ':' . $this->getState('filter.positions');

        return parent::getStoreId($id);
    }

    public function getTable($type = 'Expadmanager', $prefix = 'ExpautosproTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Load the filter state.
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $state = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
        $this->setState('filter.state', $state);

        $published = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
        $this->setState('filter.state', $published);

        $language = $this->getUserStateFromRequest($this->context . '.filter.language', 'filter_language', '');
        $this->setState('filter.language', $language);

        $userId = $this->getUserStateFromRequest($this->context . '.filter.user_id', 'filter_user_id', '');
        $this->setState('filter.user_id', $userId);

        $categoryId = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id', '');
        $this->setState('filter.category_id', $categoryId);

        $makeId = $this->getUserStateFromRequest($this->context . '.filter.make_id', 'filter_make_id', '');
        $this->setState('filter.make_id', $makeId);
        
        $positions = $this->getUserStateFromRequest($this->context . '.filter.positions', 'filter_positions', '', 'string');
        $this->setState('filter.positions', $positions);

        // Load the parameters.
        $params = JComponentHelper::getParams('com_expautospro');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.id', 'DESC');
    }

}