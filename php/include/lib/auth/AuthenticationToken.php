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
 * @subpackage Classes
 */

/** Authentication token
 *
 * @package PHP_APE_Auth
 * @subpackage Classes
 */
class PHP_APE_Auth_AuthenticationToken
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Key
   * @var string */
  private $sKey;

  /** User identifier (login name)
   * @var string */
  private $sUserID;

  /** User's password (encrypted)
   * @var string */
  private $sUserPassword;

  /** Groups identifiers
   * @var string */
  private $asGroupIDs;

  /** User's path
   * @var string */
  private $sUserPath;

  /** User's full name
   * @var string */
  private $sUserFullName;

  /** User's e-mail address
   * @var string */
  private $sUserEmail;

  /** User's phone number
   * @var string */
  private $sUserPhone;

  /** User's office (location)
   * @var string */
  private $sUserOffice;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new authentication token
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Auth_AuthenticationException</SAMP>.</P>
   *
   * @param string $sUserID Associated user identifier (login name) - <B>mandatory</B>
   * @param string $sUserPassword Associated user's password
   * @param string $asGroupIDs Associated groups identifiers
   * @param string $sUserPath Associated user's data path (home directory)
   * @param string $sUserFullName Associated user's full name
   * @param string $sUserEmail Associated user's e-mail address
   * @param string $sUserPhone Associated user's phone number
   * @param string $sUserOffice Associated user's office (location)
   */
  public function __construct( $sUserID, $sUserPassword = null, $asGroupIDs = null, $sUserPath = null, $sUserFullName = null, $sUserEmail = null, $sUserPhone = null, $sUserOffice = null )
  {
    // Sanitize input
    $sUserID = trim( PHP_APE_Type_String::parseValue( $sUserID ) );
    if( $sUserID != 'anonymous' ) {
      $sUserIDPattern = PHP_APE_Auth_WorkSpace::useEnvironment()->getStaticParameter( 'php_ape.auth.token.userid.pattern' );
      if( !empty( $sUserIDPattern ) )
        $sUserID = preg_replace( $sUserIDPattern, '${1}', $sUserID );
    }
    if( empty( $sUserID ) )
      throw new PHP_APE_Auth_AuthenticationException( __METHOD__, 'Empty user name (login name)' );
    $asGroupIDs = PHP_APE_Type_Array::parseValue( $asGroupIDs );
    foreach( $asGroupIDs as &$rsGroupID ) $rsGroupID = trim( PHP_APE_Type_String::parseValue( $rsGroupID ) );
    $sUserPath = trim( PHP_APE_Type_Path::parseValue( $sUserPath ) );
    $sUserFullName = trim( PHP_APE_Type_String::parseValue( $sUserFullName ) );
    $sUserEmail = trim( PHP_APE_Type_String::parseValue( $sUserEmail ) );
    $sUserPhone = trim( PHP_APE_Type_String::parseValue( $sUserPhone ) );
    $sUserOffice = trim( PHP_APE_Type_String::parseValue( $sUserOffice ) );
    
    // Save member fields
    $this->sKey = sha1( rand() );
    $this->sUserID = $sUserID;
    $this->sUserPassword = is_scalar( $sUserPassword ) ? PHP_APE_Auth_WorkSpace::useEnvironment()->encryptData( $sUserPassword, false ) : null;
    $this->asGroupIDs = $asGroupIDs;
    $this->sUserPath = $sUserPath;
    $this->sUserFullName = $sUserFullName;
    $this->sUserEmail = $sUserEmail;
    $this->sUserPhone = $sUserPhone;
    $this->sUserOffice = $sUserOffice;
  }


  /*
   * METHODS
   ********************************************************************************/

  /** Returns this authentication token's key
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getKey()
  {
    return $this->sKey;
  }

  /** Returns this authentication token's associated user identifier (login name)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getUserID()
  {
    return $this->sUserID;
  }

  /** Returns this authentication token's associated user password
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getUserPassword()
  {
    return isset( $this->sUserPassword ) ? PHP_APE_Auth_WorkSpace::useEnvironment()->decryptData( $this->sUserPassword, false ) : null;
  }

  /** Returns this authentication token's associated groups identifiers (groups name)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return array|string
   */
  final public function getGroupIDs()
  {
    return $this->asGroupIDs;
  }

  /** Returns this authentication token's associated user's data path (home directory)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getUserPath()
  {
    return $this->sUserPath;
  }

  /** Returns this authentication token's associated user's full name
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getUserFullName()
  {
    return $this->sUserFullName;
  }

  /** Returns this authentication token's associated user's e-mail address
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getUserEmail()
  {
    return $this->sUserEmail;
  }

  /** Returns this authentication token's associated user's phone number
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getUserPhone()
  {
    return $this->sUserPhone;
  }

  /** Returns this authentication token's associated user's office location
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getUserOffice()
  {
    return $this->sUserOffice;
  }

  /** Returns whether this authentication token matches the supplied ACL
   *
   * <P><B>NOTE:</B> The ACL must be supplied as an array of user and group IDs (the latter
   * being prefix by the arrobase ('@') character.</P>
   *
   * @param string $asIDs
   * @return boolean
   */
  final public function isMatchingACL( $asIDs )
  {
    // Sanitize input
    $asIDs = PHP_APE_Type_Array::parseValue( $asIDs );
    if( empty( $asIDs ) ) return false;

    // Split users and groups
    $asUserIDs = array();
    $asGroupIDs = array();
    foreach( $asIDs as $sID )
    {
      $sID = PHP_APE_Type_String::parseValue( $sID );
      if( empty( $sID ) ) continue;
      if( $sID{0} == '@' ) array_push( $asGroupIDs, substr( $sID, 1 ) );
      else array_push( $asUserIDs, $sID );
    }
    $asUserIDs = array_unique( $asUserIDs );
    $asGroupIDs = array_unique( $asGroupIDs );

    // Match users
    if( in_array( $this->getUserID(), $asUserIDs ) ) return true;

    // Match groups
    if( count( array_intersect( $this->getGroupIDs(), $asGroupIDs ) ) > 0 ) return true;

    // NO MATCH
    return false;
  }

}
