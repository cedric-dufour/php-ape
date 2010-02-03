<?php
// Load PHP-APE
require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );

// Retrieve environment
$roEnvironment =& PHP_APE_WorkSpace::useEnvironment();

// Add a customized resources path
PHP_APE_Resources::addPath( 'MY_RESOURCES', '/path/to/my/resources/' );

// ... and instantiate some object (defined in '/path/to/my/resources/someClass.php')
$oMyObject = new MY_RESOURCES_someClass();

// ... or load some (localized) properties (defined in '/path/to/my/resources/someProperties.res')
$asMyProperties = $roEnvironment->loadProperties( 'MY_RESOURCES_someProperties' );

// Load PHP-APE localized ISO-3166 country names
require_once( PHP_APE_ROOT.'/util/iso/load.php' );
$asISOCountryNames = $roEnvironment->loadProperties( 'PHP_APE_Util_ISO_3166' );
