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

/** Array type
 *
 * @package PHP_APE_Type
 * @subpackage Classes
 */
class PHP_APE_Type_Array
extends PHP_APE_Type_Any
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Array holding variable
   * @var array */
  protected $amValue;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs an array object
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param mixed $mValue New value
   */
  public function __construct( $mValue = null )
  {
    $this->setValue( $mValue );
  }


  /*
   * METHODS: magic - OVERRIDE
   ********************************************************************************/

  /** Clones this array object
   *
   * <P><B>NOTE:</B> Data are copied down to the last bit !</P>
   *
   * @see PHP_MANUAL#serialize(), PHP_MANUAL#unserialize()
   */
  public function __clone()
  {
    $this->amValue = unserialize( serialize( $this->amValue ) );
  }


  /*
   * METHODS: magic
   ********************************************************************************/

  /** Returns a <I>string</I> representation of this value
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return string
   */
  public function __toString()
  {
    return var_export( $this->amValue, true );
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
   * @return string
   */
  public static function parseValue( $mValue, $bStrict = false )
  {
    if( is_null( $mValue ) ) return $bStrict ? null : array();
    if( is_scalar( $mValue ) )
    {
      if( strlen( $mValue ) <= 0 ) return $bStrict ? null : array();
      return array( $mValue );
    }
    if( is_object( $mValue ) ) 
    {
      if( $mValue instanceof PHP_APE_Type_Scalar ) return array( $mValue->getValue() );
      if( $mValue instanceof PHP_APE_Type_Array ) return $mValue->getValue();
      return (array)$mValue;
    }
    return $mValue;
  }


  /*
   * METHODS: value - OVERRIDE
   ********************************************************************************/

  /** Sets this object's data value
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param mixed $mValue New value
   */
  public function setValue( $mValue )
  {
    $this->amValue = self::parseValue( $mValue, true );
  }

  /** Sets this object's data value at the given index
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param mixed $mValue New value
   * @param mixed $mIndex Array index (value pushed to array if <SAMP>null</SAMP>)
   * @return mixed
   */
  final public function setValueAt( $mValue, $mIndex = null )
  {
    if( !is_array( $this->amValue ) ) $this->amValue = array();
    if( is_null( $mIndex ) ) array_push( $this->amValue, $mValue );
    else
    {
      if( !is_numeric( $mIndex ) ) $mIndex = PHP_APE_Type_Index::parseValue( $mIndex );
      $this->amValue[ $mIndex ] = $mValue;
    }
  }

  /** Returns the this object's value
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return array
   */
  final public function getValue()
  {
    return $this->amValue;
  }

  /** Resets this object's data value to its <I>blank</I> status
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function resetValue()
  {
    $this->mValue = null;
  }

  /** Returns this object's data value at the given index
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param mixed $mIndex Array index
   * @param boolean $bStrict Strict array/index check
   * @return mixed
   */
  final public function getValueAt( $mIndex, $bStrict = true )
  {
    if( empty( $this->amValue ) )
    {
      if( $bStrict )
        throw new PHP_APE_Type_Exception( __METHOD__, 'Null/empty data' );
      return null;
    }
    if( is_null( $mIndex ) )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Null index' );
    if( !is_numeric( $mIndex ) ) $mIndex = PHP_APE_Type_Index::parseValue( $mIndex );
    if( !array_key_exists( $mIndex, $this->amValue ) )
    {
      if( $bStrict )
        throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid/unexisting index; Index: '.$mIndex );
      return null;
    }
    return $this->amValue[ $mIndex ];
  }

  /** Clears (unsets) this object's data at the given index and returns the corresponding value
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param mixed $mIndex Array index (value popped from array if <SAMP>null</SAMP>)
   * @return mixed
   */
  final public function clearValueAt( $mIndex = null )
  {
    if( empty( $this->amValue ) ) return null;
    if( is_null( $mIndex ) ) return array_pop( $this->amValue );
    else
    {
      if( !is_numeric( $mIndex ) ) $mIndex = PHP_APE_Type_Index::parseValue( $mIndex );
      if( !array_key_exists( $mIndex, $this->amValue ) ) return null;
      return $this->amValue[ $mIndex ];
    }
  }


  /*
   * METHODS: introspection
   ********************************************************************************/

  /**
   * Checks this object's data for <SAMP>null</SAMP> value
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param mixed $mIndex Array element index (considering the entire array if <SAMP>null</SAMP>)
   * @return boolean
   * @see is_null()
   */
  final public function isNull( $mIndex = null )
  {
    if( is_null( $mIndex ) ) return is_null( $this->amValue );
    if( is_null( $this->amValue ) ) return true;
    if( !is_numeric( $mIndex ) ) $mIndex = PHP_APE_Type_Index::parseValue( $mIndex );
    if( !array_key_exists( $mIndex, $this->amValue ) ) return true;
    return is_null( $this->amValue[ $mIndex ] );
  }

  /** Checks this object's data for emptiness
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param mixed $mIndex Array element index (considering the entire array if <SAMP>null</SAMP>)
   * @return boolean
   * @see empty()
   */
  final public function isEmpty( $mIndex = null )
  {
    if( is_null( $mIndex ) ) return empty( $this->amValue );
    if( is_null( $this->amValue ) ) return true;
    if( !is_numeric( $mIndex ) ) $mIndex = PHP_APE_Type_Index::parseValue( $mIndex );
    if( !array_key_exists( $mIndex, $this->amValue ) ) return true;
    return empty( $this->amValue[ $mIndex ] );
  }

  /** Returns this object's size
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return integer
   * @see count()
   */
  public function size()
  {
    return count( $this->amValue );
  }

}
