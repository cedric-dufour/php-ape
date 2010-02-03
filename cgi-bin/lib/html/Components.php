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
 * @subpackage Components
 */

/** Prebuilt HTML components
 *
 * @package PHP_APE_HTML
 * @subpackage Components
 */
class PHP_APE_HTML_Components
{

  /** Returns information message block
   *
   * @return string
   */
  public static function htmlBlockInfo( $sTitle, $sMessage = null, $sIconID = 'S-information' )
  {
    // Sanitize input
    $sTitle = PHP_APE_Type_String::parseValue( $sTitle );
    $sMessage = PHP_APE_Type_String::parseValue( $sMessage );
    $sIconID = PHP_APE_Type_Index::parseValue( $sIconID );

    // HTML
    $oDataSpace = new PHP_APE_DataSpace_HTML();

    // Output
    $sOutput = null;
    $sOutput .= '<DIV CLASS="info">'."\r\n";
    $sOutput .= '<DIV STYLE="TEXT-ALIGN:center;">'."\r\n";
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen( null, 'MARGIN:auto !important;' );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlIcon( $sIconID, null, null, null, false );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
    $sOutput .= '<H1>'.$oDataSpace->encodeData( $sTitle ).'</H1>'."\r\n";
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();
    $sOutput .= '</DIV>'."\r\n";
    if( !empty( $sMessage ) )
      $sOutput .= '<P>'.$oDataSpace->encodeData( $sMessage ).'</P>'."\r\n";
    $sOutput .= '</DIV>'."\r\n";
    return $sOutput;
  }

  /** Returns warning message block
   *
   * @return string
   */
  public static function htmlBlockWarning( $sTitle, $sMessage = null, $sIconID = 'S-warning' )
  {
    // Sanitize input
    $sTitle = PHP_APE_Type_String::parseValue( $sTitle );
    $sMessage = PHP_APE_Type_String::parseValue( $sMessage );
    $sIconID = PHP_APE_Type_Index::parseValue( $sIconID );

    // HTML
    $oDataSpace = new PHP_APE_DataSpace_HTML();

    // Output
    $sOutput = null;
    $sOutput .= '<DIV CLASS="warning">'."\r\n";
    $sOutput .= '<DIV STYLE="TEXT-ALIGN:center;">'."\r\n";
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen( null, 'MARGIN:auto !important;' );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlIcon( $sIconID, null, null, null, false );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
    $sOutput .= '<H1>'.$oDataSpace->encodeData( $sTitle ).'</H1>'."\r\n";
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();
    $sOutput .= '</DIV>'."\r\n";
    if( !empty( $sMessage ) )
      $sOutput .= '<P>'.$oDataSpace->encodeData( $sMessage ).'</P>'."\r\n";
    $sOutput .= '</DIV>'."\r\n";
    return $sOutput;
  }

  /** Returns error message block
   *
   * @return string
   */
  public static function htmlBlockError( $sTitle, $sMessage = null, $sIconID = 'S-error', PHP_APE_Exception $e = null, $bIncludeContext = false, $bIncludeMessage = false, $bIncludeStackTrace = false )
  {
    // Sanitize input
    $sTitle = PHP_APE_Type_String::parseValue( $sTitle );
    $sMessage = PHP_APE_Type_String::parseValue( $sMessage );
    $sIconID = PHP_APE_Type_Index::parseValue( $sIconID );

    // HTML
    $oDataSpace = new PHP_APE_DataSpace_HTML();

    // Output
    $sOutput = null;
    $sOutput .= '<DIV CLASS="error">'."\r\n";
    $sOutput .= '<DIV STYLE="TEXT-ALIGN:center;">'."\r\n";
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen( null, 'MARGIN:auto !important;' );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlIcon( $sIconID, null, null, null, false );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
    $sOutput .= '<H1>'.$oDataSpace->encodeData( $sTitle ).'</H1>'."\r\n";
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();
    $sOutput .= '</DIV>'."\r\n";
    if( !empty( $sMessage ) )
      $sOutput .= '<P>'.$oDataSpace->encodeData( $sMessage ).'</P>'."\r\n";
    if( !is_null( $e ) )
    {
      $sOutput .= '<UL>'."\r\n";
      if( $bIncludeContext )
      {
        $sOutput .= '<LI>'.htmlentities( $e->getContext() ).'</LI>'."\r\n";
        $sOutput .= '<LI>'.htmlentities( get_class( $e ) ).'</LI>'."\r\n";
      }
      if( $bIncludeMessage )
        $sOutput .= '<LI>'.htmlentities( $e->getMessage() ).'</LI>'."\r\n";
      if( $bIncludeStackTrace )
        $sOutput .= '<LI>'.nl2br( htmlentities( $e->getTraceAsString() ) ).'</LI>'."\r\n";
      $sOutput .= '</UL>'."\r\n";
    }
    $sOutput .= '</DIV>'."\r\n";
    return $sOutput;
  }

  /** Returns authentication success message block
   *
   * @return string
   */
  public static function htmlAuthenticationSuccess( PHP_APE_Auth_AuthenticationToken $oAuthenticationToken, $sIconID = 'S-ok' )
  {
    // Resources
    $asResources = PHP_APE_HTML_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_HTML_Components' );

    // HTML
    return self::htmlBlockInfo( $asResources['label.authenticationsuccess'].' ('.$oAuthenticationToken->getUserID().')', $asResources['tooltip.authenticationsuccess'], $sIconID );
  }

  /** Returns authentication exception message block
   *
   * @return string
   */
  public static function htmlAuthenticationException( PHP_APE_Exception $e = null  )
  {
    // Resources
    $asResources = PHP_APE_HTML_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_HTML_Components' );

    // HTML
    return self::htmlBlockError( $asResources['label.authenticationerror'], $asResources['tooltip.authenticationerror'], 'S-lock' );
  }

  /** Returns authorization exception message block
   *
   * @return string
   */
  public static function htmlAuthorizationException( PHP_APE_Exception $e = null )
  {
    // Resources
    $asResources = PHP_APE_HTML_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_HTML_Components' );

    // HTML
    return self::htmlBlockError( $asResources['label.authorizationerror'], $asResources['tooltip.authorizationerror'], 'S-lock' );
  }

  /** Returns data exception message block
   *
   * @return string
   */
  public static function htmlDataException( PHP_APE_Exception $e = null, $bIncludeContext = false, $bIncludeMessage = false  )
  {
    // Resources
    $asResources = PHP_APE_HTML_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_HTML_Components' );

    // HTML
    return self::htmlBlockError( $asResources['label.dataerror'], $asResources['tooltip.dataerror'], 'S-error', $e, $bIncludeContext, $bIncludeMessage, false );
  }

