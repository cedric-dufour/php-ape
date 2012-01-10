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
 * @package PHP_APE_Database
 * @subpackage Classes
 */

/** SQL field (column data) class
 *
 * @package PHP_APE_Database
 * @subpackage Classes
 */
class PHP_APE_Database_Field
extends PHP_APE_Data_Field
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Field expression (column name or SQL expression)
   * @var string */
  private $sExpression;

  /** Stored data
   * @var PHP_APE_Data_Scalar */
  private $oStoredData;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new field
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>.</P>
   *
   * @param mixed $mID Field identifier (ID)
   * @param string $sExpression Field querying expression (column name or SQL expression)
   * @param PHP_APE_Type_Scalar $oData Associated data object
   * @param string $iMeta Field meta code
   * @param string $sName Field name (defaults to identifier if <SAMP>empty</SAMP>)
   * @param string $sDescription Field description
   * @param PHP_APE_Type_Scalar $oStoredData Associated (template) stored data object
   */
  public function __construct( $mID, $sExpression, PHP_APE_Type_Scalar $oData, $iMeta = 0, $sName = null, $sDescription = null, PHP_APE_Type_Scalar $oStoredData = null )
  {
    // Sanitize arguments
    $sExpression = PHP_APE_Type_String::parseValue( $sExpression, true );
    if( empty( $sExpression ) )
      throw new PHP_APE_Database_Exception( __METHOD__, 'Invalid (empty) expression' );

    // Initialize member fields
    parent::__construct( $mID, $oData, $iMeta, $sName, $sDescription );
    $this->sExpression = $sExpression;
    if( !is_null( $oStoredData ) )
      $this->oStoredData = clone( $oStoredData );
  }


  /*
   * METHODS
   ********************************************************************************/

  /** Returns this field's expression (column name or SQL expression)
   *
   * @return string
   */
  public function getExpression()
  {
    return $this->sExpression;
  }

  /** Returns this field's (template) stored data content
   *
   * @return PHP_APE_Type_Scalar
   */
  public function getContentAsStored()
  {
    if( is_null( $this->oStoredData ) )
      return null;
    return clone( $this->oStoredData );
  }

}
