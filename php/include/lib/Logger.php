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
 * @package PHP_APE
 * @subpackage WorkSpace
 */

/** Generic logging facility
 *
 * @package PHP_APE
 * @subpackage WorkSpace
 */
class PHP_APE_Logger
{

  /*
   * METHODS
   ********************************************************************************/

  /** Logs (triggers) a PHP message
   *
   * <P><B>NOTE:</B> This methods calls the PHP <SAMP>trigger_error</SAMP> function, formatting the
   * message in a more developer-friendly way.</P>
   *
   * @param string $sContext Triggering context
   * @param string $sMessage Error message
   * @param int $iType Message type
   * @see PHP_MANUAL#trigger_error() trigger_error()
   */
  public static function log( $sContext, $sMessage, $iType )
  {
    trigger_error( '['.$sContext.'] '.$sMessage, $iType );
  }

}
