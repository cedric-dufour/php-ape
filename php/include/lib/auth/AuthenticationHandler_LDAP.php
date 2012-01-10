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

/** LDAP-based authentication handler
 *
 * <P><B>USAGE:</B></P>
 * <P>This handler use the following configuration parameters:</P>
 * <UL>
 * <LI><SAMP>php_ape.auth.ldap.host</SAMP>: LDAP server host name [default: '<SAMP>ldap://127.0.0.1</SAMP>']</LI>
 * <LI><SAMP>php_ape.auth.ldap.port</SAMP>: LDAP server port [default: <SAMP>389</SAMP>]</LI>
 * <LI><SAMP>php_ape.auth.ldap.timeout</SAMP>: query (search) timeout [seconds; default: <SAMP>5</SAMP>]</LI>
 * <LI><SAMP>php_ape.auth.ldap.bind.dn</SAMP>: bind directory name [default: <SAMP>null</SAMP>]</LI>
 * <LI><SAMP>php_ape.auth.ldap.bind.password</SAMP>: bind password [default: <SAMP>null</SAMP>]</LI>
 * <LI><SAMP>php_ape.auth.ldap.user.basedn</SAMP>: users base directory name [default: <SAMP>null</SAMP>]</LI>
 * <LI><SAMP>php_ape.auth.ldap.user.filter</SAMP>: users search filter [default: <SAMP>null</SAMP>]</LI>
 * <LI><SAMP>php_ape.auth.ldap.user.attr</SAMP>: users data attributes [default: array( <SAMP><data></SAMP> => <SAMP><attr></SAMP> ); <I>see below</I>]</LI>
 * <LI><SAMP>php_ape.auth.ldap.group.basedn</SAMP>: groups base directory name [default: <SAMP>null</SAMP>]</LI>
 * <LI><SAMP>php_ape.auth.ldap.group.filter</SAMP>: secondary groups search filter [default: <SAMP>null</SAMP>]</LI>
 * <LI><SAMP>php_ape.auth.ldap.group.attr</SAMP>: groups data attributes [default: array( <SAMP><data></SAMP> => <SAMP><attr></SAMP> ); <I>see below</I>]</LI>
 * <LI><SAMP>php_ape.auth.ldap.charset</SAMP>: LDAP character set (encoding) [default: '<SAMP>UTF-8</SAMP>']</LI>
 * </UL>
 * <BR/>
 * <P><B>NOTE:</B> user authentication</P>
 * <P>If the user base directory name (<SAMP>php_ape.auth.ldap.user.basedn</SAMP>) is left empty, it will be
 * assumed that the (then mandatory) bind directory name (<SAMP>php_ape.auth.ldap.bind.dn</SAMP>) corresponds
 * to the user directory name. Otherwise, the user directory name will be searched within the user base directory
 * name, using the mandatory search filter (<SAMP>php_ape.auth.ldap.user.filter</SAMP>).</P>
 * <BR/>
 * <P><B>NOTE:</B> groups retrieval</P>
 * <P>If supplied, the group base directory name (<SAMP>php_ape.auth.ldap.group.basedn</SAMP>) will be searched,
 * using the primary group's identifier attribute (see below) and/or the secondary groups search filter
 * (<SAMP>php_ape.auth.ldap.group.filter</SAMP>), to retrieve groups to which the user belongs.</P>
 * <BR/>
 * <P><B>NOTE:</B> user attributes</P>
 * <P>The following default attributes are provisioned when retrieving user data:</P>
 * <UL>
 * <LI><SAMP>user.id</SAMP> => <SAMP>uid</SAMP>: user identifier (user/login name) (<B>mandatory</B>)</LI>
 * <LI><SAMP>user.uid</SAMP> => <SAMP>uidNumber</SAMP>: user's (unique) identifier</LI>
 * <LI><SAMP>user.gid</SAMP> => <SAMP>gidNumber</SAMP>: user's primary group (unique) identifier</LI>
 * <LI><SAMP>user.path</SAMP> => <SAMP>homeDirectory</SAMP>: user's data path (home directory)</LI>
 * <LI><SAMP>user.fullname</SAMP> => <SAMP>cn</SAMP>: user's full name</LI>
 * <LI><SAMP>user.email</SAMP> => <SAMP>mail</SAMP>: user's e-mail address</LI>
 * <LI><SAMP>user.phone</SAMP> => <SAMP>phone</SAMP>: user's phone number</LI>
 * <LI><SAMP>user.office</SAMP> => <SAMP>office</SAMP>: user's office (location)</LI>
 * </UL>
 * <P>Note that the data will be ignored if the corresponding attribute is set to an <I>empty</I> value.</P>
 * <BR/>
 * <P><B>NOTE:</B> group attributes</P>
 * <P>The following default attributes are provisioned when retrieving group data:</P>
 * <UL>
 * <LI><SAMP>group.id</SAMP> => <SAMP>cn</SAMP>: group identifier (group name) (<B>mandatory</B>)</LI>
 * <LI><SAMP>group.gid</SAMP> => <SAMP>gidNumber</SAMP>: group's (unique) identifier</LI>
 * </UL>
 * <BR/>
 * <P><B>NOTE:</B> username substitution</P>
 * <P>The magic anchor <SAMP>@{username}</SAMP> will be replaced with the authenticating username
 * in the <SAMP>php_ape.auth.ldap.bind.dn</SAMP>, <SAMP>php_ape.auth.ldap.user.filter</SAMP> and
 * <SAMP>php_ape.auth.ldap.group.filter</SAMP> parameters.</P>
 *
 * @package PHP_APE_Auth
 * @subpackage Handler
 */
