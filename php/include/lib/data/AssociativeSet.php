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

/** Associative (identified) data set class
 *
 * @package PHP_APE_Data
 * @subpackage Classes
 */
class PHP_APE_Data_AssociativeSet
extends PHP_APE_Data_ExtendedDataSet
{

  /*
   * CONSTRUCTORS
   ********************************************************************************/

  public function __construct( $mID = null, $aoData = null, $sName = null, $sDescription = null )
  {
    // Initialize member fields
    parent::__construct( $mID, $aoData, $sName, $sDescription );
  }


  /*
   * METHODS: elements - OVERRIDE
   ********************************************************************************/

  /** Sets (adds) the given data element to this set
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param PHP_APE_Data_Any $oData New (or replacement) data
   * @param mixed $sKey Element's association key (data identifier if <SAMP>null</SAMP>)
   * @param boolean $bAllowOverwrite Allow to overwrite existing data with the same identifier
   */
  public function setElement( PHP_APE_Data_Any $oData, $mKey = null, $bAllowOverwrite = false )
  {
    // Check association key
    if( is_null( $mKey ) )
    {
      if( !$oData->hasIdentifier() )
        throw new PHP_APE_Data_Exception( __METHOD__, 'Unidentified data cannot be added' );
      $mKey = $oData->getID();
    }
    else
      $mKey = PHP_APE_Type_Index::parseValue( $mKey );

    // Save element
    if( array_key_exists( $mKey, $this->aoData ) and !$bAllowOverwrite )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Data already exists; Key: '.$mKey );
    $this->aoData[ $mKey ] = $oData;
  }

}
