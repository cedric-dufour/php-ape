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
 * @subpackage Page
 */

/** User interface (UI) factory
 *
 * @package PHP_APE_HTML
 * @subpackage Page
 */
class PHP_APE_HTML_Factory
extends PHP_APE_HTML_Any
{

  /*
   * FIELDS
   ********************************************************************************/

  /** HTML data space
   * @var PHP_APE_DataSpace_HTML */
  static public $oDataSpace_HTML;

  /** JavaScript data space
   * @var PHP_APE_DataSpace_JavaScript */
  static public $oDataSpace_JavaScript;

  /** User interface identifier (ID)
   * @var mixed */
  protected $mID;

  /** User interface (root) URL
   * @var string */
  protected $sURL;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new user interface instance
   *
   * @param mixed $mID Data/controller identifier (ID)
   * @param string $sURL Controller (root) URL
   */
  public function __construct( $mID, $sURL )
  {
    // Sanizite input
    $mID = PHP_APE_Type_Index::parseValue( $mID );
    $sURL = rtrim( PHP_APE_Type_Path::parseValue( $sURL ), '/' );

    // Local construction
    $this->mID = $mID;
    $this->sURL = $sURL;
  }

  public static function __static()
  {
    if( is_null( self::$oDataSpace_HTML ) ) self::$oDataSpace_HTML = new PHP_APE_DataSpace_HTML();
    if( is_null( self::$oDataSpace_JavaScript ) ) self::$oDataSpace_JavaScript = new PHP_APE_DataSpace_JavaScript();
  }


  /*
   * METHODS: properties
   ********************************************************************************/

  /** Returns this user interface's identifier (ID)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return mixed
   */
  final public function getID()
  {
    return $this->mID;
  }

  /** Returns this user interface's (root) URL
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getURL()
  {
    return $this->sURL;
  }


  /*
   * METHODS: HTML components
   ********************************************************************************/

  /** Returns the HTML page's header
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return string
   */
  public function htmlHeader()
  {
    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen();
    $sOutput .= '<IMG ALT="PHP-APE" TITLE="PHP-APE" WIDTH="48" HEIGHT="48" SRC="'.PHP_APE_HTML_WorkSpace::useEnvironment()->getVolatileParameter( 'php_ape.html.htdocs.url' ).'/img/php-ape-48x48.png"/>';
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( null, false );
    $sOutput .= '<H1>'.PHP_APE_WorkSpace::useEnvironment()->getVolatileParameter( 'php_ape.application.name' ).'</H1>';
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();
    $sOutput .= PHP_APE_HTML_SmartTags::htmlSeparator();
    return $sOutput;
  }

  /** Returns the HTML page's (top) title
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return string
   */
  public function htmlTitle()
  {
    // Output
    return PHP_APE_HTML_SmartTags::htmlLabel( 'PHP-APE Application', 'M-control', null, null, null, true, false, 'H1' );
  }

  /** Returns the HTML page's content prefix
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return string
   */
  public function htmlContentPrefix()
  {
    // Output
    return "<BLOCKQUOTE>\r\n";
  }

  /** Returns the HTML page's sub-title
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return string
   */
  public function htmlContentTitle( PHP_APE_Data_hasName $oNamed, $sIconID = null )
  {
    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( $oNamed->getName(), $sIconID, null, null, null, true, false, 'H2' );
    if( ( $oNamed instanceof PHP_APE_Data_hasDescription ) and $oNamed->hasDescription() )
      $sOutput .= '<P>'.self::$oDataSpace_HTML->encodeData( $oNamed->getDescription() ).'</P>'."\r\n";
    return $sOutput;
  }

  /** Returns the HTML page's content suffix
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return string
   */
  public function htmlContentSuffix()
  {
    // Output
    return "</BLOCKQUOTE>\r\n";
  }

  /** Returns the HTML page's footer
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return string
   */
  public function htmlFooter()
  {
    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_SmartTags::htmlSeparator();
    $sOutput .= '<DIV CLASS="do-not-print" STYLE="FLOAT:right;PADDING:2px;">'."\r\n";
    $sOutput .= PHP_APE_HTML_Components::htmlPreferences();
    $sOutput .= '</DIV>'."\r\n";
    $sOutput .= '<DIV STYLE="CLEAR:both;"></DIV>'."\r\n";
    return $sOutput;
  }

}

// Initialize static fields
PHP_APE_HTML_Factory::__static();