  /** Returns unexpected exception message block
   *
   * @return string
   */
  public static function htmlUnexpectedException( PHP_APE_Exception $e = null )
  {
    // Resources
    $asResources = PHP_APE_HTML_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_HTML_Components' );

    // HTML
    if( PHP_APE_DEBUG )
      return self::htmlBlockError( $asResources['label.systemerror'], $asResources['tooltip.systemerror'], 'S-error', $e, true, true, true );
    else
      return self::htmlBlockError( $asResources['label.systemerror'], $asResources['tooltip.systemerror'], 'S-error' );
  }

  /** Returns display controls elements
   *
   * <P><B>USAGE:</B> Use this method to output elements display controls buttons (minimize/maximize + close/open).</P>
   *
   * @param string $sControlID Control identifier (ID)
   * @param integer $iControl Controls code (see class constants )
   * @return string
   */
  public static function htmlControlDisplay( $sControlID, $iControl = PHP_APE_HTML_SmartTags::Display_Control_All )
  {
    // Sanitize input
    $sControlID = (string)$sControlID;
    $iControl = (integer)$iControl;

    // Verifiy controls display
    $bCloseOpen = (boolean)( $iControl & PHP_APE_HTML_SmartTags::Display_Control_CloseOpen );
    $bMinMax = (boolean)( $iControl & PHP_APE_HTML_SmartTags::Display_Control_MinMax );

    // Environment
    $roEnvironment =& PHP_APE_HTML_WorkSpace::useEnvironment();
    $bIconDisplay = $roEnvironment->getUserParameter( 'php_ape.html.display.icon' ) > PHP_APE_HTML_SmartTags::Icon_Hide;
    

    // Resources
    $asResources = $roEnvironment->loadProperties( 'PHP_APE_HTML_Components' );

    // HTML
    $oDataSpace_JavaScript = new PHP_APE_DataSpace_JavaScript();
    $iControlPreference = @$roEnvironment->getUserParameter( $sControlID );
    if( is_null( $iControlPreference ) ) $iControlPreference = PHP_APE_HTML_SmartTags::Display_Maximized;
    else $iControlPreference = (integer)$iControlPreference;

    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- CONTROL(Display):begin -->\r\n";
    $sOutput .= '<DIV CLASS="APE-control">';
    $sOutput .= '<TABLE CLASS="APE-control" CELLSPACING="0"><TR>';
    if( $bMinMax )
    {
      if( $iControlPreference > PHP_APE_HTML_SmartTags::Display_Minimized )
      {
        $sURL = 'javascript:document.location.replace(\''.$oDataSpace_JavaScript->encodeData( $roEnvironment->makePreferencesURL( array( $sControlID => (integer)PHP_APE_HTML_SmartTags::Display_Minimized ), $_SERVER['REQUEST_URI'] ) ).'\');';
        $sOutput .= $bIconDisplay ? '<TD CLASS="i">'.PHP_APE_HTML_SmartTags::htmlIcon( 'S-minimize', $sURL, $asResources['tooltip.display.minimize'], null, true ).'</TD>' : '<TD CLASS="l">'.PHP_APE_HTML_Tags::htmlAnchor( $sURL, '&minus;', $asResources['tooltip.display.minimize'], null, true ).'</TD>';
      }
      elseif( $iControlPreference < PHP_APE_HTML_SmartTags::Display_Maximized )
      {
        $sURL = 'javascript:document.location.replace(\''.$oDataSpace_JavaScript->encodeData( $roEnvironment->makePreferencesURL( array( $sControlID => (integer)PHP_APE_HTML_SmartTags::Display_Maximized ), $_SERVER['REQUEST_URI'] ) ).'\');';
        $sOutput .= $bIconDisplay ? '<TD CLASS="i">'.PHP_APE_HTML_SmartTags::htmlIcon( 'S-maximize', $sURL, $asResources['tooltip.display.maximize'], null, true ).'</TD>' : '<TD CLASS="l">'.PHP_APE_HTML_Tags::htmlAnchor( $sURL, '+', $asResources['tooltip.display.maximize'], null, true ).'</TD>';
      }
    }
    if( $bCloseOpen )
    {
      if( $iControlPreference > PHP_APE_HTML_SmartTags::Display_Closed )
      {
        $sURL = 'javascript:document.location.replace(\''.$oDataSpace_JavaScript->encodeData( $roEnvironment->makePreferencesURL( array( $sControlID => (integer)PHP_APE_HTML_SmartTags::Display_Closed ), $_SERVER['REQUEST_URI'] ) ).'\');';
        $sOutput .= $bIconDisplay ? '<TD CLASS="i">'.PHP_APE_HTML_SmartTags::htmlIcon( 'S-hide', $sURL, $asResources['tooltip.display.close'], null, true ).'</TD>' : '<TD CLASS="l">'.PHP_APE_HTML_Tags::htmlAnchor( $sURL, '&times;', $asResources['tooltip.display.close'], null, true ).'</TD>';
      }
      else
      {
        $sURL = 'javascript:document.location.replace(\''.$oDataSpace_JavaScript->encodeData( $roEnvironment->makePreferencesURL( array( $sControlID => (integer)PHP_APE_HTML_SmartTags::Display_Maximized ), $_SERVER['REQUEST_URI'] ) ).'\');';
        $sOutput .= $bIconDisplay ? '<TD CLASS="i">'.PHP_APE_HTML_SmartTags::htmlIcon( 'S-unhide', $sURL, $asResources['tooltip.display.open'], null, true ).'</TD>' : '<TD CLASS="l">'.PHP_APE_HTML_Tags::htmlAnchor( $sURL, '&curren;', $asResources['tooltip.display.open'], null, true ).'</TD>';
      }
    }
    $sOutput .= '</TR></TABLE>';
    $sOutput .= '</DIV>';
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- CONTROL(Display):end -->\r\n";
    return $sOutput;
  }

