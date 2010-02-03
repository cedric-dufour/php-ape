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
 * @package PHP_APE_Auth
 * @subpackage Exceptions
 */

/** Authentication exception
 *
 * @package PHP_APE_Auth
 * @subpackage Exceptions
 */
class PHP_APE_Auth_AuthenticationException
extends PHP_APE_Auth_Exception
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Authenticating principal
   * @var string */
  private $sPrincipal;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new exception
   *
   * @param string $sContext Triggering context
   * @param string $sMessage Error message
   * @param int $iCode Error code
   * @param string $sPrincipal Authenticating principal
   */
  public function __construct( $sContext, $sMessage, $iCode = 0, $sPrincipal = null ) {
    parent::__construct( $sContext, $sMessage, $iCode );
    $this->sPrincipal = $sPrincipal;
  }


  /*
   * METHODS: properties
   ********************************************************************************/

  /** Returns the authenticating principal
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getPrincipal()
  {
    return $this->sPrincipal;
  }


  /*
   * METHODS: logging - OVERRIDE
   ********************************************************************************/

  /** Log the exception to the error log
   *
   * @see PHP_MANUAL#error_log() error_log()
   */
  public function logError()
  {
    error_log( 'PHP Exception:  '.$this->getFile().'('.$this->getLine().'): ['.$this->getContext().'('.$this->getCode().')] '.$this->getMessage().' {principal:'.$this->sPrincipal.'}' );
  }

}
