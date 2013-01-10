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

class ExpAutosProTableExpimages extends JTable {

    function __construct(&$_db) {
        parent::__construct('#__expautos_images', 'id', $_db);
    }

    public function bind($array, $ignore = array()) {

        return parent::bind($array, $ignore);
    }

    function store($updateNulls = false) {
        $table = JTable::getInstance('Expimages', 'ExpAutosProTable');
        
        return parent::store($updateNulls);
    }

    function check() {
        jimport('joomla.filter.output');
        //$this->name = htmlspecialchars_decode($this->name, ENT_QUOTES);
        if ($this->state < 0) {
            $this->ordering = 0;
        } else if (empty($this->ordering)) {
            if ($this->id) {
                $this->reorder('`ordering`=' . $this->_db->Quote($oldrow->ordering) . ' AND state>=0');
            } else {
                $this->ordering = self::getNextOrder('`ordering`=' . $this->_db->Quote($this->ordering) . ' AND state>=0');
            }
        }

        return true;
    }

}
