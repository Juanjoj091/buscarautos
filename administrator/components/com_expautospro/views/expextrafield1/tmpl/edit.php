<?php
/****************************************************************************************\
**   @name			EXP Autos  1.4														**
**	 @package		Joomla 1.6                                                          **
**   @author		EXP TEAM::Alexey Kurguz (Grusha)                                 	**
**   @copyright		Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)         **
**   @link			http://www.feellove.eu                                     			**
**   @license		Commercial License                                             		**
\****************************************************************************************/

// No direct access.
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
            /* Uncomment this code if Joomla don`t fixed bug with validation select
		var form = document.getElementById('expautosprocat-form');
		//alert(form.jform_catid.value);
		if (form.jform_catid.value == "0" && task != 'expbodytype.cancel'){
			form.jform_catid.style.backgroundColor = '#FFEAEA';
      		form.jform_catid.focus();
      		return false;
		}
             */
		if (task == 'expextrafield1.cancel' || document.formvalidator.isValid(document.id('expautosprocat-form'))) {
			Joomla.submitform(task, document.getElementById('expautosprocat-form'));
		}
	}

</script>
<form action="<?php echo JRoute::_('index.php?option=com_expautospro&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="expautosprocat-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id) ? JText::_('COM_EXPAUTOSPRO_EXTRAFIELD1_NEW_TITLE') : JText::sprintf('COM_EXPAUTOSPRO_EXTRAFIELD1_EDIT_TITLE', $this->item->id); ?></legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('catid'); ?>
				<?php echo $this->form->getInput('catid'); ?></li>
				<li><?php echo $this->form->getLabel('name'); ?>
				<?php echo $this->form->getInput('name'); ?></li>
				<li><?php echo $this->form->getLabel('image'); ?>
				<?php echo $this->form->getInput('image'); ?></li>
				<li><?php echo $this->form->getLabel('state'); ?>
				<?php echo $this->form->getInput('state'); ?></li>
				<li><?php echo $this->form->getLabel('ordering'); ?>
				<?php echo $this->form->getInput('ordering'); ?></li>
				<li><?php echo $this->form->getLabel('language'); ?>
				<?php echo $this->form->getInput('language'); ?></li>
				<li><?php echo $this->form->getLabel('id'); ?>
				<?php echo $this->form->getInput('id'); ?></li>
			</ul>
		</fieldset>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
<div class="clr"></div>
</form>
