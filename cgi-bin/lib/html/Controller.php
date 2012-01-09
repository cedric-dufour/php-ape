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
 * @subpackage Control
 */

/** Page (data) controller
 *
 * @package PHP_APE_HTML
 * @subpackage Control
 */
class PHP_APE_HTML_Controller
extends PHP_APE_HTML_Factory
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Data/controller resource identifier (RID)
   * @var mixed */
  protected $sRID;

  /** Data/controller resource key
   * @var mixed */
  protected $sKey;

  /** Controller's default destination state
   * @var string */
  protected $sDefaultDestination;

  /** Data/controller form/input handler file
   * @var mixed */
  protected $sFormHandler;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new controller instance
   *
   * @param mixed $mID Data/controller identifier (ID)
   * @param string $sURL Controller (root) URL
   * @param string $sDefaultDestination Controller's default destination state
   * @param string $sFormHandler Controller's form/input handler file
   * @param boolean $bUseSessionKey Set the controller's key based on the session key
   */
  public function __construct( $mID, $sURL, $sDefaultDestination = 'list', $sFormHandler = 'index.php', $bUseSessionKey = true )
  {
    // Sanizite input
    $sDefaultDestination = strtolower( PHP_APE_Type_Index::parseValue( $sDefaultDestination ) );

    // Parent contructor
    parent::__construct( $mID, $sURL );

    // Local construction
    $this->sRID = PHP_APE_HTML_Data_Any::makeRID( $this->mID );
    $this->sKey = PHP_APE_HTML_Data_Any::makeKey( $this->mID, $bUseSessionKey );
    $this->sDefaultDestination = $sDefaultDestination;
    $this->sFormHandler = ltrim( PHP_APE_Type_Path::parseValue( $sFormHandler ), '/' );
  }


  /*
   * METHODS: properties
   ********************************************************************************/

  /** Returns this controller's resource identifier (RID)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getRID()
  {
    return $this->sRID;
  }

  /** Returns this controller's resource key
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getKey()
  {
    return $this->sKey;
  }

  /** Returns the controller form/input handling URL
   *
   * @return string
   */
  public function getFormURL()
  {
    return $this->sURL.'/'.$this->sFormHandler;
  }


  /*
   * METHODS: request
   ********************************************************************************/

  /** Returns this controller's URL for the given destination (view/action)
   *
   * @param string $sURL Target (sub-)URL
   * @param string $sDestination Page controller destination (view/action)
   * @param mixed $mPrimaryKey Result set's data primary key (required for any destination other than 'list')
   * @param array|mixed $amPassthruVariables Variables to include in <SAMP>FORM</SAMP> (associating: <I>name</I> => <I>value</I>)
   * @param array|string $asErrors Error messages (associating: <I>id</I> => <I>message</I>)
   * @param boolean $bUsedPopup Popup used to open form
   * @param string $sSource Page controller source (view)
   * @param array|string $asMessages Additional messages (associating: <I>id</I> => <I>message</I>)
   * @param boolean $bAddResourceKey Add resource key to URL (protect against CSRF)
   * @return string
   */
  public function makeRequestURL( $sURL, $sDestination = null, $mPrimaryKey = null, $amPassthruVariables = null, $asErrors = null, $bUsedPopup = null, $sSource = null, $asMessages = null, $bAddResourceKey = false )
  {
    // Sanitize input
    $sURL = PHP_APE_Type_Path::parseValue( $sURL );
    $sURL = $this->sURL.'/'.ltrim( $sURL, '/' );

    // Constructs page data
    $asRequestData = array();
    if( $bAddResourceKey )
      $asRequestData['__KEY'] = $this->sKey;
    if( !empty( $sDestination ) )
      $asRequestData['__TO'] = PHP_APE_Type_Index::parseValue( $sDestination );
    if( !is_null( $mPrimaryKey ) )
      $asRequestData['__PK'] = $mPrimaryKey;
    if( !is_null( $asErrors ) )
      $asRequestData['__ERROR'] = serialize( PHP_APE_Type_Array::parseValue( $asErrors ) );
    if( !is_null( $bUsedPopup ) )
      $asRequestData['__POPUP'] = $bUsedPopup ? 'true' : 'false';
    if( !empty( $sSource ) )
      $asRequestData['__FROM'] = PHP_APE_Type_Index::parseValue( $sSource );
    if( !is_null( $asMessages ) )
      $asRequestData['__MESSAGE'] = serialize( PHP_APE_Type_Array::parseValue( $asMessages ) );
    if( is_array( $amPassthruVariables ) )
      $asRequestData = array_merge( $asRequestData, array_diff_key( $amPassthruVariables, array_flip( array( '__KEY', '__FROM', '__TO', '__PK', '__ERROR', '__POPUP', '__MESSAGE' ) ) ) );

    // Return page URL
    $sURL = preg_replace( '/&?PHP_APE_DR_'.$this->sRID.'=[^&]*/is', null, $sURL );
    $sURL = ltrim( $sURL, '&' );
    if( !empty( $asRequestData ) )
      $sURL = PHP_APE_Util_URL::addVariable( $sURL, array( 'PHP_APE_DR_'.$this->sRID => PHP_APE_Properties::convertArray2String( $asRequestData ) ) );
    return $sURL;
  }

  /** Returns the current requested URL, relative to this controller's URL base (<SAMP>null</SAMP> if base does not match)
   *
   * @return string
   */
  public function getRequestURL()
  {
    // Sanitize input
    $sURL = $_SERVER['REQUEST_URI'];
    $sBase = preg_replace( '/https?:\/\/[^\/]*/', null, $this->sURL );
    if( strpos( $sURL, $sBase ) !== 0 ) return null;
    return ltrim( substr( $sURL, strlen( $sBase ) ), '/' );
  }

  /** Returns whether request data are existing for this controller
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return boolean
   */
  final public function hasRequestData()
  {
    // Query
    if( isset( $_POST['PHP_APE_DR_'.$this->sRID] ) )
      return true;
    elseif( isset( $_GET['PHP_APE_DR_'.$this->sRID] ) )
      return true;

    // End
    return false;
  }

  /** Retrieves the request data corresponding to this controller (<B>as reference</B>)
   *
   * <P><B>NOTE:</B> this methods use caching to optimize request data retrieval.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return array|string
   */
  final public function &useRequestData()
  {
    static $aasRequestCache;

    // Check cached data
    // NOTE: data for a given controller CAN NOT change within the same script, since the REQUEST data will necessarly be the same
    if( is_array( $aasRequestCache ) and array_key_exists( $this->sRID, $aasRequestCache ) )
      return $aasRequestCache[$this->sRID];

    // Request data
    if( isset( $_POST['PHP_APE_DR_'.$this->sRID] ) )
      $asRequestData = PHP_APE_Properties::convertString2Array( $_POST['PHP_APE_DR_'.$this->sRID], false );
    elseif( isset( $_GET['PHP_APE_DR_'.$this->sRID] ) )
      $asRequestData = PHP_APE_Properties::convertString2Array( $_GET['PHP_APE_DR_'.$this->sRID], false );
    else
      $asRequestData = array();

    // Check and cache request data
    if( array_key_exists( '__KEY', $asRequestData ) and $asRequestData['__KEY'] != $this->sKey )
      throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Invalid request data (key)' );
    $aasRequestCache[$this->sRID] = $asRequestData;

    // End
    return $aasRequestCache[$this->sRID];
  }

  /** Check the request key
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function checkRequestKey()
  {
    $rasRequestData =& $this->useRequestData();
    if( !array_key_exists( '__KEY', $rasRequestData ) or $rasRequestData['__KEY'] != $this->sKey )
      throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Invalid request key' );
  }

  /** Returns this controller's request source (view/action)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getSource()
  {
    // Data
    $rasRequestData =& $this->useRequestData();

    // End
    return array_key_exists( '__FROM', $rasRequestData ) ? strtolower( PHP_APE_Type_String::parseValue( $rasRequestData['__FROM'] ) ) : null;
  }

  /** Returns this controller's destination (view/action)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getDestination()
  {
    // Data
    $rasRequestData =& $this->useRequestData();

    // End
    return array_key_exists( '__TO', $rasRequestData ) ? strtolower( PHP_APE_Type_String::parseValue( $rasRequestData['__TO'] ) ) : $this->sDefaultDestination;
  }

  /** Returns this controller's primary key
   *
   * <P><B>NOTE:</B> an <I>array</I> of primary keys MAY be returned if more than one primary key have been defined.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getPrimaryKey()
  {
    // Data
    $rasRequestData =& $this->useRequestData();

    // End
    return array_key_exists( '__PK', $rasRequestData ) ? $rasRequestData['__PK'] : null;
  }

  /** Returns this controller's popup usage
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return boolean
   */
  final public function isPopup()
  {
    // Data
    $rasRequestData =& $this->useRequestData();

    // End
    return array_key_exists( '__POPUP', $rasRequestData ) ? PHP_APE_Type_Boolean::parseValue( $rasRequestData['__POPUP'] ) : false;
  }

  /** Returns this controller's error messages
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return array|string
   */
  final public function getErrors()
  {
    // Data
    $rasRequestData =& $this->useRequestData();

    // End
    return array_key_exists( '__ERROR', $rasRequestData ) ? PHP_APE_Type_Array::parseValue( unserialize( $rasRequestData['__ERROR'] ) ) : array();
  }

  /** Returns this controller's additional messages
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return array|string
   */
  final public function getMessages()
  {
    // Data
    $rasRequestData =& $this->useRequestData();

    // End
    return array_key_exists( '__MESSAGE', $rasRequestData ) ? PHP_APE_Type_Array::parseValue( unserialize( $rasRequestData['__MESSAGE'] ) ) : array();
  }


  /*
   * METHODS: browsing
   ********************************************************************************/

  /** Returns the controller's URL implementing the given data scroller
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sURL Target URL
   * @param PHP_APE_Data_Scroller $oScroller Data scroller object
   * @return string
   */
  final public function makeScrollerURL( $sURL, PHP_APE_Data_Scroller $oScroller )
  {
    // End
    return PHP_APE_HTML_Data_Scroller::makeURL( $this->mID, $sURL, $oScroller );
  }

  /** Parses the HTML request and returns the data scroller corresponding to this controller
   *
   * <P><B>NOTE:</B> If no corresponding request parameters are found, <SAMP>null</SAMP> is returned.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return PHP_APE_Data_Scroller
   */
  final public function parseScrollerRequest()
  {
    // End
    return PHP_APE_HTML_Data_Scroller::parseRequest( $this->mID );
  }

  /** Returns the controller's URL implementing the given data order
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sURL Target (sub-)URL
   * @param PHP_APE_Data_Order $oOrder Data order object
   * @return string
   */
  final public function makeOrderURL( $sURL, PHP_APE_Data_Order $oOrder )
  {
    // End
    return PHP_APE_HTML_Data_Order::makeURL( $this->mID, $sURL, $oOrder );
  }

  /** Parses the HTML request and returns the data order corresponding to this controller
   *
   * <P><B>NOTE:</B> If no corresponding request parameters are found, <SAMP>null</SAMP> is returned.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return PHP_APE_Data_Order
   */
  final public function parseOrderRequest()
  {
    // End
    return PHP_APE_HTML_Data_Order::parseRequest( $this->mID );
  }

  /** Returns the controller's URL implementing the given data filter
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sURL Target (sub-)URL
   * @param PHP_APE_Data_Filter $oFilter Data filter object
   * @return string
   */
  final public function makeFilterURL( $sURL, PHP_APE_Data_Filter $oFilter )
  {
    // End
    return PHP_APE_HTML_Data_Filter::makeURL( $this->mID, $sURL, $oFilter );
  }

  /** Parses the HTML request and returns the data filter corresponding to this controller
   *
   * <P><B>NOTE:</B> If no corresponding request parameters are found, <SAMP>null</SAMP> is returned.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return PHP_APE_Data_Filter
   */
  final public function parseFilterRequest()
  {
    // End
    return PHP_APE_HTML_Data_Filter::parseRequest( $this->mID );
  }

  /** Returns the controller's URL implementing the given subset data filter
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string $sURL Target (sub-)URL
   * @param PHP_APE_Data_Filter $oSubsetFilter Subset data filter object
   * @return string
   */
  final public function makeSubsetFilterURL( $sURL, PHP_APE_Data_Filter $oSubsetFilter )
  {
    // End
    return PHP_APE_HTML_Data_Filter::makeSubsetURL( $this->mID, $sURL, $oSubsetFilter );
  }

  /** Parses the HTML request and returns the subset data filter corresponding to this controller
   *
   * <P><B>NOTE:</B> If no corresponding request parameters are found, <SAMP>null</SAMP> is returned.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return PHP_APE_Data_Filter
   */
  final public function parseSubsetFilterRequest()
  {
    // End
    return PHP_APE_HTML_Data_Filter::parseRequest( $this->mID );
  }


  /** Parses the HTML request and set the corresponding browsing controls for the given resultset
   *
   * @param PHP_APE_Data_isListAbleResultSet $roResultSet Data result set
   * @param string $bSetScroller Set scroller control
   * @param string $bSetOrder Set order control
   * @param string $bSetFilter Set filter control
   * @param string $bSetSubsetFilter Set subset filter control
   */
  final public function setBrowsingControls( PHP_APE_Data_isListAbleResultSet $roResultSet, $bSetScroller = true, $bSetOrder = true, $bSetFilter = true, $bSetSubsetFilter = true )
  {

    // Set data scroller
    if( $bSetScroller and ( $roResultSet instanceof PHP_APE_Data_isScrollerAble ) and !is_null( $oScroller = self::parseScrollerRequest() ) )
      $roResultSet->setScroller( $oScroller );

    // Set data order
    if( $bSetOrder and ( $roResultSet instanceof PHP_APE_Data_isOrderAble ) and !is_null( $oOrder = self::parseOrderRequest() ) )
      $roResultSet->setOrder( $oOrder );

    // Set data filter
    if( $bSetFilter and ( $roResultSet instanceof PHP_APE_Data_isFilterAble ) and !is_null( $oFilter = self::parseFilterRequest() ) )
      $roResultSet->setFilter( $oFilter );

    // Set subset data filter
    if( $bSetSubsetFilter and ( $roResultSet instanceof PHP_APE_Data_isSubsetFilterAble ) and !is_null( $oSubsetFilter = self::parseSubsetFilterRequest() ) )
      $roResultSet->setSubsetFilter( $oSubsetFilter );

  }


  /*
   * METHODS: view
   ********************************************************************************/

  /** Returns this controller's related list view
   *
   * @param PHP_APE_Data_isListAbleResultSet $roResultSet Data result set
   * @param integer $iQueryMeta Query meta code (see {@link PHP_APE_Data_isQueryAbleResultSet} constants)
   * @param array|mixed $this->amPassthruVariables Hidden variables to include in <SAMP>FORM</SAMP> (associating: <I>name</I> => <I>value</I>)
   * @return PHP_APE_HTML_Data_FeaturedList|PHP_APE_HTML_Data_SmartyList
   */
  public function getListView( PHP_APE_Data_isListAbleResultSet $roResultSet, $iQueryMeta = PHP_APE_Data_isQueryAbleResultSet::Query_Full, $amPassthruVariables = null )
  {
    if( ( $roResultSet instanceof PHP_APE_HTML_hasSmarty ) and $roResultSet->hasSmarty() )
      return $oHTML = new PHP_APE_HTML_Data_SmartyList( $this, $roResultSet, $iQueryMeta, $amPassthruVariables );
    return $oHTML = new PHP_APE_HTML_Data_FeaturedList( $this, $roResultSet, $iQueryMeta, $amPassthruVariables );
  }

  /** Returns this controller's related detail view
   *
   * @param PHP_APE_Data_isDetailAbleResultSet $roResultSet Data result set
   * @param integer $iQueryMeta Query meta code (see {@link PHP_APE_Data_isQueryAbleResultSet} constants)
   * @param array|mixed $this->amPassthruVariables Hidden variables to include in <SAMP>FORM</SAMP> (associating: <I>name</I> => <I>value</I>)
   * @return PHP_APE_HTML_Data_FeaturedDetail|PHP_APE_HTML_Data_SmartyDetail
   */
  public function getDetailView( PHP_APE_Data_isDetailAbleResultSet $roResultSet, $iQueryMeta = PHP_APE_Data_isQueryAbleResultSet::Query_Full, $amPassthruVariables = null )
  {
    if( ( $roResultSet instanceof PHP_APE_HTML_hasSmarty ) and $roResultSet->hasSmarty() )
      return $oHTML = new PHP_APE_HTML_Data_SmartyDetail( $this, $roResultSet, PHP_APE_Type_Scalar::parseValue( $this->getPrimaryKey() ), $iQueryMeta, $amPassthruVariables );
    return $oHTML = new PHP_APE_HTML_Data_FeaturedDetail( $this, $roResultSet, PHP_APE_Type_Scalar::parseValue( $this->getPrimaryKey() ), $iQueryMeta, $amPassthruVariables );
  }

  /** Returns this controller's related form view
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   *
   * @param PHP_APE_Data_isDetailAbleResultSet $roResultSet Data result set
   * @param integer $iQueryMeta Query meta code (see {@link PHP_APE_Data_isQueryAbleResultSet} constants)
   * @param array|mixed $this->amPassthruVariables Hidden variables to include in <SAMP>FORM</SAMP> (associating: <I>name</I> => <I>value</I>)
   * @return PHP_APE_HTML_Data_FeaturedForm|PHP_APE_HTML_Data_SmartyForm
   */
  public function getFormView( PHP_APE_Data_Function $roFunction, PHP_APE_Data_isDetailAbleResultSet $roResultSet = null, $iQueryMeta = PHP_APE_Data_isQueryAbleResultSet::Query_Full, $amPassthruVariables = null )
  {
    if( ( $roFunction instanceof PHP_APE_HTML_hasSmarty ) and $roFunction->hasSmarty() )
      return $oHTML = new PHP_APE_HTML_Data_SmartyForm( $this, $roFunction, $roResultSet, is_null( $roResultSet ) ? null : PHP_APE_Type_Scalar::parseValue( $this->getPrimaryKey() ), $iQueryMeta, $amPassthruVariables );
    return $oHTML = new PHP_APE_HTML_Data_FeaturedForm( $this, $roFunction, $roResultSet, is_null( $roResultSet ) ? null : PHP_APE_Type_Scalar::parseValue( $this->getPrimaryKey() ), $iQueryMeta, $amPassthruVariables );
  }


  /*
   * METHODS: function
   ********************************************************************************/

  /** Set the given function's arguments using this controller's request data
   *
   * <P><B>RETURNS:</B> <SAMP>0</SAMP> on success (no errors), the quantity of errors otherwise.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>, <SAMP>PHP_APE_Auth_AuthorizationException</SAMP>.</P>
   *
   * @param PHP_APE_Data_Function $roFunction Data action function
   * @param array|string $rasErrors Error messages (associating: <I>(argument)id</I> => <I>(error)message</I>; <B>as reference</B>)
   * @param string $sKeyPrefix Request arguments keys' prefix
   * @param boolean $bAllowMissingValues Allow (ignore) missing arguments' values
   * @return integer
   */
  public function setFunctionArguments( PHP_APE_Data_Function $roFunction, &$rasErrors = null, $sKeyPrefix = null, $bAllowMissingValues = false )
  {
    // Check

    // ... authorization
    if( ( $roFunction instanceof PHP_APE_Data_hasAuthorization ) and !$roFunction->hasAuthorization() )
      throw new PHP_APE_Auth_AuthorizationException( __METHOD__, 'Permission denied; Class: '.get_class( $roFunction ) );

    // ... arguments
    if( !$roFunction->hasArgumentSet() )
      throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Missing (undefined) arguments; Class: '.get_class( $roFunction ) );

    // Environment
    $roEnvironment =& PHP_APE_HTML_WorkSpace::useEnvironment();
    $asResources = $roEnvironment->loadProperties( 'PHP_APE_HTML_Data_Resources' );

    // Arguments
    $rasArguments =& $this->useRequestData();
    //echo nl2br(var_export($rasArguments,true)); // DEBUG

    // Initialize output
    $bOutputError = false;
    if( func_num_args() >= 2 )
    {
      $rasErrors = array();
      $bOutputError = true;
    }

    // Set arguments
    $iArgumentsErrors = 0;
    $roArgumentSet =& $roFunction->useArgumentSet();
    //echo nl2br(var_export($roArgumentSet,true)); // DEBUG
    foreach( $roArgumentSet->getElementsKeys() as $mKey )
    {
      // Argument
      $roArgument =& $roArgumentSet->useElement( $mKey );
      $mArgumentID = $sKeyPrefix.$roArgument->getID();
      $iArgumentMeta = $roArgument->getMeta();
      $roData = $roArgument->useContent();
      //echo nl2br(var_export($roData,true)); // DEBUG


      // Set value (if not locked)
      if( !( $iArgumentMeta & PHP_APE_Data_Argument::Value_Lock ) )
      {

        // ... retrieve value
        $sRequestValue = null;
        if( $roData instanceof PHP_APE_Type_FileFromUpload )
        {
          if( array_key_exists( $mArgumentID, $_FILES ) )
            $sRequestValue = basename( $_FILES[$mArgumentID]['name'] );
          elseif( $bAllowMissingValues )
            continue;
        }
        elseif( array_key_exists( $mArgumentID, $rasArguments ) )
          $sRequestValue = trim( PHP_APE_Type_String::parseValue( $rasArguments[$mArgumentID] ) );
        elseif( $bAllowMissingValues )
          continue;
        //echo nl2br(var_export($sRequestValue,true)); // DEBUG

        // ... set value
        try
        {
          $roData->setValue( $roData->parseValue( $sRequestValue, !( $iArgumentMeta & PHP_APE_Data_Argument::Value_Coalesce ) ) );
        }
        catch( PHP_APE_Type_Exception $e )
        {
          if( $bOutputError )
            $rasErrors[$mArgumentID] = PHP_APE_DEBUG ? $e->getMessage() : $asResources['message.error.type'];
          $iArgumentsErrors++;
          continue;
        }
        //echo nl2br(var_export($roData,true)); // DEBUG
      }

      // Check

      // ... feature
      if( !$roData->hasData() and ( $iArgumentMeta & PHP_APE_Data_Argument::Feature_RequireInForm ) )
      {
        if( $bOutputError )
          $rasErrors[$mArgumentID] = $asResources['message.error.empty'];
        $iArgumentsErrors++;
        continue;
      }

      // ... constraints
      if( $roData->hasData() and !$roData->checkConstraints() )
      {
        if( $bOutputError )
          $rasErrors[$mArgumentID] = $asResources['message.error.constraints'];
        $iArgumentsErrors++;
        continue;
      }
      
    }

    // End
    return $iArgumentsErrors;
  }


  /*
   * METHODS: actions
   ********************************************************************************/

  /** Prepares data deletion
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>, <SAMP>PHP_APE_Auth_AuthorizationException</SAMP>.</P>
   *
   * @param PHP_APE_Data_Function $roFunction Deletion function
   * @return boolean
   */
  public function prepareDeleteFunction( PHP_APE_Data_Function $roFunction )
  {
    // Check

    // ... authorization
    if( ( $roFunction instanceof PHP_APE_Data_hasAuthorization ) and !$roFunction->hasAuthorization() )
      throw new PHP_APE_Auth_AuthorizationException( __METHOD__, 'Permission denied; Class: '.get_class( $roFunction ) );

    // ... arguments
    if( !$roFunction->hasArgumentSet() )
      throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Missing (undefined) arguments; Class: '.get_class( $roFunction ) );
    $roArgumentSet = $roFunction->useArgumentSet();

    // ... primary key
    $mPKey = $roArgumentSet->getElementsKeysPerMeta( PHP_APE_Data_Argument::Type_PrimaryKey );
    if( !is_array( $mPKey ) or count( $mPKey ) != 1 )
      throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'No usable primary key; Class: '.get_class( $roFunction ) );

    // End
    return true;
  }

  /** Performs data deletion
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>, <SAMP>PHP_APE_Auth_AuthorizationException</SAMP>.</P>
   *
   * @param PHP_APE_Data_Function $roFunction Deletion function
   * @param array|string $rasErrors Error messages (associating: <I>(argument)id</I> => <I>(error)message</I>; <B>as reference</B>)
   * @return boolean
   */
  public function executeDeleteFunction( PHP_APE_Data_Function $roFunction, &$rasErrors = null )
  {
    // Check request key (prevent CSRF)
    $this->checkRequestKey();

    // Prepare
    $this->prepareDeleteFunction( $roFunction );
    $roArgumentSet = $roFunction->useArgumentSet();

    // ... primary key
    $mPKey = $roArgumentSet->getElementsKeysPerMeta( PHP_APE_Data_Argument::Type_PrimaryKey );
    $mPKey = $mPKey[0];

    // Data
    $amPrimaryKeys = PHP_APE_Type_Array::parseValue( $this->getPrimaryKey(), true );
    if( is_null( $amPrimaryKeys ) )
      throw new PHP_APE_HTML_Data_Exception( __FILE__, 'Undefined (null) primary key' );
    
    // Execute
    // ... loop through primary keys
    foreach( $amPrimaryKeys as $mPrimaryKey )
    {
      $roArgumentSet->useElement( $mPKey )->useContent()->setValue( $mPrimaryKey );
      if( $roFunction->execute()->getValue() !== true )
      {
        if( func_num_args() >= 2 ) $rasErrors = $roFunction->getErrors();
        throw new PHP_APE_HTML_Data_Exception( __FILE__, 'Deletion failed' );
      }
    }

    // End
    return true;
  }

  /** Prepares data insertion
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>, <SAMP>PHP_APE_Auth_AuthorizationException</SAMP>.</P>
   *
   * @param PHP_APE_Data_Function $roFunction Insertion function
   * @return boolean
   */
  public function prepareInsertFunction( PHP_APE_Data_Function $roFunction )
  {
    // Check

    // ... authorization
    if( ( $roFunction instanceof PHP_APE_Data_hasAuthorization ) and !$roFunction->hasAuthorization() )
      throw new PHP_APE_Auth_AuthorizationException( __METHOD__, 'Permission denied; Class: '.get_class( $roFunction ) );

    // ... arguments
    if( !$roFunction->hasArgumentSet() )
      throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Missing (undefined) arguments; Class: '.get_class( $roFunction ) );

    // End
    return true;
  }

  /** Performs data insertion
   *
   * <P><B>RETURNS:</B> the inserted data primary key.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>, <SAMP>PHP_APE_Auth_AuthorizationException</SAMP>.</P>
   *
   * @param PHP_APE_Data_Function $roFunction Insertion function
   * @param array|string $rasErrors Error messages (associating: <I>(argument)id</I> => <I>(error)message</I>; <B>as reference</B>)
   * @return mixed
   */
  public function executeInsertFunction( PHP_APE_Data_Function $roFunction, &$rasErrors = null )
  {
    // Check request key (prevent CSRF)
    $this->checkRequestKey();

    // Prepare
    $this->prepareInsertFunction( $roFunction );

    // Execute
    $iErrors = func_num_args() >= 2 ? $this->setFunctionArguments( $roFunction, $rasErrors ) : $this->setFunctionArguments( $roFunction );
    if( $iErrors > 0 )
      throw new PHP_APE_HTML_Data_Exception( __FILE__, 'Arguments errors' );
    $mPrimaryKey = $roFunction->execute()->getValue();
    if( is_null( $mPrimaryKey ) )
    {
      if( func_num_args() >= 2 ) $rasErrors = $roFunction->getErrors();
      throw new PHP_APE_HTML_Data_Exception( __FILE__, 'Insertion failed' );
    }

    // End
    return $mPrimaryKey;
  }

  /** Prepares data update
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>, <SAMP>PHP_APE_Auth_AuthorizationException</SAMP>.</P>
   *
   * @param PHP_APE_Data_Function $roFunction Update function
   * @return boolean
   */
  public function prepareUpdateFunction( PHP_APE_Data_Function $roFunction )
  {
    // Check

    // ... authorization
    if( ( $roFunction instanceof PHP_APE_Data_hasAuthorization ) and !$roFunction->hasAuthorization() )
      throw new PHP_APE_Auth_AuthorizationException( __METHOD__, 'Permission denied; Class: '.get_class( $roFunction ) );

    // ... arguments
    if( !$roFunction->hasArgumentSet() )
      throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Missing (undefined) arguments; Class: '.get_class( $roFunction ) );
    $roArgumentSet = $roFunction->useArgumentSet();

    // ... primary key
    $mPKey = $roArgumentSet->getElementsKeysPerMeta( PHP_APE_Data_Argument::Type_PrimaryKey );
    if( !is_array( $mPKey ) or count( $mPKey ) != 1 )
      throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'No usable primary key; Class: '.get_class( $roFunction ) );

    // End
    return true;
  }

  /** Performs data update
   *
   * <P><B>RETURNS:</B> the updated data primary key.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>, <SAMP>PHP_APE_Auth_AuthorizationException</SAMP>.</P>
   *
   * @param PHP_APE_Data_Function $roFunction Update function
   * @param array|string $rasErrors Error messages (associating: <I>(argument)id</I> => <I>(error)message</I>; <B>as reference</B>)
   * @return mixed
   */
  public function executeUpdateFunction( PHP_APE_Data_Function $roFunction, &$rasErrors = null )
  {
    // Check request key (prevent CSRF)
    $this->checkRequestKey();

    // Prepare
    $this->prepareUpdateFunction( $roFunction );
    $roArgumentSet = $roFunction->useArgumentSet();

    // ... primary key
    $mPKey = $roArgumentSet->getElementsKeysPerMeta( PHP_APE_Data_Argument::Type_PrimaryKey );
    $mPKey = $mPKey[0];

    // Data
    $mPrimaryKey = PHP_APE_Type_Scalar::parseValue( $this->getPrimaryKey() );
    if( is_null( $mPrimaryKey ) )
      throw new PHP_APE_HTML_Data_Exception( __FILE__, 'Undefined (null) primary key' );

    // Execute
    $roPrimaryKey =& $roArgumentSet->useElement( $mPKey );
    $roPrimaryKey->addMeta( PHP_APE_Data_Argument::Value_Preset );
    $roPrimaryKey->useContent()->setValue( $mPrimaryKey );  
    $iErrors = func_num_args() >= 2 ? $this->setFunctionArguments( $roFunction, $rasErrors ) : $this->setFunctionArguments( $roFunction );
    if( $iErrors > 0 )
      throw new PHP_APE_HTML_Data_Exception( __FILE__, 'Arguments errors' );
    $mPrimaryKey = $roFunction->execute()->getValue();
    if( is_null( $mPrimaryKey ) )
    {
      if( func_num_args() >= 2 ) $rasErrors = $roFunction->getErrors();
      throw new PHP_APE_HTML_Data_Exception( __FILE__, 'Update failed' );
    }

    // End
    return $mPrimaryKey;
  }

  /** Prepares data detail view
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>, <SAMP>PHP_APE_Auth_AuthorizationException</SAMP>.</P>
   *
   * @param PHP_APE_Data_isDetailAbleResultSet $roView Deletion function
   * @return boolean
   */
  public function prepareDetailView( PHP_APE_Data_isDetailAbleResultSet $roView )
  {
    // Check

    // ... authorization
    if( ( $roView instanceof PHP_APE_Data_hasAuthorization ) and !$roView->hasAuthorization() )
      throw new PHP_APE_Auth_AuthorizationException( __METHOD__, 'Permission denied; Class: '.get_class( $roView ) );

    // ... arguments
    if( !$roView->hasArgumentSet() )
      throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Missing (undefined) arguments; Class: '.get_class( $roView ) );
    $roArgumentSet = $roView->useArgumentSet();

    // ... primary key
    $mPKey = $roArgumentSet->getElementsKeysPerMeta( PHP_APE_Data_Argument::Type_PrimaryKey );
    if( !is_array( $mPKey ) or count( $mPKey ) != 1 )
      throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'No usable primary key; Class: '.get_class( $roView ) );
    $mPKey = $mPKey[0];

    // Data
    $mPrimaryKey = PHP_APE_Type_Scalar::parseValue( $this->getPrimaryKey() );
    if( is_null( $mPrimaryKey ) )
      throw new PHP_APE_HTML_Data_Exception( __FILE__, 'Undefined (null) primary key' );
    
    // Prepare
    $roPrimaryKey =& $roArgumentSet->useElement( $mPKey );
    $roPrimaryKey->addMeta( PHP_APE_Data_Argument::Value_Preset );
    $roPrimaryKey->useContent()->setValue( $mPrimaryKey );  

    // End
    return true;
  }

  /** Prepares data list view
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Auth_AuthorizationException</SAMP>.</P>
   *
   * @param PHP_APE_Data_isListAbleResultSet $roView Deletion function
   * @return boolean
   */
  public function prepareListView( PHP_APE_Data_isListAbleResultSet $roView )
  {
    // Check

    // ... authorization
    if( ( $roView instanceof PHP_APE_Data_hasAuthorization ) and !$roView->hasAuthorization() )
      throw new PHP_APE_Auth_AuthorizationException( __METHOD__, 'Permission denied; Class: '.get_class( $roView ) );

    // End
    return true;
  }

}
