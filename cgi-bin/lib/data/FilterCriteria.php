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

/** Filter criteria class
 *
 * @package PHP_APE_Data
 * @subpackage Classes
 */
class PHP_APE_Data_FilterCriteria
extends PHP_APE_Data_Any
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Criteria data
   * @var PHP_APE_Type_Any|PHP_APE_Data_Filter */
  private $oData;

  /** Criteria operator
   * @var integer
   * @see PHP_APE_Data_Operator
   */
  private $iOperator;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new filter criteria instance
   *
   * @param mixed $mID Criteria identifier (ID)
   * @param scalar|PHP_APE_Type_String|PHP_APE_Data_Filter $oData Criteria data
   * @param integer $iOperator Criteria operator
   */
  public function __construct( $mID, $oData = null, $iOperator = null )
  {
    // Initialize member fields
    parent::__construct( $mID );
    if( !is_null( $oData ) ) $this->setContent( $oData );
    $this->setOperator( $iOperator );
  }


  /*
   * METHODS: data
   ********************************************************************************/

  /** Sets this criteria's content
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   *
   * @param scalar|PHP_APE_Type_String|PHP_APE_Data_Filter $oData Criteria data
   * @see PHP_APE_Data_Quote
   */
  public function setContent( $oData )
  {
    // Check type
    if( is_scalar( $oData ) )
      $oData = new PHP_APE_Type_String( $oData );
    if( !($oData instanceof PHP_APE_Type_String) and !($oData instanceof PHP_APE_Data_Filter) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Invalid data/type; Data/Type: '.( is_object( $oData ) ? get_class( $oData ) : gettype( $oData ) ) );
    $this->oData = clone( $oData );
  }

  /** Returns this criteria's content
   *
   * @return PHP_APE_Type_String|PHP_APE_Data_Filter
   */
  public function getContent()
  {
    return is_object( $this->oData ) ? clone( $this->oData ) : null;
  }


  /*
   * METHODS: operator
   ********************************************************************************/

  /** Sets this criteria's operator
   *
   * <P><B>NOTE:</B> The supplied operator code is binary-wise OR-ed with the existing operator code.</P>
   *
   * @param integer $iOperator Criteria operator
   * @see PHP_APE_Data_Operator
   */
  public function setOperator( $iOperator )
  {
    $this->iOperator |= (integer)$iOperator;
  }

  /** Returns this criteria's operator
   *
   * @return integer
   * @see PHP_APE_Data_Operator
   */
  public function getOperator() {
    return $this->iOperator;
  }


  /*
   * METHODS: import/export
   ********************************************************************************/
  
  /** Returns the criteria array corresponding to the given (human-friendly) properties <I>string</I> representation
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   *
   * @param string $sProperties Filter properties string
   * @return array|PHP_APE_Data_FilterCriteria
   * @see PHP_APE_Properties
   */
  public static function fromProperties( $sProperties )
  {
    $aoFilterCriterias = array();
    $asProperties = PHP_APE_Properties::convertString2Array( $sProperties, false );
    foreach( $asProperties as $mID => $sFilterString )
    {
      $oFilter = new PHP_APE_Data_Filter( $mID );
      $oFilter->fromString( $sFilterString );
      $aoFilterCriterias[ $mID ] = new PHP_APE_Data_FilterCriteria( $mID, $oFilter );
    }
    return $aoFilterCriterias;
  }

  /** Returns the (human-friendly) <I>string</I> representation corresponding to this criteria
   *
   * @return string
   */
  public function toString()
  {
    $sFilterString = null;

    // Add logic operator
    if( $this->iOperator & PHP_APE_Data_LogicalOperator::Mask )
      $sFilterString .= PHP_APE_Data_LogicalOperator::toString( $this->iOperator );

    // Add comparison operator
    if( $this->iOperator & PHP_APE_Data_ComparisonOperator::Mask )
      $sFilterString .= PHP_APE_Data_ComparisonOperator::toString( $this->iOperator );

    // Open quote
    if( $this->iOperator & PHP_APE_Data_QuoteOperator::Mask )
      $sFilterString .= PHP_APE_Data_QuoteOperator::toOpeningString( $this->iOperator );

    // Add criteria
    if( $this->oData instanceof PHP_APE_Data_Filter )
    {
      $sFilterString .= $this->oData->toString();
    }
    elseif( $this->oData instanceof PHP_APE_Type_String )
    {
      $sEscapableQuotes = PHP_APE_Data_QuoteOperator::toOpeningString( $this->iOperator ).PHP_APE_Data_QuoteOperator::toClosingString( $this->iOperator );
      $sFilterString .= addCSlashes( $this->oData->getValue(), $sEscapableQuotes );
    }
        
    // Close quote
    if( $this->iOperator & PHP_APE_Data_QuoteOperator::Mask )
      $sFilterString .= PHP_APE_Data_QuoteOperator::toClosingString( $this->iOperator );

    // END
    return $sFilterString;
  }

}
