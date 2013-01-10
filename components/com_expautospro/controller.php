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

jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT . '/helpers/expparams.php';


class ExpautosproController extends JController {
/*
    public function display($cachable = false, $urlparams = false) {
        $cachable = false;

        $document = JFactory::getDocument();

        $vName = JRequest::getCmd('view', 'expautospro');
        JRequest::setVar('view', $vName);
        $user = JFactory::getUser();
        $expuser = ExpAutosProExpparams::expuser($user->id);
        if($vName == 'expadd' && !$expuser){
           $this->setMessage(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_INSERT_DEALERINFO_TEXT'));
           $this->setRedirect(JRoute::_('index.php?option=com_expautospro&view=expuser&layout=edit', false));
        }

        $safeurlparams = array('catid' => 'INT', 'id' => 'INT', 'cid' => 'ARRAY', 'year' => 'INT', 'month' => 'INT', 'limit' => 'INT', 'limitstart' => 'INT',
            'showall' => 'INT', 'return' => 'BASE64', 'filter' => 'STRING', 'filter_order' => 'CMD', 'filter_order_Dir' => 'CMD', 'filter-search' => 'STRING', 'print' => 'BOOLEAN', 'lang' => 'CMD');

        parent::display($cachable, $safeurlparams);

        return $this;
    }
 * 
 */
    public function display($cachable = false, $urlparams = false)
	{
		// Initialise variables.
		$cachable	= false;	// Huh? Why not just put that in the constructor?
		$user		= JFactory::getUser();

		// Set the default view name and format from the Request.
		// Note we are using w_id to avoid collisions with the router and the return page.
		// Frontend is a bit messier than the backend.
		$id		= JRequest::getInt('w_id');
		$vName	= JRequest::getCmd('view', 'categories');
		JRequest::setVar('view', $vName);

		if ($user->get('id') ||($_SERVER['REQUEST_METHOD'] == 'POST' && $vName = 'categories')) {
			$cachable = false;
		}

		$safeurlparams = array(
			'id'				=> 'INT',
			'limit'				=> 'INT',
			'limitstart'		=> 'INT',
			'filter_order'		=> 'CMD',
			'filter_order_Dir'	=> 'CMD',
			'lang'				=> 'CMD'
		);

		
                $expuser = ExpAutosProExpparams::expuser($user->id);
                if($vName == 'expadd' && !$expuser){
                   $return_url = 'index.php?option=com_expautospro&view=expadd&layout=edit';
                   $this->setMessage(JText::_('COM_EXPAUTOSPRO_CP_EXPADD_ERROR_INSERT_DEALERINFO_TEXT'));
                   $this->setRedirect(JRoute::_('index.php?option=com_expautospro&view=expuser&layout=edit&return='.base64_encode($return_url), false));
                }

		return parent::display($cachable, $safeurlparams);
	}

}
