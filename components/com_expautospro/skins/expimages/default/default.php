<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

// No direct access.
defined('_JEXEC') or die;

$params_file = JPATH_COMPONENT . '/skins/expimages/default/parameters/params.php';
if(file_exists($params_file))
require_once $params_file;
ExpAutosProHelper::expskin_lang('expimages','default');

$expitem = $this->expitemid;
$cache = JFactory::getCache('com_expautospro', '');
$cache->clean('com_expautospro');
$user = JFactory::getUser();
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'components/com_expautospro/assets/css/expautospro.css');
$document->addStyleSheet(JURI::root() . 'components/com_expautospro/skins/expimages/default/css/default.css');
$document->addScript(JURI::root() . 'components/com_expautospro/assets/js/ajaxrequest.js');
$topmoduleposition = $this->expparams->get('c_admanager_addimages_tmpname');
$bmoduleposition = $this->expparams->get('c_admanager_addimages_bmpname');
$addid = (int) JRequest::getInt('id', null, '', 'array');
$checkaduser = ExpAutosProFields::expaddcheck((int) $addid, (int) $user->id);
$listimages = $this->expimages;
$countimages = count($this->expimages);
$groupimg = $this->expgroupfields->get('g_imgcount');
$groupimgcount = $groupimg ? $groupimg : '5';

$expisnew = (int) JRequest::getInt('expisnew', '');
//print_r($this->expgroupfields->get('g_imgcount'));
//print_r($this->form->getValue('catid'));
//print_r($this->expgroupfields->get('g_adscount'));
//print_r($ads_count);
?>
<div class="expautospro_topmodule">
    <div class="expautospro_topmodule_pos">
        <?php echo ExpAutosProHelper::load_module_position($topmoduleposition, $this->expparams->get('c_admanager_addimages_tmpstyle')); ?>
    </div>
    <div class="expautospro_clear"></div>
</div>

<!-- Skins Module Position !-->
<?php if($this->expparams->get('c_admanager_addimages_showskin')):?>
<div id="expskins_module">
    <?php
    $expmodparam = array('folder' => $this->expskins);
    echo ExpAutosProHelper::load_module_position('expskins', $style = 'none', $expmodparam);
    ?>
