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

class ExpautosproViewExpmake extends JView {

    protected $state;
    protected $form;
    protected $item;

    function display($tpl = null) {
        //$expcatId = JRequest::getInt('catid');
        $state      = $this->get('State');
        $items      = $this->get('Item');
        $expparams  = $this->get('Expparams');
        $expitemid  = $this->get('ExpItemid');
        $expskins   = 'expmake';
        //$this->form = $this->get('Form');

        $this->assignRef('items', $items);
        $this->assignRef('state', $state);
        $this->assignRef('expparams', $expparams);
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
        $menu = $menus->getActive();
        //print_r($this->items[0]->category_name);
        if($this->items){
            if ($menu && $menu->query['option'] = 'com_expautospro' && $menu->query['view'] == 'categories'){
                //$link_cat = JRoute::_("index.php?option=com_expautospro");
                //$pathway->addItem(JText::_('COM_EXPAUTOSPRO_CP_CATEGORIES_PATHWAY_TEXT'), $link_cat);
                if($this->items[0]->category_name)
                $pathway->addItem($this->items[0]->category_name);
            }else{
                $pathway->addItem(JText::_('COM_EXPAUTOSPRO_CP_MAKE_PATHWAY_TEXT'));
            }

            $this->document->setTitle(JText::_('COM_EXPAUTOSPRO_TEXT').JText::_('COM_EXPAUTOSPRO_CP_CATEGORY_TITLE_TEXT').":".$this->items[0]->category_name.JText::_('COM_EXPAUTOSPRO_CP_MAKES_TITLE_TEXT'));
        }
    }

}

?>
