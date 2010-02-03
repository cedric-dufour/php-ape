<?php // INDENTING (emacs/vi): -*- mode:php; tab-width:2; c-basic-offset:2; intent-tabs-mode:nil; -*- ex: set tabstop=2 expandtab:
/** PHP Application Programming Environment (PHP-APE)
 *
 * <P><B>COPYRIGHT:</B></P>
 * <PRE>
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
 * </PRE>
 *
 * @package PHP_APE_DataSpace
 * @subpackage Classes
 */

/** Core dataspace core class
 *
 * <P><B>SYNOPSIS:</B> This class contains the common definitions required to handle proper data encoding/decoding and
 * rendering/parsing to/from given data spaces (e.g. HTML, XML, Latex, etc.)</P>
 *
 * <P><B>EXAMPLE:</B><P>
 * <CODE>
 * <?php
 * // Load APE
 * require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
 *
 * // Dataspace objects
 * $oInputDataspace = new PHP_APE_DataSpace_Text();
 * $oOutputDataspace = new PHP_APE_DataSpace_HTML();
 *
 * // Data objects
 * $oString = new PHP_APE_Type_String();
 * $oTimestamp = new PHP_APE_Type_Timestamp();
 *
 * // Parse data
 * $oInputDataspace->parseData( $oString, 'Cédric (with an accented "e")' );
 * // Data value: (string)'Cédric (with an accented "e")'
 * $oInputDataspace->parseData( $oTimestamp, '2005-12-31 23:59:59.999' );
 * // Data value: (float)1136069999.999
 *
 * // Render data
 * $oOutputDataspace->formatData( $oString );
 * // Output: 'C&eacute;dric (with an accented "e")'
 * $oOutputDataspace->formatData( $oTimestamp );
 * // Output: '2005-12-31 23:59:59.999'
 * ?>
 * </CODE>
 *
 * @package PHP_APE_DataSpace
 * @subpackage Classes
 */
abstract class PHP_APE_DataSpace_Any
{

  /*
   * METHODS: decode/encode
   ********************************************************************************/

  /** Decodes characters according the dataspace-specific encoding rules
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_DataSpace_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>SHOULD be overridden</B>.</P>
   *
   * <P><B>EXAMPLE:</B><P>
   * <CODE>
   * <?php
   * // Load APE
   * require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
   *
   * // Dataspace objects
   * $oInputDataspace = new PHP_APE_DataSpace_HTML();
   *
   * // Decode data
   * echo $oInputDataspace->decodeData( 'C&eacute;dric (with an accented "e")' );
   * // Output: (string)'Cédric (with an accented "e")'
   * ?>
   * </CODE>
   *
   * @param string $sInput Data to decode
   * @param mixed $mParameters,... Additional optional parameters
   * @return string
   */
  public function decodeData( $sInput )
  {
    return $sInput;
  }

  /** Encodes characters according the dataspace-specific encoding rules
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_DataSpace_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>SHOULD be overridden</B>.</P>
   *
   * <P><B>EXAMPLE:</B><P>
   * <CODE>
   * <?php
   * // Load APE
   * require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
   *
   * // Dataspace objects
   * $oOutputDataspace = new PHP_APE_DataSpace_HTML();
   *
   * // Encode data
   * echo $oOutputDataspace->encodeData( 'Cédric (with an accented "e")' );
   * // Output: 'C&eacute;dric (with an accented "e")'
   * ?>
   * </CODE>
   *
   * @param string $sInput Data to encode
   * @param mixed $mParameters,... Additional optional parameters
   * @return string
   */
  public function encodeData( $sInput )
  {
    return $sInput;
  }


  /*
   * METHODS: parse/format
   ********************************************************************************/

  /** Parses the given data from the given value, according to their type and the dataspace-specific encoding rules
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_DataSpace_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>SHOULD be overridden</B>.</P>
   *
   * <P><B>EXAMPLE:</B><P>
   * <CODE>
   * <?php
   * // Load APE
   * require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
   *
   * // Dataspace objects
   * $oInputDataspace = new PHP_APE_DataSpace_HTML();
   *
   * // Data objects
   * $oString = new PHP_APE_Type_String();
   * $oTimestamp = new PHP_APE_Type_Timestamp();
   *
   * // Parse data
   * $oInputDataspace->parseData( $oString, 'C&eacute;dric (with an accented "e")' );
   * // Data value: (string)'Cédric (with an accented "e")'
   * $oInputDataspace->parseData( $oTimestamp, '2005-12-31 23:59:59.999' );
   * // Data value: (float)1136069999.999
   * ?>
   * </CODE>
   *
   * @param PHP_APE_Type_Any $oData Destination data object
   * @param mixed $mValue Input value
   * @param boolean $bStrict Strict data parsing (<SAMP>null</SAMP> remains <SAMP>null</SAMP>)
   * @param mixed $mFormat Data format (retrieved from the data object itself if <SAMP>null</SAMP>)
   */
  public function parseData( PHP_APE_Type_Any $oData, $mValue, $bStrict = true, $mFormat = null )
  {
    $oData->setValueParsed( $this->decodeData( $mValue ), $bStrict = true, $mFormat );
  }

  /** Formats the given data, according to their type and the dataspace-specific encoding rules
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_DataSpace_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>SHOULD be overridden</B>.</P>
   *
   * <P><B>EXAMPLE:</B><P>
   * <CODE>
   * <?php
   * // Load APE
   * require_once( $_SERVER['PHP_APE_ROOT'].'/load.php');
   *
   * // Dataspace objects
   * $oOutputDataspace = new PHP_APE_DataSpace_HTML();
   *
   * // Data objects
   * $oString = new PHP_APE_Type_String( 'Cédric (with an accented "e")' );
   * $oTimestamp = new PHP_APE_Type_Timestamp( 1136069999.999 ); // 2005-12-31 23:59:59.999
   *
   * // Format data
   * $oOutputDataspace->formatData( $oString );
   * // Output: 'C&eacute;dric (with an accented "e")'
   * $oOutputDataspace->formatData( $oTimestamp );
   * // Output: '2005-12-31 23:59:59.999'
   * ?>
   * </CODE>
   *
   * @param PHP_APE_Type_Any $oData Input data
   * @param mixed $mFormat Data format (retrieved from the data object itself if <SAMP>null</SAMP>)
   * @param string $sIfNull Default output for <SAMP>null</SAMP> input
   * @return string
   */
  public function formatData( PHP_APE_Type_Any $oData, $mFormat = null, $sIfNull = null )
  {
    return $this->encodeData( $oData->getValueFormatted( $sIfNull, $mFormat ) );
  }
}
