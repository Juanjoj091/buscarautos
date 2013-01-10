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
$canOrder = $user->authorise('core.edit.state', 'com_expautospro.exppayment');
$saveOrder = $listOrder == 'a.ordering';
?>
<form action="<?php echo JRoute::_('index.php?option=com_expautospro&view=exppayments'); ?>" method="post" name="adminForm" id="adminForm">
    <fieldset id="filter-bar">
        <div class="filter-search fltlft">
            <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
            <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_EXPAUTOSPRO_SEARCH_IN_NAME'); ?>" />
            <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
            <button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
        </div>
		<div class="filter-select fltrt">

			<select name="filter_state" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true);?>
			</select>
		</div>
	</fieldset>
	<div class="clr"> </div>
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
                <th align="left" class="title" width="40%">
                    <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_PAYMENTS_ITEMID_TEXT', 'adid', $listDirn, $listOrder); ?>
                </th>
                <th align="center">
                    <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_PAYMENTS_ITEMNAME_TEXT', 'payname', $listDirn, $listOrder); ?>
                </th>
                <th align="center">
                    <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_PAYMENTS_SUM_TEXT', 'paysum', $listDirn, $listOrder); ?>
                </th>
                <th align="center">
                    <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_PAYMENTS_STATUS_TEXT', 'status', $listDirn, $listOrder); ?>
                </th>
                <th align="center">
                    <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_PAYMENTS_DATE_TEXT', 'paydate', $listDirn, $listOrder); ?>
                </th>
                <th align="center">
                    <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_PAYMENTS_PAYEMAIL_TEXT', 'payval', $listDirn, $listOrder); ?>
                </th>
                <th align="center">
                    <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_PAYMENTS_USER_TEXT', 'jusername', $listDirn, $listOrder); ?>
                </th>
                <th align="center">
                    <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_PAYMENTS_PAYID_TEXT', 'payid', $listDirn, $listOrder); ?>
                </th>
                <th align="center">
                    <?php echo JHtml::_('grid.sort', 'COM_EXPAUTOSPRO_PAYMENTS_NOTICE_TEXT', 'paynotice', $listDirn, $listOrder); ?>
                </th>
                <th align="center" width="5%">
                <?php echo JHtml::_('grid.sort', 'JPUBLISHED', 'state', $listDirn, $listOrder); ?>
                </th>
                <th width="20">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="13"><?php echo $this->pagination->getListFooter(); ?></td>
            </tr>
        </tfoot>
        <tbody>
            <?php
            foreach ($this->items as $i => $item) :
                $ordering = ($listOrder == 'a.ordering');
                $canCreate = $user->authorise('core.create', 'com_expautospro.exppayment.' . $item->id);
                $canEdit = $user->authorise('core.edit', 'com_expautospro.exppayment.' . $item->id);
                $canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $user->get('id') || $item->checked_out == 0;
                $canChange = $user->authorise('core.edit.state', 'com_expautospro.exppayment.' . $item->id) && $canCheckin;
                $link = JRoute::_('index.php?option=com_expautospro&task=exppayment.edit&id=' . $item->id);
                $userlink = JRoute::_('index.php?option=com_users&task=user.edit&id=' . $item->payuser);
                if($item->paysysval != 5){
                    $editid = JRoute::_('index.php?option=com_expautospro&task=expadmanager.edit&id=' . $item->payid);
                }else{
                    $editid = JRoute::_('index.php?option=com_users&task=user.edit&id=' . $item->payuser);
                }
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
                        <span class="editlinktip hasTip" title="<?php echo JText::_('COM_EXPAUTOSPRO_PAYMENT_EDIT_TITLE'); ?>">
                            <a href="<?php echo $link; ?>">
    <?php echo $this->escape($item->adid); ?>
                            </a>
                        </span>
                    </td>
                    <td align="center">
    <?php echo $this->escape($item->payname); ?>
                    </td>
                    <td align="center">
    <?php echo $this->escape($item->paysum); ?>
                    </td>
                    <td align="center">
    <?php echo $this->escape($item->status); ?>
                    </td>
                    <td align="center">
    <?php echo $this->escape($item->paydate); ?>
                    </td>
                    <td align="center">
    <?php echo $this->escape($item->payval); ?>
                    </td>
                    <td align="center">
                         <a href="<?php echo $userlink; ?>">
                                <?php echo $this->escape($item->jusername); ?>
                         </a>
                    </td>
                    <td align="center">
                         <a href="<?php echo $editid; ?>">
                                <?php echo $this->escape($item->payid); ?>
                         </a>
                    </td>
                    <td align="center">
    <?php echo $this->escape($item->paynotice); ?>
                    </td>
                    <td align="center">
                            <?php echo JHtml::_('jgrid.published', $item->state, $i, 'exppayments.', $canChange, 'cb'); ?>
                    </td>
                    <td align="center">
    <?php echo $item->id; ?>
                    </td>
                </tr>
<?php endforeach; ?>
        </tbody>
    </table>
    <div>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
<?php echo JHtml::_('form.token'); ?>
    </div>
</form>
