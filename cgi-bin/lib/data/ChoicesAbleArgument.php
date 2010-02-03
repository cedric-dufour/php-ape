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

/** Function/procedure choosable argument class
 *
 * @package PHP_APE_Data
 * @subpackage Classes
 */
class PHP_APE_Data_ChoicesAbleArgument
extends PHP_APE_Data_Argument
implements PHP_APE_Data_hasChoices
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** Value: multiple (allow multiple select)
   * @var integer */
  const Value_Multiple = 64;

  /*
   * FIELDS
   ********************************************************************************/

  /** Choices
   * @var array|mixed */
  private $amChoices;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new argument instance
   *
   * @param mixed $mID Variable identifier (ID)
   * @param PHP_APE_Type_Scalar $oData Associated data object
   * @param array|mixed $amChoices Argument choices
   * @param string $iMeta Argument meta code
   * @param string $sName Variable name (default to identifier if <SAMP>empty</SAMP>)
   * @param string $sDescription Variable description
   */
  public function __construct( $mID = null, PHP_APE_Type_Scalar $oData, $amChoices, $iMeta = 0, $sName = null, $sDescription = null )
  {
    // Sanitize arguments
    $amChoices = PHP_APE_Type_Array::parseValue( $amChoices );

    // Initialize member fields
    parent::__construct( $mID, $oData, $iMeta, $sName, $sDescription );
    $this->amChoices = $amChoices;
  }


  /*
   * METHODS: PHP_APE_Data_hasChoices - IMPLEMENT
   ********************************************************************************/

  public function getChoices()
  {
    return $this->amChoices;
  }


  /*
   * METHODS: additional
   ********************************************************************************/

  /** Sets the argument's choices
   *
   * @param array|mixed $amChoices Argument choices
   */
  public function setChoices( $amChoices )
  {
    $this->amChoices = PHP_APE_Type_Array::parseValue( $amChoices );
  }

}
