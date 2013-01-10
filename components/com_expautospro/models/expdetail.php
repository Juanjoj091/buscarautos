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

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'tables');

class ExpautosproModelExpdetail extends JModelList {

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
        $postexpcatid = $this->_item['catid'];
        $expcatfields='';
        if ($postexpcatid)
            $expcatfields = ExpAutosProExpparams::getCatParams($postexpcatid);
        return $expcatfields;
    }
    
    function getExpgroupfields() {
        $listgroupparams = '';
        if($this->_item['user']>0){
            jimport( 'joomla.user.helper' );
            //$listusergroups =  implode(',', JAccess::getGroupsByUser($this->_item['user']));
            $listusergroups =  implode(',', JUserHelper::getUserGroups($this->_item['user']));
            $listusergroupid = ExpAutosProExpparams::getExpgroupid($listusergroups);
            $listgroupparams = ExpAutosProExpparams::getExpParams('userlevel',$listusergroupid);
        }
        return $listgroupparams;
    }

    function getExpimages() {
        $postid = $this->_item['id'];
        if ($postid) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('img.name as imgname,img.description as imgdescription');
            $query->from('#__expautos_images as img');
            $query->where('img.catid = ' . (int) $postid);
            $query->where('img.state = 1');
            $query->order('ordering');
            $db->setQuery($query);
            $imgquery = $db->loadAssocList();
            return $imgquery;
        }
    }

    /**
    * function getExpnumber() code cannot be deleted!
    * Removing this code will automatically lead to a breach of the license.
    * Read more here www.feellove.eu
    */
    function getExpnumber() {
        $db = JFactory::getDBO();
        $db->setQuery("SELECT license FROM #__expautos_config");			
        $number = $db->loadResult();
        return $number;
    }

    function getExpcatequipment() {
        //print_r($this->_item['catid']);
        /* equipment */
        $expqeuip = '';
        $catequip = $this->_item['catid'];
        $rowequip = $this->_item['equipment'];
        if ($rowequip && $catequip) {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('cateq.id, cateq.name');
            $query->from('#__expautos_catequipment as cateq');
            $query->where('cateq.state=1');
            $query->where('cateq.catid = ' . (int) $catequip);
            $query->order('cateq.ordering');
            $db->setQuery($query);
            $expqeuip = $db->loadObjectList();
        }
        return $expqeuip;
    }

    function getItem() {
        if (!isset($this->_item)) {
            $cache = JFactory::getCache('com_expautospro', '');
            $cache->clean('com_expautospro');

            $id = $this->getState('expdetail.id');
            $postid = JRequest::getInt('id', 0);

            $this->_item = $cache->get($id);

            if ($this->_item === false) {
                $user	= JFactory::getUser();
                $groups	= implode(',', $user->getAuthorisedViewLevels());
                $db = $this->getDbo();
                $query = $db->getQuery(true);
                $nullDate = $db->quote($db->getNullDate());

                $postexpcat = JRequest::getInt('catid');

                $query->select('a.*');
                $query->from('#__expautos_admanager as a');
                $query->where('a.state=1');
                $query->where('a.id=' . (int) $postid);

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

                /* Condition */
                $query->select('cond.id AS condid,cond.name AS cond_name');
                $query->join('LEFT', '#__expautos_condition AS cond ON cond.id = a.condition');

                /* User */
                $query->select('user.id AS userwid,user.name AS user_name, user.username AS user_username, user.email AS user_email');
                $query->join('LEFT', '#__users AS user ON user.id = a.user');

                /* EXP User */
                $query->select('expuser.id AS expuserwid,expusercountry.id AS expusercountrywid,expuserstate.id AS expuserstatewid,expusercity.id AS expusercitywid');
                $query->select('expuser.lastname AS expuser_lastname, expuser.companyname AS expuser_companyname');
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

                // Int Color
                $query->select('inter.id AS interid,inter.name AS intcolor_name, inter.image AS intcolor_image');
                $query->join('LEFT', '#__expautos_color AS inter ON inter.id = a.intcolor');

                // Extra Field 1
                $query->select('ef1.id AS ef1id,ef1.name AS ef1_name');
                $query->join('LEFT', '#__expautos_extrafield1 AS ef1 ON ef1.id = a.extrafield1');

                // Extra Field 2
                $query->select('ef2.id AS ef2id,ef2.name AS ef2_name');
                $query->join('LEFT', '#__expautos_extrafield2 AS ef2 ON ef2.id = a.extrafield2');

                // Extra Field 3
                $query->select('ef3.id AS ef3id,ef3.name AS ef3_name');
                $query->join('LEFT', '#__expautos_extrafield3 AS ef3 ON ef3.id = a.extrafield3');

                /* Access */
                $query->select('ag.title AS access_level');
                $query->join('LEFT', '#__viewlevels AS ag ON ag.id = c.access');
                $query->where('c.access IN ('.$groups.')');

                /* EXP Configuration */
                $expparams = $this->getExpparams();

                $db->setQuery((string) $query);

                if (!$db->query()) {
                    JError::raiseError(500, $db->getErrorMsg());
                }

                $this->_item = $db->loadAssoc();
                $cache->store($this->_item, $id);
            }
        }

        return $this->_item;
    }

    function getItems() {
        if (!isset($this->cache['items'])) {
            $this->cache['items'] = parent::getItems();

            foreach ($this->cache['items'] as &$item) {
                $parameters = new JRegistry;
                $parameters->loadJSON($item->params);
                $item->params = $parameters;
            }
        }
        return $this->cache['items'];
    }

    protected function populateState($ordering = null, $direction = null) {
        
    }
    
    public function hit() {
        $hits = $this->_item['hits'];
        $id = $this->_item['id'];
        if ($id) {
            $db = $this->getDbo();
            $query = $db->getQuery(true);
            $query->update('`#__expautos_admanager`');
            $query->set('`hits` = 1 + ' . (int)$hits);
            $query->where('id = ' . (int) $id);
            $db->setQuery((string) $query);
            $db->query();
            // Check for a database error.
            if ($db->getErrorNum()) {
                JError::raiseWarning(500, $db->getErrorMsg());
                return false;
            }
        }

        return true;
    }

    public function getExpItemid() {
        $expitemid = ExpAutosProExpparams::getExpLinkItemid();
        return $expitemid;
    }

}