  /** Returns frameset controls elements
   *
   * <P><B>USAGE:</B> Use this method to output frames display controls buttons (close/open).</P>
   *
   * @param array|string $asControlIDs Controls identifiers (IDs)
   * @param array|string $asNames Frames names
   * @param array|string $asIconsIDs Frames icons' identifiers (IDs)
   * @param string $sTarget Frameset target
   * @return string
   */
  public static function htmlControlFrameset( $asControlIDs, $asNames = null, $asIconsIDs = null, $sTarget = 'self' )
  {
    // Sanitize input
    if( !is_array( $asControlIDs ) ) $asControlIDs = array( $asControlIDs );
    if( !is_array( $asNames ) ) $asNames = array( $asNames );
    if( !is_array( $asIconsIDs ) ) $asNames = array( $asIconsIDs );

    // Environment
    $roEnvironment =& PHP_APE_HTML_WorkSpace::useEnvironment();

    // HTML
    $oDataSpace_JavaScript = new PHP_APE_DataSpace_JavaScript();

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- CONTROL(Frameset):begin -->\r\n";
    $sOutput .= '<DIV CLASS="APE-control">';
    $sOutput .= '<TABLE CLASS="APE-control" CELLSPACING="0"><TR>';
    foreach( $asControlIDs as $i => $sControlID )
    {
      $bUseFrame = $roEnvironment->hasUserParameter( $sControlID ) ? $roEnvironment->getUserParameter( $sControlID ) : true;
      $sName = array_key_exists( $i, $asNames ) ? $asNames[ $i ] : null;
      $sIconID = array_key_exists( $i, $asIconsIDs ) ? $asIconsIDs[ $i ] : 'S-frameset';
      $sOutput .= '<TD CLASS="i">'.PHP_APE_HTML_SmartTags::htmlIcon( $sIconID, 'javascript:'.$sTarget.'.location.replace(PHP_APE_URL_addQuery(\''.$oDataSpace_JavaScript->encodeData( ltrim( $roEnvironment->makePreferencesURL( array( $sControlID => $bUseFrame ? 0 : 1 ), null ), '?' ) ).'\','.$sTarget.'.location.href.toString()));', $sName, null, true ).'</TD>';
    }
    $sOutput .= '</TR></TABLE>';
    $sOutput .= '</DIV>';
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- CONTROL(Frameset):end -->\r\n";

    // END
    return $sOutput;
  }

  /** Returns a back control (label/button)
   *
   * @param string $sGoto Browsing destination
   * @param string $sTarget Refresh target
   * @return string
   */
  public static function htmlBack( $sGoto = null, $sTarget = 'self', $bAsButton = false, $sIconSize = 'S' )
  {
    // Sanitize input
    $sGoto = PHP_APE_Type_Path::parseValue( $sGoto );
    if( empty( $sGoto ) ) $sGoto = $_SERVER['REQUEST_URI'];
    $sIconSize = strtoupper( PHP_APE_Type_Char::parseValue( $sIconSize ) );

    // Environment
    $roEnvironment =& PHP_APE_HTML_WorkSpace::useEnvironment();

    // Resources
    $asResources = $roEnvironment->loadProperties( 'PHP_APE_HTML_Components' );

    // HTML
    $oDataSpace = new PHP_APE_DataSpace_HTML();
    $oDataSpace_JavaScript = new PHP_APE_DataSpace_JavaScript();

    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- BACK:begin -->\r\n";
    $sURL = $sGoto ? 'javascript:'.$sTarget.".location.assign('".$oDataSpace_JavaScript->encodeData( $sGoto )."')" : 'javascript:window.history.back()';
    if( $bAsButton )
      $sOutput .= PHP_APE_HTML_SmartTags::htmlButton( $asResources['label.back'], $sIconSize.'-back', $sURL, $asResources['tooltip.back'], null, true, false );
    else
      $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.back'], $sIconSize.'-back', $sURL, $asResources['tooltip.back'], null, true, false );
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- BACK:end -->\r\n";
    return $sOutput;
  }

  /** Returns an authentication control (label/button)
   *
   * @param string $sIconSize Icons' size
   * @param string $sTarget Refresh target
   * @return string
   */
  public static function htmlAuthentication( $sGoto = null, $sTarget = 'self', $bAsButton = false, $sIconSize = 'S', $bForcePopup = false )
  {
    // Check session
    if( !isset( $_SESSION ) )
      throw new PHP_APE_Exception( __METHOD__, 'No PHP session' );

    // Sanitize input
    $sGoto = PHP_APE_Type_Path::parseValue( $sGoto );
    if( empty( $sGoto ) ) $sGoto = PHP_APE_Util_URL::getFullRequestURI();
    $sIconSize = strtoupper( PHP_APE_Type_Char::parseValue( $sIconSize ) );

    // Environment
    $roEnvironment =& PHP_APE_HTML_WorkSpace::useEnvironment();
    $bPopupUse = $bForcePopup || $roEnvironment->getUserParameter( 'php_ape.html.popup.use' );
    $roEnvironment_Auth =& PHP_APE_Auth_WorkSpace::useEnvironment();

    // Resources
    $asResources = $roEnvironment->loadProperties( 'PHP_APE_HTML_Components' );

    // HTML
    $oDataSpace = new PHP_APE_DataSpace_HTML();
    $oDataSpace_JavaScript = new PHP_APE_DataSpace_JavaScript();

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- AUTHENTICATION:begin -->\r\n";
    if( $roEnvironment_Auth->hasAuthenticatedToken() )
    {
      $sURL = "javascript:".$sTarget.".location.replace(PHP_APE_URL_addQuery('PHP_APE_A=LOGOUT',".$sTarget.".location.href.toString()))";
      if( $bAsButton )
        $sOutput .= PHP_APE_HTML_SmartTags::htmlButton( $asResources['label.authentication.logout'], $sIconSize.'-logout', $sURL, $asResources['tooltip.authentication.logout'], null, true, false );
      else
      {
        $sUserID = $roEnvironment_Auth->getAuthenticationToken()->getUserID();
        if( $sIconSize == 'L' )
          $sOutput .= '<CENTER>'.PHP_APE_HTML_SmartTags::htmlIcon( 'L-logout', $sURL, $asResources['tooltip.authentication.logout'], null, false ).PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.authentication.logout'].' ('.$sUserID.')', null, $sURL, $asResources['tooltip.authentication.logout'] ).'</CENTER>';
        else
          $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.authentication.logout'].' ('.$sUserID.')', $sIconSize.'-logout', $sURL, $asResources['tooltip.authentication.logout'], null, true, false );
      }
    }
    else
    {
      $oController = new PHP_APE_HTML_Controller( 'PHP_APE_Authentication', $roEnvironment_Auth->getStaticParameter( 'php_ape.auth.htdocs.url' ), 'new' );
      
      $sURL = $oController->makeRequestURL( 'login.php', 'new', null, array( '__GOTO' => $sGoto, '__TARGET' => $sTarget ), null, $bPopupUse );
      $sURL = $bPopupUse ? 'javascript:'.self::javascriptPopup( $sURL ) : 'javascript:'.$sTarget.".location.assign('".$oDataSpace_JavaScript->encodeData( $sURL )."')";
      if( $bAsButton )
        $sOutput .= PHP_APE_HTML_SmartTags::htmlButton( $asResources['label.authentication.login'], $sIconSize.'-login', $sURL, $asResources['tooltip.authentication.login'], null, true, false );
      else
      {
        if( $sIconSize == 'L' )
          $sOutput .= '<CENTER>'.PHP_APE_HTML_SmartTags::htmlIcon( 'L-login', $sURL, $asResources['tooltip.authentication.login'], null, false ).PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.authentication.login'], null, $sURL, $asResources['tooltip.authentication.login'] ).'</CENTER>';
        else
          $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.authentication.login'], $sIconSize.'-login', $sURL, $asResources['tooltip.authentication.login'], null, true, false );
      }
    }
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- AUTHENTICATION:end -->\r\n";
    return $sOutput;
  }

