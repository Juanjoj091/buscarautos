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
require_once JPATH_COMPONENT.'/helpers/expparams.php';

class ExpautosproViewExpcompare extends JView {

    public function display($tpl = null) {

        $catid = JRequest::getInt('expgcatid', 0);
        $makeid = JRequest::getInt('expgmakeid', 0);
        $modelid = JRequest::getInt('expgmodelid', 0);
        $specificmodel = (string) JRequest::getString('expgsmodelid', 0);
        $specificmodel = JFilterOutput::cleanText($specificmodel);
        $bodytype = (int) JRequest::getInt('expgbodytype', 0);
        $yearfrom = (int) JRequest::getInt('expgyearfrom', 0);
        $yearto = (int) JRequest::getInt('expgyearto', 0);
        $pricefrom = (string) JRequest::getString('expgpricefrom', 0);
        $pricefrom = JFilterOutput::cleanText($pricefrom);
        $pricefrom = str_replace(' ', '', $pricefrom);
        $priceto = (string) JRequest::getString('expgpriceto', 0);
        $priceto = JFilterOutput::cleanText($priceto);
        $priceto = str_replace(' ', '', $priceto);
        $mileage = (string) JRequest::getString('expgmileage', 0);
        $mileage = JFilterOutput::cleanText($mileage);
        $mileage = str_replace(' ', '', $mileage);
        $fuel = (int) JRequest::getInt('expgfuel', 0);
        $drive = (int) JRequest::getInt('expgdrive', 0);
        $trans = (int) JRequest::getInt('expgtrans', 0);
        
        
        
        $this->ajax_googleads($catid,$makeid,$modelid,$specificmodel,$bodytype,$yearfrom,$yearto,$pricefrom,$priceto,$mileage,$fuel,$drive,$trans);
        
    }
    
    public function ajax_googleads($catid=0,$makeid=0,$modelid=0,$specificmodel='',$bodytype=0,$yearfrom=0,$yearto=0,$pricefrom='',$priceto='',$mileage='',$fuel=0,$drive=0,$trans=0){
        
$db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $user = JFactory::getUser();
        $groups	= implode(',', $user->getAuthorisedViewLevels());

        $query->select('a.id,a.catid,a.make,a.model,a.specificmodel');
        $query->select('a.latitude,a.longitude,a.year,a.month');
        $query->select('IF( a.bprice != "",a.bprice,a.price ) price');
        $query->select('a.vincode,a.mileage,a.engine,a.doors,a.seats,a.otherinfo, a.imgmain AS img_name');
        $query->select('a.unweight,a.grweight,a.length,a.width,a.displacement,a.street');
        $query->from('#__expautos_admanager AS a');
        
        /* Categories */
        /*
        $query->select('c.name AS category_name, c.id AS categid');
        $query->join('LEFT', '#__expautos_categories AS c ON c.id = a.catid');
         */
        $query->select('c.title AS category_name, c.id AS categid');
        $query->join('LEFT', '#__categories AS c ON c.id = a.catid');
        
        /* Makes */
        $query->select('mk.name AS make_name');
        $query->join('LEFT', '#__expautos_make AS mk ON mk.id = a.make');
        //$query->where('mk.state=1');

        /* Models */
        $query->select('md.name AS model_name');
        $query->join('LEFT', '#__expautos_model AS md ON md.id = a.model');

        /* Body Type */
        $query->select('bt.name AS bodytype_name');
        $query->join('LEFT', '#__expautos_bodytype AS bt ON bt.id = a.bodytype');

        /* Drive */
        $query->select('dr.name AS drive_name');
        $query->join('LEFT', '#__expautos_drive AS dr ON dr.id = a.drive');

        /* Ext Color */
        $query->select('ecl.name AS extcolor_name, ecl.image AS extcolor_image');
        $query->join('LEFT', '#__expautos_color AS ecl ON ecl.id = a.extcolor');

        /* Transmission */
        $query->select('tr.name AS trans_name, tr.code AS trans_code');
        $query->join('LEFT', '#__expautos_trans AS tr ON tr.id = a.trans');

        /* Fuel */
        $query->select('fl.name AS fuel_name, fl.code AS fuel_code');
        $query->join('LEFT', '#__expautos_fuel AS fl ON fl.id = a.fuel');
        
        // Country Vehicle
        $query->select('cnt.name AS cnt_name');
        $query->join('LEFT', '#__expautos_country AS cnt ON cnt.id = a.country');

        // State Vehicle
        $query->select('st.name AS st_name');
        $query->join('LEFT', '#__expautos_state AS st ON st.id = a.expstate');

        // City Vehicle
        $query->select('ct.city_name AS ct_name');
        $query->join('LEFT', '#__expautos_cities AS ct ON ct.id = a.city');
        // Equipment
        $query->select('eq.name AS eq_name');
        $query->join('LEFT', '#__expautos_equipment AS eq ON eq.id = a.equipment');
        
        // Extra Field 1
        $query->select('ef1.name AS ef1_name');
        $query->join('LEFT', '#__expautos_extrafield1 AS ef1 ON ef1.id = a.extrafield1');

        // Extra Field 2
        $query->select('ef2.name AS ef2_name');
        $query->join('LEFT', '#__expautos_extrafield2 AS ef2 ON ef2.id = a.extrafield2');

        // Extra Field 3
        $query->select('ef3.name AS ef3_name');
        $query->join('LEFT', '#__expautos_extrafield3 AS ef3 ON ef3.id = a.extrafield3');
        
        // Join over the images.
        /* old version 3.5.1
        $query->select('img.name AS img_name');
        $query->join('LEFT', '#__expautos_images AS img ON a.id = img.catid AND img.ordering = 1');
         */


        /* Access */
        $query->select('ag.title AS access_level');
        $query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access AND ag.id = c.access');
        $query->where('c.access IN ('.$groups.')');
        $query->where('a.state = 1');
        $query->where('a.latitude != 0');
        $query->where('a.longitude != 0');
        
        /*
        if((int)$catid)
            $query->where('a.catid = '.(int)$catid);
         */
        $expallcat = 0;
        if($catid){
            $expallcat = ExpAutosProExpparams::getExpCatChilds($catid);
            $query->where('a.catid IN ('.$expallcat.') ');
        }
        if((int)$makeid)
            $query->where('a.make = '.(int)$makeid);
        if((int)$modelid)
            $query->where('a.model = '.(int)$modelid);
        if ($specificmodel)
            $query->where('LOWER(a.specificmodel) LIKE ' . $db->Quote('%' . $db->getEscaped($specificmodel, true) . '%', false));
        if ($bodytype)
            $query->where('a.bodytype = ' . (int) $bodytype);
        if ($yearfrom)
            $query->where('a.year >= ' . (int) $yearfrom);
        if ($yearto)
            $query->where('a.year <= ' . (int) $yearto);
        if ($pricefrom)
            $query->where("IF( a.bprice != '',a.bprice,a.price ) >= " . (int) $pricefrom);
        if ($priceto)
            $query->where("IF( a.bprice != '',a.bprice,a.price ) <= " . (int) $priceto);
        if ($mileage)
            $query->where('a.mileage <= ' . (int) $mileage);
        if ($fuel)
            $query->where('a.fuel = ' . (int) $fuel);
        if ($drive)
            $query->where('a.drive = ' . (int) $drive);
        if ($trans)
            $query->where('a.trans = ' . (int) $trans);
        $query->order('a.id');
        // Get the options.
        $db->setQuery($query);
        $returnval = $db->loadObjectList();
        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }
        echo json_encode($returnval);
    }

}
