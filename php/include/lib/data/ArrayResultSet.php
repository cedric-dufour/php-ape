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

/** Array-based result set implementation class
 *
 * <P><B>USAGE:</B> This class allows to obtain result set behavior from <I>array</I> data.
 * The given <I>array</I> SHOULD be two-dimensional (but MAY be one-dimensional), with:</B>
 * <UL>
 * <LI>the <B>first dimension</B> associating the result set's <B>elements</B> keys</LI>
 * <LI>the <B>second dimension</B> associating the result set's <B>entries</B> for each element key</LI>
 * </UL>
 * <P><B>NOTE:</B> No more than five criteria can be used for ordering the result set (additional order criteria will be ignored).</P>
 *
 * @package PHP_APE_Data
 * @subpackage Classes
 */
abstract class PHP_APE_Data_ArrayResultSet
extends PHP_APE_Data_FieldSet
implements PHP_APE_Data_isExtendedResultSet, PHP_APE_Data_isQueryAbleResultSet, PHP_APE_Data_isArgumentSetAble, PHP_APE_Data_isScrollerAble, PHP_APE_Data_isOrderAble, PHP_APE_Data_isFilterAble, PHP_APE_Data_isSubsetFilterAble
{

  /*
   * FIELDS
   ********************************************************************************/

  /** (Original) array (associating elements => entries )
   * @var array|array|mixed */
  private $aamArray;

  /** (Original) data (row) quantity
   * @var integer */
  private $iDataQuantity;

  /** Associated scroller class
   * @var PHP_APE_Data_Scroller
   */
  private $oScroller;

  /** Associated order class
   * @var PHP_APE_Data_Order
   */
  private $oOrder;

  /** Associated filter class
   * @var PHP_APE_Data_Filter
   */
  private $oFilter;

  /** Associated system filter class
   * @var PHP_APE_Data_Filter
   */
  private $oSubsetFilter;

  /** (Queried) Result set array (associating entries => elements )
   * @var array|array|mixed */
  private $aamResultSet;

  /** (Queryable) entry (row) quantity
   * @var integer */
  private $iEntryQuantity;

  /** (Current) entry position (row)
   * @var integer */
  private $iEntryRow;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new result set instance
   *
   * @param mixed $mID Result set identifier (ID)
   * @param array|array|mixed $aamArray Result set underlying array
   * @param string $sName Result set name (default to identifier if <SAMP>empty</SAMP>)
   * @param string $sDescription Result set description
   */
  public function __construct( $mID, $aamArray = null, $sName = null, $sDescription = null )
  {
    // Initialize member fields
    parent::__construct( $mID, null, $sName, $sDescription );
    if( !is_null( $aamArray ) ) $this->_setArray( $aamArray );
  }


  /*
   * METHODS: initialization
   ********************************************************************************/

  /** Resets this set
   *
   * <P><B>SYNOPSIS:</B> This method clears this set's (queried) data content, and clears all data limit/offset/order/filter.</P>
   * <P><B>INHERITANCE:</B> This method <B>MEY be overridden</B>.</P>
   */
  public function reset()
  {
    $this->resetQuery();
    $this->unsetScroller();
    $this->unsetOrder();
    parent::reset();
  }


  /*
   * METHODS
   ********************************************************************************/

  /** Returns whether this set has an underlying array
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return boolean
   */
  final protected function _hasArray()
  {
    return is_array( $this->aamArray );
  }

  /** Sets this set's underlying array
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param array|array|mixed Underlying array
   */
  final protected function _setArray( $aamArray )
  {
    // Check argument
    if( !is_array( $aamArray ) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Argument must be an array' );

    // Save (original) array
    $this->aamArray = unserialize( serialize( $aamArray ) );
    
    // Remove non-matched elements (keys)
    foreach( array_keys( $this->aamArray ) as $mElementKey )
      if( !$this->isElement( $mElementKey ) )
        unset( $this->aamArray[ $mElementKey ] );

    // Retrieve entry quantity
    $this->iDataQuantity = 0;
    foreach( $this->aamArray as $amEntries )
    {
      if( is_array( $amEntries ) and count( $amEntries ) > $this->iDataQuantity )
        $this->iDataQuantity = count( $amEntries );
    }

    // Correct entry array
    foreach( $this->aamArray as &$ramEntries )
    {
      // make sure we deal with an array
      if( !is_array( $ramEntries ) )
        $ramEntries = array( $ramEntries );

      // make sure we have the same entry count for each element
      $ramEntries = array_pad( array_values( $ramEntries ), $this->iDataQuantity, null );

      // make sure we deal with PHP (scalar) values (required for 'array_multisorting')
      foreach( $ramEntries as &$rmEntry )
        $rmEntry = PHP_APE_Type_Scalar::parseValue( $rmEntry );
    }
    
  }


  /*
   * METHODS: PHP_APE_Data_hasArgumentSet - IMPLEMENT
   ********************************************************************************/

  /** Returns whether this view's arguments have been defined
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return boolean
   */
  final public function hasArgumentSet()
  {
    return !is_null( $this->oArgumentSet );
  }

  /** Returns this view's arguments
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return PHP_APE_Data_ArgumentSet
   */
  final public function getArgumentSet()
  {
    return clone( $this->oArgumentSet );
  }


  /*
   * METHODS: PHP_APE_Data_isArgumentSetAble - IMPLEMENT
   ********************************************************************************/

  /** Returns this view's arguments (<B>as reference</B>)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return PHP_APE_Data_ArgumentSet
   */
  final public function &useArgumentSet()
  {
    return $this->oArgumentSet;
  }

  /** Sets this view's arguments
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param PHP_APE_Data_ArgumentSet $oArgumentSet View's arguments
   */
  public function setArgumentSet( PHP_APE_Data_ArgumentSet $oArgumentSet )
  {
    $this->oArgumentSet = clone( $oArgumentSet );
  }

  /** Unsets (clears) this view's arguments
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function unsetArgumentSet()
  {
    $this->oArgumentSet = null;
  }


  /*
   * METHODS: PHP_APE_Data_hasScroller - IMPLEMENT
   ********************************************************************************/

  /** Returns whether this view's data scroller has been defined
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return boolean
   */
  final public function hasScroller()
  {
    return !is_null( $this->oScroller );
  }

  /** Returns this view's data scroller
   *
   * <P><B>NOTE:</B> If no scroller has been defined, this method SHOULD return the scroller applied by default.</P>
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return PHP_APE_Data_Scroller
   */
  public function getScroller()
  {
    if( !is_null( $this->oScroller ) )
      $oScroller = clone( $this->oScroller );
    else
      $oScroller = new PHP_APE_Data_Scroller();

    // Return the default scroller
    if( !$oScroller->isLimited() ) $oScroller->setLimit( PHP_APE_WorkSpace::useEnvironment()->getUserParameter( 'php_ape.data.query.size' ) );
    if( !$oScroller->isOffset() ) $oScroller->setOffset( 0 );
    return $oScroller;
  }


  /*
   * METHODS: PHP_APE_Data_isScrollerAble - IMPLEMENT
   ********************************************************************************/

  /** Sets this view's data scroller
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param PHP_APE_Data_Scroller $oScroller Data scroller object
   */
  public function setScroller( PHP_APE_Data_Scroller $oScroller )
  {
    // Environment
    $roEnvironment =& PHP_APE_WorkSpace::useEnvironment();

    // Check limit
    $iLimit = null;
    if( $oScroller->isLimited() )
    {
      $iLimit = $oScroller->getLimit();
      $iMinSize = $roEnvironment->getVolatileParameter( 'php_ape.data.query.minsize' );
      $iMaxSize = $roEnvironment->getVolatileParameter( 'php_ape.data.query.maxsize' );
      if( $iLimit < $iMinSize ) $iLimit = $iMinSize;
      elseif( $iLimit > $iMaxSize ) $iLimit = $iMaxSize;
    }

    // Check offset
    $iOffset = null;
    if( $oScroller->isOffset() )
    {
      $iOffset = $oScroller->getOffset();
      $iCacheSize = $roEnvironment->getVolatileParameter( 'php_ape.data.query.cachesize' );
      $iPrefSize = $roEnvironment->getUserParameter( 'php_ape.data.query.size' );
      if( $iOffset > $iCacheSize-$iPrefSize ) $iOffset = $iCacheSize-$iPrefSize;
    }

    // Set scroller
    $this->oScroller = new PHP_APE_Data_Scroller( $iLimit, $iOffset );
  }

  /** Returns this view's data scroller (<B>as reference</B>)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return PHP_APE_Data_Scroller
   */
  final public function &useScroller()
  {
    return $this->oScroller;
  }

  /** Unsets (clears) this view's row scroller
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function unsetScroller()
  {
    $this->oScroller = null;
  }


  /*
   * METHODS: PHP_APE_Data_hasOrder - IMPLEMENT
   ********************************************************************************/

  /** Returns whether this view's row order has been defined
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return boolean
   */
  final public function hasOrder()
  {
    return !is_null( $this->oOrder );
  }

  /** Returns this view's row order
   *
   * <P><B>NOTE:</B> If no order has been defined, this method SHOULD return a sample order (with usable/available order criteria keys).</P>
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return PHP_APE_Data_Order
   */
  public function getOrder()
  {
    if( !is_null( $this->oOrder ) )
      return clone( $this->oOrder );

    // Return a sample order
    $oOrder = new PHP_APE_Data_Order();
    foreach( $this->getElementsKeysPerMeta( PHP_APE_Data_hasMeta::Feature_OrderAble ) as $mKey )
      $oOrder->setElement( new PHP_APE_Data_OrderCriteria( $mKey ) );
    return $oOrder;
  }


  /*
   * METHODS: PHP_APE_Data_isOrderAble - IMPLEMENT
   ********************************************************************************/

  /** Sets this view's row order
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param PHP_APE_Data_Order $oOrder Row order object
   */
  public function setOrder( PHP_APE_Data_Order $oOrder )
  {
    $this->oOrder = clone( $oOrder );
  }

  /** Returns this view's row order (<B>as reference</B>)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return PHP_APE_Data_Order
   */
  final public function &useOrder()
  {
    return $this->oOrder;
  }

  /** Unsets (clears) this view's row order
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function unsetOrder()
  {
    $this->oOrder = null;
  }


  /*
   * METHODS: PHP_APE_Data_hasFilter - IMPLEMENT
   ********************************************************************************/

  /** Returns whether this view's row filter has been defined
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return boolean
   */
  final public function hasFilter()
  {
    return !is_null( $this->oFilter );
  }

  /** Returns this view's row filter
   *
   * <P><B>NOTE:</B> If no filter has been defined, this method SHOULD return a sample filter (with usable/available filter criteria keys).</P>
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return PHP_APE_Data_Filter
   */
  public function getFilter()
  {
    if( !is_null( $this->oFilter ) )
      return clone( $this->oFilter );

    // Return a sample filter
    $oFilter = new PHP_APE_Data_Filter();
    foreach( $this->getElementsKeysPerMeta( PHP_APE_Data_Field::Feature_FilterAble ) as $mKey )
      $oFilter->setElement( new PHP_APE_Data_FilterCriteria( $mKey ) );
    return $oFilter;
  }


  /*
   * METHODS: PHP_APE_Data_isFilterAble - IMPLEMENT
   ********************************************************************************/

  /** Sets this view's row filter
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param PHP_APE_Data_Filter $oFilter Row filter object
   */
  public function setFilter( PHP_APE_Data_Filter $oFilter )
  {
    $this->oFilter = clone( $oFilter );
  }

  /** Returns this view's row filter (<B>as reference</B>)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return PHP_APE_Data_Filter
   */
  final public function &useFilter()
  {
    return $this->oFilter;
  }

  /** Unsets (clears) this view's row filter
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function unsetFilter()
  {
    $this->oFilter = null;
  }


  /*
   * METHODS: PHP_APE_Data_hasSubsetFilter - IMPLEMENT
   ********************************************************************************/

  /** Returns whether this view's subset row filter has been defined
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return boolean
   */
  final public function hasSubsetFilter()
  {
    return !is_null( $this->oSubsetFilter );
  }

  /** Returns this view's subset row filter
   *
   * <P><B>NOTE:</B> If no filter has been defined, this method SHOULD return a sample filter (with usable/available filter criteria keys).</P>
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return PHP_APE_Data_Filter
   */
  public function getSubsetFilter()
  {
    if( !is_null( $this->oSubsetFilter ) )
      return clone( $this->oSubsetFilter );

    // Return a sample filter
    $oSubsetFilter = new PHP_APE_Data_Filter();
    foreach( $this->getElementsKeysPerMeta( PHP_APE_Data_hasMeta::Feature_FilterAble ) as $mKey )
      $oSubsetFilter->setElement( new PHP_APE_Data_FilterCriteria( $mKey ) );
    return $oSubsetFilter;
  }


  /*
   * METHODS: PHP_APE_Data_isSubsetFilterAble - IMPLEMENT
   ********************************************************************************/

  /** Sets this view's subset row filter
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param PHP_APE_Data_Filter $oSubsetFilter Row filter object
   */
  public function setSubsetFilter( PHP_APE_Data_Filter $oSubsetFilter )
  {
    $this->oSubsetFilter = clone( $oSubsetFilter );
  }

  /** Returns this view's subset row filter (<B>as reference</B>)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return PHP_APE_Data_Filter
   */
  final public function &useSubsetFilter()
  {
    return $this->oSubsetFilter;
  }

  /** Unsets (clears) this view's subset row filter
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function unsetSubsetFilter()
  {
    $this->oSubsetFilter = null;
  }


  /*
   * METHODS: PHP_APE_Data_isBasicResultSet - IMPLEMENT
   ********************************************************************************/

  /** Positions this set on the next entry
   *
   * <P><B>RETURNS:</B> <SAMP>true</SAMP> if the set has been successfully positioned on the next entry,
   * <SAMP>false</SAMP> otherwise (e.g. no more entries).</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return boolean
   */
  final public function nextEntry()
  {
    if( !is_array( $this->aamResultSet ) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Result set must be queried before fetching entry data' );
    
    // Fetch next entry (row)
    ++$this->iEntryRow;
    if( $this->iEntryRow >= count( $this->aamResultSet ) )
      return false;
    foreach( $this->getElementsKeys() as $mKey )
    {
      if( array_key_exists( $mKey, $this->aamResultSet[ $this->iEntryRow ] ) )
        $this->useElement( $mKey )->useContent()->setValue( $this->aamResultSet[ $this->iEntryRow ][ $mKey ] );
      else
        $this->useElement( $mKey )->useContent()->resetValue();
    }
    return true;
  }


  /*
   * METHODS: PHP_APE_Data_isExtendedResultSet - IMPLEMENT
   ********************************************************************************/

  /** Returns this set's current entry key
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return mixed
   */
  final public function getEntryKey()
  {
    if( !is_array( $this->aamResultSet ) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Result set must be queried before fetching entry data' );
    return $this->iEntryRow;
  }

  /** Returns this set's current (queried) entries quantity (count)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return integer
   */
  final public function countEntries()
  {
    if( !is_array( $this->aamResultSet ) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Result set must be queried before fetching entry data' );
    return count( $this->aamResultSet );
  }

  /** Returns this set's total (queryable) entries quantity (count)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return integer
   */
  final public function countAllEntries()
  {
    if( !is_array( $this->aamResultSet ) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Result set must be queried before fetching entry data' );
    return $this->iEntryQuantity;
  }

  /** Returns the entries keys from this set
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return array|mixed
   */
  final public function getEntriesKeys()
  {
    if( !is_array( $this->aamResultSet ) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Result set must be queried before fetching entry data' );
    return array_keys( $this->aamResultSet );
  }

  /** Returns whether the given key corresponds to an entry in this set
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param mixed $mKey Entry key
   * @return boolean
   */
  final public function isEntry( $mKey )
  {
    $mKey = (integer)$mKey;
    if( !is_array( $this->aamResultSet ) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Result set must be queried before fetching entry data' );
    return array_key_exists( $mKey, $this->aamResultSet );
  }

  /** Positions this set at the given entry
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param mixed $mKey Entry key
   */
  final public function gotoEntry( $mKey )
  {
    $mKey = (integer)$mKey;
    if( !is_array( $this->aamResultSet ) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Result set must be queried before fetching entry data' );
    if( !array_key_exists( $mKey, $this->aamResultSet ) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Invalid entry key; Key: '.$mKey );
    $this->iEntryRow = $mKey;
    $this->aoData = $this->aamResultSet[ $this->iEntryRow ];
  }


  /*
   * METHODS: PHP_APE_Data_isQueryAbleResultSet - IMPLEMENT
   ********************************************************************************/

  /** Fetches this set's entries (rows)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MAY be overriden</B>.</P>
   *
   * @param integer $iQueryType Query type (see {@link PHP_APE_Data_isQueryAbleResultSet} constants)
   */
  public function queryEntries( $iQueryType = PHP_APE_Data_isQueryAbleResultSet::Query_Full )
  {
    // Check if result set is being queried
    if( is_array( $this->aamResultSet ) )
      return;

    // Check underlying array
    if( !is_array( $this->aamArray ) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Missing (undefined) underlying array' );

    // Environment
    $roEnvironment =& PHP_APE_WorkSpace::useEnvironment();

    // Reset previous query
    $this->resetQuery();

    // Check primary key
    $mPrimaryKey = null;
    $mPKey_Field = $this->getElementsKeysPerMeta( PHP_APE_Data_Field::Type_PrimaryKey );
    $mPKey_Field = ( is_array( $mPKey_Field ) and count( $mPKey_Field ) == 1 ) ? $mPKey_Field[0] : null;
    if( !is_null( $mPKey_Field ) and $this->hasArgumentSet() )
    {
      $roArgumentSet =& $this->useArgumentSet();
      $mPKey_Argument = $this->getElementsKeysPerMeta( PHP_APE_Data_Argument::Type_PrimaryKey );
      $mPKey_Argument = ( is_array( $mPKey_Argument ) and count( $mPKey_Argument ) == 1 ) ? $mPKey_Argument[0] : null;
      if( !is_null( $mPKey_Argument ) )
        $mPrimaryKey = $roArgumentSet->useElementbyID( $mPKey_Argument )->useContent()->getValue();
    }
    if( !is_null( $mPrimaryKey ) )
    {
      // ... create result set
      $this->aamResultSet = array();
      $this->iEntryQuantity = 0;
      for( $d = 0; $d < $this->iDataQuantity; $d++ )
      {
        if( $this->aamArray[$mPKey_Field][$d] != $mPrimaryKey ) continue;
        $this->iEntryQuantity = 1;
        foreach( array_keys( $this->aamArray ) as $mElementKey )
          $this->aamResultSet[0][$mElementKey] = $this->aamArray[$mElementKey][$d];
        break;
      }

      // ... position entry
      $this->iEntryRow = -1;

      // ... end
      return;
    }


    // Filter result set
    $abIncludedRows = $this->iDataQuantity > 0 ? array_fill( 0, $this->iDataQuantity, true ) : array();

    // ... subset filter
    $abIncludedRows_subset = null;
    if( ( $iQueryType & PHP_APE_Data_isQueryAbleResultSet::Query_Subset ) and $this->hasSubsetFilter() )
    {
      $oFilter = $this->getSubsetFilter();

      // ... filter keys
      $amFilterKeys = $oFilter->getElementsKeys();

      // ... global filter (simple filter/search)
      $abIncludedRows_global = null;
      if( in_array( '__GLOBAL', $amFilterKeys ) )
      {
        $oCriteria = $oFilter->getElement( '__GLOBAL' );
        $oFilterGlobal = new PHP_APE_Data_Filter();
        $amFieldsKeys = $this->getElementsKeysPerMeta( PHP_APE_Data_Field::Feature_SearchAble, true );
        foreach( $amFieldsKeys as $mKey )
        {
          if( in_array( $mKey, $amFilterKeys ) ) continue; // we do NOT override advanced (field-wise) filter criteria
          $oFilterGlobal->setElement( $oCriteria, $mKey );
        }
        $abIncludedRows_global = $this->_getIncludedRows( $oFilterGlobal, true ); // simple filter/search is always OR-ed
      }

      // ... field-wise filter (advanced filter)
      $abIncludedRows_fields = $this->_getIncludedRows( $oFilter, false );

      // ... complete clause
      if( !empty( $abIncludedRows_global ) and !empty( $abIncludedRows_fields ) )
        for( $i = 0; $i < $this->iDataQuantity; $i++ )
          $abIncludedRows_subset[$i] = $abIncludedRows_global[$i] && $abIncludedRows_fields[$i];
      elseif( !empty( $abIncludedRows_global ) )
        $abIncludedRows_subset = $abIncludedRows_global;
      elseif( !empty( $abIncludedRows_fields ) )
        $abIncludedRows_subset = $abIncludedRows_fields;
    }

    // ... filter
    $abIncludedRows_filter = null;
    if( ( $iQueryType & PHP_APE_Data_isQueryAbleResultSet::Query_Filter ) and $this->hasFilter() )
    {
      $oFilter = $this->getFilter();

      // ... preferences
      $bFilterAdvanced = $roEnvironment->getUserParameter( 'php_ape.data.filter.advanced' );
      $bFilterOr = $roEnvironment->getUserParameter( 'php_ape.data.filter.or' );

      // ... filter keys
      $amFilterKeys = $oFilter->getElementsKeys();

      // ... global filter (simple filter/search)
      $abIncludedRows_global = null;
      if( in_array( '__GLOBAL', $amFilterKeys ) )
      {
        $oCriteria = $oFilter->getElement( '__GLOBAL' );
        $oFilterGlobal = new PHP_APE_Data_Filter();
        $amFieldsKeys = $this->getElementsKeysPerMeta( PHP_APE_Data_Field::Feature_SearchAble, true );
        foreach( $amFieldsKeys as $mKey )
        {
          if( in_array( $mKey, $amFilterKeys ) and $bFilterAdvanced ) continue; // we do NOT override advanced (field-wise) filter criteria
          $oFilterGlobal->setElement( $oCriteria, $mKey );
        }
        $abIncludedRows_global = $this->_getIncludedRows( $oFilterGlobal, true ); // simple filter/search is always OR-ed
      }

      // ... field-wise filter (advanced filter)
      $abIncludedRows_fields = null;
      if( $bFilterAdvanced )
        $abIncludedRows_fields = $this->_getIncludedRows( $oFilter, $bFilterOr );

      // ... complete clause
      if( !empty( $abIncludedRows_global ) and !empty( $abIncludedRows_fields ) )
      {
        if( $bFilterOr )
          for( $i = 0; $i < $this->iDataQuantity; $i++ )
            $abIncludedRows_filter[$i] = $abIncludedRows_global[$i] || $abIncludedRows_fields[$i];
        else
          for( $i = 0; $i < $this->iDataQuantity; $i++ )
            $abIncludedRows_filter[$i] = $abIncludedRows_global[$i] && $abIncludedRows_fields[$i];
      }
      elseif( !empty( $abIncludedRows_global ) )
        $abIncludedRows_filter = $abIncludedRows_global;
      elseif( !empty( $abIncludedRows_fields ) )
        $abIncludedRows_filter = $abIncludedRows_fields;
    }

    // ... complete clause
    if( !empty( $abIncludedRows_subset ) and !empty( $abIncludedRows_filter ) )
      for( $i = 0; $i < $this->iDataQuantity; $i++ )
        $abIncludedRows[$i] = $abIncludedRows_subset[$i] && $abIncludedRows_filter[$i];
    elseif( !empty( $abIncludedRows_subset ) )
      $abIncludedRows = $abIncludedRows_subset;
    elseif( !empty( $abIncludedRows_filter ) )
      $abIncludedRows = $abIncludedRows_filter;


    // Order result set (NOTE: maximum five sorting columns are supported)
    
    // ... use an entries keys intermediate ordering array
    $aiEntriesKeys = range( 0, $this->iDataQuantity-1 );
    
    // ... check if result set is ordered
    if( ( $iQueryType & PHP_APE_Data_isQueryAbleResultSet::Query_Order ) and $this->hasOrder() )
    {

      // ... retrieve result set order
      $oOrder = $this->getOrder();

      // ... build ordering criteria array (associating element's key => direction)
      $aamOrderCriteria = array();
      foreach( $oOrder->getElementsKeys() as $mKey )
      { // loop through order criteria
        $roCriteria =& $oOrder->useElement( $mKey );
        $mKey = $roCriteria->getID();
        if( array_key_exists( $mKey, $this->aamArray ) )
        { // order criteria matches array/result set element's key
          array_push( $aamOrderCriteria, array( $mKey, $roCriteria->getDirection() ) );
        }
      }

      // ... 'array_multisort' the entries keys intermediate ordering array based on the ordering criteria
      if( count( $aamOrderCriteria ) >= 5 )
      {
        $aamArray = unserialize( serialize( $this->aamArray ) );
        array_multisort( $aamArray[ $aamOrderCriteria[0][0] ], $aamOrderCriteria[0][1] < 0 ? SORT_DESC : SORT_ASC, SORT_REGULAR,
                         $aamArray[ $aamOrderCriteria[1][0] ], $aamOrderCriteria[1][1] < 0 ? SORT_DESC : SORT_ASC, SORT_REGULAR,
                         $aamArray[ $aamOrderCriteria[2][0] ], $aamOrderCriteria[2][1] < 0 ? SORT_DESC : SORT_ASC, SORT_REGULAR,
                         $aamArray[ $aamOrderCriteria[3][0] ], $aamOrderCriteria[3][1] < 0 ? SORT_DESC : SORT_ASC, SORT_REGULAR,
                         $aamArray[ $aamOrderCriteria[4][0] ], $aamOrderCriteria[4][1] < 0 ? SORT_DESC : SORT_ASC, SORT_REGULAR,
                         $aiEntriesKeys, SORT_ASC, SORT_NUMERIC
                         );
      }   
      elseif( count( $aamOrderCriteria ) >= 4 )
      {
        $aamArray = unserialize( serialize( $this->aamArray ) );
        array_multisort( $aamArray[ $aamOrderCriteria[0][0] ], $aamOrderCriteria[0][1] < 0 ? SORT_DESC : SORT_ASC, SORT_REGULAR,
                         $aamArray[ $aamOrderCriteria[1][0] ], $aamOrderCriteria[1][1] < 0 ? SORT_DESC : SORT_ASC, SORT_REGULAR,
                         $aamArray[ $aamOrderCriteria[2][0] ], $aamOrderCriteria[2][1] < 0 ? SORT_DESC : SORT_ASC, SORT_REGULAR,
                         $aamArray[ $aamOrderCriteria[3][0] ], $aamOrderCriteria[3][1] < 0 ? SORT_DESC : SORT_ASC, SORT_REGULAR,
                         $aiEntriesKeys, SORT_ASC, SORT_NUMERIC
                         );
      }   
      elseif( count( $aamOrderCriteria ) >= 3 )
      {
        $aamArray = unserialize( serialize( $this->aamArray ) );
        array_multisort( $aamArray[ $aamOrderCriteria[0][0] ], $aamOrderCriteria[0][1] < 0 ? SORT_DESC : SORT_ASC, SORT_REGULAR,
                         $aamArray[ $aamOrderCriteria[1][0] ], $aamOrderCriteria[1][1] < 0 ? SORT_DESC : SORT_ASC, SORT_REGULAR,
                         $aamArray[ $aamOrderCriteria[2][0] ], $aamOrderCriteria[2][1] < 0 ? SORT_DESC : SORT_ASC, SORT_REGULAR,
                         $aiEntriesKeys, SORT_ASC, SORT_NUMERIC
                         );
      }   
      elseif( count( $aamOrderCriteria ) >= 2 )
      {
        $aamArray = unserialize( serialize( $this->aamArray ) );
        array_multisort( $aamArray[ $aamOrderCriteria[0][0] ], $aamOrderCriteria[0][1] < 0 ? SORT_DESC : SORT_ASC, SORT_REGULAR,
                         $aamArray[ $aamOrderCriteria[1][0] ], $aamOrderCriteria[1][1] < 0 ? SORT_DESC : SORT_ASC, SORT_REGULAR,
                         $aiEntriesKeys, SORT_ASC, SORT_NUMERIC
                         );
      }   
      elseif( count( $aamOrderCriteria ) >= 1 )
      {
        $aamArray = unserialize( serialize( $this->aamArray ) );
        array_multisort( $aamArray[ $aamOrderCriteria[0][0] ], $aamOrderCriteria[0][1] < 0 ? SORT_DESC : SORT_ASC, SORT_REGULAR,
                         $aiEntriesKeys, SORT_ASC, SORT_NUMERIC
                         );
      }   

    }


    // Retrieve limit/offset settings
    $iLimit = $this->iDataQuantity;
    $iOffset = 0;
    if( $iQueryType & PHP_APE_Data_isQueryAbleResultSet::Query_Scroll ) 
    {
      $oScroller = $this->getScroller();
      if( $iQueryType & PHP_APE_Data_isQueryAbleResultSet::Query_Limit )
        $iLimit = $oScroller->getLimit();
      if( $iQueryType & PHP_APE_Data_isQueryAbleResultSet::Query_Offset )
        $iOffset = $oScroller->getOffset();
    }


    // Create result set
    $this->aamResultSet = array();
    $this->iEntryQuantity = 0;
    $amElementsKeys = array_keys( $this->aamArray );
    for( $d = 0, $o = 0, $r = 0; $d < $this->iDataQuantity; $d++ )
    {
      if( !$abIncludedRows[$aiEntriesKeys[$d]] ) continue;
      ++$this->iEntryQuantity;
      if( $r >= $iLimit ) continue;
      if( $o++ < $iOffset ) continue;
      $this->aamResultSet[$r] = array();
      foreach( $amElementsKeys as $mElementKey )
        $this->aamResultSet[$r][$mElementKey] = $this->aamArray[$mElementKey][$aiEntriesKeys[$d]];
      ++$r;
    }


    // Position entry
    $this->iEntryRow = -1;
  }

  /** Returns whether this set is being queried
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return boolean
   */
  final public function isQueried()
  {
    return( is_array( $this->aamResultSet ) );
  }

  /** Reset (clears) this set's query status and entries (rows)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function resetQuery()
  {
    $this->aamResultSet = null;
    $this->iEntryQuantity = null;
    $this->iEntryRow = null;
  }


  /*
   * METHODS: filter
   ********************************************************************************/

  /** Returns the row inclusion <I>array</I> corresponding to the given filter (associating <I>row</I> => <I>include (boolean)</I>)
   *
   * @param PHP_APE_Data_Filter $oFilter Data filter
   * @param boolean $bLogicalOR Link filter fields with logical OR (instead of logical AND)
   * @param mixed $mFieldKey Field key (retrieved from the filter itself if <SAMP>null</SAMP>)
   * @return array|boolean
   */
  protected function _getIncludedRows( PHP_APE_Data_Filter $oFilter, $bLogicalOR = false, $mFieldKey = null )
  {

    // Check result set
    if( !is_array( $this->aamArray ) or $this->iDataQuantity < 1 )
      return array();

    // Loop through filter's criteria
    $abIncludedRows = array_fill( 0, $this->iDataQuantity, !$bLogicalOR );
    $amCriteriasKeys = $oFilter->getElementsKeys();
    $mFieldKey_Original = $mFieldKey;
    foreach( $amCriteriasKeys as $mCriteriaKey )
    {
      // Field key
      if( is_null( $mFieldKey_Original ) ) $mFieldKey = $mCriteriaKey;
      else $bLogicalOR = false; // if field key is provided, we must NOT override the criteria-provided logical operator (because we are within the same "field filtering context")

      // Match identifiers (IDs/keys)
      if( !$this->isElement( $mFieldKey ) ) continue; // ignore non-matching key
      if( !array_key_exists( $mFieldKey, $this->aamArray ) ) continue; // ignore non-matching key
      $roField =& $this->useElement( $mFieldKey );
      if( !( $roField->getMeta() & PHP_APE_Data_Field::Feature_FilterAble ) ) continue; // ignore field that does not include the 'filterable' feature

      // Retrieve filter criteria
      $roCriteria =& $oFilter->useElement( $mCriteriaKey );
      $iOperator = $roCriteria->getOperator();
      $oCriteria = $roCriteria->getContent();

      // Criteria-specific filter
      $amValues = $this->aamArray[$mFieldKey];
      $abIncludedRows_SUB = null;
      if( $oCriteria instanceof PHP_APE_Data_Filter )
      {
        $abIncludedRows_SUB = $this->_getIncludedRows( $oCriteria, false, $mFieldKey );
      }
      else
      {

        // ... retrieve the data (internal) template for the targeted view element (field)
        $oData = $roField->getContent();
        try
        {
          // ... cast the criteria to the targeted view element's (internal) type
          $oData->setValue( $oCriteria ); 

          // ... filter
          $abIncludedRows_SUB = array_fill( 0, $this->iDataQuantity, true );
          $mFilter = $oData->getValue();
          if( $oData instanceof PHP_APE_Type_String )
          { // ... let's use string-frienldy filtering

            switch( $iOperator & PHP_APE_Data_ComparisonOperator::Mask )
            {

            case PHP_APE_Data_ComparisonOperator::Equal:
              for( $i = 0; $i < $this->iDataQuantity; $i++ ) $abIncludedRows_SUB[$i] = preg_match( '/^'.str_replace(array('\*','\?'),array('.*','.'),preg_quote($mFilter)).'$/i', $oData->parseValue( $amValues[$i] ) );
              break;

            case PHP_APE_Data_ComparisonOperator::NotEqual:
              for( $i = 0; $i < $this->iDataQuantity; $i++ ) $abIncludedRows_SUB[$i] = !preg_match( '/^'.str_replace(array('\*','\?'),array('.*','.'),preg_quote($mFilter)).'$/i', $oData->parseValue( $amValues[$i] ) );
              break;

            case PHP_APE_Data_ComparisonOperator::SmallerOrEqual:
              for( $i = 0; $i < $this->iDataQuantity; $i++ ) $abIncludedRows_SUB[$i] = ( strcasecmp( $oData->parseValue( $amValues[$i] ), $mFilter ) <= 0 );
              break;

            case PHP_APE_Data_ComparisonOperator::Smaller:
              for( $i = 0; $i < $this->iDataQuantity; $i++ ) $abIncludedRows_SUB[$i] |= ( strcasecmp( $oData->parseValue( $amValues[$i] ), $mFilter ) < 0 );
              break;

            case PHP_APE_Data_ComparisonOperator::BiggerOrEqual:
              for( $i = 0; $i < $this->iDataQuantity; $i++ ) $abIncludedRows_SUB[$i] = ( strcasecmp( $oData->parseValue( $amValues[$i] ), $mFilter ) >= 0 );
              break;

            case PHP_APE_Data_ComparisonOperator::Bigger:
              for( $i = 0; $i < $this->iDataQuantity; $i++ ) $abIncludedRows_SUB[$i] |= ( strcasecmp( $oData->parseValue( $amValues[$i] ), $mFilter ) > 0 );
              break;

            case PHP_APE_Data_ComparisonOperator::Proportional:
              for( $i = 0; $i < $this->iDataQuantity; $i++ ) $abIncludedRows_SUB[$i] = preg_match( '/^'.str_replace(array('\*','\?'),array('.*','.'),preg_quote($mFilter)).'/i', $oData->parseValue( $amValues[$i] ) );
              break;

            case PHP_APE_Data_ComparisonOperator::Included:
              for( $i = 0; $i < $this->iDataQuantity; $i++ ) $abIncludedRows_SUB[$i] = preg_match( '/'.str_replace(array('\*','\?'),array('.*','.'),preg_quote($mFilter)).'/i', $oData->parseValue( $amValues[$i] ) );
              break;

            }

          }
          else
          {

            switch( $iOperator & PHP_APE_Data_ComparisonOperator::Mask )
            {

            case PHP_APE_Data_ComparisonOperator::Equal:
            case PHP_APE_Data_ComparisonOperator::Proportional:
            case PHP_APE_Data_ComparisonOperator::Included:
              for( $i = 0; $i < $this->iDataQuantity; $i++ ) $abIncludedRows_SUB[$i] = ( $oData->parseValue( $amValues[$i] ) == $mFilter );
              break;

            case PHP_APE_Data_ComparisonOperator::NotEqual:
              for( $i = 0; $i < $this->iDataQuantity; $i++ ) $abIncludedRows_SUB[$i] = ( $oData->parseValue( $amValues[$i] ) != $mFilter );
              break;

            case PHP_APE_Data_ComparisonOperator::SmallerOrEqual:
              for( $i = 0; $i < $this->iDataQuantity; $i++ ) $abIncludedRows_SUB[$i] = ( $oData->parseValue( $amValues[$i] ) <= $mFilter );
              break;

            case PHP_APE_Data_ComparisonOperator::Smaller:
              for( $i = 0; $i < $this->iDataQuantity; $i++ ) $abIncludedRows_SUB[$i] = ( $oData->parseValue( $amValues[$i] ) < $mFilter );
              break;

            case PHP_APE_Data_ComparisonOperator::BiggerOrEqual:
              for( $i = 0; $i < $this->iDataQuantity; $i++ ) $abIncludedRows_SUB[$i] = ( $oData->parseValue( $amValues[$i] ) >= $mFilter );
              break;

            case PHP_APE_Data_ComparisonOperator::Bigger:
              for( $i = 0; $i < $this->iDataQuantity; $i++ ) $abIncludedRows_SUB[$i] = ( $oData->parseValue( $amValues[$i] ) > $mFilter );
              break;

            }

          }

        }
        catch( PHP_APE_Exception $e )
        { 
          $abIncludedRows_SUB = array_fill( 0, $this->iDataQuantity, false );
        }

      }

      // Construct logical clause
      if( $iOperator & PHP_APE_Data_LogicalOperator::NNot )
      {
        foreach( $abIncludedRows_SUB as &$rbIncludeRow ) $rbIncludeRow = !$rbIncludeRow;
        $iOperator &= ~PHP_APE_Data_LogicalOperator::NNot;
        $iOperator |= PHP_APE_Data_LogicalOperator::AAnd;
      }
      if( $bLogicalOR )
      {
        $iOperator &= ~PHP_APE_Data_LogicalOperator::AAnd;
        $iOperator |= PHP_APE_Data_LogicalOperator::OOr;
      }
      if( $iOperator & PHP_APE_Data_LogicalOperator::OOr )
        for( $i = 0; $i < $this->iDataQuantity; $i++ ) $abIncludedRows[$i] = $abIncludedRows[$i] || $abIncludedRows_SUB[$i];
      else
        for( $i = 0; $i < $this->iDataQuantity; $i++ ) $abIncludedRows[$i] = $abIncludedRows[$i] && $abIncludedRows_SUB[$i];

    }

    // End
    return $abIncludedRows;
  }

}
