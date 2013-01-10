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
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
require_once JPATH_COMPONENT . '/helpers/helper.php';
//require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/expimages.php';

class ExpautosproViewExpcompare extends JView {

    protected $state;
    protected $form;
    protected $item;
    protected $pagination;

    function display($tpl = null) {
        //$expmakeId = JRequest::getInt('makeid');
        $expgetcookie   = JRequest::getVar('expshortlist', null,  $hash= 'COOKIE');
        $state          = $this->get('State');
        $items          = $this->get('Items');
        $expparams      = $this->get('Expparams');
        $expcatfields   = $this->get('Expcatfields');
        $pagination	= $this->get('Pagination');
        $expgroupfield  = $this->get('Expgroupfields');
        $expitemid      = $this->get('ExpItemid');
        $expskins       = 'expcompare';
        //$this->form = $this->get('Form');

        $this->assignRef('items', $items);
        $this->assignRef('state', $state);
        $this->assignRef('expparams', $expparams);
        $this->assignRef('expcatfields', $expcatfields);
	$this->assignRef('pagination',	$pagination);
	$this->assignRef('expgroupfield',$expgroupfield);
	$this->assignRef('expgetcookie',$expgetcookie);
        $this->assignRef('expitemid', $expitemid);
        $this->assignRef('expskins', $expskins);
        //$this->assignRef('expmakeid', $expmakeId);

        $this->_prepareDocument();

        parent::display($tpl);
    }

    protected function _prepareDocument() {
        $app = JFactory::getApplication();
        $menus = $app->getMenu();
        $pathway = $app->getPathway();
        $title = null;
        $menu = $menus->getActive();
        $pathway->addItem(JText::_('COM_EXPAUTOSPRO_COMPARE_PATHWAY_TEXT'));

        $this->document->setTitle(JText::_('COM_EXPAUTOSPRO_TEXT')." - ".JText::_('COM_EXPAUTOSPRO_COMPARE_TITLE_TEXT'));
    }

}

?>
