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

class ExpAutosProModelExpcits extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'catid', 'a.catid', 'category_name',
                'city_name', 'a.city_name',
                'state', 'a.state',
                'st.name', 'country_name',
                'ordering', 'a.ordering',
                'language', 'a.language',
            );
        }

        parent::__construct($config);
    }

    protected function getListQuery() {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select(
                $this->getState(
                        'list.select', 'a.id AS id, a.city_name AS city_name,' .
                        'a.catid AS catid,' .
                        'a.city_zip AS city_zip,a.city_state AS city_state,' .
                        'a.city_latitude AS city_latitude,a.city_longitude AS city_longitude,' .
                        'a.city_county AS city_county,' .
                        'a.state AS state, a.ordering AS ordering,' .
                        'a.language'
                )
        );
        $query->from('`#__expautos_cities` AS a');

        // Join over the countries.
        $query->select('st.name AS category_name');
        $query->join('LEFT', '#__expautos_state AS st ON st.id = a.catid');

        // Join over the countries.
        $query->select('c.name AS country_name');
        $query->join('LEFT', '#__expautos_country AS c ON c.id = st.catid');

        $query->select('l.title AS language_title');
        $query->join('LEFT', '`#__languages` AS l ON l.lang_code = a.language');

        // Filter by published state
        $published = $this->getState('filter.state');

        if (is_numeric($published)) {
            $query->where('a.state = ' . (int) $published);
        } else if ($published === '') {
            $query->where('(a.state IN (0, 1))');
        }

        // Filter by country.
        $countryId = $this->getState('filter.country_id');
        if (is_numeric($countryId)) {
            $query->where('c.id = ' . (int) $countryId);
        }

        // Filter by state.
        $categoryId = $this->getState('filter.category_id');
        if (is_numeric($categoryId)) {
            $query->where('a.catid = ' . (int) $categoryId);
        }
        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->getEscaped($search, true) . '%');
                $query->where('(a.city_name LIKE ' . $search . ')');
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
        $id.= ':' . $this->getState('filter.category_id');

        return parent::getStoreId($id);
    }

    public function getTable($type = 'Expcit', $prefix = 'ExpautosproTable', $config = array()) {
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

        $categoryId = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id', '');
        $this->setState('filter.category_id', $categoryId);

        $countryId = $this->getUserStateFromRequest($this->context . '.filter.country_id', 'filter_country_id', '');
        $this->setState('filter.country_id', $countryId);

        // Load the parameters.
        //$params = JComponentHelper::getParams('com_expautospro');
        //$this->setState('params', $params);

        // List state information.
        parent::populateState('a.city_name', 'asc');
    }

}