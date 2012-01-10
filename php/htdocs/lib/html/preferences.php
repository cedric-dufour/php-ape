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

/** Preferences (customization) page
 */

// Load APE
require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
require_once( PHP_APE_ROOT.'/lib/html/load.php' );
require_once( PHP_APE_ROOT.'/lib/html/data/load.php' );

// Start session
session_start();

// Controller
$oController = new PHP_APE_HTML_Controller( 'PHP_APE_Preferences', dirname( $_SERVER['SCRIPT_NAME'] ) );
$rasRequestData =& $oController->useRequestData();
$bIsPopup = $oController->isPopup();

// Dataspace
$oDataSpace_JavaScript = new PHP_APE_DataSpace_JavaScript();

// Goto URL
$sGoto = null;
if( array_key_exists( '__GOTO', $_GET ) ) $sGoto = $_GET['__GOTO'];
elseif( array_key_exists( '__GOTO', $_POST ) ) $sGoto = $_POST['__GOTO'];
elseif( array_key_exists( '__GOTO', $rasRequestData ) ) $sGoto = $rasRequestData['__GOTO'];
// ... target
$sTarget = 'top';
if( array_key_exists( '__TARGET', $_GET ) ) $sTarget = $_GET['__TARGET'];
elseif( array_key_exists( '__TARGET', $_POST ) ) $sTarget = $_POST['__TARGET'];
elseif( array_key_exists( '__TARGET', $rasRequestData ) ) $sTarget = $rasRequestData['__TARGET'];

// Resources
$asResources = PHP_APE_HTML_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_HTML_Components' );

