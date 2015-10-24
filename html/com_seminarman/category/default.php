<?php
/**
* @Copyright Copyright (C) 2010 www.profinvent.com. All rights reserved.
* Copyright (C) 2011 Open Source Group GmbH www.osg-gmbh.de
* @website http://www.profinvent.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

?>
<script type="text/javascript">
function tableOrdering( order, dir, task ) {

	if (task == 'il') {
		var form = document.getElementById('adminForm2');
		form.filter_order2.value = order;
		form.filter_order_Dir2.value = dir;
		document.getElementById('adminForm2').submit( task );
	}
	else {
		var form = document.getElementById('adminForm');
		form.filter_order.value = order;
		form.filter_order_Dir.value	= dir;
		document.getElementById('adminForm').submit( task );
	}
}
</script>

<div class="mdl-card mdl-shadow--2dp" style="width:100%;margin-bottom:80px;">
  <?php if ($this->params->get('show_page_heading', 0)) {
    $page_heading = trim($this->params->get('page_heading')); ?>
  <div class="mdl-card__title" style="width: calc(100% - 32px);margin:0 auto;">
    <h3 class="mdl-card__title-text mdl-typography--display-1-color-contrast">
    <?php
    if (!empty($page_heading)) {
      echo $page_heading;
    } else {
      echo $this->category->title ?>
    <?php } ?>
  </h3>
  </div>
  <?php } ?>

	<div class="mdl-card__supportedtext" style="width: calc(100% - 32px);margin:0 auto;">
    <?php

    if ($this->category->id > 0)
    	echo $this->loadTemplate('category');

    if (count($this->categories) && $this->category->id > 0)
        echo $this->loadTemplate('subcategories');
    ?>
    <?php

    jimport('joomla.html.pane');
    $jversion = new JVersion();
    $short_version = $jversion->getShortVersion();

    if (version_compare($short_version, "3.0", 'ge')) {
      $tabs = JPaneOSGF::getInstance('tabs', array('startOffset'=>0));
    } else {
      $tabs = JPane::getInstance('tabs', array('startOffset'=>0));
    }
    echo $tabs->startPane('mytabs');
    echo $tabs->startPanel(JText::_('COM_SEMINARMAN_DATES'), 0);
    echo '<div class="seminarmancoursepan">' . $this->loadTemplate('courses') . '</div>';
    echo $tabs->endPanel();
    if ($this->params->get('enable_salesprospects', 0) == 1)
    {
    	echo $tabs->startPanel(JText::_('COM_SEMINARMAN_LST_OF_SALES_PROSPECTS'), 0);
    	echo '<div class="seminarmantemplatepan">' . $this->loadTemplate('templates') . '</div>';
    	echo $tabs->endPanel();
    }
    echo $tabs->endPane();*/
    ?>
  </div>
</div>
