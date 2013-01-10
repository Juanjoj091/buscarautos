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
require_once JPATH_SITE . '/components/com_expautospro/helpers/expfields.php';
require_once JPATH_SITE . '/components/com_expautospro/helpers/expparams.php';

class JFormFieldSelectstate extends JFormField {

    /**
     * The form field type.
     *
     * @var		string
     * @since	1.6
     */
    protected $type = 'Selectstate';

    /**
     * Method to get the field options.
     *
     * @return	array	The field option objects.
     * @since	1.6
     */
    public function getInput($data=0) {
        $expview = JRequest::getVar('view');
        $user = JFactory::getUser();
        $UserId = $user->id;
        $listgroupparams = '';
        if ($UserId > 0) {
            $listgroupparams = ExpAutosProExpparams::getExpDealersParams($UserId);
        }
        if($this->form->getValue('catid')){
            $expcat = $this->form->getValue('catid');
        }else{
            $expcat = JRequest::getInt('expcat', 0);
        }
        $expcatParams = ExpAutosProExpparams::getCatParams($expcat);
        $expparams = ExpAutosProExpparams::getExpParams('config', 1);
        $expgoogle ='';
        if(($expview == 'expuser' && $expparams->get('c_admanager_useradd_showgooglemaps')) || ($expview == 'expadd' && $expcatParams->get('usegooglemaps')))
            $expgoogle = 'findAddress(this);return false;';
        $country_id = $this->form->getValue('country');
        if(($expview == 'expadd' && $expcatParams->get('usecity')) || ($expview == 'expuser' && $listgroupparams->get('c_ucity'))){
            $document = JFactory::getDocument();
            $script = '';
            $script .= "
                function change_chained_state(val){
                    var url = 'index.php?option=com_expautospro&view=expuser&format=ajax&state_id='+val;
                    ajaxgetchained(url,'jformcity')
                }
                    ";

            $document->addScriptDeclaration($script);
        }
        if(!$country_id){
            $country_id=0;
        }
        $state_id = $this->form->getValue('expstate');
        $el_class = $this->element['class'];
        $attr = $this->element['onchange'] ? (string) $this->element['onchange'] : '';
        $onchange = '';
        if(($expview == 'expadd' && $expcatParams->get('usecity')) || ($expview == 'expuser' && $listgroupparams->get('c_ucity')))
            $onchange = ' change_chained_state(this.value);';
        $options=ExpAutosProFields::getExpvariables($country_id,'state','name','catid',$state_id,'name',$onchange.$expgoogle.$attr,$el_class,'',$this->name);

        return $options;
    }

}
