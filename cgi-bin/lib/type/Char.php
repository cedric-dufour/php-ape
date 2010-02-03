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

/** Single character type
 *
 * <P><B>NOTE:</B> PHP does NOT handle different types of <I>string</I> data, but other dataspaces DO.</P>
 *
 * @package PHP_APE_Type
 * @subpackage Classes
 */
class PHP_APE_Type_Char
extends PHP_APE_Type_String
{

  /*
   * METHODS: static
   ********************************************************************************/

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
    $mValue = parent::parseValue( $mValue, $bStrict );
    if( is_null( $mValue ) ) return null;
    return substr( $mValue, 0, 1 );
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

  public function setValueParsed( $mValue, $bStrict = true, $iFormat = null )
  {
    $this->mValue = self::parseValue( $mValue, $bStrict );
  }

  public function checkConstraints( $bResetOnFailure = false )
  {
    if( !$this->hasConstraints() ) return true;
    if( is_null( $this->mValue ) ) return false;
    if( !is_null( $this->mMinimumValue ) and ord( $this->mValue ) < ord( $this->mMinimumValue ) )
    {
      if( $bResetOnFailure ) $this->resetValue();
      return false;
    }
    if( !is_null( $this->mMaximumValue ) and ord( $this->mValue ) > ord( $this->mMinimumValue ) )
    {
      if( $bResetOnFailure ) $this->resetValue();
      return false;
    }
    return true;
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

}
