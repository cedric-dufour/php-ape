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

/** Time type
 *
 * <P><B>NOTE:</B> Time data are handled as <I>float</I>, in order to include subseconds information (seconds + decimal subseconds).</P>
 * <P><B>NOTE:</B> The saved value is NOT a UNIX timestamp, but the seconds count since <I>00:00:00.000000</I></P>
 *
 * @package PHP_APE_Type
 * @subpackage Classes
 */
class PHP_APE_Type_Time
extends PHP_APE_Type_Numeric
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** <SAMP>hour</SAMP>:<SAMP>minute</SAMP>:<SAMP>second</SAMP>.<SAMP>subseconds</SAMP> format
   * @var integer */
  const Format_HHMMSSMicro = 0;

  /** <SAMP>hour</SAMP>:<SAMP>minute</SAMP>:<SAMP>second</SAMP> format
   * @var integer */
  const Format_HHMMSS = 256;

  /** <SAMP>hour</SAMP>:<SAMP>minute</SAMP> format
   * @var integer */
  const Format_HHMM = 512;

  /** 24-hours format
   * @var integer */
  const Format_HH24 = 0;

  /** 12-hours AM/PM format
   * @var integer */
  const Format_AMPM = 2048;

  /** 12-hours PM indicator
   * @var integer */
  const Format_HHPM = 3072;

  /** <SAMP>:</SAMP> separator
   * @var integer */
  const Format_SeparatorCOLON = 0;

  /** <SAMP>.</SAMP> separator
   * @var integer */
  const Format_SeparatorDot = 8192;

  /** <SAMP>12h34m56.789s</SAMP> format
   * @var integer */
  const Format_SeparatorHMS = 16384;

  /** No separator
   * @var integer */
  const Format_SeparatorNone = 24576;

  /** Leading zeros
   * @var integer */
  const Format_LeadingZeros = 0;

  /** No leading zeros
   * @var integer */
  const Format_NoZeros = 32768;

  /** <SAMP>ISO</SAMP> format (sample: <I>23:59:59.999</I>)
   * @var integer */
  const Format_ISO = 0;

  /** <SAMP>European</SAMP> format (sample: <I>23:59:59</I>)
   * @var integer */
  const Format_European = 256;

  /** <SAMP>American</SAMP> format (sample: <I>11:59:59 PM</I>)
   * @var integer */
  const Format_American = 2304;


  /*
   * METHODS: static
   ********************************************************************************/

  /** Returns the current date and time (UNIX timestamp + decimal subseconds)
   *
   * @return float
   * @see microtime(), time()
   */
  public static function now()
  {
    if( function_exists( 'microtime' ) ) return self::parseValue( microtime( true ) );
    else return self::parseValue( time() );
  }

  /** Explode the given data <I>string</I> into basic components
   *
   * <P><B>RETURNS:</B> an <I>array</I> containing:</P>
   * <UL>
   * <LI><SAMP>0</SAMP>: hours (<I>integer</I>)</LI>
   * <LI><SAMP>1</SAMP>: minutes (<I>integer</I>)</LI>
   * <LI><SAMP>2</SAMP>: seconds (<I>integer</I>)</LI>
   * <LI><SAMP>3</SAMP>: subseconds (<I>float</I>)</LI>
   * <LI><SAMP>4</SAMP>: AM/PM or timezone (<I>string</I>)</LI>
   * </UL>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param string $sString Input string
   * @return array|mixed
   */
  public static function explodeString( $sString )
  {
    if( !preg_match( '/(^|[^-.:\/0-9])(2[0-3]|[0-1]?[0-9])[-.:h]([0-5]?[0-9])m?([-.:m]([0-5]?[0-9])(\.[0-9]+)?s?)?\s*(am?|pm?|[-+]2[0-3]|[0-1]?[0-9]|[a-z]{3})?([^-:\/0-9]|$)/i', $sString, $aMatch ) )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid/unparsable input; String: '.$sString );
    $amComponents = array();
    $amComponents[0] = (integer)$aMatch[2]; // hours
    $amComponents[1] = (integer)$aMatch[3]; // minutes
    $amComponents[2] = (integer)$aMatch[5]; // seconds
    $amComponents[3] = (float)$aMatch[6]; // subseconds
    $amComponents[4] = (string)$aMatch[7]; // AM/PM or timezone
    return $amComponents;
  }

  /** Parses the given data <I>string</I> into its corresponding value
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param string $sString Input string
   * @param integer $iFormat Data format [default: <SAMP>php_ape.data.format.time</SAMP> environment preference]
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
   * @param integer $iFormat Data format [default: <SAMP>php_ape.data.format.time</SAMP> environment preference]
   * @return float
   * @see explodeString()
   */
  public static function parseComponents( $amComponents, $iFormat = null )
  {
    // Check input
    if( !is_array( $amComponents ) or count( $amComponents ) < 4 )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid/unparsable components' );

    // Extract formatting tags
    if( is_null( $iFormat ) or $iFormat == PHP_APE_Type_Any::Format_Default ) $iFormat = PHP_APE_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.data.format.time' );
    $iFormat = (integer)$iFormat;
    $iFormatHour = ( $iFormat & ( self::Format_HH24 | self::Format_AMPM ) );
    $iFormatPM = ( $iFormat & self::Format_HHPM );
    $sMeta = strtolower( $amComponents[4] );
    if( in_array( $sMeta, array( 'a', 'am' ) ) )
    {
      $iFormatHour = self::Format_AMPM;
      $iFormatPM = 0;
    }
    elseif( in_array( $sMeta, array( 'p', 'pm' ) ) )
    {
      $iFormatHour = self::Format_AMPM;
      $iFormatPM = self::Format_HHPM;
    }

    // Extract time
    $iHour = (integer)$amComponents[0];
    $iMinute = (integer)$amComponents[1];
    $iSecond = (integer)$amComponents[2];
    $fSubsecond = (float)$amComponents[3];
    if( $iFormatHour==self::Format_AMPM and $iFormatPM==self::Format_HHPM ) $iHour += 12;

    // Check time
    if( $iHour<0 or $iHour>23 or $iMinute<0 or $iMinute>59 or $iSecond<0 or $iSecond>59 or $fSubsecond<0 )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid/unparsable value; Components: '.serialize( $amComponents ) );

    // Return second count
    return (float)( mktime( $iHour, $iMinute, $iSecond, 1, 1, 1970 ) - PHP_APE_MKTIME_OFFSET + $fSubsecond );
  }

  /** Parses the given <I>mixed</I> data into their corresponding value
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param mixed $mValue Input value
   * @param boolean $bStrict Strict data parsing (<SAMP>null</SAMP> remains <SAMP>null</SAMP>)
   * @param integer $iFormat Data format [default: <SAMP>php_ape.data.format.time</SAMP> environment preference]
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
      $iData_seconds = (integer)$mValue;
      $aiDate_parts = getdate( $iData_seconds + PHP_APE_MKTIME_OFFSET );
      return (float)( mktime( $aiDate_parts['hours'], $aiDate_parts['minutes'], $aiDate_parts['seconds'], 1, 1, 1970 ) - PHP_APE_MKTIME_OFFSET + round( fmod( $mValue, 1 ), 6 ) );
    }
    return self::parseString( $mValue, $iFormat );
  }

  /** Format the given data
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param mixed $mValue Input value
   * @param string $sIfNull Output for <SAMP>null</SAMP> value
   * @param integer $iFormatOutput Ouptut data format [default: <SAMP>php_ape.data.format.time</SAMP> environment preference]
   * @param integer $iFormatInput Input data format [default: <SAMP>php_ape.data.format.time</SAMP> environment preference]
   * @return string
   */
  public static function formatValue( $mValue, $sIfNull = null, $iFormatOutput = null, $iFormatInput = null )
  {
    if( $iFormatInput != PHP_APE_Type_Any::Format_Passthru ) $mValue = self::parseValue( $mValue, true, $iFormatInput, true );
    if( is_null( $mValue ) ) return (string)$sIfNull;

    // Extract formatting tags
    if( is_null( $iFormatOutput ) or $iFormatOutput == PHP_APE_Type_Any::Format_Default ) $iFormatOutput = PHP_APE_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.data.format.time' );
    $iFormat = (integer)$iFormatOutput;
    $iFormatTime = (integer)( $iFormat & ( self::Format_HHMMSSMicro | self::Format_HHMMSS | self::Format_HHMM ) );
    $iFormatHour = (integer)( $iFormat & ( self::Format_HH24 | self::Format_AMPM ) );
    $iFormatSeparator = (integer)( $iFormat & ( self::Format_SeparatorCOLON | self::Format_SeparatorDot | self::Format_SeparatorHMS | self::Format_SeparatorNone ) );
    $iFormatZero = (integer)( $iFormat & ( self::Format_LeadingZeros | self::Format_NoZeros ) );

    // Retrieve micro time
    $sMicrotime = substr( '000000'.(integer)( 1000000*round( fmod( $mValue, 1 ), 6 ) ), -6 );

    // Construct format string
    $sFormatHour = ( $iFormatHour == self::Format_AMPM ) ? ( ( $iFormatZero == self::Format_NoZeros ) ? 'g' : 'h' ) : ( ( $iFormatZero == self::Format_NoZeros ) ? 'G' : 'H' );
    $sFormatMinute = 'i';
    $sFormatSecond = 's';
    $sFormatAMPM = ( $iFormatHour==self::Format_AMPM ) ? ' A' : null;

    switch ($iFormatTime)
    {

    case self::Format_HHMM:
      $bIncludeSecond = false;
      $bIncludeMicro = false;
      break;

    case self::Format_HHMMSS:
      $bIncludeSecond = true;
      $bIncludeMicro = false;
      break;

    case self::Format_HHMMSSMicro:
    default:
      $bIncludeSecond = true;
      $bIncludeMicro = true;

    }

    switch ($iFormatSeparator)
    {

    case self::Format_SeparatorNone:
      $sFormatTime = $sFormatHour.$sFormatMinute.( $bIncludeSecond ? ( $sFormatSecond.( $bIncludeMicro ? ('.'.$sMicrotime) : null ) ) : null ).$sFormatAMPM;
      break;

    case self::Format_SeparatorHMS:
      $sFormatTime = $sFormatHour.'h'.$sFormatMinute.'m'.( $bIncludeSecond ? ( $sFormatSecond.( $bIncludeMicro ? ('.'.$sMicrotime) : null ).'s' ) : null ).$sFormatAMPM;
      break;

    case self::Format_SeparatorDot:
      $sFormatTime = $sFormatHour.'.'.$sFormatMinute.( $bIncludeSecond ? ( '.'.$sFormatSecond.( $bIncludeMicro ? ('.'.$sMicrotime) : null ) ) : null ).$sFormatAMPM;
      break;

    case self::Format_SeparatorCOLON:
    default:
      $sFormatTime = $sFormatHour.':'.$sFormatMinute.( $bIncludeSecond ? ( ':'.$sFormatSecond.( $bIncludeMicro ? ('.'.$sMicrotime) : null ) ) : null ).$sFormatAMPM;

    }
      
    // Return string
    return date( $sFormatTime, (integer)$mValue + PHP_APE_MKTIME_OFFSET );
  }


  /*
   * METHODS: magic - OVERRIDE
   ********************************************************************************/

  public function __toString()
  {
    return self::formatValue( $this->mValue, '#NULL#', self::Format_ISO, PHP_APE_Type_Any::Format_Passthru );
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
    if( is_null( $this->iFormat ) or $this->iFormat == PHP_APE_Type_Any::Format_Default )
      return PHP_APE_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.data.format.time' );
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
    if( $iFormat == PHP_APE_Type_Any::Format_Default ) $iFormat = PHP_APE_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.data.format.time' );
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
    return (float)86399.999;
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
