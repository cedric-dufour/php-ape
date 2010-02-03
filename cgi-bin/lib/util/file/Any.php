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
 * @subpackage File
 */

/** File-related core utility class
 *
 * @package PHP_APE_Util
 * @subpackage File
 */
abstract class PHP_APE_Util_File_Any
{

  /*
   * METHODS: info
   ********************************************************************************/

  /** Returns the string representation for the given (numerical) permissions
   *
   * @param integer $iPermissions File permissions
   * @return string
   */
  public static function getPermsString( $iPermissions )
  {
    // Output (NOTE: Cut 'n Paste from PHP documentation)
    $sPermissions = null;
    if (($iPermissions & 0xC000) == 0xC000) {
      // Socket
      $sPermissions = 's';
    } elseif (($iPermissions & 0xA000) == 0xA000) {
      // Symbolic Link
      $sPermissions = 'l';
    } elseif (($iPermissions & 0x8000) == 0x8000) {
      // Regular
      $sPermissions = '-';
    } elseif (($iPermissions & 0x6000) == 0x6000) {
      // Block special
      $sPermissions = 'b';
    } elseif (($iPermissions & 0x4000) == 0x4000) {
      // Directory
      $sPermissions = 'd';
    } elseif (($iPermissions & 0x2000) == 0x2000) {
      // Character special
      $sPermissions = 'c';
    } elseif (($iPermissions & 0x1000) == 0x1000) {
      // FIFO pipe
      $sPermissions = 'p';
    } else {
      // Unknown
      $sPermissions = 'u';
    }
    // Owner
    $sPermissions .= (($iPermissions & 0x0100) ? 'r' : '-');
    $sPermissions .= (($iPermissions & 0x0080) ? 'w' : '-');
    $sPermissions .= (($iPermissions & 0x0040) ?
              (($iPermissions & 0x0800) ? 's' : 'x' ) :
              (($iPermissions & 0x0800) ? 'S' : '-'));
    // Group
    $sPermissions .= (($iPermissions & 0x0020) ? 'r' : '-');
    $sPermissions .= (($iPermissions & 0x0010) ? 'w' : '-');
    $sPermissions .= (($iPermissions & 0x0008) ?
              (($iPermissions & 0x0400) ? 's' : 'x' ) :
              (($iPermissions & 0x0400) ? 'S' : '-'));
    // World
    $sPermissions .= (($iPermissions & 0x0004) ? 'r' : '-');
    $sPermissions .= (($iPermissions & 0x0002) ? 'w' : '-');
    $sPermissions .= (($iPermissions & 0x0001) ?
              (($iPermissions & 0x0200) ? 't' : 'x' ) :
              (($iPermissions & 0x0200) ? 'T' : '-'));

    // End
    return $sPermissions;
  }


  /*
   * METHODS: encode/decode
   ********************************************************************************/

  /** Encode (converts) path from (PHP) internal charset to filesystem charset
   *
   * @param string $sPath Path to encode
   * @return string
   */
  public static function encodePath( $sPath )
  {
    // Environment
    $roEnvironment =& PHP_APE_WorkSpace::useEnvironment();
    $sCharSet_Data = $roEnvironment->getVolatileParameter( 'php_ape.data.charset' );
    $sCharSet_File = $roEnvironment->getVolatileParameter( 'php_ape.filesystem.charset' );
    return iconv( $sCharSet_Data, $sCharSet_File, $sPath );
  }

  /** Decode (converts) path from filesystem charset to (PHP) internal charset
   *
   * @param string $sPath Path to decode
   * @return string
   */
  public static function decodePath( $sPath )
  {
    // Environment
    $roEnvironment =& PHP_APE_WorkSpace::useEnvironment();
    $sCharSet_Data = $roEnvironment->getVolatileParameter( 'php_ape.data.charset' );
    $sCharSet_File = $roEnvironment->getVolatileParameter( 'php_ape.filesystem.charset' );
    return iconv( $sCharSet_File, $sCharSet_Data, $sPath );
  }

}
