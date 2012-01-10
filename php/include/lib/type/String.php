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

/** String type
 *
 * @package PHP_APE_Type
 * @subpackage Classes
 */
class PHP_APE_Type_String
extends PHP_APE_Type_Scalar
{

  /*
   * CONSTRUCTORS - OVERRIDE
   ********************************************************************************/

  public function __construct( $mValue = null, $mMinimumValue = null, $mMaximumValue = null )
  {
    // Default constructor
    parent::__construct( $mValue );

    // Special handling of constraints values
    $this->mMinimumValue = null;
    $mMinimumValue = PHP_APE_Type_Scalar::parseValue( $mMinimumValue, true );
    if( !is_null( $mMinimumValue ) ) $this->mMinimumValue = $mMinimumValue;
    $this->mMaximumValue = null;
    $mMaximumValue = PHP_APE_Type_Scalar::parseValue( $mMaximumValue, true );
    if( !is_null( $mMaximumValue ) ) $this->mMaximumValue = $mMaximumValue;
  }


  /*
   * METHODS: static
   ********************************************************************************/

  /** Truncates the given <I>string</I> to the given length
   *
   * <P>Use this function to truncate any string which length is bigger than the specified length,
   * and add the specified truncation suffix accordingly.</P>
   *
   * @param string $sString Input string
   * @param integer $iLength Maximum allowed length
   * @param string $sSuffix Truncation suffix
   * @return string
   */
  public static function truncate( $sString, $iLength, $sSuffix = '...' )
  {
    if( strlen( $sString ) <= $iLength ) return $sString;
    return substr( $sString, 0, $iLength-strlen( $sSuffix ) ).$sSuffix;
  }

  /** Parses the given <I>mixed</I> data into their corresponding value
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param mixed $mValue Input value
   * @param boolean $bStrict Strict data parsing (<SAMP>null</SAMP> remains <SAMP>null</SAMP>)
   * @return string
   */
  public static function parseValue( $mValue, $bStrict = false )
  {
    $mValue = parent::parseValue( $mValue, false );
    if( is_null( $mValue ) ) return $bStrict ? null : (string)'';
    return (string)$mValue;
  }


  /*
   * METHODS: value - OVERRIDE
   ********************************************************************************/

  public function setValue( $mValue )
  {
    $this->mValue = self::parseValue( $mValue, true );
  }


  /*
   * METHODS: parse/format - OVERRIDE
   ********************************************************************************/

  public function setValueParsed( $mValue, $bStrict = false, $iFormat = null )
  {
    $this->mValue = self::parseValue( $mValue, $bStrict );
  }


  /*
   * METHODS: PHP_APE_Type_hasConstraints - IMPLEMENT
   ********************************************************************************/

  /** Check the object satisifies its constraints
   *
   * <P><B>RETURNS:</B> <SAMP>true</SAMP> if all constraints are satisfied, <SAMP>false</SAMP> otherwise.</P>
   *
   * @param boolean $bResetOnFailure Reset (clear) the object if it does not satifies its constraints
   * @return boolean
   */
  public function checkConstraints( $bResetOnFailure = false )
  {
    if( !$this->hasConstraints() ) return true;
    if( is_null( $this->mValue ) ) return false;
    if( !is_null( $this->mMinimumValue ) )
    {
      if( ( is_numeric( $this->mMinimumValue ) and strlen( $this->mValue ) < $this->mMinimumValue ) or
          ( !is_numeric( $this->mMinimumValue ) and strcasecmp( $this->mValue, $this->mMinimumValue ) < 0 ) )
      {
        if( $bResetOnFailure ) $this->resetValue();
        return false;
      }
    }
    if( !is_null( $this->mMaximumValue ) )
    {
      if( ( is_numeric( $this->mMaximumValue ) and strlen( $this->mValue ) > $this->mMaximumValue ) or
          ( !is_numeric( $this->mMaximumValue ) and strcasecmp( $this->mValue, $this->mMaximumValue ) > 0 ) )
      {
        if( $bResetOnFailure ) $this->resetValue();
        return false;
      }
    }
    return true;
  }

  public function getConstraints()
  {
    return new PHP_APE_Data_AssociativeSet( 'constraints',
                                            array( new PHP_APE_Data_Constant( 'minimum', is_numeric( $this->mMinimumValue ) ? new PHP_APE_Type_Float( $this->mMinimumValue ) : new PHP_APE_Type_String( $this->mMinimumValue ) ),
                                                   new PHP_APE_Data_Constant( 'maximum', is_numeric( $this->mMaximumValue ) ? new PHP_APE_Type_Float( $this->mMaximumValue ) : new PHP_APE_Type_String( $this->mMaximumValue ) ) ) );
  }

  public function getConstraintsString( $iFormat = null )
  {
    if( !$this->hasConstraints() ) return null;
    $asResources = PHP_APE_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_Type_Resources' );
    $sString = null;
    if( !is_null( $this->mMinimumValue ) ) 
    {
      if( is_numeric( $this->mMinimumValue ) ) $sString .= $asResources[ 'php_ape_type_string.length' ].' >= '.$this->mMinimumValue;
      else $sString .= $asResources[ 'php_ape_type_any.value' ].' >= \''.$this->mMinimumValue.'\'';
    }
    if( !is_null( $this->mMaximumValue ) ) 
    {
      if( $sString ) $sString .= '; ';
      if( is_numeric( $this->mMaximumValue ) ) $sString .= $asResources[ 'php_ape_type_string.length' ].' <= '.$this->mMaximumValue;
      else $sString .= $asResources[ 'php_ape_type_any.value' ].' <= \''.$this->mMaximumValue.'\'';
    }
    return $sString;
  }

  /*
   * METHODS: PHP_APE_Type_hasSample - IMPLEMENT
   ********************************************************************************/

  public function getSampleValue()
  {
    if( strlen( $this->mDefaultValue ) > 0 ) return $this->mDefaultValue;
    $asResources = PHP_APE_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_Type_Resources' );
    return $asResources[ strtolower( get_class( $this ) ).'.sample' ];
  }


  /*
   * METHODS: introspection - OVERRIDE
   ********************************************************************************/

  public function hasData()
  {
    return strlen( trim( $this->mValue ) ) > 0;
  }

}
