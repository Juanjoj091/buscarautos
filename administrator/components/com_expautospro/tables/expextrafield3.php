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

class ExpAutosProTableExpextrafield3 extends JTable {

    function __construct(&$_db) {
        parent::__construct('#__expautos_extrafield3', 'id', $_db);
    }

    public function bind($array, $ignore = array()) {

        return parent::bind($array, $ignore);
    }

    function store($updateNulls = false) {
        $table = JTable::getInstance('Expextrafield3', 'ExpAutosProTable');


        return parent::store($updateNulls);
    }

    function check() {
        $catId = '777';
        jimport('joomla.filter.output');

        $this->name = htmlspecialchars_decode($this->name, ENT_QUOTES);

        if ($this->state < 0) {
            $this->ordering = 0;
        } else if (empty($this->ordering)) {
            if ($this->id) {
                $this->reorder('`catid`=' . $this->_db->Quote($oldrow->catid) . ' AND state>=0');
            } else {
                $this->ordering = self::getNextOrder('`catid`=' . $catId . ' AND state>=0');
            }
        }

        return true;
    }

    public function publish($pks = null, $state = 1, $userId = 0) {
        $k = $this->_tbl_key;

        // Sanitize input.
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

        $table = JTable::getInstance('Expextrafield3', 'ExpAutosProTable');

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

}
