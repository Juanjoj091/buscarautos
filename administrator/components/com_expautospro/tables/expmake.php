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

class ExpAutosProTableExpmake extends JTable
{
	function __construct(&$_db)
	{
		parent::__construct('#__expautos_make', 'id', $_db);
	}

	public function bind($array, $ignore = array())
	{
		if (isset($array['params']) && is_array($array['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		if (isset($array['metadata']) && is_array($array['metadata'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string) $registry;
		}

		return parent::bind($array, $ignore);
	}

	function store($updateNulls = false)
	{
		if (is_array($this->params)) {
			$registry = new JRegistry();
			$registry->loadArray($this->params);
			$this->params = (string)$registry;
		}
		$table = JTable::getInstance('Expmake', 'ExpAutosProTable');

		if ($table->load(array('alias'=>$this->alias,'catid'=>$this->catid)) && ($table->id != $this->id || $this->id==0)) {
			$this->setError(JText::_('COM_EXPAUTOSPRO_ERROR_UNIQUE_ALIAS'));
			return false;
		}

		return parent::store($updateNulls);
	}

	function check()
	{
		jimport('joomla.filter.output');

		$this->name = htmlspecialchars_decode($this->name, ENT_QUOTES);

		$this->alias = JApplication::stringURLSafe($this->alias);
		if (empty($this->alias)) {
			$this->alias = JApplication::stringURLSafe($this->name);
		}

		if ($this->state < 0) {
			$this->ordering = 0;
		} else if (empty($this->ordering)) {
			if($this->id){
			$this->reorder('`catid`=' . $this->_db->Quote($oldrow->catid).' AND state>=0');
			}else{
			$this->ordering = self::getNextOrder('`catid`='. $this->_db->Quote($this->catid).' AND state>=0');
			}
		}

		return true;

		if (!empty($this->metakey)) {
			$bad_characters = array("\n", "\r", "\"", "<", ">");
			$after_clean = JString::str_ireplace($bad_characters, "", $this->metakey);
			$keys = explode(',', $after_clean);
			$clean_keys = array();
			foreach($keys as $key) {
				if (trim($key)) {
					$clean_keys[] = trim($key);
				}
			}
			$this->metakey = implode(", ", $clean_keys);
		}

		if (!empty($this->metadesc)) {
			$bad_characters = array("\"", "<", ">");
			$this->metadesc = JString::str_ireplace($bad_characters, "", $this->metadesc);
		}
		return true;
	}

	public function publish($pks = null, $state = 1, $userId = 0)
	{
		$k = $this->_tbl_key;

		JArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state  = (int) $state;

		if (empty($pks))
		{
			if ($this->$k) {
				$pks = array($this->$k);
			}
			else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		$table = JTable::getInstance('Expmake','ExpAutosProTable');

		foreach ($pks as $pk)
		{
			if(!$table->load($pk))
			{
				$this->setError($table->getError());
			}

				$table->state = $state;
				$table->check();
				if (!$table->store())
				{
					$this->setError($table->getError());
				}

		}
		return count($this->getErrors())==0;
	}


}
