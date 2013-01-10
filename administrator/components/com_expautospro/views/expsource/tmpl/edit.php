<?php
/* * **************************************************************************************\
 * *   @name		EXP Autos  2.0                                                  **
 * *   @package          Joomla 1.6                                                      **
 * *   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 * *   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 * *   @link             http://www.feellove.eu                                          **
 * *   @license		Commercial License                                              **
  \*************************************************************************************** */

// No direct access.
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
?>
<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        if (task == 'expsource.cancel' || document.formvalidator.isValid(document.id('expsource-form'))) {
<?php echo $this->form->getField('expsource')->save(); ?>
                                    Joomla.submitform(task, document.getElementById('expsource-form'));
                                } else {
                                    alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
                                }
                            }

</script>

<form action="<?php echo JRoute::_('index.php?option=com_expautospro&view=expsource&layout=edit&tmpl=component'); ?>"  method="post" name="adminForm" id="expsource-form" class="form-validate">

    <fieldset class="adminform">

        <div class="fltrt">
            <button type="button" onclick="Joomla.submitform('expsource.apply', this.form);">
                <?php echo JText::_('JAPPLY'); ?></button>
            <button type="button" onclick="<?php echo JRequest::getBool('refresh', 0) ? 'window.parent.location.href=window.parent.location.href;' : ''; ?>  window.parent.SqueezeBox.close();">
                <?php echo JText::_('JCANCEL'); ?></button>
        </div>
        <legend><?php echo JText::sprintf('COM_EXPAUTOSPRO_SOURCE_FILE_TEXT', $this->source->filename); ?></legend>

        <?php echo $this->form->getLabel('expsource'); ?>
        <div class="clr"></div>
        <div class="editor-border">
            <?php echo $this->form->getInput('expsource'); ?>
        </div>
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
    </fieldset>

    <?php echo $this->form->getInput('filename'); ?>
    <?php echo $this->form->getInput('fileskin'); ?>
</form>
