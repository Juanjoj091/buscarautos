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
require_once JPATH_COMPONENT . '/helpers/icon.php';

class ExpautosproViewExpdealerdetail extends JView {

    protected $state;
    protected $form;
    protected $item;

    function display($tpl = null) {
        $state          = $this->get('State');
        $items          = $this->get('Item');
        $expparams      = $this->get('Expparams');
        $expgroup       = $this->get('Expgroup');
        $expgroupfield  = $this->get('Expgroupfields');
        $expitemid      = $this->get('ExpItemid');
        $expskins       = 'expdealerdetail';
        //$this->form = $this->get('Form');

        $this->assignRef('items', $items);
        $this->assignRef('state', $state);
        $this->assignRef('expparams', $expparams);
        $this->assignRef('expgroup', $expgroup);
        $this->assignRef('expgroupfield', $expgroupfield);
        $this->assignRef('expitemid', $expitemid);
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
        //$link_cat = JRoute::_("index.php?option=com_expautospro");
        //$pathway->addItem(JText::_('COM_EXPAUTOSPRO_CP_CATEGORIES_PATHWAY_TEXT'), $link_cat);
        $link_dealerlist = JRoute::_("index.php?option=com_expautospro&amp;view=expdealerlist");
        $pathway->addItem(JText::_('COM_EXPAUTOSPRO_CP_DEALERLIST_PATHWAY_TEXT'), $link_dealerlist);
        $pathway->addItem(JText::_('COM_EXPAUTOSPRO_CP_DEALER_DETAIL_PATHWAY_TEXT'));
        $menu = $menus->getActive();

        $this->document->setTitle(JText::_('COM_EXPAUTOSPRO_TEXT')." ".JText::_('COM_EXPAUTOSPRO_CP_DEALER_DETAIL_PATHWAY_TEXT'));
    }

}

?>
