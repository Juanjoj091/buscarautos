<?php

/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/

//no direct access
defined('_JEXEC') or die;

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'modules/mod_expautospro_mortgage/css/mod_expautospro_mortgage_bootstrap.css');
$modules = JModuleHelper::getModule('mod_expautospro_mortgage');

if($items->bprice){
	$price = $items->bprice;
}else{
	$price = $items->price;
}
$definterest		= $params->get('definterest');
?>
<div class="<?php echo $moduleclass_sfx; ?>">
<form name="expcalculator"> 
<p><?php echo JText::_('MOD_EXPAUTOSPRO_MORGAGE_SALEPRICE_TEXT'); ?> (<?php echo $currency; ?>)<br/>
<input type="text" name="mcPrice" id="mcPrice" class="mortgageField span2" maxlength="255" value="<?php echo $price; ?>" />
</p>
<p><?php echo JText::_('MOD_EXPAUTOSPRO_MORGAGE_FIRSTPAYMENT_TEXT'); ?><br/>
<?php echo $fpayment;?>
</p>
<p><?php echo JText::_('MOD_EXPAUTOSPRO_MORGAGE_INTERESTRATE_TEXT'); ?><br/>
<input type="text" name="mcRate" id="mcRate" class="mortgageField span2" maxlength="255"  value="<?php echo $definterest; ?>"/>
</p> 
<p><?php echo JText::_('MOD_EXPAUTOSPRO_MORGAGE_PERIOD_TEXT'); ?><br/>
<?php echo $period;?>
</p> 
<input name="btncalc" class="button btn btn-primary" type="button" id="mortgageCalc" value="<?php echo JText::_('MOD_EXPAUTOSPRO_MORGAGE_CALCULATE_TEXT'); ?>" onclick="ExpformSubmit()"/>
<br /><br />
<p><?php echo JText::_('MOD_EXPAUTOSPRO_MORGAGE_MONTHLYPAYMENT_TEXT'); ?> (<?php echo $currency; ?>)<br/>
  <input type="text" name="mcPayment" id="mcPayment" class="mortgageAnswer span2" />
</p>
</form>
</div>
<script type="text/javascript">
ExpformSubmit();
function ExpformSubmit(){
  var L,P,n,c,dp; 

		L = parseInt(document.getElementById("mcPrice").value); 

		n = parseInt(document.getElementById("mcTerm").value); 

		c = parseFloat(document.getElementById("mcRate").value)/1200; 

		dp = 1 - parseFloat(document.getElementById("mcDown").value)/100; 

		L = L * dp; 

		P = (L*(c*Math.pow(1+c,n)))/(Math.pow(1+c,n)-1); 

		if(!isNaN(P)) 

		{ 

		document.getElementById("mcPayment").value=P.toFixed(2); 

		} 

		else 

		{ 

		document.getElementById("mcPayment").value='<?php echo JText::_('MOD_EXPAUTOSPRO_MORGAGE_ERROR_TEXT'); ?>'; 

		} 

		return false;
}
</script>