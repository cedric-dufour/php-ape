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

/** Order class
 *
 * @package PHP_APE_Data
 * @subpackage Classes
 */
class PHP_APE_Data_Order
extends PHP_APE_Data_OrderedSet
{

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
    return $this->toProperties();
  }


  /*
   * METHODS: data - OVERRIDE
   ********************************************************************************/

  /** Sets (adds) the given criteria to this order
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   *
   * @param PHP_APE_Data_OrderCriteria $oData Order criteria
   */
  public function setElement( PHP_APE_Data_Any $oData )
  {
    if( !($oData instanceof PHP_APE_Data_OrderCriteria) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Invalid criteria object; Class: '.get_class( $oData ) );
    parent::setElement( $oData );
  }


  /*
   * METHODS: import/export
   ********************************************************************************/
  
  /** Parses (creates) this order from the given (human-friendly) properties <I>string</I> representation
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   *
   * @param string $sProperties Order properties string
   * @see PHP_APE_Properties
   */
  public function fromProperties( $sProperties )
  {
    $this->unsetAllElements();
    $asProperties = PHP_APE_Properties::convertString2Array( $sProperties, false );
    foreach( $asProperties as $mID => $iDirection ) $this->setElement( new PHP_APE_Data_OrderCriteria( $mID, (integer)$iDirection ) );
  }

  /** Returns the (human-friendly) properties <I>string</I> representation corresponding to this order
   *
   * @return string
   * @see PHP_APE_Properties
   */
  public function toProperties()
  {
    $asProperties = array();
    foreach( $this->getElementsArray() as $oCriteria ) $asProperties[ $oCriteria->getID() ] = (string)$oCriteria->getDirection();
    return PHP_APE_Properties::convertArray2String( $asProperties );
  }

}
