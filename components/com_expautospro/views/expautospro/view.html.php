<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
require_once JPATH_COMPONENT . '/helpers/helper.php';

class ExpautosproViewExpautospro extends JView {

    protected $state = null;
    protected $item = null;
    protected $items = null;
    protected $pagination = null;
/*
    function display($tpl = null) {
        $state      = $this->get('State');
        $items      = $this->get('Item');
        $expparams  = $this->get('Expparams');
        $expitemid  = $this->get('ExpItemid');
        $expskins   = 'expcategories';
        //$this->form = $this->get('Form');

        $this->assignRef('items', $items);
        $this->assignRef('state', $state);
        $this->assignRef('expparams', $expparams);
        $this->assignRef('expitemid', $expitemid);
        $this->assignRef('expskins', $expskins);

        $this->_prepareDocument();

        parent::display($tpl);
    }
 * 
 */
    function display($tpl = null)
	{
		// Initialise variables
		$state		= $this->get('State');
		$items		= $this->get('Items');
		$parent		= $this->get('Parent');
                print_r($items);

		// Check for errors.
                /*
		if (count($errors = $this->get('Errors'))) {
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		if ($items === false) {
			return JError::raiseError(404, JText::_('JGLOBAL_CATEGORY_NOT_FOUND'));

		}

		if ($parent == false) {
			return JError::raiseError(404, JText::_('JGLOBAL_CATEGORY_NOT_FOUND'));
		}
                 * 
                 */

		$params = &$state->params;

		$items = array($parent->id => $items);

		//Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));

		$this->assign('maxLevelcat',	$params->get('maxLevelcat', -1));
		$this->assignRef('params',		$params);
		$this->assignRef('parent',		$parent);
		$this->assignRef('items',		$items);

		$this->_prepareDocument();

		parent::display($tpl);
	}

    protected function _prepareDocument() {

        $app = JFactory::getApplication();
        $menus = $app->getMenu();
        $pathway = $app->getPathway();
        $title = null;
        $menu = $menus->getActive();
        //$pathway->addItem(JText::_('COM_EXPAUTOSPRO_CP_CATEGORIES_PATHWAY_TEXT'));

        $this->document->setTitle(JText::_('COM_EXPAUTOSPRO_TEXT').JText::_('COM_EXPAUTOSPRO_CP_CATEGORIES_TITLE_TEXT'));
    }

}

?>
