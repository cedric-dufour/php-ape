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

/** ANSI-92 SQL handling class
 *
 * @package PHP_APE_Database
 * @subpackage SQL
 */
abstract class PHP_APE_Database_SQL_ANSI92
extends PHP_APE_Database_SQL_Any
{

  /*
   * METHODS: decode/encode - OVERRIDE
   ********************************************************************************/

  public function decodeData( $sInput )
  {
    return $sInput;
  }

  public function encodeData( $sInput )
  {
    return addSlashes( $sInput );
  }


  /*
   * METHODS: parse/format - OVERRIDE
   ********************************************************************************/

  public function parseData( PHP_APE_Type_Scalar $oData, $mValue, $bStrict = true )
  {
    // Check input
    if( is_null( $mValue ) and $bStrict ) return null;

    // Remove casting information
    if( !is_numeric( $mValue ) ) $mValue = preg_replace( '/(CAST\(\s*)?\'?(.*?)\'?(\s+AS\s+\w+\s*\))?/Aim', '${2}', $mValue );

    // Parse data
    if( $oData instanceof PHP_APE_Type_Date ) $oData->setValueParsed( $this->decodeData( $mValue ), $bStrict, PHP_APE_Type_Date::Format_ISO );
    elseif( $oData instanceof PHP_APE_Type_Time ) $oData->setValueParsed( $this->decodeData( $mValue ), $bStrict, PHP_APE_Type_Time::Format_ISO );
    elseif( $oData instanceof PHP_APE_Type_Timestamp )
    {
      if( $oData instanceof PHP_APE_Type_Angle )
      { // angle data are stored as timestamp
        $oData_aux = new PHP_APE_Type_Timestamp();
        $oData_aux->setValueParsed( $this->decodeData( $mValue ), $bStrict, PHP_APE_Type_Timestamp::Format_ISO );
        $oData->setValue( $oData_aux->getValue() - PHP_APE_Type_Angle::Value_Timestamp_Offset );
      } else
        $oData->setValueParsed( $this->decodeData( $mValue ), $bStrict, PHP_APE_Type_Timestamp::Format_ISO );
    }
    else
      $oData->setValueParsed( $this->decodeData( $mValue, false ), $bStrict );
  }

  public function formatData( PHP_APE_Type_Scalar $oData, $mIfNull = null )
  {
    // Check null
    if( $oData->isNull() and is_null( $mIfNull ) )
      return 'null';

    // Format data
    if( $oData instanceof PHP_APE_Type_Boolean )
    {
      if( !is_null( $mIfNull ) )
      {
        $oNull = clone( $oData );
        $oNull->setValue( $mIfNull );
        $mIfNull = $oNull->getValueFormatted( 'null', PHP_APE_Type_Boolean::Format_Boolean );
      }

      return $this->formatCast( $this->encodeData( $oData->getValueFormatted( $mIfNull, PHP_APE_Type_Boolean::Format_Boolean ) ), $oData );
    }

    if( $oData instanceof PHP_APE_Type_Integer )
    {
      if( !is_null( $mIfNull ) )
      {
        $oNull = clone( $oData );
        $oNull->setValue( $mIfNull );
        $mIfNull = $oNull->getValueFormatted( 'null', PHP_APE_Type_Numeric::Format_Raw );
      }

      if( $oData instanceof PHP_APE_Type_Int8 )
        return $this->formatCast( $this->encodeData( $oData->getValueFormatted( $mIfNull, PHP_APE_Type_Numeric::Format_Raw ) ), $oData );

      if( $oData instanceof PHP_APE_Type_Int2 )
        return $this->formatCast( $this->encodeData( $oData->getValueFormatted( $mIfNull, PHP_APE_Type_Numeric::Format_Raw ) ), $oData );

      if( $oData instanceof PHP_APE_Type_Byte )
        return $this->formatCast( $this->encodeData( $oData->getValueFormatted( $mIfNull, PHP_APE_Type_Numeric::Format_Raw ) ), $oData );

      return $this->formatCast( $this->encodeData( $oData->getValueFormatted( $mIfNull, PHP_APE_Type_Numeric::Format_Raw ) ), $oData );
    }

    if( $oData instanceof PHP_APE_Type_Float )
    {
      if( !is_null( $mIfNull ) )
      {
        $oNull = clone( $oData );
        $oNull->setValue( $mIfNull );
        $mIfNull = $oNull->getValueFormatted( 'null', PHP_APE_Type_Numeric::Format_Raw );
      }

      if( $oData instanceof PHP_APE_Type_Float4 )
        return $this->formatCast( $this->encodeData( $oData->getValueFormatted( $mIfNull, PHP_APE_Type_Numeric::Format_Raw ) ), $oData );

      if( $oData instanceof PHP_APE_Type_Decimal )
        return $this->formatCast( $this->encodeData( $oData->getValueFormatted( $mIfNull, PHP_APE_Type_Numeric::Format_Raw ) ), $oData );

      if( $oData instanceof PHP_APE_Type_Money )
        return $this->formatCast( $this->encodeData( $oData->getValueFormatted( $mIfNull, PHP_APE_Type_Numeric::Format_Raw ) ), $oData );

      return $this->formatCast( $this->encodeData( $oData->getValueFormatted( $mIfNull, PHP_APE_Type_Numeric::Format_Raw ) ), $oData );
    }

    if( $oData instanceof PHP_APE_Type_Date )
    {
      if( !is_null( $mIfNull ) )
      {
        $oNull = clone( $oData );
        $oNull->setValue( $mIfNull );
        $mIfNull = $oNull->getValueFormatted( 'null', PHP_APE_Type_Date::Format_ISO );
      }

      return $this->formatCast( $this->encodeData( $oData->getValueFormatted( $mIfNull, PHP_APE_Type_Date::Format_ISO ) ), $oData );
    }

    if( $oData instanceof PHP_APE_Type_Time )
    {
      if( !is_null( $mIfNull ) )
      {
        $oNull = clone( $oData );
        $oNull->setValue( $mIfNull );
        $mIfNull = $oNull->getValueFormatted( 'null', PHP_APE_Type_Time::Format_ISO );
      }

      return $this->formatCast( $this->encodeData( $oData->getValueFormatted( $mIfNull, PHP_APE_Type_Time::Format_ISO ) ), $oData );
    }

    if( $oData instanceof PHP_APE_Type_Timestamp )
    {
      if( $oData instanceof PHP_APE_Type_Angle )
      { // angle data are stored as timestamp
        $oData_TMP = new PHP_APE_Type_Timestamp( $oData->getValue() + PHP_APE_Type_Angle::Value_Timestamp_Offset  );
      }
      else
        $oData_TMP =& $oData;

      if( !is_null( $mIfNull ) )
      {
        $oNull = clone( $oData_TMP );
        $oNull->setValue( $mIfNull );
        $mIfNull = $oNull->getValueFormatted( 'null', PHP_APE_Type_Time::Format_ISO );
      }

      return $this->formatCast( $this->encodeData( $oData_TMP->getValueFormatted( $mIfNull, PHP_APE_Type_Timestamp::Format_ISO ) ), $oData_TMP );
    }

    if( $oData instanceof PHP_APE_Type_String )
    {
      if( $oData instanceof PHP_APE_Type_Password )
      { // password are obfuscated 
        $oData_TMP = new PHP_APE_Type_String( $oData->getValue()  );
      }
      else
        $oData_TMP =& $oData;

      if( !is_null( $mIfNull ) )
      {
        $oNull = clone( $oData_TMP );
        $oNull->setValue( $mIfNull );
        $mIfNull = $oNull->getValueFormatted( 'null' );
      }

      return $this->formatCast( $this->encodeData( $oData_TMP->getValueFormatted( $mIfNull ) ), $oData_TMP );
    }

    throw new PHP_APE_Database_SQL_Exception( __METHOD__, 'Invalid/unsupported data; Class: '.get_class( $oData ) );
  }

  public function formatCast( $sExpression, PHP_APE_Type_Scalar $oData )
  {
    if( $oData instanceof PHP_APE_Type_Boolean )
      return $sExpression;

    if( $oData instanceof PHP_APE_Type_Integer )
      return $sExpression;

    if( $oData instanceof PHP_APE_Type_Float )
      return $sExpression;

    if( $oData instanceof PHP_APE_Type_Date )
      return 'CAST(\''.$sExpression.'\' AS date)';

    if( $oData instanceof PHP_APE_Type_Time )
      return 'CAST(\''.$sExpression.'\' AS time)';

    if( $oData instanceof PHP_APE_Type_Timestamp )
    {
      if( $oData instanceof PHP_APE_Type_Angle )
        throw new PHP_APE_Database_SQL_Exception( __METHOD__, 'Invalid/unsupported data/type; Data/Type: '.get_class( $oData ) );

      return 'CAST(\''.$sExpression.'\' AS datetime)';
    }

    if( $oData instanceof PHP_APE_Type_String )
      return '\''.$sExpression.'\'';

    throw new PHP_APE_Database_SQL_Exception( __METHOD__, 'Invalid/unsupported data/type; Data/Type: '.get_class( $oData ) );
  }

  public function formatClause( $iClause, $sExpression )
  {
    switch( (integer)$iClause )
    {

    case PHP_APE_Database_SQL_Any::Select:
      return 'SELECT '.$sExpression;

    case PHP_APE_Database_SQL_Any::Select_Distinct:
      return 'SELECT DISTINCT '.$sExpression;

    case PHP_APE_Database_SQL_Any::Select_As:
      return ' AS '.$sExpression;

    case PHP_APE_Database_SQL_Any::Execute_Function:
      return 'SELECT '.$sExpression;

    case PHP_APE_Database_SQL_Any::Call_Procedure:
      return 'CALL '.$sExpression;

    case PHP_APE_Database_SQL_Any::From:
      return ' FROM '.$sExpression;

    case PHP_APE_Database_SQL_Any::From_As:
      return ' AS '.$sExpression;

    case PHP_APE_Database_SQL_Any::Join:
      return ' INNER JOIN '.$sExpression;

    case PHP_APE_Database_SQL_Any::Join_Left:
      return ' LEFT JOIN '.$sExpression;

    case PHP_APE_Database_SQL_Any::Join_Right:
      return ' RIGHT JOIN '.$sExpression;

    case PHP_APE_Database_SQL_Any::Join_On:
      return ' ON '.$sExpression;

    case PHP_APE_Database_SQL_Any::Where:
      return ' WHERE '.$sExpression;

    case PHP_APE_Database_SQL_Any::Where_In:
      return ' IN ('.$sExpression.')';

    case PHP_APE_Database_SQL_Any::Group:
      return ' GROUP BY '.$sExpression;

    case PHP_APE_Database_SQL_Any::Having:
      return ' HAVING '.$sExpression;

    case PHP_APE_Database_SQL_Any::Order:
      return ' ORDER BY '.$sExpression;

    case PHP_APE_Database_SQL_Any::Order_Asc:
      return ' '.$sExpression.' ASC';

    case PHP_APE_Database_SQL_Any::Order_Desc:
      return ' '.$sExpression.' DESC';

    case PHP_APE_Database_SQL_Any::Limit:
      return ' LIMIT '.$sExpression;

    case PHP_APE_Database_SQL_Any::Offset:
      return ' OFFSET '.$sExpression;

    case PHP_APE_Database_SQL_Any::Union:
      return ' UNION '.$sExpression;

    case PHP_APE_Database_SQL_Any::Intersect:
      return ' INTERSECT '.$sExpression;

    case PHP_APE_Database_SQL_Any::Except:
      return ' EXCEPT '.$sExpression;

    case PHP_APE_Database_SQL_Any::Aggregate_Count:
      if( count(explode(',',$sExpression))!=1 )
        return 'COUNT(*)';
      else
        return'COUNT('.$sExpression.')';

    }

    throw new PHP_APE_Database_SQL_Exception( __METHOD__, 'Invalid/unsupported clause; Code: '.$iClause );
  }

  public function formatLogicalOperator( $iOperator, $sExpression_PRIMARY, $sExpression_SECONDARY = null, PHP_APE_Type_Scalar $oData = null )
  {
    switch( (integer)$iOperator & PHP_APE_Data_LogicalOperator::Mask )
    {

    case PHP_APE_Data_LogicalOperator::NNot:
      return 'NOT('.$sExpression_PRIMARY.')';

    case PHP_APE_Data_LogicalOperator::AAnd:
      return $sExpression_PRIMARY.' AND '.$sExpression_SECONDARY;

    case PHP_APE_Data_LogicalOperator::OOr:
      return $sExpression_PRIMARY.' OR '.$sExpression_SECONDARY;

    }

    throw new PHP_APE_Database_SQL_Exception( __METHOD__, 'Invalid/unsupported clause; Code: '.$iOperator );
  }

  public function formatBasicMathOperator( $iOperator, $sExpression_PRIMARY, $sExpression_SECONDARY, PHP_APE_Type_Scalar $oData = null )
  {
    switch( (integer)$iOperator & PHP_APE_Data_BasicMathOperator::Mask )
    {

    case PHP_APE_Data_BasicMathOperator::Addition:
      if( $oData instanceof PHP_APE_Type_Boolean )
        return $sExpression_PRIMARY.' AND '.$sExpression_SECONDARY;

      if( $oData instanceof PHP_APE_Type_String )
        return $sExpression_PRIMARY.'||'.$sExpression_SECONDARY;

      return $sExpression_PRIMARY.'+'.$sExpression_SECONDARY;

    case PHP_APE_Data_BasicMathOperator::Soustraction:
      if( !is_null($oData) and !($oData instanceof PHP_APE_Type_Numeric) )
        throw new PHP_APE_Database_SQL_Exception( __METHOD__, 'Operator not supported for data/type; Operator: '.PHP_APE_Data_BasicMathOperator::toString($iOperator).'; Data/Type: '.get_class( $oData ) );

      return $sExpression_PRIMARY.'-'.$sExpression_SECONDARY;

    case PHP_APE_Data_BasicMathOperator::Multiplication:
      if( !is_null($oData) and !($oData instanceof PHP_APE_Type_Numeric) )
        throw new PHP_APE_Database_SQL_Exception( __METHOD__, 'Operator not supported for data/type; Operator: '.PHP_APE_Data_BasicMathOperator::toString($iOperator).'; Data/Type: '.get_class( $oData ) );

      return $sExpression_PRIMARY.'*'.$sExpression_SECONDARY;

    case PHP_APE_Data_BasicMathOperator::Division:
      if( !is_null($oData) and !($oData instanceof PHP_APE_Type_Numeric) )
        throw new PHP_APE_Database_SQL_Exception( __METHOD__, 'Operator not supported for data/type; Operator: '.PHP_APE_Data_BasicMathOperator::toString($iOperator).'; Data/Type: '.get_class( $oData ) );

      return $sExpression_PRIMARY.'/'.$sExpression_SECONDARY;

    }

    throw new PHP_APE_Database_SQL_Exception( __METHOD__, 'Invalid/unsupported operator; Code: '.$iOperator );
  }

  public function formatComparisonOperator( $iOperator, $sExpression_PRIMARY, $sExpression_SECONDARY, PHP_APE_Type_Scalar $oData = null )
  {
    switch( (integer)$iOperator & PHP_APE_Data_ComparisonOperator::Mask )
    {

    case PHP_APE_Data_ComparisonOperator::Equal:
      if( strcasecmp( $sExpression_PRIMARY, 'null' ) == 0 )
        return $sExpression_SECONDARY.' IS NULL';
      if( strcasecmp( $sExpression_SECONDARY, 'null' ) == 0 )
        return $sExpression_PRIMARY.' IS NULL';
      return $sExpression_PRIMARY.'='.$sExpression_SECONDARY;

    case PHP_APE_Data_ComparisonOperator::NotEqual:
      if( strcasecmp( $sExpression_PRIMARY, 'null' ) == 0 )
        return $sExpression_SECONDARY.' IS NOT NULL';
      if( strcasecmp( $sExpression_SECONDARY, 'null' ) == 0 )
        return $sExpression_PRIMARY.' IS NOT NULL';
      return $sExpression_PRIMARY.'<>'.$sExpression_SECONDARY;

    case PHP_APE_Data_ComparisonOperator::SmallerOrEqual:
      if( $oData instanceof PHP_APE_Type_Boolean )
        return $sExpression_PRIMARY.'='.$sExpression_SECONDARY;

      return $sExpression_PRIMARY.'<='.$sExpression_SECONDARY;

    case PHP_APE_Data_ComparisonOperator::Smaller:
      if( $oData instanceof PHP_APE_Type_Boolean )
        return $sExpression_PRIMARY.'!='.$sExpression_SECONDARY;

      return $sExpression_PRIMARY.'<'.$sExpression_SECONDARY;

    case PHP_APE_Data_ComparisonOperator::BiggerOrEqual:
      if( $oData instanceof PHP_APE_Type_Boolean )
        return $sExpression_PRIMARY.'='.$sExpression_SECONDARY;

      return $sExpression_PRIMARY.'>='.$sExpression_SECONDARY;

    case PHP_APE_Data_ComparisonOperator::Bigger:
      if( $oData instanceof PHP_APE_Type_Boolean )
        return $sExpression_PRIMARY.'!='.$sExpression_SECONDARY;

      return $sExpression_PRIMARY.'>'.$sExpression_SECONDARY;

    case PHP_APE_Data_ComparisonOperator::Proportional:
      if( $oData instanceof PHP_APE_Type_Boolean )
        return $sExpression_PRIMARY.'='.$sExpression_SECONDARY;

      if( $oData instanceof PHP_APE_Type_String )
        return 'lower('.$sExpression_PRIMARY.') LIKE lower('.$this->encodeLikeExpression($sExpression_SECONDARY).')';

      return $sExpression_PRIMARY.' LIKE '.$this->encodeLikeExpression($sExpression_SECONDARY);

    case PHP_APE_Data_ComparisonOperator::Included:
      if( $oData instanceof PHP_APE_Type_String )
        return 'lower('.$sExpression_PRIMARY.') LIKE concat(\'%\',lower('.$this->encodeLikeExpression($sExpression_SECONDARY).'))';

      return $sExpression_PRIMARY.' IN ('.$sExpression_SECONDARY.')';

    }

    throw new PHP_APE_Database_SQL_Exception( __METHOD__, 'Invalid/unsupported operator; Code: '.$iOperator );
  }

  public function formatField( PHP_APE_Database_View $oView, $mID, $bAlias = false )
  {
    $oField = $oView->getElement( $mID );
    $sField = $oView->getID().'.'.$oField->getExpression();
    if( $bAlias ) $sField .= $this->formatClause( PHP_APE_Database_SQL_Any::Select_As, $this->formatFieldAs( $oView, $mID ) );
    return $sField;
  }

  public function formatFieldAs( PHP_APE_Database_View $oView, $mID )
  {
    return $oView->getID().'_'.$mID;
  }

  public function formatView( PHP_APE_Database_View $oView, $bAlias = false )
  {
    if( $oView->hasArgumentSet() )
      throw new PHP_APE_Database_SQL_Exception( __METHOD__, 'Argumented view is not supported by standard ANSI92 databases' );
    $sView = $oView->getNamespace();
    $sView .= ( $sView ? '.' : null ).$oView->getExpression();
    if( $bAlias ) $sView .= $this->formatClause( PHP_APE_Database_SQL_Any::From_As, $this->formatViewAs( $oView ) );
    return $sView;
  }

  public function formatViewAs( PHP_APE_Database_View $oView )
  {
    return $oView->getID();
  }

  public function formatFunction( PHP_APE_Database_Function $oFunction, $bAlias = false )
  {
    $sFunction = $oFunction->getNamespace();
    $sFunction .= ( $sFunction ? '.' : null ).$oFunction->getExpression();
    $sArguments = null;
    if( $oFunction->hasArgumentSet() )
    {
      $roArgumentSet = $oFunction->useArgumentSet();
      $amKeys = $roArgumentSet->getElementsKeys();
      if( $roArgumentSet instanceof PHP_APE_Data_isMetaDataSet )
        $amKeys = array_diff( $amKeys, $roArgumentSet->getElementsKeysPerMeta( PHP_APE_Data_Argument::Feature_ExcludeInCall ) );
      foreach( $amKeys as $mKey )
        $sArguments .= ( strlen( trim( $sArguments ) ) ? ', ' : null ).$this->formatData( $roArgumentSet->useElement( $mKey )->useContent() );
    }
    $sFunction .= '( '.$sArguments.' )';
    if( $bAlias ) $sFunction .= $this->formatClause( PHP_APE_Database_SQL_Any::From_As, $this->formatFunctionAs( $oFunction ) );
    return $sFunction;
  }

  public function formatFunctionAs( PHP_APE_Database_Function $oFunction )
  {
    return $oFunction->getID();
  }


  /*
   * METHODS: utilities
   ********************************************************************************/

  /** Encodes <SAMP>LIKE</SAMP> expression
   *
   * <P><B>SYNOPSIS:</B> This function translates commonly used wildcards ('<SAMP>*</SAMP>' and '<SAMP>?</SAMP>')
   * to their SQL <SAMP>LIKE</SAMP> equivalent ('<SAMP>%</SAMP>' and '<SAMP>_</SAMP>'), escaping the latter to allow their
   * use in the supplied expression.</P>
   *
   * @param string $sExpression SQL expression
   * @return string
   */
  public function encodeLikeExpression( $sExpression )
  {

    // Look for string and subsitute special characters within
    $sExpression = (string)$sExpression;
    $sOutput = null; $bString = false; $bEscape = false;
    for( $c = 0, $l = strlen( $sExpression ); $c < $l; $c++ )
    {
      $sChar = $sExpression[$c];

      if( $bEscape )
      {
        $sOutput .= $sChar;
        $bEscape = false;
      }
      else
      {
        switch( $sChar )
        {

        case '\\':
          $sOutput .= $sChar;
          $bEscape = true;
          break;

        case '\'':
          if( $bString ) $sOutput .= '%'.$sChar;
          else $sOutput .= $sChar;
          $bString = !$bString;
          break;

        case '_':
        case '%':
          if( $bString ) $sOutput .= '\\\\'.$sChar;
          else $sOutput .= $sChar;
          break;

        case '?':
          if( $bString ) $sOutput .= '_';
          else $sOutput .= $sChar;
          break;

        case '*':
          if( $bString ) $sOutput .= '%';
          else $sOutput .= $sChar;
          break;

        default:
          $sOutput .= $sChar;

        }
      }
    }

    // END
    return $sOutput;

  }
}