</div>
<div class="expautospro_clear"></div>
<?php endif; ?>
<div id="expautospro">
    <h2><?php echo JText::_('COM_EXPAUTOSPRO_CP_ADDIMAGES_TEXT') ?></h2>
    <?php if ($checkaduser): ?>
    <div class="expautos_imgconfig">
        <p>
            <?php echo JText::_('COM_EXPAUTOSPRO_CP_IMAGES_MINWIDTH_TEXT').$this->expparams->get('c_images_minsize_width').JText::_('COM_EXPAUTOSPRO_CP_PX_TEXT');?>
        </p>
        <p>
            <?php echo JText::_('COM_EXPAUTOSPRO_CP_IMAGES_MINHEIGHT_TEXT').$this->expparams->get('c_images_minsize_height').JText::_('COM_EXPAUTOSPRO_CP_PX_TEXT');?>
        </p>
        <p>
            <?php echo JText::_('COM_EXPAUTOSPRO_CP_IMAGES_MAXFILESIZE_TEXT').ExpAutosProFields::exp_convertsize($this->expparams->get('c_images_maxfilesize'));?>
        </p>
        <p>
            <?php echo JText::_('COM_EXPAUTOSPRO_CP_IMAGES_OTHERS_TEXT');?>
        </p>
    </div>
        <div class="expprofile-edit">
            <form id="expmember-profile" action="<?php echo JRoute::_('index.php?option=com_expautospro&view=expimages&task=expimages'); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="expautosprocat-form" class="form-validate">
                <fieldset>
                    <legend><?php echo JText::_('COM_EXPAUTOSPRO_CP_IMAGES_TEXT'); ?></legend>
                    <table class="expimagestable">
                        <?php for ($i = 0, $n = $countimages; $i < $n; $i++) { ?>
                            <tr>
                                <td class="key">
                                    <label for="images<?php echo $i + 1; ?>">
                                        <?php echo JText::_('COM_EXPAUTOSPRO_CP_IMAGE_TEXT'); ?> <?php echo $i + 1; ?>
                                    </label>
                                </td>
                                <td>
                                    <?php
                                    $img_urlpatch = ExpAutosProExpparams::ImgUrlPatchThumbs() . $listimages[$i]->name;
                                    $img_abspatch = ExpAutosProExpparams::ImgAbsPathThumbs() . $listimages[$i]->name;
                                    if (file_exists($img_abspatch)) {
                                        echo '<img src="' . $img_urlpatch . '"/>';
                                    }
                                    echo "<br /><input type='checkbox' name='del_image[" . $listimages[$i]->id . "]' value='" . $listimages[$i]->name . "'>" . JText::_('COM_EXPAUTOSPRO_CP_IMAGE_DELETE_TEXT');
                                    ?>
                                </td>
                                <td class="key">
                                    <label for="imgdescription" style="text-align:right">
                                        <?php echo JText::_('COM_EXPAUTOSPRO_CP_IMAGE_DESCRIPTION_TEXT'); ?>
                                    </label>
                                </td>
                                <td>
                                    <textarea name="imgdescription[<?php echo $listimages[$i]->id; ?>]" id="jform_description<?php echo $i; ?>" rows="3" cols="10"  ><?php echo $listimages[$i]->description ?></textarea>
                                </td>
                                <td class="key">
                                    <label for="imgordering" style="text-align:right">
                                        <?php echo JText::_('COM_EXPAUTOSPRO_CP_IMAGE_ORDERING_TEXT'); ?>
                                    </label>
                                </td>
                                <td>
                                    <input class="inputbox" type="text" name="imgordering[<?php echo $listimages[$i]->id; ?>]" id="jform_ordering<?php echo $i; ?>" size="2" maxlength="2" value="<?php echo $listimages[$i]->ordering ?>" />
                                </td>
                            </tr>
                            <?php
                        }
                        $numimgupload = $groupimgcount;
                        for (; $i < $numimgupload; $i++) {
                            ?>
                            <tr>
                                <td class="key">
                                    <label for="images<?php echo $i + 1; ?>">
                                        <?php echo JText::_('COM_EXPAUTOSPRO_CP_IMAGE_TEXT'); ?> <?php echo $i + 1; ?>
                                    </label>
                                </td>
                                <td>
                                    <input type="file" name="adimages[<?php echo $i + 1; ?>]"/>
                                </td>
                                <td class="key">
                                    <label for="imgdescription" style="text-align:right">
                                        <?php echo JText::_('COM_EXPAUTOSPRO_CP_IMAGE_DESCRIPTION_TEXT'); ?>
                                    </label>
                                </td>
                                <td>
                                    <textarea name="imgdescription[<?php echo $i + 1; ?>]" id="jform_description<?php echo $i + 1; ?>" rows="3" cols="10"  ></textarea>
                                </td>
                                <td>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </fieldset>
                <div>
                    <button type="submit" class="validate"><span><?php echo JText::_('COM_EXPAUTOSPRO_CP_IMAGE_SAVEANDFINISHED_TEXT'); ?></span></button>

                    <input type="hidden" name="option" value="com_expautospro" />
                    <input type="hidden" name="catid" value="<?php echo $addid; ?>" />
                    <input type="hidden" name="task" value="expimages.save" />
                    <?php if($expisnew): ?>
                        <input type="hidden" name="expisnew" value="1" />
                    <?php endif; ?>
                    <input type="hidden" name="Itemid" value="<?php echo $expitem;?>" />
                    <?php echo JHtml::_('form.token'); ?>
                </div>
            </form>
        </div>
    <?php else: ?>
        <?php echo JText::_('COM_EXPAUTOSPRO_CP_EXPADD_NOTUSERAD_ERROR_TEXT'); ?>
    <?php endif; ?>

</div>
<div class="expautospro_clear"></div>
<?php if ($bmoduleposition): ?>
    <div class="expautospro_botmodule">
        <?php echo ExpAutosProHelper::load_module_position($bmoduleposition, $this->expparams->get('c_admanager_addimages_bmpstyle')); ?>
    </div>
<?php endif; ?>
