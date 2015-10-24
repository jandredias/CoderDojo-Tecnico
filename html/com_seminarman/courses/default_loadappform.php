    <!--application form-->
<script type="text/javascript">
function setVisibility() {
    document.getElementById('course_appform').style.display = 'block';
}
function unsetVisibility() {
        document.getElementById('course_appform').style.display = 'none';
}
</script>

<div class="course_applicationform" id="course_appform" style="padding-left:16px;width:calc(100%-32px);">
  <?php if ($params->get('enable_loginform') == 1){
    echo '<h3 class="underline">' . JText::_('COM_SEMINARMAN_LOGIN_PLEASE') . '</h3>';
  } ?>
  <?php  $module = JModuleHelper::getModule('mod_login','OSG Login');
  if ((!(is_null($module))) && ($params->get('enable_loginform') == 1)){
    echo JModuleHelper::renderModule($module);
  }

  switch ($params->get('enable_bookings')) {
    case 3:
      echo $this->loadTemplate('applicationform');
      break;
    case 2:
      echo $this->loadTemplate('applicationform');
      break;
    case 1:
      if ($this->user->get('guest')) {
          echo JText::_('COM_SEMINARMAN_PLEASE_LOGIN_FIRST') .'.';
      } else {
        if ($this->params->get('user_booking_rules')==1){
            $course_booking_permission = JHTMLSeminarman::check_booking_permission($this->course->id, $this->user->id);
        } else {
            $course_booking_permission = true;
        }
        if ($course_booking_permission) {
    	    echo  $this->loadTemplate('applicationform');
      	} else {
            echo JText::_('COM_SEMINARMAN_BOOKING_NOT_ALLOWED');
        }
      }
      break;
    default:
      echo JText::_('COM_SEMINARMAN_BOOKINGS_DISABLED') .'.';
  } ?>
</div>


<?php
if (!( isset($_GET['buchung']) && $_GET['buchung'] == 1 )) {
  echo '<script type="text/javascript">unsetVisibility();</script>';
}
?>
