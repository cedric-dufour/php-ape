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
 * @package PHP_APE_Data
 * @subpackage Interfaces
 */

/** Queryable result set interface
 *
 * <P><B>SYNOPSIS:</B> This interface provisions the methods that allow to query resultset-organized data (e.g. database query results) for the implementing object.</P>
 *
 * @package PHP_APE_Data
 * @subpackage Interfaces
 */
interface PHP_APE_Data_isQueryAbleResultSet
extends PHP_APE_Data_isBasicResultSet
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** Include NO special clauses
   * @var integer */
  const Query_Simple = 0;

  /** Include subset filtering clauses
   * @var integer */
  const Query_Subset = 1;

  /** Include filtering clauses
   * @var integer */
  const Query_Filter = 2;

  /** Include ordering clause
   * @var integer */
  const Query_Order = 4;

  /** Include limit clause
   * @var integer */
  const Query_Limit = 8;

  /** Include offset clause
   * @var integer */
  const Query_Offset = 16;

  /** Include scrolling clause
   * @var integer */
  const Query_Scroll = 24;

  /** Use cache (if available)
   * @var integer */
  const Query_Cache = 32;

  /** Include all query clauses and features, except scrolling
   * @var integer */
  const Query_All = 39;

  /** Include all query clauses and features
   * @var integer */
  const Query_Full = 255;

  /** Disable listable ability
   * @var integer */
  const Disable_ListAble = 256;

  /** Disable detailable ability
   * @var integer */
  const Disable_DetailAble = 512;

  /** Disable insertable ability
   * @var integer */
  const Disable_InsertAble = 1024;

  /** Disable updateable ability
   * @var integer */
  const Disable_UpdateAble = 2048;

  /** Disable deleteable ability
   * @var integer */
  const Disable_DeleteAble = 4096;

  /** Disable scrollable ability
   * @var integer */
  const Disable_ScrollerAble = 8192;

  /** Disable orderable ability
   * @var integer */
  const Disable_OrderAble = 16384;

  /** Disable filterable ability
   * @var integer */
  const Disable_FilterAble = 32768;

  /** Disable all abilities
   * @var integer */
  const Disable_All = 65280;


  /*
   * METHODS
   ********************************************************************************/

  /** Fetches this set's entries (rows)
   *
   * <P><B>NOTE:</B> This method SHOULD NOT alter a result set that is being queried (in other words, not reset).</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * @param integer $iQueryMeta Query meta code (see class constants)
   */
  public function queryEntries( $iQueryMeta = self::Query_All );

  /** Returns whether this set is being queried
   *
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * @return boolean
   */
  public function isQueried();

  /** Reset (clears) this set's query status and entries (rows)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   */
  public function resetQuery();

}
