<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/


defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');
//require_once JPATH_COMPONENT . '/helpers/expimages.php';

class JFormFieldExpimages extends JFormField {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Expimages';

    /**
     * Method to get the field options.
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    public function getInput($data=0) {
        $user	= JFactory::getUser();        
        $expid = (int)$this->form->getValue('id');
        if($expid){
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('id, name, ordering, description');
            $query->from('#__expautos_images');
            $query->where('catid = ' . (int) $expid);
            $query->order('ordering');
            // Get the options.
            $db->setQuery($query);
            $listimages = $db->loadObjectList();
            // Check for a database error.
            if ($db->getErrorNum()) {
                JError::raiseWarning(500, $db->getErrorMsg());
            }
            $countimages=count($listimages);
        }else{
            $countimages = 0;  
        }

        
        $usergroups = implode(',', JAccess::getGroupsByUser($user->id));
        $usergroupid = ExpAutosProImages::getExpgroupid($usergroups);
        $groupparams = ExpAutosProImages::getExpParams('userlevel',$usergroupid);
        $groupimgcount = $groupparams->get('g_imgcount') ? $groupparams->get('g_imgcount') : '5';
        ?>
        <table class="admintable">
            <?php for ($i = 0, $n = $countimages; $i < $n; $i++) { ?>
                <tr>
                    <td class="key">
                        <label for="images<?php echo $i + 1; ?>">
                            <?php echo JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_IMAGE_TEXT'); ?> <?php echo $i + 1; ?>
                        </label>
                    </td>
                    <td>
                        <?php
                        $img_urlpatch = ExpAutosProImages::ImgUrlPatchThumbs() . $listimages[$i]->name;
                        $img_abspatch = ExpAutosProImages::ImgAbsPathThumbs() . $listimages[$i]->name;
                        if (file_exists($img_abspatch)) {
                            echo '<img src="' . $img_urlpatch . '"/>';
                        }
                        echo "<br /><input type='checkbox' name='del_image[" . $listimages[$i]->id . "]' value='" . $listimages[$i]->name . "'>" . JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_DELETE_TEXT');
                        ?>
                    </td>
                    <td class="key">
                        <label for="imgdescription" style="text-align:right">
                            <?php echo JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_DESCRIPTION_TEXT'); ?>
                        </label>
                    </td>
                    <td>
                        <textarea name="imgdescription[<?php echo $listimages[$i]->id; ?>]" id="jform_description<?php echo $i; ?>" rows="3" cols="10"  ><?php echo $listimages[$i]->description ?></textarea>
                    </td>
                    <td class="key">
                        <label for="imgordering" style="text-align:right">
                            <?php echo JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_ORDERING_TEXT'); ?>
                        </label>
                    </td>
                    <td>
                        <input class="inputbox" type="text" name="imgordering[<?php echo $listimages[$i]->id; ?>]" id="jform_ordering<?php echo $i; ?>" size="10" maxlength="255" value="<?php echo $listimages[$i]->ordering ?>" />
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
                            <?php echo JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_IMAGE_TEXT'); ?> <?php echo $i + 1; ?>
                        </label>
                    </td>
                    <td>
                        <input type="file" name="adimages[<?php echo $i + 1; ?>]"/>
                    </td>
                    <td class="key">
                        <label for="imgdescription" style="text-align:right">
                            <?php echo JText::_('COM_EXPAUTOSPRO_ADMANAGERS_TAB_DESCRIPTION_TEXT'); ?>
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
        <?php
    }

}
