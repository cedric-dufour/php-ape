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

/** Viewable field class
 *
 * @package PHP_APE_Data
 * @subpackage Classes
 */
class PHP_APE_Data_Field
extends PHP_APE_Data_Variable
implements PHP_APE_Data_hasMeta
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** Type: data
   * @var integer */
  const Type_Data = 0;

  /** Type: id
   * @var integer */
  const Type_Identifier = 1;

  /** Type: key
   * @var integer */
  const Type_Key = 2;

  /** Type: foreign key
   * @var integer */
  const Type_ForeignKey = 2;

  /** Type: primary key
   * @var integer */
  const Type_PrimaryKey = 4;


  /** Feature: filter-able
   * @var integer */
  const Feature_FilterAble = 256;

  /** Feature: search-able
   * @var integer */
  const Feature_SearchAble = 768;

  /** Feature: order-able
   * @var integer */
  const Feature_OrderAble = 1024;


  /** Feature: hide in list view
   * @var integer */
  const Feature_HideInList = 4096;

  /** Feature: hide in detail view
   * @var integer */
  const Feature_HideInDetail = 8192;

  /** Feature: hide in form view
   * @var integer */
  const Feature_HideInForm = 16384;

  /** Feature: hide if empty
   * @var integer */
  const Feature_HideIfEmpty = 32768;

  /** Feature: always hide
   * @var integer */
  const Feature_HideAlways = 61440;


  /** Feature: collapse in list view
   * @var integer */
  const Feature_CollapseInList = 65536;

  /** Feature: collapse in detail view
   * @var integer */
  const Feature_CollapseInDetail = 131072;

  /** Feature: collapse in form view
   * @var integer */
  const Feature_CollapseInForm = 262144;

  // UNUSED: 524288

  /** Feature: always collapse
   * @var integer */
  const Feature_CollapseAlways = 983040;


  /** Feature: show in list view
   * @var integer */
  const Feature_ShowInList = 1048576;

  /** Feature: show in detail view
   * @var integer */
  const Feature_ShowInDetail = 2097152;

  /** Feature: show in form view
   * @var integer */
  const Feature_ShowInForm = 4194304;

  /** Feature: hide if empty
   * @var integer */
  const Feature_ShowIfEmpty = 8388608;

  /** Feature: always show
   * @var integer */
  const Feature_ShowAlways = 15728640;


  /** Feature: require in list view
   * @var integer */
  const Feature_RequireInList = 16777216;

  /** Feature: require in detail view
   * @var integer */
  const Feature_RequireInDetail = 33554432;

  /** Feature: require in form view
   * @var integer */
  const Feature_RequireInForm = 67108864;

  // UNUSED: 134217728

  /** Feature: always require
   * @var integer */
  const Feature_RequireAlways = 251658240;


  /*
   * FIELDS
   ********************************************************************************/

  /** Meta code
   * @var integer */
  private $iMeta;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new field instance
   *
   * @param mixed $mID Variable identifier (ID)
   * @param PHP_APE_Type_Scalar $oData Associated data object
   * @param string $iMeta Field meta code
   * @param string $sName Variable name (default to identifier if <SAMP>empty</SAMP>)
   * @param string $sDescription Variable description
   */
  public function __construct( $mID = null, PHP_APE_Type_Scalar $oData, $iMeta = 0, $sName = null, $sDescription = null )
  {
    // Sanitize arguments
    $iMeta = (integer)$iMeta;

    // Initialize member fields
    parent::__construct( $mID, $oData, $sName, $sDescription );
    $this->iMeta = $iMeta;
  }


  /*
   * METHODS: PHP_APE_Data_hasMeta - IMPLEMENT
   ********************************************************************************/

  public function getMeta()
  {
    return $this->iMeta;
  }

  public function hasMeta( $iMetaRequest, $bMatchAll = true )
  {
    $iMetaRequest = (integer)$iMetaRequest;
    if( $bMatchAll ) return ( ( $this->iMeta & $iMetaRequest ) == $iMetaRequest );
    else return ( ( $this->iMeta & $iMetaRequest ) != 0 );
  }


  /*
   * METHODS: additional
   ********************************************************************************/

  public function addMeta( $iMeta )
  {
    $this->iMeta |= (integer)$iMeta;
  }

  public function removeMeta( $iMeta )
  {
    $this->iMeta &= ~(integer)$iMeta;
  }

}
