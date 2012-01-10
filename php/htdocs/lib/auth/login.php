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
 * @subpackage WUI
 */

/** Authentication (login) page
 */

// Load APE
require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
require_once( PHP_APE_ROOT.'/lib/html/load.php' );
require_once( PHP_APE_ROOT.'/lib/html/data/load.php' );

// Start session
session_start();

// Environment
$roEnvironment =& PHP_APE_Auth_WorkSpace::useEnvironment();

// Controller
$oController = new PHP_APE_HTML_Controller( 'PHP_APE_Authentication', dirname( $_SERVER['SCRIPT_NAME'] ), 'new', 'login.php' );
$rasRequestData =& $oController->useRequestData();
$sSource = $oController->getSource();
$sDestination = $oController->getDestination();
$bIsPopup = $oController->isPopup();
$asErrors = $oController->getErrors();

// Goto URL
$sGoto = null;
if( array_key_exists( '__GOTO', $_GET ) ) $sGoto = $_GET['__GOTO'];
elseif( array_key_exists( '__GOTO', $_POST ) ) $sGoto = $_POST['__GOTO'];
elseif( array_key_exists( '__GOTO', $rasRequestData ) ) $sGoto = $rasRequestData['__GOTO'];
$sGoto = PHP_APE_Type_Path::parseValue( $sGoto );
// ... target
$sTarget = 'top';
if( array_key_exists( '__TARGET', $_GET ) ) $sTarget = $_GET['__TARGET'];
elseif( array_key_exists( '__TARGET', $_POST ) ) $sTarget = $_POST['__TARGET'];
elseif( array_key_exists( '__TARGET', $rasRequestData ) ) $sTarget = $rasRequestData['__TARGET'];

// Browsing variables
$amPasstrhuVariables = array( '__GOTO' => $sGoto, '__TARGET' => $sTarget );

// Content
try
{

  // ... Authentication handler
  $roAuthenticationHandler = $roEnvironment->useAuthenticationHandler();

  // ... HTML
  echo PHP_APE_HTML_Tags::htmlDocumentOpen();

  // ... HEAD
  echo PHP_APE_HTML_Tags::htmlHeadOpen();
  echo PHP_APE_HTML_Tags::htmlHeadCharSet();
  echo PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
  echo PHP_APE_HTML_SmartTags::htmlCSS();
  echo PHP_APE_HTML_Tags::htmlHeadTitle();
  echo PHP_APE_HTML_Tags::htmlHeadClose();

  // ... BODY
  echo PHP_APE_HTML_Tags::htmlBodyOpen( 'APE' );
  echo '<DIV CLASS="APE">'."\r\n";

  // ... Header
  //if( !$bIsPopup ) echo $oController->htmlHeader();

  // ... Title
  //echo $oController->htmlTitle();

  // ... Content prefix
  //echo $oController->htmlContentPrefix();
  echo '<DIV STYLE="WIDTH:500px;MARGIN:auto;">'."\r\n";

  // ... Content title
  echo $oController->htmlContentTitle( $roAuthenticationHandler );
  echo PHP_APE_HTML_SmartTags::htmlSpacer();

  // Authentication
  if( !$roEnvironment->isAuthenticationAllowed() )
  {
    $asResources = $roEnvironment->loadProperties( 'PHP_APE_Auth_Authentication' );
    echo PHP_APE_HTML_Components::htmlBlockError( $asResources['label.authenticationdenied'], $asResources['tooltip.authenticationdenied'], 'S-lock' );
  }
  else if( $roAuthenticationHandler->hasArgumentSet() )
  { // ... authentication handler requires arguments

    switch( $sDestination )
    {

    case 'insert':
      // Try
      $rasErrors = null;
      try
      {
        // ... login
        $oController->executeInsertFunction( $roAuthenticationHandler, $rasErrors );
        echo PHP_APE_HTML_Components::htmlAuthenticationSuccess( $roEnvironment->getAuthenticationToken() );
        if( $bIsPopup )
        {
          $asResources = $roEnvironment->loadProperties( 'PHP_APE_Auth_Authentication' );
          echo PHP_APE_HTML_Components::htmlBlockWarning( $asResources['label.authenticationpopupwarning'], $asResources['tooltip.authenticationpopupwarning'] );
        }

        // ... redirect
        if( $sGoto ) PHP_APE_Util_BrowserControl::redirect( $sGoto, $bIsPopup ? 'opener.'.$sTarget : $sTarget, !$bIsPopup, null, $bIsPopup ? 0 : 1 );
        if( $bIsPopup ) PHP_APE_Util_BrowserControl::close( null, 3 );
      }
      catch( PHP_APE_HTML_Data_Exception $e )
      {
        // ... redirect
        $sURL = $oController->makeRequestURL( 'login.php', $sSource, null, $amPasstrhuVariables, $rasErrors, $bIsPopup );
        PHP_APE_Util_BrowserControl::redirect( $sURL, null, true );
      }
      catch( PHP_APE_Auth_AuthenticationException $e )
      {
        // ... errors
        $e->logError();
        echo PHP_APE_HTML_Components::htmlAuthenticationException( $e );

        // ... redirect
        $sURL = $oController->makeRequestURL( 'login.php', $sSource, null, $amPasstrhuVariables, null, $bIsPopup );
        PHP_APE_Util_BrowserControl::redirect( $sURL, null, true, null, 2 );
      }
      break;

    default:
      // Resources
      $asResources = $roEnvironment->loadProperties( 'PHP_APE_HTML_Components' );
      
      // Output
      $oController->prepareInsertFunction( $roAuthenticationHandler );
      // ... errors
      if( count( $asErrors ) ) echo PHP_APE_HTML_Components::htmlDataException();
      // ... data
      $oHTML = $oController->getFormView( $roAuthenticationHandler, null, null, $amPasstrhuVariables );
      $oHTML->prefButtonInsert( $asResources['label.authentication.login'], $asResources['tooltip.authentication.login'] );
      echo $oHTML->html();
    }

  }
  else
  { // ... authentication handler requires NO arguments

    // Try
    try
    {
      // ... login
      $roAuthenticationHandler->execute();
      echo PHP_APE_HTML_Components::htmlAuthenticationSuccess( $roEnvironment->getAuthenticationToken() );

      // ... redirect
      if( $sGoto ) PHP_APE_Util_BrowserControl::redirect( $sGoto, $bIsPopup ? 'opener.'.$sTarget : $sTarget, !$bIsPopup, null, $bIsPopup ? 0 : 2 );
      if( $bIsPopup ) PHP_APE_Util_BrowserControl::close( null, 2 );
    }
    catch( PHP_APE_Auth_AuthenticationException $e )
    {
      // ... errors
      $e->logError();
      echo PHP_APE_HTML_Components::htmlAuthenticationException( $e );
    }
    
  }

  // ... Content suffix
  echo '</DIV>'."\r\n";
  //echo $oController->htmlContentSuffix();

  // ... Footer
  //if( !$bIsPopup ) echo $oController->htmlFooter();

  // ... End
  echo '</DIV>'."\r\n";
  echo PHP_APE_HTML_Tags::htmlBodyClose();

  echo PHP_APE_HTML_Tags::htmlDocumentClose();

}
catch( PHP_APE_Exception $e )
{
  echo PHP_APE_HTML_Components::htmlUnexpectedException( $e );
}

// End session
session_write_close();
