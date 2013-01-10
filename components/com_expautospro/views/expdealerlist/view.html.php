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
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/expimages.php';

class ExpautosproViewExpdealerlist extends JView {

    protected $state;
    protected $form;
    protected $item;
    protected $pagination;

    function display($tpl = null) {
        //$expmakeId = JRequest::getInt('makeid');
        $state          = $this->get('State');
        $items          = $this->get('Items');
        $expparams      = $this->get('Expparams');
        $expcatfields   = $this->get('Expcatfields');
        $pagination     = $this->get('Pagination');
        $expitemid      = $this->get('ExpItemid');
        $expskins       = 'expdealerlist';
        //$this->form = $this->get('Form');
        //print_r($items);

        $this->assignRef('items', $items);
        $this->assignRef('state', $state);
        $this->assignRef('expparams', $expparams);
        $this->assignRef('expcatfields', $expcatfields);
        $this->assignRef('pagination', $pagination);
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
        //$link_cat = JRoute::_("index.php?option=com_expautospro");
        //$pathway->addItem(JText::_('COM_EXPAUTOSPRO_CP_CATEGORIES_PATHWAY_TEXT'), $link_cat);
        $pathway->addItem(JText::_('COM_EXPAUTOSPRO_CP_DEALERLIST_PATHWAY_TEXT'));
        $menu = $menus->getActive();

        $this->document->setTitle(JText::_('COM_EXPAUTOSPRO_CP_DEALER_LIST_TEXT'));
    }

    function getPrewText($text, $maxwords = 30, $maxchar = 100) {
        $sep = ' ';
        $words = explode($sep, $text);
        $char = iconv_strlen($text, 'utf-8');
        if (count($words) > $maxwords) {
            $text = join($sep, array_slice($words, 0, $maxwords));
        }
        if ($char > $maxchar) {
            $text = iconv_substr($text, 0, $maxchar, 'utf-8');
        }
        return $text;
    }

}

?>
