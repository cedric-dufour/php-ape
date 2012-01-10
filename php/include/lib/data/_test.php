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
 * @package PHP_APE_Data
 * @subpackage Tests
 */

/** Load PHP-APE */
echo "<H1>APE tests</H1>\r\n";
echo "<P><B>SYNOPSIS:</B> This script tests the definition and behaviour of the objects included in this package,\r\n";
echo "which should allow developers and users to verify the functionality of its resources,\r\n";
echo "and hopefully also gain a better understanding on how to implement or use them.</P>\r\n";
require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
require_once( PHP_APE_ROOT.'/lib/data/load.php' );

// Data objects
echo "<H2>Data Objects</H2>\r\n";

// ... constant
echo "<H3>Constant</H3>\r\n";
echo "<BLOCKQUOTE>\r\n";
echo "<P>\r\n";
$oObject = new PHP_APE_Data_Constant( 'myConstant', new PHP_APE_Type_String( 'Hello World !' ), 'Named constant', 'Described constant' );
echo 'ID: "'.$oObject->getID()."\"<BR/>\r\n";
echo 'Name: "'.$oObject->getName()."\"<BR/>\r\n";
echo 'Description: "'.$oObject->getDescription()."\"<BR/>\r\n";
echo 'Content: "'.$oObject."\"<BR/>\r\n";
$oObject->getContent()->setValue( 'Good bye, cruel world...' );
echo '... retrieved (get) and modified (set): "'.$oObject."\"<BR/>\r\n";
echo "<BLOCKQUOTE><PRE STYLE=\"FONT:normal 11px courier\">\r\n";
echo var_export( $oObject, true )."\r\n";
echo "</PRE></BLOCKQUOTE><BR/>\r\n";
echo "</P>\r\n";
echo "</BLOCKQUOTE>\r\n";

// ... variable
echo "<H3>Variable</H3>\r\n";
echo "<BLOCKQUOTE>\r\n";
echo "<P>\r\n";
$oObject = new PHP_APE_Data_Variable( 'myVariable', new PHP_APE_Type_String( 'Hello World !' ), 'Named variable', 'Described variable' );
echo 'ID: "'.$oObject->getID()."\"<BR/>\r\n";
echo 'Name: "'.$oObject->getName()."\"<BR/>\r\n";
echo 'Description: "'.$oObject->getDescription()."\"<BR/>\r\n";
echo 'Content: "'.$oObject."\"<BR/>\r\n";
$oObject->getContent()->setValue( 'Good bye, cruel world...' );
echo '... retrieved (get) and modified (set): "'.$oObject."\"<BR/>\r\n";
$oObject->useContent()->setValue( 'Good bye, cruel world...' );
echo '... used (use) and modified (set): "'.$oObject."\"<BR/>\r\n";
echo "<BLOCKQUOTE><PRE STYLE=\"FONT:normal 11px courier\">\r\n";
echo var_export( $oObject, true )."\r\n";
echo "</PRE></BLOCKQUOTE><BR/>\r\n";
echo "</P>\r\n";
echo "</BLOCKQUOTE>\r\n";

// ... associative set
echo "<H3>Associative Set</H3>\r\n";
echo "<BLOCKQUOTE>\r\n";
echo "<P>\r\n";
$aoData = array ( new PHP_APE_Data_Constant( 'C1', new PHP_APE_Type_String( '(Constant) Hello World !' ) ), new PHP_APE_Data_Variable( 'V2', new PHP_APE_Type_String( '(Variable) Hello World !' ) ) );
$oObject = new PHP_APE_Data_AssociativeSet( 'myAssociativeSet', $aoData, 'Named associative set', 'Described associative set' );
echo 'ID: "'.$oObject->getID()."\"<BR/>\r\n";
echo 'Name: "'.$oObject->getName()."\"<BR/>\r\n";
echo 'Description: "'.$oObject->getDescription()."\"<BR/>\r\n";
echo 'Content: "'.$oObject."\"<BR/>\r\n";
$oObject->getElement('V2')->useContent()->setValue( 'Good bye, cruel world...' );
echo '... retrieved (get) and modified (set): "'.$oObject."\"<BR/>\r\n";
$oObject->useElement('V2')->useContent()->setValue( 'Good bye, cruel world...' );
echo '... used (use) and modified (set): "'.$oObject."\"<BR/>\r\n";
echo "<BLOCKQUOTE><PRE STYLE=\"FONT:normal 11px courier\">\r\n";
echo var_export( $oObject, true )."\r\n";
echo "</PRE></BLOCKQUOTE><BR/>\r\n";
echo "</P>\r\n";
echo "</BLOCKQUOTE>\r\n";

// ... ordered set
echo "<H3>Ordered Set</H3>\r\n";
echo "<BLOCKQUOTE>\r\n";
echo "<P>\r\n";
$aoData = array ( new PHP_APE_Data_Constant( 'C1', new PHP_APE_Type_String( '(Constant) Hello World !' ) ), new PHP_APE_Data_Variable( 'V2', new PHP_APE_Type_String( '(Variable) Hello World !' ) ) );
$oObject = new PHP_APE_Data_OrderedSet( 'myOrderedSet', $aoData, 'Named ordered set', 'Described ordered set' );
echo 'ID: "'.$oObject->getID()."\"<BR/>\r\n";
echo 'Name: "'.$oObject->getName()."\"<BR/>\r\n";
echo 'Description: "'.$oObject->getDescription()."\"<BR/>\r\n";
echo 'Content: "'.$oObject."\"<BR/>\r\n";
$oObject->getElement(1)->useContent()->setValue( 'Good bye, cruel world...' );
echo '... retrieved (get) and modified (set): "'.$oObject."\"<BR/>\r\n";
$oObject->useElement(1)->useContent()->setValue( 'Good bye, cruel world...' );
echo '... used (use) and modified (set): "'.$oObject."\"<BR/>\r\n";
echo "<BLOCKQUOTE><PRE STYLE=\"FONT:normal 11px courier\">\r\n";
echo var_export( $oObject, true )."\r\n";
echo "</PRE></BLOCKQUOTE><BR/>\r\n";
echo "</P>\r\n";
echo "</BLOCKQUOTE>\r\n";