  /** Go to the HTML authentication page
   *
   * @return string
   */
  public static function gotoAuthentication( $sGoto = null, $sTarget = 'self', $iDelay = 0, $bForceReauthentication = false )
  {
    // Check session
    if( !isset( $_SESSION ) )
      throw new PHP_APE_Exception( __METHOD__, 'No PHP session' );

    // Sanitize input
    $sGoto = PHP_APE_Type_Path::parseValue( $sGoto );
    if( empty( $sGoto ) ) $sGoto = $_SERVER['REQUEST_URI'];

    // Environment
    $roEnvironment =& PHP_APE_HTML_WorkSpace::useEnvironment();
    $roEnvironment_Auth =& PHP_APE_Auth_WorkSpace::useEnvironment();

    // HTML
    $oDataSpace = new PHP_APE_DataSpace_HTML();

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- AUTHENTICATION:begin -->\r\n";
    if( $bForceReauthentication or !$roEnvironment_Auth->hasAuthenticatedToken() )
    {
      $oController = new PHP_APE_HTML_Controller( 'PHP_APE_Authentication', $roEnvironment_Auth->getStaticParameter( 'php_ape.auth.htdocs.url' ), 'new' );
      
      $sURL = $oController->makeRequestURL( 'login.php', 'new', null, array( '__GOTO' => $sGoto, '__TARGET' => $sTarget ), null, false );
      PHP_APE_Util_BrowserControl::goto( $sURL, null, true, null, $iDelay );
    }
    else if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- AUTHENTICATION:already authenticated -->\r\n";
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- AUTHENTICATION:end -->\r\n";
    return $sOutput;
  }

  /** Returns a preferences settings control (label/button)
   *
   * @param string $sIconSize Icons' size
   * @param string $sTarget Refresh target
   * @return string
   */
  public static function htmlPreferences( $sGoto = null, $sTarget = 'self', $bAsButton = false, $sIconSize = 'S', $bForcePopup = false )
  {
    // Sanitize input
    $sGoto = PHP_APE_Type_Path::parseValue( $sGoto );
    if( empty( $sGoto ) ) $sGoto = $_SERVER['REQUEST_URI'];
    $sIconSize = strtoupper( PHP_APE_Type_Char::parseValue( $sIconSize ) );

    // Environment
    $roEnvironment =& PHP_APE_HTML_WorkSpace::useEnvironment();
    $bPopupUse = $bForcePopup || $roEnvironment->getUserParameter( 'php_ape.html.popup.use' );

    // Controller
    $oController = new PHP_APE_HTML_Controller( 'PHP_APE_Preferences', $roEnvironment->getStaticParameter( 'php_ape.html.htdocs.url' ) );

    // Resources
    $asResources = $roEnvironment->loadProperties( 'PHP_APE_HTML_Components' );

    // HTML
    $oDataSpace = new PHP_APE_DataSpace_HTML();
    $oDataSpace_JavaScript = new PHP_APE_DataSpace_JavaScript();

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCES:begin -->\r\n";
    $sURL = $oController->makeRequestURL( 'preferences.php', null, null, array( '__GOTO' => $sGoto, '__TARGET' => $sTarget ), null, $bPopupUse );
    $sURL = $bPopupUse ? 'javascript:'.self::javascriptPopup( $sURL ) : 'javascript:'.$sTarget.".location.assign('".$oDataSpace_JavaScript->encodeData( $sURL )."')";
    if( $bAsButton )
      $sOutput .= PHP_APE_HTML_SmartTags::htmlButton( $asResources['label.preferences'], $sIconSize.'-preferences', $sURL, $asResources['tooltip.preferences'], null, true, false );
    else
    {
      if( $sIconSize == 'L' )
        $sOutput .= '<CENTER>'.PHP_APE_HTML_SmartTags::htmlIcon( 'L-preferences', $sURL, $asResources['tooltip.preferences'], null, false ).PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.preferences'], null, $sURL, $asResources['tooltip.preferences'] ).'</CENTER>';
      else
        $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.preferences'], $sIconSize.'-preferences', $sURL, $asResources['tooltip.preferences'], null, true, false );
    }
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCES:end -->\r\n";
    return $sOutput;
  }

  /** Returns a selectable language (preference) list
   *
   * @return string
   */
  public static function htmlPreferenceLanguage()
  {
    // Environment
    $roEnvironment =& PHP_APE_HTML_Workspace::useEnvironment();
    $mCurrent = strtolower( $roEnvironment->getUserParameter( 'php_ape.locale.language' ) );
    $amChoicesList = $roEnvironment->getVolatileParameter( 'php_ape.locale.language.list' );
    require_once( PHP_APE_ROOT.'/lib/util/iso/load.php' );
    $asResources = $roEnvironment->loadProperties( 'PHP_APE_Util_ISO_639' );
    
    // HTML
    $oDataSpace = new PHP_APE_DataSpace_HTML();

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(Language):begin -->\r\n";
    $sOutput .= '<SELECT ONCHANGE="javascript:top.location.replace(PHP_APE_URL_addQuery(this.value,top.location.href.toString()));">';
    $sOutput .= '<OPTION VALUE="">'.$oDataSpace->encodeData( array_key_exists( $mCurrent, $asResources ) ? $asResources[$mCurrent] : $mCurrent ).'</OPTION>';
    foreach( $amChoicesList as $mChoice )
    {
      $mChoice = strtolower( $mChoice );
      $sOutput .= '<OPTION VALUE="'.ltrim( $roEnvironment->makePreferencesURL( array( 'php_ape.locale.language' => strtoupper( $mChoice ) ), null ), '?' ).'">'.$oDataSpace->encodeData( array_key_exists( $mChoice, $asResources ) ? $asResources[$mChoice] : $mChoice ).'</OPTION>';
    }
    $sOutput .= '</SELECT>';
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(Language):end -->\r\n";
    return $sOutput;
  }

