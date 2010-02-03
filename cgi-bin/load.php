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

/** Load resources
 */

/*
 * PHP-APE ENVIRONMENT
 ********************************************************************************/

/** PHP-APE version
 */
define( 'PHP_APE_VERSION', '1.1.20100201' );

if( !defined( 'PHP_APE_ROOT' ) )
{
  /** @ignore */
  if( array_key_exists( 'PHP_APE_ROOT', $_SERVER ) ) define( 'PHP_APE_ROOT', rtrim( $_SERVER[ 'PHP_APE_ROOT' ], '/' ) );
  /** @ignore */
  elseif( array_key_exists( 'PHP_APE_ROOT', $_ENV ) ) define( 'PHP_APE_ROOT', rtrim( $_ENV[ 'PHP_APE_ROOT' ], '/' ) );
  /** PHP-APE root filepath (used for inclusion)
   *
   * <P><B>NOTE:</B> Automatically computed as <SAMP>dirname( __FILE__ )</SAMP>.</P>
   */
  else define( 'PHP_APE_ROOT', dirname( __FILE__ ) );
}

if( !defined( 'PHP_APE_CACHE' ) )
{
  /** @ignore */
  if( array_key_exists( 'PHP_APE_CACHE', $_SERVER ) ) define( 'PHP_APE_CACHE', rtrim( $_SERVER[ 'PHP_APE_CACHE' ], '/' ) );
  /** @ignore */
  elseif( array_key_exists( 'PHP_APE_CACHE', $_ENV ) ) define( 'PHP_APE_CACHE', rtrim( $_ENV[ 'PHP_APE_CACHE' ], '/' ) );
  /** PHP-APE caching (directory) path
   *
   * <P>Retrieved from system or server environment variable <SAMP>PHP_APE_CACHE</SAMP>, if existing [default: '<SAMP>/tmp/php-ape</SAMP>'].</P>
   */
  else define( 'PHP_APE_CACHE', '/tmp/php-ape' );
}

if( !defined( 'PHP_APE_DATA' ) )
{
  /** @ignore */
  if( array_key_exists( 'PHP_APE_DATA', $_SERVER ) ) define( 'PHP_APE_DATA', rtrim( $_SERVER[ 'PHP_APE_DATA' ], '/' ) );
  /** @ignore */
  elseif( array_key_exists( 'PHP_APE_DATA', $_ENV ) ) define( 'PHP_APE_DATA', rtrim( $_ENV[ 'PHP_APE_DATA' ], '/' ) );
  /** PHP-APE environment (directory) path
   *
   * <P>Retrieved from system or server environment variable <SAMP>PHP_APE_DATA</SAMP>, if existing [default: '<SAMP>/var/lib/php-ape</SAMP>'].</P>
   */
  else define( 'PHP_APE_DATA', '/var/lib/php-ape' );
}

if( !defined( 'PHP_APE_CONF' ) )
{
  /** @ignore */
  if( array_key_exists( 'PHP_APE_CONF', $_SERVER ) ) define( 'PHP_APE_CONF', $_SERVER[ 'PHP_APE_CONF' ] );
  /** @ignore */
  elseif( array_key_exists( 'PHP_APE_CONF', $_ENV ) ) define( 'PHP_APE_CONF', $_ENV[ 'PHP_APE_CONF' ] );
  /** PHP-APE global properties filepath (used for environment configuration)
   *
   * <P>Retrieved from system or server environment variable <SAMP>PHP_APE_CONF</SAMP>, if existing [default: '<SAMP>/etc/php-ape/php-ape.conf.php</SAMP>'].</P>
   */
  else define( 'PHP_APE_CONF', '/etc/php-ape/php-ape.conf.php' );
}

if( !defined( 'PHP_APE_DEBUG' ) )
{
  /** @ignore */
  if( array_key_exists( 'PHP_APE_DEBUG', $_SERVER ) ) define( 'PHP_APE_DEBUG', $_SERVER[ 'PHP_APE_DEBUG' ] );
  /** @ignore */
  elseif( array_key_exists( 'PHP_APE_DEBUG', $_ENV ) ) define( 'PHP_APE_DEBUG', $_ENV[ 'PHP_APE_DEBUG' ] );
  /** PHP-APE debugging flag
   *
   * <P>Retrieved from system or server environment variable <SAMP>PHP_APE_DEBUG</SAMP>, if existing [default: <SAMP>false</SAMP>].</P>
   */
  else define( 'PHP_APE_DEBUG', false );
}

/*
 * CORE RESOURCES
 ********************************************************************************/

/** Core resources: constants */
require_once( PHP_APE_ROOT.'/lib/Constants.php' );

/** Core resources: exception */
require_once( PHP_APE_ROOT.'/lib/Exception.php' );

/** Core resources: logger */
require_once( PHP_APE_ROOT.'/lib/Logger.php' );

/** Core resources: properties */
require_once( PHP_APE_ROOT.'/lib/Properties.php' );

/** Core resources: resources */
require_once( PHP_APE_ROOT.'/lib/Resources.php' );

/*
 * INITIALIZATION: resources
 ********************************************************************************/

/** Initialize resources */
PHP_APE_Resources::init();

/*
 * AUTOLOADING
 ********************************************************************************/

/** PHP classes/interfaces auto-loading function
 *
 * <P><B>NOTE:</B> This function relies on <SAMP>{@link PHP_APE_Resources}</SAMP> class and methods to find the required resource among
 * the configured resources paths.</P>
 *
 * @param string $sResourceName Resource name
 * @see PHP_MANUAL#__autoload()
 */
function __autoload( $sResourceName )
{
  PHP_APE_Resources::loadDefinition( $sResourceName );
}

/*
 * ADDITIONAL RESOURCES
 ********************************************************************************/

/** Core resources: PEAR */
require_once( 'PEAR.php' );

/** Core resources: environment */
require_once( PHP_APE_ROOT.'/lib/Environment.php' );

/** Core resources: workspace */
require_once( PHP_APE_ROOT.'/lib/hasInterface.php' );

/** Core resources: workspace */
require_once( PHP_APE_ROOT.'/lib/WorkSpace.php' );

/** Core resources: type */
require_once( PHP_APE_ROOT.'/lib/type/load.php' );

/** Core resources: data */
require_once( PHP_APE_ROOT.'/lib/data/load.php' );

/** Core resources: dataspace */
require_once( PHP_APE_ROOT.'/lib/dataspace/load.php' );

/** Core resources: authentication/authorization */
require_once( PHP_APE_ROOT.'/lib/auth/load.php' );

/** Core resources: utilities */
require_once( PHP_APE_ROOT.'/lib/util/load.php' );

/** Additional resources: user defined */
foreach( PHP_APE_WorkSpace::useEnvironment()->getStaticParameter( 'php_ape.require' ) as $sRequiredFile ) require_once( $sRequiredFile );
