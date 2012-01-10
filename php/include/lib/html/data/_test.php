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
 * @package PHP_APE_HTML
 * @subpackage Tests
 */

/** Load PHP-APE */
require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
require_once( PHP_APE_ROOT.'/lib/html/load.php' );
require_once( PHP_APE_ROOT.'/lib/html/data/load.php' );
require_once( PHP_APE_ROOT.'/lib/util/file/load.php' );
require_once( PHP_APE_ROOT.'/lib/util/image/load.php' );
require_once( PHP_APE_ROOT.'/lib/database/load.php' );
require_once( PHP_APE_ROOT.'/lib/database/sample/load.php' );

// Controller (browser)
$oBrowser = new PHP_APE_HTML_Data_Browser( 'PHP_APE_HTML_Data_Test', dirname( $_SERVER['PHP_SELF'] ) );

// Classes
class PHP_APE_HTML_Data_Test_File_list
extends PHP_APE_Util_File_ResultSet
implements PHP_APE_Data_isListAbleResultSet, PHP_APE_Data_isDetailAbleResultSet, PHP_APE_Data_isDetailAble, PHP_APE_Data_isListAble
{
  public function __construct() { parent::__construct( 'Files', '/homes/cdufour/public/local/devel' ); }
  public function isDetailAuthorized() { return true; }
  public function getDetailView() { return $this; }
  public function isListAuthorized() { return true; }
  public function getListView() { return $this; }
}

// Classes
class PHP_APE_HTML_Data_Test_Image_list
extends PHP_APE_Util_Image_ResultSet
implements PHP_APE_Data_isListAbleResultSet, PHP_APE_Data_isDetailAbleResultSet, PHP_APE_Data_isDetailAble, PHP_APE_Data_isListAble
{
  public function __construct() { parent::__construct( 'Images', '/homes/cdufour/public/local/devel' ); }
  public function isDetailAuthorized() { return true; }
  public function getDetailView() { return $this; }
  public function isListAuthorized() { return true; }
  public function getListView() { return $this; }
}

// Destination
$bPopup = $oBrowser->isPopup();

// PHP-APE tests
if( !$bPopup )
{
  echo "<H1>APE tests</H1>\r\n";
  echo "<P><B>SYNOPSIS:</B> This script tests the definition and behaviour of the objects included in this package,\r\n";
  echo "which should allow developers and users to verify the functionality of its resources,\r\n";
  echo "and hopefully also gain a better understanding on how to implement or use them.</P>\r\n";
}

// Tests
echo PHP_APE_HTML_SmartTags::htmlCSS();
echo '<DIV CLASS="APE">'."\r\n";

// View
if( !$bPopup )
{
  // ... Header
  echo $oBrowser->htmlHeader();
  echo PHP_APE_HTML_SmartTags::htmlSeparator();
}

// Title
echo $oBrowser->htmlTitle();

// Data

// ... choice
if( !$bPopup )
{
  $sClassesNames = array( 'PHP_APE_HTML_Data_Test_File_list',
                          'PHP_APE_HTML_Data_Test_Image_list',
                          'PHP_APE_Database_Sample_Supplier_list',
                          'PHP_APE_Database_Sample_Product_list',
                          'PHP_APE_Database_Sample_Customer_list',
                          'PHP_APE_Database_Sample_Customer_listSmarty',
                          'PHP_APE_Database_Sample_Order_list' );
  echo '<DIV CLASS="do-not-print">';
  echo PHP_APE_HTML_SmartTags::htmlSpacer();
  echo PHP_APE_HTML_SmartTags::htmlAlignOpen();
  echo '<H1>View</H1>';
  echo PHP_APE_HTML_SmartTags::htmlAlignAdd();
  echo '<SELECT ONCHANGE="javascript:document.location=this.value;">';
  echo '<OPTION VALUE=""> - Please select view to display - </OPTION>';
  foreach( $sClassesNames as $sClassName ) echo '<OPTION VALUE="'.$oBrowser->makeRequestURL( null, $sClassName ).'">'.$sClassName.'</OPTION>';
  echo '</SELECT ONCHANGE="">';
  echo PHP_APE_HTML_SmartTags::htmlAlignClose();
  echo PHP_APE_HTML_SmartTags::htmlSpacer();
  echo '</DIV>'."\r\n";
}

// ... output
if( $oBrowser->hasRequestData() )
{
  try
  {
    echo $oBrowser->htmlData( PHP_APE_Data_isQueryAbleResultSet::Query_Full, false, true, true );
  }
  catch( PHP_APE_HTML_Exception $e )
  {
    PHP_APE_Logger::log( $e->getContext(), $e->getMessage(), E_USER_WARNING );
  }
}

if( !$bPopup )
{
  // ... Footer
  echo PHP_APE_HTML_SmartTags::htmlSeparator();
  echo $oBrowser->htmlFooter();

  // Statistics
  echo PHP_APE_HTML_SmartTags::htmlSeparator();
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
}

// End
echo '</DIV>'."\r\n";
