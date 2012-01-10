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
 * Memory-based data caching class
 *
 * @package PHP_APE_Util
 * @subpackage Cache
 */
class PHP_APE_Util_Cache_Memory
extends PHP_APE_Util_Cache_Any
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Cached data
   * @var array|mixed */
  private $amData;

  /** Data creation timestamp
   * @var array|integer */
  private $aiCreationTimestamp;

  /** Oldest data creation timestamp
   * @var integer */
  private $iCreationOldest;

  /** Data last access timestamp
   * @var array|integer */
  private $aiAccessTimestamp;

  /** Data access count
   * @var array|integer */
  private $aiAccessCount;

  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new memory-based cache instance
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Util_Cache_Exception</SAMP>.</P>
   *
   * @param integer $iSize Cache size (cached data quantity)
   * @param integer $iLifetime Cache lifetime (cached data lifetime [seconds])
   * @param float $fAccessWeight Data access count's weight factor
   */
  public function __construct( $iSize, $iLifetime, $fAccessWeight = 0 )
  {
    parent::__construct( $iSize, $iLifetime, $fAccessWeight);
    $this->reset();
  }


  /*
   * METHODS: cache - OVERRIDE
   ********************************************************************************/

  public function reset()
  {
    $this->amData = array();
    $this->aiCreationTimestamp = array();
    $this->iCreationOldest = null;
    $this->aiAccessTimestamp = array();
    $this->aiAccessCount = array();
  }


  /*
   * METHODS: data - OVERRIDE
   ********************************************************************************/

  public function saveData( $sIndex, $mData, $bAccessed = false )
  {
    // Sanitize input
    $sIndex = PHP_APE_Type_Index::parseValue( $sIndex );

    // Delete data
    $this->deleteData($sIndex);

    // Prioritize cache
    $this->__prioritize(1);

    // Get current timestamp
    $iCurrentTimestamp = time();

    // Save data
    $this->amData[$sIndex] = $mData;
    $this->aiCreationTimestamp[$sIndex] = $iCurrentTimestamp;
    $this->aiAccessTimestamp[$sIndex] = $iCurrentTimestamp;
    $this->aiAccessCount[$sIndex] = $bAccessed?1:0;
  }

  public function getData( $sIndex, $bUpdate = true )
  {
    // Sanitize input
    $sIndex = PHP_APE_Type_Index::parseValue( $sIndex );

    // Check data
    if( !array_key_exists( $sIndex, $this->amData ) ) return null;

    // Get current timestamp
    $iCurrentTimestamp = time();

    // Check lifetime
    if( $iCurrentTimestamp - $this->aiCreationTimestamp[$sIndex] > $this->iLifetime ) {
      $this->deleteData($sIndex);
      return null;
    }

    // Update data access timestamp/count
    if( $bUpdate ) {
      $this->aiAccessTimestamp[$sIndex] = $iCurrentTimestamp;
      $this->aiAccessCount[$sIndex]++;
    }

    // END
    return $this->amData[$sIndex];
  }

  public function deleteData( $sIndex )
  {
    // Sanitize input
    $sIndex = PHP_APE_Type_Index::parseValue( $sIndex );

    // Delete data
    unset($this->amData[$sIndex]);
    unset($this->aiCreationTimestamp[$sIndex]);
    // > no need to fix oldest creation timestamp, this would induce unnecessary extra processing
    unset($this->aiAccessTimestamp[$sIndex]);
    unset($this->aiAccessCount[$sIndex]);
  }


  /*
   * METHODS: utilities
   ********************************************************************************/

  /** Prioritizes this cache
   *
   * <P><B>NOTE:</B> This methods cleans the cache according to its size, lifetime and access statistics.</P>
   *
   * @param integer $iReserve Reserve (n) slot(s) for data to be added
   */
  private function __prioritize( $iReserve = 0 )
  {

    // Check data (no need to prioritize empty cache)
    if( !count( $this->amData ) ) return;

    // Get current timestamp
    $iCurrentTimestamp = time();

    // Check oldest creation timestamp (no need to prioritize up-to-date cache)
    if( $iCurrentTimestamp - $this->iCreationOldest > $this->iLifetime )
    {
      $this->iCreationOldest = time();
      foreach( $this->aiCreationTimestamp as $sIndex => $iCreationTimestamp )
      {
        if( $iCurrentTimestamp - $iCreationTimestamp > $this->iLifetime ) $this->deleteData( $sIndex );
        elseif( $iCreationTimestamp < $this->iCreationOldest  ) $this->iCreationOldest = $iCreationTimestamp;
      }
    }

    // Check size (no need to prioritize if cache has not reached its size limit)
    $iReserve = (integer)$iReserve;
    if( $iReserve < 0 ) $iReserve = 0;
    if( count( $this->amData ) <= ( $this->iSize - $iReserve ) ) return;

    // Prioritize data (based on last accessed timestamp and access count)
    // > keep data that were accessed more recently than other
    // > keep data that were accessed more frequently than other

    // ... (reverse) order data based on access timestamp
    arsort( $this->aiAccessTimestamp );

    // ... compute data priority (using data index order)
    $asIndexes = array_flip( array_keys( $this->aiAccessTimestamp ) );

    // ... correct priority (using data access count, pundered by access weight factor)
    foreach( $asIndexes as $sIndex => $iPriority ) $asIndexes[$sIndex] = (float)$asIndexes[$sIndex] - ( $this->fAccessWeight * (float)$this->aiAccessCount[$sIndex] );

    // ... re-order data based on corrected priority
    asort( $asIndexes );

    // ... delete data out of cache size
    $iCount=0;
    foreach( $asIndexes as $sIndex => $iPriority ) {
      if( ++$iCount > ( $this->iSize - $iReserve ) ) $this->deleteData( $sIndex );
    }

  }

}
