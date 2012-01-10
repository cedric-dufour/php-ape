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

/** Core SQL language handling class
 *
 * @package PHP_APE_Database
 * @subpackage SQL
 */
abstract class PHP_APE_Database_SQL_Any
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** Clause: <SAMP>SELECT</SAMP>
   * @var integer */
  const Select = 10;

  /** Clause: <SAMP>SELECT DISTINCT</SAMP>
   * @var integer */
  const Select_Distinct = 11;

  /** Clause: <SAMP>AS</SAMP> (within <SAMP>SELECT</SAMP>) clause
   * @var integer */
  const Select_As = 12;

  /** Clause: <SAMP>EXECUTE</SAMP>
   * @var integer */
  const Execute_Function = 15;

  /** Clause: <SAMP>CALL</SAMP>
   * @var integer */
  const Call_Procedure = 16;

  /** Clause: <SAMP>FROM</SAMP>
   * @var integer */
  const From = 20;

  /** Clause: <SAMP>AS</SAMP> (within <SAMP>FROM</SAMP>) clause
   * @var integer */
  const From_As = 21;

  /** Clause: <SAMP>JOIN</SAMP>
   * @var integer */
  const Join = 30;

  /** Clause: <SAMP>LEFT JOIN</SAMP>
   * @var integer */
  const Join_Left = 31;

  /** Clause: <SAMP>RIGHT JOIN</SAMP>
   * @var integer */
  const Join_Right = 32;

  /** Clause: <SAMP>ON</SAMP> (within <SAMP>JOIN</SAMP>) clause
   * @var integer */
  const Join_On = 33;

  /** Clause: <SAMP>WHERE</SAMP>
   * @var integer */
  const Where = 40;

  /** Clause: <SAMP>IN</SAMP> (within <SAMP>WHERE</SAMP>) clause
   * @var integer */
  const Where_In = 41;

  /** Clause: <SAMP>GROUP BY</SAMP>
   * @var integer */
  const Group = 50;

  /** Clause: <SAMP>HAVING</SAMP>
   * @var integer */
  const Having = 60;

  /** Clause: <SAMP>ORDER</SAMP>
   * @var integer */
  const Order = 70;

  /** Clause: <SAMP>(ORDER ...) ASC</SAMP>
   * @var integer */
  const Order_Asc = 71;

  /** Clause: <SAMP>(ORDER ...) DESC</SAMP>
   * @var integer */
  const Order_Desc = 72;

  /** Clause: <SAMP>LIMIT</SAMP>
   * @var integer */
  const Limit = 80;

  /** Clause: <SAMP>OFFSET</SAMP>
   * @var integer */
  const Offset = 90;

  /** Clause: <SAMP>UNION</SAMP>
   * @var integer */
  const Union = 100;

  /** Clause: <SAMP>INTERSECT</SAMP>
   * @var integer */
  const Intersect = 101;

  /** Clause: <SAMP>EXCEPT</SAMP>
   * @var integer */
  const Except = 102;

  /** Clause: <SAMP>COUNT</SAMP>
   * @var integer */
  const Aggregate_Count = 1000;


  /*
   * METHODS: decode/encode
   ********************************************************************************/

  /** Decodes characters according the SQL-specific encoding rules
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Database_SQL_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * <P><B>EXAMPLE:</B><P>
   * <CODE>
   * <?php
   * // Load APE
   * require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
   *
   * // SQL object
   * $oSQL = new PHP_APE_Database_SQL_PostgreSQL();
   *
   * // Decode data
   * echo $oSQL->decodeData( 'Cédric (with an accented "e")' );
   * // Output: (string)'Cédric (with an accented "e")'
   * ?>
   * </CODE>
   *
   * @param string $sInput Data to decode
   * @param mixed $mParameters,... Additional optional parameters
   * @return string
   */
  abstract public function decodeData( $sInput );

  /** Encodes characters according the SQL-specific encoding rules
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Database_SQL_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>SHOULD be overridden</B>.</P>
   *
   * <P><B>EXAMPLE:</B><P>
   * <CODE>
   * <?php
   * // Load APE
   * require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
   *
   * // SQL object
   * $oSQL = new PHP_APE_Database_SQL_PostgreSQL();
   *
   * // Encode data
   * echo $oSQL->encodeData( 'Cédric (with an accented "e")' );
   * // Output: 'Cédric (with an accented "e")'
   * ?>
   * </CODE>
   *
   * @param string $sInput Data to encode
   * @param mixed $mParameters,... Additional optional parameters
   * @return string
   */
  abstract public function encodeData( $sInput );


  /*
   * METHODS: parse/format
   ********************************************************************************/

  /** Parses the given data from the given value, according to their type and the SQL-specific encoding rules
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Database_SQL_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * <P><B>EXAMPLE:</B><P>
   * <CODE>
   * <?php
   * // Load APE
   * require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
   *
   * // SQL object
   * $oSQL = new PHP_APE_Database_SQL_PostgreSQL();
   *
   * // Data objects
   * $oString = new PHP_APE_Type_String();
   * $oTimestamp = new PHP_APE_Type_Timestamp();
   *
   * // Parse data
   * $oSQL->parseData( $oString, 'Cédric (with an accented "e")' );
   * // Data value: (string)'Cédric (with an accented "e")'
   * $oSQL->parseData( $oTimestamp, '2005-12-31 23:59:59.999' );
   * // Data value: (float)1136069999.999
   * ?>
   * </CODE>
   *
   * @param PHP_APE_Type_Scalar $oData Destination data object
   * @param mixed $mValue Input value
   * @param boolean $bStrict Strict data parsing (<SAMP>null</SAMP> remains <SAMP>null</SAMP>)
   */
  abstract public function parseData( PHP_APE_Type_Scalar $oData, $mValue, $bStrict = true );

  /** Formats the given data, according to their type and the SQL-specific encoding rules
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Database_SQL_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * <P><B>EXAMPLE:</B><P>
   * <CODE>
   * <?php
   * // Load APE
   * require_once( $_SERVER['PHP_APE_ROOT'].'/load.php');
   *
   * // SQL object
   * $oSQL = new PHP_APE_Database_SQL_PostgreSQL();
   *
   * // Data objects
   * $oString = new PHP_APE_Type_String( 'Cédric (with an accented "e")' );
   * $oTimestamp = new PHP_APE_Type_Timestamp( 1136069999.999 ); // 2005-12-31 23:59:59.999
   *
   * // Format data
   * echo $oSQL->formatData( $oString );
   * // Output: 'CAST( 'Cédric (with an accented "e")' AS varchar )'
   * echo $oSQL->formatData( $oTimestamp );
   * // Output: 'CAST( '2005-12-31 23:59:59.999' AS timestamp )'
   * ?>
   * </CODE>
   *
   * @param PHP_APE_Type_Scalar $oData Input data
   * @param mixed $mIfNull Default output for <SAMP>null</SAMP> input
   * @return string
   */
  abstract public function formatData( PHP_APE_Type_Scalar $oData, $mIfNull = null );

  /** Formats the given expression as being casted to the given data type
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Database_SQL_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * <P><B>EXAMPLE:</B><P>
   * <CODE>
   * <?php
   * // Load APE
   * require_once( $_SERVER['PHP_APE_ROOT'].'/load.php');
   *
   * // SQL object
   * $oSQL = new PHP_APE_Database_SQL_PostgreSQL();
   *
   * // Cast expression
   * $oSQL->formatCast( 1234, new PHP_APE_Type_Int4() );
   * // Output: CAST( 1234 AS int4 )
   * $oSQL->formatCast( 'COUNT(*)' , new PHP_APE_Type_String() );
   * // Output: CAST( COUNT(*) AS varchar )
   * ?>
   * </CODE>
   *
   * @param string $sExpression SQL Expression
   * @param PHP_APE_Type_Scalar $oData Data model (to retrieve type from)
   * @return string
   */
  abstract public function formatCast( $sExpression, PHP_APE_Type_Scalar $oData );

  /** Formats the given SQL expression as the given SQL clause
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Database_SQL_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * <P><B>EXAMPLE:</B><P>
   * <CODE>
   * <?php
   * // Load APE
   * require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
   *
   * // SQL object
   * $oSQL = new PHP_APE_Database_SQL_PostgreSQL();
   *
   * // Format clause
   * echo $oSQL::formatClause( 'myExpression', PHP_APE_Database_SQL_Any::Select );
   * // Output: 'SELECT myExpression'
   * ?>
   * </CODE>
   *
   * @param integer $iClause Clause code (see class constants)
   * @param string $sExpression SQL expression
   * @return string
   */
  abstract public function formatClause( $iClause, $sExpression );

  /** Formats the given SQL expression using the given logical operator
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Database_SQL_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * <P><B>EXAMPLE:</B><P>
   * <CODE>
   * <?php
   * // Load APE
   * require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
   *
   * // SQL object
   * $oSQL = new PHP_APE_Database_SQL_PostgreSQL();
   *
   * // Format operator
   * echo $oSQL::formatLogicalOperator( PHP_APE_Data_LogicalOperator::NNot, 'myPrimaryExpression' );
   * // Output: 'NOT myPrimaryExpression'
   * echo $oSQL::formatLogicalOperator( PHP_APE_Data_LogicalOperator::AAnd, 'myPrimaryExpression', 'mySecondaryExpression' );
   * // Output: 'myPrimaryExpression AND mySecondaryExpression'
   * ?>
   * </CODE>
   *
   * @param integer $iOperator Operator code
   * @param string $sExpression_PRIMARY Operator's 'primary' expression
   * @param string $sExpression_SECONDARY Operator's 'secondary' SQL expression
   * @param PHP_APE_Type_Scalar $oData Data model (to retrieve type from)
   * @return string
   * @see PHP_APE_Data_LogicalOperator
   */
  abstract public function formatLogicalOperator( $iOperator, $sExpression_PRIMARY, $sExpression_SECONDARY = null, PHP_APE_Type_Scalar $oData = null );

  /** Formats the given SQL expression using the given basic mathematical operator
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Database_SQL_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * <P><B>EXAMPLE:</B><P>
   * <CODE>
   * <?php
   * // Load APE
   * require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
   *
   * // SQL object
   * $oSQL = new PHP_APE_Database_SQL_PostgreSQL();
   *
   * // Format operator
   * echo $oSQL::formatBasicMathOperator( PHP_APE_Data_BasicMathOperator::Addition, 'myPrimaryExpression', 'mySecondaryExpression' );
   * // Output: 'myPrimaryExpression + mySecondaryExpression'
   * echo $oSQL::formatBasicMathOperator( PHP_APE_Data_BasicMathOperator::Addition, 'myPrimaryExpression', 'mySecondaryExpression', new PHP_APE_Type_String() );
   * // Output: 'myPrimaryExpression || mySecondaryExpression'
   * ?>
   * </CODE>
   *
   * @param integer $iOperator Operator code
   * @param string $sExpression_PRIMARY Operator's 'primary' expression
   * @param string $sExpression_SECONDARY Operator's 'secondary' SQL expression
   * @param PHP_APE_Type_Scalar $oData Data model (to retrieve type from)
   * @return string
   * @see PHP_APE_Data_BasicMathOperator
   */
  abstract public function formatBasicMathOperator( $iOperator, $sExpression_PRIMARY, $sExpression_SECONDARY, PHP_APE_Type_Scalar $oData = null );

  /** Formats the given SQL expression using the given comparison operator
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Database_SQL_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * <P><B>EXAMPLE:</B><P>
   * <CODE>
   * <?php
   * // Load APE
   * require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
   *
   * // SQL object
   * $oSQL = new PHP_APE_Database_SQL_PostgreSQL();
   *
   * // Format operator
   * echo $oSQL::formatComparisonOperator( PHP_APE_Data_ComparisonOperator::Equal, 'myPrimaryExpression', 'mySecondaryExpression' );
   * // Output: 'myPrimaryExpression = mySecondaryExpression'
   * echo $oSQL::formatComparisonOperator( PHP_APE_Data_ComparisonOperator::Proportional, 'myPrimaryExpression', 'mySecondaryExpression', new PHP_APE_Type_String() );
   * // Output: 'lower(myPrimaryExpression) LIKE lower(mySecondaryExpression)'
   * ?>
   * </CODE>
   *
   * @param integer $iOperator Operator code
   * @param string $sExpression_PRIMARY Operator's 'primary' expression
   * @param string $sExpression_SECONDARY Operator's 'secondary' SQL expression
   * @param PHP_APE_Type_Scalar $oData Data model (to retrieve type from)
   * @return string
   * @see PHP_APE_Data_ComparisonOperator
   */
  abstract public function formatComparisonOperator( $iOperator, $sExpression_PRIMARY, $sExpression_SECONDARY, PHP_APE_Type_Scalar $oData = null );

  /** Formats the given SQL field (selectable column/expression)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Database_SQL_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * @param PHP_APE_Database_View $oView SQL view
   * @param mixed $mID Field identifier (ID)
   * @param boolean $bAlias Add field alias
   * @return string
   */
  abstract public function formatField( PHP_APE_Database_View $oView, $mID, $bAlias = false );

  /** Formats the given SQL field (selectable column/expression) alias
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Database_SQL_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * @param PHP_APE_Database_View $oView SQL view
   * @param mixed $mID Field identifier (ID)
   * @return string
   */
  abstract public function formatFieldAs( PHP_APE_Database_View $oView, $mID );

  /** Formats the given SQL view
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Database_SQL_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * @param PHP_APE_Database_View $oView SQL view
   * @param boolean $bAlias Add view alias
   * @return string
   */
  abstract public function formatView( PHP_APE_Database_View $oView, $bAlias = false );

  /** Formats the given SQL view alias
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Database_SQL_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * @param PHP_APE_Database_View $oView SQL view
   * @return string
   */
  abstract public function formatViewAs( PHP_APE_Database_View $oView );

  /** Formats the given SQL function
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Database_SQL_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * @param PHP_APE_Database_Function $oFunction SQL function
   * @param boolean $bAlias Add function alias
   * @return string
   */
  abstract public function formatFunction( PHP_APE_Database_Function $oFunction, $bAlias = false );

  /** Formats the given SQL function alias
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Database_SQL_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * @param PHP_APE_Database_Function $oFunction SQL function
   * @return string
   */
  abstract public function formatFunctionAs( PHP_APE_Database_Function $oFunction );

  /** Formats the SQL <SAMP>WHERE</SAMP> clause corresponding to the given filter
   *
   * <P><B>SYNOPSIS:</B> This function will automatically construct the <SAMP>WHERE</SAMP> clause corresponding
   * to the given filter, matching the filter's criteria identifiers (IDs/keys) to those of the SQL fields identifiers (IDs/keys),
   * whenever possible (non-matching identifiers are ignored).</P>
   * <P><B>RETURNS:</B> The formatted clause, <B>excluding</B> the <SAMP>WHERE</SAMP> keyword.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Database_SQL_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param PHP_APE_Database_View $oView SQL view
   * @param PHP_APE_Data_Filter $oFilter Data filter
   * @param boolean $bLogicalOR Link filter fields with logical OR (instead of logical AND)
   * @param mixed $mFieldKey Field key (retrieved from the filter itself if <SAMP>null</SAMP>)
   * @return string
   * @see PHP_APE_Data_Filter
   */
  final public function formatFilter( PHP_APE_Database_View $oView, PHP_APE_Data_Filter $oFilter, $bLogicalOR = false, $mFieldKey = null )
  {
    $sSQL = null;

    // Loop through filter's criteria
    $amCriteriasKeys = $oFilter->getElementsKeys();
    $mFieldKey_Original = $mFieldKey;
    foreach( $amCriteriasKeys as $mCriteriaKey )
    {
      // Field key
      if( is_null( $mFieldKey_Original ) ) $mFieldKey = $mCriteriaKey;
      else $bLogicalOR = false; // if field key is provided, we must NOT override the criteria-provided logical operator (because we are within the same "field filtering context")

      // Match identifiers (IDs/keys)
      if( !$oView->isElement( $mFieldKey ) ) continue; // ignore non-matching key
      $roField =& $oView->useElement( $mFieldKey );
      if( !( $roField->getMeta() & PHP_APE_Data_Field::Feature_FilterAble ) ) continue; // ignore field that does not include the 'filterable' feature

      // Retrieve filter criteria
      $roCriteria =& $oFilter->useElement( $mCriteriaKey );
      $iOperator = $roCriteria->getOperator();
      $oCriteria = $roCriteria->getContent();

      // Build criteria-specific filter clause
      if( $oCriteria instanceof PHP_APE_Data_Filter )
      {
        $sSQL_TMP = $this->formatFilter( $oView, $oCriteria, false, $mFieldKey );
        if( !empty( $sSQL_TMP ) ) $sSQL_TMP = '('.$sSQL_TMP.')';
      }
      else
      {
        // ... retrieve the data (internal) template for the targeted view element (field)
        $oData = $roField->getContent();
        try
        {
          // ... cast the criteria to the targeted view element's (internal) type
          $oData->setValue( $oCriteria ); 

          // ... retrieve the data (storage) template for the targeted view element (field)
          $oStoredContent = $roField->getContentAsStored();
          if( !is_null( $oStoredContent ) )
          { // data IS stored using a different data object/type
            $oStoredContent->setValue( $oData ); // type conversion
            $oData = $oStoredContent;
          }

          // ... SQL
          $sSQL_TMP = $this->formatComparisonOperator( $iOperator, $this->formatField( $oView, $mFieldKey, false ), $this->formatData( $oData ), $oData );
        }
        catch( PHP_APE_Exception $e )
        { 
          // 'true <-> false' "error" (excluding) statement
          $sSQL_TMP = $this->formatComparisonOperator( $iOperator, $this->formatData( new PHP_APE_Type_Boolean( true ) ), $this->formatData( new PHP_APE_Type_Boolean( false ) ), new PHP_APE_Type_Boolean() );
        }
      }

      // ... ignore empty clause (should not happen... but never know)
      if( empty( $sSQL_TMP ) ) continue;

      // Construct logical clause
      if( $iOperator & PHP_APE_Data_LogicalOperator::NNot )
      {
        $sSQL_TMP = $this->formatLogicalOperator( $iOperator, $sSQL_TMP );
        $iOperator &= ~PHP_APE_Data_LogicalOperator::NNot;
        $iOperator |= PHP_APE_Data_LogicalOperator::AAnd;
      }
      if( $bLogicalOR )
      {
        $iOperator &= ~PHP_APE_Data_LogicalOperator::AAnd;
        $iOperator |= PHP_APE_Data_LogicalOperator::OOr;
      }
      $sSQL = $sSQL ? $this->formatLogicalOperator( $iOperator, $sSQL, $sSQL_TMP ) : $sSQL_TMP;
    }

    // End
    return $sSQL;
  }

  /** Formats the SQL <SAMP>ORDER</SAMP> clause corresponding to the given order
   *
   * <P><B>SYNOPSIS:</B> This function will automatically construct the <SAMP>ORDER</SAMP> clause corresponding
   * to the given filter, matching the order's criteria identifiers (IDs/keys) to those of the SQL fields identifiers (IDs/keys),
   * whenever possible (non-matching identifiers are ignored).</P>
   * <P><B>RETURNS:</B> The formatted clause, <B>excluding</B> the <SAMP>ORDER</SAMP> keyword.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Database_Exception</SAMP>, <SAMP>PHP_APE_Database_SQL_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param PHP_APE_Database_View $oView SQL view
   * @param PHP_APE_Data_Order $oOrder Data order
   * @return string
   * @see PHP_APE_Data_Order
   */
  final public function formatOrder( PHP_APE_Database_View $oView, PHP_APE_Data_Order $oOrder )
  {
    $sSQL = null;
    
    // Loop through order criteria
    $amCriteriasKeys = $oOrder->getElementsKeys();
    foreach( $amCriteriasKeys as $mCriteriaKey )
    {
      // Retrieve criteria
      $oCriteria = $oOrder->getElement( $mCriteriaKey );

      // Field key
      $mFieldKey = $oCriteria->getID();

      // Match identifiers (IDs/keys)
      if( !$oView->isElement( $mFieldKey ) ) continue; // ignore non-matching key
      $oField = $oView->getElement( $mFieldKey );
      if( !( $oField->getMeta() & PHP_APE_Data_Field::Feature_OrderAble ) ) continue; // ignore field that does not include the 'orderable' feature

      // Retrieve direction
      $iDirection = $oCriteria->getDirection();

      // Build SQL clause
      if( $iDirection < 0 )
        $sSQL .= ( $sSQL ? ',' : null ).$this->formatClause( PHP_APE_Database_SQL_Any::Order_Desc, $this->formatField( $oView, $mFieldKey, false ) );
      elseif( $iDirection > 0 )
        $sSQL .= ( $sSQL ? ',' : null ).$this->formatClause( PHP_APE_Database_SQL_Any::Order_Asc, $this->formatField( $oView, $mFieldKey, false ) );
    }

    // End
    return $sSQL;

  }


  /*
   * METHODS: Character set
   ********************************************************************************/

  /** Set the database client's character set to match PHP's character set
   *
   * <P><B>SYNOPSIS:</B> When supported by the database, this function will set the database client's character set to match PHP's character set.</P>
   * <P><B>RETURNS:</B> <SAMP>true</SAMP> on success, <SAMP>false</SAMP> otherwise.</P>
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param resource $rDatabaseConnection Database connection to act upon (the last opened connection if unspecified)
   * @return string
   */
  public function initCharset( $rDatabaseConnection = null )
  {
    return false;
  }

}
