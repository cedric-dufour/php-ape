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
 * @package PHP_APE_Auth
 * @subpackage Handler
 */

/** Configuration-based authentication handler
 *
 * <P><B>USAGE:</B></P>
 * <P>This handler use the following configuration parameters:</P>
 * <UL>
 * <LI><SAMP>php_ape.auth.credentials</SAMP>: array of valid credentials, associating <SAMP>username</SAMP> => <I>sha1(</I><SAMP>password</SAMP><I>)</I></LI>
 * </UL>
 *
 * @package PHP_APE_Auth
 * @subpackage Handler
 */
class PHP_APE_Auth_AuthenticationHandler_Configuration
extends PHP_APE_Auth_AuthenticationHandler_UserPass
{

  /*
   * METHODS: authentication - IMPLEMENT
   ********************************************************************************/

  public function login()
  {
    // Environment
    $roEnvironment =& PHP_APE_Auth_WorkSpace::useEnvironment();

    // Retrieve credentials
    $roArgumentSet =& $this->useArgumentSet();
    $sUserName = $roArgumentSet->useElementByID( 'username' )->useContent()->getValue();
    $sPassword = $roArgumentSet->useElementByID( 'password' )->useContent()->getValue();

    // Retrieved configured credentials
    $amCredentials = array();
    if( $roEnvironment->hasStaticParameter( 'php_ape.auth.credentials' ) )
      $amCredentials = PHP_APE_Type_Array::parseValue( $roEnvironment->getStaticParameter( 'php_ape.auth.credentials' ) );

    // Check credentials
    if( !array_key_exists( $sUserName, $amCredentials ) or $amCredentials[$sUserName] != sha1( $sPassword ) )
      throw new PHP_APE_Auth_AuthenticationException( __METHOD__, 'Invalid credentials', 0, $sUserName );

    // End
    return new PHP_APE_Auth_AuthenticationToken( $sUserName, $sPassword );
  }

}
