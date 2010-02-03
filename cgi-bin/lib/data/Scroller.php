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

/** Scroller class
 *
 * @package PHP_APE_Data
 * @subpackage Classes
 */
class PHP_APE_Data_Scroller
extends PHP_APE_Data_Any
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Scroller limit (maximum data quantity)
   * @var integer
   */
  private $iLimit;

  /** Scroller offset (data page shift)
   * @var integer
   */
  private $iOffset;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  public function __construct( $iLimit = null, $iOffset = null )
  {
    if( !is_null( $iLimit ) ) $this->setLimit( $iLimit );
    if( !is_null( $iOffset ) ) $this->setOffset( $iOffset );
  }


  /*
   * METHODS
   ********************************************************************************/

  /** Returns whether this scroller's limit (maximum data quantity) has been defined
   *
   * @return boolean
   */
  public function isLimited()
  {
    return !is_null( $this->iLimit );
  }

  /** Returns this scroller's limit (maximum data quantity)
   *
   * @return integer
   */
  public function getLimit()
  {
    return $this->iLimit;
  }

  /** Sets this scroller's limit (maximum data quantity)
   *
   * @param integer $iLimit Scroller's limit
   */
  public function setLimit( $iLimit )
  {
    $this->iLimit = (integer)$iLimit;
  }

  /** Unsets (clears) this scroller's limit (maximum data quantity)
   */
  public function unsetLimit()
  {
    $this->iLimit = null;
  }

  /** Returns whether this scroller's offset (data page shift) has been defined
   *
   * @return boolean
   */
  public function isOffset()
  {
    return !is_null( $this->iOffset );
  }

  /** Returns this scroller's offset (data page shift)
   *
   * @return integer
   */
  public function getOffset()
  {
    return $this->iOffset;
  }

  /** Sets this scroller's offset (data page shift)
   *
   * @param integer $iOffset Scroller's offset
   */
  public function setOffset( $iOffset )
  {
    $this->iOffset = (integer)$iOffset;
  }

  /** Unsets (clears) this scroller's offset (data page shift)
   */
  public function unsetOffset()
  {
    $this->iOffset = null;
  }


  /*
   * METHODS: import/export
   ********************************************************************************/
  
  /** Parses (creates) this scroller from the given (human-friendly) properties <I>string</I> representation
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   *
   * @param string $sProperties Scroller properties string
   * @see PHP_APE_Properties
   */
  public function fromProperties( $sProperties )
  {
    $this->unsetLimit();
    $this->unsetOffset();
    $asProperties = PHP_APE_Properties::convertString2Array( $sProperties, true );
    if( array_key_exists( 'limit', $asProperties ) )
      $this->setLimit( $asProperties[ 'limit' ] );
    if( array_key_exists( 'offset', $asProperties ) )
      $this->setOffset( $asProperties[ 'offset' ] );
  }

  /** Returns the (human-friendly) properties <I>string</I> representation corresponding to this scroller
   *
   * @return string
   * @see PHP_APE_Properties
   */
  public function toProperties()
  {
    $asProperties = array();
    if( !is_null( $this->iLimit ) ) $asProperties['limit'] = $this->iLimit;
    if( !is_null( $this->iOffset ) ) $asProperties['offset'] = $this->iOffset;
    return PHP_APE_Properties::convertArray2String( $asProperties );
  }

}
