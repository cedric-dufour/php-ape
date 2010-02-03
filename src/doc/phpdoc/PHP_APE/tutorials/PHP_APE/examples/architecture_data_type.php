<?php
// Load PHP-APE
require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );

// My date variable
$sDefaultValue = '2005-12-31';
$sMinimumValue = '2005-01-01';
$sMaximumValue = '2007-12-31';
$oDate = new PHP_APE_Type_Date( $sDefaultValue, $sMinimumValue, $sMaximumValue );

// ... parse value
$oDate->setValue( '2001-11-09' ); // NOTE: constraints violation!

// ... check contraints
if( !$oDate->checkConstraints() ) $oDate->setToDefaultValue(); // NOTE: value reset to default

// ... internal value
echo $oDate->getValue(); // OUTPUT: 1135983600

// ... format value
$sIfNull = 'No date';
$iFormat = PHP_APE_Type_Date::Format_European;
echo $oDate->getValueFormatted( $sIfNull, $iFormat ); // OUTPUT: 31.12.2005
