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
 * @package PHP_APE_HTML
 * @subpackage WorkSpace
 */

/** HTML workspace
 *
 * <P><B>USAGE:</B></P>
 * <P>The following static parameters (properties) are provisioned by this workspace:</P>
 * <UL>
 * <LI><SAMP>php_ape.html.htdocs.url</SAMP>: root HTML documents URL [default: <SAMP>http://localhost/php-ape/lib/html</SAMP>]</LI>
 * <LI><SAMP>php_ape.html.css.url</SAMP>: root CSS resources URL [default: <SAMP>dirname(php_ape.html.htdocs.url)</SAMP>+'<SAMP>/css</SAMP>']</LI>
 * <LI><SAMP>php_ape.html.css.list</SAMP>: available CSS resources names (comma separated, excluding extension) [default: '<SAMP>Tango</SAMP>','<SAMP>Tango-print</SAMP>','<SAMP>Crystal</SAMP>','<SAMP>Crystal-print</SAMP>']</LI>
 * <LI><SAMP>php_ape.html.css</SAMP>: CSS resource name (excluding extension) [default: '<SAMP>Tango</SAMP>']</LI>
 * <LI><SAMP>php_ape.html.javascript.url</SAMP>: root JavaScript resources URL [default: <SAMP>dirname(php_ape.html.htdocs.url)</SAMP>+'<SAMP>/js</SAMP>']</LI>
 * <LI><SAMP>php_ape.html.rid</SAMP>: resource identifier type [available: '<SAMP>short</SAMP>' (default), '<SAMP>long</SAMP>', '<SAMP>md5</SAMP>' (legacy)]</LI>
 * <LI><SAMP>php_ape.html.display.icon</SAMP>: icons/labels display preference [default: <SAMP>0</SAMP>]</LI>
 * <LI><SAMP>php_ape.html.popup.use</SAMP>: use popup [default: <SAMP>true</SAMP>]</LI>
 * <LI><SAMP>php_ape.html.popup.width</SAMP>: popup width (in pixels) [default: <SAMP>640</SAMP>]</LI>
 * <LI><SAMP>php_ape.html.popup.height</SAMP>: popup height (in pixels) [default: <SAMP>480</SAMP>]</LI>
 * <LI><SAMP>php_ape.html.data.hide.empty</SAMP>: hide empty data [default: <SAMP>true</SAMP>]</LI>
 * <LI><SAMP>php_ape.html.data.hide.optional</SAMP>: hide optional (not required) data [default: <SAMP>false</SAMP>]</LI>
 * <LI><SAMP>php_ape.html.data.list.truncate</SAMP>: data truncation length in list view [default: <SAMP>100</SAMP>]</LI>
 * <LI><SAMP>php_ape.html.data.list.control.preferences.display</SAMP>: (list) data display preferences' box status [default: <SAMP>PHP_APE_HTML_SmartTags::Display_Closed</SAMP>]</LI>
 * <LI><SAMP>php_ape.html.data.list.control.preferences.order</SAMP>: (list) data order preferences' box status [default: <SAMP>PHP_APE_HTML_SmartTags::Display_Closed</SAMP>]</LI>
 * <LI><SAMP>php_ape.html.data.detail.control.preferences.display</SAMP>: (detail) data display preferences's box status [default: <SAMP>PHP_APE_HTML_SmartTags::Display_Closed</SAMP>]</LI>
 * <LI><SAMP>php_ape.html.data.form.upload.maxsize</SAMP>: maximum uploadable file size [bytes; default: <SAMP>1048576</SAMP>]</LI>
 * <LI><SAMP>php_ape.html.smarty.install.path</SAMP>: Smarty installation directory (server) path [default: <SAMP>PHP_APE_ROOT</SAMP>+'<SAMP>/Smarty/libs</SAMP>']</LI>
 * <LI><SAMP>php_ape.html.smarty.config.path</SAMP>: Smarty configuration directory (server) path [default: <SAMP>dirname(PHP_APE_CONF)</SAMP>]</LI>
 * <LI><SAMP>php_ape.html.smarty.compile.path</SAMP>: Smarty compiled templates directory (server) path [default: <SAMP>PHP_APE_CACHE</SAMP>]</LI>
 * <LI><SAMP>php_ape.html.smarty.cache.path</SAMP>: Smarty caching directory (server) path [default: <SAMP>PHP_APE_CACHE</SAMP>]</LI>
 * </UL>
 *
 * <P><B>NOTE:</B> The HTML environment should be instantiated (retrieved) before any content is sent (unless output buffering is used),
 * for HTTP headers may need to be sent as part of its instantiation process (e.g. preferences handling).</P>
 *
 * @package PHP_APE_HTML
 * @subpackage WorkSpace
 */
