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

/** Numeric type
 *
 * @package PHP_APE_Type
 * @subpackage Classes
 */
abstract class PHP_APE_Type_Numeric
extends PHP_APE_Type_Scalar
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** No thousands separator
   * @var integer */
  const Format_ThousandSeparatorNone = 0;

  /** <SAMP>'</SAMP> thousands separator
   * @var integer */
  const Format_ThousandSeparatorQuote = 32;

  /** <SAMP>.</SAMP> thousands separator
   * @var integer */
  const Format_ThousandSeparatorDot = 64;

  /** <SAMP>,</SAMP> thousands separator
   * @var integer */
  const Format_ThousandSeparatorComma = 96;

  /** <SAMP>.</SAMP> decimal separator
   * @var integer */
  const Format_DecimalSeparatorDot = 0;

  /** <SAMP>,</SAMP> decimal separator
   * @var integer */
  const Format_DecimalSeparatorComma = 1;

  /** No decimal separator
   * @var integer */
  const Format_DecimalSeparatorNone = 2;

  /** <SAMP>Raw</SAMP> format (sample: <I>123456.789</I>)
   * @var integer */
  const Format_Raw = 0;

  /** <SAMP>European</SAMP> format (sample: <I>123'456.789</I>)
   * @var integer */
  const Format_European = 32;

  /** <SAMP>American</SAMP> format (sample: <I>123.456,789</I>)
   * @var integer */
  const Format_American = 65;


  /*
   * METHODS: PHP_APE_Type_hasConstraints - IMPLEMENT
   ********************************************************************************/

  public function checkConstraints( $bResetOnFailure = false )
  {
    if( !$this->hasConstraints() ) return true;
    if( is_null( $this->mValue ) ) return false;
    if( !is_null( $this->mMinimumValue ) and $this->mValue < $this->mMinimumValue )
    {
      if( $bResetOnFailure ) $this->resetValue();
      return false;
    }
    if( !is_null( $this->mMaximumValue ) and $this->mValue > $this->mMaximumValue )
    {
      if( $bResetOnFailure ) $this->resetValue();
      return false;
    }
    return true;
  }

  public function getConstraints()
  {
    return new PHP_APE_Data_AssociativeSet( 'constraints',
                                            array( new PHP_APE_Data_Constant( 'minimum', new PHP_APE_Type_Float( $this->mMinimumValue ) ),
                                                   new PHP_APE_Data_Constant( 'maximum', new PHP_APE_Type_Float( $this->mMaximumValue ) ) ) );
  }

  public function getConstraintsString( $iFormat = null )
  {
    if( !$this->hasConstraints() ) return null;
    $asResources = PHP_APE_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_Type_Resources' );
    $sString = $asResources[ 'php_ape_type_any.value' ];
    $oData = clone( $this );
    if( !is_null( $this->mMinimumValue ) ) 
    {
      $oData->setValue( $this->mMinimumValue );
      $sString = $oData->getValueFormatted( null, $iFormat ).' <= '.$sString;
    }
    if( !is_null( $this->mMaximumValue ) ) 
    {
      $oData->setValue( $this->mMaximumValue );
      $sString = $sString.' <= '.$oData->getValueFormatted( null, $iFormat );
    }
    return $sString;
  }


  /*
   * METHODS: static
   ********************************************************************************/

  /** Explode the given data <I>string</I> into basic components
   *
   * <P><B>RETURNS:</B> an <I>array</I> containing:</P>
   * <UL>
   * <LI><SAMP>0</SAMP>: numeric value (<I>float</I>)</LI>
   * <LI><SAMP>1</SAMP>: integer part (<I>float</I>)</LI>
   * <LI><SAMP>2</SAMP>: decimal part (<I>float</I>)</LI>
   * </UL>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param string $sString Input string
   * @return array|mixed
   */
  public static function explodeString( $sString )
  {
    if( !preg_match( '/(^|[^\',.0-9])(-?(\d+))([.,](\d+))?([^\',.0-9]|$)/', $sString, $aMatch ) )
      if( !preg_match( '/(^|[^\',.0-9])(-?\d+([\',]\d{3})*)(\.(\d+))?([^\',.0-9]|$)/', $sString, $aMatch ) )
        if( !preg_match( '/(^|[^\',.0-9])(-?\d+([\'.]\d{3})*)(,(\d+))?([^\',.0-9]|$)/', $sString, $aMatch ) )
          throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid/unparsable input; String: '.$sString );
    $amComponents = array();
    $amComponents[0] = (float)preg_replace('/[\',.]/',null,$aMatch[2])+(float)( isset($aMatch[4]) ? $aMatch[4] : 0 ); // amount
    $amComponents[1] = (float)preg_replace('/[\',.]/',null,$aMatch[2]); // integer part
    $amComponents[2] = (float)( isset($aMatch[4]) ? $aMatch[4] : 0 ); // cents
    return $amComponents;
  }

  /** Parses the given data <I>string</I> into its corresponding value
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param string $sString Input string
   * @return float
   */
  public static function parseString( $sString )
  {
    if( is_null( $sString ) ) return null;
    return self::parseComponents( self::explodeString( $sString ) );
  }


  /** Parses the given data components <I>array</I> into their corresponding value
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param array|mixed $amComponents Input components <I>array</I>
   * @return float
   * @see explodeString()
   */
  public static function parseComponents( $amComponents )
  {
    // Check input
    if( !is_array( $amComponents ) or count( $amComponents ) < 1 )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid/unparsable components' );

    // Return string
    return (float)$amComponents[0];
  }

  /** Parses the given <I>mixed</I> data into their corresponding value
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param mixed $mValue Input value
   * @param boolean $bStrict Strict data parsing (<SAMP>null</SAMP> remains <SAMP>null</SAMP>)
   * @return float
   */
  public static function parseValue( $mValue, $bStrict = true )
  {
    $mValue = parent::parseValue( $mValue, $bStrict );
    if( empty( $mValue ) )
    {
      if( is_null( $mValue ) and $bStrict ) return null;
      return (float)0;
    }
    if( is_string( $mValue ) ) return self::parseString( $mValue );
    return (float)$mValue;
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
    $iFormatDecimalSeparator = (integer)( $iFormat & ( self::Format_DecimalSeparatorDot | self::Format_DecimalSeparatorComma | self::Format_DecimalSeparatorNone ) );

    // Construct format parameters
    $absValue = abs( $absValue );
    $iPrecision = strlen( trim( $absValue - (integer)$absValue, '0' ) - 1 );
    switch( $iFormatThousandSeparator )
    {
    case self::Format_ThousandSeparatorQuote: $sFormatThousandSeparator = '\''; break;
    case self::Format_ThousandSeparatorDot: $sFormatThousandSeparator = '.'; break;
    case self::Format_ThousandSeparatorComma: $sFormatThousandSeparator = ','; break;
    default: $sFormatThousandSeparator = '';
    }
    switch( $iFormatDecimalSeparator )
    {
    case self::Format_DecimalSeparatorNone: $sFormatDecimalSeparator = ''; $iPrecision = 0; break;
    case self::Format_DecimalSeparatorComma: $sFormatDecimalSeparator = ','; break;
    default: $sFormatDecimalSeparator = '.';
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


  /*
   * METHODS: PHP_APE_Type_hasSample - IMPLEMENT
   ********************************************************************************/

  public function getSampleValue()
  {
    if( !is_null( $this->mDefaultValue ) ) return $this->mDefaultValue;
    return (float)123456.789;
  }

}
