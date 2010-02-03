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

/** (Signed) 2-bytes integer type
 *
 * <P><B>NOTE:</B> PHP does NOT handle different types of <I>integer</I> data, but other dataspaces DO.</P>
 *
 * @package PHP_APE_Type
 * @subpackage Classes
 */
class PHP_APE_Type_Int2
extends PHP_APE_Type_Integer
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** Minimum value
   * @var integer */
  const Value_MIN = -32767;

  /** Maximum value
   * @var integer */
  const Value_MAX = 32767;


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
    $mValue = parent::parseValue( $mValue, $bStrict );
    if( is_null( $mValue ) ) return null;
    if( $mValue < self::Value_MIN )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid/out-of-range value; Value: '.$mValue );
    if( $mValue > self::Value_MAX )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid/out-of-range value; Value: '.$mValue );
    return $mValue;
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

}
