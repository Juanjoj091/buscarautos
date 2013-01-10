<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

// No direct access to this file
defined('_JEXEC') or die;

// import Joomla view library
jimport('joomla.application.component.view');
require_once JPATH_COMPONENT.'/helpers/expfields.php';

class ExpAutosProViewExpuser extends JView {

    public function display($tpl = null) {
        $countryid = JRequest::getInt('expcountry_id', 0);
        $stateid = JRequest::getInt('state_id', 0);
        $makeid = JRequest::getInt('expmake_id', 0);
        if($countryid){
        $this->ajax_val($countryid,'name','state','catid','name');
        }elseif($stateid){
        $this->ajax_val($stateid,'city_name','cities','catid','city_name');            
        }elseif($makeid){
        $this->ajax_val($makeid,'name','model','makeid','name');            
        }
    }
    
    public function ajax_val($fieldId,$fieldName,$baseName,$catId,$ordering_name){
        $ajaxlist = ExpAutosProFields::getExpvariables($fieldId,$baseName,$fieldName,$catId,'',$ordering_name);
        print_r($ajaxlist);
    }

}
