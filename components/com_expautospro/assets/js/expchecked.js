
/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6/1.7/2.5                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2012  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/


    function expchecktoggle(el,elch,elmd) {
      var elvar = document.getElementById(el);
      var elcheck = document.getElementById(elch);
      var elmodel = document.getElementById(elmd);
      //var expelvalcl = document.getElementById(elvalcl);
      //var expelmodelcl = document.getElementById(elmodelcl);
      if (elcheck.checked)
        { 
            elvar.style.display = 'block';
            //addClass(expelvalcl, 'required');
            elmodel.disabled = true;
            //removeClass(expelmodelcl, 'required');
        }else{
            elvar.style.display = 'none';
            //removeClass(expelvalcl, 'required');
            elmodel.disabled = false;
            //addClass(expelmodelcl, 'required');
        }
    }
    /*
    function hasClass(el, name) {
       return new RegExp('(\\s|^)'+name+'(\\s|$)').test(el.className);
    }
    
    function removeClass(el, name){
       if (hasClass(el, name)) {
          el.className=el.className.replace(new RegExp('(\\s|^)'+name+'(\\s|$)'),' ').replace(/^\s+|\s+$/g, '');
       }
    }
    
    function addClass(el, name){
       if (!hasClass(el, name)) { el.className += (el.className ? ' ' : '') +name; }
    }
    */


