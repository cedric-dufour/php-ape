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
 * @package PHP_APE_Type
 * @subpackage Tests
 */

/** Load PHP-APE */
echo "<H1>APE tests</H1>\r\n";
echo "<P><B>SYNOPSIS:</B> This script tests the definition and behaviour of the objects included in this package,\r\n";
echo "which should allow developers and users to verify the functionality of its resources,\r\n";
echo "and hopefully also gain a better understanding on how to implement or use them.</P>\r\n";
require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
require_once( PHP_APE_ROOT.'/lib/type/load.php' );

// Data types
echo "<H2>Data types</H2>\r\n";
$roEnvironment = PHP_APE_WorkSpace::useEnvironment();

$amDataTypes =
array(
      array( 'PHP_APE_Type_Boolean', array( PHP_APE_Type_Boolean::Format_Boolean, PHP_APE_Type_Boolean::Format_Numeric, PHP_APE_Type_Boolean::Format_TrueFalse, PHP_APE_Type_Boolean::Format_YesNo, PHP_APE_Type_Boolean::Format_OnOff ) ),
      array( 'PHP_APE_Type_Integer', array() ),
      array( 'PHP_APE_Type_Byte', array() ),
      array( 'PHP_APE_Type_UByte', array() ),
      array( 'PHP_APE_Type_Int2', array() ),
      array( 'PHP_APE_Type_UInt2', array() ),
      array( 'PHP_APE_Type_Int4', array() ),
      array( 'PHP_APE_Type_Int8', array() ),
      array( 'PHP_APE_Type_Float4', array() ),
      array( 'PHP_APE_Type_Float8', array() ),
      array( 'PHP_APE_Type_Decimal', array() ),
      array( 'PHP_APE_Type_Money', array() ),
      array( 'PHP_APE_Type_Date', array( PHP_APE_Type_Date::Format_ISO, PHP_APE_Type_Date::Format_European, PHP_APE_Type_Date::Format_American ) ),
      array( 'PHP_APE_Type_Time', array( PHP_APE_Type_Time::Format_ISO, PHP_APE_Type_Time::Format_European, PHP_APE_Type_Time::Format_American ) ),
      array( 'PHP_APE_Type_Timestamp', array( PHP_APE_Type_Timestamp::Format_ISO, PHP_APE_Type_Timestamp::Format_European, PHP_APE_Type_Timestamp::Format_American ) ),
      array( 'PHP_APE_Type_Angle', array( PHP_APE_Type_Angle::Format_Literal ) ),
      array( 'PHP_APE_Type_String', array() ),
      array( 'PHP_APE_Type_Char', array() ),
      array( 'PHP_APE_Type_Text', array() ),
      array( 'PHP_APE_Type_Password', array() ),
      array( 'PHP_APE_Type_Email', array() ),
      array( 'PHP_APE_Type_URL', array() ),
      array( 'PHP_APE_Type_Path', array() ),
      array( 'PHP_APE_Type_Dir', array() ),
      array( 'PHP_APE_Type_File', array() )
      );

      $amDataTestset =
      array(
            null,
            (boolean)false,
            new PHP_APE_Type_Boolean( false ),
            new PHP_APE_Type_Boolean( true ),
            (integer)0,
            new PHP_APE_Type_Integer( 0 ),
            new PHP_APE_Type_Integer( -1 ),
            new PHP_APE_Type_Integer( +1 ),
            new PHP_APE_Type_Integer( 128 ),
            new PHP_APE_Type_Integer( 256 ),
            new PHP_APE_Type_Integer( 32768 ),
            new PHP_APE_Type_Integer( 65536 ),
            (float)0,
            new PHP_APE_Type_Float( 0 ),
            new PHP_APE_Type_Float( -0.1 ),
            new PHP_APE_Type_Float( 0.1 ),
            new PHP_APE_Type_Date( '2005-12-31' ), // 1136069999
            new PHP_APE_Type_Time( '23:59:59.999000' ), // 86399.999
            new PHP_APE_Type_Timestamp( '2005-12-31 23:59:59.999000' ), // 1136069999.999
            new PHP_APE_Type_Angle( '270∞59\'59.999000"' ), // 975599.999
            (string)'',
            new PHP_APE_Type_String( 'string' ),
            new PHP_APE_Type_String( '0' ),
            new PHP_APE_Type_String( '-1' ),
            new PHP_APE_Type_String( '+1' ),
            new PHP_APE_Type_String( '128' ),
            new PHP_APE_Type_String( '256' ),
            new PHP_APE_Type_String( '32768' ),
            new PHP_APE_Type_String( '65536' ),
            new PHP_APE_Type_String( '-0.1' ),
            new PHP_APE_Type_String( '+0.1' ),
            new PHP_APE_Type_String( 'Money is 1\'000\'000.00 (a lot of money)' ),
            new PHP_APE_Type_String( 'ISO date is 2005-12-31.' ),
            new PHP_APE_Type_String( 'European date is 31.12.2005.' ),
            new PHP_APE_Type_String( 'American date is 12/31/2005.' ),
            new PHP_APE_Type_String( 'Invalid ISO date is 1789-07-14.' ),
            new PHP_APE_Type_String( 'Invalid European date is 14-07-1789.' ),
            new PHP_APE_Type_String( 'Invalid American date is 07-14-1789.' ),
            new PHP_APE_Type_String( 'ISO/European time is 23:59:59.999000.' ),
            new PHP_APE_Type_String( 'American time is 11:59:59.999000 PM.' ),
            new PHP_APE_Type_String( 'ISO/European timestamp is 2005-12-31 23:59:59.999000.' ),
            new PHP_APE_Type_String( 'Literal angle is 270∞59\'59.999000".' ),
            new PHP_APE_Type_String( 'E-mail address is firstname.lastname@domain.tld.' ),
            new PHP_APE_Type_String( 'URL is http://server.domain.tld/path/to/files.' ),
            new PHP_APE_Type_String( '/path/to/files' ),
            new PHP_APE_Type_String( '/path/to/../files' ),
            new PHP_APE_Type_String( "A carriage-return\r\nseparated\r\ntext" ),
            new PHP_APE_Type_String( 'Some special characters: ∞"~<>·‡‚‰ÈËÍÎÌÏÓÔÛÚÙˆ˙˘˚¸' )
            );

            echo "<STYLE TYPE=\"text/css\">\r\n";
            echo "TABLE {BORDER-TOP: solid 1px #C0C0C0; BORDER-LEFT: solid 1px #C0C0C0; }\r\n";
            echo "TH {PADDING: 0px; FONT: 10px courier bold; BORDER-RIGHT: solid 1px #C0C0C0; BORDER-BOTTOM: solid 1px #C0C0C0; BACKGROUND: #C0C0C0; }\r\n";
            echo "TD {PADDING: 0px; FONT: 10px courier; BORDER-RIGHT: solid 1px #C0C0C0; BORDER-BOTTOM: solid 1px #C0C0C0; }\r\n";
            echo "</STYLE>\r\n";

            foreach( $amDataTypes as $mDataType )
{
  $sClass = $mDataType[0];
  $aiFormats = $mDataType[1];
  echo '<H3>'.$sClass."</H3>\r\n";
  echo "<BLOCKQUOTE>\r\n";
  $oData = new $sClass();
  echo "<P>\r\n";
  echo '(Dynamically-Generated) Sample: '.htmlentities($oData->getSampleString())."<BR/>\r\n";
  echo '(Dynamically-Generated) Constraints: '.htmlentities($oData->getConstraintsString())."<BR/>\r\n";
  echo "</P>\r\n";
  echo "<TABLE CELLSPACING=\"0\">\r\n";
  echo "<TR><TH>Input</TH><TH>Value</TH><TH>Output</TH><TH>Format</TH></TR>\r\n";
  foreach( $amDataTestset as $mValue )
  {
    try
    {
      $oData->setValue( $mValue );
      echo '<TR>';
      echo '<TD>';
      if( is_null( $mValue ) ) echo '#NULL#';
      elseif( is_object( $mValue ) ) echo '['.get_class( $mValue ).'] "'.$mValue->__toString().'"';
      else echo '['.gettype( $mValue ).'] "'.$mValue.'"';
      echo '</TD>';
      echo '<TD>['.gettype( $sValue=$oData->getValue() ).'] '.( strlen( $sValue ) > 0 ? '"'.nl2br( htmlentities( $sValue ) ).'"' : '&nbsp;' ).'</TD>';
      echo '<TD>'.( strlen( $sValue = $oData->getValueFormatted( '#NULL#' ) ) > 0 ? nl2br( htmlentities( $sValue ) ) : '#EMPTY#' ).'</TD>';
      echo '<TD>DEFAULT</TD>';
      echo "</TR>\r\n";
      foreach( $aiFormats as $iFormat )
      {
        echo '<TR>';
        echo '<TD COLSPAN="2">&nbsp;</TD>';
        echo '<TD>'.( strlen( $sValue = $oData->getValueFormatted( '#NULL#', $iFormat ) ) > 0 ? nl2br( htmlentities( $sValue ) ) : '#EMPTY#' ).'</TD>';
        echo '<TD>'.$iFormat.'</TD>';
        echo "</TR>\r\n";
      }
    }
    catch( PHP_APE_Type_Exception $e )
      {
        echo '<TR>';
        echo '<TD>';
        if( is_null( $mValue ) ) echo '#NULL#';
        elseif( is_object( $mValue ) ) echo '['.get_class($mValue).'] "'.$mValue->__toString().'"';
        else echo '[scalar] "'.$mValue.'"';
        echo '</TD>';
        echo '<TD>n/a</TD>';
        echo '<TD>ERROR: '.htmlentities( $e->getMessage() ).'</TD>';
        echo '<TD>n/a</TD>';
        echo "</TR>\r\n";
      }
  }
  echo "</TABLE>\r\n";
  echo "</BLOCKQUOTE>\r\n";
  
}

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
