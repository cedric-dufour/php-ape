<?php // INDENTING (emacs/vi): -*- mode:php; tab-width:2; c-basic-offset:2; intent-tabs-mode:nil; -*- ex: set tabstop=2 expandtab:
/*
 * PHP Application Programming Environment (PHP-APE)
 * Copyright (C) 2005-2006 Cedric Dufour
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

/** TESTS
 *
 * <P><B>SYNOPSIS:</B> This script tests the definition and behaviour of the objects included in this package,
 * which should allow developers and users to verify the functionality of its resources,
 * and hopefully also gain a better understanding on how to implement or use them.</P>
 *
 * @package PHP_APE_DataSpace
 * @subpackage Tests
 */

/** Load PHP-APE */
echo "<H1>APE tests</H1>\r\n";
echo "<P><B>SYNOPSIS:</B> This script tests the definition and behaviour of the objects included in this package,\r\n";
echo "which should allow developers and users to verify the functionality of its resources,\r\n";
echo "and hopefully also gain a better understanding on how to implement or use them.</P>\r\n";
require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
require_once( PHP_APE_ROOT.'/lib/dataspace/load.php' );
$roEnvironment = PHP_APE_WorkSpace::useEnvironment();

// Dataspaces
echo "<H2>Dataspaces</H2>\r\n";
$asDataspaces = array( 'Text', 'XML', 'HTML', 'JavaScript', 'LaTeX' );

$amDataTestset =
array(
      new PHP_APE_Type_Boolean( false ),
      new PHP_APE_Type_Boolean( true ),
      new PHP_APE_Type_Integer( +1 ),
      new PHP_APE_Type_Byte( 127 ),
      new PHP_APE_Type_UByte( 255 ),
      new PHP_APE_Type_Int2( 32767 ),
      new PHP_APE_Type_UInt2( 65535 ),
      new PHP_APE_Type_Float( +0.1 ),
      new PHP_APE_Type_Float4( +0.1 ),
      new PHP_APE_Type_Float8( +0.1 ),
      new PHP_APE_Type_Decimal( +0.1 ),
      new PHP_APE_Type_Money( +0.1 ),
      new PHP_APE_Type_Date( '2005-12-31' ),
      new PHP_APE_Type_Time( '23:59:59.999000' ),
      new PHP_APE_Type_Timestamp( '2005-12-31 23:59:59.999000' ),
      new PHP_APE_Type_Angle( '270°59\'59.999000"' ),
      new PHP_APE_Type_String( "A carriage-return\r\nseparated\r\ntext, with special characters: °\"~<>באגהיטךכםלמןףעפצתשûü" ),
      new PHP_APE_Type_Char( "A carriage-return\r\nseparated\r\ntext, with special characters: °\"~<>באגהיטךכםלמןףעפצתשûü" ),
      new PHP_APE_Type_Text( "A carriage-return\r\nseparated\r\ntext, with special characters: °\"~<>באגהיטךכםלמןףעפצתשûü" ),
      new PHP_APE_Type_Password( "A carriage-return\r\nseparated\r\ntext, with special characters: °\"~<>באגהיטךכםלמןףעפצתשûü" ),
      new PHP_APE_Type_Email( "firstname.lastname@domain.tld" ),
      new PHP_APE_Type_URL( "http://server.domain.tld/path/to/files" ),
      new PHP_APE_Type_Path( "/path/to/files" ),
      new PHP_APE_Type_Dir( "/path/to/files" ),
      new PHP_APE_Type_File( "/path/to/files" )
      );

      echo "<STYLE TYPE=\"text/css\">\r\n";
      echo "TABLE {BORDER-TOP: solid 1px #C0C0C0; BORDER-LEFT: solid 1px #C0C0C0; }\r\n";
      echo "TH {PADDING: 0px; FONT: 10px courier bold; BORDER-RIGHT: solid 1px #C0C0C0; BORDER-BOTTOM: solid 1px #C0C0C0; BACKGROUND: #C0C0C0; }\r\n";
      echo "TD {PADDING: 0px; FONT: 10px courier; BORDER-RIGHT: solid 1px #C0C0C0; BORDER-BOTTOM: solid 1px #C0C0C0; }\r\n";
      echo "</STYLE>\r\n";

      echo "<BLOCKQUOTE>\r\n";
      echo "<TABLE CELLSPACING=\"0\">\r\n";
      echo '<TR><TH>Data</TH><TH>Value</TH>';
      foreach( $asDataspaces as $sName ) echo '<TH>'.$sName.'</TH>';
      echo "</TR>\r\n";
      foreach( $amDataTestset as $mValue )
{
  echo '<TR><TD>['.get_class( $mValue ).'] "'.$mValue->__toString().'"</TD>';
  echo '<TD>['.gettype( $mValue->getValue() ).'] "'.$mValue->getValue().'"</TD>';
  foreach( $asDataspaces as $sName )
  {
    echo '<TD>';
    $oDataspace = $roEnvironment->getDataspace( $sName );
    $sClass = get_class($mValue);
    try
    {
      $sOutput = $oDataspace->formatData( $mValue, null, '#NULL#' );
      echo 'FORMAT: '.nl2br( htmlentities( $sOutput ) );
      $mValueBlank = new $sClass();
      try
      {
        $oDataspace->parseData( $mValueBlank, $sOutput );
        echo '<BR/>PARSED: '.nl2br( htmlentities( $mValueBlank->__toString() ) );
      }
      catch( PHP_APE_DataSpace_Exception $e )
        {
          echo '<BR/>PARSED: ERROR: '.htmlentities( $e->getMessage() );
        }
    }
    catch( PHP_APE_DataSpace_Exception $e )
      {
        echo 'FORMAT: ERROR: '.htmlentities( $e->getMessage() );
      }
    echo '</TD>';
  }
  echo "</TR>\r\n";
}
echo "</TABLE>\r\n";
echo "</BLOCKQUOTE>\r\n";

// Statistics
echo "<H2>Statistics</H2>\r\n";
echo "<BLOCKQUOTE>\r\n";
echo "<P>\r\n";
echo 'Definitions load attempts: '.PHP_APE_Resources::$iIOStatDefinitionsAttempted."<BR/>\r\n";
echo 'Definitions load successes: '.PHP_APE_Resources::$iIOStatDefinitionsLoaded."<BR/>\r\n";
echo 'Properties cache attempts: '.PHP_APE_Properties::$iIOStatPropertiesCached."<BR/>\r\n";
echo 'Properties load attempts: '.PHP_APE_Properties::$iIOStatPropertiesAttempted."<BR/>\r\n";
echo 'Properties load successes: '.PHP_APE_Properties::$iIOStatPropertiesLoaded."<BR/>\r\n";
if( function_exists( 'memory_get_usage' ) ) 
{
  echo 'Peak memory usage: '.memory_get_peak_usage()."<BR/>\r\n";
  echo 'Current memory usage: '.memory_get_usage()."<BR/>\r\n";
}
echo "</P>\r\n";
echo "</BLOCKQUOTE>\r\n";
