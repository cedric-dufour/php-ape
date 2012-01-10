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
 * @package PHP_APE_Database
 * @subpackage Tests
 */

/** Load PHP-APE */
echo "<H1>APE tests</H1>\r\n";
echo "<P><B>SYNOPSIS:</B> This script tests the definition and behaviour of the objects included in this package,\r\n";
echo "which should allow developers and users to verify the functionality of its resources,\r\n";
echo "and hopefully also gain a better understanding on how to implement or use them.</P>\r\n";
require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
require_once( PHP_APE_ROOT.'/lib/database/load.php' );

// Workspace
echo "<H2>APE database workspace</H2>\r\n";
$roEnvironment = PHP_APE_Database_WorkSpace::useEnvironment();

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

// ... database
echo "<H3>Database</H3>\r\n";
echo "<BLOCKQUOTE>\r\n";
echo "<P>\r\n";
$asNames = array( '#INVALID#', 'northwind' );
foreach( $asNames as $sName )
{
  echo 'Database: '.$sName;
  try
  {
    $roDatabase =& $roEnvironment->useDatabase( $sName );
    echo "; Connect OK<BR/>\r\n";
  }
  catch( PHP_APE_Database_Exception $e )
    {
      echo '; ERROR: '.$e->getMessage()."<BR/>\r\n";
    }
}
echo "</P>\r\n";
echo "</BLOCKQUOTE>\r\n";

// ... view
echo "<H3>View</H3>\r\n";
echo "<BLOCKQUOTE>\r\n";
echo "<P>The following output is generated using the example from the documentation (see <SAMP>PHP_APE_Database_View</SAMP>):</P>\r\n";
echo "<P>\r\n";

/** Define 'myView'
 * @package PHP_APE_Database
 * @subpackage WorkSpace
 * @ignore */
class myView extends PHP_APE_Database_View
{
  function __construct( $mID )
  {
    // Link 'myView' to the 'mySchema.myView' database entity
    parent::__construct( $mID,             // View identifier (ID)
                         'myView',         // View expresion (view name)
                         'mySchema',       // View namespace (database schema)
                         'mysql',          // View preferred database
                         'Named view',     // View (human-friendly) name
                         'Described view'  // View (human-friendly) description
                         );

    // Add primary key
    $this->setElement( new PHP_APE_Database_Field( 'PK',                               // Field identifier (ID)
                                                   'PK',                               // Field expression (column name)
                                                   new PHP_APE_Type_Int4(),                // Associated data object
                                                   PHP_APE_Data_hasMeta::Type_PrimaryKey,  // Field meta code
                                                   'Primary Key',                      // Field (human-friendly) name
                                                   'My Primary Key' )                  // Field (human-friendly) description
                       );

    // Add 'myField1'
    $this->setElement( new PHP_APE_Database_Field( 'myField1',                         // Field identifier (ID)
                                                   'myField1',                         // Field expression (column name)
                                                   new PHP_APE_Type_String(),              // Associated data object
                                                   PHP_APE_Data_hasMeta::Type_Data |       // Field meta code
                                                   PHP_APE_Data_hasMeta::Feature_OrderAble |
                                                   PHP_APE_Data_hasMeta::Feature_SearchAble,
                                                   'Field 1'    ,                      // Field (human-friendly) name
                                                   'My first field' )                  // Field (human-friendly) description
                       );

    // Add 'myField2'
    $this->setElement( new PHP_APE_Database_Field( 'myField2',                         // Field identifier (ID)
                                                   'myField2',                         // Field expression (column name)
                                                   new PHP_APE_Type_Integer(),             // Associated data object
                                                   PHP_APE_Data_hasMeta::Type_Data |       // Field meta code
                                                   PHP_APE_Data_hasMeta::Feature_OrderAble |
                                                   PHP_APE_Data_hasMeta::Feature_SearchAble,
                                                   'Field 2'    ,                      // Field (human-friendly) name
                                                   'My second field' )                 // Field (human-friendly) description
                       );

  }
}

// Instantiate 'myView'
$myView = new myView( 'myView' );

// Create and add a data order filter
$myOrder = new PHP_APE_Data_Order();
// ... Field 1 ascending
// ... Field 2 descending
$myOrder->fromProperties( "{myField1=+1;myField2=-1}" );
$myView->setOrder( $myOrder );

