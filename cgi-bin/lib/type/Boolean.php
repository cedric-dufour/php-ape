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

/** Boolean type
 *
 * @package PHP_APE_Type
 * @subpackage Classes
 */
class PHP_APE_Type_Boolean
extends PHP_APE_Type_Scalar
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** <SAMP>true / false</SAMP> format
   * @var integer */
  const Format_Boolean = 0;

  /** <SAMP>1 / 0</SAMP> format
   * @var integer */
  const Format_Numeric = 1;

  /** Localized <SAMP>true / false</SAMP> format
   * @var integer */
  const Format_TrueFalse = 2;

  /** Localized <SAMP>yes / no</SAMP> format
   * @var integer */
  const Format_YesNo = 3;

  /** Localized <SAMP>on / off</SAMP> format
   * @var integer */
  const Format_OnOff = 4;


  /*
   * METHODS: static
   ********************************************************************************/

  /** Parses the given <I>mixed</I> data into their corresponding value
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param mixed $mValue Input value
   * @param boolean $bStrict Strict data parsing (<SAMP>null</SAMP> remains <SAMP>null</SAMP>)
   * @return boolean
   */
  public static function parseValue( $mValue, $bStrict = true )
  {
    $mValue = parent::parseValue( $mValue, $bStrict );
    if( is_null( $mValue ) ) return $bStrict ? null : (boolean)false;
    if( is_numeric( $mValue ) or is_bool( $mValue ) ) return (boolean)$mValue;
    switch( strtolower( trim( $mValue ) ) )
    {
    case 'y':
    case 'yes':
    case 't':
    case 'true':
    case 'on':
      return true;
    case 'n':
    case 'no':
    case 'f':
    case 'false':
    case 'off':
      return false;
    }
    $asResources = PHP_APE_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_Type_Boolean' );
    if( $mValue == $asResources[ 'text.true' ] or $mValue == $asResources[ 'text.yes' ] or $mValue == $asResources[ 'text.on' ] ) return true;
    if( $mValue == $asResources[ 'text.false' ] or $mValue == $asResources[ 'text.no' ] or $mValue == $asResources[ 'text.off' ] ) return false;
    throw new PHP_APE_Type_Exception( __METHOD__, 'Unconvertible data; Data: '.$mValue );

  }

  /** Format the given data
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param mixed $mValue Input value
   * @param string $sIfNull Output for <SAMP>null</SAMP> value
   * @param integer $iFormatOutput Ouptut data format [default: <SAMP>php_ape.data.format.boolean</SAMP> environment preference]
   * @param integer $iFormatInput Input data format [default: <SAMP>php_ape.data.format.boolean</SAMP> environment preference]
   * @return string
   */
  public static function formatValue( $mValue, $sIfNull = null, $iFormatOutput = null, $iFormatInput = null )
  {
    if( $iFormatInput != PHP_APE_Type_Any::Format_Passthru ) $mValue = self::parseValue( $mValue, true, $iFormatInput );
    if( is_null( $mValue ) ) return (string)$sIfNull;

    // Extract formatting tags
    if( is_null( $iFormatOutput ) or $iFormatOutput == PHP_APE_Type_Any::Format_Default ) $iFormatOutput = PHP_APE_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.data.format.boolean' );
    $iFormat = (integer)$iFormatOutput;

    // Construct data string
    $asResources = PHP_APE_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_Type_Boolean' );
    switch( $iFormat )
    {

    case self::Format_OnOff:
      return $mValue ? $asResources[ 'text.on' ] : $asResources[ 'text.off' ];

    case self::Format_YesNo:
      return $mValue ? $asResources[ 'text.yes' ] : $asResources[ 'text.no' ];

    case self::Format_TrueFalse:
      return $mValue ? $asResources[ 'text.true' ] : $asResources[ 'text.false' ];

    case self::Format_Numeric:
      return $mValue ? '1' : '0';

    case self::Format_Boolean:
    default:
      return $mValue ? 'true' : 'false';

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
    return self::formatValue( $this->mValue, '#NULL#', self::Format_Numeric, PHP_APE_Type_Any::Format_Passthru );
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
    if( is_null( $this->iFormat ) or (string)$this->iFormat == PHP_APE_Type_Any::Format_Default )
      return PHP_APE_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.data.format.boolean' );
    else return $this->iFormat;
  }


  /*
   * METHODS: parse/format - OVERRIDE
   ********************************************************************************/

  public function setValueParsed( $mValue, $bStrict = true, $iFormat = null )
  {
    $this->mValue = self::parseValue( $mValue, $bStrict );
  }

  public function getValueFormatted( $sIfNull = null, $iFormat = null )
  {
    if( is_null( $this->mValue ) ) return (string)$sIfNull;
    if( is_null( $iFormat ) ) $iFormat = $this->getFormat();
    if( $iFormat == PHP_APE_Type_Any::Format_Default ) $iFormat = PHP_APE_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.data.format.boolean' );
    return self::formatValue( $this->mValue, $sIfNull, $iFormat, PHP_APE_Type_Any::Format_Passthru );
  }


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
                                            array( new PHP_APE_Data_Constant( 'minimum', new PHP_APE_Type_Boolean( $this->mMinimumValue ) ),
                                                   new PHP_APE_Data_Constant( 'maximum', new PHP_APE_Type_Boolean( $this->mMaximumValue ) ) ) );
  }

  public function getConstraintsString( $iFormat = null )
  {
    if( !$this->hasConstraints() ) return null;
    $asResources = PHP_APE_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_Type_Resources' );
    $sString = $asResources[ 'php_ape_type_any.value' ];
    $oData = $this->cloneData();
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
    return (boolean)true;
  }

}
