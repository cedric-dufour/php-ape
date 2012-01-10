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

/** Arguments set class
 *
 * @package PHP_APE_Data
 * @subpackage Classes
 */
class PHP_APE_Data_ArgumentSet
extends PHP_APE_Data_OrderedSet
implements PHP_APE_Data_isMetaDataSet
{

  /*
   * METHODS: elements - OVERRIDE
   ********************************************************************************/

  /** Sets (adds) the given argument to this set
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param PHP_APE_Data_Argument $oArgument New argument
   */
  public function setElement( PHP_APE_Data_Any $oArgument )
  {
    if( !($oArgument instanceof PHP_APE_Data_Argument) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Invalid argument object; Class: '.get_class( $oArgument ) );
    parent::setElement( $oArgument );
  }


  /*
   * METHODS: PHP_APE_Data_isMetaDataSet - IMPLEMENT
   ********************************************************************************/

  /** Returns the elements (fields) keys from this set, which match the given requested meta data
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param integer $iMetaRequested Requested meta data
   * @param boolean $bMatchAll Match all requested meta data
   * @return array|mixed
   */
  final public function getElementsKeysPerMeta( $iMetaRequested, $bMatchAll = false )
  {
    $iMetaRequested = (integer)$iMetaRequested;
    $amKeys = array();
    foreach( $this->getElementsKeys() as $mKey )
    {
      $iMeta = $this->useElement( $mKey )->getMeta() & $iMetaRequested;
      if( ( $iMeta == $iMetaRequested ) or ( !$bMatchAll and $iMeta != 0 ) ) array_push( $amKeys, $mKey );
    }
    return $amKeys;
  }

}