  /** Returns a selectable country (preference) list
   *
   * @return string
   */
  public static function htmlPreferenceCountry()
  {
    // Environment
    $roEnvironment =& PHP_APE_HTML_Workspace::useEnvironment();
    $mCurrent = strtolower( $roEnvironment->getUserParameter( 'php_ape.locale.country' ) );
    $amChoicesList = $roEnvironment->getVolatileParameter( 'php_ape.locale.country.list' );
    require_once( PHP_APE_ROOT.'/lib/util/iso/load.php' );
    $asResources = $roEnvironment->loadProperties( 'PHP_APE_Util_ISO_3166' );
    
    // HTML
    $oDataSpace = new PHP_APE_DataSpace_HTML();

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(Country):begin -->\r\n";
    $sOutput .= '<SELECT ONCHANGE="javascript:top.location.replace(PHP_APE_URL_addQuery(this.value,top.location.href.toString()));">';
    $sOutput .= '<OPTION VALUE="">'.$oDataSpace->encodeData( array_key_exists( $mCurrent, $asResources ) ? $asResources[$mCurrent] : $mCurrent ).'</OPTION>';
    foreach( $amChoicesList as $mChoice )
    {
      $mChoice = strtolower( $mChoice );
      $sOutput .= '<OPTION VALUE="'.ltrim( $roEnvironment->makePreferencesURL( array( 'php_ape.locale.country' => strtoupper( $mChoice ) ), null ), '?' ).'">'.$oDataSpace->encodeData( array_key_exists( $mChoice, $asResources ) ? $asResources[$mChoice] : $mChoice ).'</OPTION>';
    }
    $sOutput .= '</SELECT>';
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(Country):end -->\r\n";
    return $sOutput;
  }

  /** Returns a selectable currency (preference) list
   *
   * @return string
   */
  public static function htmlPreferenceCurrency()
  {
    // Environment
    $roEnvironment =& PHP_APE_HTML_Workspace::useEnvironment();
    $mCurrent = strtolower( $roEnvironment->getUserParameter( 'php_ape.locale.currency' ) );
    $amChoicesList = $roEnvironment->getVolatileParameter( 'php_ape.locale.currency.list' );
    require_once( PHP_APE_ROOT.'/lib/util/iso/load.php' );
    $asResources = $roEnvironment->loadProperties( 'PHP_APE_Util_ISO_4217' );
    
    // HTML
    $oDataSpace = new PHP_APE_DataSpace_HTML();

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(Currency):begin -->\r\n";
    $sOutput .= '<SELECT ONCHANGE="javascript:top.location.replace(PHP_APE_URL_addQuery(this.value,top.location.href.toString()));">';
    $sOutput .= '<OPTION VALUE="">'.$oDataSpace->encodeData( array_key_exists( $mCurrent, $asResources ) ? $asResources[$mCurrent] : $mCurrent ).'</OPTION>';
    foreach( $amChoicesList as $mChoice )
    {
      $mChoice = strtolower( $mChoice );
      $sOutput .= '<OPTION VALUE="'.ltrim( $roEnvironment->makePreferencesURL( array( 'php_ape.locale.currency' => strtoupper( $mChoice ) ), null ), '?' ).'">'.$oDataSpace->encodeData( array_key_exists( $mChoice, $asResources ) ? $asResources[$mChoice] : $mChoice ).'</OPTION>';
    }
    $sOutput .= '</SELECT>';
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(Currency):end -->\r\n";
    return $sOutput;
  }

  /** Returns a selectable boolean format (preference) list
   *
   * @return string
   */
  public static function htmlPreferenceBoolean()
  {
    // Environment
    $roEnvironment =& PHP_APE_HTML_Workspace::useEnvironment();
    $mCurrent = $roEnvironment->getUserParameter( 'php_ape.data.format.boolean' );
    $amChoicesList = array( PHP_APE_Type_Boolean::Format_Boolean, PHP_APE_Type_Boolean::Format_Numeric, PHP_APE_Type_Boolean::Format_TrueFalse, PHP_APE_Type_Boolean::Format_YesNo, PHP_APE_Type_Boolean::Format_OnOff );
    
    // HTML
    $oDataSpace = new PHP_APE_DataSpace_HTML();
    $oBoolean_T = new PHP_APE_Type_Boolean( true );
    $oBoolean_F = new PHP_APE_Type_Boolean( false );

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(FormatBoolean):begin -->\r\n";
    $sOutput .= '<SELECT ONCHANGE="javascript:top.location.replace(PHP_APE_URL_addQuery(this.value,top.location.href.toString()));">';
    $sOutput .= '<OPTION VALUE="">'.$oDataSpace->formatData( $oBoolean_T, $mCurrent ).' / '.$oDataSpace->formatData( $oBoolean_F, $mCurrent ).'</OPTION>';
    foreach( $amChoicesList as $mChoice )
      $sOutput .= '<OPTION VALUE="'.ltrim( $roEnvironment->makePreferencesURL( array( 'php_ape.data.format.boolean' => $mChoice ), null ), '?' ).'">'.$oDataSpace->formatData( $oBoolean_T, $mChoice ).' / '.$oDataSpace->formatData( $oBoolean_F, $mChoice ).'</OPTION>';
    $sOutput .= '</SELECT>';
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(FormatBoolean):end -->\r\n";
    return $sOutput;
  }

  /** Returns a selectable numeric format (preference) list
   *
   * @return string
   */
  public static function htmlPreferenceNumeric()
  {
    // Environment
    $roEnvironment =& PHP_APE_HTML_Workspace::useEnvironment();
    $mCurrent = $roEnvironment->getUserParameter( 'php_ape.data.format.numeric' );
    $amChoicesList = array( PHP_APE_Type_Numeric::Format_Raw, PHP_APE_Type_Numeric::Format_European, PHP_APE_Type_Numeric::Format_American );
    
    // HTML
    $oDataSpace = new PHP_APE_DataSpace_HTML();
    $oNumeric = new PHP_APE_Type_Float();
    $oNumeric->setValue( $oNumeric->getSampleValue() );

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(FormatNumeric):begin -->\r\n";
    $sOutput .= '<SELECT ONCHANGE="javascript:top.location.replace(PHP_APE_URL_addQuery(this.value,top.location.href.toString()));">';
    $sOutput .= '<OPTION VALUE="">'.$oDataSpace->formatData( $oNumeric, $mCurrent ).'</OPTION>';
    foreach( $amChoicesList as $mChoice )
      $sOutput .= '<OPTION VALUE="'.ltrim( $roEnvironment->makePreferencesURL( array( 'php_ape.data.format.numeric' => $mChoice ), null ), '?' ).'">'.$oDataSpace->formatData( $oNumeric, $mChoice ).'</OPTION>';
    $sOutput .= '</SELECT>';
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(FormatNumeric):end -->\r\n";
    return $sOutput;
  }

