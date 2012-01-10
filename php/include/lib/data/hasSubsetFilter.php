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

/** Subset (filtered) data interface
 *
 * <P><B>SYNOPSIS:</B> This interface provisions the methods that allow to retrieve the subset data filter associated with the implementing object.</P>
 *
 * @package PHP_APE_Data
 * @subpackage Interfaces
 */
interface PHP_APE_Data_hasSubsetFilter
extends PHP_APE_Data_hasInterface
{

  /*
   * METHODS
   ********************************************************************************/

  /** Returns if this object's subset data filter has been defined
   *
   * @return boolean
   */
  public function hasSubsetFilter();

  /** Returns this object's subset data filter
   *
   * <P><B>NOTE:</B> If no filter has been defined, this method SHOULD return the filter applied by default,
   * or a sample filter (with usable/available filter criteria keys).</P>
   *
   * @return PHP_APE_Data_Filter
   */
  public function getSubsetFilter();

} 
