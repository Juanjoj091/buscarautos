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
<form action="<?php echo JRoute::_('index.php?option=com_expautospro&view=import'); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">
    <?php echo JText::_('COM_EXPAUTOSPRO_CSVIMPORT_SELECT_TEXT'); ?> 
    <input type="file" name="uploadfile"/>
    <select name="importtable" class="required" aria-invalid="true">
        <option value=""><?php echo JText::_('COM_EXPAUTOSPRO_CSV_SELECTDATABASE_TEXT'); ?></option>
        <?php echo $this->buildpositions; ?>
    </select>
    <select name="expautosadmincsv" id="expautosadmincsv" class="inputbox" size="1">
        <option value="0" ><?php echo JText::_('COM_EXPAUTOSPRO_CSVIMPORT_INSERT_TEXT'); ?></option>
        <option value="1" ><?php echo JText::_('COM_EXPAUTOSPRO_CSVIMPORT_UPDATE_TEXT'); ?></option>
    </select>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="fname" value="<?php echo $this->link; ?>" />
    <input type="hidden" name="boxchecked" value="0" />
    <?php echo JHtml::_('form.token'); ?>
</form>
