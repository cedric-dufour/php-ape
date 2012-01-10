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

/** HTML dataspace
 *
 * @package PHP_APE_DataSpace
 * @subpackage Classes
 */
class PHP_APE_DataSpace_HTML
extends PHP_APE_DataSpace_Any
{

  /*
   * METHODS: stream (static)
   ********************************************************************************/

  /** Explodes the supplied stream into its basic components
   *
   * <P><B>RETURNS:</B> an <I>array</I> (per part) of <I>array</I> containing:</P>
   * <UL>
   * <LI><SAMP>header</SAMP>: stream header</LI>
   * <LI><SAMP>data</SAMP>: stream data</LI>
   * </UL>
   *
   * @param string $sInput Input stream
   * @return array|array|string
   */
  static public function explodeStream( $sInput )
  {
    $aOutput = array();
    $aParts = preg_split( '/(HTTP\/.*?\r?\n)\r?\n/s', $sInput, -1, PREG_SPLIT_DELIM_CAPTURE );
    for( $i = 0, $l = (integer)((count($aParts)-1)/2); $i < $l; $i++ )
    {
      $aOutput[$i]['header'] = $aParts[$i*2+1];
      $aOutput[$i]['data'] = $aParts[$i*2+2];
    }
    return $aOutput;
  }


  /*
   * METHODS: decode/encode - OVERRIDE
   ********************************************************************************/

  /** Decodes characters according the dataspace-specific encoding rules
   *
   * <P><B>INHERITANCE:</B> This method <B>SHOULD NOT be overridden</B>.</P>
   *
   * @param string $sInput Data to encode
   * @param boolean $bBR2NL Convert HTML line breaks into new lines
   * @return string
   */
  public function decodeData( $sInput, $bBR2NL = false )
  {
    return html_entity_decode( $bBR2NL ? preg_replace('/<br\s*\/?>/i', "\n" , $sInput ) : $sInput );
  }

  /** Encodes characters according the dataspace-specific encoding rules
   *
   * <P><B>INHERITANCE:</B> This method <B>SHOULD NOT be overridden</B>.</P>
   *
   * @param string $sInput Data to encode
   * @param boolean $bNL2BR Convert new lines into HTML line breaks
   * @param boolean $bSP2NBSP Convert space into HTML non-breakable spaces
   * @return string
   */
  public function encodeData( $sInput, $bNL2BR = false, $bSP2NBSP = false )
  {
    $sOutput = htmlentities( $sInput );
    if( $bNL2BR ) $sOutput = nl2br( $sOutput );
    if( $bSP2NBSP ) $sOutput = strtr( $sOutput, array( ' ' => '&nbsp;' ) );
    return $sOutput;
  }


  /*
   * METHODS: parse/format - OVERRIDE
   ********************************************************************************/

  public function parseData( PHP_APE_Type_Any $oData, $mValue, $bStrict = true, $mFormat = null )
  {
    if( $oData instanceof PHP_APE_Type_Text ) $oData->setValueParsed( $this->decodeData( $mValue, true ), $bStrict, $mFormat );
    $oData->setValueParsed( $this->decodeData( $mValue, false ), $bStrict, $mFormat );
  }

  public function formatData( PHP_APE_Type_Any $oData, $mFormat = null, $sIfNull = null )
  {
    if( $oData instanceof PHP_APE_Type_Text ) return $this->encodeData( $oData->getValueFormatted( $sIfNull, $mFormat ), true );
    return $this->encodeData( $oData->getValueFormatted( $sIfNull, $mFormat ), false );
  }

}
