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

/** SQL function class
 *
 * <P><B>SYNOPSIS:</B> This class allows to represent (map) an existing database function,
 * and provides the necessary methods to query the database and retrieve the corresponding data,
 * thus providing a powerful abstraction layer on top of the SQL space.</P>
 *
 * <P><B>EXAMPLE: myFunction.sql</B></P>
 * <CODE>
 * -- PostgreSQL function
 * CREATE OR REPLACE FUNCTION mySchema.myFunction( char ) RETURNS char AS '
 *   SELECT md5( $1 );
 * ' LANGUAGE SQL
 * ;
 * </CODE>
 *
 * <P><B>EXAMPLE: myFunction.php</B></P>
 * <CODE>
 * <?php
 * // Define 'myFunction'
 * class myFunction extends PHP_APE_Database_Function
 * {
 *   function __construct( $mID )
 *   {
 *     // Link 'myFunction' to the 'mySchema.myFunction' database entity
 *     parent::__construct( $mID, // ....................................................... Function identifier (ID)
 *                          new PHP_APE_Type_String(), // ...................................... Function's result associated data object
 *                          'myFunction', // ............................................... Function expresion (function name)
 *                          'mySchema', // ................................................. Function namespace (database schema)
 *                          'mysql', // .................................................... Function preferred database
 *                          'Named function', // ........................................... Function (human-friendly) name
 *                          'Described function' // ........................................ Function (human-friendly) description
 *                          );
 *
 *     // Prepare arguments
 *     $oArgumentSet = new PHP_APE_Data_ArgumentSet();
 *
 *     // ... 'myArgument1'
 *     $oArgumentSet->setElement( new PHP_APE_Data_Argument( 'myArgument1', // ................. Argument identifier (ID)
 *                                                       new PHP_APE_Type_String(), // ......... Associated data object
 *                                                       PHP_APE_Data_Argument::Type_Data, // .. Argument meta code
 *                                                       'Argument 1', // .................. Argument (human-friendly) name
 *                                                       'My first argument' ) // .......... Argument (human-friendly) description
 *                                 );
 *
 *     // Add arguments
 *     $this->setArguments( $oArgumentSet );
 *   }
 * }
 *
 * // Instantiate 'myFunction'
 * $myFunction = new myFunction( 'myFunction' );
 *
 * // Set 'myFunction' arguments
 * $myFunction->useArguments()->useElementByID( 'myArgument1' )->useContent()->setValue( 'myArgument1Value' );
 *
 * // Execute 'myFunction'
 * $roDatabase =& $roEnvironment->useDatabase( $myView->getDatabaseID() );
 * $myFunction->attachDatabase( $roDatabase );
 * echo 'ID: "'.$myFunction->getID()."\"<BR/>\r\n";
 * echo 'Name: "'.$myFunction->getName()."\"<BR/>\r\n";
 * echo 'Description: "'.$myFunction->getDescription()."\"<BR/>\r\n";
 * echo 'Arguments: "'.$myFunction->useArguments()."\"<BR/>\r\n";
 * echo 'Result: "'.$myFunction."\"<BR/>\r\n";
 * echo "<PRE>\r\n";
 * echo "EXECUTE:<BR/>\r\n";
 * echo " SQL: ".$myFunction->formatSQL2Execute()."<BR/>\r\n";
 * echo " OUTPUT: ".$myFunction->execute()->getValue()."<BR/>\r\n";
 * echo "</PRE><BR/>\r\n";
 * ?>
 * </CODE>
 *
 * <P><B>OUTPUT: myFunction.php</B></P>
 * <PRE>
 * EXECUTE:
 *  SQL: SELECT mySchema.myFunction( CAST('myArgument1Value' AS char) )
 *  OUTPUT: &lt;value&gt;
 * </PRE>
 *
 * @package PHP_APE_Database
 * @subpackage Classes
 */
