<?php
/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */

// No direct access to this file
defined('_JEXEC') or die;

// import Joomla view library
jimport('joomla.application.component.view');
require_once JPATH_COMPONENT . '/helpers/expfields.php';
require_once JPATH_COMPONENT . '/helpers/expparams.php';

class ExpAutosProViewExpuser extends JView {

    public function display($tpl = null) {
        $countryid = JRequest::getInt('expcountry_id', -2);
        $stateid = JRequest::getInt('state_id', -2);
        $makeid = JRequest::getInt('expmake_id', -2);
        $catid = JRequest::getInt('expcat_id', -2);
        $ecount = JRequest::getInt('ecount', -2);
        $bodytype = JRequest::getInt('bodytype_id', -2);
        $imgbtypecat = JRequest::getInt('imgbtype_catid', -2);
        $wimg = JRequest::getInt('wimg', -2);
        $expitemid = JRequest::getInt('expitemid', -2);
        $extrafield1 = JRequest::getInt('extrafield1_id', -2);
        $expshortlist = JRequest::getInt('expshortlist', 0);
        $eqcatid = JRequest::getInt('expcequip_id', -2);
        $expeq = JRequest::getString('expeq');
        $expeqval = JRequest::getString('expeqval');
        if ($countryid >= 0) {
            $this->ajax_val($countryid, 'name', 'state', 'catid', 'name');
        } elseif ($stateid >= 0) {
            $this->ajax_val($stateid, 'city_name', 'cities', 'catid', 'city_name');
        } elseif ($catid >= 0) {
            $this->ajax_val($catid, 'name', 'make', 'catid', 'name',$ecount);
        } elseif ($makeid >= 0) {
            $this->ajax_val($makeid, 'name', 'model', 'makeid', 'name',$ecount);
        } elseif ($bodytype >= 0) {
            $this->ajax_val($bodytype, 'name', 'bodytype', 'catid', 'name');
        } elseif ($imgbtypecat >= 0) {
            $this->ajax_vabtypeimg($imgbtypecat,$wimg,0,$expitemid);
        } elseif ($extrafield1 >= 0) {
            $this->ajax_val($extrafield1, 'name', 'extrafield1', 'catid', 'name');
        } elseif ($expshortlist) {
            $expval = JRequest::getInt('expval', 0);
            $expid = JRequest::getVar('expid', 0);
            ExpAutosProExpparams::getExpShortList($expval, $expid);
        } elseif ($eqcatid >= 0 && $expeq) {
            echo ExpAutosProExpparams::getExpEquipments($eqcatid, $expeq, $expeqval);
        }
    }

    public function ajax_val($fieldId, $fieldName, $baseName, $catId, $ordering_name,$expcount=0) {
        $ajaxlist = ExpAutosProFields::getExpSelect($fieldId, $baseName, $fieldName, $catId, '', $ordering_name,'',$expcount);
        print_r($ajaxlist);
    }

    public function ajax_vabtypeimg($catid,$imgbtype=1,$bodytype=0,$expitemid=0) {
        $html = null;
        $ajaxbtype = null;
        if ($catid) {
           $ajaxbtype = ExpAutosProExpparams::getExpImgBtype($catid,$imgbtype,$bodytype,$expitemid);
        }
        echo $ajaxbtype;
    }

}
