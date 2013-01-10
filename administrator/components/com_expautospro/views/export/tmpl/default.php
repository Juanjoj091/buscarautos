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
?>
<form action="<?php echo JRoute::_('index.php?option=com_expautospro&view=export'); ?>" method="post" name="adminForm" id="adminForm">
    <fieldset>
        <select name="exporttable" class="inputbox" onchange="this.form.submit()">
            <option value=""><?php echo JText::_('COM_EXPAUTOSPRO_CSV_SELECTDATABASE_TEXT'); ?></option>
            <?php echo $this->buildpositions; ?>
        </select>
        <select name="filter_catid" class="">
            <option value=""><?php echo JText::_('JALL'); ?></option>
            <?php echo $this->lists; ?>
        </select>
    </fieldset>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="fname" value="<?php echo $this->link; ?>" />
    <input type="hidden" name="boxchecked" value="0" />
    <?php echo JHtml::_('form.token'); ?>
</form>