class PHP_APE_Auth_AuthenticationHandler_LDAP
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

    // Retrieved LDAP configuration

    // ... host
    $sHost =
      $roEnvironment->hasStaticParameter( 'php_ape.auth.ldap.host' ) ?
      PHP_APE_Type_String::parseValue( $roEnvironment->getStaticParameter( 'php_ape.auth.ldap.host' ) ) :
      'ldap://127.0.0.1';

    // ... port
    $iPort = 
      $roEnvironment->hasStaticParameter( 'php_ape.auth.ldap.port' ) ? 
      PHP_APE_Type_Integer::parseValue( $roEnvironment->getStaticParameter( 'php_ape.auth.ldap.port' ) ) :
      389;
    if( $iPort < 1 or $iPort > 65535 )
      throw new PHP_APE_Auth_Exception( __METHOD__, 'Invalid server port' );

    // ... time-out
    $iTimeOut = 
      $roEnvironment->hasStaticParameter( 'php_ape.auth.ldap.timeout' ) ? 
      PHP_APE_Type_Integer::parseValue( $roEnvironment->getStaticParameter( 'php_ape.auth.ldap.timeout' ) ) :
      5;
    if( $iTimeOut < 1 or $iTimeOut > 60 )
      throw new PHP_APE_Auth_Exception( __METHOD__, 'Invalid time-out' );

    // ... bind DN
    $sBind_DN = 
      $roEnvironment->hasStaticParameter( 'php_ape.auth.ldap.bind.dn' ) ?
      PHP_APE_Type_String::parseValue( $roEnvironment->getStaticParameter( 'php_ape.auth.ldap.bind.dn' ) ) :
      null;
    $sBind_DN = str_ireplace( '@{username}', $sUserName, $sBind_DN );

    // ... bind password
    $sBind_Password =
      $roEnvironment->hasStaticParameter( 'php_ape.auth.ldap.bind.password' ) ?
      PHP_APE_Type_String::parseValue( $roEnvironment->getStaticParameter( 'php_ape.auth.ldap.bind.password' ) ) :
      null;

    // ... users search base
    $sUser_BaseDN =
      $roEnvironment->hasStaticParameter( 'php_ape.auth.ldap.user.basedn' ) ?
      PHP_APE_Type_String::parseValue( $roEnvironment->getStaticParameter( 'php_ape.auth.ldap.user.basedn' ) ) :
      null;

    // ... users search filter
    $sUser_Filter =
      $roEnvironment->hasStaticParameter( 'php_ape.auth.ldap.user.filter' ) ? 
      PHP_APE_Type_String::parseValue( $roEnvironment->getStaticParameter( 'php_ape.auth.ldap.user.filter' ) ) :
      null;
    $sUser_Filter = str_ireplace( '@{username}', $sUserName, $sUser_Filter );

    // ... user attributes
    $asUser_Attributes =
      $roEnvironment->hasStaticParameter( 'php_ape.auth.ldap.user.attr' ) ?
      PHP_APE_Type_Array::parseValue( $roEnvironment->getStaticParameter( 'php_ape.auth.ldap.user.attr' ) ) :
      array();

    // ... groups search base
    $sGroup_BaseDN =
      $roEnvironment->hasStaticParameter( 'php_ape.auth.ldap.group.basedn' ) ?
      PHP_APE_Type_String::parseValue( $roEnvironment->getStaticParameter( 'php_ape.auth.ldap.group.basedn' ) ) :
      null;

    // ... groups search filter
    $sGroup_Filter =
      $roEnvironment->hasStaticParameter( 'php_ape.auth.ldap.group.filter' ) ?
      PHP_APE_Type_String::parseValue( $roEnvironment->getStaticParameter( 'php_ape.auth.ldap.group.filter' ) ) :
      null;
    $sGroup_Filter = str_ireplace( '@{username}', $sUserName, $sGroup_Filter );

    // ... group attributes
    $asGroup_Attributes =
      $roEnvironment->hasStaticParameter( 'php_ape.auth.ldap.group.attr' ) ?
      PHP_APE_Type_Array::parseValue( $roEnvironment->getStaticParameter( 'php_ape.auth.ldap.group.attr' ) ) :
      array();

    // ... LDAP character set
    $sLDAPCharset =
      $roEnvironment->hasStaticParameter( 'php_ape.auth.ldap.charset' ) ?
      PHP_APE_Type_String::parseValue( $roEnvironment->getStaticParameter( 'php_ape.auth.ldap.charset' ) ) :
      'UTF-8';

    // ... PHP character set
    $sPHPCharset = PHP_APE_WorkSpace::useEnvironment()->getStaticParameter( 'php_ape.data.charset' );


    // Check DNs

    // ... user
    if( empty( $sBind_DN ) and empty( $sUser_BaseDN ) )
      throw new PHP_APE_Auth_Exception( __METHOD__, 'Missing both bind and user base directory names' );


    // Check attributes

    // ... user
    $sUserID_Attribute = array_key_exists( 'user.id', $asUser_Attributes ) ? $asUser_Attributes['user.id'] : 'uid';
    $sUserUID_Attribute = array_key_exists( 'user.uid', $asUser_Attributes ) ? $asUser_Attributes['user.uid'] : 'uidNumber';
    $sUserGID_Attribute = array_key_exists( 'user.gid', $asUser_Attributes ) ? $asUser_Attributes['user.gid'] : 'gidNumber';
    $sUserPath_Attribute = array_key_exists( 'user.path', $asUser_Attributes ) ? $asUser_Attributes['user.path'] : 'homeDirectory';
    $sUserFullName_Attribute = array_key_exists( 'user.fullname', $asUser_Attributes ) ? $asUser_Attributes['user.fullname'] : 'cn';
    $sUserEmail_Attribute = array_key_exists( 'user.email', $asUser_Attributes ) ? $asUser_Attributes['user.email'] : 'mail';
    $sUserPhone_Attribute = array_key_exists( 'user.phone', $asUser_Attributes ) ? $asUser_Attributes['user.phone'] : 'phone';
    $sUserOffice_Attribute = array_key_exists( 'user.office', $asUser_Attributes ) ? $asUser_Attributes['user.office'] : 'office';
    // ... for LDAP
    $asUser_Attributes = array();
    if( !empty( $sUserID_Attribute ) ) $asUser_Attributes['user.id'] = $sUserID_Attribute;
    if( !empty( $sUserUID_Attribute ) ) $asUser_Attributes['user.uid'] = $sUserUID_Attribute;
    if( !empty( $sUserGID_Attribute ) ) $asUser_Attributes['user.gid'] = $sUserGID_Attribute;
    if( !empty( $sUserPath_Attribute ) ) $asUser_Attributes['user.path'] = $sUserPath_Attribute;
    if( !empty( $sUserFullName_Attribute ) ) $asUser_Attributes['user.fullname'] = $sUserFullName_Attribute;
    if( !empty( $sUserEmail_Attribute ) ) $asUser_Attributes['user.email'] = $sUserEmail_Attribute;
    if( !empty( $sUserPhone_Attribute ) ) $asUser_Attributes['user.phone'] = $sUserPhone_Attribute;
    if( !empty( $sUserOffice_Attribute ) ) $asUser_Attributes['user.office'] = $sUserOffice_Attribute;
    
    // ... group
    $sGroupID_Attribute = array_key_exists( 'group.id', $asGroup_Attributes ) ? $asGroup_Attributes['group.id'] : 'cn';
    $sGroupGID_Attribute = array_key_exists( 'group.gid', $asGroup_Attributes ) ? $asGroup_Attributes['group.gid'] : 'gidNumber';
    // ... for LDAP
    $asGroup_Attributes = array();
    if( !empty( $sGroupID_Attribute ) ) $asGroup_Attributes['group.id'] = $sGroupID_Attribute;
    if( !empty( $sGroupGID_Attribute ) ) $asGroup_Attributes['group.gid'] = $sGroupGID_Attribute;

    
    // Check dependencies

    // ... user
    if( !isset( $asUser_Attributes['user.id'] ) )
      throw new PHP_APE_Auth_Exception( __METHOD__, 'Missing user identifier attribute' );
    if( !empty( $sUser_BaseDN ) and empty( $sUser_Filter ) )
      throw new PHP_APE_Auth_Exception( __METHOD__, 'Missing user search filter' );

    // ... group
    if( !empty( $sGroup_BaseDN ) )
    {
      if( !isset( $asGroup_Attributes['group.id'] ) )
        throw new PHP_APE_Auth_Exception( __METHOD__, 'Missing group identifier attribute' );
      if( empty( $asGroup_Attributes['group.gid'] ) and empty( $sGroup_Filter ) )
        throw new PHP_APE_Auth_Exception( __METHOD__, 'Missing group (unique) identifier attribute and secondary group search filter' );
      if( !empty( $asGroup_Attributes['group.gid'] ) and empty( $asUser_Attributes['user.gid'] ) )
        throw new PHP_APE_Auth_Exception( __METHOD__, 'Missing user\'s primary group identifier attribute' );
    }
        

    // Check and retrieve LDAP crendentials
    $resLDAP = null;
    try
    {

      // Authentication

      // ... connect
      $resLDAP = ldap_connect( $sHost, $iPort );
      if( $resLDAP === false )
        throw new PHP_APE_Auth_Exception( __METHOD__, 'Failed to connect to server' );

      // ... bind
      $sUser_DN = null;
      if( !empty( $sBind_DN ) )
      {

        if( empty( $sUser_BaseDN ) )
        { // ... one-pass bind

          // ... bind
          $sUser_DN = $sBind_DN;
          if( !@ldap_bind( $resLDAP, iconv( $sPHPCharset, $sLDAPCharset, $sUser_DN ), iconv( $sPHPCharset, $sLDAPCharset, $sPassword ) ) )
            throw new PHP_APE_Auth_AuthenticationException( __METHOD__, 'Invalid credentials', 0, $sUserName );

        }
        else
        { // ... two-pass bind

          // ... bind (first pass)
          if( !ldap_bind( $resLDAP, iconv( $sPHPCharset, $sLDAPCharset, $sBind_DN ), iconv( $sPHPCharset, $sLDAPCharset, $sBind_Password ) ) )
            throw new PHP_APE_Auth_Exception( __METHOD__, 'Failed to bind to server' );

          // ... search user
          $resSearch = ldap_search( $resLDAP, iconv( $sPHPCharset, $sLDAPCharset, $sUser_BaseDN ), iconv( $sPHPCharset, $sLDAPCharset, $sUser_Filter ), array(), 0, 2, $iTimeOut );
          if( $resSearch === false )
            throw new PHP_APE_Auth_Exception( __METHOD__, 'Failed to search for user entries' );

          // ... check result
          $iCount = ldap_count_entries( $resLDAP, $resSearch );
          if( $iCount == 0 )
            throw new PHP_APE_Auth_AuthenticationException( __METHOD__, 'Invalid credentials', 0, $sUserName );
          if( $iCount > 1 )
            throw new PHP_APE_Auth_Exception( __METHOD__, 'More than one matching user entry' );

          // ... get entry
          $resEntry = ldap_first_entry( $resLDAP, $resSearch );
          if( $resEntry === false )
            throw new PHP_APE_Auth_Exception( __METHOD__, 'Failed to retrieve user data' );

          // ... get user DN
          $sUser_DN = iconv( $sLDAPCharset, $sPHPCharset, ldap_get_dn( $resLDAP, $resEntry ) );
          if( $sUser_DN === false )
            throw new PHP_APE_Auth_Exception( __METHOD__, 'Failed to retrieve user directory name' );

          // ... free resouces
          ldap_free_result( $resSearch );

          // ... bind (second-pass)
          if( !@ldap_bind( $resLDAP, iconv( $sPHPCharset, $sLDAPCharset, $sUser_DN ), iconv( $sPHPCharset, $sLDAPCharset, $sPassword ) ) )
            throw new PHP_APE_Auth_AuthenticationException( __METHOD__, 'Invalid credentials', 0, $sUserName );

        }

      }


      // User data

      // ... retrieve entry
      $resSearch = ldap_read( $resLDAP, iconv( $sPHPCharset, $sLDAPCharset, $sUser_DN ), 'objectClass=*', array_values( $asUser_Attributes ), 0, 1, $iTimeOut );
      if( $resSearch === false )
        throw new PHP_APE_Auth_Exception( __METHOD__, 'Failed to retrieve user entry' );
      $resEntry = ldap_first_entry( $resLDAP, $resSearch );
      if( $resEntry === false )
        throw new PHP_APE_Auth_Exception( __METHOD__, 'Failed to retrieve user data' );

      // ... retrieve data
      $asAttributes = ldap_get_attributes( $resLDAP, $resEntry );
      // ... identifier
      if( !array_key_exists( $asUser_Attributes['user.id'], $asAttributes ) )
        throw new PHP_APE_Auth_Exception( __METHOD__, 'Missing user identifier' );
      $sUserID = iconv( $sLDAPCharset, $sPHPCharset, $asAttributes[$asUser_Attributes['user.id']][0] );
      // ... unique identifier
      $sUserUID =
        array_key_exists( $asUser_Attributes['user.uid'], $asAttributes ) ?
        $asAttributes[$asUser_Attributes['user.uid']][0] :
        null;
      // ... group's unique identifier
      $sUserGID =
        array_key_exists( $asUser_Attributes['user.gid'], $asAttributes ) ?
        $asAttributes[$asUser_Attributes['user.gid']][0] :
        null;
      // ... data path (home directory)
      $sUserPath =
        array_key_exists( $asUser_Attributes['user.path'], $asAttributes ) ?
        $asAttributes[$asUser_Attributes['user.path']][0] :
        null;
      // ... full name
      $sUserFullName =
        array_key_exists( $asUser_Attributes['user.fullname'], $asAttributes ) ?
        iconv( $sLDAPCharset, $sPHPCharset, $asAttributes[$asUser_Attributes['user.fullname']][0] ) :
        null;
      // ... e-mail address
      $sUserEmail =
        array_key_exists( $asUser_Attributes['user.email'], $asAttributes ) ?
        iconv( $sLDAPCharset, $sPHPCharset, $asAttributes[$asUser_Attributes['user.email']][0] ) :
        null;
      // ... phone number
      $sUserPhone =
        array_key_exists( $asUser_Attributes['user.phone'], $asAttributes ) ?
        iconv( $sLDAPCharset, $sPHPCharset, $asAttributes[$asUser_Attributes['user.phone']][0] ) :
        null;
      // ... office (location)
      $sUserOffice =
        array_key_exists( $asUser_Attributes['user.office'], $asAttributes ) ?
        iconv( $sLDAPCharset, $sPHPCharset, $asAttributes[$asUser_Attributes['user.office']][0] ) :
        null;

      // ... free resouces
      ldap_free_result( $resSearch );


      // Group data
      $asGroupIDs = array();
      if( !empty( $sGroup_BaseDN ) )
      {

        // ... primary group
        if( isset( $asGroup_Attributes['group.gid'] ) and !empty( $sUserGID ) )
        {
          // ... retrieve entry
          $resSearch = ldap_search( $resLDAP, iconv( $sPHPCharset, $sLDAPCharset, $sGroup_BaseDN ), $asGroup_Attributes['group.gid'].'='.$sUserGID, array_values( $asGroup_Attributes ), 0, 2, $iTimeOut );
          if( $resSearch === false )
            throw new PHP_APE_Auth_Exception( __METHOD__, 'Failed to retrieve primary group entry' );

          // ... check result
          $iCount = ldap_count_entries( $resLDAP, $resSearch );
          if( $iCount == 0 )
            throw new PHP_APE_Auth_Exception( __METHOD__, 'Missing primary group entry' );
          if( $iCount > 1 )
            throw new PHP_APE_Auth_Exception( __METHOD__, 'More than one matching primary group entry' );

          $resEntry = ldap_first_entry( $resLDAP, $resSearch );
          if( $resEntry === false )
            throw new PHP_APE_Auth_Exception( __METHOD__, 'Failed to retrieve primary group data' );

          // ... retrieve data
          $asAttributes = ldap_get_attributes( $resLDAP, $resEntry );
          // ... identifier
          if( !array_key_exists( $asGroup_Attributes['group.id'], $asAttributes ) )
            throw new PHP_APE_Auth_Exception( __METHOD__, 'Missing group identifier' );
          array_push( $asGroupIDs, iconv( $sLDAPCharset, $sPHPCharset, $asAttributes[$asGroup_Attributes['group.id']][0] ) );

          // ... free resouces
          ldap_free_result( $resSearch );
        }
        
        // ... secondary groups
        if( !empty( $sGroup_Filter ) )
        {
          // ... search entries
          $resSearch = ldap_search( $resLDAP, iconv( $sPHPCharset, $sLDAPCharset, $sGroup_BaseDN ), iconv( $sPHPCharset, $sLDAPCharset, $sGroup_Filter ), array_values( $asGroup_Attributes ), 0, 100, $iTimeOut );
          if( $resSearch === false )
            throw new PHP_APE_Auth_Exception( __METHOD__, 'Failed to search for secondary groups entries' );

          // ... check result
          if( ldap_count_entries( $resLDAP, $resSearch ) > 0 )
          {

            // ... get first entry
            $resEntry = ldap_first_entry( $resLDAP, $resSearch );
            if( $resEntry === false )
              throw new PHP_APE_Auth_Exception( __METHOD__, 'Failed to retrieve secondary group data' );

            // ... loop through entries
            do
            { 
              // ... retrieve data
              $asAttributes = ldap_get_attributes( $resLDAP, $resEntry );
              // ... identifier
              if( !array_key_exists( $asGroup_Attributes['group.id'], $asAttributes ) )
                throw new PHP_APE_Auth_Exception( __METHOD__, 'Missing group identifier' );
              array_push( $asGroupIDs, iconv( $sLDAPCharset, $sPHPCharset, $asAttributes[$asGroup_Attributes['group.id']][0] ) );
            }
            while( $resEntry = ldap_next_entry( $resLDAP, $resEntry ) );

          }

          // ... free resouces
          ldap_free_result( $resSearch );
        }

      }
      

      // ... unbind
      ldap_unbind( $resLDAP );

    }
    catch( PHP_APE_Exception $e )
    {
      // ... unbind
      if( $resLDAP ) ldap_unbind( $resLDAP );
      
      // ... throw again
      throw $e;
    }


    // End
    return new PHP_APE_Auth_AuthenticationToken( $sUserID, $sPassword, $asGroupIDs, $sUserPath, $sUserFullName, $sUserEmail, $sUserPhone, $sUserOffice );
  }

}

