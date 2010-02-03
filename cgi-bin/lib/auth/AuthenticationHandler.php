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

/** Authentication handler
 *
 * <P><B>NOTE:</B> The function results contains the authenticated user name (login name).</P>
 *
 * @package PHP_APE_Auth
 * @subpackage Handler
 */
abstract class PHP_APE_Auth_AuthenticationHandler
extends PHP_APE_Data_Function
{

  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new authenticator
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   *
   */
  public function __construct()
  {
    // Resources
    $asResources = PHP_APE_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_Auth_Authentication' );

    // Parent constructor
    parent::__construct( 'Authentication', new PHP_APE_Type_String(), $asResources['name.authentication'], $asResources['description.authentication'] );
  }


  /*
   * METHODS: execute - IMPLEMENT
   ********************************************************************************/

  /** Performs authentication (login) and returns the authenticated user identifier (login name)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Auth_AuthenticationException</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return PHP_APE_Type_String
   */
  final public function execute()
  {
    // Environment
    $roEnvironment =& PHP_APE_Auth_WorkSpace::useEnvironment();
    $roEnvironment->doAuthenticationLogin();
    $this->useContent()->setValue( $roEnvironment->getAuthenticationToken()->getUserID() );
    return $this->getContent();
  }


  /*
   * METHODS
   ********************************************************************************/

  /** Performs authentication (login) and returns the related authentication token
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Auth_AuthenticationException</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * @return PHP_APE_Auth_AuthenticationToken
   */
  abstract public function login();

  /** Performs de-authentication (logout) and returns default user name (login name)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Auth_AuthenticationException</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   */
  public function logout()
  {
    // Save user name
    $this->useContent()->setValue( 'anonymous' );
  }

}
