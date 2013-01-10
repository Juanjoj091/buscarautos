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

class ExpAutosProModelExppayments extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'adid', 'a.adid',
                'paydate', 'a.paydate',
                'payval', 'a.payval',
                'paysum', 'a.paysum',
                'status', 'a.status',
                'payname', 'a.payname',
                'paynotice', 'a.paynotice',
                'payuser', 'a.payuser',
                'payid', 'a.payid',
                'state', 'a.state',
            );
        }

        parent::__construct($config);
    }

    protected function getListQuery() {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select(
                $this->getState(
                        'list.select', 'a.id AS id, a.adid AS adid,' .
                        'a.payval AS payval,a.paysum AS paysum,' .
                        'a.status AS status,a.paydate AS paydate,' .
                        'a.payname AS payname,a.paynotice AS paynotice,' .
                        'a.payuser AS payuser,a.payid AS payid,'.
                        'a.paysysval AS paysysval,a.state AS state'
                )
        );
        $query->from('`#__expautos_payment` AS a');
        
        $query->select('us.name AS jname, us.username AS jusername');
        $query->join('LEFT', '#__users AS us ON us.id = a.payuser');
        
        $published = $this->getState('filter.state');

        if (is_numeric($published)) {
            $query->where('a.state = ' . (int) $published);
        } else if ($published === '') {
            $query->where('(a.state IN (0, 1))');
        }
        
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'adid:') === 0) {
                $query->where('a.adid = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->getEscaped($search, true) . '%');
                $query->where('(a.adid LIKE ' . $search . ')');
            }
        }

        $orderCol = $this->state->get('list.ordering');
        $orderDirn = $this->state->get('list.direction');
        if ($orderCol == 'a.adid') {
            $orderCol = 'a.adid';
        }

        $query->order($db->getEscaped($orderCol . ' ' . $orderDirn));
        return $query;
    }

    protected function getStoreId($id = '') {
        $id.= ':' . $this->getState('filter.search');
        $id.= ':' . $this->getState('filter.state');

        return parent::getStoreId($id);
    }

    public function getTable($type = 'Exppayment', $prefix = 'ExpautosproTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    protected function populateState($ordering = null, $direction = null) {
        $app = JFactory::getApplication('administrator');

        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $state = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string');
        $this->setState('filter.state', $state);

        /*
          $params = JComponentHelper::getParams('com_expautospro');
          $this->setState('params', $params);
         */

        parent::populateState('a.paydate', 'asc');
    }

}