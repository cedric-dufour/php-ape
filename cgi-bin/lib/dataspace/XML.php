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

 * @package PHP_APE_DataSpace
 * @subpackage Classes
 */

/** XML dataspace
 *
 * @package PHP_APE_DataSpace
 * @subpackage Classes
 */
class PHP_APE_DataSpace_XML
extends PHP_APE_DataSpace_Any
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** <I>CDATA</I> data format
   * @var integer */
  const Format_CDATA = 65536;

  /** <I>UTF-8</I> data transcoding
   * @var integer */
  const Format_UTF8 = 131072;


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
    $aParts = preg_split( '/(<\\?xml .*?\\?>)\r?\n?/s', $sInput, -1, PREG_SPLIT_DELIM_CAPTURE );
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
   * @param boolean $bCDATA Parse input as XML's CDATA
   * @param boolean $bUTF8 Decode using UTF-8 transcoding
   * @return string
   */
  public function decodeData( $sInput, $bCDATA = false, $bUTF8 = false )
  {
    static $asXMLEntities=array( '&amp;' => '&', '&lt;' => '<', '&gt;' => '>', '&quot;' => '"', '&apos;' => '\'' );

    if( $bCDATA and preg_match('/<!\[CDATA\[(.*)\]\]>/is', $sInput, $aCDATA ) ) $sInput = $aCDATA[1];
    else $sInput = strtr( $sInput, $asXMLEntities );
    if( $bUTF8 ) $sInput = utf8_decode( $sInput );
    return $sInput;
  }

  /** Encodes characters according the dataspace-specific encoding rules
   *
   * <P><B>INHERITANCE:</B> This method <B>SHOULD NOT be overridden</B>.</P>
   *
   * @param string $sInput Data to encode
   * @param boolean $bCDATA Format output as XML's CDATA
   * @param boolean $bUTF8 Encode using UTF-8 transcoding
   * @return string
   */
  public function encodeData( $sInput, $bCDATA = false, $bUTF8 = false )
  {
    static $asXMLEntities=array( '&' => '&amp;', '<' => '&lt;', '>' => '&gt;', '"' => '&quot;', '\'' => '&apos;' );

    if( $bUTF8 ) $sInput = utf8_encode( $sInput );
    if( $bCDATA ) $sInput = '<![CDATA['.$sInput.']]>';
    else strtr( $sInput, $asXMLEntities );
    return $sInput;
  }


  /*
   * METHODS: parse/format - OVERRIDE
   ********************************************************************************/

  public function parseData( PHP_APE_Type_Any $oData, $mValue, $bStrict = true, $mFormat = null )
  {
    $bCDATA = (bool)( $mFormat & self::Format_CDATA );
    $bUTF8 = (bool)( $mFormat & self::Format_UTF8 );
    $oData->setValueParsed( $this->decodeData( $mValue, $bCDATA, $bUTF8 ), $bStrict, $mFormat );
  }

  public function formatData( PHP_APE_Type_Any $oData, $mFormat = null, $sIfNull = null )
  {
    $bCDATA = (bool)( $mFormat & self::Format_CDATA );
    $bUTF8 = (bool)( $mFormat & self::Format_UTF8 );
    return $this->encodeData( $oData->getValueFormatted( $sIfNull, $mFormat ), $bCDATA, $bUTF8 );
  }

}
