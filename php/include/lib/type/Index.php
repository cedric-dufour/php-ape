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

/** Index type
 *
 * <P><B>NOTE:</B> PHP does NOT handle different types of <I>string</I> data, but other dataspaces DO.</P>
 *
 * @package PHP_APE_Type
 * @subpackage Classes
 */
class PHP_APE_Type_Index
extends PHP_APE_Type_String
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** Maximum <I>safe</I> index length
   * @var integer */
  const SAFE_INDEX_LENGTH = 256;


  /*
   * METHODS: static
   ********************************************************************************/

  /** Sanitizes data
   *
   * <P><B>SYNOPSIS:</B> This functions checks the supplied data and returns <I>safe</I> data.
   * Data length shall NOT exceed the <I>SAFE_INDEX_LENGTH</I>.</P> 
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param string $sString Input string
   * @return string
   * @uses SAFE_INDEX_LENGTH
   */
  public static function sanitize( $sString = null )
  {
    if( strlen( $sString ) > self::SAFE_INDEX_LENGTH )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Index exceeds allowed length; Index: '.$sString );
    return $sString;
  }

  /** Parses the given <I>mixed</I> data into their corresponding value
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param mixed $mValue Input value
   * @param boolean $bStrict Strict data parsing (<SAMP>null</SAMP> remains <SAMP>null</SAMP>)
   * @return string
   */
  public static function parseValue( $mValue, $bStrict = true )
  {
    return self::sanitize( parent::parseValue( $mValue, $bStrict ) );
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
