<?php
// Load PHP-APE
require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );

// My text variable
$oText = new PHP_APE_Type_Text();

// ... HTML input
$oDataSpace_HTML = new PHP_APE_DataSpace_HTML();
$oDataSpace_HTML->parseData( $oText, 'C&eacute;dric<BR/>(with an accented &quot;e&quot;)' );

// ... PHP value
echo $oText->getValue(); // OUTPUT: Cédric
                         //         (with an accented "e")

// ... LaTeX output
$oDataSpace_LaTeX = new PHP_APE_DataSpace_LaTeX();
echo $oDataSpace_LaTeX->formatData( $oText ); // OUTPUT: C\'{e}dric\\(with an accented "e")
