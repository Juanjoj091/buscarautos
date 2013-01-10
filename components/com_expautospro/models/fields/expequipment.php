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
require_once JPATH_SITE . '/components/com_expautospro/helpers/expparams.php';

class JFormFieldExpequipment extends JFormField {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Expequipment';

    /**
     * Method to get the field options.
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    public function getInput($data=0) {
        // Initialize variables.
        //$options = array();
        
        $expparams = ExpAutosProExpparams::getExpParams('config', 1);
        $equipcolumn = $expparams->get('c_admanager_add_equipcolumn');
        $patchs = explode(',', $this->form->getValue('equipment'));
        $exppostcat = JRequest::getInt('expcat', 0);
        if ($exppostcat) {
            $expcat = JRequest::getInt('expcat', 0);
        } else {
            $expcat = $this->form->getValue('catid');
        }
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id, name');
        $query->from('#__expautos_catequipment');
        $query->where('catid = ' . (int) $expcat);
        $query->where('state = 1');
        $query->order('ordering');
        // Get the options.
        $db->setQuery($query);

        $catequipment = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }
        //print_r($this->form->getValue('equipment'));
        //die();
        ?>
        <table class="expequiptable">
            <?php
            foreach ($catequipment as $eqcat) {
                $query2 = $db->getQuery(true);
                $query2->select('id, name');
                $query2->from('#__expautos_equipment');
                $query2->where('catid = ' . (int) $eqcat->id);
                $query2->where('state = 1');
                $query2->order('ordering');
                // Get the options.
                $db->setQuery($query2);

                $equipment = $db->loadObjectList();
                $c = 0;
                $kr = $equipcolumn;
                ?>
                <tr>
                    <td colspan="<?php echo $kr; ?>" class="expadd_equip">
                        <h3><?php echo $eqcat->name; ?></h3>
                    </td>
                </tr>
                <?php
                for ($i = 0, $n = count($equipment); $i < $n; $i++) {

                    if (($c % $kr) == 0) {

                        echo '</tr>';

                        echo '<tr>';
                    }
                    ?>

                    <td>

                   <input name="jform[equipment][<?php echo $equipment[$i]->id; ?>]" type="checkbox" id="jform_equipment[<?php echo $equipment[$i]->id; ?>]" size="60" <?php foreach($patchs as $patch){if($patch == $equipment[$i]->id){echo' checked="checked" ';}}?>  value="<?php echo $equipment[$i]->id; ?>" />

            </td>

			<td class="key" style="width:300px; text-align:left;">

				<label for="equipment<?php echo $equipment[$i]->id; ?>">

					<?php echo $equipment[$i]->name; ?>

				</label>

			</td>

                    <?php
                    $c++;
                }
            }
            ?>

        </tr>

        </table>
        <?php
    }

}
