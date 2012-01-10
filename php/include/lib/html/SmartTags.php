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

/** Smart HTML tags
 *
 * <P><B>USAGE:</B></P>
 * <P><I>Smart</I> tags are an attempt to <B>rely entirely on Cascaded Style Sheets (CSS)</B> to control
 * the visual (graphical) elements of the Graphical User Interface (GUI), and provide enhanced visual elements
 * such as "nicely" framed boxes or buttons, as well as include useful features such as min-/maximizing display
 * elements, change user locale settings, etc.</P>
 *
 * <P><B>USAGE:</B> <SAMP>Frame</SAMP></P>
 * <P>Smart CSS frames are 3x3-cells tables that allow to output visually (graphically) enhanced framed content.
 * The proper CSS styles MUST be defined beforehand.</P>
 * <P>Please note that the <SAMP>!important</SAMP> CSS directive is necessary to guarantee optimal browser compatibility/portability.</P>
 *
 * <P><B>EXAMPLE:</B> myStyle.css</P>
 * <CODE>
 * TABLE.APE-frame { MARGIN:0px !important; BORDER:none !important; FONT-SIZE:1px !important; }
 * TABLE.APE-frame TD { MARGIN:0px !important; PADDING:0px !important; BORDER:none !important; }
 * TABLE.APE-frame TD.tl { WIDTH:10px !important; HEIGHT:10px !important; BACKGROUND:url("frame-tl.png") !important; }
 * TABLE.APE-frame TD.tc { HEIGHT:10px !important; BACKGROUND:url("frame-tc.png") !important; }
 * TABLE.APE-frame TD.tr { WIDTH:10px !important; HEIGHT:10px !important; BACKGROUND:url("frame-tr.png") !important; }
 * TABLE.APE-frame TD.ml { WIDTH:10px !important; BACKGROUND:url("frame-ml.png") !important; }
 * TABLE.APE-frame TD.mc { BACKGROUND:transparent !important; }
 * TABLE.APE-frame TD.mr { WIDTH:10px !important; BACKGROUND:url("frame-mr.png") !important; }
 * TABLE.APE-frame TD.bl { WIDTH:10px !important; HEIGHT:10px !important; BACKGROUND:url("frame-bl.png") !important; }
 * TABLE.APE-frame TD.bc { HEIGHT:10px !important; BACKGROUND:url("frame-bc.png") !important; }
 * TABLE.APE-frame TD.br { WIDTH:10px !important; HEIGHT:10px !important; BACKGROUND:url("frame-br.png") !important; }
 * </CODE>
 *
 * <P><B>EXAMPLE:</B> frame.php</P>
 * <CODE>
 * <?php
 * // PREREQUISITE: Load the appropriate CSS stylesheet beforehand
 * // Display box
 * PHP_APE_HTML_SmartTags::htmlBoxOpen();
 * echo 'Hello World !';
 * PHP_APE_HTML_SmartTags::htmlBoxClose();
 * ?>
 * </CODE>
 *
 * <P><B>USAGE:</B> <SAMP>Icon</SAMP></P>
 * <P>Smart CSS icons are background-enabled and size-constrained <SAMP>DIV</SAMP> that allow to output CSS-controled icons.
 * The proper CSS styles MUST be defined beforehand.</P>
 *
 * <P><B>EXAMPLE:</B> myStyle.css</P>
 * <CODE>
 * DIV.APE-icon { MARGIN:0px; PADDING:0px; }
 * DIV.APE-icon DIV.myIcon { WIDTH:16px; HEIGHT:16px; BACKGROUND:url("myIcon.png"); }
 * </CODE>
 *
 * <P><B>EXAMPLE:</B> icon.php</P>
 * <CODE>
 * <?php
 * // PREREQUISITE: Load the appropriate CSS stylesheet beforehand
 * // Display 'myIcon'
 * PHP_APE_HTML_SmartTags::htmlIcon( 'myIcon' );
 * ?>
 * </CODE>
 *
 * <P><B>USAGE:</B> <SAMP>Label</SAMP></P>
 * <P>Smart CSS labels text labels with optional CSS-controled icon. The proper CSS styles MUST be defined beforehand.</P>
 * <P>Please note that the <SAMP>!important</SAMP> CSS directive is necessary to guarantee optimal browser compatibility/portability.</P>
 *
 * <P><B>EXAMPLE:</B> myStyle.css</P>
 * <CODE>
 * TABLE.APE-label { MARGIN:0px !important; PADDING:0px !important; BORDER:none !important; FONT-SIZE:1px !important; }
 * TABLE.APE-label TD { MARGIN:0px !important; PADDING:0px !important; BORDER:none !important; }
 * TABLE.APE-label TD.i { WIDTH:16px !important; HEIGHT:16px !important; PADDING-RIGHT:2px !important; BACKGROUND:transparent !important; VERTICAL-ALIGN:middle !important; }
 * TABLE.APE-label TD.l { BACKGROUND:transparent !important; VERTICAL-ALIGN:middle !important; }
 * </CODE>
 *
 * <P><B>EXAMPLE:</B> label.php</P>
 * <CODE>
 * <?php
 * // PREREQUISITE: Load the appropriate CSS stylesheet beforehand
 * // Display 'myLabel'
 * PHP_APE_HTML_SmartTags::htmlLabel( 'myLabel', 'myIcon' );
 * ?>
 * </CODE>
 *
 * <P><B>USAGE:</B> <SAMP>Frame</SAMP></P>
 * <P>Smart CSS buttons are 1x3-cells tables that allow to output visually (graphically) enhanced buttons. The proper CSS styles MUST be defined beforehand.</P>
 * <P>Please note that the <SAMP>!important</SAMP> CSS directive is necessary to guarantee optimal browser compatibility/portability.</P>
 *
 * <P><B>EXAMPLE: myStyle.css</B></P>
 * <CODE>
 * TABLE.APE-button { MARGIN:0px !important; PADDING:0px !important; BORDER:none !important; FONT-SIZE:1px !important; }
 * TABLE.APE-button TD { MARGIN:0px !important; PADDING:0px !important; BORDER:none !important; }
 * TABLE.APE-button TD.l { WIDTH:5px !important; HEIGHT:20px !important; BACKGROUND:url("APE/button-l.png") !important; }
 * TABLE.APE-button TD.c { PADDING:0px 3px !important; BACKGROUND:url("APE/button-c.png") !important; VERTICAL-ALIGN:middle; }
 * TABLE.APE-button TD.r { WIDTH:5px !important; HEIGHT:20px !important; BACKGROUND:url("APE/button-r.png") !important; }
 * </CODE>
 *
 * <P><B>EXAMPLE: button.php</B></P>
 * <CODE>
 * <?php
 * // PREREQUISITE: Load the appropriate CSS stylesheet beforehand
 * // Display 'myButton'
 * PHP_APE_HTML_SmartTags::htmlButton( 'myButton', 'myIcon', "javascript:window.alert('Hello World !')" );
 * ?>
 * </CODE>
 *
 * @package PHP_APE_HTML
 * @subpackage Components
 */
