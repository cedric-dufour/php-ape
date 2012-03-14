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
 * @package PHP_APE_DataSpace
 * @subpackage Classes
 */

/** JavaScript dataspace
 *
 * <P><B>NOTE:</B> This dataspace does NOT implement (support) decode/parse methods.</P>
 *
 * @package PHP_APE_DataSpace
 * @subpackage Classes
 */
class PHP_APE_DataSpace_JavaScript
extends PHP_APE_DataSpace_Any
{

  /*
   * METHODS: decode/encode - OVERRIDE
   ********************************************************************************/

  /** Decodes characters according the dataspace-specific encoding rules
   *
   * <P><B>NOT IMPLEMENTED</B></P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_DataSpace_Exception</SAMP>.</P>
   */
  public function decodeData( $sInput )
  {
    throw new PHP_APE_DataSpace_Exception( __METHOD__, 'Method not implemented' );
  }

  public function encodeData( $sInput )
  {
    return addCSlashes( $sInput, "\x0..\x1F\\\"'/" );
  }


  /*
   * METHODS: parse/format
   ********************************************************************************/

  /** Parses data according to their type and the dataspace-specific encoding rules
   *
   * <P><B>NOT IMPLEMENTED</B></P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_DataSpace_Exception</SAMP>.</P>
   */
  public function parseData( PHP_APE_Type_Any $oData, $mValue, $bStrict = true, $mFormat = null )
  {
    throw new PHP_APE_DataSpace_Exception( __METHOD__, 'Method not implemented' );
  }

}
