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
 * @package PHP_APE_Data
 * @subpackage Classes
 */

/** Constant data class
 *
 * @package PHP_APE_Data
 * @subpackage Classes
 */
class PHP_APE_Data_Constant
extends PHP_APE_Data_Any
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Data
   * @var PHP_APE_Data_Any */
  protected $oData;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new constant instance
   *
   * @param mixed $mID Constant identifier (ID)
   * @param PHP_APE_Type_Any $oData Associated data object
   * @param string $sName Constant name (default to identifier if <SAMP>empty</SAMP>)
   * @param string $sDescription Constant description
   */
  public function __construct( $mID, PHP_APE_Type_Any $oData, $sName = null, $sDescription = null )
  {
    // Initialize member fields
    parent::__construct( $mID, $sName, $sDescription );
    $this->oData = clone( $oData ); // constant must NOT be modifiable through (object) reference
  }


  /*
   * METHODS: magic - OVERRIDE
   ********************************************************************************/

  /** Clones this object */
  public function __clone()
  {
    $this->oData = clone( $this->oData );
  }

  /** Returns a <I>string</I> representation of this object
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return string
   */
  public function __toString()
  {
    if( is_null( $this->oData ) ) return '#NULL#';
    return (string)$this->oData;
  }


  /*
   * METHODS: data
   ********************************************************************************/

  /** Returns this constant's data content
   *
   * @return PHP_APE_Type_Any
   */
  public function getContent()
  {
    return clone( $this->oData ); // constant must NOT be modifiable through (object) reference
  }

}
