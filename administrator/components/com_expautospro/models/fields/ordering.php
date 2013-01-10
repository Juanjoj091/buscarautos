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

class JFormFieldOrdering extends JFormField {

    protected $type = 'Ordering';

    protected function getInput() {
        $databasename = (string) $this->element['database'];
        $wherefield = (string) $this->element['wherefield'];
        $catidnum = (int) $this->element[$wherefield];
        $html = array();
        $attr = '';

        // Initialize some field attributes.
        $attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
        $attr .= ( (string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
        $attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';

        // Initialize JavaScript field attributes.
        $attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

        $expcategoryId = (int) $this->form->getValue('id');
        if ($catidnum) {
            $categoryId = $catidnum;
        } else {
            $categoryId = (int) $this->form->getValue($wherefield);
        }
        switch ($databasename) {
            case 'cities':
                $selectname = 'city_name';
                break;
            case 'userlevel':
                $selectname = 'userlevel';
                break;
            case 'expuser':
                $selectname = 'lastname';
                break;
            case 'admanager':
                $selectname = 'id';
                break;
            default:
                $selectname = 'name';
        }


        $query = 'SELECT ordering AS value, ' . $selectname . ' AS text' .
                ' FROM #__expautos_' . $databasename
                . ' WHERE ' . $wherefield . ' = ' . (int) $categoryId
                . ' ORDER BY ordering';

        if ((string) $this->element['readonly'] == 'true') {
            $html[] = JHtml::_('list.ordering', '', $query, trim($attr), $this->value, $expcategoryId ? 0 : 1);
            $html[] = '<input type="hidden" name="' . $this->name . '" value="' . $this->value . '"/>';
        } else {
            $html[] = JHtml::_('list.ordering', $this->name, $query, trim($attr), $this->value, $expcategoryId ? 0 : 1);
        }
        return implode($html);
    }

}