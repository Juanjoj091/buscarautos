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

jimport('joomla.application.component.view');
jimport('joomla.html.html.tabs');
require_once JPATH_COMPONENT . '/helpers/helper.php';

class ExpAutosProViewExpuser extends JView {

    protected $data;
    protected $form;
    protected $params;
    protected $state;

    public function display($tpl = null) {
        // Initialiase variables.
        $this->data         = $this->get('Data');
        $this->form         = $this->get('Form');
        $this->state        = $this->get('State');
        $expparams          = $this->get('Expparams');
        $expgroup           = $this->get('Expgroup');
        $expgroupid         = $this->get('Expgroupid');
        $expgroupfields     = $this->get('Expgroupfields');
        $expskins           = 'expuser';
        
        $this->assignRef('expparams', $expparams);
        $this->assignRef('expgroup', $expgroup);
        $this->assignRef('expgroupid', $expgroupid);
        $this->assignRef('expgroupfields', $expgroupfields);
        $this->assignRef('expskins', $expskins);
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $this->_prepareDocument();
        
        parent::display($tpl);
    }
    
    protected function _prepareDocument() {

        $app = JFactory::getApplication();
        $menus = $app->getMenu();
        $pathway = $app->getPathway();
        $title = null;
        $menu = $menus->getActive();
        //$link_cat = JRoute::_("index.php?option=com_expautospro");
        //$pathway->addItem(JText::_('COM_EXPAUTOSPRO_CP_CATEGORIES_PATHWAY_TEXT'), $link_cat);
        $pathway->addItem(JText::_('COM_EXPAUTOSPRO_CP_EXPUSER_PATHWAY_TEXT'));

        $this->document->setTitle(JText::_('COM_EXPAUTOSPRO_TEXT').JText::_('COM_EXPAUTOSPRO_CP_EXPUSER_TITLE_TEXT'));
    }

}
