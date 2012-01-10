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

/** URL type
 *
 * <P><B>NOTE:</B> PHP does NOT handle different types of <I>string</I> data, but other dataspaces DO.</P>
 *
 * @package PHP_APE_Type
 * @subpackage Classes
 */
class PHP_APE_Type_URL
extends PHP_APE_Type_String
{

  /*
   * METHODS: static
   ********************************************************************************/

  /** Explode the given data <I>string</I> into basic components
   *
   * <P><B>RETURNS:</B> an <I>array</I> containing:</P>
   * <UL>
   * <LI><SAMP>0</SAMP>: protocol</LI>
   * <LI><SAMP>1</SAMP>: domain</LI>
   * <LI><SAMP>2</SAMP>: path</LI>
   * </UL>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param string $sString Input string
   * @param boolean $bStrictPath Apply strict path checking rules
   * @param string $sDomain Domain name constraint (ignored if <SAMP>null</SAMP>)
   * @param string $sProtocol Protocol constraint (ignored if <SAMP>null</SAMP>)
   * @return array|mixed
   */
  public static function explodeString( $sString, $bStrictPath = false, $sDomain = null, $sProtocol = null )
  {
    if( !preg_match( '/^('.($sProtocol?preg_quote($sProtocol):'\w+').'):\/\/('.($sDomain?'('.preg_quote($sDomain).')':'(\w+[-_.])*\w+\.\w+').')'.($bStrictPath?'((\/(\w+[-_.])*\w+)*(\/(\w+[-_.])*\w+\.\w+)?)?':'(\S+)?').'/i', $sString, $aMatch ) )
      if( !preg_match( '/\s('.($sProtocol?preg_quote($sProtocol):'\w+').'):\/\/('.($sDomain?'('.preg_quote($sDomain).')':'(\w+[-_.])*\w+\.\w+').')'.($bStrictPath?'((\/(\w+[-_.])*\w+)*(\/(\w+[-_.])*\w+\.\w+)?)?':'(\S+)?').'/i', $sString, $aMatch ) )
        throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid/unparsable input; String: '.$sString );
    $amComponents = array();
    $amComponents[0] = $aMatch[1]; // protocol
    $amComponents[1] = $aMatch[2]; // domain
    $amComponents[2] = isset($aMatch[4]) ? $aMatch[4] : null; // path
    return $amComponents;
  }

  /** Parses the given data <I>string</I> into its corresponding value
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param string $sString Input string
   * @param boolean $bStrictPath Apply strict path checking rules
   * @param string $sDomain Domain name constraint (ignored if <SAMP>null</SAMP>)
   * @param string $sProtocol Protocol constraint (ignored if <SAMP>null</SAMP>)
   * @return string
   */
  public static function parseString( $sString, $bStrictPath = false, $sDomain = null, $sProtocol = null )
  {
    if( is_null( $sString ) ) return null;
    return self::parseComponents( self::explodeString( $sString, $bStrictPath = false, $sDomain = null, $sProtocol = null ) );
  }


  /** Parses the given data components <I>array</I> into their corresponding value
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param array|mixed $amComponents Input components <I>array</I>
   * @return string
   * @see explodeString()
   */
  public static function parseComponents( $amComponents )
  {
    // Check input
    if( !is_array( $amComponents ) or count( $amComponents ) < 3 )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid/unparsable components' );

    // Return string
    return (string)$amComponents[0].'://'.$amComponents[1].$amComponents[2];
  }

  /** Parses the given <I>mixed</I> data into their corresponding value
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param mixed $mValue Input value
   * @param boolean $bStrict Strict data parsing (<SAMP>null</SAMP> remains <SAMP>null</SAMP>)
   * @param boolean $bStrictPath Apply strict path checking rules
   * @param string $sDomain Domain name constraint (ignored if <SAMP>null</SAMP>)
   * @param string $sProtocol Protocol constraint (ignored if <SAMP>null</SAMP>)
   * @return string
   */
  public static function parseValue( $mValue, $bStrict = true, $bStrictPath = false, $sDomain = null, $sProtocol = null )
  {
    $mValue = parent::parseValue( $mValue, $bStrict );
    if( is_null( $mValue ) ) return null;
    return self::parseString( $mValue, $bStrictPath = false, $sDomain = null, $sProtocol = null );
  }


  /*
   * METHODS: value - OVERRIDE
   ********************************************************************************/

  public function setValue( $mValue )
  {
    $this->mValue = self::parseValue( $mValue, true );
  }


  /*
   * METHODS: parse/format - OVERRIDE
   ********************************************************************************/

  public function setValueParsed( $mValue, $bStrict = true, $iFormat = null )
  {
    $this->mValue = self::parseValue( $mValue, $bStrict );
  }

}
