<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');
?>

<div class="floattext">
	<h4 class="seminarman cat<?php echo $this->category->id; ?>"><?php echo $this->escape($this->category->title); ?></h4>
  <?php if (!empty($this->category->image)){ ?>
  	<div class="catimg">
      <?php
      $jversion = new JVersion();
      $short_version = $jversion->getShortVersion();
      ?><img src="<?php echo 'images/' . $this->category->image ?>" style="width:200px;"/>
    </div>
  <?php } ?>
	<div class="catdescription"><?php echo $this->category->text; ?></div>
</div>
