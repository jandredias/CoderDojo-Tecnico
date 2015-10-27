<?php defined( '_JEXEC' ) or die( 'Restricted access' );?>
<?php $config         = JFactory::getConfig(); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"
   xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
  <head>
    <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.5/material.blue-orange.min.css" />
    <!--<link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.5/material.grey-blue.min.css" />-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template.css" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <jdoc:include type="head" />
  </head>
  <body>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-59300930-1', 'auto');
      ga('send', 'pageview');

    </script>
    <script src="https://storage.googleapis.com/code.getmdl.io/1.0.5/material.min.js"></script>
    <script src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/js/template.js"></script>
      <!-- HEADER -->
      <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header has-drawer">
        <header class="coderdojo-header mdl-layout__header mdl-layout__header--waterfall">
          <div class="mdl-layout__header-row">
            <!-- Title -->
            <span class="mdl-layout-title"><?php echo htmlspecialchars($config->get('sitename')); ?></span>

            <!-- Add spacer to align navigation to the right -->
            <div class="mdl-layout-spacer"></div>



            <!-- Navigation -->
            <div class="mdl-cell--hide-phone">
              <jdoc:include type="modules" name="navbar" />
            </div>
            <!-- Search Box -->
            <jdoc:include type="modules" name="search" />
            <!-- End Search Box -->
            <!-- Option menu on navbar -->
            <?php if ($this->countModules( 'vertOptionNavbar' )) : ?>
            <button class="mdl-button mdl-js-button mdl-button--icon">
              <i class="material-icons">more_vert</i>
            </button>
            <jdoc:include type="modules" name="vertOptionNavbar" />
            <?php endif; ?>
            <!-- End Option Menu on navbar -->
          </div>
        </header>

        <?php if($this->countModules('sidebar')) : ?>
        <div class="mdl-layout__drawer mdl-layout--fixed-drawer">
          <div class="mdl-layout--small-screen-only mdl-cell--hide-tablet">
            <jdoc:include type="modules" name="navbar" />
          </div>
          <jdoc:include type="modules" name="sidebar" />
        </div>
        <?php endif; ?>
        <!-- END HEADER -->
        <!-- MAIN CONTENT -->
        <main class="coderdojo-content mdl-layout__content">
          <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--2-col mdl-cell--hide-tablet mdl-cell--hide-phone"></div>
            <div class="content mdl-color-text--grey-800 mdl-cell mdl-cell--8-col">
              <div style="margin-top:50px;"></div>
              <!-- <div class="mdl-card mdl-shadow--2dp" style="width:100%;margin-bottom:80px;"> -->
                <?php // JFactory::getApplication()->enqueueMessage('', 'warning'); ?>
                <?php // JFactory::getApplication()->enqueueMessage('', 'notice'); ?>
                <?php // JFactory::getApplication()->enqueueMessage('', 'error'); ?>
                <?php // JFactory::getApplication()->enqueueMessage('', 'message'); ?>
              <!-- </div> -->
              <jdoc:include type="messages" />
              <jdoc:include type="modules" name="main_top" />
              <!-- DON'T FORGET TO ADD mdl-color--white mdl-shadow--4dp classes to inner element -->
              <jdoc:include type="component" />

              <jdoc:include type="modules" name="main_bottom" />
            </div>
          </div>
          <!-- FOOTER -->
          <!-- Footer is inside main element, so it is not sticked to the bottom of the package
              this way, it will only appear when the user scrolls down the package
              FIXME
          -->
          <footer class="mdl-mini-footer">
            <div class="mdl-mini-footer__left-section">
              <?php if($this->countModules('footer')) : ?>
                <jdoc:include type="modules" name="footer" />
              <?php endif; ?>
              <div class="mdl-logo"><?php echo htmlspecialchars($config->get('sitename')); ?></div>
              <ul class="mdl-mini-footer__link-list">
                <li>Designed by <a href="http://jdiastk.com">AndréDias</a></li>
                <li>Powered by <a href="https://www.joomla.org/">Joomla</a></li>
              </ul>
            </div>
          </footer>
          <!-- END FOOTER -->
        </main>
        <!-- END MAIN CONTENT -->
      </div>
  </body>
</html>
