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
 * @subpackage WorkSpace
 */

/** PHP-APE authentication/authorization work space
 *
 * <P><B>USAGE:</B></P>
 * <P>The following static parameters (properties) are provisioned by this workspace:</P>
 * <UL>
 * <LI><SAMP>php_ape.auth.handler</SAMP>: authentication handler [default: '<SAMP>PHP_APE_Auth_AuthenticationHandler_Configuration</SAMP>']</LI>
 * <LI><SAMP>php_ape.auth.timeout</SAMP>: authentication (inactivity) time-out [seconds; default: <SAMP>3600</SAMP>]</LI>
 * <LI><SAMP>php_ape.auth.htdocs.url</SAMP>: root HTML documents URL [default: <SAMP>http://localhost/php-ape/lib/auth</SAMP>]</LI>
 * <LI><SAMP>php_ape.auth.allow.session_less</SAMP>: allow session-less authentication (useful for batch processes) [default: <SAMP>false</SAMP>]</LI>
 * <LI><SAMP>php_ape.auth.allow.ip_regexp</SAMP>: Perl regular expression matching IP addresses allowed to authenticate [default: <SAMP>empty</SAMP>]</LI>
 * <LI><SAMP>php_ape.auth.token.userid.pattern</SAMP>: Perl regular expression matching and replacing the authenticated user ID [default: <SAMP>empty</SAMP>]</LI>
 * </UL>
 *
 * @package PHP_APE_Auth
 * @subpackage WorkSpace
 */
