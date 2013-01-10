<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');
jimport('joomla.application.component.helper');
require_once JPATH_COMPONENT . '/helpers/expparams.php';
require_once JPATH_COMPONENT . '/helpers/expfields.php';

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'tables');

class ExpautosproModelExpcompare extends JModelList {

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
                'ordering', 'a.ordering',
                'catid', 'a.catid', 'category_title',
            );
        }
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null) {
        $user = JFactory::getUser();
        $userid = (int) $user->id;
        $expuserid = (int) JRequest::getInt('userid', 0);
        if ($userid && $expuserid == 1) {
            $app = JFactory::getApplication();
            $app->redirect(JRoute::_('index.php?option=com_expautospro&view=explist&userid=' . $userid, false));
        }
        parent::populateState();
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
            jimport( 'joomla.user.helper' );
            //$listusergroups = implode(',', JAccess::getGroupsByUser($postuserid));
            $listusergroups =  implode(',', JUserHelper::getUserGroups($postuserid));
            $listusergroupid = ExpAutosProExpparams::getExpgroupid($listusergroups);
            $listgroupparams = ExpAutosProExpparams::getExpParams('userlevel', $listusergroupid);
        }
        return $listgroupparams;
    }

    function getListQuery(){
        $cache = JFactory::getCache('com_expautospro', '');
        $cache->clean('com_expautospro');
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $user = JFactory::getUser();
        $groups	= implode(',', $user->getAuthorisedViewLevels());
        $expparams = $this->getExpparams();
        $nullDate = $db->quote($db->getNullDate());

        $expid = (int) JRequest::getInt('id', 0);
        //$excompare = (int) JRequest::getInt('compare', 0);
        $excompare = JRequest::getVar('compare', array(), 'get', 'array');
        $excompare = array_filter($excompare, 'is_numeric');
        if($excompare){
            $excompare =  implode(',', $excompare);
        }else{
            $excompare =  0;
        }
        
        //print_r($excompare);

        $query->select(
                'a.*, IF( a.bprice != "",a.bprice,a.price ) price, a.imgmain AS img_name '
        );
        $query->from('#__expautos_admanager as a');

        $query->where('a.id IN ('.$excompare.')');

        /* Categories */
        /*
        $query->select('c.name AS category_name, c.id AS categid');
        $query->join('LEFT', '#__expautos_categories AS c ON c.id = a.catid');
         */
        $query->select('c.title AS category_name, c.id AS categid');
        $query->join('LEFT', '#__categories AS c ON c.id = a.catid');
        //$query->where('c.state=1');

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

        /* User */
        $query->select('user.name AS user_name, user.username AS user_username, user.email AS user_email');
        $query->join('LEFT', '#__users AS user ON user.id = a.user');

        /* EXP User */
        $query->select('expuser.lastname AS expuser_lastname, expuser.companyname AS expuser_companyname');
        $query->select('expuser.street AS expuser_street,expuser.phone AS expuser_phone');
        $query->select('expuser.web AS expuser_web, expuser.logo AS expuser_logo');
        $query->select('expuser.zipcode AS expuser_zipcode, expuser.fax AS expuser_fax');
        $query->select('expuser.mobphone AS expuser_mobphone, expuser.userinfo AS expuser_userinfo');
        $query->select('expuser.utop AS expuser_utop, expuser.ucommercial AS expuser_ucommercial');
        $query->select('expuser.uspecial AS expuser_uspecial');
        $query->select('expusercountry.name AS expuser_country, expusercity.city_name AS expuser_city');
        $query->select('expuserstate.name AS expuser_state');
        $query->join('LEFT', '#__expautos_expuser AS expuser ON expuser.userid = a.user');
        $query->join('LEFT', '#__expautos_country AS expusercountry ON expusercountry.id = expuser.country');
        $query->join('LEFT', '#__expautos_state AS expuserstate ON expuserstate.id = expuser.expstate');
        $query->join('LEFT', '#__expautos_cities AS expusercity ON expusercity.id = expuser.city');



        // Country Vehicle
        $query->select('cnt.name AS cnt_name');
        $query->join('LEFT', '#__expautos_country AS cnt ON cnt.id = a.country');

        // State Vehicle
        $query->select('st.name AS st_name');
        $query->join('LEFT', '#__expautos_state AS st ON st.id = a.expstate');

        // City Vehicle
        $query->select('ct.city_name AS ct_name');
        $query->join('LEFT', '#__expautos_cities AS ct ON ct.id = a.city');

        /* Uncomment ths code if you want use this variable

          // Int Color
          $query->select('inter.name AS intcolor_name, inter.image AS intcolor_image');
          $query->join('LEFT', '#__expautos_color AS inter ON inter.id = a.intcolor');

         */
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


        /* Currency 
          $query->select('cur.cname AS curname,cur.exchange AS curexchange,cur.cvariable AS curcvariable');
          $query->join('LEFT', '#__expautos_currency AS cur ON cur.exchange = 1 AND cur.state=1');
          //$query->where('mk.state=1');
         */

        // Join over the images.
        /* old version 3.5.1
        $query->select('img.name AS img_name');
        $query->join('LEFT', '#__expautos_images AS img ON a.id = img.catid AND img.ordering = 1');
         */


        /* Access */
        $query->select('ag.title AS access_level');
        $query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access AND ag.id = c.access');
        $query->where('c.access IN ('.$groups.')');


        
        return $query;
    }

    public function getExpItemid() {
        $expitemid = ExpAutosProExpparams::getExpLinkItemid();
        return $expitemid;
    }

}