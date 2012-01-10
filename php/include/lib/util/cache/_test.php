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
 * @package PHP_APE_Util
 * @subpackage Tests
 */

/** Load PHP-APE */
echo "<H1>APE tests</H1>\r\n";
echo "<P><B>SYNOPSIS:</B> This script tests the definition and behaviour of the objects included in this package,\r\n";
echo "which should allow developers and users to verify the functionality of its resources,\r\n";
echo "and hopefully also gain a better understanding on how to implement or use them.</P>\r\n";
require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
require_once( PHP_APE_ROOT.'/lib/util/load.php' );
require_once( PHP_APE_ROOT.'/lib/util/cache/load.php' );

// Utilities
echo "<H2>Utilities</H2>\r\n";

// ... cache
echo "<H3>Cache (memory-based)</H3>\r\n";
echo "<BLOCKQUOTE>\r\n";
echo "<P><B>Settings:</B><BR/>\r\n";
$iSize = 3;
echo 'Size: '.$iSize."<BR/>\r\n";
$iLifetime = 2;
echo 'Lifetime [seconds]: '.$iLifetime."<BR/>\r\n";
$fAccessWeight = 1;
echo 'Access Weight: '.$fAccessWeight;
echo "</P>\r\n";
$oCache = new PHP_APE_Util_Cache_Memory( $iSize, $iLifetime, $fAccessWeight );
echo "<P><B>t=0</B><BR/>\r\n";
echo "Set: A=1 (accessing 2 times) B=2 C=3 D=4<BR/>\r\n";
$oCache->saveData( 'A', 1 ); $oCache->getData( 'A' ); $oCache->getData( 'A' );
$oCache->saveData( 'B', 2 );
$oCache->saveData( 'C', 3 );
$oCache->saveData( 'D', 4 );
echo 'Get:';
echo ' A>'.( is_null( $mValue=$oCache->getData( 'A' ) ) ? '_' : $mValue );
echo ' B>'.( is_null( $mValue=$oCache->getData( 'B' ) ) ? '_' : $mValue );
echo ' C>'.( is_null( $mValue=$oCache->getData( 'C' ) ) ? '_' : $mValue );
echo ' D>'.( is_null( $mValue=$oCache->getData( 'D' ) ) ? '_' : $mValue );
echo "</P>\r\n";
sleep( 1 );
echo "<P><B>t=1</B><BR/>\r\n";
echo "Set: E=5<BR/>\r\n";
$oCache->saveData( 'E', 5 );
echo 'Get:';
echo ' A>'.( is_null( $mValue=$oCache->getData( 'A' ) ) ? '_' : $mValue );
echo ' B>'.( is_null( $mValue=$oCache->getData( 'B' ) ) ? '_' : $mValue );
echo ' C>'.( is_null( $mValue=$oCache->getData( 'C' ) ) ? '_' : $mValue );
echo ' D>'.( is_null( $mValue=$oCache->getData( 'D' ) ) ? '_' : $mValue );
echo ' E>'.( is_null( $mValue=$oCache->getData( 'E' ) ) ? '_' : $mValue );
echo "</P>\r\n";
sleep( 2 );
echo "<P><B>t=3</B><BR/>\r\n";
echo ' A>'.( is_null( $mValue=$oCache->getData( 'A' ) ) ? '_' : $mValue );
echo ' B>'.( is_null( $mValue=$oCache->getData( 'B' ) ) ? '_' : $mValue );
echo ' C>'.( is_null( $mValue=$oCache->getData( 'C' ) ) ? '_' : $mValue );
echo ' D>'.( is_null( $mValue=$oCache->getData( 'D' ) ) ? '_' : $mValue );
echo ' E>'.( is_null( $mValue=$oCache->getData( 'E' ) ) ? '_' : $mValue );
echo "</P>\r\n";
echo "</BLOCKQUOTE>\r\n";

// ... cache
echo "<H3>Cache (file-based)</H3>\r\n";
echo "<BLOCKQUOTE>\r\n";
echo "<P><B>Settings:</B><BR/>\r\n";
$sPath = '/tmp/PHP_APE_Test';
echo 'Path: '.$sPath."<BR/>\r\n";
$iSize = 3;
echo 'Size: '.$iSize."<BR/>\r\n";
$iLifetime = 2;
echo 'Lifetime [seconds]: '.$iLifetime."<BR/>\r\n";
$fAccessWeight = 1;
echo 'Access Weight: '.$fAccessWeight;
echo "</P>\r\n";
$oCache = new PHP_APE_Util_Cache_File( $sPath, $iSize, $iLifetime, $fAccessWeight );
echo "<P><B>t=0</B><BR/>\r\n";
echo "Set: A=1 (accessing 2 times) B=2 C=3 D=4<BR/>\r\n";
$oCache->saveData( 'A', 1 ); $oCache->getData( 'A' ); $oCache->getData( 'A' );
$oCache->saveData( 'B', 2 );
$oCache->saveData( 'C', 3 );
$oCache->saveData( 'D', 4 );
echo 'Get:';
echo ' A>'.( is_null( $mValue=$oCache->getData( 'A' ) ) ? '_' : $mValue );
echo ' B>'.( is_null( $mValue=$oCache->getData( 'B' ) ) ? '_' : $mValue );
echo ' C>'.( is_null( $mValue=$oCache->getData( 'C' ) ) ? '_' : $mValue );
echo ' D>'.( is_null( $mValue=$oCache->getData( 'D' ) ) ? '_' : $mValue );
echo "</P>\r\n";
sleep( 1 );
echo "<P><B>t=1</B><BR/>\r\n";
echo "Set: E=5<BR/>\r\n";
$oCache->saveData( 'E', 5 );
echo 'Get:';
echo ' A>'.( is_null( $mValue=$oCache->getData( 'A' ) ) ? '_' : $mValue );
echo ' B>'.( is_null( $mValue=$oCache->getData( 'B' ) ) ? '_' : $mValue );
echo ' C>'.( is_null( $mValue=$oCache->getData( 'C' ) ) ? '_' : $mValue );
echo ' D>'.( is_null( $mValue=$oCache->getData( 'D' ) ) ? '_' : $mValue );
echo ' E>'.( is_null( $mValue=$oCache->getData( 'E' ) ) ? '_' : $mValue );
echo "</P>\r\n";
sleep( 2 );
echo "<P><B>t=3</B><BR/>\r\n";
echo ' A>'.( is_null( $mValue=$oCache->getData( 'A' ) ) ? '_' : $mValue );
echo ' B>'.( is_null( $mValue=$oCache->getData( 'B' ) ) ? '_' : $mValue );
echo ' C>'.( is_null( $mValue=$oCache->getData( 'C' ) ) ? '_' : $mValue );
echo ' D>'.( is_null( $mValue=$oCache->getData( 'D' ) ) ? '_' : $mValue );
echo ' E>'.( is_null( $mValue=$oCache->getData( 'E' ) ) ? '_' : $mValue );
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