abstract class PHP_APE_Database_Function
extends PHP_APE_Data_Function
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** Include NO special directives
   * @var integer */
  const Execute_Simple = 0;

  /** Use cache (if available)
   * @var integer */
  const Execute_Cache = 16; // 0b...1....

  /** Include all execute clauses and features
   * @var integer */
  const Execute_Full = 255; // 0b11111111


  /*
   * FIELDS
   ********************************************************************************/

  /** Function's expression (function name or SQL expression)
   * @var string */
  private $sExpression;

  /** Function's namespace (database schema)
   * @var string */
  private $sNamespace;

  /** Function's preferred database identifier (ID)
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


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new function
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>.</P>
   *
   * @param mixed $mID Function identifier (ID)
   * @param PHP_APE_Type_Scalar $oResult Function's result associated object
   * @param string $sExpression Function expression (table/function/procedure name or SQL expression)
   * @param string $sNamespace Function's namespace (database schema)
   * @param string $mDatabaseID Function's preferred database identifier (ID)
   * @param string $sName Function name (defaults to identifier if <SAMP>empty</SAMP>)
   * @param string $sDescription Function description
   */
  public function __construct( $mID, PHP_APE_Type_Scalar $oResult, $sExpression, $sNamespace = null, $mDatabaseID = null, $sName = null, $sDescription = null )
  {
    // Sanitize arguments
    $sExpression = PHP_APE_Type_String::parseValue( $sExpression, true );
    if( empty( $sExpression ) )
      throw new PHP_APE_Database_Exception( __METHOD__, 'Invalid (empty) expression' );
    $sNamespace = PHP_APE_Type_String::parseValue( $sNamespace, true );
    $mDatabaseID = PHP_APE_Type_Index::parseValue( $mDatabaseID, true );

    // Initialize member fields
    parent::__construct( $mID, $oResult, $sName, $sDescription );
    $this->sExpression = $sExpression;
    $this->sNamespace = $sNamespace;
    $this->mDatabaseID = $mDatabaseID;
  }


  /*
   * METHODS: initialization
   ********************************************************************************/

  /** Resets the function
   *
   * <P><B>SYNOPSIS:</B> This method clears this function result and detaches any associated database or cache.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>.</P>
   */
  public function reset()
  {
    $this->detachDatabase();
    $this->detachCache();
    parent::reset();
  }


  /*
   * METHODS: data
   ********************************************************************************/

  /** Returns this function's expression (table/function/procedure name or SQL expression)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getExpression() {
    return $this->sExpression;
  }

  /** Returns this function's namespace (database schema)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getNamespace() {
    return $this->sNamespace;
  }

  /** Returns this function's preferred database identifier (ID)
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

  /** Associates the given (PEAR::MDB2) database to this function
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param MDB2_Driver_common Database (<B>as reference</B>)
   */
  final public function attachDatabase( MDB2_Driver_common &$roMDB2 )
  {
    // Attach database
    $this->roMDB2 =& $roMDB2;
  }

  /** Returns this function's currently associated database (<B>as reference</B>)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return MDB2_Driver_common
   */
  final public function &useDatabase()
  {
    return $this->roMDB2;
  }

  /** Detaches this function's currently associated database
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function detachDatabase()
  {
    // Detach database
    unset( $this->roMDB2 ); // prevent nasty reference side-effects...
    $this->roMDB2 = null;
  }


  /*
   * METHODS: cache
   ********************************************************************************/

  /** Associates the given caching object to this function
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param PHP_APE_Util_Cache_Any Caching object
   */
  final public function attachCache( PHP_APE_Util_Cache_Any &$roCache )
  {
    $this->roCache =& $roCache;
  }

  /** Returns this function's currently associated cache (<B>as reference</B>)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return PHP_APE_Util_Cache_Any
   */
  final public function &useCache()
  {
    return $this->roCache;
  }

  /** Detaches this function's currently associated cache
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
   * METHODS: execute
   ********************************************************************************/

  /** Executes this function
   *
   * <P><B>USAGE:</B> Once this method called, use the {@link PHP_APE_Data_Variable} class methods to further manipulate this function's result.</P> 
   * <P><B>NOTE:</B> This function will be optimized if cache is available (see {@link attachCache()}).</P> 
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Auth_AuthorizationException</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param integer $iExecuteType Function execution type (see {@link PHP_APE_Database_Function} constants)
   * @param boolean $bDebug Show debugging information while performing the query
   * @return PHP_APE_Type_Scalar
   */
  final public function execute( $iExecuteType = self::Execute_Full, $bDebug = false )
  {
    // Try
    try
    {

      // Check authorization
      if( ( $this instanceof PHP_APE_Data_hasAuthorization ) and !$this->hasAuthorization() )
        throw new PHP_APE_Auth_AuthorizationException( __METHOD__, 'Permission denied' );

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

      // Reset result content
      $this->resetContent();

      // Sanitize input
      $iExecuteType = (integer)$iExecuteType;

      // Verify cache usage
      $bUseCache = (boolean)( $iExecuteType & self::Execute_Cache );
      $bUseCache = $bUseCache && !is_null( $this->roCache );
      $iExecuteType &= ~self::Execute_Cache;
      if( $bUseCache ) $iExecuteType |= self::Execute_Cache;

      // Build SQL statement
      $sSQL = $this->formatSQL2Execute( $iExecuteType );

      // Query cache
      $oResult = null;
      if( $bUseCache )
      { // DO use cache

        // Build cache index; let's assume that the SHA1+MD5-hash of the SQL statement is good-enough
        $sCacheIndex = 'SQL-'.sha1($sSQL).'-'.md5($sSQL).'-RES';
      
        // Retrieve cache data
        $oResult = $this->roCache->getData( $sCacheIndex );

      }

      // Query database
      if( is_null( $oResult ) )
      {
        if( $bDebug ) echo nl2br( var_export( $sSQL, true ) );
        $roResultSet =& $this->roMDB2->query( $sSQL );
        if( PEAR::isError( $roResultSet ) )
        {
          if( PHP_APE_DEBUG )
            throw new PHP_APE_Database_Exception( __METHOD__, $roResultSet->getMessage().'; Info: '.$this->roMDB2->errorInfo().'; SQL: '.$sSQL );
          throw new PHP_APE_Database_Exception( __METHOD__, $roResultSet->getMessage() );
        }
        if( !is_array( $amRow = $roResultSet->fetchRow( MDB2_FETCHMODE_ORDERED ) ) )
          throw new PHP_APE_Database_Exception( __METHOD__, 'Failed to retrieve query result' );

        // Save result
        $this->useContent()->setValue( $amRow[0] );
        $oResult = $this->getContent();

        // Free resultset
        $roResultSet->free();

        // Cache result
        if( $bUseCache ) $this->roCache->saveData( $sCacheIndex, $oResult, true );

      }

      // End
      return $oResult;

    }
    catch( PHP_APE_Exception $e )
    {
      // Set error
      $this->asErrors['__GLOBAL'] = 'Database error';

      // Throw further
      throw $e;
    }

  }


  /*
   * METHODS: SQL
   ********************************************************************************/

  /** Returns the SQL statement that allows to retrieve this function's entries from its associated database
   *
   * <P><B>NOTE:</B> This function will be optimized if cache is available (see {@link attachCache()}).</P> 
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Database_SQL_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param integer $iExecuteType Execute type (see class constants)
   * @return string
   */
  final public function formatSQL2Execute( $iExecuteType = self::Execute_Full )
  {

    // Check status
    if( is_null( $this->roMDB2 ) )
      throw new PHP_APE_Database_Exception( __METHOD__, 'No database' );

    // Sanitize input
    $iExecuteType = (integer)$iExecuteType;

    // Retrieve environment
    $roEnvironment =& PHP_APE_Database_WorkSpace::useEnvironment();

    // Retrieve SQL handling object
    $oSQL = $roEnvironment->getDatabaseSQL( $this->roMDB2 );

    // Build SQL
    $sSQL = $oSQL->formatClause( PHP_APE_Database_SQL_Any::Execute_Function, $oSQL->formatFunction( $this, false ) );

    // End
    return $sSQL;
  }

}