  /** Returns a selectable date format (preference) list
   *
   * @return string
   */
  public static function htmlPreferenceDate()
  {
    // Environment
    $roEnvironment =& PHP_APE_HTML_Workspace::useEnvironment();
    $mCurrent = $roEnvironment->getUserParameter( 'php_ape.data.format.date' );
    $amChoicesList = array( PHP_APE_Type_Date::Format_ISO, PHP_APE_Type_Date::Format_European, PHP_APE_Type_Date::Format_American );
    
    // HTML
    $oDataSpace = new PHP_APE_DataSpace_HTML();
    $oDate = new PHP_APE_Type_Date();
    $oDate->setValue( $oDate->getSampleValue() );

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(FormatDate):begin -->\r\n";
    $sOutput .= '<SELECT ONCHANGE="javascript:top.location.replace(PHP_APE_URL_addQuery(this.value,top.location.href.toString()));">';
    $sOutput .= '<OPTION VALUE="">'.$oDataSpace->formatData( $oDate, $mCurrent ).'</OPTION>';
    foreach( $amChoicesList as $mChoice )
      $sOutput .= '<OPTION VALUE="'.ltrim( $roEnvironment->makePreferencesURL( array( 'php_ape.data.format.date' => $mChoice ), null ), '?' ).'">'.$oDataSpace->formatData( $oDate, $mChoice ).'</OPTION>';
    $sOutput .= '</SELECT>';
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(FormatDate):end -->\r\n";
    return $sOutput;
  }

  /** Returns a selectable time format (preference) list
   *
   * @return string
   */
  public static function htmlPreferenceTime()
  {
    // Environment
    $roEnvironment =& PHP_APE_HTML_Workspace::useEnvironment();
    $mCurrent = $roEnvironment->getUserParameter( 'php_ape.data.format.time' );
    $amChoicesList = array( PHP_APE_Type_Time::Format_ISO, PHP_APE_Type_Time::Format_European, PHP_APE_Type_Time::Format_American );
    
    // HTML
    $oDataSpace = new PHP_APE_DataSpace_HTML();
    $oTime = new PHP_APE_Type_Time();
    $oTime->setValue( $oTime->getSampleValue() );

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(FormatTime):begin -->\r\n";
    $sOutput .= '<SELECT ONCHANGE="javascript:top.location.replace(PHP_APE_URL_addQuery(this.value,top.location.href.toString()));">';
    $sOutput .= '<OPTION VALUE="">'.$oDataSpace->formatData( $oTime, $mCurrent ).'</OPTION>';
    foreach( $amChoicesList as $mChoice )
      $sOutput .= '<OPTION VALUE="'.ltrim( $roEnvironment->makePreferencesURL( array( 'php_ape.data.format.time' => $mChoice ), null ), '?' ).'">'.$oDataSpace->formatData( $oTime, $mChoice ).'</OPTION>';
    $sOutput .= '</SELECT>';
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(FormatTime):end -->\r\n";
    return $sOutput;
  }

  /** Returns a selectable angle format (preference) list
   *
   * @return string
   */
  public static function htmlPreferenceAngle()
  {
    // Environment
    $roEnvironment =& PHP_APE_HTML_Workspace::useEnvironment();
    $mCurrent = $roEnvironment->getUserParameter( 'php_ape.data.format.angle' );
    $amChoicesList = array( PHP_APE_Type_Angle::Format_DDDMMSSMicro, PHP_APE_Type_Angle::Format_DDDMMSS );
    
    // HTML
    $oDataSpace = new PHP_APE_DataSpace_HTML();
    $oAngle = new PHP_APE_Type_Angle();
    $oAngle->setValue( $oAngle->getSampleValue() );

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(FormatAngle):begin -->\r\n";
    $sOutput .= '<SELECT ONCHANGE="javascript:top.location.replace(PHP_APE_URL_addQuery(this.value,top.location.href.toString()));">';
    $sOutput .= '<OPTION VALUE="">'.$oDataSpace->formatData( $oAngle, $mCurrent ).'</OPTION>';
    foreach( $amChoicesList as $mChoice )
      $sOutput .= '<OPTION VALUE="'.ltrim( $roEnvironment->makePreferencesURL( array( 'php_ape.data.format.angle' => $mChoice ), null ), '?' ).'">'.$oDataSpace->formatData( $oAngle, $mChoice ).'</OPTION>';
    $sOutput .= '</SELECT>';
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(FormatAngle):end -->\r\n";
    return $sOutput;
  }

  /** Returns a selectable empty data hiding (preference) list
   *
   * @return string
   */
  public static function htmlPreferenceHideEmptyData()
  {
    // Environment
    $roEnvironment =& PHP_APE_HTML_Workspace::useEnvironment();
    $asResources = $roEnvironment->loadProperties( 'PHP_APE_HTML_Components' );
    $mCurrent = (integer)$roEnvironment->getUserParameter( 'php_ape.html.data.hide.empty' );
    $amChoicesList = array( 0, 1 );
    $amChoicesText = array( 0 => $asResources['label.preference.data.empty.show'], 1 => $asResources['label.preference.data.empty.hide'] );
    
    // HTML
    $oDataSpace = new PHP_APE_DataSpace_HTML();

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(HideEmpty):begin -->\r\n";
    $sOutput .= '<SELECT ONCHANGE="javascript:top.location.replace(PHP_APE_URL_addQuery(this.value,top.location.href.toString()));">';
    $sOutput .= '<OPTION VALUE="">'.$oDataSpace->encodeData( array_key_exists( $mCurrent, $amChoicesText ) ? $amChoicesText[$mCurrent] : $mCurrent ).'</OPTION>';
    foreach( $amChoicesList as $mChoice )
      $sOutput .= '<OPTION VALUE="'.ltrim( $roEnvironment->makePreferencesURL( array( 'php_ape.html.data.hide.empty' => $mChoice ), null ), '?' ).'">'.$oDataSpace->encodeData( $amChoicesText[$mChoice] ).'</OPTION>';
    $sOutput .= '</SELECT>';
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(HideEmpty):end -->\r\n";
    return $sOutput;
  }

  /** Returns a selectable optional data hiding (preference) list
   *
   * @return string
   */
  public static function htmlPreferenceHideOptionalData()
  {
    // Environment
    $roEnvironment =& PHP_APE_HTML_Workspace::useEnvironment();
    $asResources = $roEnvironment->loadProperties( 'PHP_APE_HTML_Components' );
    $mCurrent = (integer)$roEnvironment->getUserParameter( 'php_ape.html.data.hide.optional' );
    $amChoicesList = array( 0, 1 );
    $amChoicesText = array( 0 => $asResources['label.preference.data.optional.show'], 1 => $asResources['label.preference.data.optional.hide'] );
    
    // HTML
    $oDataSpace = new PHP_APE_DataSpace_HTML();

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(HideOptional):begin -->\r\n";
    $sOutput .= '<SELECT ONCHANGE="javascript:top.location.replace(PHP_APE_URL_addQuery(this.value,top.location.href.toString()));">';
    $sOutput .= '<OPTION VALUE="">'.$oDataSpace->encodeData( array_key_exists( $mCurrent, $amChoicesText ) ? $amChoicesText[$mCurrent] : $mCurrent ).'</OPTION>';
    foreach( $amChoicesList as $mChoice )
      $sOutput .= '<OPTION VALUE="'.ltrim( $roEnvironment->makePreferencesURL( array( 'php_ape.html.data.hide.optional' => $mChoice ), null ), '?' ).'">'.$oDataSpace->encodeData( $amChoicesText[$mChoice] ).'</OPTION>';
    $sOutput .= '</SELECT>';
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(HideOptional):end -->\r\n";
    return $sOutput;
  }

