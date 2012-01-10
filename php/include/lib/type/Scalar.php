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

/** Scalar type
 *
 * @package PHP_APE_Type
 * @subpackage Classes
 */
abstract class PHP_APE_Type_Scalar
extends PHP_APE_Type_Any
implements PHP_APE_Type_hasDefault, PHP_APE_Type_hasSample, PHP_APE_Type_hasConstraints
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Value
   * @var mixed */
  protected $mValue;

  /** Default value
   * @var mixed */
  protected $mDefaultValue;

  /** Minimum value
   * @var mixed */
  protected $mMinimumValue;

  /** Maximum value
   * @var mixed */
  protected $mMaximumValue;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a scalar data object
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param mixed $mValue New scalar value (also used as default value)
   * @param mixed $mMinimumValue Minimum value constraint
   * @param mixed $mMaximumValue Maximum value constraint
   */
  public function __construct( $mValue = null, $mMinimumValue = null, $mMaximumValue = null )
  {
    // Initialize member fields
    $this->mDefaultValue = null;
    $this->mMinimumValue = null;
    $this->mMaximumValue = null;

    // First, we set the minimum value, so we can use the (maybe overridden) value-setting method
    $this->setValue( $mMinimumValue );
    if( !is_null( $this->mValue ) )
      $this->mMinimumValue = $this->mValue;

    // ... then the maximum value
    $this->setValue( $mMaximumValue );
    if( !is_null( $this->mValue ) )
      $this->mMaximumValue = $this->mValue;

    // ... then the actual value
    $this->setValue( $mValue );

    // ... then the default value
    if( !is_null( $this->mValue ) )
      $this->mDefaultValue = $this->mValue;
  }


  /*
   * METHODS: magic - OVERRIDE
   ********************************************************************************/

  /** Returns a <I>string</I> representation of this value
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return string
   */
  public function __toString()
  {
    if( is_null( $this->mValue ) ) return '#NULL#';
    return (string)$this->mValue;
  }


  /*
   * METHODS: static
   ********************************************************************************/

  /** Parses the given <I>mixed</I> data into their corresponding value
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param mixed $mValue Input value
   * @param boolean $bStrict Strict data parsing (<SAMP>null</SAMP> remains <SAMP>null</SAMP>)
   * @return scalar
   */
  public static function parseValue( $mValue, $bStrict = true )
  {
    if( is_null( $mValue ) ) return null;
    if( is_object( $mValue ) )
    {
      if( $mValue instanceof PHP_APE_Type_Scalar ) return self::parseValue( $mValue->getValue(), $bStrict );
      if( $mValue instanceof PHP_APE_Type_Array ) return self::parseValue( $mValue->getValueAt( 0, false ), $bStrict );
      throw new PHP_APE_Type_Exception( __METHOD__, 'Object cannot be converted to scalar; Class: '.get_class( $mValue ) );
    }
    if( is_array( $mValue ) )
    {
      if( array_key_exists( 0, $mValue ) ) return self::parseValue( $mValue[ 0 ], $bStrict );
      return null;
    }
    if( is_string( $mValue ) ) {
      if( strcasecmp( $mValue, '#NULL#' ) == 0 ) return null;
      if( strlen( $mValue ) <= 0 and $bStrict ) return null;
    }
    return $mValue;
  }


  /*
   * METHODS: value - OVERRIDE
   ********************************************************************************/

  /** Sets this object's data value
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param mixed $mValue New value
   */
  public function setValue( $mValue )
  {
    $this->mValue = self::parseValue( $mValue, true );
  }

  /** Returns this object's data value
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return mixed
   */
  final public function getValue()
  {
    return $this->mValue;
  }

  /** Resets this object's data value to its <I>blank</I> status
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function resetValue()
  {
    $this->mValue = null;
  }


  /*
   * METHODS: parse/format - OVERRIDE
   ********************************************************************************/

  public function getValueFormatted( $sIfNull = null, $iFormat = null )
  {
    if( is_null( $this->mValue ) ) return (string)$sIfNull;
    return (string)$this->mValue;
  }


  /*
   * METHODS: PHP_APE_Type_hasDefault - IMPLEMENT
   ********************************************************************************/

  public function hasDefault()
  {
    return !is_null( $this->mDefaultValue );
  }

  public function getDefaultValue()
  {
    return $this->mDefaultValue;
  }

  public function setToDefaultValue()
  {
    $this->setValue( $this->mDefaultValue );
  }


  /*
   * METHODS: PHP_APE_Type_hasSample - IMPLEMENT
   ********************************************************************************/

  public function hasSample()
  {
    return true;
  }

  public function getSampleString( $iFormat = null, $bShowOnlyType = false )
  {
    $asResources = PHP_APE_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_Type_Resources' );
    if( $bShowOnlyType )
      return $asResources[ strtolower( get_class( $this ) ) ];
    else
    {
      $oData = clone( $this );
      $oData->setValue( $this->getSampleValue() );
      $sSample = $oData->getValueFormatted( null, $iFormat );
      return $asResources[ strtolower( get_class( $this ) ) ].( strlen( $sSample ) > 0 ? ' (ex: '.$sSample.')' : null );
    }
  }


  /*
   * METHODS: PHP_APE_Type_hasConstraints - IMPLEMENT
   ********************************************************************************/

  public function hasConstraints()
  {
    return ( !is_null( $this->mMinimumValue ) || !is_null( $this->mMaximumValue ) );
  }


  /*
   * METHODS: introspection
   ********************************************************************************/

  /** Checks this object's data for <SAMP>null</SAMP> value
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return boolean
   * @see is_null()
   */
  final public function isNull()
  {
    return is_null( $this->mValue );
  }

  /** Checks this object's data for emptiness
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return boolean
   * @see empty()
   */
  final public function isEmpty()
  {
    return empty( $this->mValue );
  }

  /** Checks this object's data for meaningful content
   *
   * @return boolean
   */
  public function hasData()
  {
    return !is_null( $this->mValue );
  }

}
