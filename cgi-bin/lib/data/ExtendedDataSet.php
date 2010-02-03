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

/** Extended data set class
 *
 * @package PHP_APE_Data
 * @subpackage Classes
 */
abstract class PHP_APE_Data_ExtendedDataSet
extends PHP_APE_Data_BasicDataSet
{


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new data set instance
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   *
   * @param mixed $mID Constant identifier (ID)
   * @param array|PHP_APE_Data_Any $mData Initial data elements
   * @param string $sName Constant name (default to identifier if <SAMP>empty</SAMP>)
   * @param string $sDescription Constant description
   */
  protected function __construct( $mID = null, $aoData = null, $sName = null, $sDescription = null )
  {
    // Initialize member fields
    parent::__construct( $mID, $sName, $sDescription );
    $this->aoData = array();
    if( !is_null( $aoData ) )
    {
      if( !is_array( $aoData ) ) $aoData = array( $aoData );
      foreach( $aoData as $oData ) $this->setElement( $oData );
    }
  }

  /** Clones this data set
   *
   * <P><B>NOTE:</B> Data are copied down to the last bit !</P>
   *
   * @see PHP_MANUAL#serialize(), PHP_MANUAL#unserialize()
   */
  public function __clone()
  {
    $this->aoData = unserialize( serialize( $this->aoData ) );
  }


  /*
   * METHODS: elements
   ********************************************************************************/

  /** Returns the <I>array</I> corresponding to the data elements in this set
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return array|mixed
   */
  final public function getElementsArray()
  {
    return $this->aoData;
  }

  /** Sets (adds) the given data element to this set
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * @param PHP_APE_Data_Any $oData New (or replacement) data
   */
  abstract public function setElement( PHP_APE_Data_Any $oData );

  /** Unsets (clears) the data element corresponding to the given key from this set
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param mixed $mKey Data element key
   * @param boolean $bCheckExistency Check data existency
   */
  final public function unsetElement( $mKey, $bCheckExistency = false )
  {
    if( !is_numeric( $mKey ) ) $mKey = PHP_APE_Type_Index::parseValue( $mKey );
    if( $bCheckExistency and !array_key_exists( $mKey, $this->aoData ) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Unexisting data; Key: '.$mKey );
    unset( $this->aoData[ $mKey ] );
  }

  /** Unsets (clears) the data element corresponding to the given key from this set
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param mixed $mID Data element identifier (ID)
   * @param boolean $bCheckExistency Check data existency
   */
  final public function unsetElementByID( $mID, $bCheckExistency = false )
  {
    $mID = PHP_APE_Type_Index::parseValue( $mID );
    // Look for identifier (ID)
    foreach( $this->aoData as $mKey => &$roData )
      if( $roData->getID() == $mID )
        return $this->unsetElement( $mKey, $bCheckExistency );
    if( $bCheckExistency )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Unexisting data; ID: '.$mID );
  }

  /** Unsets (clears) all data elements from this set
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   */
  final public function unsetAllElements()
  {
    $this->aoData = array();
  }


  /*
   * METHODS: instrospection
   ********************************************************************************/

  /** Returns wether this data set is equal to the given data set
   *
   * <P><B>NOTE:</B> Data are compared down to the last bit !</P>
   *
   * @see PHP_MANUAL#serialize()
   */
  final public function isEqual( PHP_APE_Data_DataSet $oSet )
  {
    return ( strcmp( serialize( $this ), serialize( $oSet ) ) == 0 );
  }
}
