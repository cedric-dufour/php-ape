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
 * @package PHP_APE_Type
 * @subpackage Classes
 */

/** Angle type
 *
 * <P><B>NOTE:</B> Angle data are handled as <I>float</I>.</P>
 *
 * @package PHP_APE_Type
 * @subpackage Classes
 */
class PHP_APE_Type_Angle
extends PHP_APE_Type_Numeric
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** <I>250000°00'00.000000"</I> offset correction for zero-value angle (allowing negative angle storage as <I>Timestamp</I>)
   * @var integer */
  const Value_Timestamp_Offset = 900000000;

  /** <SAMP>degree</SAMP>:<SAMP>minute</SAMP>:<SAMP>second</SAMP>.<SAMP>subseconds</SAMP> format
   * @var integer */
  const Format_DDDMMSSMicro = 0;

  /** <SAMP>degree</SAMP>:<SAMP>minute</SAMP>:<SAMP>second</SAMP> format
   * @var integer */
  const Format_DDDMMSS = 256;

  /** <SAMP>degree</SAMP>:<SAMP>minute</SAMP> format
   * @var integer */
  const Format_DDDMM = 512;

  /** <SAMP>ddd°mm'ss.micro"</SAMP> format
   * @var integer */
  const Format_SeparatorDMS = 0;

  /** <SAMP>.</SAMP> separator
   * @var integer */
  const Format_SeparatorDot = 8192;

  /** <SAMP>:</SAMP> separator
   * @var integer */
  const Format_SeparatorColon = 16384;

  /** No separator
   * @var integer */
  const Format_SeparatorNone = 24576;

  /** Leading zeros
   * @var integer */
  const Format_LeadingZeros = 0;

  /** No leading zeros
   * @var integer */
  const Format_NoZeros = 32768;

  /** <SAMP>LITERAL</SAMP> format (sample: <I>270°05'10.456789"</I>)
   * @var integer */
  const Format_Literal = 0;


  /*
   * METHODS: static
   ********************************************************************************/

  /** Explode the given data <I>string</I> into basic components
   *
   * <P><B>RETURNS:</B> an <I>array</I> containing:</P>
   * <UL>
   * <LI><SAMP>0</SAMP>: degrees (<I>integer</I>)</LI>
   * <LI><SAMP>1</SAMP>: minutes (<I>integer</I>)</LI>
   * <LI><SAMP>2</SAMP>: seconds (<I>integer</I>)</LI>
   * <LI><SAMP>3</SAMP>: subseconds (<I>float</I>)</LI>
   * </UL>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param string $sString Input string
   * @return array|mixed
   */
  public static function explodeString( $sString )
  {
    if( !preg_match( '/(^|[^-.:\/0-9])(-?[0-9]+)°?([-.:°]([0-5]?[0-9])[m\']?([-.:m\']([0-5]?[0-9])(\.[0-9]+)?[s"]?)?)?([^-:\/0-9]|$)/i', $sString, $aMatch ) )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid/unparsable input; String: '.$sString );
    $amComponents = array();
    $amComponents[0] = (integer)$aMatch[2]; // degrees
    $amComponents[1] = isset($aMatch[4]) ? (integer)$aMatch[4] : 0; // minutes
    $amComponents[2] = isset($aMatch[6]) ? (integer)$aMatch[6] : 0; // seconds
    $amComponents[3] = isset($aMatch[7]) ? (float)$aMatch[7] : 0; // subseconds
    return $amComponents;
  }

  /** Parses the given data <I>string</I> into its corresponding value
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param string $sString Input string
   * @param integer $iFormat Data format (retrieved from the <SAMP>{@link PHP_APE_WorkSpace}</SAMP> environment if <SAMP>null</SAMP>)
   * @return float
   */
  public static function parseString( $sString, $iFormat = null )
  {
    if( is_null( $sString ) ) return null;
    return self::parseComponents( self::explodeString( $sString ), $iFormat );
  }

  /** Parses the given data components <I>array</I> into their corresponding value
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param array|mixed $amComponents Input components <I>array</I>
   * @param integer $iFormat Data format (retrieved from the <SAMP>{@link PHP_APE_WorkSpace}</SAMP> environment if <SAMP>null</SAMP>)
   * @return float
   * @see explodeString()
   */
  public static function parseComponents( $amComponents, $iFormat = null )
  {
    // Check input
    if( !is_array( $amComponents ) or count( $amComponents ) < 4 )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid/unparsable components' );

    // Extract angle
    $iDegree = (integer)$amComponents[0];
    $iMinute = (integer)$amComponents[1];
    $iSecond = (integer)$amComponents[2];
    $fSubsecond = (float)$amComponents[3];
      
    // Check angle
    if( $iMinute<0 or $iMinute>59 or $iSecond<0 or $iSecond>59 or $fSubsecond<0 )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid/unparsable value; Components: '.serialize( $amComponents ) );

    // Return seconds
    $fValue = 3600*$iDegree + 60*$iMinute + $iSecond + $fSubsecond;
    return $fValue;
  }

  /** Parses the given <I>mixed</I> data into their corresponding value
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param mixed $mValue Input value
   * @param boolean $bStrict Strict data parsing (<SAMP>null</SAMP> remains <SAMP>null</SAMP>)
   * @param integer $iFormat Data format (retrieved from the <SAMP>{@link PHP_APE_WorkSpace}</SAMP> environment if <SAMP>null</SAMP>)
   * @param boolean $bParseInternalValue Allow input value to be parsed as an already parsed (internal) value
   * @return float
   */
  public static function parseValue( $mValue, $bStrict = true, $iFormat = null, $bParseInternalValue = false )
  {
    if( ( $mValue instanceof PHP_APE_Type_Date ) or ( $mValue instanceof PHP_APE_Type_Timestamp ) )
    {
      $mValue = PHP_APE_Type_Scalar::parseValue( $mValue, $bStrict );
      if( !is_null( $mValue ) ) $mValue -= PHP_APE_MKTIME_OFFSET;
    }
    else
      $mValue = PHP_APE_Type_Scalar::parseValue( $mValue, $bStrict );
    if( is_null( $mValue ) ) return $bStrict ? null : (float)0;
    if( $bParseInternalValue and ( !is_string( $mValue ) or is_numeric( $mValue ) ) )
    {
      $mValue = (float)$mValue;
      return $mValue;
    }
    return self::parseString( $mValue, $iFormat );
  }

  /** Format the given data
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param mixed $mValue Input value
   * @param string $sIfNull Output for <SAMP>null</SAMP> value
   * @param integer $iFormatOutput Ouptut data format (retrieved from the <SAMP>{@link PHP_APE_WorkSpace}</SAMP> environment if <SAMP>null</SAMP>)
   * @param integer $iFormatInput Input data format (retrieved from the <SAMP>{@link PHP_APE_WorkSpace}</SAMP> environment if <SAMP>null</SAMP>)
   * @return string
   */
  public static function formatValue( $mValue, $sIfNull = null, $iFormatOutput = null, $iFormatInput = null )
  {
    if( $iFormatInput != PHP_APE_Type_Any::Format_Passthru ) $mValue = self::parseValue( $mValue, true, $iFormatInput, true );
    if( is_null( $mValue ) ) return (string)$sIfNull;

    // Extract formatting tags
    if( is_null( $iFormatOutput ) or $iFormatOutput == PHP_APE_Type_Any::Format_Default ) $iFormatOutput = PHP_APE_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.data.format.angle' );
    $iFormat = (integer)$iFormatOutput;
    $iFormatAngle = (integer)( $iFormat & ( self::Format_DDDMMSSMicro | self::Format_DDDMMSS | self::Format_DDDMM ) );
    $iFormatSeparator = (integer)( $iFormat & ( self::Format_SeparatorDMS | self::Format_SeparatorDot | self::Format_SeparatorColon | self::Format_SeparatorNone ) );

    // Retrieve components
    $mValue = (float)$mValue;
    $bNegative = ( $mValue < 0  );
    $mValue = abs( $mValue );
    $iDegree = (integer)floor( $mValue / 3600 );
    $iMinute = (integer)floor( ( $mValue - 3600 * $iDegree ) / 60 );
    $iSecond = (integer)floor( ( $mValue - 3600 * $iDegree - 60 * $iMinute ) );
    $iSubsecond = (integer)( 1000000*round( fmod( $mValue, 1 ), 6 ) );

    // Construct data string
    $sSign = $bNegative ? '-' : null;
    $sDegree = (string)$iDegree;
    $sMinute = substr( '00'.$iMinute, -2 );
    $sSecond = substr( '00'.$iSecond, -2 );
    $sSubsecond = substr( '000000'.$iSubsecond, -6 );

    switch ($iFormatAngle)
    {

    case self::Format_DDDMM:
      $bIncludeSecond = false;
      $bIncludeMicro = false;
      break;

    case self::Format_DDDMMSS:
      $bIncludeSecond = true;
      $bIncludeMicro = false;
      break;

    case self::Format_DDDMMSSMicro:
    default:
      $bIncludeSecond = true;
      $bIncludeMicro = true;

    }

    switch ($iFormatSeparator)
    {

    case self::Format_SeparatorNone:
      return $sSign.$sDegree.$sMinute.( $bIncludeSecond ? ( $sSecond.( $bIncludeMicro ? ('.'.$sSubsecond) : null ) ) : null );

    case self::Format_SeparatorColon:
      return $sSign.$sDegree.':'.$sMinute.( $bIncludeSecond ? ( ':'.$sSecond.( $bIncludeMicro ? ('.'.$sSubsecond) : null ) ) : null );
      break;

    case self::Format_SeparatorDot:
      return $sSign.$sDegree.'.'.$sMinute.( $bIncludeSecond ? ( '.'.$sSecond.( $bIncludeMicro ? ('.'.$sSubsecond) : null ) ) : null );
      break;

    case self::Format_SeparatorDMS:
    default:
      return $sSign.$sDegree.'°'.$sMinute.'\''.( $bIncludeSecond ? ( $sSecond.( $bIncludeMicro ? ('.'.$sSubsecond) : null ).'"' ) : null );

    }

  }


  /*
   * METHODS: magic - OVERRIDE
   ********************************************************************************/

  public function __toString()
  {
    return self::formatValue( $this->mValue, '#NULL#', self::Format_Literal, PHP_APE_Type_Any::Format_Passthru );
  }


  /*
   * METHODS: value - OVERRIDE
   ********************************************************************************/

  public function setValue( $mValue )
  {
    $this->mValue = self::parseValue( $mValue, true, null, true );
  }


  /*
   * METHODS: format - OVERRIDE
   ********************************************************************************/

  public function getFormat()
  {
    if( is_null( $this->iFormat ) or (string)$this->iFormat == PHP_APE_Type_Any::Format_Default )
      return PHP_APE_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.data.format.angle' );
    return $this->iFormat;
  }


  /*
   * METHODS: parse/format - OVERRIDE
   ********************************************************************************/

  public function setValueParsed( $mValue, $bStrict = true, $iFormat = null )
  {
    if( is_null( $iFormat ) ) $iFormat = $this->getFormat();
    $this->mValue = self::parseValue( $mValue, $bStrict, $iFormat );
  }

  public function getValueFormatted( $sIfNull = null, $iFormat = null )
  {
    if( is_null( $this->mValue ) ) return (string)$sIfNull;
    if( is_null( $iFormat ) ) $iFormat = $this->getFormat();
    if( $iFormat == PHP_APE_Type_Any::Format_Default ) $iFormat = PHP_APE_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.data.format.angle' );
    return self::formatValue( $this->mValue, $sIfNull, $iFormat, PHP_APE_Type_Any::Format_Passthru );
  }


  /*
   * METHODS: introspection - OVERRIDE
   ********************************************************************************/

  /** Returns a sample value for this object
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return mixed
   */
  public function getSampleValue()
  {
    if( !is_null( $this->mDefaultValue ) ) return $this->mDefaultValue;
    return (float)972000;
  }


  /*
   * METHODS
   ********************************************************************************/

  /** Returns the seconds part
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return integer
   */
  final public function getSeconds()
  {
    if( is_null( $this->mValue ) ) return null;
    return (integer)$this->mValue;
  }

  /** Returns the subseconds part
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param integer $iPrecision Decimal precision
   * @return integer
   */
  final public function getSubseconds( $iPrecision = 6 )
  {
    if( is_null( $this->mValue ) ) return null;
    $iPrecision = (integer)$iPrecision;
    if( $iPrecision < 0 ) $iPrecision = 0;
    elseif( $iPrecision > 6 ) $iPrecision = 6;
    return (integer)( pow( 10, $iPrecision ) * round( fmod( $this->mValue, 1 ), $iPrecision ) );
  }

}
