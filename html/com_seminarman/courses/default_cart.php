<?php
/**
*
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.formvalidation');
$mainframe = JFactory::getApplication();
$params = $mainframe->getParams('com_seminarman');

// custom fields
$js = '';
foreach ($this->fields as $this->fieldGroup){
	foreach ($this->fieldGroup as $f) {
		$f = JArrayHelper::toObject ($f);
		$f->value = $this->escape($f->value);

		if ($f->published == 1 && $f->required == 1 && $f->type == "checkboxtos") {
			$js .= '
		fields = form.getElementById("field' . $f->id . '")
		if(fields.getAttribute("aria-invalid") == "true" || !fields.checked) {
			if(fields.className.indexOf("invalid") < 0) {
				fields.className += " invalid";
			}
			return alert("' . JText::sprintf('COM_SEMINARMAN_ACCEPT_TOS', $f->name) . '");
		}';
		}
	}
}
?>

<script language="javascript" type="text/javascript">
function submitbuttonSeminarman(task)
{
	var form = document.adminForm;
	var fields;

	if (task == "cancel") {
		Joomla.submitform( task );
	} else {
		<?php echo $js;?>
		if (document.formvalidator.isValid(form)) {
			if(document.adminForm.submitSeminarman) {
				document.adminForm.submitSeminarman.disabled = true;
			}
			Joomla.submitform( task );
		} else {	
			return alert("<?php echo JText::_('COM_SEMINARMAN_VALUES_NOT_ACCEPTABLE'); ?>");
		}
	}
};
</script>
<?php 
// list of courses
$db = JFactory::getDBO();
$db->setQuery('SELECT * FROM #__seminarman_courses WHERE id ='. $_POST['course_id']);
if (!$db->query()) {
	JError::raiseError(500, $db->stderr(true));
	return;
}

$courseRows = $db->loadObject();

// egtl. $courseRows->start_date, $courseRows->start_time werden nicht mehr benutzt, stattdessen $this->course->start mit Berücksichtigung von Zeitzone
if ($courseRows->start_date != '0000-00-00'){
	$courseRows->start_date = JFactory::getDate($courseRows->start_date)->format("j. M Y");
} else{
	$courseRows->start_date = JText::_('COM_SEMINARMAN_NOT_SPECIFIED');
}
if ($courseRows->finish_date != '0000-00-00'){
	$courseRows->finish_date = JFactory::getDate($courseRows->finish_date)->format("j. M Y");
}else{
	$courseRows->finish_date = JText::_('COM_SEMINARMAN_NOT_SPECIFIED');
}

if (!empty($courseRows->start_time)) {
	$courseRows->start_time = date('H:i', strtotime($courseRows->start_time));
} else {
	$courseRows->start_time = '';
}

if (!empty($courseRows->finish_time)) {
	$courseRows->finish_time = date('H:i', strtotime($courseRows->finish_time));
} else {
	$courseRows->finish_time = '';
}

$price_orig = $courseRows->price;
$price_booking = $price_orig;
if ($_POST['booking_price'][0] == 1) { // 2. price group
	$price_booking = $courseRows->price2;
} elseif ($_POST['booking_price'][0] == 2) { // 3. price group
	$price_booking = $courseRows->price3;
} elseif ($_POST['booking_price'][0] == 3) { // 4. price group
	$price_booking = $courseRows->price4;
} elseif ($_POST['booking_price'][0] == 4) { // 5. price group
	$price_booking = $courseRows->price5;
}
if (empty($_POST['attendees'])) {
	$_POST['attendees'] = 1;
}
$price_total_orig = $price_orig * $_POST['attendees'];
$price_total_booking = $price_booking * $_POST['attendees'];
$price_total_discount = $price_total_orig - $price_total_booking;
$tax_rate = $courseRows->vat / 100.0;
$tax_booking = $price_total_booking * $tax_rate;
$price_total_booking_with_tax = $price_total_booking * (1 + $tax_rate);

?>
<div id="seminarman" class="seminarman">
<h2><?php echo JText::_('COM_SEMINARMAN_CART_CONFIRM'); ?></h2>
<br />
    <table class="seminarman_cart">
    <tr><td colspan="2"><h3 class="underline"><?php echo JText::_('COM_SEMINARMAN_CART_REG_DATA');?></h3></td></tr>
    <tr><td class="paramlist_key vtop">&nbsp;</td>
        <td class="paramlist_value vtop"><?php echo $_POST['salutation'] . ' ' . $_POST['first_name'] . ' ' . $_POST['last_name']; ?></td></tr>
    <tr><td class="paramlist_key vtop"><label for="jformemail"><?php echo JText::_('COM_SEMINARMAN_EMAIL'); ?>:</label></td>
        <td class="paramlist_value vtop"><?php echo $_POST['email']; ?></td></tr>
    <tr><td colspan="2">&nbsp;<br>&nbsp;</td></tr>      
    <?php
    // custom fields
    foreach ($this->fields as $name => $this->fieldGroup){
    if ($name != 'ungrouped'){?>
    <tr><td colspan="2"><h3 class="underline"><?php echo $name;?></h3></td></tr>
    <?php
    }

    ?>

            <?php

            foreach ($this->fieldGroup as $f){
            $f = JArrayHelper::toObject ($f);
            $f->value = $this->escape($f->value);

            ?>
            <tr>
                <td class="paramlist_key vtop" id="lblfield<?php echo $f->id;?>"><label for="lblfield<?php echo $f->id;?>"><?php if ($f->type != "checkboxtos") echo JText::_($f->name) . ':'; ?></label></td>
                <td class="paramlist_value vtop"><?php
                    $var = 'field' . $f->id;
                    if ($f->type != "checkboxtos") {
                    	if (($f->type == "checkbox") || ($f->type == "list") || ($f->type == "time") || ($f->type == "url")) {
                    		if (isset($_POST[$var])) {
                    		    foreach ($_POST[$var] as $i => $f_item) {
                    		        echo $f_item;
                    		        if ($i < 1) {
	                    		        switch ($f->type) {
											case "time":
												echo ":";
												break;
											case "url":
												break;
											default:
												echo "<br />";
										}	
									}
                    		    }
                    		}
                    	// } elseif ($f->type == "date") {
                    	//	$str_datum = "";
                    	//	foreach ($_POST[$var] as $f_item) {
                    	//		$str_datum = $str_datum . "." . $f_item;    
                    	//    }
                    	//    $str_datum = substr($str_datum, 1);
                    	//    echo $str_datum;
                    	} else {
                    	    if (isset($_POST[$var])) echo $_POST[$var]; 
                    	}
                    }
                  ?></td>
            </tr>
            <?php
            }

            ?>
    <tr><td colspan="2">&nbsp;<br>&nbsp;</td></tr>
    <?php
    }

    ?>
    </table>
	<br /><br />
	<table class="seminarman_cart_invoice">
	<thead>
	<?php switch ($this->params->get('payment_overview_layout')){ 
		case 1:?>
	<tr>
	<td class="seminarman_cart_course"><?php echo JText::_('COM_SEMINARMAN_COURSE'); ?></td>
	<td class="seminarman_cart_quantity"><?php echo JText::_('COM_SEMINARMAN_CART_QUANTITY'); ?></td>
	<td class="seminarman_cart_price_single"><?php echo JText::_('COM_SEMINARMAN_CART_PRICE_SINGLE') . ' ' . $params->get('currency'); ?></td>
	<td class="seminarman_cart_price_total"><?php echo JText::_('COM_SEMINARMAN_CART_PRICE_TOTAL') . ' ' . $params->get('currency'); ?></td>
	</tr>
	</thead>
	<tbody>
	<tr class="seminarman_cart_item">
	<td class="seminarman_cart_course" data-title="<?php echo JText::_('COM_SEMINARMAN_COURSE'); ?>:"><?php echo $courseRows->title . " (" . $courseRows->code . ")"; ?><br/>
	<?php
	  echo JText::_('COM_SEMINARMAN_START_DATE') . ': ';
	  echo $this->course->start;
	  echo ', ' . JText::_('COM_SEMINARMAN_FINISH_DATE') . ': '; 
	  echo $this->course->finish;
	?>
	</td>
	<td class="seminarman_cart_quantity" data-title="<?php echo JText::_('COM_SEMINARMAN_CART_QUANTITY'); ?>:"><?php echo $_POST['attendees']; ?></td>
	<td class="seminarman_cart_price_single" data-title="<?php echo JText::_('COM_SEMINARMAN_CART_PRICE_SINGLE'); ?>:" data-currency=" <?php echo $params->get('currency'); ?>"><?php echo JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($price_orig))), 2)); ?></td>
	<td class="seminarman_cart_price_total" data-title="<?php echo JText::_('COM_SEMINARMAN_CART_PRICE_TOTAL'); ?>:" data-currency=" <?php echo $params->get('currency'); ?>"><?php echo JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($price_total_orig))), 2)); ?></td>
	</tr>

	<?php if ($courseRows->vat <> 0) { ?>
	<tr>
	<td class="seminarman_cart_netto_total_title" colspan="3"><?php echo JText::_('COM_SEMINARMAN_CART_NETTO_TOTAL'); ?></td>
	<td class="seminarman_cart_netto_total" data-title="<?php echo JText::_('COM_SEMINARMAN_CART_NETTO_TOTAL'); ?>:" data-currency=" <?php echo $params->get('currency'); ?>"><?php echo JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($price_total_orig))), 2)); ?></td>
	</tr>
	<?php } ?>
	<?php if ($price_total_discount <> 0) { ?>
	<tr>
	<td class="seminarman_cart_discount_total_title" colspan="3"><?php echo JText::_('COM_SEMINARMAN_CART_DISCOUNT_TOTAL'); ?></td>
	<td class="seminarman_cart_discount_total" data-title="<?php echo JText::_('COM_SEMINARMAN_CART_DISCOUNT_TOTAL'); ?>:" data-currency=" <?php echo $params->get('currency'); ?>"><?php echo JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($price_total_discount))), 2)); ?></td>
	</tr>
	<?php } ?>
	<?php if ($courseRows->vat <> 0) { ?>
	<tr>
	<td class="seminarman_cart_withoutVat_total_title" colspan="3"><?php echo JText::sprintf('COM_SEMINARMAN_CART_WITHOUT_VAT', JText::sprintf('%.0f', round(doubleval(str_replace(",", ".", $this->escape($courseRows->vat))), 2)) . '%'); ?></td>
	<td class="seminarman_cart_withoutVat_total" data-title="<?php echo JText::sprintf('COM_SEMINARMAN_CART_WITHOUT_VAT', JText::sprintf('%.0f', round(doubleval(str_replace(",", ".", $this->escape($courseRows->vat))), 2)) . '%'); ?>:" data-currency=" <?php echo $params->get('currency'); ?>"><?php echo JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($tax_booking))), 2)); ?></td>
	</tr>
	<?php } ?>
	<tr>
	<td class="seminarman_cart_booking_total_title" colspan="3"><?php echo JText::_('COM_SEMINARMAN_CART_BOOKING_TOTAL'); ?></td>
	<td class="seminarman_cart_booking_total" data-title="<?php echo JText::_('COM_SEMINARMAN_CART_BOOKING_TOTAL'); ?>:" data-currency=" <?php echo $params->get('currency'); ?>"><?php echo JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($price_total_booking_with_tax))), 2)); ?></td>
	</tr>
	<?php
			break;
		default:
	?>
	<tr>
	<td class="seminarman_cart_code"><?php echo JText::_('COM_SEMINARMAN_COURSE_CODE'); ?></td>
	<td class="seminarman_cart_course"><?php echo JText::_('COM_SEMINARMAN_COURSE'); ?></td>
	<td class="seminarman_cart_date"><?php echo JText::_('COM_SEMINARMAN_DATE'); ?></td>
	<td class="seminarman_cart_quantity"><?php echo JText::_('COM_SEMINARMAN_CART_QUANTITY'); ?></td>
	<td class="seminarman_cart_price_single"><?php echo JText::_('COM_SEMINARMAN_CART_PRICE_SINGLE') . ' ' . $params->get('currency'); ?></td>
	<td class="seminarman_cart_price_total"><?php echo JText::_('COM_SEMINARMAN_CART_PRICE_TOTAL') . ' ' . $params->get('currency'); ?></td>
	</tr>
	</thead>
	<tbody>
	<tr class="seminarman_cart_item">
	<td class="seminarman_cart_code" data-title="<?php echo JText::_('COM_SEMINARMAN_COURSE_CODE'); ?>:"><?php echo $courseRows->code; ?></td>
	<td class="seminarman_cart_course" data-title="<?php echo JText::_('COM_SEMINARMAN_COURSE'); ?>:"><?php echo $courseRows->title; ?></td>
	<td class="seminarman_cart_date" nowrap="nowrap" data-title="<?php echo JText::_('COM_SEMINARMAN_DATE'); ?>">
	<?php
	  echo $this->course->start;
	  ?>
	  <?php
	  echo ' - <br/>'; 
	  echo $this->course->finish;
	?>
	</td>
	<td class="seminarman_cart_quantity" data-title="<?php echo JText::_('COM_SEMINARMAN_CART_QUANTITY'); ?>:"><?php echo $_POST['attendees']; ?></td>
	<td class="seminarman_cart_price_single" data-title="<?php echo JText::_('COM_SEMINARMAN_CART_PRICE_SINGLE'); ?>:" data-currency=" <?php echo $params->get('currency'); ?>"><?php echo JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($price_orig))), 2)); ?></td>
	<td class="seminarman_cart_price_total" data-title="<?php echo JText::_('COM_SEMINARMAN_CART_PRICE_TOTAL'); ?>:" data-currency=" <?php echo $params->get('currency'); ?>"><?php echo JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($price_total_orig))), 2)); ?></td>
	</tr>

	<?php if ($courseRows->vat <> 0) { ?>
	<tr>
	<td class="seminarman_cart_netto_total_title" colspan="4"><?php echo JText::_('COM_SEMINARMAN_CART_NETTO_TOTAL'); ?></td>
	<td class="seminarman_cart_netto_total" colspan="2" data-title="<?php echo JText::_('COM_SEMINARMAN_CART_NETTO_TOTAL'); ?>:" data-currency=" <?php echo $params->get('currency'); ?>"><?php echo JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($price_total_orig))), 2)); ?></td>
	</tr>
	<?php } ?>
	<?php if ($price_total_discount <> 0) { ?>
	<tr>
	<td class="seminarman_cart_discount_total_title" colspan="4"><?php echo JText::_('COM_SEMINARMAN_CART_DISCOUNT_TOTAL'); ?></td>
	<td class="seminarman_cart_discount_total" colspan="2" data-title="<?php echo JText::_('COM_SEMINARMAN_CART_DISCOUNT_TOTAL'); ?>:" data-currency=" <?php echo $params->get('currency'); ?>"><?php echo JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($price_total_discount))), 2)); ?></td>
	</tr>
	<?php } ?>
	<?php if ($courseRows->vat <> 0) { ?>
	<tr>
	<td class="seminarman_cart_withoutVat_total_title" colspan="4"><?php echo JText::sprintf('COM_SEMINARMAN_CART_WITHOUT_VAT', JText::sprintf('%.0f', round(doubleval(str_replace(",", ".", $this->escape($courseRows->vat))), 2)) . '%'); ?></td>
	<td class="seminarman_cart_withoutVat_total" colspan="2" data-title="<?php echo JText::sprintf('COM_SEMINARMAN_CART_WITHOUT_VAT', JText::sprintf('%.0f', round(doubleval(str_replace(",", ".", $this->escape($courseRows->vat))), 2)) . '%'); ?>:" data-currency=" <?php echo $params->get('currency'); ?>"><?php echo JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($tax_booking))), 2)); ?></td>
	</tr>
	<?php } ?>
	<tr>
	<td class="seminarman_cart_booking_total_title" colspan="4"><?php echo JText::_('COM_SEMINARMAN_CART_BOOKING_TOTAL'); ?></td>
	<td class="seminarman_cart_booking_total" colspan="2" data-title="<?php echo JText::_('COM_SEMINARMAN_CART_BOOKING_TOTAL'); ?>:" data-currency=" <?php echo $params->get('currency'); ?>"><?php echo JText::sprintf('%.2f', round(doubleval(str_replace(",", ".", $this->escape($price_total_booking_with_tax))), 2)); ?></td>
	</tr>
	<?php } ?>

<?php 
	$dispatcher=JDispatcher::getInstance();
	JPluginHelper::importPlugin('seminarman');
	$html_tmpl=$dispatcher->trigger('onGetAddPriceForCart',array($courseRows));  // we need the course id
	if(isset($html_tmpl) && !empty($html_tmpl)) echo $html_tmpl[0];
?>	
	
	</tbody>
	</table>
	<br />
	<center>
	<form action="#" method="post" name="adminForm" id="adminForm" class="form-validate"  enctype="multipart/form-data">
	<?php 
	    foreach ($this->fields as $name => $this->fieldGroup){
	    	foreach ($this->fieldGroup as $f){
	    		$f = JArrayHelper::toObject ($f);
	    		$f->value = $this->escape($f->value);
	    		// $var = 'field' . $f->id;
	    		// echo '<input type="hidden" name="' . $var .'" value="' . $_POST[$var] . '" />';
	    		if ($f->type == "checkboxtos") {
	    			$tos = $f->options->{"0"};
	    			// echo '<input type="checkbox" id="cm_tos" /> ' . $tos . '<br /><br />';
	    			echo '<div style="text-align: left; overflow: hidden;">'. SeminarmanCustomfieldsLibrary::getFieldHTML($f , '') . '</div>';
	    		}
	    	}
	    }
	?>
	<button type="button" class="btn btn-primary cancel" onclick="submitbuttonSeminarman('cancel')">
	<?php echo JText::_('COM_SEMINARMAN_CART_CANCEL_BUTTON');?>
	</button>
	<button id="submitSeminarman" type="button" class="btn btn-primary validate" onclick="submitbuttonSeminarman('save')">
	<?php echo JText::_('COM_SEMINARMAN_CART_CONFIRM_BUTTON');?>
	</button>
	    <input type="hidden" name="course_id" value="<?php echo $_POST['course_id']; ?>" />
	    <input type="hidden" name="email" value="<?php echo $_POST['email']; ?>" />
	    <input type="hidden" name="attendees" value="<?php echo $_POST['attendees']; ?>" />
	    <input type="hidden" name="salutation" value="<?php echo $_POST['salutation']; ?>" />
	    <input type="hidden" name="title" value="<?php echo $_POST['title']; ?>" />
	    <input type="hidden" name="first_name" value="<?php echo $_POST['first_name']; ?>" />
	    <input type="hidden" name="last_name" value="<?php echo $_POST['last_name']; ?>" />
	    <input type="hidden" name="booking_price[]" value="<?php echo $_POST['booking_price'][0]; ?>" />
	    <?php 
	    foreach ($this->fields as $name => $this->fieldGroup){
	    	foreach ($this->fieldGroup as $f){
	    		$f = JArrayHelper::toObject ($f);
	    		$f->value = $this->escape($f->value);
	    		$var = 'field' . $f->id;
				if (($f->type == "checkbox") || ($f->type == "list") || ($f->type == "time") || ($f->type == "url")) {
					if (isset($_POST[$var])) {
						foreach ($_POST[$var] as $f_item) {
							echo '<input type="hidden" name="' . $var .'[]" value="' . $f_item . '" />';
						}
					}
	    		} elseif ($f->type != "checkboxtos") {
	    			if (isset($_POST[$var])) {
	    		        echo '<input type="hidden" name="' . $var .'" value="' . $_POST[$var] . '" />';
	    			}
	    		}
	    	}
	    }
	    ?>
	    
<?php 
	$dispatcher=JDispatcher::getInstance();
	JPluginHelper::importPlugin('seminarman');
	$html_tmpl=$dispatcher->trigger('onPostAddPriceForCart',array($courseRows));  // we need the course id
	if(isset($html_tmpl) && !empty($html_tmpl)) echo $html_tmpl[0];
?>	    
	    
	    <input type="hidden" name="option" value="com_seminarman" />
	    <input type="hidden" name="controller" value="application" />
	    <input type="hidden" name="task" value="" />
	<?php
	    echo JHTML::_('form.token');
	?>
	</form>
	</center>
</div>