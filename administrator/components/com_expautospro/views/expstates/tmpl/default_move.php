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
defined('_JEXEC') or die;

$published	= $this->state->get('filter.published');
?>
<fieldset class="batch">
	<legend><?php echo JText::_('COM_EXPAUTOSPRO_STATES_COPY_LABEL');?></legend>

	<?php if ($published >= 0) : ?>
		<label id="batch-choose-action-lbl" for="batch-choose-action">
			<?php echo JText::_('COM_EXPAUTOSPRO_STATES_COPY_SELECT'); ?>
		</label>
		<fieldset id="batch-choose-action" class="combo">
			<select name="build_cat" class="inputbox">
				<option value=""><?php echo JText::_('JSELECT');?></option>
				<?php echo JHtml::_('select.options', JFormFieldCategor::getOptions('country'), 'value', 'text');?>
			</select>
			<?php echo $this->buildradiolist; ?>
		</fieldset>
	<?php endif; ?>
	<button type="submit" onclick="submitbutton('expstate.make_build_contr');">
		<?php echo JText::_('JGLOBAL_BATCH_PROCESS'); ?>
	</button>
</fieldset>