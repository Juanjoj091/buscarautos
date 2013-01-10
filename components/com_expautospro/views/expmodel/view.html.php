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

class ExpautosproViewExpmodel extends JView {

    protected $state;
    protected $form;
    protected $item;

    function display($tpl = null) {
        //$expmakeId = JRequest::getInt('makeid');
        $state = $this->get('State');
        $items = $this->get('Item');
        $expparams  = $this->get('Expparams');
        $expitemid  = $this->get('ExpItemid');
        $expskins = 'expmodel';
        //$this->form = $this->get('Form');

        $this->assignRef('items', $items);
        $this->assignRef('state', $state);
        $this->assignRef('expparams', $expparams);
        $this->assignRef('expitemid', $expitemid);
        $this->assignRef('expskins', $expskins);
        //$this->assignRef('expmakeid', $expmakeId);
        //print_r($items);

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
            if((int)$this->items[0]->categid && $menu->query['view'] != 'expmake'){
                $link_makes = JRoute::_("index.php?option=com_expautospro&amp;view=expmake&amp;catid=".(int)$this->items[0]->categid);
                if($this->items[0]->category_name)
                $pathway->addItem($this->items[0]->category_name, $link_makes);
            }
            $pathway->addItem($this->items[0]->make_name);


            $this->document->setTitle(JText::_('COM_EXPAUTOSPRO_TEXT').JText::_('COM_EXPAUTOSPRO_CP_CATEGORY_TITLE_TEXT').":".$this->items[0]->category_name.JText::_('COM_EXPAUTOSPRO_CP_MAKE_TITLE_TEXT').":".$this->items[0]->make_name.JText::_('COM_EXPAUTOSPRO_CP_MODELS_TITLE_TEXT'));
        }
    }

}

?>
