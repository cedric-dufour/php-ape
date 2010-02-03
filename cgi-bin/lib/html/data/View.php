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
 * @package PHP_APE_HTML
 * @subpackage Page
 */

/** Data (view) display object
 *
 * @package PHP_APE_HTML
 * @subpackage Page
 */
abstract class PHP_APE_HTML_Data_View
extends PHP_APE_Data_Any
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Working environment
   * @var PHP_APE_HTML_WorkSpace */
  static protected $roEnvironment;

  /** Resources
   * @var array|string */
  static protected $asResources;

  /** HTML data space
   * @var PHP_APE_DataSpace_HTML */
  static protected $oDataSpace_HTML;

  /** JavaScript data space
   * @var PHP_APE_DataSpace_JavaScript */
  static protected $oDataSpace_JavaScript;

  /** Associated controller
   * @var PHP_APE_HTML_Controller */
  protected $roController;

  /** Data/controller resource identifier (RID)
   * @var mixed */
  protected $sRID;

  /** Associated (queryable) result set
   * @var PHP_APE_Data_isQueryAbleResultSet */
  protected $roResultSet;

  /** Associated function
   * @var PHP_APE_Data_Function */
  protected $roFunction;

  /** Associated query meta code
   * @var integer */
  protected $iQueryMeta;

  /** Controller source
   * @var string */
  protected $sSource;

  /** Data primary key
   * @var mixed */
  protected $mPrimaryKey;

  /** Passthru variables
   * @var array|mixed */
  protected $amPassthruVariables;

  /** Function errors
   * @var array|string */
  protected $asErrors;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new view instance
   *
   * @param PHP_APE_HTML_Controller $roController Associated controller
   * @param string $sName View name
   * @param string $sDescription View description
   */
  public function __construct( PHP_APE_HTML_Controller $roController, $sName = null, $sDescription = null )
  {
    // Initialize member fields
    parent::__construct( $roController->getID(), $sName, $sDescription );
    $this->roController =& $roController;
    $this->sRID = $this->roController->getRID();
  }

  public static function __static()
  {
    if( is_null( self::$roEnvironment ) ) self::$roEnvironment =& PHP_APE_HTML_WorkSpace::useEnvironment();
    if( is_null( self::$asResources ) ) self::$asResources = self::$roEnvironment->loadProperties( 'PHP_APE_HTML_Data_Resources' );
    if( is_null( self::$oDataSpace_HTML ) ) self::$oDataSpace_HTML = new PHP_APE_DataSpace_HTML();
    if( is_null( self::$oDataSpace_JavaScript ) ) self::$oDataSpace_JavaScript = new PHP_APE_DataSpace_JavaScript();
  }


  /*
   * METHODS: controller
   ********************************************************************************/

  /** Returns this view's assocatiated controller (<B>as reference</B>)
   *
   * @return PHP_APE_HTML_Controller
   */
  public function &useController()
  {
    return $this->roController();
  }

  /** Returns this view's data/controller resource identifier (RID)
   *
   * @return string
   */
  public function getRID()
  {
    return $this->sRID;
  }


  /*
   * METHODS: result set
   ********************************************************************************/

  /** Associates the given (queryable) result set to this view
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param PHP_APE_Data_isQueryAbleResultSet $roResultSet Result set (<B>as reference</B>)
   * @param integer $iQueryMeta Result set query meta code
   * @param mixed $mPrimaryKey Data primary key
   */
  final public function attachResultSet( PHP_APE_Data_isQueryAbleResultSet &$roResultSet, $iQueryMeta = PHP_APE_Data_isQueryAbleResultSet::Query_Full, $mPrimaryKey = null )
  {
    $this->roResultSet =& $roResultSet;
    $this->iQueryMeta = (integer)$iQueryMeta;
    if( func_num_args() >= 3 ) $this->mPrimaryKey = PHP_APE_Type_Scalar::parseValue( $mPrimaryKey );
  }

  /** Returns this view's currently associated result set (<B>as reference</B>)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return PHP_APE_Data_isQueryAbleResultSet
   */
  final public function &useResultSet()
  {
    return $this->roResultSet;
  }

  /** Adds the given meta code to this view's currently associated result set
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return integer
   */
  final public function addQueryMeta( $iMetaCode )
  {
    return $this->iQueryMeta |= (integer)$iMetaCode;
  }

  /** Returns this view's currently associated result set's query meta code
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return integer
   */
  final public function getQueryMeta()
  {
    return $this->iQueryMeta;
  }

  /** Detaches this view's currently associated result set
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function detachResultSet()
  {
    unset( $this->roResultSet ); // prevent nasty reference side-effects...
    $this->roResultSet = null;
    $this->iQueryMeta = null;
  }


  /*
   * METHODS: function
   ********************************************************************************/

  /** Associates the given function to this view
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param PHP_APE_Data_Function $roFunction function (<B>as reference</B>)
   * @param mixed $mPrimaryKey Data primary key
   */
  final public function attachFunction( PHP_APE_Data_Function &$roFunction, $mPrimaryKey = null )
  {
    $this->roFunction =& $roFunction;
    if( func_num_args() >= 2 ) $this->mPrimaryKey = $mPrimaryKey;
  }

  /** Returns this view's currently associated function (<B>as reference</B>)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return PHP_APE_Data_Function
   */
  final public function &useFunction()
  {
    return $this->roFunction;
  }

  /** Detaches this view's currently associated function
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function detachFunction()
  {
    unset( $this->roFunction ); // prevent nasty reference side-effects...
    $this->roFunction = null;
  }

  /** Set this view's function arguments using the (controller) request data
   *
   * <P><B>RETURNS:</B> <SAMP>0</SAMP> on success (no errors), the quantity of errors otherwise.</P>
   * <P><B>NOTE:</B> Data error messages can be retrieved using the view's {@link getErrors()} method.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>, <SAMP>PHP_APE_Auth_AuthorizationException</SAMP>.</P>
   * @return integer
   */
  public function setFunctionArguments()
  {
    // Check
    if( is_null( $this->roFunction ) )
      throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'View has no associated function' );

    // Set arguments
    $this->asErrors = null;
    $iDataErrors = $this->roController->setFunctionArguments( $this->roFunction, $rasErrors );
    if( $iDataErrors > 0 ) $this->asErrors = $rasErrors;

    // End
    return $iDataErrors;
  }


  /*
   * METHODS: request
   ********************************************************************************/

  /** Returns the view URL for the given destination (view/action)
   *
   * @param string $sURL Target (sub-) URL
   * @param string $sDestination Page controller destination (view/action)
   * @param boolean $bUsedPopup Popup used to open form
   * @return string
   */
  public function makeRequestURL( $sURL, $sDestination = null, $bUsedPopup = null )
  {
    return $this->roController->makeRequestURL( $sURL, $this->getID(), $sDestination, $this->mPrimaryKey, $this->amPassthruVariables, $this->asErrors, $bUsedPopup );
  }

  /** Sets this view's (data) primary key
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param mixed $mPrimaryKey Primary key
   */
  final public function setPrimaryKey( $mPrimaryKey )
  {
    $this->mPrimaryKey = PHP_APE_Type_Scalar::parseValue( $mPrimaryKey );
  }

  /** Returns this view's (data) primary key
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return mixed
   */
  final public function getPrimaryKey()
  {
    // Check (data) primary key
    // NOTE: (data) primary key CAN NOT change within the same script, since the REQUEST data will necessarly be the same
    if( !is_null( $this->amPrimaryKey ) )
      return $this->amPrimaryKey;

    // Retrieve request primary key
    $this->amPrimaryKey = $this->roController->getPrimaryKey();

    // End
    return $this->amPrimaryKey;
  }

  /** Clears this view's (data) primary key
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function clearPrimaryKey()
  {
    $this->mPrimaryKey = null;
  }

  /** Sets this view's passthru variables
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param array|mixed $amPassthruVariables Passthru variables (associating: <I>name</I> => <I>value</I>)
   */
  final public function setPassthruVariables( $amPassthruVariables = null )
  {
    $this->amPassthruVariables = PHP_APE_Type_Array::parseValue( $amPassthruVariables, true );
  }

  /** Returns this view's passthru variables
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return array|mixed
   */
  final public function getPassthruVariables()
  {
    return $this->amPassthruVariables;
  }

  /** Clears this view's passthru variables
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function clearPassthruVariables()
  {
    $this->amPassthruVariables = null;
  }

  /** Returns this view's request (data) errors
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return array|mixed
   */
  final public function getErrors()
  {
    // Check request errors
    // NOTE: request errors CAN NOT change within the same script, since the REQUEST data will necessarly be the same
    if( !is_null( $this->asErrors ) )
      return $this->asErrors;

    // Retrieve request errors
    $this->asErrors = $this->roController->getErrors();

    // End
    return $this->asErrors;
  }

  /** Clears this view's errors
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function clearErrors()
  {
    $this->asErrors = null;
  }

}

// Initialize static fields
PHP_APE_HTML_Data_View::__static();
