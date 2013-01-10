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

class ExpautosproViewExppaylevel extends JView {

    protected $state;
    protected $form;
    protected $item;

    function display($tpl = null) {
        $explevelparams = new JRegistry();
        $state      = $this->get('State');
        $items      = $this->get('Item');
        $expparams  = $this->get('Expparams');
        $expskins   = 'exppaylevel';
        //$this->form = $this->get('Form');

        $this->assignRef('items', $items);
        $this->assignRef('state', $state);
        $this->assignRef('expparams', $expparams);
        $this->assignRef('explevelparams', $explevelparams);
        $this->assignRef('expskins', $expskins);

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
        $pathway->addItem(JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_PATHWAY_TEXT'));

        $this->document->setTitle(JText::_('COM_EXPAUTOSPRO_TEXT').JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_TITLE_TEXT'));
    }
    
    public function expunlimited_text($val){
        if($val > 0){
            $value = $val;
        }else{
            $value = JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_UNLIMITED_TEXT');
        }
        return $value;
    }
    
    public function expyesno_text($val){
        if($val){
            $value = JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_YES_TEXT');
        }else{
            $value = JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_NO_TEXT');
        }
        return $value;
    }
    
    public function exppublished_text($val){
        if($val){
            $value = JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_AUTOMATIC_TEXT');
        }else{
            $value = JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_APPROVED_TEXT');
        }
        return $value;
    }
    
    public function expfree_text($val){
        if($val > 0){
            $value = $val."&nbsp;".$this->explevelparams->get('p_pcurrency');
        }else{
            $value = JText::_('COM_EXPAUTOSPRO_CP_DEALER_PAYMENT_FREE_TEXT');
        }
        return $value;
    }

}

?>
