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

class ExpAutosProModelExpmods extends JModelList
{

	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'makeid', 'm.id', 'make_name',
				'catid', 'c.id', 'category_name', 'category_title',
				'name', 'a.name',
				'alias', 'a.alias',
				'state', 'a.state',
				'ordering', 'a.ordering',
				'language', 'a.language',

			);
		}

		parent::__construct($config);
	}

	protected function getListQuery()
	{
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		$query->select(
			$this->getState(
				'list.select',
				'a.id AS id, a.name AS name, a.alias AS alias,'.
				'a.makeid AS makeid,' .
				'a.metakey AS metakey,'.
				'a.state AS state, a.ordering AS ordering,'.
				'a.language'
			)
		);
		$query->from('`#__expautos_model` AS a');

		$query->select('m.name AS make_name');
		$query->join('LEFT', '#__expautos_make AS m ON m.id = a.makeid');
                /*
		$query->select('c.name AS category_name');
		$query->join('LEFT', '#__expautos_categories AS c ON c.id = m.catid');
                */
                // Join over the categories.
                $query->select('c.title AS category_title');
                $query->join('LEFT', '#__categories AS c ON c.id = m.catid');
        
		$query->select('l.title AS language_title');
		$query->join('LEFT', '`#__languages` AS l ON l.lang_code = a.language');

		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');


		if ($access = $this->getState('filter.access')) {
			$query->where('a.access = '.(int) $access);
		}

		$published = $this->getState('filter.state');

		if (is_numeric($published)) {
			$query->where('a.state = '.(int) $published);
		} else if ($published === '') {
			$query->where('(a.state IN (0, 1))');
		}

		$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId)) {
			$query->where('c.id = '.(int) $categoryId);
		}

		$makeId = $this->getState('filter.make_id');
		if (is_numeric($makeId)) {
			$query->where('m.id = '.(int) $makeId);
		}

		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
				$query->where('(a.name LIKE '.$search.' OR a.alias LIKE '.$search.')');
			}
		}


		if ($language = $this->getState('filter.language')) {
			$query->where('a.language = ' . $db->quote($language));
		}

		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		if ($orderCol == 'a.ordering') {
			$orderCol = 'a.ordering';
		}
		$query->order($db->getEscaped($orderCol.' '.$orderDirn));
		return $query;
	}

	protected function getStoreId($id = '')
	{
		$id.= ':' . $this->getState('filter.search');
		$id.= ':' . $this->getState('filter.access');
		$id.= ':' . $this->getState('filter.state');
		$id.= ':' . $this->getState('filter.language');
		$id.= ':' . $this->getState('filter.make_id');
		$id.= ':' . $this->getState('filter.category_id');

		return parent::getStoreId($id);
	}


	public function getTable($type = 'Expmod', $prefix = 'ExpautosproTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication('administrator');

		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$state = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string');
		$this->setState('filter.state', $state);

		$accessId = $this->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', null, 'int');
		$this->setState('filter.access', $accessId);

		$published = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);

		$language = $this->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);

		$makeId = $this->getUserStateFromRequest($this->context.'.filter.make_id', 'filter_make_id', '');
		$this->setState('filter.make_id', $makeId);

		$categoryId = $this->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id', '');
		$this->setState('filter.category_id', $categoryId);

		$params = JComponentHelper::getParams('com_expautospro');
		$this->setState('params', $params);

		parent::populateState('a.name', 'asc');
	}

}