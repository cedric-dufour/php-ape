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
 * @subpackage Control
 */

/** HTML data filter utilities
 *
 * @package PHP_APE_HTML
 * @subpackage Control
 */
class PHP_APE_HTML_Data_Filter
extends PHP_APE_HTML_Data_Any
{

  /*
   * METHODS: static
   ********************************************************************************/

  /** Returns the HTML page URL implementing the given data filter
   *
   * @param mixed $mID HTML element identifier (ID)
   * @param string $sURL Target URL
   * @param PHP_APE_Data_Filter $oFilter Data filter object
   * @return string
   */
  public static function makeURL( $mID, $sURL, PHP_APE_Data_Filter $oFilter )
  {
    // Retrieve resource identifier (ID)
    $sRID = PHP_APE_HTML_Data_Any::makeRID( $mID );

    // Return URL
    $sURL = preg_replace( '/&?PHP_APE_DF_'.$sRID.'=[^&]*/is', null, $sURL );
    $sURL = ltrim( $sURL, '&' );
    return PHP_APE_Util_URL::addVariable( $sURL, array( 'PHP_APE_DF_'.$sRID => $oFilter->toProperties() ) );
  }

  /** Parses the HTML request parameters and returns the corresponding data filter
   *
   * <P><B>NOTE:</B> If no corresponding request parameters are found, <SAMP>null</SAMP> is returned.</P>
   *
   * @param mixed $mID HTML element identifier (ID)
   * @return PHP_APE_Data_Filter
   */
  public static function parseRequest( $mID )
  {
    // Retrieve resource identifier (ID)
    $sRID = PHP_APE_HTML_Data_Any::makeRID( $mID );

    // Parse request
    $oFilter = null;
    if( isset( $_POST['PHP_APE_DF_'.$sRID] ) )
    {
      $oFilter = new PHP_APE_Data_Filter();
      $oFilter->fromProperties( $_POST['PHP_APE_DF_'.$sRID] );
    }
    elseif( isset( $_GET['PHP_APE_DF_'.$sRID] ) )
    {
      $oFilter = new PHP_APE_Data_Filter();
      $oFilter->fromProperties( $_GET['PHP_APE_DF_'.$sRID] );
    }

    // Return filter object
    return $oFilter;
  }

  /** Returns the HTML page URL implementing the given subset data filter
   *
   * @param mixed $mID HTML element identifier (ID)
   * @param string $sURL Target URL
   * @param PHP_APE_Data_Filter $oSubsetFilter Data filter object
   * @return string
   */
  public static function makeSubsetURL( $mID, $sURL, PHP_APE_Data_Filter $oSubsetFilter )
  {
    // Retrieve resource identifier (ID)
    $sRID = PHP_APE_HTML_Data_Any::makeRID( $mID );

    // Return URL
    $sURL = preg_replace( '/&?PHP_APE_DSF_'.$sRID.'=[^&]*/is', null, $sURL );
    $sURL = ltrim( $sURL, '&' );
    return PHP_APE_Util_URL::addVariable( $sURL, array( 'PHP_APE_DSF_'.$sRID => $oSubsetFilter->toProperties() ) );
  }

  /** Parses the HTML request parameters and returns the corresponding subset data filter
   *
   * <P><B>NOTE:</B> If no corresponding request parameters are found, <SAMP>null</SAMP> is returned.</P>
   *
   * @param mixed $mID HTML element identifier (ID)
   * @return PHP_APE_Data_Filter
   */
  public static function parseSubsetRequest( $mID )
  {
    // Retrieve resource identifier (ID)
    $sRID = PHP_APE_HTML_Data_Any::makeRID( $mID );

    // Parse request
    $oSubsetFilter = null;
    if( isset( $_POST['PHP_APE_DSF_'.$sRID] ) )
    {
      $oSubsetFilter = new PHP_APE_Data_Filter();
      $oSubsetFilter->fromProperties( $_POST['PHP_APE_DSF_'.$sRID] );
    }
    elseif( isset( $_GET['PHP_APE_DSF_'.$sRID] ) )
    {
      $oSubsetFilter = new PHP_APE_Data_Filter();
      $oSubsetFilter->fromProperties( $_GET['PHP_APE_DSF_'.$sRID] );
    }

    // Return filter object
    return $oSubsetFilter;
  }

  /** Returns the HTML filter <SAMP>FORM</SAMP> prefix
   *
   * @param mixed $mID HTML element identifier (ID)
   * @param string $sGlobalCriteria Global (search) criteria
   * @return string
   */
  public static function htmlFormPrefix( $mID, $sGlobalCriteria = null )
  {
    // Retrieve resource identifier (ID)
    $sRID = PHP_APE_HTML_Data_Any::makeRID( $mID );

    // Let's execute only ONCE per page
    static $abCalled;
    if( isset( $abCalled[$sRID] ) ) return null;
    else $abCalled[$sRID] = true;

    // Sanitize input
    $sGlobalCriteria = (string)$sGlobalCriteria;

    // Data
    $sQuery = preg_replace( '/&?PHP_APE_DF_'.$sRID.'=[^&]*/is', null, $_SERVER['QUERY_STRING'] );
    $sQuery = preg_replace( '/&?PHP_APE_DS_'.$sRID.'=[^&]*/is', null, $sQuery );
    $sQuery = ltrim( $sQuery, '&' );

    // HTML
    $oDataSpace = new PHP_APE_DataSpace_HTML();

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    if( !isset( $abCalled['__JAVASCRIPT__'] ) )
    {
      $abCalled['__JAVASCRIPT__'] = true;
      if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FORM: PHP_APE_HTML_Data_Filter::htmlForm(Prefix) - JAVASCRIPT -->\r\n";
      $sOutput .= '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript"><!--'."\r\n";
      $sOutput .= "function PHP_APE_DF_onKeyPress(rid,event)\r\n";
      $sOutput .= "{\r\n";
      $sOutput .= " if( event.keyCode==13 ) PHP_APE_DF_apply(rid);\r\n";
      $sOutput .= " return true;\r\n";
      $sOutput .= "}\r\n";
      $sOutput .= "function PHP_APE_DF_global(rid,criteria)\r\n";
      $sOutput .= "{\r\n";
      $sOutput .= " form = document.forms['PHP_APE_DF_'+rid];\r\n";
      $sOutput .= " fvar = form.elements['PHP_APE_DF_'+rid];\r\n";
      $sOutput .= " if( fvar.disabled )\r\n";
      $sOutput .= " {\r\n";
      $sOutput .= "  form.__GLOBAL.value = criteria;\r\n";
      $sOutput .= "  form.__GLOBAL.disabled = false;\r\n";
      $sOutput .= " }\r\n";
      $sOutput .= "}\r\n";
      $sOutput .= "function PHP_APE_DF_apply(rid)\r\n";
      $sOutput .= "{\r\n";
      $sOutput .= " form = document.forms['PHP_APE_DF_'+rid];\r\n";
      $sOutput .= " fvar = form.elements['PHP_APE_DF_'+rid];\r\n";
      $sOutput .= " if( fvar.disabled )\r\n";
      $sOutput .= " {\r\n";
      $sOutput .= "  for( i=0; i<form.elements.length; i++ )\r\n";
      $sOutput .= "   if( !form.elements[i].value )\r\n";
      $sOutput .= "    form.elements[i].disabled = true;\r\n";
      $sOutput .= "  PHP_APE_IN_Form_args( form, fvar, true );\r\n";
      $sOutput .= "  form.elements['PHP_APE_DS_'+rid].disabled = false;\r\n";
      $sOutput .= "  PHP_APE_IN_Form_get( form );\r\n";
      $sOutput .= " }\r\n";
      $sOutput .= "}\r\n";
      $sOutput .= "function PHP_APE_DF_clear(rid)\r\n";
      $sOutput .= "{\r\n";
      $sOutput .= " form = document.forms['PHP_APE_DF_'+rid];\r\n";
      $sOutput .= " fvar = form.elements['PHP_APE_DF_'+rid];\r\n";
      $sOutput .= " if( fvar.disabled )\r\n";
      $sOutput .= " {\r\n";
      $sOutput .= "  for( i=0; i<form.elements.length; i++ ) form.elements[i].disabled = true;\r\n";
      $sOutput .= "  fvar.value = null;\r\n";
      $sOutput .= "  fvar.disabled = false;\r\n";
      $sOutput .= "  form.elements['PHP_APE_DS_'+rid].disabled = false;\r\n";
      $sOutput .= "  PHP_APE_IN_Form_get( form );\r\n";
      $sOutput .= " }\r\n";
      $sOutput .= "}\r\n";
      $sOutput .= "--></SCRIPT>\r\n";
    }
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FORM: PHP_APE_HTML_Data_Filter::htmlForm(Prefix) - BEGIN ('.$sRID.') -->\r\n";
    $sOutput .= '<FORM NAME="PHP_APE_DF_'.$sRID.'" ACTION="'.$_SERVER['PHP_SELF'].($sQuery?'?'.$sQuery:null).'" METHOD="get">'."\r\n";
    $sOutput .= '<INPUT TYPE="hidden" NAME="PHP_APE_DF_'.$sRID.'" DISABLED />'."\r\n";
    $sOutput .= '<INPUT TYPE="hidden" NAME="PHP_APE_DS_'.$sRID.'" DISABLED />'."\r\n";
    $sOutput .= '<INPUT TYPE="hidden" NAME="__GLOBAL" VALUE="'.$oDataSpace->encodeData( $sGlobalCriteria ).'" />'."\r\n";
    return $sOutput;
  }

  /** Returns the HTML filter <SAMP>FORM</SAMP> suffix
   *
   * @param mixed $mID HTML element identifier (ID)
   * @return string
   */
  public static function htmlFormSuffix( $mID )
  {
    // Retrieve resource identifier (ID)
    $sRID = PHP_APE_HTML_Data_Any::makeRID( $mID );

    // Let's execute only ONCE per page
    static $abCalled;
    if( isset( $abCalled[$sRID] ) ) return null;
    else $abCalled[$sRID] = true;

    // Output
    $sOutput = null;
    $sOutput .= '</FORM>'."\r\n";
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FORM: PHP_APE_HTML_Data_Filter::htmlForm(Suffix) - END ('.$sRID.') -->\r\n";
    return $sOutput;
  }

  /** Returns the HTML filter <SAMP>FORM</SAMP> (prefix+suffix)
   *
   * @param mixed $mID HTML element identifier (ID)
   * @param string $sGlobalCriteria Global (search) criteria
   * @return string
   */
  public static function htmlForm( $mID, $sGlobalCriteria = null )
  {
    return self::htmlFormPrefix( $mID, $sGlobalCriteria ).self::htmlFormSuffix( $mID );
  }

  /** Returns the HTML filter controls
   *
   * @param mixed $mID HTML element identifier (ID)
   * @param boolean $bUseGlobalSearch Use global search
   * @param string $sGlobalCriteria Global (search) criteria
   * @param boolean $bUseAdvancedFilter Use advanced filter
   * @param boolean $bUseSelfLink Use self-pointing hyperlink
   * @return string
   * @todo Add help button
   */
  public static function htmlControls( $mID, $bUseGlobalSearch = false, $sGlobalCriteria = null, $bUseAdvancedFilter = false, $bUseSelfLink = false )
  {
    // Retrieve resource identifier (ID)
    $sRID = PHP_APE_HTML_Data_Any::makeRID( $mID );

    // Sanitize input
    $bUseGlobalSearch = (boolean)$bUseGlobalSearch;
    $sGlobalCriteria = (string)$sGlobalCriteria;
    $bUseAdvancedFilter = (boolean)$bUseAdvancedFilter;

    // Environment
    $roEnvironment =& PHP_APE_HTML_WorkSpace::useEnvironment();

    // HTML
    $asResources = $roEnvironment->loadProperties( 'PHP_APE_HTML_Data_Filter' );
    $oDataSpace = new PHP_APE_DataSpace_HTML();

    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_Filter::htmlControls - BEGIN ('.$sRID.') -->\r\n";
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen();

    // ... label
    $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.filter'].':', null, null, $asResources['tooltip.filter'], null, true, false );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:5px !important;', false );

    // ... global criteria
    if( $bUseGlobalSearch )
    {
      $sOutput .= '<INPUT CLASS="standard" TYPE="text" VALUE="'.$oDataSpace->encodeData( $sGlobalCriteria ).'" ONKEYPRESS="javascript:PHP_APE_DF_global(\''.$sRID.'\',this.value);PHP_APE_DF_onKeyPress(\''.$sRID.'\',event);">';
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
    }

    // ... buttons
    if( $bUseGlobalSearch or $bUseAdvancedFilter )
    {
      // ... apply
      $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.apply'], 'S-commit', 'javascript:PHP_APE_DF_apply(\''.$sRID.'\')', $asResources['tooltip.apply'], null, false, false );

      // ... clear
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
      $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.clear'], 'S-clear', 'javascript:PHP_APE_DF_clear(\''.$sRID.'\')', $asResources['tooltip.clear'], null, false, false );
      // ... help
      //      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
      //      $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.help'], 'S-help', 'javascript:PHP_APE_DF_help()', $asResources['tooltip.help'], null, false, false );
    }

    // ... avanced/filter toggle
    if( $bUseAdvancedFilter )
    {
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
      if( $roEnvironment->getUserParameter( 'php_ape.data.filter.advanced' ) ) $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.simple'], 'S-less', 'javascript:document.location.replace(\''.$roEnvironment->makePreferencesURL( array( 'php_ape.data.filter.advanced' => 0 ), $_SERVER['REQUEST_URI'] ).'\')', $asResources['tooltip.simple'], null, false, false );
      else $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.advanced'], 'S-more', 'javascript:document.location.replace(\''.$roEnvironment->makePreferencesURL( array( 'php_ape.data.filter.advanced' => 1 ), $_SERVER['REQUEST_URI'] ).'\')', $asResources['tooltip.advanced'], null, false, false );
    }

    // ... self-link
    if( $bUseSelfLink )
    {
      $oFilter = PHP_APE_HTML_Data_Filter::parseRequest( $mID );
      if( !is_null( $oFilter ) and strlen( $oFilter->toString() ) > 0 )
      {
        $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
        $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.selflink'], 'S-hyperlink', PHP_APE_HTML_Data_Filter::makeURL( $mID, $_SERVER['PHP_SELF'], $oFilter ), $asResources['tooltip.selflink'], null, false, false );
      }
    }

    // ... end
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();
    $sOutput .= "\r\n";
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_Filter::htmlControls - BEGIN ('.$sRID.') -->\r\n";
    return $sOutput;
  }

}