  /** Returns a selectable CSS (preference) list
   *
   * @return string
   */
  public static function htmlPreferenceCSS()
  {
    // Environment
    $roEnvironment =& PHP_APE_HTML_Workspace::useEnvironment();
    $mCurrent = $roEnvironment->getUserParameter( 'php_ape.html.css' );
    $amChoicesList = $roEnvironment->getVolatileParameter( 'php_ape.html.css.list' );
    
    // HTML
    $oDataSpace = new PHP_APE_DataSpace_HTML();

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(CSS):begin -->\r\n";
    $sOutput .= '<SELECT ONCHANGE="javascript:top.location.replace(PHP_APE_URL_addQuery(this.value,top.location.href.toString()));">';
    $sOutput .= '<OPTION VALUE="">'.$oDataSpace->encodeData( $mCurrent ).'</OPTION>';
    foreach( $amChoicesList as $mChoice )
      $sOutput .= '<OPTION VALUE="'.ltrim( $roEnvironment->makePreferencesURL( array( 'php_ape.html.css' => $mChoice ), null ), '?' ).'">'.$oDataSpace->encodeData( $mChoice ).'</OPTION>';
    $sOutput .= '</SELECT>';
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(CSS):end -->\r\n";
    return $sOutput;
  }

  /** Returns a selectable icon display (preference) list
   *
   * @return string
   */
  public static function htmlPreferenceIcon()
  {
    // Environment
    $roEnvironment =& PHP_APE_HTML_Workspace::useEnvironment();
    $asResources = $roEnvironment->loadProperties( 'PHP_APE_HTML_Components' );
    $mCurrent = $roEnvironment->getUserParameter( 'php_ape.html.display.icon' );
    $amChoicesList = array( PHP_APE_HTML_SmartTags::Icon_Hide, PHP_APE_HTML_SmartTags::Icon_Show, PHP_APE_HTML_SmartTags::Icon_NoLabel );
    $amChoicesText = array( PHP_APE_HTML_SmartTags::Icon_Hide => $asResources['label.preference.display.icon.hide'], PHP_APE_HTML_SmartTags::Icon_Show => $asResources['label.preference.display.icon.show'], PHP_APE_HTML_SmartTags::Icon_NoLabel => $asResources['label.preference.display.icon.nolabel'] );
    
    // HTML
    $oDataSpace = new PHP_APE_DataSpace_HTML();

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(Icon):begin -->\r\n";
    $sOutput .= '<SELECT ONCHANGE="javascript:top.location.replace(PHP_APE_URL_addQuery(this.value,top.location.href.toString()));">';
    $sOutput .= '<OPTION VALUE="">'.$oDataSpace->encodeData( array_key_exists( $mCurrent, $amChoicesText ) ? $amChoicesText[$mCurrent] : $mCurrent ).'</OPTION>';
    foreach( $amChoicesList as $mChoice )
      $sOutput .= '<OPTION VALUE="'.ltrim( $roEnvironment->makePreferencesURL( array( 'php_ape.html.display.icon' => $mChoice ), null ), '?' ).'">'.$oDataSpace->encodeData( $amChoicesText[$mChoice] ).'</OPTION>';
    $sOutput .= '</SELECT>';
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(Icon):end -->\r\n";
    return $sOutput;
  }

  /** Returns a selectable popup display (preference) list
   *
   * @return string
   */
  public static function htmlPreferencePopup()
  {
    // Environment
    $roEnvironment =& PHP_APE_HTML_Workspace::useEnvironment();
    $asResources = $roEnvironment->loadProperties( 'PHP_APE_HTML_Components' );
    $bPopupUse = $roEnvironment->getUserParameter( 'php_ape.html.popup.use' );
    $iPopupWidth = $roEnvironment->getUserParameter( 'php_ape.html.popup.width' );
    $iPopupHeight = $roEnvironment->getUserParameter( 'php_ape.html.popup.height' );
    $mCurrent = $bPopupUse ? $iPopupWidth.'x'.$iPopupHeight : 0;
    $amChoicesList = array( 0, '640x480', '800x600', '1024x768', '1280x1024' );
    $amChoicesText = array( 0 => $asResources['label.preference.display.popup.disable'], '640x480' => '640x480', '800x600' => '800x600', '1024x768' => '1024x768', '1280x1024' => '1280x1024' );
    
    // HTML
    $oDataSpace = new PHP_APE_DataSpace_HTML();

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(Icon):begin -->\r\n";
    $sOutput .= '<SELECT ONCHANGE="javascript:top.location.replace(PHP_APE_URL_addQuery(this.value,top.location.href.toString()));">';
    $sOutput .= '<OPTION VALUE="">'.$oDataSpace->encodeData( array_key_exists( $mCurrent, $amChoicesText ) ? $amChoicesText[$mCurrent] : $mCurrent ).'</OPTION>';
    foreach( $amChoicesList as $mChoice )
    {
      if( count( $aiGeometry = explode( 'x', $mChoice ) ) >= 2 )
        $sURL = $roEnvironment->makePreferencesURL( array( 'php_ape.html.popup.use' => 1, 'php_ape.html.popup.width' => $aiGeometry[0], 'php_ape.html.popup.height' => $aiGeometry[1] ), null );
      else
        $sURL = $roEnvironment->makePreferencesURL( array( 'php_ape.html.popup.use' => 0 ), null );
      $sOutput .= '<OPTION VALUE="'.ltrim( $sURL, '?' ).'">'.$oDataSpace->encodeData( $amChoicesText[$mChoice] ).'</OPTION>';
    }
    $sOutput .= '</SELECT>';
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- PREFERENCE(Icon):end -->\r\n";
    return $sOutput;
  }

