<?php

/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');
jimport('joomla.application.component.helper');
require_once JPATH_COMPONENT . '/helpers/expparams.php';
require_once JPATH_COMPONENT . '/helpers/expfields.php';

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'tables');

class ExpautosproModelExplist extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'name', 'a.name',
                'state', 'a.state',
                'price', 'a.price',
                'stocknum', 'a.stocknum',
                'mk.name', 'make_name',
                'md.name', 'model_name',
                'bt.name', 'bodytype_name',
                'dr.name', 'drive_name',
                'a.mileage', 'mileage',
                'a.extcolor', 'extcolor_name',
                'a.trans', 'trans_name',
                'a.fuel', 'fuel_name',
                'a.year', 'year',
                'a.ftop', 'ftop',
                'ordering', 'a.ordering',
                'catid', 'a.catid', 'category_title',
            );
        }
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null) {
        $app = JFactory::getApplication();
        $params = JComponentHelper::getParams('com_expautospro');

        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'uint');
        $this->setState('list.limit', $limit);

        $limitstart = JRequest::getUInt('limitstart', 0);
        $this->setState('list.start', $limitstart);
        
        $expparams = $this->getExpparams();
        $expgroup = $expparams->get('c_admanager_lspage_groupby');
        $orderCol = $app->getUserStateFromRequest('filter_order', 'filter_order', '', 'string');
        if (!in_array($orderCol, $this->filter_fields)) {
            $orderCol = $expgroup;
        }
        $this->setState('list.ordering', $orderCol);

        $expsortby = $expparams->get('c_admanager_lspage_sortby');
        $listOrder = $app->getUserStateFromRequest('filter_order_Dir', 'filter_order_Dir', $expsortby, 'string');
        if (!in_array(strtoupper($listOrder), array('DESC', 'ASC', ''))) {
            $listOrder = $expsortby;
        }
        $this->setState('list.direction', $listOrder);

        $id = JRequest::getVar('id', 0, '', 'int');
        $this->setState('category.id', $id);

        $this->setState('filter.language', $app->getLanguageFilter());
    }

    public function getStart() {
        return $this->getState('list.start');
    }

    public function insertdata($id, $field, $value) {
        $user = JFactory::getUser();
        $userid = (int) $user->id;
        $expparams = $this->getExpparams();
        if (($field == 'solid' && $expparams->get('c_general_enablesolid')) || ($field == 'expreserved' && $expparams->get('c_general_enableexpreserved')) && $userid && (int) $id && (string) $field) {
            $return = ExpAutosProFields::expaddcheck($id, $userid);
            if ($return) {
                if ($value == 1) {
                    $val = 0;
                } elseif ($value == 0) {
                    $val = 1;
                } else {
                    return false;
                }
                $db = $this->getDbo();
                $query = $db->getQuery(true);
                $query->update('`#__expautos_admanager`');
                $query->set('`' . (string) $field . '` = ' . (int) $val);
                $query->where('id = ' . (int) $id);
                $db->setQuery((string) $query);
                $db->query();
                // Check for a database error.
                if ($db->getErrorNum()) {
                    JError::raiseWarning(500, $db->getErrorMsg());
                    return false;
                }
                return true;
            }
        } else {
            return false;
        }
    }

    public function delete_ads($id) {
        $user = JFactory::getUser();
        $userid = (int) $user->id;
        $expparams = $this->getExpparams();
        if ($expparams->get('c_general_enabledelete') && $userid && (int) $id) {
            $return = ExpAutosProFields::expaddcheck($id, $userid);
            if ($return) {
                if ($expparams->get('c_general_enabledelete') == 1) {
                    if (ExpAutosProExpparams::delete_ads((int) $id)) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    if (ExpAutosProExpparams::changestatus((int) $id)) {
                        return true;
                    } else {
                        return false;
                    }
                }
            } else {
                return false;
            }
        }
    }

    public function extend_ads($id) {
        $user = JFactory::getUser();
        $userid = (int) $user->id;
        $expparams = $this->getExpparams();
        if ($expparams->get('c_general_useextend') && $userid && (int) $id) {
            $return = ExpAutosProFields::expaddcheck($id, $userid);
            if ($return) {
                $date_plus = JFactory::getDate('+' . $expparams->get('c_general_extenddays') . ' day ' . date('Y-m-d', strtotime('now')))->toMySQL();
                $db = $this->getDbo();
                $query = $db->getQuery(true);
                $query->update('`#__expautos_admanager`');
                $query->set('`expirdate` = ' . $db->Quote($date_plus));
                $query->set('`expemail` = 0');
                $query->where('id = ' . (int) $id);
                $db->setQuery((string) $query);
                $db->query();
                // Check for a database error.
                if ($db->getErrorNum()) {
                    JError::raiseWarning(500, $db->getErrorMsg());
                    return false;
                }
                return true;
            }
        } else {
            return false;
        }
    }

    protected function getStoreId($id = '') {
        $id.= ':' . $this->getState('filter.access');
        $id.= ':' . $this->getState('filter.category_id');

        return parent::getStoreId($id);
    }

    function getExpparams() {
        $expparams = ExpAutosProExpparams::getExpParams('config', 1);
        return $expparams;
    }

    function getExpcatfields() {
        $postexpcatid = JRequest::getInt('catid');
        $expcatfields = '';
        if ($postexpcatid)
            $expcatfields = ExpAutosProExpparams::getCatParams($postexpcatid);
        return $expcatfields;
    }

    function getExpgroupfields() {
        $postuserid = JRequest::getInt('userid');
        $listgroupparams = '';
        if ($postuserid > 0) {
            jimport('joomla.user.helper');
            //$listusergroups = implode(',', JAccess::getGroupsByUser($postuserid));
            //$listusergroups =  implode(',', JUserHelper::getUserGroups($postuserid));
            $listusergroups = implode(',', JAccess::getGroupsByUser($postuserid));
            $listusergroupid = ExpAutosProExpparams::getExpgroupid($listusergroups);
            $listgroupparams = ExpAutosProExpparams::getExpParams('userlevel', $listusergroupid);
        }
        return $listgroupparams;
    }

    function getListQuery() {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $user = JFactory::getUser();
        $groups = implode(',', $user->getAuthorisedViewLevels());
        $nullDate = $db->quote($db->getNullDate());
        $expparams = $this->getExpparams();
        $multi_models = $expparams->get('c_admanager_mdpage_multi',0);

        $expid = (int) JRequest::getInt('id', 0);
        $stocknum = (string) JRequest::getString('stocknum', 0);
        $stocknum = JFilterOutput::cleanText($stocknum);
        $vincode = (string) JRequest::getString('vincode', 0);
        $vincode = JFilterOutput::cleanText($vincode);
        $catid = (int) JRequest::getInt('catid', 0);
        $makeid = (int) JRequest::getInt('makeid', 0);
        if(!$multi_models){
            $modelid = (int) JRequest::getInt('modelid', 0);
        }else{
            $modelid = JRequest::getVar('modelid', array(), 'get', 'array');
            $count_model = count($modelid);
            $mod_flag = null;
            if($count_model > 1){
                $mod_flag = true;
            }
            $modelid = array_filter($modelid, 'is_numeric');
            if($modelid){
                $modelid =  implode(',', $modelid);
            }else{
                $modelid =  0;
            }
        }
        $specificmodel = (string) JRequest::getString('specificmodel', 0);
        $specificmodel = JFilterOutput::cleanText($specificmodel);
        $condition = (int) JRequest::getInt('condition', 0);
        $bodytype = (int) JRequest::getInt('bodytype', 0);
        $extrafield1 = (int) JRequest::getInt('extrafield1', 0);
        $extrafield2 = (int) JRequest::getInt('extrafield2', 0);
        $extrafield3 = (int) JRequest::getInt('extrafield3', 0);
        $yearfrom = (int) JRequest::getInt('yearfrom', 0);
        $yearto = (int) JRequest::getInt('yearto', 0);
        $pricefrom = (string) JRequest::getString('pricefrom', 0);
        if ($pricefrom){
            $pricefrom = JFilterOutput::cleanText($pricefrom);
            $pricefrom = str_replace(' ', '', $pricefrom);
		}
        $priceto = (string) JRequest::getString('priceto', 0);
        if ($priceto){
            $priceto = JFilterOutput::cleanText($priceto);
            $priceto = str_replace(' ', '', $priceto);
		}
        $mileage = (string) JRequest::getString('mileage', 0);
        if ($mileage){
            $mileage = JFilterOutput::cleanText($mileage);
            $mileage = str_replace(' ', '', $mileage);
		}
        $fuel = (int) JRequest::getInt('fuel', 0);
        $drive = (int) JRequest::getInt('drive', 0);
        $trans = (int) JRequest::getInt('trans', 0);
        $country = (int) JRequest::getInt('country', 0);
        $expstate = (int) JRequest::getInt('expstate', 0);
        $city = (int) JRequest::getInt('city', 0);
        $userid = (int) JRequest::getInt('userid', 0);
        $zipcode = (int) JRequest::getInt('zipcode', 0);
        $radius = (int) JRequest::getInt('radius', 0);
        $ageof = (int) JRequest::getInt('ageof', 0);
        $sortby = (int) JRequest::getInt('sortby', 0);
        $solid = (int) JRequest::getInt('solid', 0);
        $special = (int) JRequest::getInt('special', 0);
        $img = (int) JRequest::getInt('img', 0);
        $wsolid = (int) JRequest::getInt('wsolid', 0);
        $expkeyword = (string) JRequest::getString('expkeyword', 0);
        $location = (int) JRequest::getInt('location', 0);
        $radvar = (int) JRequest::getInt('radvar', 0);
        $radval = (int) JRequest::getInt('radval', 0);
        $equipmets = JRequest::getVar('equipmets', null, '', 'array');
        $color = (int) JRequest::getInt('color', 0);
        //$equipmets = implode(',', $equipmets);

        $expallcat = 0;
        if($catid){
            $expallcat = ExpAutosProExpparams::getExpCatChilds($catid);
        }

        $query->select(
                'a.*, IF( a.bprice != "",a.bprice,a.price ) price ,a.price AS aprice, a.bprice AS bprice, a.imgmain AS img_name '
        );
        $query->from('#__expautos_admanager as a');

        // Join over the images.
        /*
        $query->select('img.name AS img_name');
        $query->join('', '(SELECT im2.name,im2.ordering,im2.catid 
			  FROM #__expautos_images AS im2 ORDER BY im2.ordering ASC
			  ) AS img ON a.id = img.catid');
        $query->group('a.id');
        */
        /* old version 3.5.1 
        $query->select('img.name AS img_name');
        $query->join('LEFT', '#__expautos_images AS img ON a.id = img.catid AND img.ordering = 1');
        */
        //$query->where('a.makeid=' . (int) $postexpmake);

        /* Categories */
        /*
        $query->select('c.name AS category_name, c.id AS categid');
        $query->join('LEFT', '#__expautos_categories AS c ON c.id = a.catid');
         */
        $query->select('c.title AS category_name, c.id AS categid');
        $query->join('LEFT', '#__categories AS c ON c.id = a.catid');

        /* Makes */
        $query->select('mk.id AS mkid,mk.name AS make_name');
        $query->join('LEFT', '#__expautos_make AS mk ON mk.id = a.make');
        //$query->where('mk.state=1');

        /* Models */
        $query->select('md.id AS mdid,md.name AS model_name');
        $query->join('LEFT', '#__expautos_model AS md ON md.id = a.model');

        /* Body Type */
        $query->select('bt.id AS btid,bt.name AS bodytype_name');
        $query->join('LEFT', '#__expautos_bodytype AS bt ON bt.id = a.bodytype');

        /* Drive */
        $query->select('dr.id AS drid,dr.name AS drive_name');
        $query->join('LEFT', '#__expautos_drive AS dr ON dr.id = a.drive');

        /* Ext Color */
        $query->select('ecl.id AS eclid,ecl.name AS extcolor_name, ecl.image AS extcolor_image');
        $query->join('LEFT', '#__expautos_color AS ecl ON ecl.id = a.extcolor');

        /* Transmission */
        $query->select('tr.id AS trid,tr.name AS trans_name, tr.code AS trans_code');
        $query->join('LEFT', '#__expautos_trans AS tr ON tr.id = a.trans');

        /* Fuel */
        $query->select('fl.id AS flid,fl.name AS fuel_name, fl.code AS fuel_code');
        $query->join('LEFT', '#__expautos_fuel AS fl ON fl.id = a.fuel');

        /* User */
        $query->select('user.id AS userwid,user.name AS user_name, user.username AS user_username, user.email AS user_email');
        $query->join('LEFT', '#__users AS user ON user.id = a.user');

        /* EXP User */
        
        $query->select('expusercountry.id AS expusercountrywid,expuserstate.id AS expuserstatewid,expusercity.id AS expusercitywid');
        $query->select('expuser.id AS expuser_id,expuser.lastname AS expuser_lastname, expuser.companyname AS expuser_companyname');
        $query->select('expuser.street AS expuser_street,expuser.phone AS expuser_phone');
        $query->select('expuser.web AS expuser_web, expuser.logo AS expuser_logo');
        $query->select('expuser.zipcode AS expuser_zipcode, expuser.fax AS expuser_fax');
        $query->select('expuser.mobphone AS expuser_mobphone, expuser.userinfo AS expuser_userinfo');
        $query->select('expuser.utop AS expuser_utop, expuser.ucommercial AS expuser_ucommercial');
        $query->select('expuser.uspecial AS expuser_uspecial');
        $query->select('expusercountry.name AS expuser_country, expusercity.city_name AS expuser_city');
        $query->select('expuserstate.name AS expuser_state');
        $query->select('expuser.latitude AS expuser_latitude,expuser.longitude AS expuser_longitude');
        $query->join('LEFT', '#__expautos_expuser AS expuser ON expuser.userid = a.user');
        $query->join('LEFT', '#__expautos_country AS expusercountry ON expusercountry.id = expuser.country');
        $query->join('LEFT', '#__expautos_state AS expuserstate ON expuserstate.id = expuser.expstate');
        $query->join('LEFT', '#__expautos_cities AS expusercity ON expusercity.id = expuser.city');



        // Country Vehicle
        $query->select('cnt.id AS cntid,cnt.name AS cnt_name');
        $query->join('LEFT', '#__expautos_country AS cnt ON cnt.id = a.country');

        // State Vehicle
        $query->select('st.id AS stid,st.name AS st_name');
        $query->join('LEFT', '#__expautos_state AS st ON st.id = a.expstate');

        // City Vehicle
        $query->select('ct.id AS ctid,ct.city_name AS ct_name');
        $query->join('LEFT', '#__expautos_cities AS ct ON ct.id = a.city');

        /* Uncomment ths code if you want use this variable

          // Int Color
          $query->select('inter.name AS intcolor_name, inter.image AS intcolor_image');
          $query->join('LEFT', '#__expautos_color AS inter ON inter.id = a.intcolor');

         */
        
        /* Uncomment ths code if you want use this variable
        // Equipment
        $query->select('eq.id AS eqid,eq.name AS eq_name');
        $query->join('LEFT', '#__expautos_equipment AS eq ON eq.id = a.equipment');
         */
        
        /* Uncomment ths code if you want use this variable
        // Extra Field 1
        $query->select('ef1.id AS ef1id,ef1.name AS ef1_name');
        $query->join('LEFT', '#__expautos_extrafield1 AS ef1 ON ef1.id = a.extrafield1');
         * 
         */
        
        /* Uncomment ths code if you want use this variable
        // Extra Field 2
        $query->select('ef2.id AS ef2id,ef2.name AS ef2_name');
        $query->join('LEFT', '#__expautos_extrafield2 AS ef2 ON ef2.id = a.extrafield2');
         * 
         */
        
        /* Uncomment ths code if you want use this variable

        // Extra Field 3
        $query->select('ef3.id AS ef3id,ef3.name AS ef3_name');
        $query->join('LEFT', '#__expautos_extrafield3 AS ef3 ON ef3.id = a.extrafield3');
         * 
         */


        /* Currency 
          $query->select('cur.cname AS curname,cur.exchange AS curexchange,cur.cvariable AS curcvariable');
          $query->join('LEFT', '#__expautos_currency AS cur ON cur.exchange = 1 AND cur.state=1');
          //$query->where('mk.state=1');
         */

        /* Access */
        $query->select('ag.title AS access_level');
        $query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access AND ag.id = c.access');
        $query->where('c.access IN ('.$groups.')');

        /* SQL Where */
        if ((int) $userid && (int) $user->id == (int) $userid) {
            $query->where('a.state IN (1,0)');
        } else {
            $query->where('a.state=1');
            if($solid == 1){
                $query->where('a.solid = 1');
            }elseif($solid == 2){
                $query->where('a.solid IN (1,0)');
            }elseif($expparams->get('c_admanager_lspage_showsolid')){
                $query->where('a.solid != 1');
            }
        }
        /*
        if ($wsolid){
            $query->where('a.solid IN (0,1) ');
        }elseif(!$wsolid && $expparams->get('c_admanager_lspage_showsolid')){
            $query->where('a.solid != 1');
        }
         */

        if ($expid)
            $query->where('a.id = ' . (int) $expid);
        if ($stocknum)
            $query->where('a.stocknum = ' . $db->Quote($stocknum));
        if ($vincode)
            $query->where('a.vincode = ' . $db->Quote($vincode));
        if ($catid)
            $query->where('a.catid IN (' . $expallcat . ') ');
        if ($makeid)
            $query->where('a.make = ' . (int) $makeid);
        if(!$multi_models){
            if ($modelid){
                $query->where('a.model = ' . (int) $modelid);  
            }
        }else{
            if ($modelid){
                if($mod_flag){
                    $query->where('a.model IN( ' . $modelid.')');
                }else{
                    $query->where('a.model = ' . (int) $modelid);   
                }
            }
        }
        if ($specificmodel)
            $query->where('LOWER(a.specificmodel) LIKE ' . $db->Quote('%' . $db->getEscaped($specificmodel, true) . '%', false));
        if ($condition)
            $query->where('a.condition = ' . (int) $condition);
        if ($bodytype)
            $query->where('a.bodytype = ' . (int) $bodytype);
        if ($extrafield1)
            $query->where('a.extrafield1 = ' . (int) $extrafield1);
        if ($extrafield2)
            $query->where('a.extrafield2 = ' . (int) $extrafield2);
        if ($extrafield3)
            $query->where('a.extrafield3 = ' . (int) $extrafield3);
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
        if ($color)
            $query->where('a.extcolor = ' . (int) $color);
        if ($equipmets) {
            foreach ($equipmets as $eqitem) {
                $query->where(' FIND_IN_SET(' . $eqitem . ',a.equipment)>0');
            }
        }
        if ($location) {
            if ($country)
                $query->where('expuser.country = ' . (int) $country);
            if ($expstate)
                $query->where('expuser.expstate = ' . (int) $expstate);
            if ($city)
                $query->where('expuser.city = ' . (int) $city);
        }else {
            if ($country)
                $query->where('a.country = ' . (int) $country);
            if ($expstate)
                $query->where('a.expstate = ' . (int) $expstate);
            if ($city)
                $query->where('a.city = ' . (int) $city);
        }
        if ($userid)
            $query->where('a.user = ' . (int) $userid);
        if ($img)
            $query->where('a.imgmain != 0');
        /*
          if ($zipcode)
          $query->where('expuser.zipcode = ' . (int) $zipcode);
         */
        if ($ageof > 0) {
            $ageof = JFactory::getDate(date('Y-m-d H:i:s', strtotime("-" . (int) $ageof . " days")))->toMySQL();
            $query->where("a.creatdate >= '" . $ageof . "'");
        }
        if ($special) {
            switch ($special) {
                case 1:
                    $query->where('a.ftop = 1');
                    break;
                case 2:
                    $query->where('a.fcommercial = 1');
                    break;
                case 3:
                    $query->where('a.special = 1');
                    break;
            }
        }

        if ($zipcode > 0) {
            if ($radius > 0) {
                if ($radvar) {
                    $zips = $this->zipcodeRadius($zipcode, $radius);
                } else {
                    $zips = $this->zipcodeRadiusNew($zipcode, $radius, $radval);
                }
            } else {
                $zips = $zipcode;
            }
            //print_r($zips);
            //die();
            if ($zips) {
                if ($radvar || $radval) {
                    $query->where('expuser.zipcode IN ( ' . $zips . ' )');
                } else {
                    $query->where('a.zipcode IN ( ' . $zips . ' )');
                }
            }
        }

        if ($expkeyword) {
            $anywords = true;
            $text = trim($expkeyword);
            if ($text == '') {
                return array();
            }
            if ($anywords) {
                $words = explode(' ', $text);
                foreach ($words as $word) {
                    if (!empty($word)) {
                        $word = $db->Quote('%' . $db->getEscaped($word, true) . '%', false);
                        $wheres2 = array();
                        $wheres2[] = 'a.vincode LIKE ' . $word;
                        $wheres2[] = 'a.year LIKE ' . $word;
                        $wheres2[] = 'a.price LIKE ' . $word;
                        $wheres2[] = 'a.bprice LIKE ' . $word;
                        $wheres2[] = 'a.id LIKE ' . $word;
                        $wheres2[] = 'a.specificmodel LIKE ' . $word;
                        $wheres2[] = 'a.otherinfo LIKE ' . $word;
                        $wheres2[] = 'c.title LIKE ' . $word;
                        $wheres2[] = 'c.description LIKE ' . $word;
                        $wheres2[] = 'mk.name LIKE ' . $word;
                        $wheres2[] = 'mk.description LIKE ' . $word;
                        $wheres2[] = 'md.name LIKE ' . $word;
                        $wheres2[] = 'md.description LIKE ' . $word;
                        $wheres2[] = 'bt.name LIKE ' . $word;
                        //$wheres2[] = 'eq.name LIKE ' . $word;
                        $wheres2[] = 'fl.name LIKE ' . $word;
                        $wheres2[] = 'tr.name LIKE ' . $word;
                        $where = implode(' OR ', $wheres2);
                    }
                }
            } else {
                $text = $db->Quote('%' . $db->getEscaped($text, true) . '%', false);
                $wheres2 = array();
                $wheres2[] = 'a.vincode LIKE ' . $text;
                $wheres2[] = 'a.year LIKE ' . $text;
                $wheres2[] = 'a.price LIKE ' . $text;
                $wheres2[] = 'a.bprice LIKE ' . $text;
                $wheres2[] = 'a.id LIKE ' . $text;
                $wheres2[] = 'a.specificmodel LIKE ' . $text;
                $wheres2[] = 'a.otherinfo LIKE ' . $text;
                $wheres2[] = 'c.title LIKE ' . $text;
                $wheres2[] = 'c.description LIKE ' . $text;
                $wheres2[] = 'mk.name LIKE ' . $text;
                $wheres2[] = 'mk.description LIKE ' . $text;
                $wheres2[] = 'md.name LIKE ' . $text;
                $wheres2[] = 'md.description LIKE ' . $text;
                $wheres2[] = 'bt.name LIKE ' . $text;
                //$wheres2[] = 'eq.name LIKE ' . $text;
                $wheres2[] = 'fl.name LIKE ' . $text;
                $wheres2[] = 'tr.name LIKE ' . $text;
                $where = '(' . implode(') OR (', $wheres2) . ')';
            }
            $query->where('(' . $where . ')');
        }
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
        }

        /* EXP Configuration */
        //$expparams = $this->getExpparams();
        $expgroup = $expparams->get('c_admanager_lspage_groupby');
        $expsort = $expparams->get('c_admanager_lspage_sortby');
        $orderCol = $this->getState('list.ordering');
        $orderDirn = $this->getState('list.direction');
        $expsort_top = null;
        if ($orderCol)
            $expgroup = $orderCol;
        if ($orderDirn)
            $expsort = $orderDirn;
        if ($orderCol == $expparams->get('c_admanager_lspage_groupby'))
            $expsort_top = 'a.ftop DESC, ';
        
        $query->order($expsort_top . $expgroup . ' ' . $expsort);
        return $query;
    }

    function zipcodeRadiusNew($zipcode, $radius, $location) {
        $db = JFactory::getDBO();
        $expparams = $this->getExpparams();
        $configradius = $expparams->get('c_general_distanceunit');
        if ($location)
            $sqldatabase = "#__expautos_expuser";
        else
            $sqldatabase = "#__expautos_admanager";
        $sql = "SELECT * FROM " . $sqldatabase . " WHERE zipcode = '" . (int) $zipcode . "'";
        $result = $db->setQuery($sql);
        $row = $db->loadObject();

        $lat1 = $row->latitude;
        $lon1 = $row->longitude;
        $d = $radius;
        $distansinit = $configradius;
        /*         * * distance unit ** */
        switch ($distansinit):
            /*             * * miles ** */
            case 1:
                $r = 3963;
                break;
            /*             * * nautical miles ** */
            case 2:
                $r = 3444;
                break;
            default:
                /*                 * * kilometers ** */
                $r = 6371;
        endswitch;
        //$r = 3959;
        //compute max and min latitudes / longitudes for search square
        $latN = rad2deg(asin(sin(deg2rad($lat1)) * cos($d / $r) + cos(deg2rad($lat1)) * sin($d / $r) * cos(deg2rad(0))));
        $latS = rad2deg(asin(sin(deg2rad($lat1)) * cos($d / $r) + cos(deg2rad($lat1)) * sin($d / $r) * cos(deg2rad(180))));
        $lonE = rad2deg(deg2rad($lon1) + atan2(sin(deg2rad(90)) * sin($d / $r) * cos(deg2rad($lat1)), cos($d / $r) - sin(deg2rad($lat1)) * sin(deg2rad($latN))));
        $lonW = rad2deg(deg2rad($lon1) + atan2(sin(deg2rad(270)) * sin($d / $r) * cos(deg2rad($lat1)), cos($d / $r) - sin(deg2rad($lat1)) * sin(deg2rad($latN))));
        $query = "SELECT zipcode FROM " . $sqldatabase . " WHERE (latitude <= $latN AND latitude >= $latS AND longitude <= $lonE AND longitude >= $lonW) AND (latitude != $lat1 AND longitude != $lon1) AND latitude != '' AND longitude != '' ORDER BY zipcode";
        $result = $db->setQuery($query);
        $row = $db->loadResultArray();
        $array = $row;
        if ($array) {
            $comma_separated = implode(",", $array);
        }
        if ($comma_separated) {
            $returnzip = $comma_separated . "," . $zipcode;
        } else {
            $returnzip = $zipcode;
        }
        return $returnzip;
    }

    function zipcodeRadius($zipcode, $radius) {
        $db = JFactory::getDBO();
        $expparams = $this->getExpparams();
        $configradius = $expparams->get('c_general_distanceunit');
        $sql = "SELECT * FROM #__expautos_cities WHERE city_zip = '" . (int) $zipcode . "'";
        $result = $db->setQuery($sql);
        $row = $db->loadObject();

        $lat1 = $row->city_latitude;
        $lon1 = $row->city_longitude;
        $d = $radius;
        $distansinit = $configradius;
        /*         * * distance unit ** */
        switch ($distansinit):
            /*             * * miles ** */
            case 1:
                $r = 3963;
                break;
            /*             * * nautical miles ** */
            case 2:
                $r = 3444;
                break;
            default:
                /*                 * * kilometers ** */
                $r = 6371;
        endswitch;
        //$r = 3959;
        //compute max and min latitudes / longitudes for search square
        $latN = rad2deg(asin(sin(deg2rad($lat1)) * cos($d / $r) + cos(deg2rad($lat1)) * sin($d / $r) * cos(deg2rad(0))));
        $latS = rad2deg(asin(sin(deg2rad($lat1)) * cos($d / $r) + cos(deg2rad($lat1)) * sin($d / $r) * cos(deg2rad(180))));
        $lonE = rad2deg(deg2rad($lon1) + atan2(sin(deg2rad(90)) * sin($d / $r) * cos(deg2rad($lat1)), cos($d / $r) - sin(deg2rad($lat1)) * sin(deg2rad($latN))));
        $lonW = rad2deg(deg2rad($lon1) + atan2(sin(deg2rad(270)) * sin($d / $r) * cos(deg2rad($lat1)), cos($d / $r) - sin(deg2rad($lat1)) * sin(deg2rad($latN))));
        $query = "SELECT city_zip FROM #__expautos_cities WHERE (city_latitude <= $latN AND city_latitude >= $latS AND city_longitude <= $lonE AND city_longitude >= $lonW) AND (city_latitude != $lat1 AND city_longitude != $lon1) AND city_name != '' ORDER BY city_state, city_name, city_latitude, city_longitude";
        $result = $db->setQuery($query);
        $row = $db->loadResultArray();
        $array = $row;
        $comma_separated = '';
        if ($array) {
            $comma_separated = implode(",", $array);
        }
        if ($comma_separated) {
            $returnzip = $comma_separated . "," . $zipcode;
        } else {
            $returnzip = $zipcode;
        }
        return $returnzip;
    }

    public function getExpItemid() {
        $expitemid = ExpAutosProExpparams::getExpLinkItemid();
        return $expitemid;
    }

}