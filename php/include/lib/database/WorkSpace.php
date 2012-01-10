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
 * @subpackage WorkSpace
 */

/** Load PEAR resources */
require_once( 'MDB2.php' );

/** PHP-APE database workspace
 *
 * <P><B>NOTE:</B> This workspace relies on PHP PEAR::MDB2 package.</P>
 *
 * <P><B>USAGE:</B></P>
 * <P>The following static parameters (properties) are provisioned by this workspace:</P>
 * <UL>
 * <LI><SAMP>php_ape.database.dsn</SAMP>: identified database DSN [format: '<SAMP><id>:<mdb2_dsn></SAMP>']</LI>
 * <LI><SAMP>php_ape.database.cache.file.prefix</SAMP>: file-based cache prefix [default: <SAMP>PHP_APE_CACHE</SAMP>+'<SAMP>/PHP_APE_Database</SAMP>']</LI>
 * <LI><SAMP>php_ape.database.cache.file.size</SAMP>: file-based cache size [default: <SAMP>1000</SAMP>]</LI>
 * <LI><SAMP>php_ape.database.cache.file.lifetime</SAMP>: file-based cache lifetime, in seconds [default: <SAMP>3600</SAMP>]</LI>
 * <LI><SAMP>php_ape.database.cache.file.accessweight</SAMP>: file-based cache access weight factor [default: <SAMP>0</SAMP>]</LI>
 * <LI><SAMP>php_ape.database.cache.memory.size</SAMP>: memory-based cache size [default: <SAMP>1000</SAMP>]</LI>
 * <LI><SAMP>php_ape.database.cache.memory.lifetime</SAMP>: memory-based cache lifetime, in seconds [default: <SAMP>3600</SAMP>]</LI>
 * <LI><SAMP>php_ape.database.cache.memory.accessweight</SAMP>: memory-based cache access weight factor [default: <SAMP>0</SAMP>]</LI>
 * </UL>
 *
 * @package PHP_APE_Database
 * @subpackage WorkSpace
 */
