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
 * @package PHP_APE_Util
 * @subpackage Cache
 */

/**
 * File-based data caching class
 *
 * @package PHP_APE_Util
 * @subpackage Cache
 */
class PHP_APE_Util_Cache_File
extends PHP_APE_Util_Cache_Any
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Cache files prefix (including path)
   *
   * <P><B>NOTE:</B> Make sure to add a trailing slash when prefixing with a directory path.</P>
   *
   * @var string
   */
  private $sPathPrefix;

  /** Oldest data creation timestamp
   * @var integer */
  private $iCreationOldest;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new file-based cache instance
   *
   * <P><B>NOTE:</B> Make sure to add a trailing slash when prefixing with a directory path.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Util_Cache_Exception</SAMP>.</P>
   *
   * @param string $sPathPrefix Cache files prefix (including path)
   * @param integer $iSize Cache size (cached data quantity)
   * @param integer $iLifetime Cache lifetime (cached data lifetime [seconds])
   * @param float $fAccessWeight Data access count's weight factor
   */
  public function __construct( $sPathPrefix, $iSize, $iLifetime, $fAccessWeight = 0 )
  {
    // Sanitize input
    $sPathPrefix = PHP_APE_Type_Path::parseValue( $sPathPrefix );

    // Initialize member fields
    parent::__construct( $iSize, $iLifetime, $fAccessWeight );
    $this->sPathPrefix = $sPathPrefix;
    $this->reset();
  }


  /*
   * METHODS: cache - OVERRIDE
   ********************************************************************************/

  public function reset()
  {
    foreach( glob( $this->__pathData( '*' ) ) as $sFilename ) @unlink( $sFilename );
    foreach( glob( $this->__pathStat( '*' ) ) as $sFilename ) @unlink( $sFilename );
    $this->iCreationOldest = null;
  }


  /*
   * METHODS: data - OVERRIDE
   ********************************************************************************/

  public function saveData( $sIndex, $mData, $bAccessed = false )
  {
    // Sanitize input
    $sIndex = PHP_APE_Type_Index::parseValue( $sIndex );

    // Delete data
    $this->deleteData( $sIndex );

    // Prioritize cache
    $this->__prioritize( 1 );

    // Save data
    file_put_contents( $this->__pathData( $sIndex ), serialize( $mData ) , LOCK_EX );
    if ( $bAccessed ) $this->__updateStat( $sIndex );
  }

  public function getData( $sIndex, $bUpdate = true )
  {
    // Sanitize input
    $sIndex = PHP_APE_Type_Index::parseValue( $sIndex );

    // Get data path
    $sPathData = $this->__pathData( $sIndex );

    // Check data
    if( !file_exists( $sPathData ) ) return null;

    // Get current timestamp
    $iCurrentTimestamp = time();

    // Check lifetime
    if( $iCurrentTimestamp - filemtime( $sPathData ) > $this->iLifetime )
    {
      $this->deleteData( $sIndex );
      return null;
    }

    // Update data access statistics
    if( $bUpdate ) $this->__updateStat( $sIndex );

    // Retrieve data
    if( ( $mData = file_get_contents( $sPathData, false ) ) === false )
    {
      $this->deleteData( $sIndex );
      return null;
    }

    // END
    return unserialize( $mData );
  }

  public function deleteData( $sIndex )
  {
    // Sanitize input
    $sIndex = PHP_APE_Type_Index::parseValue( $sIndex );

    // Delete data
    @unlink( $this->__pathData( $sIndex ) );
    @unlink( $this->__pathStat( $sIndex ) );
  }


  /*
   * METHODS: utilities
   ********************************************************************************/

  /** Returns the data file path/name for the given data from this cache
   *
   * @return string
   */
  private function __pathData( $sIndex )
  {
    return $this->sPathPrefix.'#'.$sIndex.'.data';
  }

  /** Returns the statistics file path/name for the given data from this cache
   *
   * @return string
   */
  private function __pathStat( $sIndex )
  {
    return $this->sPathPrefix.'#'.$sIndex.'.stat';
  }

  /** Returns the (cached) data indexes from this cache
   *
   * @return array|string
   */
  private function __indexCache()
  {
    $asIndexes = array();

    // Retrieve cache files names
    $asFilenames = glob( $this->sPathPrefix.'#*.data' );
    if( !is_array( $asFilenames ) or !count( $asFilenames ) ) return $asIndexes;

    // Retrieve corresponding data index
    foreach( $asFilenames as $sFilename ) {
      if( preg_match( '/'.preg_quote( $this->sPathPrefix,'/').'#(.+)\.data/', $sFilename, $aMatch ) ) array_push( $asIndexes, $aMatch[1] );
    }

    // END
    return $asIndexes;
  }

  /** Returns the access count for the given data
   *
   * @return integer
   */
  private function __getStat( $sIndex )
  {
    // Get statistics path
    $sPathStat = $this->__pathStat( $sIndex );

    // Retrieve access count
    $iAccessCount = @file_get_contents( $sPathStat, false );
    if( $iAccessCount === false ) $iAccessCount = 0;
    else $iAccessCount = unserialize( $iAccessCount );

    // END
    return $iAccessCount;
  }

  /** Updates the access count for the given data
   */
  private function __updateStat( $sIndex )
  {
    // Get statistics path
    $sPathStat = $this->__pathStat( $sIndex );

    // Retrieve access count
    $iAccessCount = $this->__getStat( $sIndex );

    // Update statistics
    ++$iAccessCount;
    file_put_contents( $sPathStat, serialize( (integer)$iAccessCount ), LOCK_EX );
  }

  /** Prioritizes this cache
   *
   * <P><B>NOTE:</B> This methods cleans the cache according to its size, lifetime and access statistics.</P>
   *
   * @param integer $iReserve Reserve (n) slot(s) for data to be added
   */
  private function __prioritize( $iReserve = 0 )
  {

    // Get data
    $asIndexes = $this->__indexCache();

    // Check data (no need to prioritize empty cache)
    if( $asIndexes===false or !count( $asIndexes ) ) return;

    // Get current timestamp
    $iCurrentTimestamp = time();

    // Check oldest creation timestamp (no need to prioritize up-to-date cache)
    if( $iCurrentTimestamp - $this->iCreationOldest > $this->iLifetime ) {
      $this->iCreationOldest = time();
      foreach( $asIndexes as $sIndex ) {
        $iCreationTimestamp = filemtime( $this->__pathData( $sIndex ) );
        if( $iCurrentTimestamp - $iCreationTimestamp > $this->iLifetime ) $this->deleteData( $sIndex );
        elseif( $iCreationTimestamp < $this->iCreationOldest  ) $this->iCreationOldest = $iCreationTimestamp;
      }
    }

    // Check size (no need to prioritize if cache has not reached its size limit)
    $iReserve = (integer)$iReserve;
    if( $iReserve < 0 ) $iReserve = 0;
    if( count( $asIndexes ) <= ( $this->iSize - $iReserve ) ) return;

    // Prioritize data (based on last accessed timestamp and access count)
    // > keep data that were accessed more recently than other
    // > keep data that were accessed more frequently than other

    // ... retrieve access statistics
    $aiAccessTimestamp = array();
    $aiAccessCount = array();
    foreach( $asIndexes as $sIndex ) {
      $aiAccessTimestamp[$sIndex] = fileatime( $this->__pathData( $sIndex ) );
      $aiAccessCount[$sIndex] = $this->__getStat( $sIndex );
    }

    // ... (reverse) order data based on access timestamp
    arsort( $aiAccessTimestamp );

    // ... compute data priority (using data index order)
    $asIndexes = array_flip( array_keys( $aiAccessTimestamp ) );

    // ... correct priority (using data access count, pundered by access weight factor)
    foreach( $asIndexes as $sIndex => $iPriority ) $asIndexes[$sIndex] = (float)$asIndexes[$sIndex] - ( $this->fAccessWeight * (float)$aiAccessCount[$sIndex] );

    // ... re-order data based on corrected priority
    asort( $asIndexes );

    // ... delete data out of cache size
    $iCount=0;
    foreach( $asIndexes as $sIndex => $iPriority ) {
      if( ++$iCount > ( $this->iSize - $iReserve ) ) $this->deleteData( $sIndex );
    }

  }

}
