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

/** HTML data scroller utilities
 *
 * @package PHP_APE_HTML
 * @subpackage Control
 */
class PHP_APE_HTML_Data_Scroller
extends PHP_APE_HTML_Data_Any
{

  /*
   * METHODS: static
   ********************************************************************************/

  /** Returns the HTML page URL implementing the given data scroller
   *
   * @param mixed $mID HTML element identifier (ID)
   * @param string $sURL Target URL
   * @param PHP_APE_Data_Scroller $oScroller Data scroller object
   * @return string
   */
  public static function makeURL( $mID, $sURL, PHP_APE_Data_Scroller $oScroller )
  {
    // Retrieve resource identifier (ID)
    $sRID = PHP_APE_HTML_Data_Any::makeRID( $mID );

    // Return URL
    $sURL = preg_replace( '/&?PHP_APE_DS_'.$sRID.'=[^&]*/is', null, $sURL );
    $sURL = ltrim( $sURL, '&' );
    return PHP_APE_Util_URL::addVariable( $sURL, array( 'PHP_APE_DS_'.$sRID => $oScroller->toProperties() ) );
  }

  /** Parses the HTML request parameters and returns the corresponding data scroller
   *
   * <P><B>NOTE:</B> If no corresponding request parameters are found, <SAMP>null</SAMP> is returned.</P>
   *
   * @param mixed $mID HTML element identifier (ID)
   * @return PHP_APE_Data_Scroller
   */
  public static function parseRequest( $mID )
  {
    // Retrieve resource identifier (ID)
    $sRID = PHP_APE_HTML_Data_Any::makeRID( $mID );

    // Parse request
    $oScroller = null;
    if( isset( $_POST['PHP_APE_DS_'.$sRID] ) )
    {
      $oScroller = new PHP_APE_Data_Scroller();
      $oScroller->fromProperties( $_POST['PHP_APE_DS_'.$sRID] );
    }
    elseif( isset( $_GET['PHP_APE_DS_'.$sRID] ) )
    {
      $oScroller = new PHP_APE_Data_Scroller();
      $oScroller->fromProperties( $_GET['PHP_APE_DS_'.$sRID] );
    }

    // Return scroller object
    return $oScroller;
  }

  /** Returns the HTML scroller <SAMP>FORM</SAMP>
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
    $sQuery = preg_replace( '/&?PHP_APE_DS_'.$sRID.'=[^&]*/is', null, $_SERVER['QUERY_STRING'] );
    $sQuery = ltrim( $sQuery, '&' );

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    if( !isset( $abCalled['__JAVASCRIPT__'] ) )
    {
      $abCalled['__JAVASCRIPT__'] = true;
      if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FORM: PHP_APE_HTML_Data_Scroller::htmlForm - JAVASCRIPT -->\r\n";
      $sOutput .= '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript"><!--'."\r\n";
      $sOutput .= "function PHP_APE_DS_go(rid,offset,limit)\r\n";
      $sOutput .= "{\r\n";
      $sOutput .= " form = document.forms['PHP_APE_DS_'+rid];\r\n";
      $sOutput .= " fvar = form.elements['PHP_APE_DS_'+rid];\r\n";
      $sOutput .= " if( fvar.disabled )\r\n";
      $sOutput .= " {\r\n";
      $sOutput .= "  form.offset.value = offset;\r\n";
      $sOutput .= "  form.offset.disabled = false;\r\n";
      $sOutput .= "  form.limit.value = limit;\r\n";
      $sOutput .= "  form.limit.disabled = false;\r\n";
      $sOutput .= "  PHP_APE_IN_Form_get( form, fvar );\r\n";
      $sOutput .= " }\r\n";
      $sOutput .= "}\r\n";
      $sOutput .= "--></SCRIPT>\r\n";
    }
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FORM: PHP_APE_HTML_Data_Scroller::htmlForm - BEGIN ('.$sRID.') -->\r\n";
    $sOutput .= '<FORM NAME="PHP_APE_DS_'.$sRID.'" ACTION="'.($sQuery?'?'.$sQuery:null).'" METHOD="get">'."\r\n";
    $sOutput .= '<INPUT TYPE="hidden" NAME="PHP_APE_DS_'.$sRID.'" DISABLED>'."\r\n";
    $sOutput .= '<INPUT TYPE="hidden" NAME="offset" DISABLED>'."\r\n";
    $sOutput .= '<INPUT TYPE="hidden" NAME="limit" DISABLED>'."\r\n";
    $sOutput .= '</FORM>'."\r\n";
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FORM: PHP_APE_HTML_Data_Scroller::htmlForm - END ('.$sRID.') -->\r\n";
    return $sOutput;
  }

  /** Returns the HTML scroller controls
   *
   * @param mixed $mID HTML element identifier (ID)
   * @param integer $iLimit (Current) data limit
   * @param integer $iOffset (Current) data offset
   * @param integer $iQuantity (Available) data quantity
   * @return string
   */
  public static function htmlControls( $mID, $iLimit, $iOffset, $iQuantity )
  {
    // Retrieve resource identifier (ID)
    $sRID = PHP_APE_HTML_Data_Any::makeRID( $mID );

    // Sanitize input
    $iLimit = (integer)$iLimit;
    $iOffset = (integer)$iOffset;
    $iQuantity = (integer)$iQuantity;

    // Environment
    $roEnvironment =& PHP_APE_HTML_WorkSpace::useEnvironment();
    $bIconDisplay = $roEnvironment->getUserParameter( 'php_ape.html.display.icon' ) > PHP_APE_HTML_SmartTags::Icon_Hide;
    $iQueryMinSize = $roEnvironment->getVolatileParameter( 'php_ape.data.query.minsize' );
    $iQueryMaxSize = $roEnvironment->getVolatileParameter( 'php_ape.data.query.maxsize' );
    $iQueryPageSpan = $roEnvironment->getUserParameter( 'php_ape.data.query.page' );

    // Check scroller
    if( $iOffset < 0 ) $iOffset = 0;
    if( $iLimit < $iQueryMinSize ) $iLimit = $iQueryMinSize;
    if( $iLimit > $iQueryMaxSize ) $iLimit = $iQueryMaxSize;
    if( $iQuantity < 0 ) $iQuantity = 0;

    // Page variables
    $iPageCurrent = floor( $iOffset / $iLimit);
    $iPageMin = max( $iPageCurrent - $iQueryPageSpan, 0 );
    $iPageMax = min( $iPageCurrent + $iQueryPageSpan, floor( ( $iQuantity - 1 ) / $iLimit ) );

    // HTML
    $asResources = $roEnvironment->loadProperties( 'PHP_APE_HTML_Data_Scroller' );
    $oDataSpace = new PHP_APE_DataSpace_HTML();

    // Output
    $sOutput = null;

    // ... begin
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_Scroller::htmlControls - BEGIN ('.$sRID.') -->\r\n";
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen();

    // ... entries
    $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.entries'].':', null, null, $asResources['tooltip.entries'], null, true, false );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
    $sOutput .= '<P CLASS="value">'.$oDataSpace->encodeData($iQuantity).'</P>';

    // ... separator
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();

    // ... page
    $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.page'].':', null, null, $asResources['tooltip.page'], null, false, false );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );

    // ... first/previous
    if( $iPageCurrent > 0 )
    {
      // ... first
      $sOutput .= $bIconDisplay ? PHP_APE_HTML_SmartTags::htmlIcon( 'S-first', "javascript:PHP_APE_DS_go('".$sRID."',0,".$iLimit.')', $asResources['tooltip.first'], null, true ) : PHP_APE_HTML_SmartTags::htmlLabel( '&lt;&lt;', null, "javascript:PHP_APE_DS_go('".$sRID."',0,".$iLimit.')', $asResources['tooltip.first'], null, true, false, 'P', true );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
      // ... previous
      $sOutput .= $bIconDisplay ? PHP_APE_HTML_SmartTags::htmlIcon( 'S-previous', "javascript:PHP_APE_DS_go('".$sRID."',".(($iPageCurrent-1)*$iLimit).','.$iLimit.')', $asResources['tooltip.previous'], null, true ) : PHP_APE_HTML_SmartTags::htmlLabel( '&lt;', null, "javascript:PHP_APE_DS_go('".$sRID."',".(($iPageCurrent-1)*$iLimit).','.$iLimit.')', $asResources['tooltip.previous'], null, true, false, 'P', true );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
    }

    // ... select
    $sOutput .= '<SELECT ONCHANGE="javascript:PHP_APE_DS_go(\''.$sRID.'\',this.value,'.$iLimit.');">';
    $bSelected=false;
    for( $iPage = $iPageMin; $iPage <= $iPageMax; $iPage++ )
    {
      if( $iPage == $iPageCurrent )
      {
        $bSelected=true;
        $sOutput .= '<OPTION VALUE="'.($iPage*$iLimit).' " SELECTED>'.($iPage+1).'</OPTION>';
      }
      else $sOutput .= '<OPTION VALUE="'.($iPage*$iLimit).'">'.($iPage+1).'</OPTION>';
    }
    if( !$bSelected ) $sOutput .= '<OPTION VALUE="'.($iPageCurrent*$iLimit).'" SELECTED>'.($iPageCurrent+1).'</OPTION>';
    $sOutput .= '</SELECT>';

    // ... next/last
    if( $iPageCurrent < $iPageMax )
    {
      // ... next
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
      $sOutput .= $bIconDisplay ? PHP_APE_HTML_SmartTags::htmlIcon( 'S-next', "javascript:PHP_APE_DS_go('".$sRID."',".(($iPageCurrent+1)*$iLimit).','.$iLimit.')', $asResources['tooltip.next'], null, true ) : PHP_APE_HTML_SmartTags::htmlLabel( '&gt;', null, "javascript:PHP_APE_DS_go('".$sRID."',".(($iPageCurrent+1)*$iLimit).','.$iLimit.')', $asResources['tooltip.next'], null, true, false, 'P', true );
      // ... last
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
      $sOutput .= $bIconDisplay ? PHP_APE_HTML_SmartTags::htmlIcon( 'S-last', "javascript:PHP_APE_DS_go('".$sRID."',".($iPageMax*$iLimit).','.$iLimit.')', $asResources['tooltip.last'], null, true ) : PHP_APE_HTML_SmartTags::htmlLabel( '&gt;&gt;', null, "javascript:PHP_APE_DS_go('".$sRID."',".($iPageMax*$iLimit).','.$iLimit.')', $asResources['tooltip.last'], null, true, false, 'P', true );
    }

    // ... separator
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();

    // ... group by
    $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( $asResources['label.group'].':', null, null, $asResources['tooltip.group'], null, false, false );
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );

    // ... less
    if( ( $iLimit - 10 ) >= $iQueryMinSize )
    {
      $sOutput .= $bIconDisplay ? PHP_APE_HTML_SmartTags::htmlIcon( 'S-less', "javascript:PHP_APE_DS_go('".$sRID."',0,".($iLimit-10).')', $asResources['tooltip.less'], null, true ) : PHP_APE_HTML_SmartTags::htmlLabel( '&minus;', null, "javascript:PHP_APE_DS_go('".$sRID."',0,".($iLimit-10).')', $asResources['tooltip.less'], null, true, false, 'P', true );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
    }
    // ... input
    $sOutput .= '<INPUT CLASS="tiny" TYPE="text" VALUE="'.$iLimit.'" ONCHANGE="javascript:PHP_APE_DS_go(\''.$sRID.'\',0,this.value);" />';
    // ... more
    if( ( $iLimit + 10 ) <= $iQueryMaxSize )
    {
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
      $sOutput .= $bIconDisplay ? PHP_APE_HTML_SmartTags::htmlIcon( 'S-more', "javascript:PHP_APE_DS_go('".$sRID."',0,".($iLimit+10).')', $asResources['tooltip.more'], null, true ) : PHP_APE_HTML_SmartTags::htmlLabel( '+', null, "javascript:PHP_APE_DS_go('".$sRID."',0,".($iLimit+10).')', $asResources['tooltip.more'], null, true, false, 'P', true );
    }

    // ... end
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();
    $sOutput .= "\r\n";
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_Scroller::htmlControls - END ('.$sRID.') -->\r\n";
    return $sOutput;
  }

}
