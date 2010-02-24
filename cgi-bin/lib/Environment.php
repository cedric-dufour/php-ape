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
 * @package PHP_APE
 * @subpackage WorkSpace
 */

/** Required extensions */
PEAR::loadExtension( 'mcrypt' );
PEAR::loadExtension( 'mhash' );

/** PHP-APE environment
 *
 * <P><B>NOTE:</B></P>
 * <P>This class provides the methods to handle:</P>
 * <OL>
 * <LI>Static parameters - read-only data (retrieved from static configuration file);</LI>
 * <LI>Persistent parameters - persistent runtime data (stored/retrieved from encrypted data file),
 * defaulting to static parameters if undefined;</LI>
 * <LI>Session parameters - session-based data (stored/retrieved from encrypted session variable),
 * defaulting to persistent parameters if undefined;</LI>
 * <LI>Volatile parameters - volatile runtime data, defaulting to persistent parameters if undefined;</LI>
 * <LI>User parameters - user-customizable data (stored/retrieved from encrypted data file),
 * defaulting to volatile parameters if undefined.</LI>
 * </OL>
 *
 * <P><B>USAGE:</B></P>
 * <P>The following static parameters are provisioned by this environment:</P>
 * <UL>
 * <LI><SAMP>php_ape.cookie.expiration.days</SAMP>: cookie lifetime (in days) [default: <SAMP>90</SAMP>]</LI>
 * <LI><SAMP>php_ape.path.uploaded</SAMP>: uploaded data path [default: '<SAMP>/var/lib/ape/uploaded</SAMP>']</LI>
 * <LI><SAMP>php_ape.util.crypto.algorithm.hash</SAMP>: hash (one-way encryption) algorithm [default: <SAMP>MHASH_SHA256</SAMP>]</LI>
 * <LI><SAMP>php_ape.util.crypto.algorithm.encryption</SAMP>: (reversible) encryption algorithm [default: <SAMP>MCRYPT_RIJNDAEL_256</SAMP>]</LI>
 * <LI><SAMP>php_ape.util.crypto.key</SAMP>: global encryption key [default: <SAMP>null</SAMP>]</LI>
 * <LI><SAMP>php_ape.util.crypto.key.client</SAMP>: client-side encryption key [default: <SAMP>php_ape.util.crypto.key</SAMP>]</LI>
 * <LI><SAMP>php_ape.util.crypto.key.server</SAMP>: server-side encryption key [default: <SAMP>php_ape.util.crypto.key</SAMP>]</LI>
 * <LI><SAMP>php_ape.util.filesystem.charset</SAMP>: file-system character set (encoding) [default: '<SAMP>UTF-8</SAMP>']</LI>
 * </UL>
 *
 * @package PHP_APE
 * @subpackage WorkSpace
 */
abstract class PHP_APE_Environment
{

  /*
   * FIELDS
   ********************************************************************************/

  /** User key
   * @var string */
  private $sUserKey;

  /** Static parameters
   * @var array|string */
  protected $asStaticParameters;

  /** Persistent parameters
   * @var array|string */
  protected $asPersistentParameters;

  /** Session parameters
   * @var array|string */
  protected $asSessionParameters;

  /** Volatile parameters
   * @var array|string */
  protected $asVolatileParameters;

  /** User parameters
   * @var array|string */
  protected $asUserParameters;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs the environment
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   */
  protected function __construct()
  {
    // Initialize members fields
    $this->sUserKey = sha1( rand() );
    //$this->asStaticParameters = array(); // This MUST be handled by __loadStaticParameters()
    $this->asPersistentParameters = array();
    $this->asSessionParameters = array();
    $this->asVolatileParameters = array();
    $this->asUserParameters = array();

    // Static parameters
    // NOTE: static parameters MUST exist
    $this->__loadStaticParameters();

    // Cookies
    // NOTE: cookie MAY be corrupted (if cryptography settings are changed)
    // ... load
    try { @$this->__loadCookie(); }
    catch( PHP_APE_Exception $e ) {}

    // Persistent parameters
    // NOTE: persistent parameters MAY not exist
    // ... load
    try { @$this->__loadPersistentParameters(); }
    catch( PHP_APE_Exception $e ) {}
    if( !is_array( $this->asPersistentParameters ) ) $this->asPersistentParameters = array();

    // Session parameters
    // NOTE: session parameters MAY not exist
    if( isset( $_SESSION ) )
    {
      // ... load
      try { @$this->__loadSessionParameters(); }
      catch( PHP_APE_Exception $e ) {}
    }
    if( !is_array( $this->asSessionParameters ) ) $this->asSessionParameters = array();

    // Volatile parameters
    // NOTE: volatile parameters MAY not exist
    try { @$this->__verifyVolatileParameters(); }
    catch( PHP_APE_Exception $e ) {}
    if( !is_array( $this->asVolatileParameters ) ) $this->asVolatileParameters = array();

    // User parameters
    // ... load
    try { @$this->__loadUserParameters(); } // NOTE: user parameters MAY not exist
    catch( PHP_APE_Exception $e ) {}
    if( !is_array( $this->asUserParameters ) ) $this->asUserParameters = array();
  }


