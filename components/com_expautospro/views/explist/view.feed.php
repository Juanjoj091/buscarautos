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

class ExpautosproViewExplist extends JView {

    function display($tpl = null) {
        $expgetcookie   = JRequest::getVar('expshortlist', null,  $hash= 'COOKIE');
        $state          = $this->get('State');
        $items          = $this->get('Items');
        $expparams      = $this->get('Expparams');
        $expcatfields   = $this->get('Expcatfields');
        $pagination	= $this->get('Pagination');
        $expgroupfield  = $this->get('Expgroupfields');
        $expitemid      = $this->get('ExpItemid');
        //$this->form = $this->get('Form');

        $this->assignRef('items', $items);
        $this->assignRef('state', $state);
        $this->assignRef('expparams', $expparams);
        $this->assignRef('expcatfields', $expcatfields);
	$this->assignRef('pagination',	$pagination);
	$this->assignRef('expgroupfield',$expgroupfield);
	$this->assignRef('expgetcookie',$expgetcookie);
        $this->assignRef('expitemid', $expitemid);
        //$this->assignRef('expmakeid', $expmakeId);

        $this->_exprssDocument();
    }
    
    protected function _exprssDocument() {
        $document           = JFactory::getDocument();
        $user               = JFactory::getUser();
        $imgquery           = $this->expimages;
        $expcatequipment    = $this->expcatequipment;
        $tmblink            = ExpAutosProExpparams::ImgUrlPatchThumbs();
        $middlelink         = ExpAutosProExpparams::ImgUrlPatchMiddle();
        $biglink            = ExpAutosProExpparams::ImgUrlPatchBig();
        $expimgrss          = (int) JRequest::getInt('imgrss', 0);
        $explimitrss        = (int) JRequest::getInt('limitrss', 0);
        $explimitrss        = $explimitrss-1;
        foreach ($this->items as $key=>$item){
            if($key<=($explimitrss)){
                $href_link = JRoute::_(JURI::root() . 'index.php?option=com_expautospro&view=expdetail&id=' . (int) $item->id.'&Itemid='.(int) $this->expitemid);
                $topname = $this->escape($item->make_name) . "&nbsp;";
                $topname .= $this->escape($item->model_name) . "&nbsp;";
                if ($item->specificmodel)
                    $topname .= $this->escape($item->specificmodel) . "&nbsp;";
                if ($item->displacement > 0)
                    $topname .= $this->escape($item->displacement) . JText::_('COM_EXPAUTOSPRO_LITER_S_TEXT') . "&nbsp;";
                if ($item->engine > 0)
                    $topname .= $this->escape($item->engine) . JText::_('COM_EXPAUTOSPRO_KW_TEXT'). "&nbsp;";
                if ($item->year > 0)
                    $topname .= $this->escape($item->year). "&nbsp;";
                if ($item->price) {
                    $topname .= JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_PRICE_TEXT');
                    $price = ExpAutosProExpparams::price_formatdata($item->price, 2);
                    $topname .= $price . "&nbsp;";
                }
                if ($item->expprice) {
                    $topname .= JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_GENERALINFO_EXPPRICE_TEXT');
                    $price = ExpAutosProExpparams::price_formatdata($item->expprice, 2);
                    $topname .= $price . "&nbsp;";
                }

                if ($item->img_name) {
                    $img_file = '<a href="' . $href_link . '"><img src="' . $tmblink . $item->img_name . '"/></a>';
                } else {
                    $img_file = '<a href="' . $href_link . '"><img src="' . ExpAutosProExpparams::ImgUrlPatch() . 'assets/images/no_photo.jpg"/></a>';
                }
                if($expimgrss){
                    $desc = $img_file;
                }else{
                    $desc = '';
                }
                if($item->bodytype_name){
                        $desc .= '&nbsp;'.$this->escape($item->bodytype_name);
                }
                if($item->fuel_name){
                        $desc .= '&nbsp;,'.$this->escape($item->fuel_name);
                }
                if($item->trans_name){
                        $desc .= '&nbsp;,'.$this->escape($item->trans_name);
                }
                if($item->drive_name){
                        $desc .= '&nbsp;,'.$this->escape($item->drive_name);
                }
                if($item->mileage){
                        $desc .= '&nbsp;,'.$this->escape($item->mileage)."&nbsp;".JText::_('COM_EXPAUTOSPRO_KM_TEXT');
                }

                $expitem = new JFeedItem();
                $expitem->title 	= html_entity_decode($topname, ENT_COMPAT, 'UTF-8');
                $expitem->link 		= $this->escape($href_link);
                $expitem->description 	= $desc;
                $expitem->date		= $this->escape($item->creatdate);
                $document->addItem($expitem);
            }
        }
        
        
    }
}

?>