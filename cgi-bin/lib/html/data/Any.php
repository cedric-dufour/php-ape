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
 * @subpackage Classes
 */

/** Core HTML data class
 *
 * @package PHP_APE_HTML
 * @subpackage Classes
 */
abstract class PHP_APE_HTML_Data_Any
extends PHP_APE_HTML_Any
{

  /*
   * METHODS: static
   ********************************************************************************/

  /** Returns the HTML resource identifier (ID) for the given element identifier (ID)
   *
   * <P><B>NOTE:</B> This method returns a unique resource identifier (ID) which is safe to use HTML, JavaScript and Regular Expressions data spaces.</P>
   *
   * @param mixed $mID HTML data page identifier (ID)
   * @return string
   */
  public static function makeRID( $mID )
  {
    static $sType;
    if( is_null( $sType ) ) $sType = PHP_APE_HTML_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.html.rid' );
    $mID = PHP_APE_Type_Index::parseValue( $mID );
    switch( $sType )
    {
    case 'md5': return md5( $mID );
    case 'long': return substr( md5( $mID ), 0, 8 ).substr( sha1( $mID ), 0, 8 );
    default: return substr( md5( $mID ), 0, 4 ).substr( sha1( $mID ), 0, 4 );
    }
  }

}
