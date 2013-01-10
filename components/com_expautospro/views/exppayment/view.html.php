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
require_once JPATH_COMPONENT . '/helpers/expfields.php';

class ExpautosproViewExppayment extends JView {

    protected $state;
    protected $form;
    protected $item;

    function display($tpl = null) {
        //$expcatId = JRequest::getInt('catid');
        $state = $this->get('State');
        $items = $this->get('Item');
        $expparams  = $this->get('Expparams');
        $expgroupid  = $this->get('Expgroupid');
        $expgroupfields  = $this->get('Expgroupfields');
        $expskins   = 'exppayment';
        //$this->form = $this->get('Form');

        $this->assignRef('items', $items);
        $this->assignRef('state', $state);
        $this->assignRef('expparams', $expparams);
        $this->assignRef('expgroupid', $expgroupid);
        $this->assignRef('expgroupfields', $expgroupfields);
        $this->assignRef('expskins', $expskins);
        //$this->assignRef('expcatid', $expcatId);
        //print_r($items[0]->category_name);

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
        $pathway->addItem(JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_PATHWAY_TEXT'));

        $this->document->setTitle(JText::_('COM_EXPAUTOSPRO_TEXT').JText::_('COM_EXPAUTOSPRO_CP_PAYMENT_TITLE_TEXT'));
    }

}

?>
