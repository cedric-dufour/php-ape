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

/** Data caching class
 *
 * <P><B>USAGE:</B> Cache's configuration is provisioned using the following parameters:</P>
 * <UL>
 * <LI><SAMP>size</SAMP>: the maximum quantity of data elements the cache may contain</LI>
 * <LI><SAMP>lifetime</SAMP>: the number of seconds before a data element &quot;expires&quot; and gets deleted</LI>
 * <LI><SAMP>access weight</SAMP>: the access-based priority correction factor that allows to prioritize data elements
 * when the maximum quantity of data elements is reached and one or more data element(s) must be deleted.<BR/>
 * A data element's <I>priority</I> is defined as its index among the elements list (populated on a <I>push</I> basis
 * as elements are saved in the cache; elements are then deleted following a <I>first-in-first-out</I> rule).<BR/>
 * When the necessity of deleting elements arises because of the cache <SAMP>size</SAMP> limit, each element's <I>priority</I>
 * is re-calculated, based on the <I>access count</I> of each element, multiplied by the <SAMP>access weight</SAMP> factor.<BR/>
 * More precisely, each element is moved in the list, its index/priority being recalculated as:<BR/>
 * <I>new index/priority</I> = <I>old index/priority</I> - ( <SAMP>access weight</SAMP> * <I>access count</I> )<BR/>
 * Consequently, if the <SAMP>access weight</SAMP> is <SAMP>null</SAMP> (zero), NO access-based prioritizing occurs.</LI>
 * </UL>
 *
 * @package PHP_APE_Util
 * @subpackage Cache
 */
abstract class PHP_APE_Util_Cache_Any
extends PHP_APE_Util_Any
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Cache size (cached data quantity)
   * @var integer */
  protected $iSize;

  /** Cache lifetime (cached data lifetime [seconds])
   * @var integer */
  protected $iLifetime;

  /** Data access count's weight factor
   * @var float */
  protected $fAccessWeight;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new cache instance
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Util_Cache_Exception</SAMP>.</P>
   *
   * @param integer $iSize Cache size (cached data quantity)
   * @param integer $iLifetime Cache lifetime (cached data lifetime [seconds])
   * @param float $fAccessWeight Data access count's weight factor
   */
  protected function __construct( $iSize, $iLifetime, $fAccessWeight = 0 )
  {
    // Sanitize input
    $iSize = (integer)$iSize;
    if( $iSize <= 0 )
      throw new PHP_APE_Util_Cache_Exception( __METHOD__, 'Invalid size; Size: '.$iSize );
    $iLifetime = (integer)$iLifetime;
    if( $iLifetime <= 0 )
      throw new PHP_APE_Util_Cache_Exception( __METHOD__, 'Invalid lifetime; Lifetime: '.$iLifetime );
    $fAccessWeight = (float)$fAccessWeight;
    if( $fAccessWeight < 0 )
      throw new PHP_APE_Util_Cache_Exception( __METHOD__, 'Invalid access weight factor; Factor: '.$fAccessWeight );

    // Initialize member fields
    $this->iSize = $iSize;
    $this->iLifetime = $iLifetime;
    $this->fAccessWeight = $fAccessWeight;
  }


  /*
   * METHODS: cache
   ********************************************************************************/

  /** Resets (clears) this cache
   */
  abstract public function reset();


  /*
   * METHODS: data
   ********************************************************************************/

  /** Saves given data in cache
   *
   * @param string $sIndex Data index
   * @param mixed $mData Data
   * @param boolean $bAccessed Consider data accessed (one time) while saved
   */
  abstract public function saveData( $sIndex, $mData, $bAccessed = false );

  /** Return the given data from this cache (if available)
   *
   * @param string $sIndex Data index
   * @param boolean $bUpdate Update access information
   */
  abstract public function getData( $sIndex, $bUpdate = true );

  /** Deletes the given data from this cache
   *
   * @param string $sIndex Data index
   */
  abstract public function deleteData( $sIndex );

}
