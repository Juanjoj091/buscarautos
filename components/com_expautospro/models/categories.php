<?php

/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.application.component.model');
require_once JPATH_COMPONENT . '/helpers/expparams.php';

class ExpautosproModelCategories extends JModel {

    public $_context = 'com_expautospro.categories';
    protected $_extension = 'com_expautospro';
    private $_parent = null;
    private $_items = null;

    protected function populateState() {
        $app = JFactory::getApplication();
        $this->setState('filter.extension', $this->_extension);

        // Get the parent id if defined.
        $parentId = JRequest::getInt('id');
        $this->setState('filter.parentId', $parentId);

        $params = $app->getParams();
        $this->setState('params', $params);

        $this->setState('filter.published', 1);
        $this->setState('filter.access', true);
    }

    protected function getStoreId($id = '') {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.extension');
        $id .= ':' . $this->getState('filter.published');
        $id .= ':' . $this->getState('filter.access');
        $id .= ':' . $this->getState('filter.parentId');

        return parent::getStoreId($id);
    }

    public function getItems() {
        if (!count($this->_items)) {
            $app = JFactory::getApplication();
            $menu = $app->getMenu();
            $active = $menu->getActive();
            $params = new JRegistry();
            if ($active) {
                $params->loadString($active->params);
            }
            $options = array();
            $options['countItems'] = $params->get('show_empty_categories_cat', 1);
            $categories = JCategories::getInstance('Expautospro', $options);
            $this->_parent = $categories->get($this->getState('filter.parentId', 'root'));
            if (is_object($this->_parent)) {
                $this->_items = $this->_parent->getChildren();
            } else {
                $this->_items = false;
            }
        }

        return $this->_items;
    }

    public function getParent() {
        if (!is_object($this->_parent)) {
            $this->getItems();
        }
        return $this->_parent;
    }

    function getExpparams() {
        $expparams = ExpAutosProExpparams::getExpParams('config', 1);
        return $expparams;
    }

    public function getExpItemid() {
        $expitemid = ExpAutosProExpparams::getExpLinkItemid();
        return $expitemid;
    }

}