// ... order
echo "<H3>Data Order</H3>\r\n";
echo "<BLOCKQUOTE>\r\n";
echo "<P>\r\n";
$sProperties="{myField1=-1;myField2=+1'}";
$oObject = new PHP_APE_Data_Order( 'myOrder', null, 'Named data order', 'Described data order' );
$oObject->fromProperties( $sProperties );
echo 'ID: "'.$oObject->getID()."\"<BR/>\r\n";
echo 'Name: "'.$oObject->getName()."\"<BR/>\r\n";
echo 'Description: "'.$oObject->getDescription()."\"<BR/>\r\n";
echo 'Content: "'.$oObject."\"<BR/>\r\n";
echo '... from: "'.$sProperties."\"<BR/>\r\n";
echo "<BLOCKQUOTE><PRE STYLE=\"FONT:normal 11px courier\">\r\n";
echo var_export( $oObject, true )."\r\n";
echo "</PRE></BLOCKQUOTE><BR/>\r\n";
echo "</P>\r\n";
echo "</BLOCKQUOTE>\r\n";

// ... filter
echo "<H3>Data Filter</H3>\r\n";
echo "<BLOCKQUOTE>\r\n";
echo "<P>\r\n";
$sProperties="{myField1='~smith | ( ~john !~johnny )';myField2='<=5 | =10 | =15 | >20'}";
$oObject = new PHP_APE_Data_Filter( 'myFilter', null, 'Named data filter', 'Described data filter' );
$oObject->fromProperties( $sProperties );
echo 'ID: "'.$oObject->getID()."\"<BR/>\r\n";
echo 'Name: "'.$oObject->getName()."\"<BR/>\r\n";
echo 'Description: "'.$oObject->getDescription()."\"<BR/>\r\n";
echo 'Content: "'.$oObject."\"<BR/>\r\n";
echo '... from: "'.$sProperties."\"<BR/>\r\n";
echo "<BLOCKQUOTE><PRE STYLE=\"FONT:normal 11px courier\">\r\n";
echo var_export( $oObject, true )."\r\n";
echo "</PRE></BLOCKQUOTE><BR/>\r\n";
echo "</P>\r\n";
echo "</BLOCKQUOTE>\r\n";

// ... array-based result set
echo "<H3>Array-based Result Set</H3>\r\n";
echo "<BLOCKQUOTE>\r\n";
echo "<P>\r\n";

/** Define 'myResultSet'
 * @package PHP_APE_Data
 * @subpackage Classes
 * @ignore */
class myResultSet extends PHP_APE_Data_ArrayResultSet
{
  function __construct( $mID )
  {
    // Create result set
    parent::__construct( $mID,                   // Result set identifier (ID)
                         null,                   // Result set underlying array
                         'Named result set',     // Result set (human-friendly) name
                         'Described result set'  // Result set (human-friendly) description
                         );

    // Add 'myField1'
    $this->setElement( new PHP_APE_Data_Field( 'myField1',                       // Field identifier (ID)
                                               new PHP_APE_Type_String(),            // Associated data object
                                               PHP_APE_Data_hasMeta::Type_Data |     // Field meta code
                                               PHP_APE_Data_hasMeta::Feature_OrderAble,
                                               'Field 1'    ,                    // Field (human-friendly) name
                                               'My first field' )                // Field (human-friendly) description
                       );

    // Add 'myField2'
    $this->setElement( new PHP_APE_Data_Field( 'myField2',                       // Field identifier (ID)
                                               new PHP_APE_Type_Integer(),           // Associated data object
                                               PHP_APE_Data_hasMeta::Type_Data |     // Field meta code
                                               PHP_APE_Data_hasMeta::Feature_OrderAble,
                                               'Field 2'    ,                    // Field (human-friendly) name
                                               'My second field' )               // Field (human-friendly) description
                       );

  }
}

// ... instantiate 'myResultSet'
$myResultSet = new myResultSet( 'myResultSet' );

// ... link data
$myResultSet->setArray( array( 'myField1' => array( 'Entry 1', 'Entry 2', 'Entry 3', 'Entry 4', 'Entry 5' ),
                               'myField2' => array( 1, 2, 3, 4, 5 ),
                               'myField3' => array( 'Optional 1' ) )
                        );

// ... create and add a data order
$myOrder = new PHP_APE_Data_Order();
// ... Field 1 ascending
// ... Field 2 descending
$myOrder->fromProperties( "{myField1=+1;myField2=-1}" );
$myResultSet->setOrder( $myOrder );

// ... query
$myResultSet->queryEntries();

// ... output
echo 'ID: "'.$myResultSet->getID()."\"<BR/>\r\n";
echo 'Name: "'.$myResultSet->getName()."\"<BR/>\r\n";
echo 'Description: "'.$myResultSet->getDescription()."\"<BR/>\r\n";
echo 'Content (unqueried): "'.$myResultSet."\"<BR/>\r\n";
echo "Content (queried):<BR/>\r\n";
echo "<BLOCKQUOTE><PRE STYLE=\"FONT:normal 11px courier\">\r\n";
while( $myResultSet->nextEntry() )
  echo $myResultSet."\"<BR/>\r\n";
echo "</PRE></BLOCKQUOTE><BR/>\r\n";
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