class PHP_APE_Auth_WorkSpace
extends PHP_APE_WorkSpace
{

  /*
   * FIELDS: static
   ********************************************************************************/

  /** Authentication/authorization work space singleton
   * @var PHP_APE_Auth_WorkSpace */
  private static $oWORKSPACE;


  /*
   * FIELDS
   ********************************************************************************/

  /** Authentication handler
   * @var PHP_APE_Auth_AuthenticationHandler */
  private $oAuthenticationHandler;

  /** Authentication data
   * @var array|mixed */
  private $amAuthenticationData;

  /** Session-base authentication mode
   * @var boolean */
  private $bUseSession;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs the environment
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Auth_Exception</SAMP>.</P>
   */
  protected function __construct()
  {
    // Check session
    $this->bUseSession = isset( $_SESSION );
    if( PHP_APE_DEBUG )
      PHP_APE_WorkSpace::useEnvironment()->log( __METHOD__, 'Using session for authentication: '.($this->bUseSession?'true':'false'), E_USER_NOTICE );

    // Call the parent constructor
    parent::__construct();

    // Set the authentication handler
    $sAuthenticationHandler = $this->getStaticParameter( 'php_ape.auth.handler' );
    PHP_APE_Resources::loadDefinition( $sAuthenticationHandler );
    $oAuthenticationHandler = new $sAuthenticationHandler();
    if( !( $oAuthenticationHandler instanceof PHP_APE_Auth_AuthenticationHandler ) )
      throw new PHP_APE_Auth_Exception( __METHOD__, 'Invalid authentication handler; Class: '.get_class( $oAuthenticationHandler ) );
    $this->oAuthenticationHandler =& $oAuthenticationHandler;

    // Check authentication time-out
    $this->__checkAuthenticationTimeout();

    // Load authentication data
    $this->__loadAuthenticationData();
  }


  /*
   * METHODS: factory
   ********************************************************************************/

  /** Returns the (singleton) environment instance (<B>as reference</B>)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   *
   * @return PHP_APE_WorkSpace
   */
  public static function &useEnvironment()
  {
    if( is_null( self::$oWORKSPACE ) ) self::$oWORKSPACE = new PHP_APE_Auth_WorkSpace();
    return self::$oWORKSPACE;
  }


  /*
   * METHODS: verification
   ********************************************************************************/

  /** Verify and sanitize the supplied parameters
   *
   * @param array|string $rasParameters Input/output parameters (<B>as reference</B>)
   */
  protected function _verifyParameters( &$rasParameters )
  {
    // Parent environment
    parent::_verifyParameters( $rasParameters );

    // Authentication handler
    if( array_key_exists( 'php_ape.auth.handler', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.auth.handler' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = 'PHP_APE_Auth_AuthenticationHandler_Anonymous';
    }

    // Authentication timeout [seconds]
    if( array_key_exists( 'php_ape.auth.timeout', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.auth.timeout' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( is_null( $rValue ) or $rValue < 0 )
        $rValue = 3600;
    }

    // Root HTML documents URL
    if( array_key_exists( 'php_ape.auth.htdocs.url', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.auth.htdocs.url' ];
      $rValue = trim( PHP_APE_Type_Path::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = 'http://localhost/php-ape/lib/auth';
      else
        $rValue = rtrim( $rValue, '/' );
    }

    // Allow session-less authentication (useful for batch processes)
    if( array_key_exists( 'php_ape.auth.allow.session_less', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.auth.allow.session_less' ];
      $rValue = PHP_APE_Type_Boolean::parseValue( $rValue );
    }

    // Perl regular expression matching IP addresses allowed to authenticate
    if( array_key_exists( 'php_ape.auth.allow.ip_regexp', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.auth.allow.ip_regexp' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
    }

    // Authentication token
    if( array_key_exists( 'php_ape.auth.token', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.auth.token' ];
      if( !( $rValue instanceof PHP_APE_Auth_AuthenticationToken ) )
        $rValue = new PHP_APE_Auth_AuthenticationToken( 'anonymous' );
    }

    // Authentication token's user ID pattern
    if( array_key_exists( 'php_ape.auth.token.userid.pattern', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.auth.token.userid.pattern' ];
      $rValue = trim( PHP_APE_Type_Path::parseValue( $rValue ) );
    }

    // Authentication heart-beat [UNIX timestamp]
    if( array_key_exists( 'php_ape.auth.heartbeat', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.auth.heartbeat' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( is_null( $rValue ) or $rValue < 0 )
        $rValue = time();
    }

  }


  /*
   * METHODS: static parameters - OVERRIDE
   ********************************************************************************/

  protected function _mandatoryStaticParameters()
  {
    return array_merge( parent::_mandatoryStaticParameters(),
                        array(
                              'php_ape.auth.handler' => null, 'php_ape.auth.timeout' => null,
                              'php_ape.auth.htdocs.url' => null,
                              'php_ape.auth.allow.session_less' => null, 'php_ape.auth.allow.ip_regexp' => null,
                              'php_ape.auth.token.userid.pattern' => null,
                              )
                        );
  }

  protected function _mandatorySessionParameters()
  {
    return array_merge( parent::_mandatorySessionParameters(),
                        array( 'php_ape.auth.token' => null, 'php_ape.auth.heartbeat' => null ) );
  }

  protected function _mandatoryVolatileParameters()
  {
    return array_merge( parent::_mandatoryVolatileParameters(),
                        array( 'php_ape.auth.token' => null ) );
  }


  /*
   * METHODS: authentication
   ********************************************************************************/

  /** Returns the work space authentication handler (<B>as reference</B>)
   */
  final private function __checkAuthenticationTimeout()
  {
    // Only for session-base authentication
    if( $this->bUseSession )
    {
      // Check heart-beat/time-out
      if( PHP_APE_DEBUG )
        PHP_APE_WorkSpace::useEnvironment()->log( __METHOD__, 'Authentication last hearbeat: '.$this->getSessionParameter( 'php_ape.auth.heartbeat', false ), E_USER_NOTICE );
      if( time() - $this->getSessionParameter( 'php_ape.auth.heartbeat', false ) > $this->getStaticParameter( 'php_ape.auth.timeout' ) )
        $this->doAuthenticationLogout();

      // Update heart-beat
      $this->setSessionParameter( 'php_ape.auth.heartbeat', time() );
      $this->saveSessionParameters();
    }
  }

  /** Returns whether authentication is allowed
   */
  final public function isAuthenticationAllowed()
  {
    if( !$this->bUseSession )
    {
      return $this->getStaticParameter( 'php_ape.auth.allow.session_less' );
    }
    else
    {
      $regIPAddressFilter = $this->getStaticParameter( 'php_ape.auth.allow.ip_regexp' );
      if( empty( $regIPAddressFilter ) ) return true;
      if( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) return preg_match( $regIPAddressFilter, $_SERVER['HTTP_X_FORWARDED_FOR'] );
      if( isset( $_SERVER['REMOTE_ADDR'] ) ) return preg_match( $regIPAddressFilter, $_SERVER['REMOTE_ADDR'] );
    }
    return false;
  }

  /** Returns the work space authentication handler (<B>as reference</B>)
   *
   * @return PHP_APE_Auth_AuthenticationHandler
   */
  final public function &useAuthenticationHandler()
  {
    return $this->oAuthenticationHandler;
  }

  /** Performs authentication (login)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Auth_AuthenticationException</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function doAuthenticationLogin()
  {
    // Check if authentication is allowed
    if( !$this->isAuthenticationAllowed() )
      throw new PHP_APE_Auth_AuthenticationException( __METHOD__, 'Authentication denied' );

    // Logout before-hand
    $this->doAuthenticationLogout();

    // Login
    $oAuthenticationToken = clone( $this->oAuthenticationHandler->login() ); // NOTE: clone object, to prevent references lying around
    if( $this->bUseSession )
    {
      $this->setSessionParameter( 'php_ape.auth.token', $oAuthenticationToken );
      $this->saveSessionParameters();
    }
    else
    {
      $this->setVolatileParameter( 'php_ape.auth.token', $oAuthenticationToken );
    }

    // Log
    PHP_APE_WorkSpace::useEnvironment()->log( __METHOD__, 'Authentication successful; User: '.$oAuthenticationToken->getUserID(), E_USER_NOTICE );
  }

  /** Returns whether the current session has an authenticated token
   *
   * @return boolean
   */
  final public function hasAuthenticatedToken()
  {
    if( $this->bUseSession )
    {
      return $this->getSessionParameter( 'php_ape.auth.token', false )->getUserID() != 'anonymous';
    }
    else
    {
      return $this->getVolatileParameter( 'php_ape.auth.token', false )->getUserID() != 'anonymous';
    }
  }

  /** Returns the current authentication token
   *
   * @return PHP_APE_Auth_AuthenticationToken
   */
  final public function getAuthenticationToken()
  {
    if( $this->bUseSession )
    {
      return clone( $this->getSessionParameter( 'php_ape.auth.token', false ) ); // NOTE: clone object, to prevent token modification
    }
    else
    {
      return clone( $this->getVolatileParameter( 'php_ape.auth.token', false ) ); // NOTE: clone object, to prevent token modification
    }
  }

  /** Performs de-authentication (logout)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Auth_AuthenticationException</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function doAuthenticationLogout()
  {
    // Logout
    $this->oAuthenticationHandler->logout();
    if( $this->bUseSession )
    {
      $this->setSessionParameter( 'php_ape.auth.token', new PHP_APE_Auth_AuthenticationToken( 'anonymous' ) );
      $this->unsetSessionParameter( 'php_ape.auth.data' );
      $this->saveSessionParameters();
    }
    else
    {
      $this->setVolatileParameter( 'php_ape.auth.token', new PHP_APE_Auth_AuthenticationToken( 'anonymous' ) );
    }
  }


  /*
   * METHODS: authentication data
   ********************************************************************************/

  /** Loads authentication data
   */
  final private function __loadAuthenticationData()
  {
    // Check data existency
    $this->amAuthenticationData = array();
    if( !$this->bUseSession or !$this->hasSessionParameter( 'php_ape.auth.data' ) ) return;

    // Retrieve and decrypt data
    $sKey = $this->getSessionParameter( 'php_ape.auth.token', false )->getKey();
    $amData = $this->getSessionParameter( 'php_ape.auth.data', false );
    if( !empty( $amData ) )
    {
      $amData = PHP_APE_Util_Cryptography::decrypt( $amData, $sKey, $this->getStaticParameter( 'php_ape.util.crypto.algorithm.encryption' ) );
      $amData = unserialize( $amData );
      if( $amData === false )
        throw new PHP_APE_Exception( __METHOD__, 'Failed to unserialize parameters' );
    }
    else
      $amData = array();
    $this->amAuthenticationData =& $amData;
  }

  /** Returns the given authentication's data
   *
   * @return mixed
   */
  final public function getAuthenticationData( $sName )
  {
    $sName = strtolower( PHP_APE_Type_Index::parseValue( $sName ) ); // let's be developer-friendly ;-)
    return array_key_exists( $sName, $this->amAuthenticationData ) ? $this->amAuthenticationData[$sName] : null;
  }

  /** Sets the current given authentication's data
   *
   * <P><B>NOTE:</B> Data must be explicitely saved to retain their value (<SAMP>{@link saveAuthenticationData()}</SAMP>).</P>
   */
  final public function setAuthenticationData( $sName, $mValue )
  {
    $sName = strtolower( PHP_APE_Type_Index::parseValue( $sName ) ); // let's be developer-friendly ;-)
    $this->amAuthenticationData[$sName] = $mValue;
  }

  /** Clears the current authentication token's given data
   *
   * <P><B>NOTE:</B> Data must be explicitely saved to retain their value (<SAMP>{@link saveAuthenticationData()}</SAMP>).</P>
   */
  final public function unsetAuthenticationData( $sName )
  {
    $sName = strtolower( PHP_APE_Type_Index::parseValue( $sName ) ); // let's be developer-friendly ;-)
    unset( $this->amAuthenticationData[$sName] );
  }

  /** Saves the authentication data
   */
  final public function saveAuthenticationData()
  {
    // Check status
    if( !$this->hasAuthenticatedToken() )
      throw new PHP_APE_Auth_Exception( __METHOD__, 'No authentication token' );

    // Only for session-base authentication
    if( $this->bUseSession )
    {
      // Retrieve and decrypt data
      $sKey = $this->getSessionParameter( 'php_ape.auth.token', false )->getKey();
      $amData = serialize( $this->amAuthenticationData );
      $amData = PHP_APE_Util_Cryptography::encrypt( $amData, $sKey, $this->getStaticParameter( 'php_ape.util.crypto.algorithm.encryption' ) );
      $this->setSessionParameter( 'php_ape.auth.data', $amData );
      $this->saveSessionParameters();
    }
  }

}
