<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

defined('_JEXEC') or die;

class JHtmlIcon {

    public static function print_icon($id) {
        $print_popup = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';
        $attribs['title'] = JText::_('JGLOBAL_PRINT');
        $attribs['onclick'] = "window.open(this.href,'win2','" . $print_popup . "'); return false;";
        $attribs['rel'] = 'nofollow';
        $text = JHtml::_('image', 'system/printButton.png', JText::_('JGLOBAL_PRINT'), NULL, true);
        $print_href = 'index.php?option=com_expautospro&view=expdetail&id=' . (int) $id . '&tmpl=component&print=1&layout=default&page=print';
        return JHtml::_('link', JRoute::_($print_href), $text, $attribs);
    }

    static function print_screen(){
            $text = JHtml::_('image', 'system/printButton.png', JText::_('JGLOBAL_PRINT'), NULL, true);
            return '<a href="#" onclick="window.print();return false;">'.$text.'</a>';
    }

    public static function dealerprint_icon($userid) {
        $print_popup = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';
        $attribs['title'] = JText::_('JGLOBAL_PRINT');
        $attribs['onclick'] = "window.open(this.href,'win2','" . $print_popup . "'); return false;";
        $attribs['rel'] = 'nofollow';
        $text = JHtml::_('image', 'system/printButton.png', JText::_('JGLOBAL_PRINT'), NULL, true);
        $print_href = 'index.php?option=com_expautospro&view=expdealerdetail&userid=' . (int) $userid . '&tmpl=component&print=1&layout=default&page=print';
        return JHtml::_('link', JRoute::_($print_href), $text, $attribs);
    }

    static function dealerprint_screen(){
            $text = JHtml::_('image', 'system/printButton.png', JText::_('JGLOBAL_PRINT'), NULL, true);
            return '<a href="#" onclick="window.print();return false;">'.$text.'</a>';
    }

    public static function email_icon($id) {
        require_once(JPATH_SITE . DS . 'components' . DS . 'com_mailto' . DS . 'helpers' . DS . 'mailto.php');
        $uri = JURI::getInstance();
        $base = $uri->toString(array('scheme', 'host', 'port'));
        $template = JFactory::getApplication()->getTemplate();
        $email_popup = 'width=400,height=350,menubar=yes,resizable=yes';
        $attribs['title'] = JText::_('JGLOBAL_EMAIL');
        $attribs['onclick'] = "window.open(this.href,'win2','" . $email_popup . "'); return false;";
        $text = JHtml::_('image', 'system/emailButton.png', JText::_('JGLOBAL_EMAIL'), NULL, true);
        $url = $base . JRoute::_('index.php?option=com_expautospro&view=expdetail&id=' . (int) $id);
        $link_href = 'index.php?option=com_mailto&tmpl=component&template=' . $template . '&link=' . MailToHelper::addLink($url);
        return JHtml::_('link', JRoute::_($link_href), $text, $attribs);
    }

    public static function html_icon($id) {
        $html_popup = 'width=600,height=550,menubar=yes,resizable=yes';
        $attribs['title'] = JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_ICON_HTML_TEXT');
        $attribs['rel'] = 'nofollow';
        $attribs['onclick'] = "window.open(this.href,'win2','" . $html_popup . "'); return false;";
        $text = JHtml::_('image', 'components/com_expautospro/assets/images/html.png', JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_ICON_HTML_TEXT'));
        $html_href = 'index.php?option=com_expautospro&view=expdetail&id=' .(int)$id. '&format=show';
        return JHtml::_('link', JRoute::_($html_href), $text, $attribs);
    }

    public static function rss_icon($id) {
        
        $document           = JFactory::getDocument();
        $link = '&format=feed';
        $attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
        $document->addHeadLink(JRoute::_($link . '&type=rss'), 'alternate', 'rel', $attribs);
        $attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
        $document->addHeadLink(JRoute::_($link . '&type=atom'), 'alternate', 'rel', $attribs);
        $attribs['target'] = '_blank';
        $text = JHtml::_('image', 'system/livemarks.png', JText::_('COM_EXPAUTOSPRO_CP_DETAILPAGE_ICON_RSS_TEXT'), NULL, true);
        $rss_href = 'index.php?option=com_expautospro&view=expdetail&id=' .(int)$id. '&format=feed';
        return JHtml::_('link', JRoute::_($rss_href), $text, $attribs);
        
    }

}