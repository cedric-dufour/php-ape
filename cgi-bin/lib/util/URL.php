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
 * @package PHP_APE_Util
 * @subpackage Miscellaneous
 */

/** URL-related utilities
 *
 * @package PHP_APE_Util
 * @subpackage Miscellaneous
 */
class PHP_APE_Util_URL
extends PHP_APE_Util_Any
{

  /*
   * METHODS: static
   ********************************************************************************/

  /** Returns whether the given <I>string</I> matches a valid e-mail address
   *
   * @param string $sString Input string
   * @return boolean
   */
  public static function isEmail( $sString )
  {
    return preg_match( '/s*(\w+[-_.])*\w+@(\w+[-_.])*\w+\.\w+\s*/A', $sString );
  }

  /** Returns whether the given <I>string</I> matches a valid HTTP URL
   *
   * @param string $sString Input string
   * @param boolean $bStrict Perform strict syntax checking (check path is valid)
   * @return boolean
   */
  public static function isHTTP( $sString, $bStrict = false )
  {
    if( $bStrict ) return preg_match( '/https?:\/\/(\w+[-_.])*\w+\.\w+(\/(\w+[-_.])*\w+)*\/?(\?.*)?/A', $sString );
    else return preg_match( '/^https?:\/\/(\w+[-_.])*\w+\.\w+\/?/', $sString );
  }

  /** Returns whether the given <I>string</I> matches a valid FTP URL
   *
   * @param string $sString Input string
   * @param boolean $bStrict Perform strict syntax checking (check path is valid)
   * @return boolean
   */
  public static function isFTP( $sString, $bStrict = false )
  {
    if( $bStrict ) return preg_match( '/ftps?:\/\/(\w+[-_.])*\w+\.\w+(\/(\w+[-_.])*\w+)*\/?(\?.*)?/A', $sString );
    else return preg_match( '/^ftps?:\/\/(\w+[-_.])*\w+\.\w+\/?/', $sString );
  }

  /** Returns whether the given URL matches the required domain
   *
   * @param string $sString Input string
   * @param string $sDomain Required domain name
   * @param boolean $bStrict Perform strict syntax checking (check path is valid)
   * @return boolean
   */
  public static function isDomain( $sString, $sDomain, $bStrict = false )
  {
    if( $bStrict ) return preg_match( '/\s*\w+:\/\/'.preg_quote($sDomain).'(\/(\w+[-_.])*\w+)*\/?(\?.*)?\s*$/A', $sString );
    else return preg_match( '/^\s*\w+:\/\/'.preg_quote($sDomain).'/i', $sString );
  }

  /** Returns the input URL, with the given variables/values pairs appended
   *
   * <P><B>NOTE:</B> This method adds variables/values pairs to the given URL using proper <SAMP>RFC 1738</SAMP> encoding.</P>
   *
   * @param string $sURL Input URL
   * @param array $asValues Variable(key)/value pairs
   * @return string
   */
  public static function addVariable( $sURL, $asValues )
  {
    if( !is_array( $asValues ) or !count( $asValues ) ) return $sURL;
    $sURL_suffix = null;
    foreach( $asValues as $sVariable => $sValue ) $sURL_suffix .= ( $sURL_suffix ? '&' : null ).$sVariable.'='.rawurlencode( $sValue );
    return $sURL.( strpos( $sURL, '?' )===false ? '?' : '&' ).$sURL_suffix;
  }

  /** Returns the full request URI (including protocol/server/port) for the current page
   *
   * <P><B>WARNING:</B> This method relies on the <SAMP>$_SERVER['HTTPS']</SAMP>, <SAMP>$_SERVER['HTTP_HOST']</SAMP>
   * and <SAMP>$_SERVER['SERVER_PORT']</SAMP> variables, which MAY not contain the proper values if the web server
   * was not set up appropriately.</P>
   *
   * @param boolean $bIncludeQueryString Include the <SAMP>$_SERVER['QUERY_STRING']</SAMP> in the result.
   * @return string
   */
  public static function getFullRequestURI( $bIncludeQueryString = true )
  {
    if( !isset( $_SERVER['HTTP_HOST'] ) or empty( $_SERVER['HTTP_HOST'] ) ) return null;
    if( isset( $_SERVER['HTTPS'] ) and $_SERVER['HTTPS'] and $_SERVER['HTTPS'] != 'off' )
      return 'https://'.$_SERVER['HTTP_HOST'].( $_SERVER['SERVER_PORT'] != 443 ? ':'.$_SERVER['SERVER_PORT'] : null ).( $bIncludeQueryString ? $_SERVER['REQUEST_URI'] : $_SERVER['SCRIPT_NAME'] );
    else
      return 'http://'.$_SERVER['HTTP_HOST'].( $_SERVER['SERVER_PORT'] != 80 ? ':'.$_SERVER['SERVER_PORT'] : null ).( $bIncludeQueryString ? $_SERVER['REQUEST_URI'] : $_SERVER['SCRIPT_NAME'] );
  }

}