class PHP_APE_Database_WorkSpace
extends PHP_APE_WorkSpace
{

  /*
   * FIELDS: static
   ********************************************************************************/

  /** Work space singleton
   * @var PHP_APE_Database_WorkSpace */
  private static $oWORKSPACE;


  /*
   * FIELDS
   ********************************************************************************/

  /** Databases DSNs
   * @var array|string */
  private $asDSNs;

  /** Connected databases
   * @var array|DB */
  private $aoConnectedDBs;

  /** File-based cache
   * @var PHP_APE_Util_Cache_File */
  private $oCache2File;

  /** Memory-based cache
   * @var PHP_APE_Util_Cache_Memory */
  private $oCache2Memory;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs the workspace
   */
  protected function __construct()
  {
    // Initialize member fields
    $this->asDSNs = array();
    $this->aoConnectedDBs = array();

    // Call the parent constructor
    parent::__construct();

    // Initialize database DSNs
    foreach( PHP_APE_Type_Array::parseValue( $this->asStaticParameters['php_ape.database.dsn' ] ) as $mID => $sDSN )
      $this->asDSNs[ strtolower( $mID ) ] = $sDSN;

  }


  /*
   * METHODS: factory
   ********************************************************************************/

  /** Returns the (singleton) environment instance (<B>as reference</B>)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   *
   * @return PHP_APE_Database_WorkSpace
   */
  public static function &useEnvironment()
  {
    if( is_null( self::$oWORKSPACE ) ) self::$oWORKSPACE = new PHP_APE_Database_WorkSpace();
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

    // Database DSNs
    if( array_key_exists( 'php_ape.database.dsn', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.database.dsn' ];
      $rValue = PHP_APE_Type_Array::parseValue( $rValue );
    }

    // File-based cache prefix
    if( array_key_exists( 'php_ape.database.cache.file.prefix', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.database.cache.file.prefix' ];
      $rValue = trim( PHP_APE_Type_Path::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = PHP_APE_CACHE;
      else
        $rValue = rtrim( $rValue, '/' );
    }

    // File-based cache size
    if( array_key_exists( 'php_ape.database.cache.file.size', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.database.cache.file.size' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( empty( $rValue ) )
        $rValue = 1000;
      elseif( $rValue < 0 )
        $rValue = 0;
    }

    // File-based cache lifetime [seconds]
    if( array_key_exists( 'php_ape.database.cache.file.lifetime', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.database.cache.file.lifetime' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( empty( $rValue ) )
        $rValue = 3600;
      if( $rValue < 1 )
        $rValue = 1;
    }

    // File-based cache accessweight
    if( array_key_exists( 'php_ape.database.cache.file.accessweight', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.database.cache.file.accessweight' ];
      $rValue = PHP_APE_Type_Float::parseValue( $rValue );
      if( empty( $rValue ) or $rValue < 0 )
        $rValue = 0;
      elseif( $rValue > 1 )
        $rValue = 1;
    }

    // Memory-based cache size
    if( array_key_exists( 'php_ape.database.cache.memory.size', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.database.cache.memory.size' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( empty( $rValue ) )
        $rValue = 1000;
      elseif( $rValue < 0 )
        $rValue = 0;
    }

    // Memory-based cache lifetime [seconds]
    if( array_key_exists( 'php_ape.database.cache.memory.lifetime', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.database.cache.memory.lifetime' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( empty( $rValue ) )
        $rValue = 3600;
      if( $rValue < 1 ) 
        $rValue = 1;
    }

    // Memory-based cache accessweight
    if( array_key_exists( 'php_ape.database.cache.memory.accessweight', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.database.cache.memory.accessweight' ];
      $rValue = PHP_APE_Type_Float::parseValue( $rValue );
      if( empty( $rValue ) or $rValue < 0 )
        $rValue = 0;
      elseif( $rValue > 1 )
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
                              'php_ape.database.cache.file.prefix' => null, 'php_ape.database.cache.file.size' => null, 'php_ape.database.cache.file.lifetime' => null, 'php_ape.database.cache.file.accessweight' => null,
                              'php_ape.database.cache.memory.size' => null, 'php_ape.database.cache.memory.lifetime' => null, 'php_ape.database.cache.memory.accessweight' => null
                              )
                        );
  }


  /*
   * METHODS: databases
   ********************************************************************************/

  /** Returns the PEAR::MDB2 (connected) database object for the given database (<B>as reference</B>)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>.</P>
   *
   * @param mixed $mID Database identifier (ID)
   * @param boolean $bConnected Return a connected database
   * @return MDB2_Driver_common
   */
  public function &useDatabase( $mID, $bConnected = true )
  {
    // Retrieve database
    $mID = strtolower( $mID ); // let's be developer friendly ;-)
    if( array_key_exists( $mID, $this->aoConnectedDBs ) ) return $this->aoConnectedDBs[ $mID ];
    
    // Connect database
    if( !array_key_exists( $mID, $this->asDSNs ) )
      throw new PHP_APE_Database_Exception( __METHOD__, 'Invalid/unknown database; Name: '.$mID );
    if( $bConnected )
    {
      $amOptions = array( 'field_case' => CASE_LOWER );
      $roDatabase =& MDB2::connect( $this->asDSNs[ $mID ], $amOptions );
      if( PEAR::isError( $roDatabase ) )
        throw new PHP_APE_Database_Exception( __METHOD__, 'Failed to connect database; Name: '.$mID.'; Error: '.$roDatabase->getMessage() );
      // ... character set
      $this->getDatabaseSQL( $roDatabase )->initCharset();
    }
    else
    {
      $roDatabase =& MDB2::factory( $this->asDSNs[ $mID ] );
      if( PEAR::isError( $roDatabase ) )
        throw new PHP_APE_Database_Exception( __METHOD__, 'Failed to retrieve database instance; Name: '.$mID.'; Error: '.$roDatabase->getMessage() );
    }
    $this->aoConnectedDBs[ $mID ] =& $roDatabase;

    // End
    return $this->aoConnectedDBs[ $mID ];
  }

  /** Disconnects all connected databases
   *
   * <P><B>NOTE:</B> This method <B>SHOULD be called</B> whenever a database has been retrieved using the {@link getDatabase()} method.</P>
   *
   * @return DB
   */
  public function disconnectDatabases()
  {
    // Loop through connected databases
    foreach( $this->aoConnectedDBs as $sName => $oDatabase )
    {
      // Disconnect
      $oDatabase->disconnect();
      unset( $this->aoConnectedDBs[ $sName ] );
    }
  }


  /*
   * METHODS: dataspace
   ********************************************************************************/

  /** Returns the database name corresponding to the given (PEAR::MDB2) database
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>.</P>
   *
   * @param MDB2_Driver_common $oDatabase Database
   * @return string
   */
  public function getDatabaseName( MDB2_Driver_common $oDatabase )
  {
    // Retrieve dataspace space
    if( $oDatabase instanceof MDB2_Driver_pgsql ) return 'PostgreSQL';
    if( $oDatabase instanceof MDB2_Driver_mysql ) return 'MySQL';
    if( $oDatabase instanceof MDB2_Driver_mssql ) return 'MSSQL';
    throw new PHP_APE_Database_Exception( __METHOD__, 'Invalid/unsupported database; Class: '.get_class( $oDatabase ) );
  }

  /** Returns the SQL handling object corresponding to the given (PEAR::MDB2) database
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>.</P>
   *
   * @param MDB2_Driver_common $oDatabase Database
   * @param string $sDataspaceClassPrefix The dataspace class prefix to add to the dataspace name to obtain its corresponding class name
   * @return PHP_APE_DataSpace_Any
   */
  public function getDatabaseSQL( MDB2_Driver_common $oDatabase, $sSQLClassPrefix = 'PHP_APE_Database_SQL_' )
  {
    // Retrieve database name
    $sDatabaseName = $this->getDatabaseName( $oDatabase );

    // Build SQL class name
    $sSQL_class = $sSQLClassPrefix.$sDatabaseName;

    // End
    return new $sSQL_class();
  }


  /*
   * METHODS: cache
   ********************************************************************************/

  /** Returns this workspace's file-based caching object (<B>as reference</B>)
   *
   * @return PHP_APE_Util_Cache_File
   */
  public function &useCache2File()
  {
    // Check cache object
    if( is_null( $this->oCache2File ) )
      $this->oCache2File = new PHP_APE_Util_Cache_File( $this->asStaticParameters[ 'php_ape.database.cache.file.prefix' ],
                                                        $this->asStaticParameters[ 'php_ape.database.cache.file.size' ],
                                                        $this->asStaticParameters[ 'php_ape.database.cache.file.lifetime' ],
                                                        $this->asStaticParameters[ 'php_ape.database.cache.file.accessweight' ] );
    return $this->oCache2File;
  }

  /** Returns this workspace's memory-based caching object (<B>as reference</B>)
   *
   * @return PHP_APE_Util_Cache_Memory
   */
  public function &useCache2Memory()
  {
    // Check cache object
    if( is_null( $this->oCache2Memory ) )
      $this->oCache2Memory = new PHP_APE_Util_Cache_Memory( $this->asStaticParameters[ 'php_ape.database.cache.memory.size' ],
                                                            $this->asStaticParameters[ 'php_ape.database.cache.memory.lifetime' ],
                                                            $this->asStaticParameters[ 'php_ape.database.cache.memory.accessweight' ] );
    return $this->oCache2Memory;
  }

}
