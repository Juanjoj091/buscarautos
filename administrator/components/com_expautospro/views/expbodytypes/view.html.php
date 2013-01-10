<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

// No direct access to this file
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class ExpAutosProViewExpbodytypes extends JView
{
	protected $categories;
	protected $items;
	protected $pagination;
	protected $state;

	public function display($tpl = null)
	{
		$this->categories	= $this->get('CategoryOrders');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$build_options = array(
			JHtml::_('select.option', 'c', JText::_('JGLOBAL_BATCH_COPY')),
			JHtml::_('select.option', 'm', JText::_('JGLOBAL_BATCH_MOVE'))
		);
		$build_radiolist = JHTML::_( 'select.radiolist', $build_options, 'make_action', '', 'value', 'text', 'c');
		$this->buildradiolist = $build_radiolist;

		$this->addToolbar();
		require_once JPATH_COMPONENT .'/models/fields/categor.php';
		parent::display($tpl);
	}

	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.'/helpers/helper.php';
		JHtml::stylesheet( 'administrator/components/com_expautospro/assets/expautospro.css' );

		$canDo	= ExpAutosProHelper::getActions('expbodytype',$this->state->get('filter.category_id'));

		JToolBarHelper::title(JText::_('COM_EXPAUTOSPRO_BODYTYPES_TITLE_TEXT'), 'expautosprobodytype.png');
		if ($canDo->get('core.create')) {
			JToolBarHelper::addNew('expbodytype.add','JTOOLBAR_NEW');
		}

		if (($canDo->get('core.edit'))) {
			JToolBarHelper::editList('expbodytype.edit','JTOOLBAR_EDIT');
		}

		if ($canDo->get('core.edit.state')) {
			if ($this->state->get('filter.state') != 2){
				JToolBarHelper::divider();
				JToolBarHelper::custom('expbodytypes.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('expbodytypes.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}

			if ($this->state->get('filter.state') != -1 ) {
				JToolBarHelper::divider();
				if ($this->state->get('filter.state') != 2) {
					JToolBarHelper::archiveList('expbodytypes.archive','JTOOLBAR_ARCHIVE');
				}
				else if ($this->state->get('filter.state') == 2) {
					JToolBarHelper::unarchiveList('expbodytypes.publish', 'JTOOLBAR_UNARCHIVE');
				}
			}
		}
/*
		if ($canDo->get('core.edit.state')) {
			JToolBarHelper::custom('expbodytypes.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
		}
*/
		if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			JToolBarHelper::deleteList('', 'expbodytypes.delete','JTOOLBAR_EMPTY_TRASH');
			JToolBarHelper::divider();
		}
		else if ($canDo->get('core.edit.state')) {
			JToolBarHelper::trash('expbodytypes.trash','JTOOLBAR_TRASH');
			JToolBarHelper::divider();
		}

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_expautospro');
			JToolBarHelper::divider();
		}

		$bar = JToolBar::getInstance('toolbar');
			$bar->appendButton('Link', 'upload', 'COM_EXPAUTOSPRO_IMPORT_TEXT', 'index.php?option=com_expautospro&amp;view=import&amp;link=bodytype');

		$bar = JToolBar::getInstance('toolbar');
			$bar->appendButton('Link', 'export', 'COM_EXPAUTOSPRO_EXPORT_TEXT', 'index.php?option=com_expautospro&amp;view=export&amp;link=bodytype');
		JToolBarHelper::divider();

		JToolBarHelper::help('expbodytypes.html', $com = true);
	}
}
