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

/** Common HTML tags
 *
 * @package PHP_APE_HTML
 * @subpackage Components
 */
class PHP_APE_HTML_Tags
{

  /*
   * METHODS: static
   ********************************************************************************/

  /** Opens the <SAMP><HTML></SAMP> tag
   *
   * @return string
   */
  public static function htmlDocumentOpen()
  {
    $sOutput = null;
    $sOutput .= '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">'."\r\n";
    $sOutput .= '<!-- This page has been generated using PHP Application Programing Environment (PHP-APE) -->'."\r\n";
    $sOutput .= '<!-- by Cedric Dufour - http://cedric.dufour.name/software/php-ape -->'."\r\n";
    $sOutput .= ( PHP_APE_DEBUG ? "\r\n<!-- HTML:begin -->\r\n" : null ).'<HTML>'."\r\n";
    return $sOutput;
  }

  /** Closes the <SAMP></HTML></SAMP> tag
   *
   * @return string
   */
  public static function htmlDocumentClose()
  {
    return ( PHP_APE_DEBUG ? "\r\n<!-- HTML:end -->\r\n" : null ).'</HTML>'."\r\n";
  }

  /** Opens the <SAMP><HEAD></SAMP> tag
   *
   * @return string
   */
  public static function htmlHeadOpen()
  {
    return ( PHP_APE_DEBUG ? "\r\n<!-- HEAD:begin -->\r\n" : null ).'<HEAD>'."\r\n";
  }

  /** Returns the character set (encoding) directive
   *
   * @param string $sCharSet Character set (encoding) [default: <SAMP>php_ape.data.charset</SAMP> environment setting]
   * @return string
   */
  public static function htmlHeadCharSet( $sCharSet = null )
  {
    if( empty( $sCharSet ) )
    {
      $roEnvironment =& PHP_APE_HTML_WorkSpace::useEnvironment();
      $sCharSet = $roEnvironment->getVolatileParameter( 'php_ape.data.charset' );
    }
    $oDataSpace = new PHP_APE_DataSpace_HTML();
    return ( PHP_APE_DEBUG ? "\r\n<!-- HEAD:charset -->\r\n" : null ).'<META HTTP-EQUIV="content-type" CONTENT="text/html; charset='.$oDataSpace->encodeData( $sCharSet ).'" />'."\r\n";
  }

  /** Returns the window title directive
   *
   * @param string $sTitle Window title [default: <SAMP>php_ape.application.name</SAMP> environment setting]
   * @return string
   */
  public static function htmlHeadTitle( $sTitle = null )
  {
    if( empty( $sTitle ) )
    {
      $roEnvironment =& PHP_APE_HTML_WorkSpace::useEnvironment();
      $sTitle = $roEnvironment->getVolatileParameter( 'php_ape.application.name' );
    }
    $oDataSpace = new PHP_APE_DataSpace_HTML();
    return ( PHP_APE_DEBUG ? "\r\n<!-- HEAD:title -->\r\n" : null ).'<TITLE>'.$oDataSpace->encodeData( $sTitle ).'</TITLE>'."\r\n";
  }

  /** Closes the <SAMP></HEAD></SAMP> tag
   *
   * @return string
   */
  public static function htmlHeadClose()
  {
    return ( PHP_APE_DEBUG ? "\r\n<!-- HEAD:end -->\r\n" : null ).'</HEAD>'."\r\n";
  }

  /** Opens the <SAMP><BODY></SAMP> tag
   *
   * <P><B>USAGE:</B></P>
   * <P>The following JavaScript functions are provisioned:</P>
   * <UL>
   * <LI><SAMP>self._onLoad()</SAMP>: executed once the document has been fully loaded</LI>
   * <LI><SAMP>self._onUnload()</SAMP>: executed before the document is unloaded (before loading another document)</LI>
   * </UL>
   *
   * @param string $sClass Associated CSS class (<SAMP>CLASS="..."</SAMP>)
   * @param string $sStyle Associated CSS style directives (<SAMP>STYLE="..."</SAMP>)
   * @return string
   */
  public static function htmlBodyOpen( $sClass = null, $sStyle = null )
  {
    // Sanitize input
    $sClass = (string)$sClass;
    $sStyle = (string)$sStyle;

    // Output
    $sOutput = null;
    $sOutput .= ( PHP_APE_DEBUG ? "\r\n<!-- BODY:begin -->\r\n" : null ).'<BODY';
    if( $sClass ) $sOutput .= ' CLASS="'.$sClass.'"';
    $sOutput .= ' ONLOAD="javascript:if(self._onLoad)self._onLoad();"';
    if( strstr( $_SERVER['HTTP_USER_AGENT'] , 'MSIE' ) ) $sOutput .= ' ONBEFOREUNLOAD="javascript:if(self._onUnload)self._onUnload();"';
    else $sOutput .= ' ONUNLOAD="javascript:if(self._onUnload)self._onUnload();"';
    if( $sStyle ) $sOutput .= ' STYLE="'.$sStyle.'"';
    $sOutput .= ">\r\n";
    return $sOutput;
  }

  /** Closes the <SAMP></BODY></SAMP> tag
   *
   * @return string
   */
  public static function htmlBodyClose()
  {
    return ( PHP_APE_DEBUG ? "\r\n<!-- BODY:end -->\r\n" : null ).'</BODY>'."\r\n";
  }

  /** Returns a JavaScript popup-opening directive (<SAMP><SCRIPT...>window.alert(...)</SCRIPT></SAMP>)
   *
   * @param string $sMessage Popup message
   * @return string
   */
  public static function htmlPopup( $sMessage )
  {
    // Sanitize input
    $sMessage = (string)$sMessage;

    // Output
    if( $sMessage )
    {
      $oDataSpace = new PHP_APE_DataSpace_JavaScript();
      return ( PHP_APE_DEBUG ? "\r\n<!-- POPUP -->\r\n" : null ).'<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">window.alert(\''.$oDataSpace->encodeData($sMessage).'\');</SCRIPT>'."\r\n";
    }
  }

