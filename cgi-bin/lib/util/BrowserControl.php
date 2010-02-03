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

/** (Client) browser control utilities
 *
 *
 * @package PHP_APE_Util
 * @subpackage Miscellaneous
 */
class PHP_APE_Util_BrowserControl
extends PHP_APE_Util_Any
{

  /*
   * METHODS: static
   ********************************************************************************/

  /** Send HTTP headers to prevent page caching
   *
   * <P><B>USAGE:</B> If the supplied time is <SAMP>zero</SAMP> (<SAMP>0</SAMP>), then no caching
   * at all should be perfomed by the browser; otherwise, caching should be done according to the
   * <I>modification</I> time supplied. If <SAMP>null</SAMP>, the <I>modification</I> time defaults
   * to the current time.</P>
   * <P><B>NOTE:</B> This method will have NO effect if HTTP headers have already been sent.</P>
   *
   * @param integer $tsTime Time (as UNIX timestamp)
   */
  public static function noCache( $tsTime = 0 )
  {
    if( headers_sent() ) return;
    if( is_null( $tsTime ) ) $tsTime = time();
    else $tsTime = (integer)$tsTime;
    if( $tsTime <= 0 )
    {
      header( 'Expires: Thu, 1 Jan 1970 00:00:00 GMT' ); // Date in the past
      header( 'Cache-Control: no-store, no-cache, must-revalidate' ); // HTTP/1.1
      header( 'Pragma: no-cache' ); // HTTP/1.0
    }
    else
    {
      header( 'Last-Modified: '.gmdate( 'D, d M Y H:i:s', $tsTime ).' GMT' );
    }
  }

  /** Send HTTP header or JavaScript command to refresh the browser's content
   *
   * @param string $sTarget Redirection target (JavaScript namespace)
   * @param integer $iDelay Wait given seconds before redirecting/reloading
   * @param boolean $bReload Force browser to reload (refresh) document content
   */
  public static function refresh( $sTarget = null, $iDelay = 0, $bReload = false  )
  {
    // Sanitize input
    $sTarget = (string)$sTarget;
    if( !$sTarget ) $sTarget = 'self';
    $iDelay = (integer)$iDelay;
    if( $iDelay < 0 ) $iDelay = 0;
    $bReload = (boolean)$bReload;

    // Command
    // NOTE: Let's NOT use the 'Refresh' HTTP header, since we don't know exactly what the effect is on caching
    // header( 'Refresh: '.$iDelay );
    echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">'."\r\n";
    if( $bReload )
      $sCommand = $sTarget.'.document.reload(true);'."\r\n";
    else
      $sCommand = $sTarget.'.document.location.replace('.$sTarget.'.document.location);'."\r\n";
    if( $iDelay > 0 )
      echo "window.setTimeout('".addCSlashes( str_replace( '"', "'", $sCommand ), "\x0..\x1F\\'" )."',".(1000*$iDelay).");\r\n";
    else
      echo $sCommand."\r\n";
    echo '</SCRIPT>'."\r\n";
  }

  /** Send HTTP header or JavaScript command to send the browser to the given URL
   *
   * @param string $sURL Redirection URL
   * @param string $sTarget Redirection target (JavaScript namespace)
   * @param boolean $bReplace Replace document location (skip history)
   * @param string $sCloseWindow Close window (JavaScript namespace)
   * @param integer $iDelay Wait given seconds before redirecting/reloading
   */
  public static function goto( $sURL, $sTarget = null, $bReplace = false, $sCloseWindow = null, $iDelay = 0 )
  {
    // Sanitize input
    $sURL = (string)$sURL;
    $sTarget = (string)$sTarget;
    if( !$sTarget ) $sTarget = 'self';
    $bReplace = (boolean)$bReplace;
    $sCloseWindow = (string)$sCloseWindow;
    $iDelay = (integer)$iDelay;
    if( $iDelay < 0 ) $iDelay = 0;

    // Command
    // NOTE: Let's NOT use the 'Refresh' HTTP header, since we don't know exactly what the effect is on caching
    // header( 'Refresh: '.$iDelay.'; URL='.$sURL );
    echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">'."\r\n";
    if( $bReplace ) $sCommand = $sTarget.'.document.location.replace(\''.addCSlashes( str_replace( '"', "'", $sURL ), "\x0..\x1F\\'" ).'\');';
    else $sCommand = $sTarget.'.document.location.href=\''.addCSlashes( str_replace( '"', "'", $sURL ), "\x0..\x1F\\'" ).'\';';
    if( $sCloseWindow ) $sCommand .= $sCloseWindow.'.close();';
    if( $iDelay > 0 )
      echo "window.setTimeout('".addCSlashes( str_replace( '"', "'", $sCommand ), "\x0..\x1F\\'" )."',".(1000*$iDelay).");\r\n";
    else
      echo $sCommand."\r\n";
    echo '</SCRIPT>'."\r\n";
  }

  /** Send JavaScript command to send the browser's window back one step through its history
   *
   * @param string $sTarget Redirection target (JavaScript namespace)
   * @param integer $iDelay Wait given seconds before redirecting/reloading
   */
  public static function back( $sTarget = null, $iDelay = 0 )
  {
    // Sanitize input
    $sTarget = (string)$sTarget;
    if( !$sTarget ) $sTarget = 'self';
    $iDelay = (integer)$iDelay;
    if( $iDelay < 0 ) $iDelay = 0;

    // Command
    echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">'."\r\n";
    $sCommand = $sTarget.'.window.history.back();';
    if( $iDelay > 0 )
      echo "window.setTimeout('".addCSlashes( str_replace( '"', "'", $sCommand ), "\x0..\x1F\\'" )."',".(1000*$iDelay).");\r\n";
    else
      echo $sCommand."\r\n";
    echo '</SCRIPT>'."\r\n";
  }

  /** Send JavaScript command to close the browser's window
   *
   * @param string $sTarget Redirection target (JavaScript namespace)
   * @param integer $iDelay Wait given seconds before redirecting/reloading
   */
  public static function close( $sTarget = null, $iDelay = 0 )
  {
    // Sanitize input
    $sTarget = (string)$sTarget;
    if( !$sTarget ) $sTarget = 'self';
    $iDelay = (integer)$iDelay;
    if( $iDelay < 0 ) $iDelay = 0;

    // Command
    echo '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">'."\r\n";
    $sCommand = $sTarget.'.window.close();';
    if( $iDelay > 0 )
      echo "window.setTimeout('".addCSlashes( str_replace( '"', "'", $sCommand ), "\x0..\x1F\\'" )."',".(1000*$iDelay).");\r\n";
    else
      echo $sCommand."\r\n";
    echo '</SCRIPT>'."\r\n";
  }

  /** Send HTTP headers to initiate file download
   *
   * <P><B>NOTE:</B> This method will have NO effect if HTTP headers have already been sent.</P>
   *
   * @param string $sFilePath File (server) path
   * @param string $sFileName File name
   * @param boolean $bAsAttachment Download file as attachment (prevent <I>inline</I> rendering of content by browser)
   */
  public static function download( $sFilePath, $sFileName = null, $bAsAttachment = false )
  {
    if( headers_sent() ) return;
    header( 'Content-Type: '.mime_content_type( $sFilePath ) );
    header( 'Content-Length: '.filesize( $sFilePath ) );
    if( strlen( $sFileName ) >= 0 )
      header( 'Content-Disposition: '.( $bAsAttachment ? 'attachment' : 'inline' ).'; filename="'.$sFileName.'"' );
    else
      header( 'Content-Disposition: inline' );
    readfile( $sFilePath );
  }

  /** Send HTTP headers to initiate data export (and 'save as' prompt)
   *
   * <P><B>NOTE:</B> This method will have NO effect if HTTP headers have already been sent.</P>
   *
   * @param string $sMIMEType Data 
   * @param string $sFileName 'Save as' File name
   * @param boolean $bAsAttachment Download file as attachment (prevent <I>inline</I> rendering of content by browser)
   */
  public static function export( $sMIMEType, $sFileName = null, $bAsAttachment = false )
  {
    if( headers_sent() ) return;
    header( 'Content-Type: '.$sMIMEType );
    if( strlen( $sFileName ) >= 0 )
      header( 'Content-Disposition: '.( $bAsAttachment ? 'attachment' : 'inline' ).'; filename="'.$sFileName.'"' );
    else
      header( 'Content-Disposition: inline' );
  }

}
