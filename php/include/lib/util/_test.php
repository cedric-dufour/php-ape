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

// ... cryptography
echo "<H3>Cryptography</H3>\r\n";
echo "<BLOCKQUOTE>\r\n";
echo "<P>\r\n";
$sUnencrypted = 'Hello World !';
echo 'Unencrypted data: "'.$sUnencrypted."\"<BR/>\r\n";
$sHashed = PHP_APE_Util_Cryptography::hash( $sUnencrypted, MHASH_MD5 );
echo 'Hashed data (MD5): "'.$sHashed.'"; Expected: "'.md5($sUnencrypted)."\"<BR/>\r\n";
$sHashed = PHP_APE_Util_Cryptography::hash( $sUnencrypted );
echo 'Hashed data (default algorithm): "'.$sHashed."\"<BR/>\r\n";
$sSecretKey = 'Secret Key';
echo 'Secret Key: "'.$sSecretKey."\"<BR/>\r\n";
$sHashed = PHP_APE_Util_Cryptography::hash( $sUnencrypted, null, true, $sSecretKey );
echo 'Hashed data (using Secret Key): "'.$sHashed."\"<BR/>\r\n";
$sEncrypted = PHP_APE_Util_Cryptography::encrypt( $sUnencrypted, $sSecretKey );
echo 'Encrypted data: "'.htmlentities($sEncrypted)."\"<BR/>\r\n";
$sDecrypted = PHP_APE_Util_Cryptography::decrypt( $sEncrypted, $sSecretKey );
echo 'Decrypted data: "'.$sDecrypted."\"<BR/>\r\n";
echo "</P>\r\n";
echo "</BLOCKQUOTE>\r\n";

// ... URL
echo "<H3>URL</H3>\r\n";
echo "<BLOCKQUOTE>\r\n";
echo "<P>\r\n";
echo "Is e-mail address: 'user@domain.tld'=".PHP_APE_Type_Boolean::formatValue( PHP_APE_Util_URL::isEmail('user@domain.tld'),'#NULL#')."; 'user@domain_tld'=".PHP_APE_Type_Boolean::formatValue( PHP_APE_Util_URL::isEmail('user@domain_tld'),'#NULL#')."<BR/>\r\n";
echo "Is HTTP URL: 'http://server.domain.tld'=".PHP_APE_Type_Boolean::formatValue( PHP_APE_Util_URL::isHTTP('http://server.domain.tld'),'#NULL#')."; 'server.domain.tld'=".PHP_APE_Type_Boolean::formatValue( PHP_APE_Util_URL::isHTTP('server.domain.tld'),'#NULL#')."<BR/>\r\n";
echo "Is FTP URL: 'ftp://server.domain.tld'=".PHP_APE_Type_Boolean::formatValue( PHP_APE_Util_URL::isFTP('ftp://server.domain.tld'),'#NULL#')."; 'server.domain.tld'=".PHP_APE_Type_Boolean::formatValue( PHP_APE_Util_URL::isFTP('server.domain.tld'),'#NULL#')."<BR/>\r\n";
echo "Is 'server.domain.tld' Domain: 'http://server.domain.tld'=".PHP_APE_Type_Boolean::formatValue( PHP_APE_Util_URL::isDomain('http://server.domain.tld','server.domain.tld'),'#NULL#')."; 'http://www.domain.tld'=".PHP_APE_Type_Boolean::formatValue( PHP_APE_Util_URL::isDomain('http://www.domain.tld','server.domain.tld'),'#NULL#')."<BR/>\r\n";
echo "Add 'variable=Hello World !' to 'http://server.domain.tld' URL: ".PHP_APE_Util_URL::addVariable('http://server.domain.tld',array('variable'=>'Hello World !'))."<BR/>\r\n";
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
