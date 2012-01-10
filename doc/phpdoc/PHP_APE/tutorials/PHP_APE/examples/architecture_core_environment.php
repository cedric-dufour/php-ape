<?php
// Load PHP-APE
require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );

// Use PHP-APE environment
$roEnvironment =& PHP_APE_WorkSpace::useEnvironment();
$sPreferredLanguage = $roEnvironment->getUserParameter( 'php_ape.locale.language' );
