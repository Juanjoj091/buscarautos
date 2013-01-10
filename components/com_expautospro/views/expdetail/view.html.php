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
require_once JPATH_COMPONENT . '/helpers/icon.php';

class ExpautosproViewExpdetail extends JView {

    protected $state;
    protected $form;
    protected $item;

    function display($tpl = null) {
        //$expcatId = JRequest::getInt('catid');
        $expgetcookie = JRequest::getVar('expshortlist', null,  $hash= 'COOKIE');
        $state = $this->get('State');
        $items = $this->get('Item');
        $expparams  = $this->get('Expparams');
        $expcatfields = $this->get('Expcatfields');
        $expimages = $this->get('Expimages');
        $expcatequipment = $this->get('Expcatequipment');
        $expgroupfield = $this->get('Expgroupfields');
        $expitemid      = $this->get('ExpItemid');
        $expskins = 'expdetail';
        
        /**
        * $expnumber code cannot be deleted!
        * Removing this code will automatically lead to a breach of the license.
        * Read more here www.feellove.eu
        */
        $expnumber = $this->get('Expnumber');
        //$this->form = $this->get('Form');

        $this->assignRef('items', $items);
        $this->assignRef('state', $state);
        $this->assignRef('expparams', $expparams);
        $this->assignRef('expcatfields', $expcatfields);
        $this->assignRef('expimages', $expimages);
        $this->assignRef('expcatequipment', $expcatequipment);
        $this->assignRef('expgroupfield', $expgroupfield);
	$this->assignRef('expgetcookie',$expgetcookie);
        $this->assignRef('expitemid', $expitemid);
        $this->assignRef('expskins', $expskins);
        
        /*
        * This code cannot be deleted!
        * Removing this code will automatically lead to a breach of the license.
        * Read more here www.feellove.eu
        */
        $this->assignRef('expnumber', $expnumber);
        echo '<div id="expnumber" style="display:none;">'.$this->expnumber.'</div>';
        /* End License code */
        if($expparams->get('c_general_uselifeduration')){
            if($expparams->get('c_general_sendexpiriesemail')){
                ExpAutosProExpparams::expexpires_mail();
            }
            if($expparams->get('c_general_lifedurationstatus')==1){
                ExpAutosProExpparams::delete_bydate();
            }else{
                ExpAutosProExpparams::changestatus_ads($expparams->get('c_general_lifedurationstatus'));
            }
        }
        
        $model = $this->getModel();
	$model->hit();

        $this->_prepareDocument();

        parent::display($tpl);
    }
    
    public function expSkins_save($cookies_name,$cookies_val){
        $lifetime = time() + 365*24*60*60;
        $config	= JFactory::getConfig();
        $cookie_domain = $config->get('cookie_domain');
        $cookie_path = "/";
        setcookie($cookies_name, $cookies_val, $lifetime, $cookie_path, $cookie_domain );
    }

    protected function _prepareDocument() {

        $app = JFactory::getApplication();
        $menus = $app->getMenu();
        $pathway = $app->getPathway();
        $title = null;
        $menu = $menus->getActive();
        if((int)$this->items['catid'] && $menu->query['view'] != 'expmake'){
            $link_makes = JRoute::_("index.php?option=com_expautospro&amp;view=expmake&amp;catid=".(int)$this->items['catid']);
            $pathway->addItem($this->items['category_name'], $link_makes);
        }
        if((int)$this->items['make'] && $menu->query['view'] != 'expmodel'){
            $link_modelsval = "index.php?option=com_expautospro&amp;view=expmodel";
            if((int)$this->items['catid']){
                $link_modelsval .= "&amp;catid=".(int)$this->items['catid'];
            }
            $link_modelsval .= "&amp;makeid=".(int)$this->items['make'];
            $link_models = JRoute::_($link_modelsval);
            $pathway->addItem($this->items['make_name'], $link_models);
        }
        if((int)$this->items['model']){
            $link_modelsval = "index.php?option=com_expautospro&amp;view=explist";
            if((int)$this->items['catid']){
                $link_modelsval .= "&amp;catid=".(int)$this->items['catid'];
            }
            if((int)$this->items['make']){
                $link_modelsval .= "&amp;makeid=".(int)$this->items['make'];
            }
            $link_modelsval .= "&amp;modelid=".(int)$this->items['model'];
            $link_models = JRoute::_($link_modelsval);
            $pathway->addItem($this->items['model_name'], $link_models);
        }
        $pathway->addItem(JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_PATHWAY_TEXT'));
        $specname = null;
        if($this->items['specificmodel'])
                $specname = " ".$this->items['specificmodel'];

        $this->document->setTitle(JText::_('COM_EXPAUTOSPRO_TEXT').JText::_('COM_EXPAUTOSPRO_CP_CATEGORY_TITLE_TEXT').":".$this->items['category_name'].JText::_('COM_EXPAUTOSPRO_CP_MAKE_TITLE_TEXT').":".$this->items['make_name'].JText::_('COM_EXPAUTOSPRO_CP_MODEL_TITLE_TEXT').":".$this->items['model_name'].$specname.JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_TITLE_TEXT'));
    }

}

?>
