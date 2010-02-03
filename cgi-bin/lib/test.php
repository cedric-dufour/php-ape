<?php
require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
//$asPackages = array( '.', 'type', 'dataspace', 'data', 'util', 'util/cache', 'database', 'html', 'html/data' );
$asPackages = array( 'html/data' );
foreach( $asPackages as $sPackage )
{
  require_once( PHP_APE_ROOT.'/lib/'.$sPackage.'/_test.php' );
}

