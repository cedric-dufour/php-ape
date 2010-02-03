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
 * @subpackage Classes
 */

/** Order criteria class
 *
 * @package PHP_APE_Data
 * @subpackage Classes
 */
class PHP_APE_Data_OrderCriteria
extends PHP_APE_Data_Any
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** Unspecified order
   * @var integer */
  const UNSPECIFIED = 0;

  /** Ascending order
   * @var integer */
  const ASCENDING = 1;

  /** Descending order
   * @var integer */
  const DESCENDING = -1;


  /*
   * FIELDS
   ********************************************************************************/

  /** Direction operator
   * @var integer
   */
  private $iDirection;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new order criteria instance
   *
   * @param mixed $mID Criteria identifier (ID)
   * @param integer $iDirection Direction operator
   */
  public function __construct( $mID, $iDirection = null )
  {
    // Initialize member fields
    parent::__construct( $mID );
    $this->setDirection( $iDirection );
  }


  /*
   * METHODS: direction
   ********************************************************************************/

  /** Sets this criteria's direction
   *
   * @param integer $iDirection Direction operator
   */
  public function setDirection( $iDirection )
  {
    $this->iDirection = (integer)$iDirection;
  }

  /** Returns this criteria's direction
   *
   * @return integer
   */
  public function getDirection() {
    return $this->iDirection;
  }

}
