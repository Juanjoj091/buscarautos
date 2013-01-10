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

class ExpautosproViewExpdetail extends JView {

    function display($tpl = null) {
        $state = $this->get('State');
        $items = $this->get('Item');
        $expparams = $this->get('Expparams');
        $expcatfields = $this->get('Expcatfields');
        $expimages = $this->get('Expimages');
        $expcatequipment = $this->get('Expcatequipment');
        $expitemid      = $this->get('ExpItemid');

        $this->assignRef('items', $items);
        $this->assignRef('state', $state);
        $this->assignRef('expparams', $expparams);
        $this->assignRef('expcatfields', $expcatfields);
        $this->assignRef('expimages', $expimages);
        $this->assignRef('expcatequipment', $expcatequipment);
        $this->assignRef('expitemid', $expitemid);

        $this->_exprssDocument();
    }
    
    protected function _exprssDocument() {
        $document           = JFactory::getDocument();
        $href_link          = JRoute::_(JURI::root() . 'index.php?option=com_expautospro&view=expdetail&id=' . (int) $this->items['id'].'&Itemid='.(int) $this->expitemid);
        $user               = JFactory::getUser();
        $imgquery           = $this->expimages;
        $expcatequipment    = $this->expcatequipment;
        $tmblink            = ExpAutosProExpparams::ImgUrlPatchThumbs();
        $middlelink         = ExpAutosProExpparams::ImgUrlPatchMiddle();
        $biglink            = ExpAutosProExpparams::ImgUrlPatchBig();
        
        $topname = $this->escape($this->items['make_name']) . "&nbsp;";
        $topname .= $this->escape($this->items['model_name']) . "&nbsp;";
        if ($this->items['specificmodel'])
            $topname .= $this->escape($this->items['specificmodel']) . "&nbsp;";
        if ($this->items['displacement'] > 0)
            $topname .= $this->escape($this->items['displacement']) . JText::_('COM_EXPAUTOSPRO_LITER_S_TEXT') . "&nbsp;";
        if ($this->items['engine'] > 0)
            $topname .= $this->escape($this->items['engine']) . JText::_('COM_EXPAUTOSPRO_KW_TEXT'). "&nbsp;";
        if ($this->items['year'] > 0)
            $topname .= $this->escape($this->items['year']). "&nbsp;";
        if ($this->items['price']) {
            $topname .= JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_PRICE_TEXT');
            $price = ExpAutosProExpparams::price_formatdata($this->items['price'], 2);
            $topname .= $price . "&nbsp;";
        }
        if ($this->items['bprice']) {
            $topname .= JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_BPRICE_TEXT');
            $price = ExpAutosProExpparams::price_formatdata($this->items['bprice'], 2);
            $topname .= $price . "&nbsp;";
        }
        if ($this->items['expprice']) {
            $topname .= JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_EXPPRICE_TEXT');
            $price = ExpAutosProExpparams::price_formatdata($this->items['expprice'], 2);
            $topname .= $price . "&nbsp;";
        }
        
        if ($imgquery) {
            $img_file = '<a href="' . $href_link . '"><img src="' . $tmblink . $imgquery[0]['imgname'] . '"/></a>';
        } else {
            $img_file = '<a href="' . $href_link . '"><img src="' . ExpAutosProExpparams::ImgUrlPatch() . 'assets/images/no_photo.jpg"/></a>';
        }
        
        $desc = $img_file;
        if($this->items['bodytype_name']){
                $desc .= '&nbsp;'.$this->escape($this->items['bodytype_name']);
        }
        if($this->items['fuel_name']){
                $desc .= '&nbsp;,'.$this->escape($this->items['fuel_name']);
        }
        if($this->items['trans_name']){
                $desc .= '&nbsp;,'.$this->escape($this->items['trans_name']);
        }
        if($this->items['drive_name']){
                $desc .= '&nbsp;,'.$this->escape($this->items['drive_name']);
        }
        if($this->items['mileage']){
                $desc .= '&nbsp;,'.$this->escape($this->items['mileage'])."&nbsp;".JText::_('COM_EXPAUTOSPRO_KM_TEXT');
        }
        
        $item = new JFeedItem();
	$item->title 		= html_entity_decode($topname, ENT_COMPAT, 'UTF-8');
	$item->link 		= $this->escape($href_link);
	$item->description 	= $desc;
	$item->date		= $this->escape($this->items['creatdate']);
	$document->addItem($item);
        
        
    }
}

?>