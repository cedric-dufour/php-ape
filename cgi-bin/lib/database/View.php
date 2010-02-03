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
 * @package PHP_APE_Database
 * @subpackage Classes
 */

/** SQL view class
 *
 * <P><B>SYNOPSIS:</B> This class allows to represent (map) an existing database table/view/procedure,
 * and provides the necessary methods to query the database and retrieve the corresponding data,
 * thus providing a powerful abstraction layer on top of the SQL space.</P>
 *
 * <P><B>EXAMPLE: myView.sql</B></P>
 * <CODE>
 * -- PostgreSQL view
 * CREATE OR REPLACE VIEW mySchema.myView AS
 * SELECT
 *  PK, -- Primary key
 *  myField1,
 *  myField2
 * FROM
 *  mySchema.myTable
 * ;
 * </CODE>
 *
 * <P><B>EXAMPLE: myView.php</B></P>
 * <CODE>
 * <?php
 * // Define 'myView'
 * class myView extends PHP_APE_Database_View
 * {
 *   function __construct( $mID )
 *   {
 *     // Link 'myView' to the 'mySchema.myView' database entity
 *     parent::__construct( $mID, // ................................................... View identifier (ID)
 *                          'myView', // ............................................... View expresion (view name)
 *                          'mySchema', // ............................................. View namespace (database schema)
 *                          'mysql', // ................................................ View preferred database
 *                          'Named view', // ........................................... View (human-friendly) name
 *                          'Described view' // ........................................ View (human-friendly) description
 *                          );
 *
 *     // Add primary key
 *     $this->setElement( new PHP_APE_Database_Field( 'PK', // ............................. Field identifier (ID)
 *                                                    'PK', // ............................. Field expression (column name)
 *                                                    new PHP_APE_Type_Int4(), // .............. Associated data object
 *                                                    PHP_APE_Data_Field::Type_PrimaryKey, // .. Field meta code
 *                                                    'Primary Key', // .................... Field (human-friendly) name
 *                                                    'My Primary Key' ) // ................ Field (human-friendly) description
 *                        );
 *
 *     // Add 'myField1'
 *     $this->setElement( new PHP_APE_Database_Field( 'myField1', // ....................... Field identifier (ID)
 *                                                    'myField1', // ....................... Field expression (column name)
 *                                                    new PHP_APE_Type_String(), // ............ Associated data object
 *                                                    PHP_APE_Data_Field::Type_Data | // ....... Field meta code
 *                                                    PHP_APE_Data_Field::Feature_OrderAble |
 *                                                    PHP_APE_Data_Field::Feature_SearchAble,
 *                                                    'Field 1', // ........................ Field (human-friendly) name
 *                                                    'My first field' ) // ................ Field (human-friendly) description
 *                        );
 *
 *     // Add 'myField2'
 *     $this->setElement( new PHP_APE_Database_Field( 'myField2', // ....................... Field identifier (ID)
 *                                                    'myField2', // ....................... Field expression (column name)
 *                                                    new PHP_APE_Type_Integer(), // ........... Associated data object
 *                                                    PHP_APE_Data_Field::Type_Data | // ....... Field meta code
 *                                                    PHP_APE_Data_Field::Feature_OrderAble |
 *                                                    PHP_APE_Data_Field::Feature_SearchAble,
 *                                                    'Field 2', // ........................ Field (human-friendly) name
 *                                                    'My second field' ) // ............... Field (human-friendly) description
 *                        );
 *
 *   }
 * }
 *
 *
 * // Instantiate 'myView'
 * $myView = new myView( 'myView' );
 *
 * // Create and add a data order filter
 * $myOrder = new PHP_APE_Data_Order();
 * // ... Field 1 ascending
 * // ... Field 2 descending
 * $myOrder->fromProperties( "{myField1=+1;myField2=-1}" );
 * $myView->setOrder( $myOrder );
 *
 * // Create and add a data filter
 * $myFilter = new PHP_APE_Data_Filter();
 * // ... Field 1 like 'smith', or 'john' but not 'johnny'
 * // ... Field 2 smaller or equal than 5, equal to 10 or 15, or bigger than 20 
 * $myFilter->fromProperties( "{myField1='~smith | ( ~john !~johnny )';myField2='<=5 | 10 | 15 | >20'}" );
 * $myView->setFilter( $myFilter );
 *
 * // Query 'myView'
 * $roDatabase =& $roEnvironment->useDatabase( $myView->getDatabaseID() );
 * $myView->attachDatabase( $roDatabase );
 * echo 'ID: "'.$myView->getID()."\"<BR/>\r\n";
 * echo 'Name: "'.$myView->getName()."\"<BR/>\r\n";
 * echo 'Description: "'.$myView->getDescription()."\"<BR/>\r\n";
 * echo 'Fields: "'.$myView."\"<BR/>\r\n";
 * echo 'Order: "'.$myOrder."\"<BR/>\r\n";
 * echo 'Filter: "'.$myFilter."\"<BR/>\r\n";
 * echo "<PRE>\r\n";
 * echo "COUNT:<BR/>\r\n";
 * echo " SQL: ".$myView->formatSQL2QueryCount()."<BR/>\r\n";
 * echo " OUTPUT: ".$myView->queryCount()."\r\n";
 * echo "DATA:<BR/>\r\n";
 * echo " SQL: ".$myView->formatSQL2QueryEntries()."<BR/>\r\n";
 * echo " OUTPUT:\r\n";
 * $myView->queryEntries();
 * while( $myView->nextEntry() )
 * {
 *   echo "   Row ".$myView->getEntryKey().":\r\n";
 *   foreach( $myView->getElementsKeys() as $mFieldKey ) {
 *     $myField = $myView->useElement( $mFieldKey );
 *     echo "    - ".$myField->getName().": ".$myField->getContent()->getValue()."\r\n";
 *   }
 * }
 * echo "</PRE><BR/>\r\n";
 * ?>
 * </CODE>
 *
 * <P><B>OUTPUT: myView.php</B></P>
 * <PRE>
 * ID: "myView"
 * Name: "Named view"
 * Description: "Described view"
 * Fields: "{PK='NULL';myField1='NULL';myField2='NULL'}"
 * Order: "{myField1='1';myField2='-1'}"
 * Filter: "{myField1='~smith |(~john !~johnny)';myField2='<=5 |10 |15 |>20'}"
 * COUNT:
 *  SQL: SELECT COUNT(myView.PK) FROM mySchema.myView AS myView WHERE (myView.myField1 LIKE CAST('smith%' AS char) OR (myView.myField1 LIKE CAST('john%' AS char) AND NOT(myView.myField1 LIKE CAST('johnny%' AS char)))) AND (myView.myField2<=CAST(5 AS int) OR myView.myField2=CAST(10 AS int) OR myView.myField2=CAST(15 AS int) OR myView.myField2>CAST(20 AS int))
 *  OUTPUT: &lt;n&gt;
 * DATA:
 *  SQL: SELECT myView.PK AS myView_PK, myView.myField1 AS myView_myField1, myView.myField2 AS myView_myField2 FROM mySchema.myView AS myView  WHERE (myView.myField1 LIKE CAST('smith%' AS char) OR (myView.myField1 LIKE CAST('john%' AS char) AND NOT(myView.myField1 LIKE CAST('johnny%' AS char)))) AND (myView.myField2<=CAST(5 AS int) OR myView.myField2=CAST(10 AS int) OR myView.myField2=CAST(15 AS int) OR myView.myField2>CAST(20 AS int)) ORDER BY  myView.myField1 ASC, myView.myField2 DESC LIMIT 25 OFFSET 0
 *  OUTPUT:
 *   Row 0:
 *    - Primary Key: &lt;value&gt;
 *    - Field 1: &lt;value&gt;
 *    - Field 2: &lt;value&gt;
 *   Row 1:
 *    [...]
 * </PRE>
 *
 * @package PHP_APE_Database
 * @subpackage Classes
 */
