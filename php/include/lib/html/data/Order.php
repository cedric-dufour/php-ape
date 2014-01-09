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

/** HTML data order utilities
 *
 * @package PHP_APE_HTML
 * @subpackage Control
 */
class PHP_APE_HTML_Data_Order
extends PHP_APE_HTML_Data_Any
{

  /*
   * METHODS: static
   ********************************************************************************/

  /** Returns the HTML page URL implementing the given data order
   *
   * @param mixed $mID HTML element identifier (ID)
   * @param string $sURL Target URL
   * @param PHP_APE_Data_Order $oOrder Data order object
   * @return string
   */
  public static function makeURL( $mID, $sURL, PHP_APE_Data_Order $oOrder )
  {
    // Retrieve resource identifier (ID)
    $sRID = PHP_APE_HTML_Data_Any::makeRID( $mID );

    // Return URL
    $sURL = preg_replace( '/&?PHP_APE_DO_'.$sRID.'=[^&]*/is', null, $sURL );
    $sURL = ltrim( $sURL, '&' );
    return PHP_APE_Util_URL::addVariable( $sURL, array( 'PHP_APE_DO_'.$sRID => $oOrder->toProperties() ) );
  }

  /** Parses the HTML request parameters and returns the corresponding data order
   *
   * <P><B>NOTE:</B> If no corresponding request parameters are found, <SAMP>null</SAMP> is returned.</P>
   *
   * @param mixed $mID HTML element identifier (ID)
   * @return PHP_APE_Data_Order
   */
  public static function parseRequest( $mID )
  {
    // Retrieve resource identifier (ID)
    $sRID = PHP_APE_HTML_Data_Any::makeRID( $mID );

    // Parse request
    $oOrder = null;
    if( isset( $_POST['PHP_APE_DO_'.$sRID] ) )
    {
      $oOrder = new PHP_APE_Data_Order();
      $oOrder->fromProperties( $_POST['PHP_APE_DO_'.$sRID] );
    }
    elseif( isset( $_GET['PHP_APE_DO_'.$sRID] ) )
    {
      $oOrder = new PHP_APE_Data_Order();
      $oOrder->fromProperties( $_GET['PHP_APE_DO_'.$sRID] );
    }

    // Return order object
    return $oOrder;
  }

  /** Returns the HTML order <SAMP>FORM</SAMP>
   *
   * @param mixed $mID HTML element identifier (ID)
   * @return string
   */
  public static function htmlForm( $mID )
  {
    // Retrieve resource identifier (ID)
    $sRID = PHP_APE_HTML_Data_Any::makeRID( $mID );

    // Let's execute only ONCE per page
    static $abCalled;
    if( isset( $abCalled[$sRID] ) ) return null;
    else $abCalled[$sRID] = true;

    // Query
    $sQuery = preg_replace( '/&?PHP_APE_DO_'.$sRID.'=[^&]*/is', null, $_SERVER['QUERY_STRING'] );
    $sQuery = preg_replace( '/&?PHP_APE_DS_'.$sRID.'=[^&]*/is', null, $sQuery );
    $sQuery = ltrim( $sQuery, '&' );

    // Form
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    if( !isset( $abCalled['__JAVASCRIPT__'] ) )
    {
      $abCalled['__JAVASCRIPT__'] = true;
      if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FORM: PHP_APE_HTML_Data_Order::htmlForm - JAVASCRIPT -->\r\n";
      $sOutput .= '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript"><!--'."\r\n";
      $sOutput .= "function PHP_APE_DO_go(rid,field,direction)\r\n";
      $sOutput .= "{\r\n";
      $sOutput .= " form = document.forms['PHP_APE_DO_'+rid];\r\n";
      $sOutput .= " fvar = form.elements['PHP_APE_DO_'+rid];\r\n";
      $sOutput .= " if( fvar.disabled )\r\n";
      $sOutput .= " {\r\n";
      $sOutput .= "  fvar.value = '{'+field+'='+direction+'}';\r\n";
      $sOutput .= "  fvar.disabled = false;\r\n";
      $sOutput .= "  form.elements['PHP_APE_DS_'+rid].disabled = false;\r\n";
      $sOutput .= "  PHP_APE_IN_Form_get( form );\r\n";
      $sOutput .= " }\r\n";
      $sOutput .= "}\r\n";
      $sOutput .= "--></SCRIPT>\r\n";
    }
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FORM: PHP_APE_HTML_Data_Order::htmlForm - BEGIN ('.$sRID.') -->\r\n";
    $sOutput .= '<FORM NAME="PHP_APE_DO_'.$sRID.'" ACTION="'.$_SERVER['PHP_SELF'].( $sQuery ? '?'.$sQuery : null ).'" METHOD="get">'."\r\n";
    $sOutput .= '<INPUT TYPE="hidden" NAME="PHP_APE_DO_'.$sRID.'" DISABLED>'."\r\n";
    $sOutput .= '<INPUT TYPE="hidden" NAME="PHP_APE_DS_'.$sRID.'" DISABLED>'."\r\n";
    $sOutput .= '</FORM>'."\r\n";
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FORM: PHP_APE_HTML_Data_Order::htmlForm - END ('.$sRID.') -->\r\n";

    // JavaScript
    return $sOutput;
  }

}
