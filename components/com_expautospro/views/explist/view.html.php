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
require_once JPATH_COMPONENT . '/helpers/grid.php';
//require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/expimages.php';

class ExpautosproViewExplist extends JView {

    protected $state;
    protected $form;
    protected $item;
    protected $pagination;

    function display($tpl = null) {
        //$document = JFactory::getDocument();
        $user = JFactory::getUser();
        $userid = (int) $user->id;
        $expuserid = (int) JRequest::getInt('userid', 0);
        if ($userid && $expuserid == 1) {
            $app = JFactory::getApplication();
            $app->redirect(JRoute::_('index.php?option=com_expautospro&view=explist&userid=' . $userid, false));
        }
        //$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/exptableordering.js');
        //$expmakeId = JRequest::getInt('makeid');
        $expgetcookie   = JRequest::getVar('expshortlist', null,  $hash= 'COOKIE');
        $state          = $this->get('State');
        $items          = $this->get('Items');
        $expparams      = $this->get('Expparams');
        $expcatfields   = $this->get('Expcatfields');
        $pagination	= $this->get('Pagination');
        $expgroupfield  = $this->get('Expgroupfields');
        $expitemid      = $this->get('ExpItemid');
        $expskins       = 'explist';
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
        if($this->items){
            if((int)JRequest::getInt('catid') && (int)JRequest::getInt('catid') == $this->items[0]->catid && $menu->query['view'] != 'expmake'){
                $link_makes = JRoute::_("index.php?option=com_expautospro&amp;view=expmake&amp;catid=".(int)JRequest::getInt('catid'));
                if(isset($this->items[0]->category_name))
                $pathway->addItem($this->items[0]->category_name, $link_makes);
            }
            if((int)JRequest::getInt('makeid') && $menu->query['view'] != 'expmodel'){
                $link_modelsval = "index.php?option=com_expautospro&amp;view=expmodel";
                if((int)JRequest::getInt('catid')){
                    $link_modelsval .= "&amp;catid=".(int)JRequest::getInt('catid');
                }
                $link_modelsval .= "&amp;makeid=".(int)JRequest::getInt('makeid');
                $link_models = JRoute::_($link_modelsval);
                if(isset($this->items[0]->make_name))
                $pathway->addItem($this->items[0]->make_name, $link_models);
            }
            if((int)JRequest::getInt('makeid')){
                if(isset($this->items[0]->model_name))
                    $pathway->addItem($this->items[0]->model_name);
                else
                    $pathway->addItem(JText::_('COM_EXPAUTOSPRO_CP_LIST_PATHWAY_TEXT'));
            }else{
                $pathway->addItem(JText::_('COM_EXPAUTOSPRO_CP_LIST_PATHWAY_TEXT'));
            }
            }
        $cattext = null;
        $maketext = null;
        $modeltext = null;
        $multi_models = $this->expparams->get('c_admanager_mdpage_multi',0);
        if($multi_models){
            $modelid = JRequest::getVar('modelid', array(), 'get', 'array');
        }else{
            $modelid = (int) JRequest::getInt('modelid', 0);
        }
        if(!empty($this->items[0]->category_name) && (int)JRequest::getInt('catid') && (int)JRequest::getInt('catid') == $this->items[0]->catid)
            $cattext = JText::_('COM_EXPAUTOSPRO_CP_CATEGORY_TITLE_TEXT').":".$this->items[0]->category_name;
        if(!empty($this->items[0]->make_name) && (int)JRequest::getInt('makeid'))
            $maketext = JText::_('COM_EXPAUTOSPRO_CP_MAKE_TITLE_TEXT').":".$this->items[0]->make_name;
        if(!empty($this->items[0]->model_name) && $modelid)
            $modeltext = JText::_('COM_EXPAUTOSPRO_CP_MODEL_TITLE_TEXT').":".$this->items[0]->model_name;

        $this->document->setTitle(JText::_('COM_EXPAUTOSPRO_TEXT').$cattext.$maketext.$modeltext.JText::_('COM_EXPAUTOSPRO_CP_LIST_TITLE_TEXT'));
    }

}

?>