abstract class PHP_APE_Database_View
extends PHP_APE_Data_View
{

  /*
   * FIELDS
   ********************************************************************************/

  /** View's expression (table/view/procedure name or SQL expression)
   * @var string */
  private $sExpression;

  /** View's namespace (database schema)
   * @var string */
  private $sNamespace;

  /** View's preferred database identifier (ID)
   * @var string */
  private $mDatabaseID;

  /** Associated database
   * @var PEAR::MDB2
   */
  private $roMDB2;

  /** Associated cache
   * @var PHP_APE_Util_Cache_Any
   */
  private $roCache;

  /** Fetching resultset
   * @var MDB2_Result
   */
  private $roResultSet;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new view
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>.</P>
   *
   * @param mixed $mID View identifier (ID)
   * @param string $sExpression View expression (table/view/procedure name or SQL expression)
   * @param string $sNamespace View's namespace (database schema)
   * @param string $mDatabaseID View's preferred database identifier (ID)
   * @param string $sName View name (defaults to identifier if <SAMP>empty</SAMP>)
   * @param string $sDescription View description
   */
  public function __construct( $mID, $sExpression, $sNamespace = null, $mDatabaseID = null, $sName = null, $sDescription = null )
  {
    // Sanitize arguments
    $sExpression = PHP_APE_Type_String::parseValue( $sExpression, true );
    if( empty( $sExpression ) )
      throw new PHP_APE_Database_Exception( __METHOD__, 'Invalid (empty) expression' );
    $sNamespace = PHP_APE_Type_String::parseValue( $sNamespace, true );
    $mDatabaseID = PHP_APE_Type_Index::parseValue( $mDatabaseID, true );

    // Initialize member fields
    parent::__construct( $mID, $sName, $sDescription );
    $this->sExpression = $sExpression;
    $this->sNamespace = $sNamespace;
    $this->mDatabaseID = $mDatabaseID;
  }


  /*
   * METHODS: initialization
   ********************************************************************************/

  /** Resets the view
   *
   * <P><B>SYNOPSIS:</B> This method clears this view's data content, releases the underlying resultset resources,
   * and detaches any associated database, cache or data limit/offset/order/filter.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>.</P>
   */
  public function reset()
  {
    $this->resetQuery();
    $this->detachDatabase();
    $this->detachCache();
    parent::reset();
  }


  /*
   * METHODS: data
   ********************************************************************************/

  /** Returns this view's expression (table/view/procedure name or SQL expression)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getExpression() {
    return $this->sExpression;
  }

  /** Returns this view's namespace (database schema)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getNamespace() {
    return $this->sNamespace;
  }

  /** Returns this view's preferred database identifier (ID)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return mixed
   */
  final public function getDatabaseID() {
    return $this->mDatabaseID;
  }


  /*
   * METHODS: database
   ********************************************************************************/

  /** Associates the given (PEAR::MDB2) database to this view
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param MDB2_Driver_common $roMDB2 Database (<B>as reference</B>)
   */
  final public function attachDatabase( MDB2_Driver_common &$roMDB2 )
  {
    // Check status
    if( !is_null( $this->roResultSet ) )
      throw new PHP_APE_Database_Exception( __METHOD__, 'Database cannot be changed unless current resultset is closed' );

    // Attach database
    $this->roMDB2 =& $roMDB2;
  }

  /** Returns this view's currently associated database (<B>as reference</B>)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return MDB2_Driver_common
   */
  final public function &useDatabase()
  {
    return $this->roMDB2;
  }

  /** Detaches this view's currently associated database
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function detachDatabase()
  {
    // Check status
    if( !is_null( $this->roResultSet ) )
      throw new PHP_APE_Database_Exception( __METHOD__, 'Database cannot be changed unless current resultset is closed' );

    // Detach database
    unset( $this->roMDB2 ); // prevent nasty reference side-effects...
    $this->roMDB2 = null;
  }


  /*
   * METHODS: cache
   ********************************************************************************/

  /** Associates the given caching object to this view
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param PHP_APE_Util_Cache_Any $roCache Caching object
   */
  final public function attachCache( PHP_APE_Util_Cache_Any &$roCache )
  {
    $this->roCache =& $roCache;
  }

  /** Returns this view's currently associated cache (<B>as reference</B>)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return PHP_APE_Util_Cache_Any
   */
  final public function &useCache()
  {
    return $this->roCache;
  }

  /** Detaches this view's currently associated cache
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function detachCache()
  {
    // Detach cache
    unset( $this->roCache ); // prevent nasty reference side-effects...
    $this->roCache = null;
  }


  /*
   * METHODS: elements - OVERRIDE
   ********************************************************************************/

  /** Sets (adds) the given element (field) to this view
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param PHP_APE_Database_Field $oField New field
   */
  final public function setElement( PHP_APE_Data_Any $oField )
  {
    if( !($oField instanceof PHP_APE_Database_Field) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Invalid field object; Class: '.get_class( $oField ) );
    parent::setElement( $oField );
  }


  /*
   * METHODS: PHP_APE_Data_isBasicResultSet - IMPLEMENT
   ********************************************************************************/

  /** Positions this view on the next entry (row)
   *
   * <P><B>RETURNS:</B> <SAMP>true</SAMP> if the set has been successfully positioned on the next entry,
   * <SAMP>false</SAMP> otherwise (e.g. no more entries).</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return boolean
   */
  final public function nextEntry()
  {
    // Check status
    if( is_null( $this->roResultSet ) )
      throw new PHP_APE_Database_Exception( __METHOD__, 'No resultset' );

    // Reset element
    $this->resetElements();

    // Go to next entry
    $amRow = $this->roResultSet->fetchRow( MDB2_FETCHMODE_ASSOC );
    if( !is_array( $amRow ) ) return false;
    
    // Environment
    $roEnvironment =& PHP_APE_Database_WorkSpace::useEnvironment();

    // Retrieve SQL handling object
    $oSQL = $roEnvironment->getDatabaseSQL( $this->roMDB2 );

    // Retrieve fields IDs/keys
    $amKeys = $this->getElementsKeys();
    
    // Loop through fields
    foreach( $amKeys as $mKey )
    {
      // Set each field's value (if queried)
      $sColumnName = strtolower( $oSQL->formatFieldAs( $this, $mKey ) );
      if( !array_key_exists( $sColumnName, $amRow ) )
        continue; // field was not queried

      // ... retrieve field
      $roField =& $this->useElement( $mKey );

      // ... retrieve field's storage (template) object
      $oStoredContent = $roField->getContentAsStored();

      // ... set field's value
      if( !is_null( $oStoredContent ) )
      { // data IS stored using a different data object/type
        $oStoredContent->setValue( $amRow[ $sColumnName ] );
        $roField->useContent()->setValue( $oStoredContent ); // type conversion
      }
      else
        $roField->useContent()->setValue( $amRow[ $sColumnName ] );
    }

    // End
    return true;
  }


  /*
   * METHODS: PHP_APE_Data_isExtendedResultSet - IMPLEMENT
   ********************************************************************************/

  /** Returns this view's current entry (row) key
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return integer
   */
  final public function getEntryKey()
  {
    // Check status
    if( is_null( $this->roResultSet ) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'No resultset' );

    // Retrieve current row index
    return $this->roResultSet->rowCount();
  }

  /** Returns this view's current (queried) entries quantity (count)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return integer
   */
  final public function countEntries()
  {
    // Check status
    if( is_null( $this->roResultSet ) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Result set must be queried before fetching entry data' );

    // Retrieve row count
    if( $this->roMDB2->getOption('result_buffering') )
      return $this->roResultSet->numRows();
    else
      throw new PHP_APE_Data_Exception( __METHOD__, 'Feature disabled or not supported by database' );
  }

  /** Returns this view's total (queryable) entries quantity (count)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return integer
   */
  final public function countAllEntries()
  {
    return $this->queryCount();
  }

  /** Returns the entries (rows) keys (indexes) from this view
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return array|integer
   */
  final public function getEntriesKeys()
  {
    // Check status
    if( is_null( $this->roResultSet ) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Result set must be queried before fetching entry data' );

    // Retrieve row indexes
    return range( 0, $this->countEntries() );
  }

  /** Returns whether the given key (index) corresponds to an entry (row) in this view
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param mixed $mKey Entry key
   * @return boolean
   */
  final public function isEntry( $mKey )
  {
    // Check status
    if( is_null( $this->roResultSet ) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Result set must be queried before fetching entry data' );

    // Check row index
    $mKey = (integer)$mKey;
    if( $mKey < 0 ) return false;
    return ( $mKey < $this->countEntries() );
  }

  /** Positions this view at the given entry (row)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param mixed $mKey Entry key
   */
  final public function gotoEntry( $mKey )
  {
    // Check status
    if( is_null( $this->roResultSet ) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Result set must be queried before fetching entry data' );

    // Goto row
    $this->roResultSet->seek( (integer)$mKey );
  }


  /*
   * METHODS: PHP_APE_Data_isQueryAbleResultSet - IMPLEMENT
   ********************************************************************************/

  /** Fetches this view's entries (rows)
   *
   * <P><B>USAGE:</B> Once this method called, use the {@link PHP_APE_Data_isExtendedResultSet} interface methods to further manipulate this view's entries.</P> 
   * <P><B>NOTE:</B> This function will be optimized if cache is available (see {@link attachCache()}).</P> 
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>, <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Auth_AuthorizationException</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param integer $iQueryType Query type (see {@link PHP_APE_Data_isQueryAbleResultSet} constants)
   * @param array|mixed $amElementsKeys Elements keys to retrieve (all if <SAMP>null</SAMP>)
   * @param boolean $bDebug Show debugging information while performing the query
   */
  final public function queryEntries( $iQueryType = PHP_APE_Data_isQueryAbleResultSet::Query_All, $amElementsKeys = null, $bDebug = false )
  {

    // Check authorization
    if( ( $this instanceof PHP_APE_Data_hasAuthorization ) and !$this->hasAuthorization() )
      throw new PHP_APE_Auth_AuthorizationException( __METHOD__, 'Permission denied' );

    // Check if result set is being queried
    if( !is_null( $this->roResultSet ) )
      return;

    // Environment
    $roEnvironment =& PHP_APE_Database_WorkSpace::useEnvironment();

    // Check database
    if( is_null( $this->roMDB2 ) )
    {
      // Try to connect database
      if( !is_null( $this->mDatabaseID ) )
        $this->attachDatabase( $roEnvironment->useDatabase( $this->mDatabaseID ) );
      else
        throw new PHP_APE_Data_Exception( __METHOD__, 'No database' );
    }

    // Reset elements
    $this->resetElements();

    // Reset previous query
    $this->resetQuery();

    // Sanitize input
    $iQueryType = (integer)$iQueryType;
    if( is_array( $amElementsKeys ) ) $amElementsKeys = array_intersect( $this->getElementsKeys(), $amElementsKeys ); // make sure no undefined keys are specified
    if( !is_null( $amElementsKeys ) and count( $amElementsKeys ) <= 0 )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Invalid/missing (empty) elements keys' );

    // Verify cache usage
    $bUseCache = (boolean)( $iQueryType & PHP_APE_Data_isQueryAbleResultSet::Query_Cache );
    try { $mPKey = $this->getPrimaryKey(); }
    catch( PHP_APE_Database_Exception $e ) { $mPKey = null; }
    if( $bUseCache )
    {
      if(
         // check primary key
         is_null( $mPKey )
         // check cache object
         or is_null( $this->roCache )
         // check fetch type's limit directives (caching without those leads to performance issues, cf. cached 'WHERE' clause including too many primary keys)
         or !( $iQueryType & PHP_APE_Data_isQueryAbleResultSet::Query_Limit )
         )
        $bUseCache = false;
    }
    // ... [HACK] or if the 'LIMIT ... OFFSET ...' clause is not supported (MS SQL Server)
    if( !$bUseCache and !is_null( $mPKey ) and !is_null( $this->roCache ) )
    {
      $oSQL = $roEnvironment->getDatabaseSQL( $this->roMDB2 );
      if( ( $iQueryType & PHP_APE_Data_isQueryAbleResultSet::Query_Limit ) and is_null( $oSQL->formatClause( PHP_APE_Database_SQL_Any::Limit, 0 ) ) )
        $bUseCache = true;
      if( ( $iQueryType & PHP_APE_Data_isQueryAbleResultSet::Query_Offset ) and is_null( $oSQL->formatClause( PHP_APE_Database_SQL_Any::Offset, 0 ) ) )
        $bUseCache = true;
    }
    $iQueryType &= ~PHP_APE_Data_isQueryAbleResultSet::Query_Cache;
    if( $bUseCache ) $iQueryType |= PHP_APE_Data_isQueryAbleResultSet::Query_Cache;

    // Build SQL statement
    $sSQL = $this->formatSQL2QueryEntries( $iQueryType, $amElementsKeys );

    // Query database
    if( $bDebug ) echo nl2br( var_export( $sSQL, true ) );
    $roResultSet =& $this->roMDB2->query( $sSQL );
    if( PEAR::isError( $roResultSet ) )
    {
      if( PHP_APE_DEBUG )
        throw new PHP_APE_Database_Exception( __METHOD__, $roResultSet->getMessage().'; SQL: '.$sSQL );
      throw new PHP_APE_Data_Exception( __METHOD__, $roResultSet->getMessage() );
    }
    $this->roResultSet =& $roResultSet;

  }

  /** Returns whether this set is being queried
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return boolean
   */
  final public function isQueried()
  {
    return( !is_null( $this->roResultSet ) );
  }

  /** Reset (clears) this set's query status and entries (rows)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function resetQuery()
  {
    if( !is_null( $this->roResultSet ) ) $this->roResultSet->free();
    unset( $this->roResultSet );
    $this->roResultSet = null;
  }


  /*
   * METHODS: query
   ********************************************************************************/

  /** Returns this view's (field) primary key
   *
   * <P><B>USAGE:</B> The queried view MUST have one SINGLE primary key for this method to work.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return mixed
   */
  final public function getPrimaryKey()
  {
    $mPKey = $this->getElementsKeysPerMeta( PHP_APE_Data_Field::Type_PrimaryKey );
    if( !is_array( $mPKey ) or count( $mPKey ) != 1 )
      throw new PHP_APE_Database_Exception( __METHOD__, 'Missing or too many primary keys; Count: '.count( $mPKey ) );
    return $mPKey[0];
  }

  /** Returns this view's entries (rows) quantity (count)
   *
   * <P><B>NOTE:</B> This function will be optimized if cache is available (see {@link attachCache()}).</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Auth_AuthorizationException</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param integer $iQueryType Query type (see {@link PHP_APE_Data_isQueryAbleResultSet} constants)
   * @param boolean $bDebug Show debugging information while performing the query
   * @return integer
   */
  final public function queryCount( $iQueryType = PHP_APE_Data_isQueryAbleResultSet::Query_All, $bDebug = false )
  {

    // Check authorization
    if( ( $this instanceof PHP_APE_Data_hasAuthorization ) and !$this->hasAuthorization() )
      throw new PHP_APE_Auth_AuthorizationException( __METHOD__, 'Permission denied' );

    // Check status
    if( is_null( $this->roMDB2 ) )
      throw new PHP_APE_Database_Exception( __METHOD__, 'No database' );

    // Environment
    $roEnvironment =& PHP_APE_Database_WorkSpace::useEnvironment();

    // Check database
    if( is_null( $this->roMDB2 ) )
    {
      // Try to connect database
      if( !is_null( $this->mDatabaseID ) )
        $this->attachDatabase( $roEnvironment->useDatabase( $this->mDatabaseID ) );
      else
        throw new PHP_APE_Data_Exception( __METHOD__, 'No database' );
    }

    // Sanitize input
    $iQueryType = (integer)$iQueryType;

    // Check cache usage
    $bUseCache = (boolean)( $iQueryType & PHP_APE_Data_isQueryAbleResultSet::Query_Cache );
    try { $mPKey = $this->getPrimaryKey(); }
    catch( PHP_APE_Database_Exception $e ) { $mPKey = null; }
    if( $bUseCache )
    {
      if(
         // check primary key
         is_null( $mPKey )
         // check cache object
         or is_null( $this->roCache )
         // check fetch type's limit directives (caching without those leads to performance issues, cf. cached 'WHERE' clause including too many primary keys)
         or !( $iQueryType & PHP_APE_Data_isQueryAbleResultSet::Query_Limit )
         )
        $bUseCache = false;
    }
    $iQueryType &= ~PHP_APE_Data_isQueryAbleResultSet::Query_Cache;
    if( $bUseCache ) $iQueryType |= PHP_APE_Data_isQueryAbleResultSet::Query_Cache;

    // Retrieve entries count from database/cache
    $iCount = null;
    if( $bUseCache )
    { // DO use cache

      // Retrieve (cached) primary key data
      $amPKValues = $this->queryPrimaryKeys( $iQueryType );

      // Save count
      $iCount = count( $amPKValues );

    }
    else
    { // do NOT use cache

      // Build SQL statement
      $sSQL = $this->formatSQL2QueryCount( $iQueryType );

      // Query resultset
      if( $bDebug ) echo nl2br( var_export( $sSQL, true ) );
      $roResultSet =& $this->roMDB2->query( $sSQL );
      if( PEAR::isError( $roResultSet ) )
      {
        if( PHP_APE_DEBUG )
          throw new PHP_APE_Database_Exception( __METHOD__, $roResultSet->getMessage().'; SQL: '.$sSQL );
        throw new PHP_APE_Database_Exception( __METHOD__, $roResultSet->getMessage() );
      }
      if( !is_array( $amRow = $roResultSet->fetchRow( MDB2_FETCHMODE_ORDERED ) ) )
        throw new PHP_APE_Database_Exception( __METHOD__, 'Failed to retrieve query result' );

      // Save count
      $iCount = (integer)$amRow[0];

      // Free resultset
      $roResultSet->free();

    }

    // END
    return $iCount;
  }

  /** Returns this view's primary keys values
   *
   * <P><B>USAGE:</B> The queried view MUST have one SINGLE primary key for this method to work.</P>
   * <P><B>NOTE:</B> This function will be optimized if cache is available (see {@link attachCache()}).</P> 
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Auth_AuthorizationException</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param integer $iQueryType Query type (see {@link PHP_APE_Data_isQueryAbleResultSet} constants)
   * @param boolean $bDebug Show debugging information while performing the query
   * @return array|mixed
   */
  final public function queryPrimaryKeys( $iQueryType = PHP_APE_Data_isQueryAbleResultSet::Query_All, $bDebug = false )
  {

    // Check authorization
    if( ( $this instanceof PHP_APE_Data_hasAuthorization ) and !$this->hasAuthorization() )
      throw new PHP_APE_Auth_AuthorizationException( __METHOD__, 'Permission denied' );

    // Sanitize input
    $iQueryType = (integer)$iQueryType;

    // Environment
    $roEnvironment =& PHP_APE_Database_WorkSpace::useEnvironment();

    // Check database
    if( is_null( $this->roMDB2 ) )
    {
      // Try to connect database
      if( !is_null( $this->mDatabaseID ) )
        $this->attachDatabase( $roEnvironment->useDatabase( $this->mDatabaseID ) );
      else
        throw new PHP_APE_Data_Exception( __METHOD__, 'No database' );
    }

    // Verify cache usage
    $bUseCache = (boolean)( $iQueryType & PHP_APE_Data_isQueryAbleResultSet::Query_Cache );
    $bUseCache = $bUseCache && !is_null( $this->roCache );
    $iQueryType &= ~PHP_APE_Data_isQueryAbleResultSet::Query_Cache;
    if( $bUseCache ) $iQueryType |= PHP_APE_Data_isQueryAbleResultSet::Query_Cache;

    // Retrieve SQL statement
    $sSQL = $this->formatSQL2QueryPrimaryKeys( $iQueryType );

    // Query cache
    $amValues = null;
    if( $bUseCache )
    { // DO use cache

      // Build cache index; let's assume that the SHA1+MD5-hash of the SQL statement is good-enough
      $sCacheIndex = 'SQL-'.sha1( $sSQL ).'-'.md5( $sSQL ).'-PK';
      
      // Retrieve cache data
      $amValues = $this->roCache->getData( $sCacheIndex );

    }

    // Query database
    if( !is_array( $amValues ) )
    { // NO cached data available

      // Query resultset
      if( $bDebug ) echo nl2br( var_export( $sSQL, true ) );
      $roResultSet =& $this->roMDB2->query( $sSQL );
      if( PEAR::isError( $roResultSet ) )
      {
        if( PHP_APE_DEBUG )
          throw new PHP_APE_Database_Exception( __METHOD__, $roResultSet->getMessage().'; SQL: '.$sSQL );
        throw new PHP_APE_Database_Exception( __METHOD__, $roResultSet->getMessage() );
      }

      // Build cache data
      $amValues = array();
      while( $amRow = $roResultSet->fetchRow( MDB2_FETCHMODE_ORDERED ) )
        array_push( $amValues, $amRow[0] );

      // Free resultset
      $roResultSet->free();

      // Cache result
      if( $bUseCache ) $this->roCache->saveData( $sCacheIndex, $amValues, true );

    }

    // END
    return $amValues;

  }


  /*
   * METHODS: SQL
   ********************************************************************************/

  /** Returns the SQL statement that allows to retrieve this view's entries from its associated database
   *
   * <P><B>NOTE:</B> This function will be optimized if cache is available (see {@link attachCache()}).</P> 
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Database_SQL_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param integer $iQueryType Query type (see {@link PHP_APE_Data_isQueryAbleResultSet} constants)
   * @param array|mixed $amElementsKeys Elements keys to retrieve (all if <SAMP>null</SAMP>)
   * @return string
   */
  final public function formatSQL2QueryEntries( $iQueryType = PHP_APE_Data_isQueryAbleResultSet::Query_All, $amElementsKeys = null )
  {

    // Check status
    if( is_null( $this->roMDB2 ) )
      throw new PHP_APE_Database_Exception( __METHOD__, 'No database' );

    // Sanitize input
    $iQueryType = (integer)$iQueryType;
    if( is_array( $amElementsKeys ) ) $amElementsKeys = array_intersect( $this->getElementsKeys(), $amElementsKeys ); // make sure no undefined keys are specified
    if( !is_null( $amElementsKeys ) and count( $amElementsKeys ) <= 0 )
      throw new PHP_APE_Database_Exception( __METHOD__, 'Invalid/missing (empty) elements keys' );

    // Environment
    $roEnvironment =& PHP_APE_Database_WorkSpace::useEnvironment();

    // Retrieve SQL handling object
    $oSQL = $roEnvironment->getDatabaseSQL( $this->roMDB2 );

    // Verify cache usage
    $bUseCache = (boolean)( $iQueryType & PHP_APE_Data_isQueryAbleResultSet::Query_Cache );
    $bUseCache = $bUseCache && !is_null( $this->roCache );
    $iQueryType &= ~PHP_APE_Data_isQueryAbleResultSet::Query_Cache;
    if( $bUseCache ) $iQueryType |= PHP_APE_Data_isQueryAbleResultSet::Query_Cache;

    // Retrieve limit/offset settings
    $oScroller = $this->getScroller();
    $iLimit = $oScroller->getLimit();
    $iOffset = $oScroller->getOffset();

    // Build SQL
    $sSQL = $this->_formatSQL( $iQueryType, PHP_APE_Database_SQL_Any::Select, $amElementsKeys );
    $sSQL .= $this->_formatSQL( $iQueryType, PHP_APE_Database_SQL_Any::From ).' ';
    if( $bUseCache )
    { // use cached primary key values

      // Retrieve primary key
      $mPKey = $this->getPrimaryKey();

      // Retrieve primary key values
      $amPKValues = $this->queryPrimaryKeys( $iQueryType );

      // Limit/offset primary key values
      $amPKValues = array_slice( $amPKValues, $iOffset, $iLimit );

      // Build SQL statement
      $oPKField = $this->useElement( $mPKey )->getContent();
      $sSQL_where = null;
      foreach( $amPKValues as $mPKValue )
      {
        if( !empty( $sSQL_where ) ) $sSQL_where .= ',';
        $oPKField->setValue( $mPKValue );
        $sSQL_where .= $oSQL->formatData( $oPKField );
      }
      if( !empty( $sSQL_where ) )
        $sSQL_where = $oSQL->formatField( $this, $mPKey, false ).$oSQL->formatClause( PHP_APE_Database_SQL_Any::Where_In, $sSQL_where );
      else
        $sSQL_where = $oSQL->formatData( new PHP_APE_Type_Boolean( false ) );
      $sSQL .= $oSQL->formatClause( PHP_APE_Database_SQL_Any::Where, $sSQL_where );
      if( ( $iQueryType & PHP_APE_Data_isQueryAbleResultSet::Query_Order ) ) $sSQL .= $this->_formatSQL( $iQueryType, PHP_APE_Database_SQL_Any::Order );

    }
    else
    { // NO cached primary keys

      if( ( $iQueryType & ( PHP_APE_Data_isQueryAbleResultSet::Query_Subset | PHP_APE_Data_isQueryAbleResultSet::Query_Filter ) ) ) $sSQL .= $this->_formatSQL( $iQueryType, PHP_APE_Database_SQL_Any::Where );
      if( ( $iQueryType & PHP_APE_Data_isQueryAbleResultSet::Query_Order ) ) $sSQL .= $this->_formatSQL( $iQueryType, PHP_APE_Database_SQL_Any::Order );
      if( ($iQueryType & PHP_APE_Data_isQueryAbleResultSet::Query_Limit ) or ( $iQueryType & PHP_APE_Data_isQueryAbleResultSet::Query_Offset ) ) $sSQL .= $oSQL->formatClause( PHP_APE_Database_SQL_Any::Limit, $iLimit ).$oSQL->formatClause( PHP_APE_Database_SQL_Any::Offset, $iOffset );

    }

    // End
    return $sSQL;
  }

  /** Returns the SQL statement that allows to retrieve this view's entries quantity (count) from its associated database
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Database_SQL_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param integer $iQueryType Query type (see {@link PHP_APE_Data_isQueryAbleResultSet} constants)
   * @return string
   */
  final public function formatSQL2QueryCount( $iQueryType = PHP_APE_Data_isQueryAbleResultSet::Query_All )
  {

    // Check status
    if( is_null( $this->roMDB2 ) )
      throw new PHP_APE_Database_Exception( __METHOD__, 'No database' );

    // Environment
    $roEnvironment =& PHP_APE_Database_WorkSpace::useEnvironment();

    // Retrieve SQL handling object
    $oSQL = $roEnvironment->getDatabaseSQL( $this->roMDB2 );

    // Build SQL statement
    $sSQL = $this->_formatSQL( $iQueryType, PHP_APE_Database_SQL_Any::Aggregate_Count );
    $sSQL .= $this->_formatSQL( $iQueryType, PHP_APE_Database_SQL_Any::From );
    if( ( $iQueryType & ( PHP_APE_Data_isQueryAbleResultSet::Query_Subset | PHP_APE_Data_isQueryAbleResultSet::Query_Filter ) ) ) $sSQL .= $this->_formatSQL( $iQueryType, PHP_APE_Database_SQL_Any::Where );

    // END
    return $sSQL;

  }

  /** Returns the SQL statement that allows to retrieve this view's primary keys from its associated database
   *
   * <P><B>USAGE:</B> The queried view MUST have one SINGLE primary key for this method to work.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param integer $iQueryType Query type (see {@link PHP_APE_Data_isQueryAbleResultSet} constants)
   * @return string
   */
  final public function formatSQL2QueryPrimaryKeys( $iQueryType = PHP_APE_Data_isQueryAbleResultSet::Query_All )
  {

    // Check status
    if( is_null( $this->roMDB2 ) )
      throw new PHP_APE_Database_Exception( __METHOD__, 'No database' );

    // Retrieve primary key
    $mKey = $this->getPrimaryKey();

    // Environment
    $roEnvironment =& PHP_APE_Database_WorkSpace::useEnvironment();

    // Retrieve SQL handling object
    $oSQL = $roEnvironment->getDatabaseSQL( $this->roMDB2 );

    // Build SQL statement
    $sSQL = $oSQL->formatClause( PHP_APE_Database_SQL_Any::Select, $oSQL->formatField( $this, $mKey, false ) );
    $sSQL .= $this->_formatSQL( $iQueryType, PHP_APE_Database_SQL_Any::From );
    if( ( $iQueryType & ( PHP_APE_Data_isQueryAbleResultSet::Query_Subset | PHP_APE_Data_isQueryAbleResultSet::Query_Filter ) ) ) $sSQL .= $this->_formatSQL( $iQueryType, PHP_APE_Database_SQL_Any::Where );
    if( ( $iQueryType & PHP_APE_Data_isQueryAbleResultSet::Query_Order ) ) $sSQL .= $this->_formatSQL( $iQueryType, PHP_APE_Database_SQL_Any::Order );
    if( ( $iQueryType & PHP_APE_Data_isQueryAbleResultSet::Query_Limit ) ) $sSQL .= $oSQL->formatClause( PHP_APE_Database_SQL_Any::Limit, (integer)$roEnvironment->getVolatileParameter( 'php_ape.data.query.cachesize' ) );

    // END
    return $sSQL;

  }

  /** Returns the SQL statement for the given clause
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Database_SQL_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param integer $iQueryType Query type (see {@link PHP_APE_Data_isQueryAbleResultSet} constants)
   * @param integer $iClause SQL clause code
   * @param mixed $mParameters Clause-specific parameters
   * @see PHP_APE_Database_SQL_Any
   */
  protected function _formatSQL( $iQueryType, $iClause, $mParameters = null )
  {

    // Environment
    $roEnvironment =& PHP_APE_Database_WorkSpace::useEnvironment();

    // Retrieve SQL handling object
    $oSQL = $roEnvironment->getDatabaseSQL( $this->roMDB2 );

    // Build SQL clause
    $sSQL = null;
    $iClause = (integer)$iClause;
    switch( $iClause )
    {

    case PHP_APE_Database_SQL_Any::Select:
    case PHP_APE_Database_SQL_Any::Select_Distinct:
      // Retrieve fields IDs/keys
      $amElementsKeys = $mParameters;
      if( !is_array( $amElementsKeys ) ) $amElementsKeys = $this->getElementsKeys();

      // Build SQL clause
      foreach( $amElementsKeys as $mKey )
        $sSQL .= ( $sSQL ? ', ' : null ).$oSQL->formatField( $this, $mKey, true );
      if( empty( $sSQL ) ) // that should NOT happen
        throw new PHP_APE_Database_Exception( __METHOD__, 'Empty SELECT clause' );
      return $oSQL->formatClause( $iClause, $sSQL );

    case PHP_APE_Database_SQL_Any::From:
    case PHP_APE_Database_SQL_Any::Join:
    case PHP_APE_Database_SQL_Any::Join_Left:
    case PHP_APE_Database_SQL_Any::Join_Right:
      $sSQL = $oSQL->formatView( $this, true );
      return $oSQL->formatClause( $iClause, $sSQL );

    case PHP_APE_Database_SQL_Any::Where:
    case PHP_APE_Database_SQL_Any::Having:
      // Retrieve filter
      if( !$this->hasSubsetFilter() and !$this->hasFilter() ) return null;

      // Build SQL clause

      // ... subset filter
      $sSQL_subset = null;
      if( ( $iQueryType & PHP_APE_Data_isQueryAbleResultSet::Query_Subset ) and $this->hasSubsetFilter() )
      {
        $oFilter = $this->getSubsetFilter();

        // ... filter keys
        $amFilterKeys = $oFilter->getElementsKeys();

        // ... global filter (simple filter/search)
        $sSQL_global = null;
        if( in_array( '__GLOBAL', $amFilterKeys ) )
        {
          $oCriteria = $oFilter->getElement( '__GLOBAL' );
          $oFilterGlobal = new PHP_APE_Data_Filter();
          $amFieldsKeys = $this->getElementsKeysPerMeta( PHP_APE_Data_Field::Feature_SearchAble, true );
          foreach( $amFieldsKeys as $mKey )
          {
            if( in_array( $mKey, $amFilterKeys ) ) continue; // we do NOT override advanced (field-wise) filter criteria
            $oFilterGlobal->setElement( $oCriteria, $mKey );
          }
          $sSQL_global = $oSQL->formatFilter( $this, $oFilterGlobal, true ); // simple filter/search is always OR-ed
        }

        // ... field-wise filter (advanced filter)
        $sSQL_fields = $oSQL->formatFilter( $this, $oFilter, false );

        // ... complete clause
        if( !empty( $sSQL_global ) and !empty( $sSQL_fields ) )
          $sSQL_subset = $oSQL->formatLogicalOperator( PHP_APE_Data_LogicalOperator::AAnd, '('.$sSQL_global.')', '('.$sSQL_fields.')' );
        elseif( !empty( $sSQL_global ) )
          $sSQL_subset = $sSQL_global;
        elseif( !empty( $sSQL_fields ) )
          $sSQL_subset = $sSQL_fields;
      }

      // ... filter
      $sSQL_filter = null;
      if( ( $iQueryType & PHP_APE_Data_isQueryAbleResultSet::Query_Filter ) and $this->hasFilter() )
      {
        $oFilter = $this->getFilter();

        // ... preferences
        $bFilterAdvanced = $roEnvironment->getUserParameter( 'php_ape.data.filter.advanced' );
        $bFilterOr = $roEnvironment->getUserParameter( 'php_ape.data.filter.or' );

        // ... filter keys
        $amFilterKeys = $oFilter->getElementsKeys();

        // ... global filter (simple filter/search)
        $sSQL_global = null;
        if( in_array( '__GLOBAL', $amFilterKeys ) )
        {
          $oCriteria = $oFilter->getElement( '__GLOBAL' );
          $oFilterGlobal = new PHP_APE_Data_Filter();
          $amFieldsKeys = $this->getElementsKeysPerMeta( PHP_APE_Data_Field::Feature_SearchAble, true );
          foreach( $amFieldsKeys as $mKey )
          {
            if( in_array( $mKey, $amFilterKeys ) and $bFilterAdvanced ) continue; // we do NOT override advanced (field-wise) filter criteria
            $oFilterGlobal->setElement( $oCriteria, $mKey );
          }
          $sSQL_global = $oSQL->formatFilter( $this, $oFilterGlobal, true ); // simple filter/search is always OR-ed
        }

        // ... field-wise filter (advanced filter)
        $sSQL_fields = null;
        if( $bFilterAdvanced )
          $sSQL_fields = $oSQL->formatFilter( $this, $oFilter, $bFilterOr );

        // ... complete clause
        if( !empty( $sSQL_global ) and !empty( $sSQL_fields ) ) {
          $iLogicalOperator = $bFilterOr ? PHP_APE_Data_LogicalOperator::OOr : PHP_APE_Data_LogicalOperator::AAnd;
          $sSQL_filter = $oSQL->formatLogicalOperator( $iLogicalOperator, '('.$sSQL_global.')', '('.$sSQL_fields.')' );
        }
        elseif( !empty( $sSQL_global ) )
          $sSQL_filter = $sSQL_global;
        elseif( !empty( $sSQL_fields ) )
          $sSQL_filter = $sSQL_fields;
      }

      // ... complete clause
      if( !empty( $sSQL_subset ) and !empty( $sSQL_filter ) )
        $sSQL = $oSQL->formatLogicalOperator( PHP_APE_Data_LogicalOperator::AAnd, '('.$sSQL_subset.')', '('.$sSQL_filter.')' );
      elseif( !empty( $sSQL_subset ) )
        $sSQL = $sSQL_subset;
      elseif( !empty( $sSQL_filter ) )
        $sSQL = $sSQL_filter;
      else
        return null; // empty SQL clause is possible, if no filter IDs/keys matched the view IDs/keys
      return $oSQL->formatClause( $iClause, $sSQL );

    case PHP_APE_Database_SQL_Any::Order:
      // Retrieve order
      if( !$this->hasOrder() ) return null;
      $oOrder = $this->getOrder();

      // Build SQL clause
      $sSQL = $oSQL->formatOrder( $this, $oOrder );
      if( empty( $sSQL ) )
        return null; // empty SQL clause is possible, if no filter IDs/keys matched the view IDs/keys
      return $oSQL->formatClause( $iClause, $sSQL );

    case PHP_APE_Database_SQL_Any::Aggregate_Count:
      try { $mPKey = $this->getPrimaryKey(); }
      catch( PHP_APE_Database_Exception $e ) { $mPKey = null; }
      if( !is_null( $mPKey ) ) $sSQL = $oSQL->formatField( $this, $mPKey, false );
      else $sSQL = '*';
      return $oSQL->formatClause( PHP_APE_Database_SQL_Any::Select, $oSQL->formatClause( $iClause, $sSQL ) );

    }

    throw new PHP_APE_Database_Exception( __METHOD__, 'Invalid/unsupported SQL clause; Code: '.$iClause );

  }

}
