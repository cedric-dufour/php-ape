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
 * @subpackage SQL
 */

/** MySQL handling class
 *
 * @package PHP_APE_Database
 * @subpackage SQL
 */
class PHP_APE_Database_SQL_MySQL
extends PHP_APE_Database_SQL_ANSI92
{

  /*
   * METHODS: parse/format - OVERRIDE
   ********************************************************************************/

  public function formatBasicMathOperator( $iOperator, $sExpression_PRIMARY, $sExpression_SECONDARY, PHP_APE_Type_Scalar $oData = null )
  {
    switch( (integer)$iOperator & PHP_APE_Data_BasicMathOperator::Mask )
    {

    case PHP_APE_Data_BasicMathOperator::Addition:
      if( $oData instanceof PHP_APE_Type_String )
        return 'concat('.$sExpression_PRIMARY.','.$sExpression_SECONDARY.')';

    }

    return parent::formatBasicMathOperator( $iOperator, $sExpression_PRIMARY, $sExpression_SECONDARY, $oData );
  }


  public function formatComparisonOperator( $iOperator, $sExpression_PRIMARY, $sExpression_SECONDARY, PHP_APE_Type_Scalar $oData = null )
  {
    switch( (integer)$iOperator & PHP_APE_Data_ComparisonOperator::Mask )
    {

    case PHP_APE_Data_ComparisonOperator::Proportional:
      if( $oData instanceof PHP_APE_Type_String )
        return $sExpression_PRIMARY.' LIKE '.$this->encodeLikeExpression($sExpression_SECONDARY);

    }

    return parent::formatComparisonOperator( $iOperator, $sExpression_PRIMARY, $sExpression_SECONDARY, $oData );
  }

  public function formatView( PHP_APE_Database_View $oView, $bAlias = false )
  {
    if( $oView->hasArgumentSet() )
    {
      // NOTE: Since MySQL (as of version 5.0.x) does NOT support 'SELECT * FROM myStoreProcedure(...);', we can
      //       only use arguments as filtering criteria. This is NOT the intended behavior... but at least it allows
      //       to use argumented view in order to be compliant with PHP_APE_Data_isDetailAble requirements.

      // Build filter corresponding to arguments
      $oFilter = new PHP_APE_Data_Filter();
      $roArgumentSet = $oView->useArgumentSet();
      $amKeys = $roArgumentSet->getElementsKeys();
      if( $roArgumentSet instanceof PHP_APE_Data_isMetaDataSet )
        $amKeys = array_diff( $amKeys, $roArgumentSet->getElementsKeysPerMeta( PHP_APE_Data_Argument::Feature_ExcludeInCall ) );
      foreach( $amKeys as $mKey )
      {
        $roArgument = $roArgumentSet->useElement( $mKey );
        $mID = $roArgument->getID();
        $oFilterCriteria = new PHP_APE_Data_FilterCriteria( $mID, new PHP_APE_Type_String( $roArgument->useContent()->getValue() ) );
        $oFilter->setElement( $oFilterCriteria );
      }

      // Build view
      $sViewClass = get_class( $oView );
      $oView_SUB = new $sViewClass(); // we create a sub-view, where...
      $oView_SUB->unsetArgumentSet(); // ... we don't want arguments any longer...
      $sView = $this->formatClause( PHP_APE_Database_SQL_Any::Select, '*' );
      $sView .= $this->formatClause( PHP_APE_Database_SQL_Any::From, $this->formatView( $oView_SUB, false ) );
      $sView .= $this->formatClause( PHP_APE_Database_SQL_Any::From_As, $this->formatViewAs( $oView_SUB ) );
      $sView .= $this->formatClause( PHP_APE_Database_SQL_Any::Where, $this->formatFilter( $oView_SUB, $oFilter, false ) );
      $sView = '('.$sView.')';
      if( $bAlias ) $sView .= $this->formatClause( PHP_APE_Database_SQL_Any::From_As, $this->formatViewAs( $oView ) );
    }
    else
    {
      $sView = $oView->getNamespace();
      $sView .= ( $sView ? '.' : null ).$oView->getExpression();
      if( $bAlias ) $sView .= $this->formatClause( PHP_APE_Database_SQL_Any::From_As, $this->formatViewAs( $oView ) );
    }
    return $sView;
  }

}
