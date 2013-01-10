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
require_once JPATH_COMPONENT . '/helpers/expfields.php';

class ExpAutosProViewExpimages extends JView {

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
        $expcategoryfields  = $this->get('Expcategoryfields');
        $expimages          = $this->get('Expimages');
        $expitemid          = $this->get('ExpItemid');
        $expskins           = 'expimages';
        
        $this->assignRef('expparams', $expparams);
        $this->assignRef('expgroup', $expgroup);
        $this->assignRef('expgroupid', $expgroupid);
        $this->assignRef('expgroupfields', $expgroupfields);
        $this->assignRef('expcategoryfields', $expcategoryfields);
        $this->assignRef('expimages', $expimages);
        $this->assignRef('expitemid', $expitemid);
        $this->assignRef('expskins', $expskins);
        
        //print_r($this->data->id);
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
        //$link_cat = JRoute::_("index.php?option=com_expautospro");
        //$pathway->addItem(JText::_('COM_EXPAUTOSPRO_CP_CATEGORIES_PATHWAY_TEXT'), $link_cat);
        $pathway->addItem(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_PATHWAY_TEXT'));
        $pathway->addItem(JText::_('COM_EXPAUTOSPRO_CP_ADDIMAGES_PATHWAY_TEXT'));
        $menu = $menus->getActive();

        $this->document->setTitle(JText::_('COM_EXPAUTOSPRO_TEXT').JText::_('COM_EXPAUTOSPRO_CP_EXPADD_TITLE_TEXT').JText::_('COM_EXPAUTOSPRO_CP_ADDIMAGES_TITLE_TEXT'));
    }

}