class PHP_APE_HTML_SmartTags
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** Icons: icons are hidden
   * @var integer */
  const Icon_Hide = -1;

  /** Icons: icons are displayed with text labels
   * @var integer */
  const Icon_Show = 0;

  /** Icons: icons are displayed without text labels
   * @var integer */
  const Icon_NoLabel = 1;

  /** Display: no controls
   * @var integer */
  const Display_Control_None = 0;

  /** Display: close/open button
   * @var integer */
  const Display_Control_CloseOpen = 1;

  /** Display: minimize/maximize button
   * @var integer */
  const Display_Control_MinMax = 2;

  /** Display: all controls
   * @var integer */
  const Display_Control_All = 3;

  /** Display: closed
   * @var integer */
  const Display_Closed = -1;

  /** Display: minimized
   * @var integer */
  const Display_Minimized = 0;

  /** Display: maximized
   * @var integer */
  const Display_Maximized = 1;

  /*
   * METHODS: static
   ********************************************************************************/

  /** Returns the CSS-loading directive
   *
   * @param string $sStyle Style name [default: <SAMP>php_ape.html.css</SAMP> environment preference]
   * @param string $sStyleURL Root CSS URL [default: <SAMP>php_ape.html.css.url</SAMP> environment setting]
   * @return string
   */
  public static function htmlCSS( $sStyleName = null, $sStyleURL = null )
  {
    // Sanitize input
    $sStyleName = (string)$sStyleName;
    $sStyleURL = (string)$sStyleURL;
    
    // Environment
    $roEnvironment =& PHP_APE_HTML_Workspace::useEnvironment();
    if( empty( $sStyleName ) ) $sStyleName = $roEnvironment->getUserParameter( 'php_ape.html.css' );
    if( empty( $sStyleURL ) ) $sStyleURL = $roEnvironment->getVolatileParameter( 'php_ape.html.css.url' );

    // Ouptut
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- CSS -->\r\n";
    $sOutput .= '<LINK REL="stylesheet" TYPE="text/css" HREF="'.$sStyleURL.'/'.$sStyleName.'.css" />'."\r\n";
    return $sOutput;
  }

  /** Returns a spacer element (<SAMP><DIV CLASS="APE-spacer"></DIV></SAMP>)
   *
   * @return string
   */
  public static function htmlSpacer()
  {
    return ( PHP_APE_DEBUG ? "\r\n<!-- SPACER -->\r\n" : null ).'<DIV CLASS="APE-spacer"></DIV>'."\r\n";
  }

  /** Returns a separator element (<SAMP><DIV CLASS="APE-separator"></DIV></SAMP>)
   *
   * @return string
   */
  public static function htmlSeparator()
  {
    return ( PHP_APE_DEBUG ? "\r\n<!-- SEPARATOR -->\r\n" : null ).'<DIV CLASS="APE-separator"></DIV>'."\r\n";
  }

  /** Opens a (possibly floating elements) zone
   *
   * <P><B>USAGE:</B> Use this method to compartiment into separate zones.</P>
   *
   * @return string
   */
  public static function htmlZoneOpen()
  {
    return ( PHP_APE_DEBUG ? "\r\n<!-- ZONE:begin -->\r\n" : null ).'<DIV CLASS="APE-zone-begin"></DIV>';
  }

  /** Closes a (possibly floating elements) zone
   *
   * @return string
   * @see htmlZoneOpen()
   */
  public static function htmlZoneClose()
  {
    return '<DIV CLASS="APE-zone-end"></DIV>'.( PHP_APE_DEBUG ? "\r\n<!-- ZONE:end -->\r\n" : null );
  }

  /** Opens a content group
   *
   * <P><B>USAGE:</B> Use this method to group left/middle-aligned elements on a single line.</P>
   *
   * @param string $sStyle Associated (cell) CSS style directives (<SAMP>STYLE="..."</SAMP>)
   * @param string $sTableStyle Associated (table) CSS style directives (<SAMP>STYLE="..."</SAMP>)
   * @return string
   */
  public static function htmlAlignOpen( $sStyle = null, $sTableStyle = null )
  {
    // Sanitize input
    $sStyle = (string)$sStyle;

    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- ALIGN:begin -->\r\n";
    $sOutput .= '<TABLE CLASS="APE-align"';
    if( $sTableStyle ) $sOutput .= ' STYLE="'.$sTableStyle.'"';
    $sOutput .= ' CELLSPACING="0"><TR><TD CLASS="c"';
    if( $sStyle ) $sOutput .= ' STYLE="'.$sStyle.'"';
    $sOutput .= ">";
    return $sOutput;
  }

  /** Adds a content group
   *
   * <P><B>USAGE:</B> Use this method to add a new left/middle-aligned element to an opened group.</P>
   *
   * @param string $sStyle Associated CSS style directives (<SAMP>STYLE="..."</SAMP>)
   * @param boolean $bAddSeparator Add separator element
   * @return string
   * @see htmlAlignOpen()
   */
  public static function htmlAlignAdd( $sStyle = null, $bAddSeparator = true )
  {
    // Sanitize input
    $sStyle = (string)$sStyle;
    $bAddSeparator = (boolean)$bAddSeparator;

    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- ALIGN:add -->\r\n";
    $sOutput .= '</TD>';
    if( $bAddSeparator ) $sOutput .= '<TD CLASS="s">&middot;</TD>';
    $sOutput .= '<TD CLASS="c"';
    if( $sStyle ) $sOutput .= ' STYLE="'.$sStyle.'"';
    $sOutput .= ">";
    return $sOutput;
  }

  /** Closes a content group
   *
   * @return string
   * @see htmlAlignOpen()
   */
  public static function htmlAlignClose()
  {
    return '</TD></TR></TABLE>'.( PHP_APE_DEBUG ? "\r\n<!-- ALIGN:end -->\r\n" : null )."\r\n";
  }

  /** Opens a content column
   *
   * <P><B>USAGE:</B> Use this method to organize top-aligned content in columns.</P>
   *
   * @param string $sStyle Associated (cell) CSS style directives (<SAMP>STYLE="..."</SAMP>)
   * @param string $sTableStyle Associated (table) CSS style directives (<SAMP>STYLE="..."</SAMP>)
   * @return string
   */
  public static function htmlColumnOpen( $sStyle = null, $sTableStyle = null )
  {
    // Sanitize input
    $sStyle = (string)$sStyle;

    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- COLUMN:begin -->\r\n";
    $sOutput .= '<TABLE CLASS="APE-column"';
    if( $sTableStyle ) $sOutput .= ' STYLE="'.$sTableStyle.'"';
    $sOutput .= ' CELLSPACING="0"><TR><TD CLASS="c"';
    if( $sStyle ) $sOutput .= ' STYLE="'.$sStyle.'"';
    $sOutput .= ">\r\n";
    return $sOutput;
  }

  /** Adds a content column
   *
   * @param string $sStyle Associated CSS style directives (<SAMP>STYLE="..."</SAMP>)
   * @param boolean $bAddSpacer Add spacer element
   * @return string
   * @see htmlColumnOpen()
   */
  public static function htmlColumnAdd( $sStyle = null, $bAddSpacer = true )
  {
    // Sanitize input
    $sStyle = (string)$sStyle;
    $bAddSpacer = (boolean)$bAddSpacer;

    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- COLUMN:add -->\r\n";
    $sOutput .= '</TD>';
    if( $bAddSpacer ) $sOutput .= '<TD CLASS="s"></TD>';
    $sOutput .= '<TD CLASS="c"';
    if( $sStyle ) $sOutput .= ' STYLE="'.$sStyle.'"';
    $sOutput .= ">\r\n";
    return $sOutput;
  }

  /** Adds a new row to content columns
   *
   * @param string $sStyle Associated CSS style directives (<SAMP>STYLE="..."</SAMP>)
   * @return string
   * @see htmlColumnOpen()
   */
  public static function htmlColumnAddRow( $sStyle = null )
  {
    // Sanitize input
    $sStyle = (string)$sStyle;

    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- COLUMN:add -->\r\n";
    $sOutput .= '</TD>';
    $sOutput .= '</TR>';
    $sOutput .= '<TR>';
    $sOutput .= '<TD CLASS="c"';
    if( $sStyle ) $sOutput .= ' STYLE="'.$sStyle.'"';
    $sOutput .= ">\r\n";
    return $sOutput;
  }

  /** Closes a content column
   *
   * @return string
   * @see htmlColumnOpen()
   */
  public static function htmlColumnClose()
  {
    return '</TD></TR></TABLE>'.( PHP_APE_DEBUG ? "\r\n<!-- COLUMN:end -->\r\n" : null )."\r\n";
  }

  /** Opens a CSS-controlled frame
   *
   * <P><B>USAGE:</B> Use this method to surround content by a CSS-controlled frame (see the class description for further detail).</P>
   *
   * @return string
   */
  public static function htmlFrameOpen()
  {
    return ( PHP_APE_DEBUG ? "\r\n<!-- FRAME:begin -->\r\n" : null ).'<TABLE CLASS="APE-frame" CELLSPACING="0">'."\r\n";
  }

  /** Returns the header part of an opened CSS-controlled frame
   *
   * @param string $sTitle Frame title
   * @param integer $iControl Controls code (see class constants)
   * @param string $sControlID Frame controls identifier (ID)
   * @param boolean $bPassthru No HTML encoding/formatting
   * @return string
   * @see htmlFrameOpen()
   */
  public static function htmlFrameHeader( $sTitle = null, $iControl = self::Display_Control_None, $sControlID = null, $bPassthru = false )
  {
    // Sanitize input
    $sTitle = (string)$sTitle;
    $iControl = (integer)$iControl;
    $sControlID = (string)$sControlID;
    $bPassthru = (boolean)$bPassthru;

    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FRAME:header -->\r\n";
    $sOutput .= '<TR CLASS="t">';
    $sOutput .= '<TD CLASS="tl">&nbsp;</TD>';
    $sOutput .= '<TD CLASS="tc">';
    if( $iControl & self::Display_Control_All ) $sOutput .= PHP_APE_HTML_Components::htmlControlDisplay( $sControlID, $iControl );
    if( $sTitle )
    {
      if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FRAME:header:title (BEGIN) -->\r\n";
      if( $bPassthru ) $sOutput .= $sTitle;
      else
      {
        $oDataSpace = new PHP_APE_DataSpace_HTML();
        $sOutput .= '<P><SPAN>'.$oDataSpace->encodeData( $sTitle ).'</SPAN></P>';
      }
      if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FRAME:header:title (END) -->\r\n";
    }
    $sOutput .= '</TD>';
    $sOutput .= '<TD CLASS="tr">&nbsp;</TD>';
    $sOutput .= '</TR>'."\r\n";
    return $sOutput;
  }

  /** Begins the content part of an opened CSS-controlled frame
   *
   * @param string $sLeftnote Frame left's note
   * @param boolean $bPassthru No HTML encoding/formatting
   * @return string
   * @see htmlFrameOpen(), htmlFrameHeader()
   */
  public static function htmlFrameContentBegin( $sLeftnote = null, $bPassthru = false )
  {
    // Sanitize input
    $sLeftnote = (string)$sLeftnote;
    $bPassthru = (boolean)$bPassthru;

    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FRAME:content -->\r\n";
    $sOutput .= '<TR CLASS="m">';
    $sOutput .= '<TD CLASS="ml">';
    if( $sLeftnote )
    {
      if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FRAME:content:leftnote (BEGIN) -->\r\n";
      if( $bPassthru ) $sOutput .= $sLeftnote;
      else
      {
        $oDataSpace = new PHP_APE_DataSpace_HTML();
        $sOutput .= $oDataSpace->encodeData( $sLeftnote );
      }
      if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FRAME:content:leftnote (END) -->\r\n";
    }
    $sOutput .= '</TD>';
    $sOutput .= '<TD CLASS="mc">'."\r\n";
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FRAME:content (BEGIN) -->\r\n";
    return $sOutput;
  }

  /** Ends the content part of an opened CSS-controlled frame
   *
   * @param string $sRightnote Frame right's note
   * @param boolean $bPassthru No HTML encoding/formatting
   * @return string
   * @see htmlFrameOpen(), htmlFrameContentBegin()
   */
  public static function htmlFrameContentEnd( $sRightnote = null, $bPassthru = false )
  {
    // Sanitize input
    $sRightnote = (string)$sRightnote;
    $bPassthru = (boolean)$bPassthru;

    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FRAME:content (END) -->\r\n";
    $sOutput .= "\r\n".'</TD>';
    $sOutput .= '<TD CLASS="mr">';
    if( $sRightnote )
    {
      if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FRAME:content:rightnote (BEGIN) -->\r\n";
      if( $bPassthru ) $sOutput .= $sRightnote;
      else
      {
        $oDataSpace = new PHP_APE_DataSpace_HTML();
        $sOutput .= $oDataSpace->encodeData( $sRightnote );
      }
      if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FRAME:content:rightnote (END) -->\r\n";
    }
    $sOutput .= '</TD>';
    $sOutput .= '</TR>'."\r\n";
    return $sOutput;
  }

  /** Returns the footer part of an opened CSS-controlled frame
   *
   * @param string $sFootnote Frame footnote
   * @param boolean $bPassthru No HTML encoding/formatting
   * @return string
   * @see htmlFrameOpen(), htmlFrameHeader()
   */
  public static function htmlFrameFooter( $sFootnote = null, $bPassthru = false )
  {
    // Sanitize input
    $sFootnote = (string)$sFootnote;
    $bPassthru = (boolean)$bPassthru;

    // Output
    $sOutput = null;
    $oDataSpace = new PHP_APE_DataSpace_HTML();
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FRAME:footer -->\r\n";
    $sOutput .= '<TR CLASS="b">';
    $sOutput .= '<TD CLASS="bl">&nbsp;</TD>';
    $sOutput .= '<TD CLASS="bc">';
    if( $sFootnote )
    {
      if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FRAME:footer:footnote (BEGIN) -->\r\n";
      if( $bPassthru ) $sOutput .= $sFootnote;
      else
      {
        $oDataSpace = new PHP_APE_DataSpace_HTML();
        $sOutput .= $oDataSpace->encodeData( $sFootnote );
      }
      if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FRAME:footer:footnote (END) -->\r\n";
    }
    $sOutput .= '</TD>';
    $sOutput .= '<TD CLASS="br">&nbsp;</TD>';
    $sOutput .= '</TR>'."\r\n";
    return $sOutput;
  }

  /** Closes a CSS-controlled frame
   *
   * @return string
   * @see htmlFrameOpen()
   */
  public static function htmlFrameClose()
  {
    return '</TABLE>'.( PHP_APE_DEBUG ? "\r\n<!-- FRAME:end -->\r\n" : null )."\r\n";
  }

  /** Opens a CSS-controlled box (title-less and control-less frame)
   *
   * @return string
   * @see htmlFrameOpen(), htmlFrameHeader(), htmlFrameContentBegin()
   */
  public static function htmlBoxOpen()
  {
    return self::htmlFrameOpen().self::htmlFrameHeader().self::htmlFrameContentBegin();
  }

  /** Closes a CSS-controlled box (footer-less frame)
   *
   * @return string
   * @see htmlFrameClose(), htmlFrameFooter(), htmlFrameContentEnd()
   */
  public static function htmlBoxClose()
  {
    return self::htmlFrameContentEnd().self::htmlFrameFooter().self::htmlFrameClose();
  }

  /** Returns a CSS-controlled icon
   *
   * <P><B>USAGE:</B> Use this method to output a CSS-controlled icon (see the class description for further detail).</P>
   *
   * @param string $sID Icon identifier (ID)
   * @param string $sURL Icon URL (<SAMP>HREF="..."</SAMP>)
   * @param string $sTooltip Icon tooltip (<SAMP>TITLE="..."</SAMP>)
   * @param string $sTarget Icon target (<SAMP>TARGET="..."</SAMP>)
   * @param boolean $bForce Display icon (regardless of <SAMP>php_ape.html.display.icon</SAMP> environment preference)
   * @see PHP_APE_HTML_Tags::htmlAnchor()
   */
  public static function htmlIcon( $sID, $sURL = null, $sTooltip = null, $sTarget = null, $bForce = true )
  {
    // Sanitize input
    $sID = (string)$sID;
    $sURL = (string)$sURL;
    $sTooltip = (string)$sTooltip;
    $sTarget = (string)$sTarget;
    $bForce = (boolean)$bForce;

    // Output
    $sOutput = null;
    $roEnvironment =& PHP_APE_HTML_WorkSpace::useEnvironment();
    if( $bForce or $roEnvironment->getUserParameter( 'php_ape.html.display.icon' ) > self::Icon_Hide )
    {
      // Prepare icon
      $oDataSpace = new PHP_APE_DataSpace_HTML();
      $sIcon = null;
      $sIcon .= '<DIV CLASS="'.$sID.'"';
      if( $sTooltip && !$sURL ) $sIcon .= ' TITLE="'.$oDataSpace->encodeData( $sTooltip ).'" STYLE="CURSOR:help;"';
      $sIcon .= '></DIV>';

      // Output icon
      $sOutput = null;
      if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- ICON:begin -->\r\n";
      $sOutput .= '<DIV CLASS="APE-icon">';
      $sOutput .= $sURL ? PHP_APE_HTML_Tags::htmlAnchor( $sURL, $sIcon, $sTooltip, $sTarget, true ) : $sIcon;
      $sOutput .= '</DIV>';
      if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- ICON:end -->\r\n";
    }
    return $sOutput;
  }

  /** Returns a CSS-controlled label (icon + anchor)
   *
   * <P><B>USAGE:</B> Use this method to output a CSS-controlled label (see the class description for further detail).</P>
   *
   * @param string $sLabelName Label name
   * @param string $sIconID Icon identifier (ID)
   * @param string $sURL Label/icon URL
   * @param string $sTooltip Label/icon tooltip (<SAMP>TITLE="..."</SAMP>)
   * @param string $sTarget Label/icon target (<SAMP>TARGET="..."</SAMP>)
   * @param boolean $bLabelForce Display label (regardless of <SAMP>php_ape.html.display.icon</SAMP> environment preference)
   * @param boolean $bIconForce Display icon (regardless of <SAMP>php_ape.html.display.icon</SAMP> environment preference)
   * @param string $sLabelTag Surrounding HTML tag
   * @param boolean $bPassthru No HTML encoding/formatting
   * @see PHP_APE_HTML_Tags::htmlAnchor(), PHP_APE_HTML_SmartTags::htmlIcon()
   */
  public static function htmlLabel( $sLabelName, $sIconID = null, $sURL = null, $sTooltip = null, $sTarget = null, $bLabelForce = true, $bIconForce = false, $sLabelTag = 'P', $bPassthru = false )
  {
    // Sanitize input
    $sLabelName = (string)$sLabelName;
    $sIconID = (string)$sIconID;
    $sURL = (string)$sURL;
    $sTooltip = (string)$sTooltip;
    $sTarget = (string)$sTarget;
    $bLabelForce = (boolean)$bLabelForce;
    $bIconForce = (boolean)$bIconForce;
    $sLabelTag = (string)strtoupper( $sLabelTag ); if( !in_array( $sLabelTag, array( 'P', 'H1', 'H2', 'H3', 'H4', 'H5', 'H6' ) ) ) $sLabelTag = 'P';
    $bPassthru = (boolean)$bPassthru;

    // Verify display status
    $roEnvironment =& PHP_APE_HTML_WorkSpace::useEnvironment();
    $bIconDisplay = $sIconID && ( $bIconForce || $roEnvironment->getUserParameter( 'php_ape.html.display.icon' ) > self::Icon_Hide );
    $bLabelDisplay = $sLabelName && ( $bLabelForce || $roEnvironment->getUserParameter( 'php_ape.html.display.icon' ) < self::Icon_NoLabel );

    // Output
    $sOutput = null;
    $oDataSpace = new PHP_APE_DataSpace_HTML();
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- LABEL:begin -->\r\n";
    if( $bLabelDisplay or $bIconDisplay )
    {
      $sOutput .= '<DIV CLASS="APE-label">';
      if( $bLabelDisplay )
      {
        $sOutput .= '<TABLE CLASS="APE-label" CELLSPACING="0"';
        if( $sTooltip and !$sURL ) $sOutput .= ' TITLE="'.$oDataSpace->encodeData( $sTooltip ).'" STYLE="CURSOR:help;"';
        $sOutput .= '><TR>';
        if( $bIconDisplay ) $sOutput .= '<TD CLASS="i">'.self::htmlIcon( $sIconID, $bLabelDisplay ? null : $sURL, $sTooltip, $sTarget, true ).'</TD>';
        $sOutput .= '<TD CLASS="l">';
        $sOutput .= '<'.$sLabelTag.'>'.( $sURL ? PHP_APE_HTML_Tags::htmlAnchor( $sURL, $sLabelName, $sTooltip, $sTarget, $bPassthru ) : ( $bPassthru ? $sLabelName : $oDataSpace->encodeData( $sLabelName ) ) ).'</'.$sLabelTag.'>';
        $sOutput .= '</TD>';
        $sOutput .= '</TR></TABLE>';
      }
      else
      {
        $sOutput .= '<TABLE CLASS="APE-label" CELLSPACING="0"><TR>';
        $sOutput .= '<TD CLASS="i">'.self::htmlIcon( $sIconID, $sURL, $sTooltip, $sTarget, true ).'</TD>';
        $sOutput .= '</TR></TABLE>';
      }
      $sOutput .= '</DIV>';
    }
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- LABEL:end -->\r\n";
    return $sOutput;
  }

  /** Returns a CSS-controlled button (label + icon + anchor)
   *
   * <P><B>USAGE:</B> Use this method to output a CSS-controlled button (see the class description for further detail).</P>
   *
   * @param string $sLabelName Label name
   * @param string $sIconID Icon identifier (ID)
   * @param string $sURL Button URL
   * @param string $sTooltip Button tooltip (<SAMP>TITLE="..."</SAMP>)
   * @param string $sTarget Button target (<SAMP>TARGET="..."</SAMP>)
   * @param boolean $bLabelForce Display label (regardless of <SAMP>php_ape.html.display.icon</SAMP> environment preference)
   * @param boolean $bIconForce Display icon (regardless of <SAMP>php_ape.html.display.icon</SAMP> environment preference)
   * @param boolean $bIconSplit Display icon in button and label right of the button
   * @uses PHP_APE_HTML_SmartTags::htmlLabel(), PHP_APE_HTML_SmartTags::htmlIcon()
   */
  public static function htmlButton( $sLabelName = null, $sIconID = null, $sURL = null, $sTooltip = null, $sTarget = null, $bLabelForce = true, $bIconForce = false, $bIconSplit = false )
  {
    // Sanitize input
    $sLabelName = (string)$sLabelName;
    $sIconID = (string)$sIconID;
    $sURL = (string)$sURL;
    $sTooltip = (string)$sTooltip;
    $sTarget = (string)$sTarget;
    $bLabelForce = (boolean)$bLabelForce;
    $bIconForce = (boolean)$bIconForce;
    $bIconSplit = (boolean)$bIconSplit;

    // Verify display status
    $roEnvironment =& PHP_APE_HTML_WorkSpace::useEnvironment();
    if( !$sIconID ) $bLabelForce=true;
    if( !$sLabelName ) $bIconForce=true;
    if( !$bLabelForce and !$bIconForce ) $bLabelForce=true;
    $bIconDisplay = $sIconID && ( $bIconForce || $roEnvironment->getUserParameter( 'php_ape.html.display.icon' ) > self::Icon_Hide );
    $bLabelDisplay = $sLabelName && ( $bLabelForce || $roEnvironment->getUserParameter( 'php_ape.html.display.icon' ) < self::Icon_NoLabel );

    // Output
    $sOutput = null;
    $oDataSpace = new PHP_APE_DataSpace_HTML();
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- BUTTON:begin -->\r\n";
    $sOutput .= '<DIV CLASS="APE-button">';
    if( !$bIconSplit )
    {
      $sOutput .= '<TABLE CLASS="APE-button" CELLSPACING="0"';
      if( $sTooltip ) $sOutput .= ' TITLE="'.$oDataSpace->encodeData( $sTooltip ).'" STYLE="CURSOR:help;"';
      $sOutput .= '><TR>';
      $sOutput .= '<TD CLASS="l">&nbsp;</TD>';
      $sOutput .= '<TD CLASS="c">';
      $sOutput .= self::htmlLabel( $sLabelName, $sIconID, $sURL, null, $sTarget, $bLabelForce, $bIconForce );
      $sOutput .= '</TD>';
      $sOutput .= '<TD CLASS="r">&nbsp;</TD>';
      $sOutput .= '</TR></TABLE>';
    }
    else
    {
      $sOutput .= self::htmlAlignOpen();
      if( $bIconDisplay )
      {
        $sOutput .= '<TABLE CLASS="APE-button" CELLSPACING="0"';
        if( $sTooltip ) $sOutput .= ' TITLE="'.$oDataSpace->encodeData( $sTooltip ).'" STYLE="CURSOR:help;"';
        $sOutput .= '><TR>';
        $sOutput .= '<TD CLASS="l">&nbsp;</TD>';
        $sOutput .= '<TD CLASS="c">';
        $sOutput .= self::htmlIcon( $sIconID, $sURL, null, $sTarget, true );
        $sOutput .= '</TD>';
        $sOutput .= '<TD CLASS="r">&nbsp;</TD>';
        $sOutput .= '</TR></TABLE>';
      }
      $sOutput .= self::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
      if( $bLabelDisplay )
      {
        $sOutput .= self::htmlLabel( $sLabelName, null, $sURL, $sTooltip, $sTarget );
      }
      $sOutput .= self::htmlAlignClose();
    }
    $sOutput .= '</DIV>';
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- BUTTON:end -->\r\n";
    return $sOutput;
  }

  /** 
   *
   * <P><B>USAGE:</B> Use this method to group left/middle-aligned elements on a single line.</P>
   *
   * @param string $sStyle Associated (cell) CSS style directives (<SAMP>STYLE="..."</SAMP>)
   * @param string $sTableStyle Associated (table) CSS style directives (<SAMP>STYLE="..."</SAMP>)
   * @return string
   */
  public static function htmlAlert( $asMessages )
  {
    // Sanitize input
    $asMessages = PHP_APE_Type_Array::parseValue( $asMessages );

    // Output
    $oDataSpace_JavaScript = new PHP_APE_DataSpace_JavaScript();
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- ALERT:begin -->\r\n";
    $sOutput .= '<SCRIPT TYPE="text/javascript">'."\r\n";
    foreach( $asMessages as $sMessage )
      $sOutput .= "window.alert('".$oDataSpace_JavaScript->encodeData( $sMessage )."');\r\n";
    $sOutput .= '</SCRIPT>'."\r\n";
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- ALERT:end -->\r\n";
    return $sOutput;
  }

}