  /** Returns a (locale) preferences-setting bar
   *
   * @param string $sStyle Associated (cell) CSS style directives (<SAMP>STYLE="..."</SAMP>)
   * @param string $sTableStyle Associated (table) CSS style directives (<SAMP>STYLE="..."</SAMP>)
   * @param string $sIconSize Icons' size
   * @return string
   */
  public static function htmlPreferencesLocaleBar( $sStyle = null, $sTableStyle = null, $sIconSize = 'S' )
  {
    // Sanitize input
    $sIconSize = strtoupper( PHP_APE_Type_Char::parseValue( $sIconSize ) );

    // Resources
    $asResources = PHP_APE_HTML_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_HTML_Components' );

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen( $sStyle, $sTableStyle );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.preferences.locale'].':', $sIconSize.'-locale', null, $asResources['tooltip.preferences.locale'], null, false, false );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
    $sOutput .= self::htmlPreferenceLanguage();
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
    $sOutput .= self::htmlPreferenceCountry();
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
    $sOutput .= self::htmlPreferenceCurrency();
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();
    return $sOutput;
  }

  /** Returns a (format) preferences-setting bar
   *
   * @param string $sStyle Associated (cell) CSS style directives (<SAMP>STYLE="..."</SAMP>)
   * @param string $sTableStyle Associated (table) CSS style directives (<SAMP>STYLE="..."</SAMP>)
   * @param string $sIconSize Icons' size
   * @return string
   */
  public static function htmlPreferencesFormatBar( $sStyle = null, $sTableStyle = null, $sIconSize = 'S' )
  {
    // Sanitize input
    $sIconSize = strtoupper( PHP_APE_Type_Char::parseValue( $sIconSize ) );

    // Resources
    $asResources = PHP_APE_HTML_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_HTML_Components' );

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen( $sStyle, $sTableStyle );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.preferences.format'].':', $sIconSize.'-preferences', null, $asResources['tooltip.preferences.format'], null, false, false );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
    $sOutput .= self::htmlPreferenceBoolean();
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
    $sOutput .= self::htmlPreferenceDate();
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
    $sOutput .= self::htmlPreferenceTime();
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
    $sOutput .= self::htmlPreferenceAngle();
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();
    return $sOutput;
  }

  /** Returns a (data) preferences-setting bar
   *
   * @param string $sStyle Associated (cell) CSS style directives (<SAMP>STYLE="..."</SAMP>)
   * @param string $sTableStyle Associated (table) CSS style directives (<SAMP>STYLE="..."</SAMP>)
   * @param string $sIconSize Icons' size
   * @return string
   */
  public static function htmlPreferencesDataBar( $sStyle = null, $sTableStyle = null, $sIconSize = 'S' )
  {
    // Sanitize input
    $sIconSize = strtoupper( PHP_APE_Type_Char::parseValue( $sIconSize ) );

    // Resources
    $asResources = PHP_APE_HTML_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_HTML_Components' );

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen( $sStyle, $sTableStyle );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.preferences.data'].':', $sIconSize.'-data', null, $asResources['tooltip.preferences.data'], null, false, false );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
    $sOutput .= self::htmlPreferenceHideEmptyData();
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
    $sOutput .= self::htmlPreferenceHideOptionalData();
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();
    return $sOutput;
  }

  /** Returns a (display) preferences-setting bar
   *
   * @param string $sStyle Associated (cell) CSS style directives (<SAMP>STYLE="..."</SAMP>)
   * @param string $sTableStyle Associated (table) CSS style directives (<SAMP>STYLE="..."</SAMP>)
   * @param string $sIconSize Icons' size
   * @return string
   */
  public static function htmlPreferencesDisplayBar( $sStyle = null, $sTableStyle = null, $sIconSize = 'S' )
  {
    // Sanitize input
    $sIconSize = strtoupper( PHP_APE_Type_Char::parseValue( $sIconSize ) );

    // Resources
    $asResources = PHP_APE_HTML_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_HTML_Components' );

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen( $sStyle, $sTableStyle );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.preferences.display'].':', $sIconSize.'-display', null, $asResources['tooltip.preferences.display'], null, false, false );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
    $sOutput .= self::htmlPreferenceCSS();
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
    $sOutput .= self::htmlPreferenceIcon();
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
    $sOutput .= self::htmlPreferencePopup();
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();
    return $sOutput;
  }

  /** Returns a (javascript) popup opening directive
   *
   * @return string
   */
  public static function javascriptPopup( $sURL, $mID = null, $iWidth = null, $iHeight = null, $bMenuBar = false, $bToolBar = false, $bLocation = false, $bScrollBars = true, $bDialog = true )
  {
    // Environment
    $roEnvironment =& PHP_APE_HTML_WorkSpace::useEnvironment();

    // Javascript
    $oDataSpace = new PHP_APE_DataSpace_JavaScript();

    // Sanitize input
    $sURL = (string)$sURL;
    $mID = PHP_APE_Type_Index::parseValue( $mID );
    $mID = $roEnvironment->getVolatileParameter( 'php_ape.application.id' ).'_'.( $mID ? $mID : 'Popup' );
    if( is_null( $iWidth ) )
      $iWidth = $roEnvironment->getUserParameter( 'php_ape.html.popup.width' );
    $iWidth = (integer)$iWidth;
    if( is_null( $iHeight ) )
      $iHeight = $roEnvironment->getUserParameter( 'php_ape.html.popup.height' );
    $iHeight = (integer)$iHeight;

    // Output
    $sOutput = 'wPopup=window.open(';
    $sOutput .= '\''.$oDataSpace->encodeData($sURL).'\',';
    $sOutput .= '\''.$oDataSpace->encodeData($mID).'\',';
    $sOutput .= '\'width='.$iWidth.',height='.$iHeight.',menubar='.($bMenuBar?'yes':'no').',toolbar='.($bToolBar?'yes':'no').',location='.($bLocation?'yes':'no').',scrollbars='.($bScrollBars?'yes':'no').',resizable=yes,dialog='.($bDialog?'yes':'no').',modal='.($bDialog?'yes':'no').'\'';
    $sOutput .= ',true';
    $sOutput .= ');wPopup.focus();';
    return $sOutput;
  }

  /** Returns a (javascript) date-chooser popup opening directive
   *
   * @return string
   */
  public static function javascriptDateChooser( $sTargetID, $iDate = null )
  {
    // Sanitize input
    $sTargetID = PHP_APE_Type_Index::parseValue( $sTargetID );

    // Environment
    $roEnvironment =& PHP_APE_HTML_WorkSpace::useEnvironment();

    // Popup
    $sURL = $roEnvironment->getVolatileParameter( 'php_ape.html.htdocs.url' ).'/DateChooser.php';
    $sURL = PHP_APE_Util_URL::addVariable( $sURL, array( 'target' => $sTargetID ) );
    if( $iDate )
    {
      try { $sURL = PHP_APE_Util_URL::addVariable( $sURL, array( 'date' => PHP_APE_Type_Date::formatValue( $iDate, null, PHP_APE_Type_Date::Format_ISO ) ) ); }
      catch( PHP_APE_Type_Exception $e ) { } // ignore invalid date
    }
    return self::javascriptPopup( $sURL, 'DateChooser', 200, 200, false, false, false, false );
  }

}
