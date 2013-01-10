<?php
/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

// no direct access
defined('_JEXEC') or die;

// Component Helper
jimport('joomla.application.component.helper');
jimport('joomla.application.categories');

class ExpautosproCategories extends JCategories
{
	public function __construct($options = array())
	{
		$options['table'] = '#__expautos_admanager';
		$options['extension'] = 'com_expautospro';
		//$options['statefield'] = 'published';
		parent::__construct($options);
	}
}
