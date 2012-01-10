<?php
// Load PHP-APE
require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
require_once( PHP_APE_ROOT.'/database/load.php' );

// Use core environment
$roEnvironment_Core =& PHP_APE_WorkSpace::useEnvironment();
$sPreferredLanguage = $roEnvironment_Core->getUserParameter( 'php_ape.locale.language' );

// Use database environment
$roEnvironment_Database =& PHP_APE_Database_WorkSpace::useEnvironment();
$sPreferredLanguage = $roEnvironment_Database->getUserParameter( 'php_ape.locale.language' ); // Core work space environment is inherited
$asDatabaseDSNs = $roEnvironment_Database->getUserParameter( 'php_ape.database.dsn' ); // Database-specific work space environment parameter
