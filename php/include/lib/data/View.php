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

/** Data view class
 *
 * @package PHP_APE_Data
 * @subpackage Classes
 */
abstract class PHP_APE_Data_View
extends PHP_APE_Data_FieldSet
implements PHP_APE_Data_isExtendedResultSet, PHP_APE_Data_isQueryAbleResultSet, PHP_APE_Data_isArgumentSetAble, PHP_APE_Data_isScrollerAble, PHP_APE_Data_isOrderAble, PHP_APE_Data_isFilterAble, PHP_APE_Data_isSubsetFilterAble
{

  /*
   * FIELDS
   ********************************************************************************/

  /** View arguments
   * @var PHP_APE_Data_ArgumentSet */
  private $oArgumentSet;

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


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new view
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   *
   * @param mixed $mID View identifier (ID)
   * @param string $sName View name (defaults to identifier if <SAMP>empty</SAMP>)
   * @param string $sDescription View description
   */
  public function __construct( $mID, $sName = null, $sDescription = null )
  {
    parent::__construct( $mID, null, $sName, $sDescription );
  }


  /*
   * METHODS: initialization
   ********************************************************************************/

  /** Resets the view
   *
   * <P><B>SYNOPSIS:</B> This method clears this view's data content, and clears all data limit/offset/order/filter.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   */
  public function reset()
  {
    $this->unsetScroller();
    $this->unsetOrder();
    $this->unsetFilter();
    parent::reset();
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

}