class PHP_APE_HTML_WorkSpace
extends PHP_APE_WorkSpace
{

  /*
   * FIELDS: static
   ********************************************************************************/

  /** Work space singleton
   * @var PHP_APE_HTML_WorkSpace */
  private static $oWORKSPACE;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs the workspace
   */
  protected function __construct()
  {
    // Call the parent constructor
    parent::__construct();

    // Handle authentication
    $this->__doAuthentication();

    // Synchronize preferences
    $this->__syncPreferences();
  }


  /*
   * METHODS: factory
   ********************************************************************************/

  /** Returns the (singleton) environment instance (<B>as reference</B>)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   *
   * @return PHP_APE_HTML_WorkSpace
   */
  public static function &useEnvironment()
  {
    if( is_null( self::$oWORKSPACE ) ) self::$oWORKSPACE = new PHP_APE_HTML_WorkSpace();
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

    // Root HTML documents URL
    if( array_key_exists( 'php_ape.html.htdocs.url', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.html.htdocs.url' ];
      $rValue = trim( PHP_APE_Type_Path::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = 'http://localhost/php-ape/lib/html';
      else
        $rValue = rtrim( $rValue, '/' );
    }

    // Root CSS resources URL
    if( array_key_exists( 'php_ape.html.css.url', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.html.css.url' ];
      $rValue = trim( PHP_APE_Type_Path::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = dirname( $rasParameters[ 'php_ape.html.htdocs.url' ] ).'/css';
      else
        $rValue = rtrim( $rValue, '/' );
    }

    // Available CSS resources names
    if( array_key_exists( 'php_ape.html.css.list', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.html.css.list' ];
      $rValue = PHP_APE_Type_Array::parseValue( $rValue );
      if( empty( $rValue ) )
        $rValue = array( 'Crystal', 'Crystal-print', 'Tango', 'Tango-print' );
    }

    // Default CSS resource name
    if( array_key_exists( 'php_ape.html.css', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.html.css' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = 'Tango';
    }

    // Root JavaScript resources URL
    if( array_key_exists( 'php_ape.html.javascript.url', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.html.javascript.url' ];
      $rValue = trim( PHP_APE_Type_Path::parseValue( $rValue ) );
      if( empty( $rValue ) ) 
        $rValue = dirname( $rasParameters[ 'php_ape.html.htdocs.url' ] ).'/js';
      else
        $rValue = rtrim( $rValue, '/' );
    }

    // Resource identifier type
    if( array_key_exists( 'php_ape.html.rid', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.html.rid' ];
      $rValue = strtolower( trim( PHP_APE_Type_String::parseValue( $rValue ) ) );
      switch( $rValue )
      {
      case 'short':
      case 'long':
      case 'md5': break;
      default: $rValue = 'short';
      }
    }
    

    // Default icon display
    if( array_key_exists( 'php_ape.html.display.icon', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.html.display.icon' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( empty( $rValue ) ) 
        $rValue = 0;
    }

    // Use popup
    if( array_key_exists( 'php_ape.html.popup.use', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.html.popup.use' ];
      $rValue = PHP_APE_Type_Boolean::parseValue( $rValue );
      if( is_null( $rValue ) ) 
        $rValue = true;
    }

    // Default popup width
    if( array_key_exists( 'php_ape.html.popup.width', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.html.popup.width' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( empty( $rValue ) ) 
        $rValue = 640;
      elseif( $rValue < 100 ) 
        $rValue = 100;
      elseif( $rValue > 4000 ) 
        $rValue = 4000;
    }

    // Default popup height
    if( array_key_exists( 'php_ape.html.popup.height', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.html.popup.height' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( empty( $rValue ) ) 
        $rValue = 480;
      elseif( $rValue < 100 )
        $rValue = 100;
      elseif( $rValue > 3000 )
        $rValue = 3000;
    }

    // Hide empty data
    if( array_key_exists( 'php_ape.html.data.hide.empty', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.html.data.hide.empty' ];
      $rValue = PHP_APE_Type_Boolean::parseValue( $rValue );
      if( is_null( $rValue ) ) 
        $rValue = true;
    }

    // Hide optional (not required) data
    if( array_key_exists( 'php_ape.html.data.hide.optional', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.html.data.hide.optional' ];
      $rValue = PHP_APE_Type_Boolean::parseValue( $rValue );
      if( is_null( $rValue ) ) 
        $rValue = false;
    }

    // Data truncation length in list view
    if( array_key_exists( 'php_ape.html.data.list.truncate', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.html.data.list.truncate' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( empty( $rValue ) ) 
        $rValue = 100;
      elseif( $rValue < 10 )
        $rValue = 10;
    }

    // (List) data display preferences' box status
    if( array_key_exists( 'php_ape.html.data.list.control.preferences.display', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.html.data.list.control.preferences.display' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( is_null( $rValue ) ) 
        $rValue = -1;
    }

    // (List) data order preferences' box status
    if( array_key_exists( 'php_ape.html.data.list.control.preferences.order', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.html.data.list.control.preferences.order' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( is_null( $rValue ) ) 
        $rValue = -1;
    }

    // (Detail) data display preferences' box status
    if( array_key_exists( 'php_ape.html.data.detail.control.preferences.display', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.html.data.detail.control.preferences.display' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( is_null( $rValue ) ) 
        $rValue = -1;
    }

    // Maximum uploadable file size [bytes]
    if( array_key_exists( 'php_ape.html.data.form.upload.maxsize', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.html.data.form.upload.maxsize' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( empty( $rValue ) ) 
        $rValue = 1048576;
      if( $rValue < 1024 )
        $rValue = 1024;
    }

    // Smarty installation directory (server) path
    if( array_key_exists( 'php_ape.html.smarty.install.path', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.html.smarty.install.path' ];
      $rValue = trim( PHP_APE_Type_Path::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = PHP_APE_ROOT.'/lib/Smarty/libs';
      else
        $rValue = rtrim( $rValue, '/' );
    }

    // Smarty configuration directory (server) path
    if( array_key_exists( 'php_ape.html.smarty.config.path', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.html.smarty.config.path' ];
      $rValue = trim( PHP_APE_Type_Path::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = dirname( PHP_APE_CONF );
      else
        $rValue = rtrim( $rValue, '/' );
    }

    // Smarty compiled templates directory (server) path
    if( array_key_exists( 'php_ape.html.smarty.compile.path', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.html.smarty.compile.path' ];
      $rValue = trim( PHP_APE_Type_Path::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = PHP_APE_CACHE;
      else
        $rValue = rtrim( $rValue, '/' );
    }

    // Smarty caching directory (server) path
    if( array_key_exists( 'php_ape.html.smarty.cache.path', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.html.smarty.cache.path' ];
      $rValue = trim( PHP_APE_Type_Path::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = PHP_APE_CACHE;
      else
        $rValue = rtrim( $rValue, '/' );
    }

  }


  /*
   * METHODS: static parameters - OVERRIDE
   ********************************************************************************/

  protected function _mandatoryStaticParameters()
  {
    return array_merge( parent::_mandatoryStaticParameters(),
                        array(
                              'php_ape.html.htdocs.url' => null,
                              'php_ape.html.css.url' => null, 'php_ape.html.css.list' => null, 'php_ape.html.css' => null,
                              'php_ape.html.javascript.url' => null,
                              'php_ape.html.rid' => null,
                              'php_ape.html.display.icon' => null,
                              'php_ape.html.popup.use' => null, 'php_ape.html.popup.width' => null, 'php_ape.html.popup.height' => null,
                              'php_ape.html.data.hide.empty' => null, 'php_ape.html.data.hide.optional' => null,
                              'php_ape.html.data.list.truncate' => null,
                              'php_ape.html.data.list.control.preferences.display' => null, 'php_ape.html.data.list.control.preferences.order' => null, 'php_ape.html.data.detail.control.preferences.display' => null,
                              'php_ape.html.data.form.upload.maxsize' => null,
                              'php_ape.html.smarty.install.path' => null, 'php_ape.html.smarty.config.path' => null, 'php_ape.html.smarty.compile.path' => null, 'php_ape.html.smarty.cache.path' => null
                              )
                        );
  }


  /*
   * METHODS: utilities
   ********************************************************************************/

  /** Returns a preferences-setting URL
   *
   * <P><B>USAGE:</B> This methods returns the given URL with the appropriate query parameters,
   * so the HTML environment can synchronize the preferences accordingly when instantiated.</P>
   * <P><B>NOTE:</B> Preferences identifiers (IDs) are encrypted in order to avoid Denail-of-Service
   * by someone overflowing the preferences parameters.</P>
   *
   * @param string $asPreferences Preferences array
   * @param string $sURL Target URL
   * @return string
   */
  public function makePreferencesURL( $asPreferences, $sURL )
  {
    // Sanitize input
    $sURL = (string)$sURL;
    if( !is_array( $asPreferences ) or !count( $asPreferences ) ) return $sURL;

    // Encrypt preferences identifiers (IDs) (avoid )
    $asEncryptedPreferences = array();
    foreach( $asPreferences as $sID => $sValue )
      $asEncryptedPreferences[ $this->encryptData( $sID ) ] = $sValue;

    // Return preferences URL
    return PHP_APE_Util_URL::addVariable( $sURL, array( 'PHP_APE_P' => PHP_APE_Properties::convertArray2String( $asEncryptedPreferences ) ) );
  }

  /** Handle authentication based on the incoming request (HTTP POST or GET) content
   */
  private function __doAuthentication()
  {

    // Check preferences data
    if( array_key_exists( 'PHP_APE_A', $_POST ) )
    { // HTTP POST
      $sDo = $_POST['PHP_APE_A'];
    }
    elseif( array_key_exists( 'PHP_APE_A', $_GET ) )
    { // HTTP GET
      $sDo = $_GET['PHP_APE_A'];
    }
    else return;

    // Handle authentication
    if( strtolower( $sDo ) == 'logout' )
      PHP_APE_Auth_WorkSpace::useEnvironment()->doAuthenticationLogout();

    // Reload
    $sURL = preg_replace( '/&?PHP_APE_A=[^&]*/is', null , $_SERVER['QUERY_STRING'] );
    $sURL = ltrim( $sURL, '&' );
    $sURL = $_SERVER['SCRIPT_NAME'].( $sURL ? '?'.$sURL : null );
    PHP_APE_Util_BrowserControl::goto( $sURL, null, true );

  }

  /** Synchronize preferences based on the incoming request (HTTP POST or GET) content
   */
  private function __syncPreferences()
  {

    // Check preferences data
    if( array_key_exists( 'PHP_APE_P', $_POST ) )
    { // HTTP POST
      $asPreferences = PHP_APE_Properties::convertString2Array( $_POST['PHP_APE_P'] );
    }
    elseif( array_key_exists( 'PHP_APE_P', $_GET ) )
    { // HTTP GET
      $asPreferences = PHP_APE_Properties::convertString2Array( $_GET['PHP_APE_P'] );
    }
    else return;

    // Save preferences
    if( is_array( $asPreferences ) and count( $asPreferences ) > 0 )
    {
      foreach( $asPreferences as $sID => $sValue )
        $this->setUserParameter( $this->decryptData( $sID ), $sValue );
      $this->saveUserParameters();
    }

    // Reload
    $sURL = preg_replace( '/&?PHP_APE_P=[^&]*/is', null , $_SERVER['QUERY_STRING'] );
    $sURL = ltrim( $sURL, '&' );
    $sURL = $_SERVER['SCRIPT_NAME'].( $sURL ? '?'.$sURL : null );
    PHP_APE_Util_BrowserControl::goto( $sURL, null, true );

  }

}
