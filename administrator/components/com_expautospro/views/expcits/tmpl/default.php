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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHTML::_('script', 'system/multiselect.js', false, true);
$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$canOrder = $user->authorise('core.edit.state', 'com_expautospro.expcit');
$saveOrder = $listOrder == 'a.ordering';
$statefilter = $this->state->get('filter.country_id');
?>
<form action="<?php echo JRoute::_('index.php?option=com_expautospro&view=expcits'); ?>" method="post" name="adminForm" id="adminForm">
    <fieldset id="filter-bar">
        <div class="filter-search fltlft">
            <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
            <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_EXPAUTOSPRO_SEARCH_IN_NAME'); ?>" />
            <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
            <button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
        </div>
        <div class="filter-select fltrt">

            <select name="filter_country_id" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('COM_EXPAUTOSPRO_COUNTRSELECT_DESC'); ?></option>
                <?php echo JHtml::_('select.options', JFormFieldCategor::getOptions('country'), 'value', 'text', $this->state->get('filter.country_id')); ?>
            </select>

            <select name="filter_category_id" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('COM_EXPAUTOSPRO_STATESELECT_DESC'); ?></option>
                <?php echo JHtml::_('select.options', JFormFieldExpcities::getOptions('1',$statefilter), 'value', 'text', $this->state->get('filter.category_id')); ?>
            </select>

            <select name="filter_state" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED'); ?></option>
                <?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true); ?>
            </select>

            <select name="filter_language" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE'); ?></option>
                <?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language')); ?>
            </select>
        </div>
    </fieldset>
    <div class="clr"> </div>
    <table class="adminlist">
        <thead>
            <tr>
                <th width="5">
                    <?php echo JText::_('NUM'); ?>
                </th>
                <th width="20">
                    <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
                </th>
                <th align="left" class="title">
                    <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CITS_NAME_TEXT', 'a.city_name', $listDirn, $listOrder); ?>
                </th>
                <th width="10%">
                    <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_STATE_TEXT', 'category_name', $listDirn, $listOrder); ?>
                </th>
                <th width="10%">
                    <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_COUNTR_TEXT', 'country_name', $listDirn, $listOrder); ?>
                </th>
                <th align="center">
                    <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CITS_ZIPNAME_TEXT', 'a.city_zip', $listDirn, $listOrder); ?>
                </th>
                <th align="center">
                    <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CITS_STATENAME_TEXT', 'a.city_state', $listDirn, $listOrder); ?>
                </th>
                <th align="center">
                    <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CITS_LATNAME_TEXT', 'a.city_latitude', $listDirn, $listOrder); ?>
                </th>
                <th align="center">
                    <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CITS_LONGNAME_TEXT', 'a.city_longitude', $listDirn, $listOrder); ?>
                </th>
                <th align="center">
                    <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_CITS_COUNTRNAME_TEXT', 'a.city_county', $listDirn, $listOrder); ?>
                </th>
                <th align="center" width="5%">
                    <?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'state', $listDirn, $listOrder); ?>
                </th>
                <th align="center" width="100">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ORDERING', 'a.ordering', $listDirn, $listOrder); ?>
                    <?php if ($canOrder && $saveOrder) : ?>
                        <?php echo JHtml::_('grid.order', $this->items, 'filesave.png', 'expcits.saveorder'); ?>
                    <?php endif; ?>
                </th>
                <th width="5%">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'a.language', $this->state->get('list.direction'), $this->state->get('list.ordering')); ?>
                </th>
                <th width="20">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="14"><?php echo $this->pagination->getListFooter(); ?></td>
            </tr>
        </tfoot>
        <tbody>
            <?php
            foreach ($this->items as $i => $item) :
                $ordering = ($listOrder == 'a.ordering');
                $canCreate = $user->authorise('core.create', 'com_expautospro.expcit.' . $item->id);
                $canEdit = $user->authorise('core.edit', 'com_expautospro.expcit.' . $item->id);
                $canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $user->get('id') || $item->checked_out == 0;
                $canChange = $user->authorise('core.edit.state', 'com_expautospro.expcit.' . $item->id) && $canCheckin;
                $link = JRoute::_('index.php?option=com_expautospro&task=expcit.edit&id=' . $item->id);
                //print_r($item);
                ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td>
    <?php echo $this->pagination->getRowOffset($i); ?>
                    </td>
                    <td width="20">
    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                    </td>
                    <td>
                        <span class="editlinktip hasTip" title="<?php echo JText::_('COM_EXPAUTOSPRO_CIT_EDIT_TITLE'); ?>::<?php echo $this->escape($item->city_name); ?>">
                            <a href="<?php echo $link; ?>">
    <?php echo $this->escape($item->city_name); ?>
                            </a>
                        </span>
                    </td>
                    <td class="center">
    <?php echo $this->escape($item->category_name); ?>
                    </td>
                    <td class="center">
    <?php echo $this->escape($item->country_name); ?>
                    </td>
                    <td class="center">
    <?php echo $this->escape($item->city_zip); ?>
                    </td>
                    <td class="center">
    <?php echo $this->escape($item->city_state); ?>
                    </td>
                    <td class="center">
    <?php echo $this->escape($item->city_latitude); ?>
                    </td>
                    <td class="center">
    <?php echo $this->escape($item->city_longitude); ?>
                    </td>
                    <td class="center">
    <?php echo $this->escape($item->city_county); ?>
                    </td>
                    <td align="center">
    <?php echo JHtml::_('jgrid.published', $item->state, $i, 'expcits.', $canChange, 'cb'); ?>
                    </td>
                    <td class="order">
                        <?php if ($canChange) : ?>
                            <?php if ($saveOrder) : ?>
            <?php if ($listDirn == 'asc') : ?>
                                    <span><?php echo $this->pagination->orderUpIcon($i, (@$this->items[$i - 1]->catid == $item->catid), 'expcits.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
                                    <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, (@$this->items[$i + 1]->catid == $item->catid), 'expcits.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
            <?php elseif ($listDirn == 'desc') : ?>
                                    <span><?php echo $this->pagination->orderUpIcon($i, (@$this->items[$i - 1]->catid == $item->catid), 'expcits.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
                                    <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, (@$this->items[$i + 1]->catid == $item->catid), 'expcits.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php $disabled = $saveOrder ? '' : 'disabled="disabled"'; ?>
                            <input type="text" name="order[]" size="5" value="<?php echo $item->ordering; ?>" <?php echo $disabled; ?> class="text-area-order" />
                        <?php else : ?>
                            <?php echo $item->ordering; ?>
    <?php endif; ?>
                    </td>
                    <td class="center nowrap">
                        <?php if ($item->language == '*'): ?>
                            <?php echo JText::alt('JALL', 'language'); ?>
                        <?php else: ?>
                            <?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
    <?php endif; ?>
                    </td>
                    <td align="center">
    <?php echo $item->id; ?>
                    </td>
                </tr>
<?php endforeach; ?>
        </tbody>
    </table>
<?php echo $this->loadTemplate('move'); ?>
    <div>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
<?php echo JHtml::_('form.token'); ?>
    </div>
</form>