// Content
try
{

  // ... HTML
  echo PHP_APE_HTML_Tags::htmlDocumentOpen();

  // ... HEAD
  echo PHP_APE_HTML_Tags::htmlHeadOpen();
  echo PHP_APE_HTML_Tags::htmlHeadCharSet();
  echo PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
  echo PHP_APE_HTML_SmartTags::htmlCSS();
  echo PHP_APE_HTML_Tags::htmlHeadTitle();
  if( $bIsPopup ) PHP_APE_Util_BrowserControl::refresh( 'opener' );
  echo PHP_APE_HTML_Tags::htmlHeadClose();

  // ... BODY
  echo PHP_APE_HTML_Tags::htmlBodyOpen( 'APE' );
  echo '<DIV CLASS="APE">'."\r\n";

  // ... Header
  if( !$bIsPopup ) echo $oController->htmlHeader();

  // ... Title
  echo $oController->htmlTitle();

  // ... Content prefix
  echo $oController->htmlContentPrefix();

  // ... Content title
  echo $oController->htmlContentTitle( new PHP_APE_Data_Constant( 'title', new PHP_APE_Type_String(), $asResources['label.preferences'], $asResources['tooltip.preferences'] ) );
  echo PHP_APE_HTML_SmartTags::htmlSpacer();

  // ... Content: column 1
  echo '<BLOCKQUOTE>';
  echo PHP_APE_HTML_SmartTags::htmlColumnOpen( 'WIDTH:50% !important;', 'WIDTH:100% !important;' );

  // ... Content: locale
  echo PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.preferences.locale'].':', 'M-locale', null, $asResources['tooltip.preferences.locale'], null, true, false, 'H3' );
  echo '<BLOCKQUOTE>';
  echo PHP_APE_HTML_SmartTags::htmlColumnOpen( 'WIDTH:150px !important;' );
  echo PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.preference.locale.language'].':', null, null, $asResources['tooltip.preference.locale.language'] );
  echo PHP_APE_HTML_SmartTags::htmlColumnAdd( 'PADDING-LEFT:2px !important;PADDING-BOTTOM:2px !important;', false );
  echo PHP_APE_HTML_Components::htmlPreferenceLanguage();
  echo PHP_APE_HTML_SmartTags::htmlColumnAddRow();
  echo PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.preference.locale.country'].':', null, null, $asResources['tooltip.preference.locale.country'] );
  echo PHP_APE_HTML_SmartTags::htmlColumnAdd( 'PADDING-LEFT:2px !important;PADDING-BOTTOM:2px !important;', false );
  echo PHP_APE_HTML_Components::htmlPreferenceCountry();
  echo PHP_APE_HTML_SmartTags::htmlColumnAddRow();
  echo PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.preference.locale.currency'].':', null, null, $asResources['tooltip.preference.locale.currency'] );
  echo PHP_APE_HTML_SmartTags::htmlColumnAdd( 'PADDING-LEFT:2px !important;PADDING-BOTTOM:2px !important;', false );
  echo PHP_APE_HTML_Components::htmlPreferenceCurrency();
  echo PHP_APE_HTML_SmartTags::htmlColumnClose();
  echo '</BLOCKQUOTE>';

  // ... Content: format
  echo PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.preferences.format'].':', 'M-preferences', null, $asResources['tooltip.preferences.format'], null, true, false, 'H3' );
  echo '<BLOCKQUOTE>';
  echo PHP_APE_HTML_SmartTags::htmlColumnOpen( 'WIDTH:150px !important;' );
  echo PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.preference.format.numeric'].':', null, null, $asResources['tooltip.preference.format.numeric'] );
  echo PHP_APE_HTML_SmartTags::htmlColumnAdd( 'PADDING-LEFT:2px !important;PADDING-BOTTOM:2px !important;', false );
  echo PHP_APE_HTML_Components::htmlPreferenceNumeric();
  echo PHP_APE_HTML_SmartTags::htmlColumnAddRow();
  echo PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.preference.format.date'].':', null, null, $asResources['tooltip.preference.format.date'] );
  echo PHP_APE_HTML_SmartTags::htmlColumnAdd( 'PADDING-LEFT:2px !important;PADDING-BOTTOM:2px !important;', false );
  echo PHP_APE_HTML_Components::htmlPreferenceDate();
  echo PHP_APE_HTML_SmartTags::htmlColumnAddRow();
  echo PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.preference.format.time'].':', null, null, $asResources['tooltip.preference.format.time'] );
  echo PHP_APE_HTML_SmartTags::htmlColumnAdd( 'PADDING-LEFT:2px !important;PADDING-BOTTOM:2px !important;', false );
  echo PHP_APE_HTML_Components::htmlPreferenceTime();
  echo PHP_APE_HTML_SmartTags::htmlColumnAddRow();
  echo PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.preference.format.angle'].':', null, null, $asResources['tooltip.preference.format.angle'] );
  echo PHP_APE_HTML_SmartTags::htmlColumnAdd( 'PADDING-LEFT:2px !important;PADDING-BOTTOM:2px !important;', false );
  echo PHP_APE_HTML_Components::htmlPreferenceAngle();
  echo PHP_APE_HTML_SmartTags::htmlColumnAddRow();
  echo PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.preference.format.boolean'].':', null, null, $asResources['tooltip.preference.format.boolean'] );
  echo PHP_APE_HTML_SmartTags::htmlColumnAdd( 'PADDING-LEFT:2px !important;PADDING-BOTTOM:2px !important;', false );
  echo PHP_APE_HTML_Components::htmlPreferenceBoolean();
  echo PHP_APE_HTML_SmartTags::htmlColumnClose();
  echo '</BLOCKQUOTE>';

  // ... Content: column 2
  echo PHP_APE_HTML_SmartTags::htmlColumnAdd();

  // ... Content: display
  echo PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.preferences.display'].':', 'M-display', null, $asResources['tooltip.preferences.display'], null, true, false, 'H3' );
  echo '<BLOCKQUOTE>';
  echo PHP_APE_HTML_SmartTags::htmlColumnOpen( 'WIDTH:150px !important;' );
  echo PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.preference.display.style'].':', null, null, $asResources['tooltip.preference.display.style'] );
  echo PHP_APE_HTML_SmartTags::htmlColumnAdd( 'PADDING-LEFT:2px !important;PADDING-BOTTOM:2px !important;', false );
  echo PHP_APE_HTML_Components::htmlPreferenceCSS();
  echo PHP_APE_HTML_SmartTags::htmlColumnAddRow();
  echo PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.preference.display.icon'].':', null, null, $asResources['tooltip.preference.display.icon'] );
  echo PHP_APE_HTML_SmartTags::htmlColumnAdd( 'PADDING-LEFT:2px !important;PADDING-BOTTOM:2px !important;', false );
  echo PHP_APE_HTML_Components::htmlPreferenceIcon();
  echo PHP_APE_HTML_SmartTags::htmlColumnAddRow();
  echo PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.preference.display.popup'].':', null, null, $asResources['tooltip.preference.display.popup'] );
  echo PHP_APE_HTML_SmartTags::htmlColumnAdd( 'PADDING-LEFT:2px !important;PADDING-BOTTOM:2px !important;', false );
  echo PHP_APE_HTML_Components::htmlPreferencePopup();
  echo PHP_APE_HTML_SmartTags::htmlColumnClose();
  echo '</BLOCKQUOTE>';

  // ... Content: data
  echo PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.preferences.data'].':', 'M-data', null, $asResources['tooltip.preferences.data'], null, true, false, 'H3' );
  echo '<BLOCKQUOTE>';
  echo PHP_APE_HTML_SmartTags::htmlColumnOpen( 'WIDTH:150px !important;' );
  echo PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.preference.data.empty'].':', null, null, $asResources['tooltip.preference.data.empty'] );
  echo PHP_APE_HTML_SmartTags::htmlColumnAdd( 'PADDING-LEFT:2px !important;PADDING-BOTTOM:2px !important;', false );
  echo PHP_APE_HTML_Components::htmlPreferenceHideEmptyData();
  echo PHP_APE_HTML_SmartTags::htmlColumnAddRow();
  echo PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.preference.data.optional'].':', null, null, $asResources['tooltip.preference.data.optional'] );
  echo PHP_APE_HTML_SmartTags::htmlColumnAdd( 'PADDING-LEFT:2px !important;PADDING-BOTTOM:2px !important;', false );
  echo PHP_APE_HTML_Components::htmlPreferenceHideOptionalData();
  echo PHP_APE_HTML_SmartTags::htmlColumnClose();
  echo '</BLOCKQUOTE>';

  // ... Content: column end
  echo '</BLOCKQUOTE>';
  echo PHP_APE_HTML_SmartTags::htmlColumnClose();

  // ... Content suffix
  echo $oController->htmlContentSuffix();

  // ... Footer
  if( !$bIsPopup )
  {
    echo PHP_APE_HTML_SmartTags::htmlSeparator();
    echo '<DIV CLASS="do-not-print" STYLE="FLOAT:right;PADDING:2px;">'."\r\n";
    echo PHP_APE_HTML_Components::htmlBack( $sGoto, $sTarget );
    echo '</DIV>'."\r\n";
    echo '<DIV STYLE="CLEAR:both;"></DIV>'."\r\n";
  }

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
