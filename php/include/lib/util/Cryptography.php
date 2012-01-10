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

/** Cryptography-related utilities
 *
 * <P><B>NOTE:</B> This class relies on PHP <B>mcrypt</B> and <B>mhash</B> resources.</P>
 *
 * @package PHP_APE_Util
 * @subpackage Miscellaneous
 */
class PHP_APE_Util_Cryptography
extends PHP_APE_Util_Any
{
  // TODO: encrypt Initialization Vector (IV) before prepending it to the encrypted data.

  /*
   * METHODS: static
   ********************************************************************************/

  /** Hashes (one-way encryption) the given data
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Util_CryptographyException</SAMP>.</P>
   *
   * @param string $sData Data to hashed
   * @param mixed $mAlgorithm Hashing algorithm name (if <SAMP>null</SAMP>, retrieved from the <SAMP>{@link PHP_APE_WorkSpace}</SAMP> environment)
   * @param boolean $bToHexadecimal Use hexadecical encoding
   * @param string $sKey Hashing key
   * @return string
   */
  public static function hash( $sData, $mAlgorithm = null, $bToHexadecimal = true, $sKey = null )
  {
    // Check input
    if( !is_scalar( $sData ) )
      throw new PHP_APE_Util_CryptographyException( __METHOD__, 'Invalid (non-scalar) data' );
    if( !is_null( $sKey ) and !is_scalar( $sKey ) )
      throw new PHP_APE_Util_CryptographyException( __METHOD__, 'Invalid (non-scalar) hashing key' );

    // Retrieve algorithm
    if( !is_scalar( $mAlgorithm ) or empty( $mAlgorithm ) ) $mAlgorithm = PHP_APE_WorkSpace::useEnvironment()->getStaticParameter( 'php_ape.util.crypto.algorithm.hash' );
    $mAlgorithm = strtolower( $mAlgorithm );

    // Hash data
    $sData = mhash( $mAlgorithm, $sData, $sKey );
    if( $sData === false )
      throw new PHP_APE_Util_CryptographyException( __METHOD__, 'Failed to hash data; Algorithm: '.$mAlgorithm );

    // Encode to hexadecimal
    if( $bToHexadecimal )
    {
      $sData_HEX = '';
      for( $i = 0, $l = strlen( $sData ); $i< $l; $i++ ) $sData_HEX .= substr( '0'.dechex( ord( $sData[$i] ) ), -2 );
      $sData = $sData_HEX;
    }

    // End
    return $sData;
  }

  /** Encrypts the given data, using the given key
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Util_CryptographyException</SAMP>.</P>
   *
   * @param string $sData Data to be encrypted
   * @param string $sKey Encryption key
   * @param mixed $mAlgorithm Encryption algorithm name (if <SAMP>null</SAMP>, retrieved from the <SAMP>{@link PHP_APE_WorkSpace}</SAMP> environment)
   * @param boolean $bToHexadecimal Use hexadecimal (transport-friendly) encoding
   * @return string
   */
  public static function encrypt( $sData, $sKey, $mAlgorithm = null, $bToHexadecimal = false )
  {
    // Check input
    if( !is_scalar( $sData ) )
      throw new PHP_APE_Util_CryptographyException( __METHOD__, 'Invalid (non-scalar) data' );
    if( !is_scalar( $sKey ) )
      throw new PHP_APE_Util_CryptographyException( __METHOD__, 'Invalid (non-scalar) encryption key' );
    if( empty( $sKey ) )
      throw new PHP_APE_Util_CryptographyException( __METHOD__, 'Missing (empty) encryption key' );

    // Retrieve algorithm
    if( !is_scalar( $mAlgorithm ) or empty( $mAlgorithm ) ) $mAlgorithm = PHP_APE_WorkSpace::useEnvironment()->getStaticParameter( 'php_ape.util.crypto.algorithm.encryption' );
    $mAlgorithm = strtolower( $mAlgorithm );

    // Encrypt data

    // ... retrieve encryption resource
    $rMcryptResource = mcrypt_module_open( $mAlgorithm, '', 'cbc', '' );
    if( $rMcryptResource === false )
    {
      throw new PHP_APE_Util_CryptographyException( __METHOD__, 'Failed to open cryptographic module; Algorithm: '.$mAlgorithm );
    }

    // ... retrieve initialzation vector (IV)
    srand(); // seed random number generator
    $sInitializationVector = mcrypt_create_iv( mcrypt_enc_get_iv_size( $rMcryptResource ), MCRYPT_RAND );

    // ... retrieve algorithm-compatible encryption key
    $sKey = substr( sha1( $sKey ), 0, mcrypt_enc_get_key_size( $rMcryptResource ) );

    // ... initialize encryption resource
    $iResult = mcrypt_generic_init( $rMcryptResource, $sKey, $sInitializationVector );
    if( $iResult === false or $iResult < 0 )
    {
      throw new PHP_APE_Util_CryptographyException( __METHOD__, 'Failed to initialize cryptographic module; Algorithm: '.$mAlgorithm );
    }
    
    // ... encrypt data
    $sData = $sInitializationVector.mcrypt_generic( $rMcryptResource, $sData ); // NOTE: original string is right-padded with '\0' to match algorithm block size

    // ... dispose encryption resource
    mcrypt_generic_deinit( $rMcryptResource );
    mcrypt_module_close( $rMcryptResource );

    // Encode to hexadecimal
    if( $bToHexadecimal )
    {
      $sData_HEX = '';
      for( $i = 0, $l = strlen( $sData ); $i< $l; $i++ ) $sData_HEX .= substr( '0'.dechex( ord( $sData[$i] ) ), -2 );
      $sData = $sData_HEX;
    }

    // End
    return $sData;
  }

  /** Decrypts the given data, using the given key
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Util_CryptographyException</SAMP>.</P>
   *
   * @param string $sData Data to be decrypted
   * @param string $sKey Decryption key
   * @param mixed $mAlgorithm Decryption algorithm name (if <SAMP>null</SAMP>, retrieved from the <SAMP>{@link PHP_APE_WorkSpace}</SAMP> environment)
   * @param boolean $bFromHexadecimal Use hexadecical (transport-friendly) decoding
   * @return string
   */
  public static function decrypt( $sData, $sKey, $mAlgorithm = null, $bFromHexadecimal = false )
  {
    // Check input
    if( !is_scalar( $sData ) )
      throw new PHP_APE_Util_CryptographyException( __METHOD__, 'Invalid (non-scalar) data' );
    if( !is_scalar( $sKey ) )
      throw new PHP_APE_Util_CryptographyException( __METHOD__, 'Invalid (non-scalar) decryption key' );
    if( empty( $sKey ) )
      throw new PHP_APE_Util_CryptographyException( __METHOD__, 'Missing (empty) decryption key' );

    // Retrieve algorithm
    if( !is_scalar( $mAlgorithm ) or empty( $mAlgorithm ) ) $mAlgorithm = PHP_APE_WorkSpace::useEnvironment()->getStaticParameter( 'php_ape.util.crypto.algorithm.encryption' );
    $mAlgorithm = strtolower( $mAlgorithm );


    // Decode from hexadecimal
    if( $bFromHexadecimal )
    {
      $sData_CHR = '';
      for( $i = 0, $l = strlen( $sData ); $i< $l; $i+=2 ) $sData_CHR .= chr( hexdec( substr( $sData, $i, 2 ) ) );
      $sData = $sData_CHR;
    }

    // Decrypt data

    // ... retrieve decryption resource
    $rMcryptResource = mcrypt_module_open( $mAlgorithm, '', 'cbc', '' );
    if( $rMcryptResource === false )
    {
      throw new PHP_APE_Util_CryptographyException( __METHOD__, 'Failed to open cryptographic module; Algorithm: '.$mAlgorithm );
    }

    // ... retrieve initialzation vector (IV)
    srand(); // seed random number generator
    $sInitializationVectorSize = mcrypt_enc_get_iv_size( $rMcryptResource );
    $sInitializationVector = substr( $sData, 0, $sInitializationVectorSize );
    $sData = substr( $sData, $sInitializationVectorSize );

    // ... retrieve algorithm-compatible decryption key
    $sKey = substr( sha1( $sKey ), 0, mcrypt_enc_get_key_size( $rMcryptResource ) );

    // ... initialize decryption resource
    $iResult = mcrypt_generic_init( $rMcryptResource, $sKey, $sInitializationVector );
    if( $iResult === false or $iResult < 0 )
    {
      throw new PHP_APE_Util_CryptographyException( __METHOD__, 'Failed to initialize cryptographic module; Algorithm: '.$mAlgorithm );
    }
    
    // ... decrypt data
    $sData = mdecrypt_generic( $rMcryptResource, $sData );

    // ... dispose decryption resource
    mcrypt_generic_deinit( $rMcryptResource );
    mcrypt_module_close( $rMcryptResource );

    // End
    return rtrim( $sData, "\0" ); // NOTE: decrypted string is right-padded with '\0' to match algorithm block size
  }

}
