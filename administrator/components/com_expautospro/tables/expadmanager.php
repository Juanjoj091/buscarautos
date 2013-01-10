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
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/expimages.php';

class ExpAutosProTableExpadmanager extends JTable {

    function __construct(&$_db) {
        parent::__construct('#__expautos_admanager', 'id', $_db);
        //$expreqfield = ExpAutosProImages::getExpParams('config',1);
        //$expexpirdate = $expreqfield->get('c_general_adlifeduration');
        if($this->user){
            $expUserid = $this->user;
        }else{
            $user = JFactory::getUser();
            $expUserid = $user->id;
        }
        $expreqfield = ExpAutosProImages::getExpDealersParams($expUserid);
        $expexpirdate = $expreqfield->get('c_adlifeduration');
        if(!$expexpirdate)
            $expexpirdate = 30;
        $this->creatdate = JFactory::getDate()->toMySQL();
        $this->expirdate = JFactory::getDate('+'.$expexpirdate.' day '.date('Y-m-d',strtotime('now')))->toMySQL();
    }

    public function bind($array, $ignore = array()) {
        if (isset($array['params']) && is_array($array['params'])) {
            $registry = new JRegistry();
            $registry->loadArray($array['params']);
            $array['params'] = (string) $registry;
        }

        return parent::bind($array, $ignore);
    }

    function store($updateNulls = false) {
        /*
        if (is_array($this->params)) {
            $registry = new JRegistry();
            $registry->loadArray($this->params);
            $this->params = (string) $registry;
        }
         * 
         */
        if ($this->equipment) {
            $this->equipment = implode(',', $this->equipment);
        }else{
          $this->equipment = '';
        }
        if(!$this->creatdate){
            //$expreqfield = ExpAutosProImages::getExpParams('config',1);
            //$expexpirdate = $expreqfield->get('c_general_adlifeduration');
        if($this->user){
            $expUserid = $this->user;
        }else{
            $user = JFactory::getUser();
            $expUserid = $user->id;
        }
            $expreqfield = ExpAutosProImages::getExpDealersParams($expUserid);
            $expexpirdate = $expreqfield->get('c_adlifeduration');
            $this->creatdate = JFactory::getDate()->toMySQL();
            $this->expirdate = JFactory::getDate('+'.$expexpirdate.' day '.date('Y-m-d',strtotime('now')))->toMySQL(); 
        }
        $this->mileage = JFilterOutput::cleanText(str_replace(' ','',$this->mileage));
        $this->price = JFilterOutput::cleanText(str_replace(' ','',$this->price));
        $this->bprice = JFilterOutput::cleanText(str_replace(' ','',$this->bprice));
        $this->expprice = JFilterOutput::cleanText(str_replace(' ','',$this->expprice));
        $this->embedcode=strip_tags(stripslashes($_POST['jform']['embedcode']),'<object><param><embed><iframe>');
        $expcf = ExpAutosProImages::getExpParams('config',1);
        $maxotherinfo = $expcf->get('c_admanager_add_maxotherinfo');
        $this->otherinfo = substr($this->otherinfo,0,$maxotherinfo);

        $table = JTable::getInstance('Expadmanager', 'ExpAutosProTable');
        
        return parent::store($updateNulls);
    }

    function check() {
        jimport('joomla.filter.output');
        //$this->name = htmlspecialchars_decode($this->name, ENT_QUOTES);
        if ($this->state < 0) {
            $this->ordering = 0;
        } else if (empty($this->ordering)) {
            if ($this->id) {
                $this->reorder('`catid`=' . $this->_db->Quote($oldrow->catid) . ' AND state>=0');
            } else {
                $this->ordering = self::getNextOrder('`catid`=' . $this->_db->Quote($this->catid) . ' AND state>=0');
            }
        }

        return true;
    }

    public function publish($pks = null, $state = 1, $userId = 0) {
        $k = $this->_tbl_key;

        JArrayHelper::toInteger($pks);
        $userId = (int) $userId;
        $state = (int) $state;

        if (empty($pks)) {
            if ($this->$k) {
                $pks = array($this->$k);
            }
            else {
                $this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
                return false;
            }
        }

        $table = JTable::getInstance('Expadmanager', 'ExpAutosProTable');

        foreach ($pks as $pk) {
            if (!$table->load($pk)) {
                $this->setError($table->getError());
            }

            $table->state = $state;
            $table->check();
            if (!$table->store()) {
                $this->setError($table->getError());
            }
        }
        return count($this->getErrors()) == 0;
    }

    public function exptask($pks = null, $state = 1, $userId = 0,$expmodel='') {
        $k = $this->_tbl_key;

        JArrayHelper::toInteger($pks);
        $userId = (int) $userId;
        $state = (int) $state;

        if (empty($pks)) {
            if ($this->$k) {
                $pks = array($this->$k);
            }
            else {
                $this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
                return false;
            }
        }

        $table = JTable::getInstance('Expadmanager', 'ExpAutosProTable');

        foreach ($pks as $pk) {
            if (!$table->load($pk)) {
                $this->setError($table->getError());
            }
            switch ($expmodel) {
                case 'ftop':
                $table->ftop = $state;
                    break;
                case 'fcommercial':
                $table->fcommercial = $state;
                    break;
                case 'special':
                    $table->special = $state;
                    break;
                case 'solid':
                    $table->solid = $state;
                    break;
                case 'expreserved':
                    $table->expreserved = $state;
                    break;
            }

                $table->check();

                if (!$table->store()) {
                    $this->setError($table->getError());
                }
        }
        return count($this->getErrors()) == 0;
    }

}
