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

/** Basic data set class
 *
 * @package PHP_APE_Data
 * @subpackage Classes
 */
abstract class PHP_APE_Data_BasicDataSet
extends PHP_APE_Data_Any
implements PHP_APE_Data_isDataSet
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Data holding array
   * @var array|PHP_APE_Data_Any */
  protected $aoData;


  /*
   * METHODS: magic - OVERRIDE
   ********************************************************************************/

  /** Returns a <I>string</I> representation of this object
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return string
   */
  public function __toString()
  {
    if( !is_array( $this->aoData ) ) return '#EMPTY#';
    return PHP_APE_Properties::convertArray2String( $this->aoData );
  }


  /*
   * METHODS: PHP_APE_Data_isDataSet - IMPLEMENT
   ********************************************************************************/

  /** Returns the data elements count from this set
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return integer
   */
  final public function countElements()
  {
    return count( $this->aoData );
  }

  /** Returns the data elements keys from this set
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return array|mixed
   */
  final public function getElementsKeys()
  {
    return array_keys( $this->aoData );
  }

  /** Returns whether the given key corresponds to a data element in this set
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param mixed $mKey Data element key
   * @return boolean
   */
  final public function isElement( $mKey )
  {
    if( !is_numeric( $mKey ) ) $mKey = PHP_APE_Type_Index::parseValue( $mKey );
    return array_key_exists( $mKey, $this->aoData );
  }

  /** Returns the data element corresponding to the given key from this set
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param mixed $mKey Data element key
   * @return PHP_APE_Data_Any
   */
  final public function getElement( $mKey )
  {
    if( !is_numeric( $mKey ) ) $mKey = PHP_APE_Type_Index::parseValue( $mKey );
    if( !array_key_exists( $mKey, $this->aoData ) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Unexisting data; Key: '.$mKey );
    return clone( $this->aoData[ $mKey ] );
  }

  /** Returns the data element corresponding to the given key from this set (<B>as reference</B>)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param mixed $mKey Data element key
   * @return PHP_APE_Data_Any
   */
  final public function &useElement( $mKey )
  {
    if( !is_numeric( $mKey ) ) $mKey = PHP_APE_Type_Index::parseValue( $mKey );
    if( !array_key_exists( $mKey, $this->aoData ) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Unexisting data; Key: '.$mKey );
    return $this->aoData[ $mKey ];
  }

  /** Returns the data elements identifiers (IDs) from this set (associating: <I>key</I> => <I>id</I>)
   *
   * @return array|mixed
   */
  public function getElementsIDs()
  {
    $amIDs = array();
    foreach( $this->aoData as $mKey => &$roData )
      $amIDs[ $mKey ] = $roData->getID();
    return $amIDs;
  }

  /** Returns whether the given identifier (ID) corresponds to a data element in this set
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param mixed $mID Data element identifier (ID)
   * @return boolean
   */
  final public function isElementID( $mID )
  {
    $mID = PHP_APE_Type_Index::parseValue( $mID );
    // Look for identifier (ID)
    foreach( $this->aoData as $oData )
      if( $oData->getID() == $mID )
        return true;
    return false;
  }

  /** Returns the data element corresponding to the given identifier (ID) from this set
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param mixed $mID Data element identifier (ID)
   * @return PHP_APE_Data_Any
   */
  final public function getElementByID( $mID )
  {
    $mID = PHP_APE_Type_Index::parseValue( $mID );
    // Look for identifier (ID)
    foreach( $this->aoData as $oData )
      if( $oData->getID() == $mID )
        return clone( $oData );
    throw new PHP_APE_Data_Exception( __METHOD__, 'Unexisting data; ID: '.$mID );
  }

  /** Returns the data element corresponding to the given identifier (ID) from this set (<B>as reference</B>)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param mixed $mID Data element identifier (ID)
   * @return PHP_APE_Data_Any
   */
  final public function &useElementByID( $mID )
  {
    $mID = PHP_APE_Type_Index::parseValue( $mID );
    // Look for identifier (ID)
    foreach( $this->aoData as &$roData )
      if( $roData->getID() == $mID )
        return $roData;
    throw new PHP_APE_Data_Exception( __METHOD__, 'Unexisting data; ID: '.$mID );
  }

}
