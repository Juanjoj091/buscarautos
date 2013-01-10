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

jimport('joomla.application.component.controllerform');

class ExpAutosProControllerExpcurr extends JControllerForm {

    protected function allowAdd($data = array()) {
        // Initialise variables.
        $user = JFactory::getUser();
        $categoryId = JArrayHelper::getValue($data, 'catid', JRequest::getInt('filter_category_id'), 'int');
        $allow = null;

        if ($categoryId) {
            $allow = $user->authorise('core.create', $this->option . '.expcurr.' . $categoryId);
        }

        if ($allow === null) {
            return parent::allowAdd($data);
        } else {
            return $allow;
        }
    }

    protected function allowEdit($data = array(), $key = 'id') {
        $recordId = (int) isset($data[$key]) ? $data[$key] : 0;
        $categoryId = 0;

        if ($recordId) {
            $categoryId = '777';
        }

        if ($categoryId) {
            return JFactory::getUser()->authorise('core.edit', $this->option . '.expcurr.' . $categoryId);
        } else {
            return parent::allowEdit($data, $key);
        }
    }

}