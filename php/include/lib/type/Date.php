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

/** Date type
 *
 * <P><B>NOTE:</B> Date data are handled as <I>integer</I> (UNIX timestamp).</P>
 * <P><B>NOTE:</B> Date before January 1st, 1970 (until January st, 1902) are accepted on Linux; Windows behavior is untested.</P>
 *
 * @package PHP_APE_Type
 * @subpackage Classes
 */
class PHP_APE_Type_Date
extends PHP_APE_Type_Numeric
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** <SAMP>year</SAMP>-<SAMP>month</SAMP>-<SAMP>day</SAMP> format
   * @var integer */
  const Format_YYMMDD = 0;

  /** <SAMP>month</SAMP>-<SAMP>day</SAMP>-<SAMP>year</SAMP> format
   * @var integer */
  const Format_MMDDYY = 1;

  /** <SAMP>day</SAMP>-<SAMP>month</SAMP>-<SAMP>year</SAMP> format
   * @var integer */
  const Format_DDMMYY = 2;

  /** 4-digits <SAMP>year</SAMP> format
   * @var integer */
  const Format_YYYY = 0;

  /** 2-digits <SAMP>year</SAMP> format
   * @var integer */
  const Format_YY = 4;

  /** <SAMP>-</SAMP> separator
   * @var integer */
  const Format_SeparatorDash = 0;

  /** <SAMP>.</SAMP> separator
   * @var integer */
  const Format_SeparatorDot = 32;

  /** <SAMP>/</SAMP> separator
   * @var integer */
  const Format_SeparatorSlash = 64;

  /** No separator
   * @var integer */
  const Format_SeparatorNone = 96;

  /** Leading zeros
   * @var integer */
  const Format_LeadingZeros = 0;

  /** No leading zeros
   * @var integer */
  const Format_NoZeros = 128;

  /** <SAMP>ISO</SAMP> format (sample: <I>2005-12-31</I>)
   * @var integer */
  const Format_ISO = 0;

  /** <SAMP>European</SAMP> format (sample: <I>31.12.2005</I>)
   * @var integer */
  const Format_European = 34;

  /** <SAMP>American</SAMP> format (sample: <I>12/31/2005</I>)
   * @var integer */
  const Format_American = 65;


  /*
   * METHODS: static
   ********************************************************************************/

  /** Returns the current date (UNIX timestamp)
   *
   * @return integer
   * @see time()
   */
  public static function now()
  {
    return self::parseValue( time() );
  }

  /** Explodes the given data <I>string</I> into basic components
   *
   * <P><B>RETURNS:</B> an <I>array</I> containing:</P>
   * <UL>
   * <LI><SAMP>0</SAMP>: first date field (<I>integer</I>)</LI>
   * <LI><SAMP>1</SAMP>: second date field (<I>integer</I>)</LI>
   * <LI><SAMP>2</SAMP>: third date field (<I>integer</I>)</LI>
   * </UL>
   * <P>Fields order is undefined and must be handled according to <I>a priori</I> information.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param string $sString Input string
   * @return array|integer
   */
  public static function explodeString( $sString )
  {
    if( !preg_match( '/(^|[^-.:\/0-9])([0-9]{1,4})([-.\/]([0-9]{1,4})([-.\/]([0-9]{1,4}))?)?([^-:\/0-9]|$)/', $sString, $aMatch ) )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid/unparsable input; String: '.$sString );
    $aiComponents = array();
    $aiComponents[0] = (integer)$aMatch[2]; // date 1
    $aiComponents[1] = (integer)$aMatch[4]; // date 2
    $aiComponents[2] = (integer)$aMatch[6]; // date 3
    return $aiComponents;
  }

  /** Parses the given data <I>string</I> into its corresponding value
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param string $sString Input string
   * @param integer $iFormat Data format [default: <SAMP>php_ape.data.format.date</SAMP> environment preference]
   * @return integer
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
   * @param array|integer $aiComponents Input components <I>array</I>
   * @param integer $iFormat Data format [default: <SAMP>php_ape.data.format.date</SAMP> environment preference]
   * @return integer
   * @see explodeString()
   */
  public static function parseComponents( $aiComponents, $iFormat = null )
  {
    // Check input
    if( !is_array( $aiComponents ) or count( $aiComponents ) < 3 )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid/unparsable components' );

    // Extract formatting tags
    if( is_null( $iFormat ) or $iFormat == PHP_APE_Type_Any::Format_Default ) $iFormat = PHP_APE_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.data.format.date' );
    $iFormat = (integer)$iFormat;
    $iFormatDate = (integer)( $iFormat & ( self::Format_YYMMDD | self::Format_MMDDYY | self::Format_DDMMYY ) );
    $iFormatYear = (integer)( $iFormat & ( self::Format_YYYY | self::Format_YY ) );

    // ... but let's try to be a little more user friendly
    if( $aiComponents[2] > 31 ) // European or American date
    {
      if( $aiComponents[1] > 12 ) // American date
        $iFormatDate = self::Format_MMDDYY;
      elseif( $iFormatDate != self::Format_MMDDYY ) // European date
        $iFormatDate = self::Format_DDMMYY;
      if( $aiComponents[2] < 100 ) $iFormatYear = self::Format_YY;
      else $iFormatYear = self::Format_YYYY;
    }
    elseif( $aiComponents[1] > 31 ) // Partial (day-less) European or American date -> transform to ISO
    {
      $iMonth = $aiComponents[0];
      $aiComponents[0] = $aiComponents[1]; 
      $aiComponents[1] = $iMonth; 
      $aiComponents[2] = 1; 
      $iFormatDate = self::Format_YYMMDD;
      if( $aiComponents[0] < 100 ) $iFormatYear = self::Format_YY;
      else $iFormatYear = self::Format_YYYY;
    }
    elseif( $aiComponents[0] > 31 ) // ISO date
    {
      $iFormatDate = self::Format_YYMMDD;
      if( $aiComponents[0] < 100 ) $iFormatYear = self::Format_YY;
      else $iFormatYear = self::Format_YYYY;
    }
    if( $aiComponents[2] < 1 ) $aiComponents[2] = 1;
    if( $aiComponents[1] < 1 ) $aiComponents[1] = 1;

    // Extract date
    switch( $iFormatDate )
    {

    case self::Format_MMDDYY:
      $iYear = (integer)$aiComponents[2];
      $iMonth = (integer)$aiComponents[0];
      $iDay = (integer)$aiComponents[1];
      break;

    case self::Format_DDMMYY:
      $iYear = (integer)$aiComponents[2];
      $iMonth = (integer)$aiComponents[1];
      $iDay = (integer)$aiComponents[0];
      break;

    case self::Format_YYMMDD:
    default:
      $iYear = (integer)$aiComponents[0];
      $iMonth = (integer)$aiComponents[1];
      $iDay = (integer)$aiComponents[2];
      break;

    }

    if( $iFormatYear==self::Format_YY ) $iYear += ($iYear<70) ? 2000 : 1900;

    // Check date
    if( !checkdate( $iMonth, $iDay, $iYear ) )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid/unparsable value; Components: '.serialize( $aiComponents ) );

    // Return UNIX timestamp
    return (integer)mktime( 0, 0, 0, $iMonth, $iDay, $iYear );
  }

  /** Parses the given <I>mixed</I> data into their corresponding value
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param mixed $mValue Input value
   * @param boolean $bStrict Strict data parsing (<SAMP>null</SAMP> remains <SAMP>null</SAMP>)
   * @param integer $iFormat Data format [default: <SAMP>php_ape.data.format.date</SAMP> environment preference]
   * @param boolean $bParseInternalValue Allow input value to be parsed as an already parsed (internal) value
   * @return integer
   */
  public static function parseValue( $mValue, $bStrict = true, $iFormat = null, $bParseInternalValue = false )
  {
    if( ( $mValue instanceof PHP_APE_Type_Time ) )
    {
      $mValue = PHP_APE_Type_Scalar::parseValue( $mValue, $bStrict );
      if( !is_null( $mValue ) ) $mValue += PHP_APE_MKTIME_OFFSET;
    }
    else
      $mValue = PHP_APE_Type_Scalar::parseValue( $mValue, $bStrict );
    if( is_null( $mValue ) ) return $bStrict ? null : (integer)0;
    if( $bParseInternalValue and ( !is_string( $mValue ) or is_numeric( $mValue ) ) )
    {
      $mValue = (integer)$mValue;
      $aiDate_parts = getdate( $mValue );
      return (integer)mktime( 0, 0, 0, $aiDate_parts['mon'], $aiDate_parts['mday'], $aiDate_parts['year'] );
    }
    return self::parseString( $mValue, $iFormat );
  }

  /** Format the given data
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param mixed $mValue Input value
   * @param string $sIfNull Output for <SAMP>null</SAMP> value
   * @param integer $iFormatOutput Ouptut data format [default: <SAMP>php_ape.data.format.date</SAMP> environment preference]
   * @param integer $iFormatInput Input data format [default: <SAMP>php_ape.data.format.date</SAMP> environment preference]
   * @return string
   */
  public static function formatValue( $mValue, $sIfNull = null, $iFormatOutput = null, $iFormatInput = null )
  {
    if( $iFormatInput != PHP_APE_Type_Any::Format_Passthru ) $mValue = self::parseValue( $mValue, true, $iFormatInput, true );
    if( is_null( $mValue ) ) return (string)$sIfNull;

    // Extract formatting tags
    if( is_null( $iFormatOutput ) or $iFormatOutput == PHP_APE_Type_Any::Format_Default ) $iFormatOutput = PHP_APE_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.data.format.time' );
    $iFormat = (integer)$iFormatOutput;
    $iFormatDate = (integer)( $iFormat & ( self::Format_YYMMDD |self::Format_MMDDYY | self::Format_DDMMYY ) );
    $iFormatYear = (integer)( $iFormat & ( self::Format_YYYY | self::Format_YY ) );
    $iFormatSeparator = (integer)( $iFormat & ( self::Format_SeparatorDash | self::Format_SeparatorDot | self::Format_SeparatorSlash | self::Format_SeparatorNone ) );
    $iFormatZero = (integer)( $iFormat & ( self::Format_LeadingZeros | self::Format_NoZeros ) );
      
    // Construct format string
    $sFormatYear = ( $iFormatYear == self::Format_YY ) ? 'y' : 'Y';
    $sFormatMonth = ( $iFormatZero == self::Format_NoZeros ) ? 'n' : 'm';
    $sFormatDay = ( $iFormatZero == self::Format_NoZeros ) ? 'j' : 'd';

    switch ($iFormatSeparator)
    {

    case self::Format_SeparatorNone:
      $sFormatSeparator = '';
      break;

    case self::Format_SeparatorSlash:
      $sFormatSeparator = '/';
      break;

    case self::Format_SeparatorDot:
      $sFormatSeparator = '.';
      break;

    case self::Format_SeparatorDash:
    default:
      $sFormatSeparator = '-';
    }

    switch ($iFormatDate)
    {

    case self::Format_DDMMYY:
      $sFormatDate = $sFormatDay.$sFormatSeparator.$sFormatMonth.$sFormatSeparator.$sFormatYear;
      break;

    case self::Format_MMDDYY:
      $sFormatDate = $sFormatMonth.$sFormatSeparator.$sFormatDay.$sFormatSeparator.$sFormatYear;
      break;

    case self::Format_YYMMDD:
    default:
      $sFormatDate = $sFormatYear.$sFormatSeparator.$sFormatMonth.$sFormatSeparator.$sFormatDay;

    }
      
    // Return string
    return date( $sFormatDate, $mValue );
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
      return PHP_APE_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.data.format.date' );
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
    return (integer)1136069999;
  }


  /*
   * METHODS: introspection - OVERRIDE
   ********************************************************************************/

  public function hasData()
  {
    return !empty( $this->mValue );
  }

}
