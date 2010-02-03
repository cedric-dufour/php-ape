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
 * @package PHP_APE_RSS
 * @subpackage WorkSpace
 */

/** RSS workspace
 *
 * <P><B>USAGE:</B></P>
 * <P>The following static parameters (properties) are provisioned by this workspace:</P>
 * <UL>
 * <LI><SAMP>php_ape.rss.xsl.url</SAMP>: default associated XSL stylesheet URL [default: <SAMP>http://localhost/php-ape/lib/rss/default.xsl</SAMP>]</LI>
 * </UL>
 *
 * @package PHP_APE_RSS
 * @subpackage WorkSpace
 */
class PHP_APE_RSS_WorkSpace
extends PHP_APE_WorkSpace
{

  /*
   * FIELDS: static
   ********************************************************************************/

  /** Work space singleton
   * @var PHP_APE_RSS_WorkSpace */
  private static $oWORKSPACE;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs the workspace
   */
  protected function __construct()
  {
    // Call the parent constructor
    parent::__construct();
  }


  /*
   * METHODS: factory
   ********************************************************************************/

  /** Returns the (singleton) environment instance (<B>as reference</B>)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   *
   * @return PHP_APE_RSS_WorkSpace
   */
  public static function &useEnvironment()
  {
    if( is_null( self::$oWORKSPACE ) ) self::$oWORKSPACE = new PHP_APE_RSS_WorkSpace();
    return self::$oWORKSPACE;
  }


  /*
   * METHODS: verification
   ********************************************************************************/

  /** Verify and sanitize the supplied parameters
   *
   * @param array|string $rasParameters Input/output parameters (<B>as reference</B>)
   */
  protected function _verifyParameters( &$rasParameters )
  {

    // Parent environment
    parent::_verifyParameters( $rasParameters );

    // Default associated XSL stylesheet URL
    if( array_key_exists( 'php_ape.rss.xsl.url', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.rss.xsl.url' ];
      $rValue = trim( PHP_APE_Type_Path::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = 'http://localhost/php-ape/lib/rss/default.xsl';
      else
        $rValue = rtrim( $rValue, '/' );
    }

    // Default channel Time-To-Live (minutes)
    if( array_key_exists( 'php_ape.rss.ttl', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.rss.ttl' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( empty( $rValue ) ) 
        $rValue = 1440;
      elseif( $rValue < 1 )
        $rValue = 1;
    }

  }


  /*
   * METHODS: static parameters - OVERRIDE
   ********************************************************************************/

  protected function _mandatoryStaticParameters()
  {
    return array_merge( parent::_mandatoryStaticParameters(),
                        array(
                              'php_ape.rss.xsl.url' => null,
                              'php_ape.rss.ttl' => null,
                              )
                        );
  }

}