  /** Returns an anchor (<SAMP><A HREF="...">...</A></SAMP>) with the given parameters
   *
   * <P><B>NOTE:</B> <I>JavaScript:</I> anchor is converted to <SAMP>ONCLICK</SAMP> anchor, for optimal browser behavior.</P>
   *
   * @param string $sURL Anchor URL (<SAMP>HREF="..."</SAMP>)
   * @param string $sName Anchor name
   * @param string $sTooltip Anchor tooltip (<SAMP>TITLE="..."</SAMP>)
   * @param string $sTarget Anchor target (<SAMP>TARGET="..."</SAMP>)
   * @param boolean $bPassthru No HTML encoding/formatting
   */
  public static function htmlAnchor( $sURL, $sName = null, $sTooltip = null, $sTarget = null, $bPassthru = false )
  {
    // Sanitize input
    $sURL = (string)$sURL;
    if( !is_null( $sName ) ) $sName = (string)$sName;
    else $sName = $sURL;
    if( !is_null( $sTooltip ) ) $sTooltip = (string)$sTooltip;
    if( !is_null( $sTarget ) ) $sTarget = (string)$sTarget;
    $bPassthru = (boolean)$bPassthru;

    // Output
    $sOutput = null;
    $oDataSpace = new PHP_APE_DataSpace_HTML();
    if( preg_match('/^javascript:/i',$sURL) )
      $sHREF = 'HREF="javascript:;" STYLE="CURSOR:pointer;" ONCLICK="'.$sURL.';return false;"';
    else
      $sHREF = 'HREF="'.$sURL.'"'.( $sTarget ? ' TARGET="'.$sTarget.'"' : null );
    $sOutput .= '<A '.$sHREF;
    if( $sTooltip ) $sOutput .= ' TITLE="'.$oDataSpace->encodeData( $sTooltip ).'"';
    $sOutput .= '>';
    $sOutput .= ( $bPassthru ? $sName : $oDataSpace->encodeData( $sName ) );
    $sOutput .= '</A>';
    return $sOutput;
  }

  /** Returns the JavaScript resources loading directive
   *
   * <P><B>USAGE:</B></P>
   * <P>The following packages are provisioned:</P>
   * <UL>
   * <LI><SAMP>PHP-APE</SAMP>: PHP-APE JavaScript resources</LI>
   * <LI><SAMP>ToolMan</SAMP>: Tim Taylor's <I>Direct Manipulation API</I> (providing <B>drag'n sort</B> support)</LI>
   * <LI><SAMP>overLIB</SAMP>: The <I>overLIB</I> API (providing enhanced <B>popups and tooltips</B> support)</LI>
   * </UL>
   *
   * @param string $sPackage JavaScript package
   * @param string $sJavaScriptURL Root JavaScript URL [default: <SAMP>php_ape.html.javascript.url</SAMP> environment setting]
   * @return string
   */
  public static function htmlJavaScript( $sPackage, $sJavaScriptURL = null )
  {
    // Sanitize input
    $sPackage = strtolower( $sPackage );
    $sJavaScriptURL = (string)$sJavaScriptURL;

    // Let's execute only ONCE per page
    static $abCalled;
    if( isset( $abCalled[$sPackage] ) ) return null;
    else $abCalled[$sPackage] = true;

    // Environment
    $sPath = $sJavaScriptURL;
    if( empty( $sPath ) ) 
    {
      $roEnvironment =& PHP_APE_HTML_WorkSpace::useEnvironment();
      $sPath = $roEnvironment->getVolatileParameter( 'php_ape.html.javascript.url' );
    }

    // Output
    $sOutput = null;
    switch( $sPackage )
    {

    case 'php-ape':
      if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- SCRIPT:APE -->\r\n";
      $sOutput .= '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="'.$sPath.'/browser.js"></SCRIPT>'."\r\n";
      $sOutput .= '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="'.$sPath.'/url.js"></SCRIPT>'."\r\n";
      $sOutput .= '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="'.$sPath.'/element.js"></SCRIPT>'."\r\n";
      $sOutput .= '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="'.$sPath.'/input.js"></SCRIPT>'."\r\n";
      break;

    case 'toolman':
      $sPath .= '/toolman';
      if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- SCRIPT:ToolMan -->\r\n";
      $sOutput .= '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="'.$sPath.'/core.js"></SCRIPT>'."\r\n";
      $sOutput .= '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="'.$sPath.'/events.js"></SCRIPT>'."\r\n";
      $sOutput .= '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="'.$sPath.'/css.js"></SCRIPT>'."\r\n";
      $sOutput .= '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="'.$sPath.'/coordinates.js"></SCRIPT>'."\r\n";
      $sOutput .= '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="'.$sPath.'/drag.js"></SCRIPT>'."\r\n";
      $sOutput .= '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="'.$sPath.'/dragsort.js"></SCRIPT>'."\r\n";
      $sOutput .= '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="'.$sPath.'/cookies.js"></SCRIPT>'."\r\n";
      $sOutput .= '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript"><!--'."\r\n";
      $sOutput .= 'var dragsort = ToolMan.dragsort();'."\r\n";
      $sOutput .= '--></SCRIPT>'."\r\n";
      break;

    case 'overlib':
      $sPath .= '/overlib';
      if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- SCRIPT:overLIB -->\r\n";
      $sOutput .= '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript" SRC="'.$sPath.'/overlib.js"></SCRIPT>'."\r\n";
      break;

    }
  
    return $sOutput;
  }

}