  /*
   * METHODS: logging
   ********************************************************************************/

  /** Logs (triggers) a PHP message
   *
   * <P><B>NOTE:</B> By default, this methods calls the <SAMP>{@link PHP_APE_Logger::log()}</SAMP> function.</P>
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param string $sContext Triggering context
   * @param string $sMessage Error message
   * @param int $iType Message type
   */
  public function log( $sContext, $sMessage, $iType )
  {
    PHP_APE_Logger::log( $sContext, $sMessage, $iType );
  }


  /*
   * METHODS: user
   ********************************************************************************/

  /** Returns the user attributed key
   *
   * @return string
   */
  protected function _getUserKey()
  {
    return $this->sUserKey;
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
    // Cookie expiration days count
    if( array_key_exists( 'php_ape.cookie.expiration.days', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.cookie.expiration.days' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( empty( $rValue ) )
        $rValue = 90;
      elseif( $rValue < 0 )
        $rValue = 0;
    }

    // Uploaded data path
    if( array_key_exists( 'php_ape.path.uploaded', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.path.uploaded' ];
      $rValue = trim( PHP_APE_Type_Path::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = '/var/lib/ape/uploaded';
      else
        $rValue = rtrim( $rValue, '/' );
    }

    // Hash algorithm
    if( array_key_exists( 'php_ape.util.crypto.algorithm.hash', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.util.crypto.algorithm.hash' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = MHASH_SHA256;
    }

    // Encryption algorithm
    if( array_key_exists( 'php_ape.util.crypto.algorithm.encryption', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.util.crypto.algorithm.encryption' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = MCRYPT_RIJNDAEL_256;
    }

    // Global encryption key
    if( array_key_exists( 'php_ape.util.crypto.key', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.util.crypto.key' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = null;
    }

    // Client-side encryption key
    if( array_key_exists( 'php_ape.util.crypto.key.client', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.util.crypto.key.client' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = $rasParameters[ 'php_ape.util.crypto.key' ];
    }

    // Server-side encryption key
    if( array_key_exists( 'php_ape.util.crypto.key.server', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.util.crypto.key.server' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = $rasParameters[ 'php_ape.util.crypto.key' ];
    }

    // File-system character set
    if( array_key_exists( 'php_ape.util.filesystem.charset', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.util.filesystem.charset' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = 'UTF-8';
    }
  }


  /*
   * METHODS: encryption
   ********************************************************************************/

  /** Returns the data for the HTML data page
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Util_CryptographyException</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sData Unencrypted data
   * @param boolean $bUseClientEncryption Use (two-pass) client encryption
   * @param boolean $bToHexadecimal Use hexadecimal (transport-friendly) encoding
   * @return string
   */
  final public function encryptData( $sData, $bUseClientEncryption = true, $bToHexadecimal = true )
  {
    // NOTE: We use double-pass encryption when server and client encryption keys are different.
    //       This is recommended to avoid encrypted data to be easily associable when their clear-text
    //       counter-part (e.g. for preferences parameters, file paths, etc.).
    //       Also, we use Blowfish algorithm, which is reasonably fast and produces reasonably long output.

    // Encrypt data

    // ... algorithm and keys
    $mAlgorithm = $this->getStaticParameter( 'php_ape.util.crypto.algorithm.encryption' );
    $sEncryptionKey_Server = $this->getStaticParameter( 'php_ape.util.crypto.key.server' );
    if( empty( $sEncryptionKey_Server ) ) $sEncryptionKey_Server = 'PHP-APE';
    $sEncryptionKey_Client = $this->getStaticParameter( 'php_ape.util.crypto.key.client' );
    if( empty( $sEncryptionKey_Client ) ) $sEncryptionKey_Client = 'PHP-APE';
    $bTwoPassEncryption = $bUseClientEncryption && $sEncryptionKey_Client != $sEncryptionKey_Server;

    // ... server-key encryption
    if( is_scalar( $sData ) )
      $sEncryptedData = PHP_APE_Util_Cryptography::encrypt( 'PHP_APE_DATA#'.$sData, $sEncryptionKey_Server, $mAlgorithm, !$bTwoPassEncryption );
    else 
    {
      $sEncryptedData = $sData;
      foreach( $sEncryptedData as &$rsValue )
        $rsValue = PHP_APE_Util_Cryptography::encrypt( 'PHP_APE_DATA#'.$rsValue, $sEncryptionKey_Server, $mAlgorithm, !$bTwoPassEncryption );
    }

    // ... client-key encryption
    if( $bTwoPassEncryption )
    {
      if( is_scalar( $sEncryptedData ) )
        $sEncryptedData = PHP_APE_Util_Cryptography::encrypt( $sEncryptedData, $sEncryptionKey_Client, $mAlgorithm, $bToHexadecimal );
      else
        foreach( $sEncryptedData as &$rsValue )
          $rsValue = PHP_APE_Util_Cryptography::encrypt( $rsValue, $sEncryptionKey_Client, $mAlgorithm, $bToHexadecimal );
    }

    // Output
    return $sEncryptedData;
  }

  /** Returns the decrypted data for the HTML data page
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>, <SAMP>PHP_APE_Util_CryptographyException</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sEncryptedData Encrypted data
   * @param boolean $bUseClientDecryption Use (two-pass) client decryption
   * @param boolean $bFromHexadecimal Use hexadecical (transport-friendly) decoding
   * @return string
   */
  final public function decryptData( $sEncryptedData, $bUseClientDecryption = true, $bFromHexadecimal = true )
  {
    // NOTE: We use double-pass decryption when server and client encryption keys are different.
    //       See note in encryption method.

    // Decrypt data

    // ... algorithm and keys
    $mAlgorithm = $this->getStaticParameter( 'php_ape.util.crypto.algorithm.encryption' );
    $sDecryptionKey_Server = $this->getStaticParameter( 'php_ape.util.crypto.key.server' );
    if( empty( $sDecryptionKey_Server ) ) $sDecryptionKey_Server = 'PHP-APE';
    $sDecryptionKey_Client = $this->getStaticParameter( 'php_ape.util.crypto.key.client' );
    if( empty( $sDecryptionKey_Client ) ) $sDecryptionKey_Client = 'PHP-APE';
    $bTwoPassDecryption = $bUseClientDecryption && $sDecryptionKey_Client != $sDecryptionKey_Server;

    // ... client-key decryption
    if( $bTwoPassDecryption )
    {
      if( is_scalar( $sEncryptedData ) )
        $sEncryptedData = PHP_APE_Util_Cryptography::decrypt( $sEncryptedData, $sDecryptionKey_Client, $mAlgorithm, $bFromHexadecimal );
      else
        foreach( $sEncryptedData as &$rsValue )
          $rsValue = PHP_APE_Util_Cryptography::decrypt( $rsValue, $sDecryptionKey_Client, $mAlgorithm, $bFromHexadecimal );
    }
    
    // ... server-key decryption
    if( is_scalar( $sEncryptedData ) )
    {
      $sData = PHP_APE_Util_Cryptography::decrypt( $sEncryptedData, $sDecryptionKey_Server, $mAlgorithm, !$bTwoPassDecryption );
      if( substr( $sData, 0, 13 ) != 'PHP_APE_DATA#' )
        throw new PHP_APE_Exception( __METHOD__, 'Invalid/undecryptable data' );
      $sData = substr( $sData, 13 );
    }
    else
    {
      $sData = $sEncryptedData;
      foreach( $sData as &$rsValue )
      {
        $rsValue = PHP_APE_Util_Cryptography::decrypt( $rsValue, $sDecryptionKey_Server, $mAlgorithm, !$bTwoPassDecryption );
        if( substr( $rsValue, 0, 13 ) != 'PHP_APE_DATA#' )
          throw new PHP_APE_Exception( __METHOD__, 'Invalid/undecryptable data' );
        $rsValue = substr( $rsValue, 13 );
      }
    }
    return $sData;
  }


  /*
   * METHODS: static parameters
   ********************************************************************************/

  /** Returns the path of the file (persistent storage location) containing the static parameters
   *
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * @return string
   */
  abstract protected function _getStaticParametersFilepath();

  /** Loads the static parameters from their persistent storage location
   *
   * <P><B>NOTE:</B> This method is automatically called by the <SAMP>{@link __construct()}</SAMP> method.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final private function __loadStaticParameters()
  {

    // Parameters data file
    $sFilePath = $this->_getStaticParametersFilepath();

    // Check cache file
    $bSaveCache = true;
    $sCacheSignature = get_class( $this ).'#'.$sFilePath;
    $sCachePath = PHP_APE_CACHE.'/PHP_APE_Environment#'.sha1( $sCacheSignature ).md5( $sCacheSignature ).'.data';
    if( file_exists( $sCachePath ) and filemtime( $sCachePath ) > filemtime( $sFilePath ) )
    {
      $this->asStaticParameters = unserialize( file_get_contents( $sCachePath, false ) );
      if( is_array( $this->asStaticParameters ) ) $bSaveCache = false;
    }

    // Load, parse and check parameters data
    if( !is_array( $this->asStaticParameters ) )
    {
      require( $sFilePath );
      if( !isset( $_CONFIG ) or !is_array( $_CONFIG ) )
        throw new PHP_APE_Exception( __METHOD__, 'Missing configuration; Path: '.$sFilePath );
      $this->asStaticParameters = $_CONFIG;
      $this->__verifyStaticParameters();
    }

    // Save file cache
    if( $bSaveCache )
    {
      file_put_contents( $sCachePath, serialize( $this->asStaticParameters ), LOCK_EX );
      @chmod( $sCachePath, 0660 );
    }

  }

  /** Returns the array of static parameters that MUST be verified (associating <I>name</I> => <SAMP>null</SAMP>)
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return array|string
   */
  protected function _mandatoryStaticParameters()
  {
    return array(
                 'php_ape.cookie.expiration.days' => null,
                 'php_ape.path.uploaded' => null,
                 'php_ape.util.crypto.algorithm.hash' => null, 'php_ape.util.crypto.algorithm.encryption' => null,
                 'php_ape.util.crypto.key' => null, 'php_ape.util.crypto.key.client' => null, 'php_ape.util.crypto.key.server' => null,
                 'php_ape.util.filesystem.charset' => null
                 );

  }

  /** Verifies the static parameters
   *
   * <P><B>NOTE:</B> This method is automatically called by the <SAMP>{@link __loadStaticParameters()}</SAMP> method.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final private function __verifyStaticParameters()
  {
    if( !is_array( $this->asStaticParameters ) ) $this->asStaticParameters = array();
    $this->asStaticParameters = array_merge( $this->_mandatoryStaticParameters(), $this->asStaticParameters );
    $this->_verifyParameters( $this->asStaticParameters );
  }

  /** Returns the names of the provisioned static parameters
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return array|string
   */
  final public function getStaticParametersNames()
  {
    return array_keys( $this->asStaticParameters );
  }
  
  /** Returns whether the given static parameter value exists
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sName Parameter name
   * @return boolean
   */
  final public function hasStaticParameter( $sName )
  {
    $sName = strtolower( $sName ); // let's be developer-friendly ;-)
    return array_key_exists( $sName, $this->asStaticParameters );
  }
  
  /** Returns the given static parameter value
   *
   * <P><B>RETURNS:</B> <SAMP>null</SAMP> if the parameter is not existing.</P>
   * <P><B>LOGS:</B> <SAMP>WARNING</SAMP> message if the parameter is not existing.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sName Parameter name
   * @return mixed
   */
  final public function getStaticParameter( $sName )
  {
    $sName = strtolower( $sName ); // let's be developer-friendly ;-)
    if( array_key_exists( $sName, $this->asStaticParameters ) ) return $this->asStaticParameters[$sName];
    
    // undefined parameter
    $this->log( __METHOD__, 'Undefined parameter; Name: '.$sName, E_USER_WARNING );
    return null;
  }


  /*
   * METHODS: cookie
   ********************************************************************************/

  /** Returns the name of the cookie related to this environment
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return string
   */
  protected function _getCookieName()
  {
    return get_class( $this );
  }

  /** Loads the environment-related cookie from the HTTP stream (headers)
   *
   * <P><B>NOTE:</B> This method is automatically called by the <SAMP>{@link __construct()}</SAMP> method.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final private function __loadCookie()
  {
    $sCookieName = $this->_getCookieName();
    if( isset( $_COOKIE[ $sCookieName ] ) )
    {
      // Decrypt and unserialize cookie data
      $amCookieData = $_COOKIE[ $sCookieName ];
      $amCookieData = unserialize( $this->decryptData( $amCookieData ) );
      if( !is_array( $amCookieData ) )
        throw new PHP_APE_Exception( __METHOD__, 'Failed to unserialize cookie' );

      // Retrieve cookie data
      if( array_key_exists( 'user_key', $amCookieData ) ) $this->sUserKey = $amCookieData[ 'user_key' ];
    }
  }

  /** Saves the environment-related cookie to the HTTP stream (headers)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function saveCookie()
  {
    // Check HTTP headers
    if( headers_sent() )
      throw new PHP_APE_Exception( __METHOD__, 'Failed to save/send cookie; HTTP header(s) already sent' );

    // Build cookie data
    $amCookieData = array();
    $amCookieData[ 'user_key' ] = $this->sUserKey;

    // Encrypt and save/send cookie
    $sCookieName = $this->_getCookieName();
    $amCookieData = $this->encryptData( serialize( $amCookieData ) );
    if( !setcookie( $sCookieName, $amCookieData, time()+86400*$this->getStaticParameter( 'php_ape.cookie.expiration.days' ), '/' ) )
      throw new PHP_APE_Exception( __METHOD__, 'Failed to save/send cookie' );
  }


  /*
   * METHODS: persistent parameters
   ********************************************************************************/

  /** Returns the path of the file (persistent storage location) containing the persistent parameters
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return string
   */
  protected function _getPersistentParametersFilepath()
  {
    return PHP_APE_DATA.'/'.get_class( $this ).'.data';
  }

  /** Returns the persistent data from their persistent storage location (<B>as reference</B>)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final private function &__loadPersistentData()
  {
    // Load parameters data
    $sFilePath = $this->_getPersistentParametersFilepath();
    $asParameters = file_get_contents( $sFilePath, false );
    if( $asParameters === false )
      throw new PHP_APE_Exception( __METHOD__, 'Failed to load parameters from file; Filepath: '.$sFilePath );
    $asParameters = unserialize( $this->decryptData( $asParameters, false, false ) );
    if( $asParameters === false )
      throw new PHP_APE_Exception( __METHOD__, 'Failed to unserialize parameters; Filepath: '.$sFilePath );

    // Output
    return $asParameters;
  }

  /** Loads the persistent parameters from their persistent storage location
   *
   * <P><B>NOTE:</B> This method is automatically called by the <SAMP>{@link __construct()}</SAMP> method.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final private function __loadPersistentParameters()
  {
    // Load parameters data
    $this->asPersistentParameters =& $this->__loadPersistentData();

    // Check parameters data
    $this->__verifyPersistentParameters();

  }

  /** Returns the array of persistent parameters that MUST be verified (associating <I>name</I> => <SAMP>null</SAMP>)
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return array|string
   */
  protected function _mandatoryPersistentParameters()
  {
    return array();
  }

  /** Verifies the persistent parameters
   *
   * <P><B>NOTE:</B> This method is automatically called by the <SAMP>{@link savePersistentParameters()}</SAMP> method.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final private function __verifyPersistentParameters()
  {
    if( !is_array( $this->asPersistentParameters ) ) $this->asPersistentParameters = array();
    $this->asPersistentParameters = array_merge( $this->_mandatoryPersistentParameters(), $this->asPersistentParameters );
    $this->_verifyParameters( $this->asPersistentParameters );
  }

  /** Returns the names of the provisioned persistent parameters
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return array|string
   */
  final public function getPersistentParametersNames()
  {
    return array_keys( $this->asPersistentParameters );
  }
  
  /** Returns whether the given persistent parameter value exists
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sName Parameter name
   * @return boolean
   */
  final public function hasPersistentParameter( $sName )
  {
    $sName = strtolower( $sName ); // let's be developer-friendly ;-)
    return array_key_exists( $sName, $this->asPersistentParameters );
  }
  
  /** Returns the given persistent parameter value
   *
   * <P><B>RETURNS:</B> <SAMP>null</SAMP> if the parameter is not existing.</P>
   * <P><B>LOGS:</B> <SAMP>WARNING</SAMP> message if the parameter is not existing.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sName Parameter name
   * @param boolean $bHierarchicalLookup Search parameter value in parent environment stores (static, etc.)
   * @return mixed
   */
  final public function getPersistentParameter( $sName, $bHierarchicalLookup = true )
  {
    $sName = strtolower( $sName ); // let's be developer-friendly ;-)
    if( array_key_exists( $sName, $this->asPersistentParameters ) ) return $this->asPersistentParameters[ $sName ];
    if( $bHierarchicalLookup and array_key_exists( $sName, $this->asStaticParameters ) ) return $this->asStaticParameters[ $sName ];

    // undefined parameter
    $this->log( __METHOD__, 'Undefined parameter; Name: '.$sName, E_USER_WARNING );
    return null;
  }

  /** Sets the given persistent parameter value
   *
   * <P><B>NOTE:</B> Parameters must be explicitely saved to retain their value (<SAMP>{@link savePersistentParameters()}</SAMP>).</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sName Parameter name
   * @param mixed $mValue Parameter value
   */
  final public function setPersistentParameter( $sName, $mValue )
  {
    $sName = strtolower( $sName ); // let's be developer-friendly ;-)
    $this->asPersistentParameters[ $sName ] = $mValue;
  }

  /** Unsets the given persistent parameter
   *
   * <P><B>NOTE:</B> Parameters must be explicitely saved to retain their value (<SAMP>{@link savePersistentParameters()}</SAMP>).</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sName Parameter name
   */
  final public function unsetPersistentParameter( $sName )
  {
    $sName = strtolower( $sName ); // let's be developer-friendly ;-)
    $this->asPersistentParameters[ $sName ] = null;
  }

  /** Saves the persistent parameters to their persistent storage location
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function savePersistentParameters()
  {
    // Load and fix parameters data
    try
    { 
      $asUnsetParameters = array_filter( $this->asPersistentParameters, 'is_null' );
      $asParameters = @$this->__loadPersistentData();
      $asParameters = array_merge( array_diff_key( $asParameters, $asUnsetParameters ), array_diff_key( $this->asPersistentParameters, $asUnsetParameters ) );
    }
    catch( PHP_APE_Exception $e )
    {
      $asParameters =& $this->asPersistentParameters;
    }

    // Save parameters data
    $sFilePath = $this->_getPersistentParametersFilepath();
    $asParameters = $this->encryptData( serialize( $asParameters ), false, false );
    $bChangeMode = !file_exists( $sFilePath );
    if( file_put_contents( $sFilePath, $asParameters, LOCK_EX ) != strlen( $asParameters ) )
      throw new PHP_APE_Exception( __METHOD__, 'Failed to save parameters to file; Filepath: '.$sFilePath );
    if( $bChangeMode and !chmod( $sFilePath, 0660 ) )
      throw new PHP_APE_Exception( __METHOD__, 'Failed to change file permissions; Filepath: '.$sFilePath );
  }


  /*
   * METHODS: session parameters
   ********************************************************************************/

  /** Returns the name of the session variable related to this environment
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return string
   */
  protected function _getSessionName()
  {
    return get_class( $this );
  }

  /** Returns the session data from their session variable (<B>as reference</B>)
   *
   * <P><B>NOTE:</B> You MUST have started the PHP session (<SAMP>{@link session_start()}</SAMP>) beforehand.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final private function &__loadSessionData()
  {
    // Check session
    if( !isset( $_SESSION ) )
      throw new PHP_APE_Exception( __METHOD__, 'No PHP session' );
    $sSessionName = $this->_getSessionName();

    // Load parameters data
    if( array_key_exists( $sSessionName, $_SESSION ) )
    {
      $asParameters = $_SESSION[$sSessionName];
      $asParameters = unserialize( $this->decryptData( $asParameters, false, false ) );
      if( $asParameters === false )
        throw new PHP_APE_Exception( __METHOD__, 'Failed to unserialize parameters' );
    }
    else
      $asParameters = array();

    // Output
    return $asParameters;
  }

  /** Loads the session parameters from their session variable
   *
   * <P><B>NOTE:</B> This method is automatically called by the <SAMP>{@link __construct()}</SAMP> method.</P>
   * <P><B>NOTE:</B> You MUST have started the PHP session (<SAMP>{@link session_start()}</SAMP>) beforehand.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final private function __loadSessionParameters()
  {
    // Load parameters data
    $this->asSessionParameters =& $this->__loadSessionData();

    // Check parameters data
    $this->__verifySessionParameters();
  }

  /** Returns the array of session parameters that MUST be verified (associating <I>name</I> => <SAMP>null</SAMP>)
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return array|string
   */
  protected function _mandatorySessionParameters()
  {
    return array();
  }

  /** Verifies the session parameters
   *
   * <P><B>NOTE:</B> This method is automatically called by the <SAMP>{@link saveSessionParameters()}</SAMP> method.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final private function __verifySessionParameters()
  {
    if( !is_array( $this->asSessionParameters ) ) $this->asSessionParameters = array();
    $this->asSessionParameters = array_merge( $this->_mandatorySessionParameters(), $this->asSessionParameters );
    $this->_verifyParameters( $this->asSessionParameters );
  }

  /** Returns the names of the provisioned session parameters
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return array|string
   */
  final public function getSessionParametersNames()
  {
    return array_keys( $this->asSessionParameters );
  }
  
  /** Returns whether the given session parameter value exists
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sName Parameter name
   * @return boolean
   */
  final public function hasSessionParameter( $sName )
  {
    $sName = strtolower( $sName ); // let's be developer-friendly ;-)
    return array_key_exists( $sName, $this->asSessionParameters );
  }
  
  /** Returns the given session parameter value
   *
   * <P><B>RETURNS:</B> <SAMP>null</SAMP> if the parameter is not existing.</P>
   * <P><B>LOGS:</B> <SAMP>WARNING</SAMP> message if the parameter is not existing.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sName Parameter name
   * @param boolean $bHierarchicalLookup Search parameter value in parent environment stores (static, etc.)
   * @return mixed
   */
  final public function getSessionParameter( $sName, $bHierarchicalLookup = true )
  {
    $sName = strtolower( $sName ); // let's be developer-friendly ;-)
    if( array_key_exists( $sName, $this->asSessionParameters ) ) return $this->asSessionParameters[ $sName ];
    if( $bHierarchicalLookup )
    {
      if( array_key_exists( $sName, $this->asPersistentParameters ) ) return $this->asPersistentParameters[ $sName ];
      if( array_key_exists( $sName, $this->asStaticParameters ) ) return $this->asStaticParameters[ $sName ];
    }

    // undefined parameter
    $this->log( __METHOD__, 'Undefined parameter; Name: '.$sName, E_USER_WARNING );
    return null;
  }

  /** Sets the given session parameter value
   *
   * <P><B>NOTE:</B> Parameters must be explicitely saved to retain their value (<SAMP>{@link saveSessionParameters()}</SAMP>).</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sName Parameter name
   * @param mixed $mValue Parameter value
   */
  final public function setSessionParameter( $sName, $mValue )
  {
    $sName = strtolower( $sName ); // let's be developer-friendly ;-)
    $this->asSessionParameters[ $sName ] = $mValue;
  }

  /** Unsets the given session parameter
   *
   * <P><B>NOTE:</B> Parameters must be explicitely saved to retain their value (<SAMP>{@link saveSessionParameters()}</SAMP>).</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sName Parameter name
   */
  final public function unsetSessionParameter( $sName )
  {
    $sName = strtolower( $sName ); // let's be developer-friendly ;-)
    $this->asSessionParameters[ $sName ] = null;
  }

  /** Saves the session parameters to their session storage location
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function saveSessionParameters()
  {
    // Load and fix parameters data
    try
    { 
      $asUnsetParameters = array_filter( $this->asSessionParameters, 'is_null' );
      $asParameters = @$this->__loadSessionData();
      $asParameters = array_merge( array_diff_key( $asParameters, $asUnsetParameters ), array_diff_key( $this->asSessionParameters, $asUnsetParameters ) );
    }
    catch( PHP_APE_Exception $e )
    {
      $asParameters =& $this->asSessionParameters;
    }

    // Save parameters data
    $sSessionName = $this->_getSessionName();
    $asParameters = $this->encryptData( serialize( $asParameters ), false, false );
    $_SESSION[$sSessionName] = $asParameters;
  }


  /*
   * METHODS: volatile parameters
   ********************************************************************************/

  /** Returns the array of volatile parameters that MUST be verified (associating <I>name</I> => <SAMP>null</SAMP>)
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return array|string
   */
  protected function _mandatoryVolatileParameters()
  {
    return array();
  }

  /** Verifies the volatile parameters
   *
   * <P><B>NOTE:</B> This method is automatically called by the <SAMP>{@link setVolatileParameter()}</SAMP> method.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final private function __verifyVolatileParameters()
  {
    if( !is_array( $this->asVolatileParameters ) ) $this->asVolatileParameters = array();
    $this->asVolatileParameters = array_merge( $this->_mandatoryVolatileParameters(), $this->asVolatileParameters );
    $this->_verifyParameters( $this->asVolatileParameters );
  }

  /** Returns the names of the provisioned volatile parameters
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return array|string
   */
  final public function getVolatileParametersNames()
  {
    return array_keys( $this->asVolatileParameters );
  }
  
  /** Returns whether the given volatile parameter value exists
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sName Parameter name
   * @return boolean
   */
  final public function hasVolatileParameter( $sName )
  {
    $sName = strtolower( $sName ); // let's be developer-friendly ;-)
    return array_key_exists( $sName, $this->asVolatileParameters );
  }

  /** Returns the given volatile parameter value
   *
   * <P><B>RETURNS:</B> <SAMP>null</SAMP> if the parameter is not existing.</P>
   * <P><B>LOGS:</B> <SAMP>WARNING</SAMP> message if the parameter is not existing.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sName Parameter name
   * @param boolean $bHierarchicalLookup Search parameter value in parent environment stores (static, etc.)
   * @return mixed
   */
  final public function getVolatileParameter( $sName, $bHierarchicalLookup = true )
  {
    $sName = strtolower( $sName ); // let's be developer-friendly ;-)
    if( array_key_exists( $sName, $this->asVolatileParameters ) ) return $this->asVolatileParameters[ $sName ];
    if( $bHierarchicalLookup )
    {
      if( array_key_exists( $sName, $this->asSessionParameters ) ) return $this->asSessionParameters[ $sName ];
      if( array_key_exists( $sName, $this->asPersistentParameters ) ) return $this->asPersistentParameters[ $sName ];
      if( array_key_exists( $sName, $this->asStaticParameters ) ) return $this->asStaticParameters[ $sName ];
    }

    // undefined parameter
    $this->log( __METHOD__, 'Undefined parameter; Name: '.$sName, E_USER_WARNING );
    return null;
  }

  /** Sets the given volatile parameter value
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sName Parameter name
   * @param mixed $mValue Parameter value
   */
  final public function setVolatileParameter( $sName, $mValue )
  {
    $sName = strtolower( $sName ); // let's be developer-friendly ;-)
    $this->asVolatileParameters[ $sName ] = $mValue;
  }

  /** Unsets (clears) the given volatile parameter
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sName Parameter name
   */
  final public function unsetVolatileParameter( $sName )
  {
    $sName = strtolower( $sName ); // let's be developer-friendly ;-)
    unset( $this->asVolatileParameters[ $sName ] );
  }


  /*
   * METHODS: user parameters
   ********************************************************************************/

  /** Returns the path of the file (persistent storage location) containing the user parameters
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return string
   */
  protected function _getUserParametersFilepath()
  {
    return $this->getStaticParameter( 'php_ape.path.environment' ).'/'.get_class( $this ).'.USER#'.$this->sUserKey.'.data';
  }

  /** Returns the user data from their user storage location (<B>as reference</B>)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final private function &__loadUserData()
  {
    // Load parameters data
    $sFilePath = $this->_getUserParametersFilepath();
    $asParameters = file_get_contents( $sFilePath, false );
    if( $asParameters === false )
      throw new PHP_APE_Exception( __METHOD__, 'Failed to load parameters from file; Filepath: '.$sFilePath );
    $asParameters = unserialize( $this->decryptData( $asParameters, false ) );
    if( $asParameters === false )
      throw new PHP_APE_Exception( __METHOD__, 'Failed to unserialize parameters; Filepath: '.$sFilePath );

    // Output
    return $asParameters;
  }

  /** Loads the user parameters from their user storage location
   *
   * <P><B>NOTE:</B> This method is automatically called by the <SAMP>{@link __construct()}</SAMP> method.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final private function __loadUserParameters()
  {
    // Load parameters data
    $this->asUserParameters =& $this->__loadUserData();

    // Check parameters data
    $this->__verifyUserParameters();
  }

  /** Returns the array of user parameters that MUST be verified (associating <I>name</I> => <SAMP>null</SAMP>)
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return array|string
   */
  protected function _mandatoryUserParameters()
  {
    return array();
  }

  /** Verifies the user parameters
   *
   * <P><B>NOTE:</B> This method is automatically called by the <SAMP>{@link __construct()}</SAMP> method.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final private function __verifyUserParameters()
  {
    if( !is_array( $this->asUserParameters ) ) $this->asUserParameters = array();
    $this->asUserParameters = array_merge( $this->_mandatoryUserParameters(), $this->asUserParameters );
    $this->_verifyParameters( $this->asUserParameters );
  }


  /** Returns the names of the provisioned user parameters
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return array|string
   */
  final public function getUserParametersNames()
  {
    return array_keys( $this->asUserParameters );
  }
  
  /** Returns whether the given user parameter value exists
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sName Parameter name
   * @return boolean
   */
  final public function hasUserParameter( $sName )
  {
    $sName = strtolower( $sName ); // let's be developer-friendly ;-)
    return array_key_exists( $sName, $this->asUserParameters );
  }
  
  /** Returns the given user parameter value
   *
   * <P><B>RETURNS:</B> <SAMP>null</SAMP> if the parameter is not existing.</P>
   * <P><B>LOGS:</B> <SAMP>WARNING</SAMP> message if the parameter is not existing.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sName Parameter name
   * @param boolean $bHierarchicalLookup Search parameter value in parent environment stores (static, etc.)
   * @return mixed
   */
  final public function getUserParameter( $sName, $bHierarchicalLookup = true )
  {
    $sName = strtolower( $sName ); // let's be developer-friendly ;-)
    if( array_key_exists( $sName, $this->asUserParameters ) ) return $this->asUserParameters[ $sName ];
    if( $bHierarchicalLookup )
    {
      if( array_key_exists( $sName, $this->asVolatileParameters ) ) return $this->asVolatileParameters[ $sName ];
      if( array_key_exists( $sName, $this->asSessionParameters ) ) return $this->asSessionParameters[ $sName ];
      if( array_key_exists( $sName, $this->asPersistentParameters ) ) return $this->asPersistentParameters[ $sName ];
      if( array_key_exists( $sName, $this->asStaticParameters ) ) return $this->asStaticParameters[ $sName ];
    }

    // undefined parameter
    $this->log( __METHOD__, 'Undefined parameter; Name: '.$sName, E_USER_WARNING );
    return null;
  }

  /** Sets the given user parameter value
   *
   * <P><B>NOTE:</B> Parameters must be explicitely saved to retain their value (<SAMP>{@link saveUserParameters()}</SAMP>).</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sName Parameter name
   * @param mixed $mValue Parameter value
   */
  final public function setUserParameter( $sName, $mValue )
  {
    $sName = strtolower( $sName ); // let's be developer-friendly ;-)
    $this->asUserParameters[ $sName ] = $mValue;
  }

  /** Unsets (clears) the given user parameter
   *
   * <P><B>NOTE:</B> Parameters must be explicitely saved to retain their value (<SAMP>{@link saveUserParameters()}</SAMP>).</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sName Parameter name
   */
  final public function unsetUserParameter( $sName )
  {
    $sName = strtolower( $sName ); // let's be developer-friendly ;-)
    $this->asUserParameters[ $sName ] = null;
  }

  /** Saves the user parameters to their user storage location
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function saveUserParameters()
  {
    // Save cookie (since we depend on its content for user parameters)
    $this->saveCookie();

    // Load and fix parameters data
    try
    { 
      $asUnsetParameters = array_filter( $this->asUserParameters, 'is_null' );
      $asParameters = @$this->__loadUserData();
      $asParameters = array_merge( array_diff_key( $asParameters, $asUnsetParameters ), array_diff_key( $this->asUserParameters, $asUnsetParameters ) );
    }
    catch( PHP_APE_Exception $e )
    {
      $asParameters =& $this->asUserParameters;
    }

    // Save parameters data
    $sFilePath = $this->_getUserParametersFilepath();
    $asParameters = $this->encryptData( serialize( $asParameters ), false );
    $bChangeMode = !file_exists( $sFilePath );
    if( file_put_contents( $sFilePath, $asParameters, LOCK_EX ) != strlen( $asParameters ) )
      throw new PHP_APE_Exception( __METHOD__, 'Failed to save parameters to file; Filepath: '.$sFilePath );
    if( $bChangeMode and !chmod( $sFilePath, 0660 ) )
      throw new PHP_APE_Exception( __METHOD__, 'Failed to change file permissions; Filepath: '.$sFilePath );
  }

}

