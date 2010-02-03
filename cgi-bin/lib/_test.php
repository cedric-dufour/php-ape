<?php // INDENTING (emacs/vi): -*- mode:php; tab-width:2; c-basic-offset:2; intent-tabs-mode:nil; -*- ex: set tabstop=2 expandtab:
/*
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
 */

/** TESTS
 *
 * <P><B>SYNOPSIS:</B> This script tests the definition and behaviour of the objects included in this package,
 * which should allow developers and users to verify the functionality of its resources,
 * and hopefully also gain a better understanding on how to implement or use them.</P>
 *
 * @package PHP_APE
 * @subpackage Tests
 */

/** Load PHP-APE */
echo "<H1>APE tests</H1>\r\n";
echo "<P><B>SYNOPSIS:</B> This script tests the definition and behaviour of the objects included in this package,\r\n";
echo "which should allow developers and users to verify the functionality of its resources,\r\n";
echo "and hopefully also gain a better understanding on how to implement or use them.</P>\r\n";
require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );

// Workspace
echo "<H2>APE workspace</H2>\r\n";
echo "<BLOCKQUOTE>\r\n";
echo "<P>\r\n";
echo 'PHP_APE_ROOT: '.PHP_APE_ROOT."<BR/>\r\n";
echo 'PHP_APE_CONF: '.PHP_APE_CONF."<BR/>\r\n";
echo "</P>\r\n";
echo "</BLOCKQUOTE>\r\n";
$roEnvironment = PHP_APE_WorkSpace::useEnvironment();

// ... user
echo "<H3>User</H3>\r\n";
echo "<BLOCKQUOTE>\r\n";
echo "<P>\r\n";
echo 'User Key: '.$roEnvironment->getUserKey()."<BR/>\r\n";
echo "</P>\r\n";
echo "</BLOCKQUOTE>\r\n";

// ... static parameters
echo "<H3>Static parameters (properties)</H3>\r\n";
echo "<BLOCKQUOTE>\r\n";
echo "<P>\r\n";
$asNames = $roEnvironment->getStaticParametersNames();
foreach( $asNames as $sName )
{
  $mParameter = $roEnvironment->getStaticParameter( $sName );
  if( is_array( $mParameter ) ) foreach( $mParameter as $sKey => $sValue ) echo $sName.'['.$sKey.']: '.$sValue."<BR/>\r\n";
  else echo $sName.': '.$mParameter."<BR/>\r\n";
}
echo "</P>\r\n";
echo "</BLOCKQUOTE>\r\n";

// ... persistent parameters
echo "<H3>Persistent parameters (configuration)</H3>\r\n";
echo "<BLOCKQUOTE>\r\n";
echo "<P>\r\n";
$asNames = $roEnvironment->getPersistentParametersNames();
foreach( $asNames as $sName )
{
  $mParameter = $roEnvironment->getPersistentParameter( $sName );
  if( is_array( $mParameter ) ) foreach( $mParameter as $sKey => $sValue ) echo $sName.'['.$sKey.']: '.$sValue."<BR/>\r\n";
  else echo $sName.': '.$mParameter."<BR/>\r\n";
}
echo "</P>\r\n";
echo "</BLOCKQUOTE>\r\n";

// ... volatile parameters
echo "<H3>Volatile parameters (settings)</H3>\r\n";
echo "<BLOCKQUOTE>\r\n";
echo "<P>\r\n";
$asNames = $roEnvironment->getVolatileParametersNames();
foreach( $asNames as $sName )
{
  $mParameter = $roEnvironment->getVolatileParameter( $sName );
  if( is_array( $mParameter ) ) foreach( $mParameter as $sKey => $sValue ) echo $sName.'['.$sKey.']: '.$sValue."<BR/>\r\n";
  else echo $sName.': '.$mParameter."<BR/>\r\n";
}
echo "</P>\r\n";
echo "</BLOCKQUOTE>\r\n";

// ... user parameters
echo "<H3>User parameters (preferences)</H3>\r\n";
echo "<BLOCKQUOTE>\r\n";
echo "<P>\r\n";
$asNames = $roEnvironment->getUserParametersNames();
foreach( $asNames as $sName )
{
  $mParameter = $roEnvironment->getUserParameter( $sName );
  if( is_array( $mParameter ) ) foreach( $mParameter as $sKey => $sValue ) echo $sName.'['.$sKey.']: '.$sValue."<BR/>\r\n";
  else echo $sName.': '.$mParameter."<BR/>\r\n";
}
echo "</P>\r\n";
echo "</BLOCKQUOTE>\r\n";

// Statistics
echo "<H2>Statistics</H2>\r\n";
echo "<BLOCKQUOTE>\r\n";
echo "<P>\r\n";
echo 'Definitions load attempts: '.PHP_APE_Resources::$iIOStatDefinitionsAttempted."<BR/>\r\n";
echo 'Definitions load successes: '.PHP_APE_Resources::$iIOStatDefinitionsLoaded."<BR/>\r\n";
echo 'Properties cache attempts: '.PHP_APE_Properties::$iIOStatPropertiesCached."<BR/>\r\n";
echo 'Properties load attempts: '.PHP_APE_Properties::$iIOStatPropertiesAttempted."<BR/>\r\n";
echo 'Properties load successes: '.PHP_APE_Properties::$iIOStatPropertiesLoaded."<BR/>\r\n";
if( function_exists( 'memory_get_usage' ) ) 
{
  echo 'Peak memory usage: '.memory_get_peak_usage()."<BR/>\r\n";
  echo 'Current memory usage: '.memory_get_usage()."<BR/>\r\n";
}
echo "</P>\r\n";
echo "</BLOCKQUOTE>\r\n";
