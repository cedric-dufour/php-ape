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

/** Timestamp type
 *
 * <P><B>NOTE:</B> Timestamp data are handled as <I>float</I>, in order to include subseconds information (UNIX timestamp + decimal subseconds).</P>
 *
 * @package PHP_APE_Type
 * @subpackage Classes
 */
class PHP_APE_Type_Timestamp
extends PHP_APE_Type_Numeric
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** Space separator
   * @var integer */
  const Format_SeparatorSpace = 0;

  /** <SAMP>T</SAMP> separator
   * @var integer */
  const Format_SeparatorISOT = 65536;

  /** No separator
   * @var integer */
  const Format_SeparatorNone = 131072;

  /** <SAMP>ISO</SAMP> format (sample: <I>2005-12-31 23:59:59.999</I>)
   * @var string */
  const Format_ISO = 0;

  /** <SAMP>ISO</SAMP> 'T'-separated format (sample: <I>2005-12-31T23:59:59.999</I>)
   * @var string */
  const Format_ISOT = 65536;

  /** <SAMP>European</SAMP> format (sample: <I>31.12.2005 23:59:59</I>)
   * @var string */
  const Format_European = 290;

  /** <SAMP>American</SAMP> format (sample: <I>12/31/2005 11:59:59 PM</I>)
   * @var string */
  const Format_American = 2369;


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

  /** Explodes the given data <I>string</I> into basic components
   *
   * <P><B>RETURNS:</B> an <I>array</I> containing:</P>
   * <UL>
   * <LI><SAMP>0</SAMP>: date part (<I>string</I>)</LI>
   * <LI><SAMP>1</SAMP>: time part (<I>string</I>)</LI>
   * <LI><SAMP>2</SAMP>: first date field (<I>integer</I>)</LI>
   * <LI><SAMP>3</SAMP>: second date field (<I>integer</I>)</LI>
   * <LI><SAMP>4</SAMP>: third date field (<I>integer</I>)</LI>
   * <LI><SAMP>5</SAMP>: hours (<I>integer</I>)</LI>
   * <LI><SAMP>6</SAMP>: minutes (<I>integer</I>)</LI>
   * <LI><SAMP>7</SAMP>: seconds (<I>integer</I>)</LI>
   * <LI><SAMP>8</SAMP>: subseconds (<I>float</I>)</LI>
   * <LI><SAMP>9</SAMP>: AM/PM or timezone (<I>string</I>)</LI>
   * </UL>
   * <P>Fields order is undefined and must be handled according to <I>à priori</I> information.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param string $sString Input string
   * @return array|mixed
   */
  public static function explodeString( $sString )
  {
    if( !preg_match( '/(^|[^-.\/0-9])(([0-9]{1,4})[-.\/]([0-9]{1,4})[-.\/]([0-9]{1,4})\D?)[  t]*((2[0-3]|[0-1]?[0-9])[-.:h]([0-5]?[0-9])m?([-.:m]([0-5]?[0-9])(\.[0-9]+)?s?)?\s*(am?|pm?|[-+]2[0-3]|[0-1]?[0-9]|[a-z]{3})?)([^-:\/0-9]|$)/i', $sString, $aMatch ) )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid/unparsable input; String: '.$sString );
    $amComponents = array();
    $amComponents[0] = $aMatch[2]; // date
    $amComponents[1] = $aMatch[6]; // time
    $amComponents[2] = (integer)$aMatch[3]; // date 1
    $amComponents[3] = (integer)$aMatch[4]; // date 2
    $amComponents[4] = (integer)$aMatch[5]; // date 3
    $amComponents[5] = (integer)$aMatch[7]; // hours
    $amComponents[6] = (integer)$aMatch[8]; // minutes
    $amComponents[7] = (integer)$aMatch[10]; // seconds
    $amComponents[8] = (float)$aMatch[11]; // subseconds
    $amComponents[9] = (string)$aMatch[12]; // AM/PM or timezone
    return $amComponents;
  }

  /** Parses the given data <I>string</I> into its corresponding value
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param string $sString Input string
   * @param integer $iFormat Data format [default: <SAMP>php_ape.data.format.date</SAMP>+<SAMP>php_ape.data.format.time</SAMP> environment preference]
   * @return float
   */
  public static function parseString( $sString, $iFormat = null )
  {
    if( is_null( $sString ) ) return null;
    try
    {
      $amComponents = self::explodeString( $sString );
      return PHP_APE_Type_Date::parseString( $amComponents[0], $iFormat ) + PHP_APE_Type_Time::parseString( $amComponents[1], $iFormat );
    }
    catch( PHP_APE_Type_Exception $e )
      { // ... maybe we have a date-only string...
        try
        {
          return PHP_APE_Type_Date::parseString( $sString, $iFormat );
        }
        catch( PHP_APE_Type_Exception $e2 )
          {
            throw $e;
          }
      }
    
  }

  /** Parses the given <I>mixed</I> data into their corresponding value
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param mixed $mValue Input value
   * @param boolean $bStrict Strict data parsing (<SAMP>null</SAMP> remains <SAMP>null</SAMP>)
   * @param integer $iFormat Data format [default: <SAMP>php_ape.data.format.date</SAMP>+<SAMP>php_ape.data.format.time</SAMP> environment preference]
   * @param boolean $bParseInternalValue Allow input value to be parsed as an already parsed (internal) value
   * @return float
   */
  public static function parseValue( $mValue, $bStrict = true, $iFormat = null, $bParseInternalValue = false )
  {
    if( ( $mValue instanceof PHP_APE_Type_Time ) )
      $mValue = PHP_APE_Type_Scalar::parseValue( $mValue, $bStrict ) + PHP_APE_MKTIME_OFFSET;
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
   * @param integer $iFormatOutput Ouptut data format [default: <SAMP>php_ape.data.format.date</SAMP>+<SAMP>php_ape.data.format.time</SAMP> environment preference]
   * @param integer $iFormatInput Input data format [default: <SAMP>php_ape.data.format.date</SAMP>+<SAMP>php_ape.data.format.time</SAMP> environment preference]
   * @return string
   */
  public static function formatValue( $mValue, $sIfNull = null, $iFormatOutput = null, $iFormatInput = null )
  {
    if( $iFormatInput != PHP_APE_Type_Any::Format_Passthru ) $mValue = self::parseValue( $mValue, true, $iFormatInput, true );
    if( is_null( $mValue ) ) return (string)$sIfNull;

    // Extract formatting tags
    if( is_null( $iFormatOutput ) or $iFormatOutput == PHP_APE_Type_Any::Format_Default ) $iFormatOutput = (integer)PHP_APE_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.data.format.date' ) | (integer)PHP_APE_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.data.format.time' );
    $iFormat = (integer)$iFormatOutput;
    $iFormatSeparator = (integer)( $iFormat & ( self::Format_SeparatorSpace | self::Format_SeparatorISOT | self::Format_SeparatorNone ) );

    // Construct data string
    $sDate = PHP_APE_Type_Date::formatValue( $mValue, $sIfNull, $iFormatOutput, PHP_APE_Type_Any::Format_Passthru );
    $sTime = PHP_APE_Type_Time::formatValue( $mValue - PHP_APE_MKTIME_OFFSET, $sIfNull, $iFormatOutput, PHP_APE_Type_Any::Format_Passthru );
    switch ($iFormatSeparator)
    {

    case self::Format_SeparatorNone:
      return $sDate.$sTime;


    case self::Format_SeparatorISOT:
      return $sDate.'T'.$sTime;

    case self::Format_SeparatorSpace:
    default:
      return $sDate.' '.$sTime;

    }
  }


  /*
   * METHODS: magic - OVERRIDE
   ********************************************************************************/

  /** Returns a <I>string</I> representation of this value
   *
   * @return string
   */
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
    if( is_null( $this->iFormat ) or (string)$this->iFormat == PHP_APE_Type_Any::Format_Default )
      return (integer)PHP_APE_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.data.format.date' ) | (integer)PHP_APE_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.data.format.time' );
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
    if( $iFormat == PHP_APE_Type_Any::Format_Default ) $iFormat = (integer)PHP_APE_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.data.format.date' ) | (integer)PHP_APE_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.data.format.time' );
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
    return (float)1136069999.999;
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