// Create and add a data filter
$myFilter = new PHP_APE_Data_Filter();
// ... Field 1 like 'smith', or 'john' but not 'johnny'
// ... Field 2 smaller or equal than 5, equal to 10 or 15, or bigger than 20 
$myFilter->fromProperties( "{myField1='~smith | ( ~john !~johnny )';myField2='<=5 | 10 | 15 | >20'}" );
$myView->setFilter( $myFilter );

// Query 'myView'
$roDatabase =& $roEnvironment->useDatabase( $myView->getDatabaseID() );
$myView->useDatabase( $roDatabase );
echo 'ID: "'.$myView->getID()."\"<BR/>\r\n";
echo 'Name: "'.$myView->getName()."\"<BR/>\r\n";
echo 'Description: "'.$myView->getDescription()."\"<BR/>\r\n";
echo 'Fields: "'.$myView."\"<BR/>\r\n";
echo 'Order: "'.$myOrder."\"<BR/>\r\n";
echo 'Filter: "'.$myFilter."\"<BR/>\r\n";
echo "<PRE>\r\n";
echo "COUNT:<BR/>\r\n";
echo " SQL: ".$myView->formatSQL2QueryCount()."<BR/>\r\n";
echo "DATA:<BR/>\r\n";
echo " SQL: ".$myView->formatSQL2QueryEntries()."<BR/>\r\n";
echo "</PRE><BR/>\r\n";

echo "</P>\r\n";
echo "</BLOCKQUOTE>\r\n";

// ... function
echo "<H3>Function</H3>\r\n";
echo "<BLOCKQUOTE>\r\n";
echo "<P>The following output is generated using the example from the documentation (see <SAMP>PHP_APE_Database_Function</SAMP>):</P>\r\n";
echo "<P>\r\n";

/** Define 'myFunction'
 * @package PHP_APE_Database
 * @subpackage WorkSpace
 * @ignore */
class myFunction extends PHP_APE_Database_Function
{
  function __construct( $mID )
  {
    // Link 'myFunction' to the 'mySchema.myFunction' database entity
    parent::__construct( $mID,                   // Function identifier (ID)
                         new PHP_APE_Type_String(),  // Function's result associated data object
                         'myFunction',           // Function expresion (function name)
                         'mySchema',             // Function namespace (database schema)
                         'mysql',                // Function preferred database
                         'Named function',       // Function (human-friendly) name
                         'Described function '   // Function (human-friendly) description
                         );

    // Prepare arguments
    $oArgumentSet = new PHP_APE_Data_ArgumentSet();

    // ... 'myArgument1'
    $oArgumentSet->setElement( new PHP_APE_Data_Argument( 'myArgument1',                // Argument identifier (ID)
                                                          new PHP_APE_Type_String(),        // Associated data object
                                                          PHP_APE_Data_hasMeta::Type_Data,  // Argument meta code
                                                          'Argument 1',                 // Argument (human-friendly) name
                                                          'My first argument' )         // Argument (human-friendly) description
                               );

    // Add arguments
    $this->setArguments( $oArgumentSet );
  }
}

// Instantiate 'myFunction'
$myFunction = new myFunction( 'myFunction' );

// Set 'myFunction' arguments
$myFunction->useArguments()->useElementByID( 'myArgument1' )->useContent()->setValue( 'myArgument1Value' );

// Execute 'myFunction'
$roDatabase =& $roEnvironment->useDatabase( $myView->getDatabaseID() );
$myFunction->useDatabase( $roDatabase );
echo 'ID: "'.$myFunction->getID()."\"<BR/>\r\n";
echo 'Name: "'.$myFunction->getName()."\"<BR/>\r\n";
echo 'Description: "'.$myFunction->getDescription()."\"<BR/>\r\n";
echo 'Arguments: "'.$myFunction->useArguments()."\"<BR/>\r\n";
echo 'Result: "'.$myFunction."\"<BR/>\r\n";
echo "<PRE>\r\n";
echo "EXECUTE:<BR/>\r\n";
echo " SQL: ".$myFunction->formatSQL2Execute()."<BR/>\r\n";
echo "</PRE><BR/>\r\n";

echo "</P>\r\n";
echo "</BLOCKQUOTE>\r\n";

// ... database
echo "<H3>Database</H3>\r\n";
echo "<BLOCKQUOTE>\r\n";
echo "<P>\r\n";
echo "Disconnecting all databases<BR/>\r\n";
$roEnvironment->disconnectDatabases();
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
