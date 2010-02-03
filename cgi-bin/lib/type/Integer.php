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

/** Integer type
 *
 * <P><B>NOTE:</B> PHP natively handles <B>4-bytes</B> <I>integer</I>.</P>
 *
 * @package PHP_APE_Type
 * @subpackage Classes
 */
class PHP_APE_Type_Integer
extends PHP_APE_Type_Numeric
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** Minimum value
   * @var integer */
  const Value_MIN = -2147483647;

  /** Maximum value
   * @var integer */
  const Value_MAX = 2147483647;


  /*
   * CONSTRUCTORS - OVERRIDE
   ********************************************************************************/

  public function __construct( $mValue = null, $mMinimumValue = self::Value_MIN, $mMaximumValue = self::Value_MAX )
  {
    parent::__construct( $mValue, $mMinimumValue, $mMaximumValue );
  }


  /*
   * METHODS: static
   ********************************************************************************/

  /** Parses the given data <I>string</I> into its corresponding value
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param string $sString Input string
   * @return integer
   */
  public static function parseString( $sString )
  {
    if( is_null( $sString ) ) return null;
    if( !preg_match( '/(^|[^\',.0-9])(-?\d+([\'.,]\d{3})*)([^\',.0-9]|$)/', $sString, $aMatch ) )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid/unparsable input; String: '.$sString );
    return (integer)preg_replace('/[\',.]/',null,$aMatch[2]);
  }

  /** Parses the given <I>mixed</I> data into their corresponding value
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param mixed $mValue Input value
   * @param boolean $bStrict Strict data parsing (<SAMP>null</SAMP> remains <SAMP>null</SAMP>)
   * @return integer
   */
  public static function parseValue( $mValue, $bStrict = true )
  {
    $mValue = PHP_APE_Type_Scalar::parseValue( $mValue, $bStrict );
    if( empty( $mValue ) )
    {
      if( is_null( $mValue ) and $bStrict ) return null;
      return (integer)0;
    }
    if( is_string( $mValue ) ) return self::parseString( $mValue );
    return (integer)$mValue;
  }

  /** Format the given data
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param mixed $mValue Input value
   * @param string $sIfNull Output for <SAMP>null</SAMP> value
   * @param integer $iFormatOutput Ouptut data format [default: <SAMP>php_ape.data.format.numeric</SAMP> environment preference]
   * @param integer $iFormatInput Input data format [default: <SAMP>php_ape.data.format.numeric</SAMP> environment preference]
   * @return string
   */
  public static function formatValue( $mValue, $sIfNull = null, $iFormatOutput = null, $iFormatInput = null )
  {
    if( $iFormatInput != PHP_APE_Type_Any::Format_Passthru ) $mValue = self::parseValue( $mValue, true );
    if( is_null( $mValue ) ) return (string)$sIfNull;

    // Extract formatting tags
    if( is_null( $iFormatOutput ) or $iFormatOutput == PHP_APE_Type_Any::Format_Default ) $iFormatOutput = PHP_APE_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.data.format.numeric' );
    $iFormat = (integer)$iFormatOutput;
    $iFormatThousandSeparator = (integer)( $iFormat & ( self::Format_ThousandSeparatorNone | self::Format_ThousandSeparatorQuote | self::Format_ThousandSeparatorDot | self::Format_ThousandSeparatorComma ) );

    // Construct format parameters
    $absValue = abs( $absValue );
    $iPrecision = 0;
    $sFormatDecimalSeparator = '';
    switch( $iFormatThousandSeparator )
    {
    case self::Format_ThousandSeparatorQuote: $sFormatThousandSeparator = '\''; break;
    case self::Format_ThousandSeparatorDot: $sFormatThousandSeparator = '.'; break;
    case self::Format_ThousandSeparatorComma: $sFormatThousandSeparator = ','; break;
    default: $sFormatThousandSeparator = '';
    }

    // Output
    return number_format( $mValue, $iPrecision, $sFormatDecimalSeparator, $sFormatThousandSeparator );
  }


  /*
   * METHODS: magic - OVERRIDE
   ********************************************************************************/

  public function __toString()
  {
    return self::formatValue( $this->mValue, '#NULL#', self::Format_Raw, PHP_APE_Type_Any::Format_Passthru );
  }


  /*
   * METHODS: value - OVERRIDE
   ********************************************************************************/

  public function setValue( $mValue )
  {
    $this->mValue = self::parseValue( $mValue, true );
  }


  /*
   * METHODS: format - OVERRIDE
   ********************************************************************************/

  public function getFormat()
  {
    if( is_null( $this->iFormat ) or $this->iFormat == PHP_APE_Type_Any::Format_Default )
      return PHP_APE_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.data.format.numeric' );
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
    if( $iFormat == PHP_APE_Type_Any::Format_Default ) $iFormat = PHP_APE_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.data.format.numeric' );
    return self::formatValue( $this->mValue, $sIfNull, $iFormat, PHP_APE_Type_Any::Format_Passthru );
  }

}
