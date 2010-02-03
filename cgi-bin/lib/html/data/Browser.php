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

/** (Meta-)Featured data browser
 *
 * @package PHP_APE_HTML
 * @subpackage Control
 */
class PHP_APE_HTML_Data_Browser
extends PHP_APE_HTML_Controller
{

  /*
   * CONSTRUCTORS
   ********************************************************************************/

  public function __construct( $mID, $sURL )
  {
    // Parent constructor
    parent::__construct( $mID, $sURL, 'list' );
  }


  /*
   * METHODS: HTML components - OVERRIDE
   ********************************************************************************/

  public function htmlTitle()
  {
    return PHP_APE_HTML_SmartTags::htmlLabel( 'PHP-APE Data Browser', 'M-browse', null, null, null, true, false, 'H1' );
  }


  /*
   * METHODS: request
   ********************************************************************************/

  /** Returns this browser's page URL for the given result set's class name and destination (view/action)
   *
   * <P><B>USAGE:</B> This methods returns the given URL with the appropriate query parameters,
   * so the data page retrieves the appropriate result set's class for display.</P>
   * <P><B>NOTE:</B> The result set's class name is encrypted, in order to avoid having class names
   * supplied from the client without prior server proposal.</P>
   *
   * @param string $sURL Target (sub-)URL
   * @param string $sClassName Result set's class name
   * @param string $sDestination Page controller destination (view/action)
   * @param mixed $mPrimaryKey Result set's data primary key (required for any destination other than 'list')
   * @param array|mixed $amPassthruVariables Variables to include in <SAMP>FORM</SAMP> (associating: <I>name</I> => <I>value</I>)
   * @param array|string $asErrors Error messages (associating: <I>id</I> => <I>message</I>)
   * @param boolean $bUsedPopup Popup used to open form
   * @return string
   */
  public function makeRequestURL( $sURL, $sClassName, $sDestination = null, $mPrimaryKey = null, $amPassthruVariables = null, $asErrors = null, $bUsedPopup = null )
  {
    // Sanitize input
    $sClassName = (string)$sClassName;
    if( !empty( $sClassName ) ) 
    {
      $asRequestData = array( '__VIEW' => PHP_APE_HTML_WorkSpace::useEnvironment()->encryptData( $sClassName ) );
      if( is_array( $amPassthruVariables ) )
        $amPassthruVariables = array_merge( $amPassthruVariables, $asRequestData );
      else
        $amPassthruVariables = $asRequestData;
    }

    // Retrieve URL
    return parent::makeRequestURL( $sURL, $sDestination, $mPrimaryKey, $amPassthruVariables, $asErrors, $bUsedPopup );
  }

  /** Returns this browser's related result set
   *
   * @return PHP_APE_Data_isQueryAbleResultSet
   */
  public function getView()
  {
    static $oView;

    // Check cached data
    // NOTE: data CAN NOT change within the same script, since the REQUEST data will necessarly be the same
    if( !is_null( $oView ) )
      return $oView;

    // Arguments
    $rasRequestData =& $this->useRequestData();

    // Create result set object
    if( !array_key_exists( '__VIEW', $rasRequestData ) )
      throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Missing result set class name' );
    $sViewClass = PHP_APE_HTML_WorkSpace::useEnvironment()->decryptData( PHP_APE_Type_Scalar::parseValue( $rasRequestData['__VIEW'] ) );
    $oView = new $sViewClass();

    // ... check resultset
    if( !( $oView instanceof PHP_APE_Data_isQueryAbleResultSet ) )
      throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Invalid result set class; Class: '.get_class( $oView ) );

    // End
    return $oView;
  }


  /*
   * METHODS: view
   ********************************************************************************/

  /** Returns this browser's (HTML) data output
   *
   * @param integer $iQueryMeta Query meta code (see {@link PHP_APE_Data_isQueryAbleResultSet} constants)
   * @param boolean $bRequireSelectForInsert Require selection for insert
   * @param boolean $bUseControls Use browsing controls
   * @param boolean $bUseHeaderAndFooter Use header and footer elements
   * @param boolean $bUsePopup Use popup for form/input
   * @param boolean $bUseDisplayPreferences Use display preferences (and display corresponding HTML element)
   * @param boolean $bUseOrderPreferences Use order preferences (and display corresponding HTML element)
   * @param boolean $bAllowReorder Allow fields re-ordering (using drag 'n drop)
   */
  public function htmlData( $iQueryMeta = PHP_APE_Data_isQueryAbleResultSet::Query_Full, $bRequireSelectForInsert = false, $bUseControls = true, $bUseHeaderAndFooter = true, $bUsePopup = null, $bUseDisplayPreferences = true, $bUseOrderPreferences = true, $bAllowReorder = true )
  {
    // Retrieve data
    $oView = $this->getView();
    $sSource = $this->getSource();
    $sDestination = $this->getDestination();
    $bIsPopup = $this->isPopup();

    // State controller
    $sOutput = null;
    switch( $sDestination )
    {

    case 'delete':
      // View
      $oView_SOURCE = $oView;
      if( !empty( $sSource ) and $sSource != 'list' )
      {
        if( !( $oView instanceof PHP_APE_Data_isListAble ) )
          throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Source object is not listable; Class: '.get_class( $oView ) );
        $oView = $oView->getListView();
      }

      // Function
      if( !( $oView instanceof PHP_APE_Data_isDeleteAble ) )
        throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Source object is not deleteable; Class: '.get_class( $oView ) );
      $oFunction = $oView->getDeleteFunction();

      // Try
      $rasFunctionErrors = null;
      try
      {
        // Execute
        $this->executeDeleteFunction( $oFunction, $rasFunctionErrors );

        // Redirect
        PHP_APE_Util_BrowserControl::goto( $this->makeRequestURL( null, get_class( $oView ) ), null, true );
      }
      catch( PHP_APE_HTML_Data_Exception $e )
      {
        // Redirect
        PHP_APE_Util_BrowserControl::goto( $this->makeRequestURL( null, get_class( $oView ), null, null, null, $rasFunctionErrors ), null, true );
      }
      break;

    case 'new':
      // View
      if( $sSource == 'list' )
      {
        if( !( $oView instanceof PHP_APE_Data_isDetailAble ) )
          throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Source object is not detailable; Class: '.get_class( $oView ) );
        $oView = $oView->getDetailView();
      }

      // Function
      if( !( $oView instanceof PHP_APE_Data_isInsertAble ) )
        throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Source object is not insertable; Class: '.get_class( $oView ) );
      $oFunction = $oView->getInsertFunction();

      // Prepare
      $this->prepareInsertFunction( $oFunction );

      // Variables
      $amPassthruVariables = array( '__VIEW' => PHP_APE_HTML_WorkSpace::useEnvironment()->encryptData( get_class( $oView ) ) );

      // Output
      $oHTML = $this->getFormView( $oFunction, null, $iQueryMeta, $amPassthruVariables );
      $sOutput .= $this->htmlContentTitle( $oFunction );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlSpacer();
      // ... errors
      $asErrors = $oHTML->getErrors();
      if( count( $asErrors ) )
      {
        $e = null;
        if( array_key_exists( '__GLOBAL', $asErrors ) ) $e = new PHP_APE_HTML_Data_Exception( null, $asErrors['__GLOBAL'] );
        $sOutput .= PHP_APE_HTML_Components::htmlDataException( $e, false, true );
      }
      // ... view
      $sOutput .= $oHTML->html( $bUseHeaderAndFooter, $bUsePopup, null, null, $bUseDisplayPreferences );
      break;

    case 'insert':
      // View
      $oView_SOURCE = $oView;
      if( $sSource == 'list' )
      {
        if( !( $oView instanceof PHP_APE_Data_isDetailAble ) )
          throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Source object is not detailable; Class: '.get_class( $oView ) );
        $oView = $oView->getDetailView();
      }

      // Function
      if( !( $oView instanceof PHP_APE_Data_isInsertAble ) )
        throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Source object is not insertable; Class: '.get_class( $oView ) );
      $oFunction = $oView->getInsertFunction();

      // Try
      $rasFunctionErrors = null;
      try
      {
        // Execute
        $mPrimaryKey = $this->executeInsertFunction( $oFunction, $rasFunctionErrors );

        // Redirect
        if( $bIsPopup ) 
        {
          PHP_APE_Util_BrowserControl::refresh( 'opener' );
          PHP_APE_Util_BrowserControl::close();
        }
        else
          PHP_APE_Util_BrowserControl::goto( $this->makeRequestURL( null, get_class( $oView ), 'detail', $mPrimaryKey ), null, true );
      }
      catch( PHP_APE_HTML_Data_Exception $e )
      {
        // Redirect
        PHP_APE_Util_BrowserControl::goto( $this->makeRequestURL( null, get_class( $oView_SOURCE ), $sSource, $mPrimaryKey, $this->useRequestData(), $rasFunctionErrors, $bIsPopup ), null, true );
      }
      break;

    case 'edit':
      // View
      if( $sSource == 'list' )
      {
        if( !( $oView instanceof PHP_APE_Data_isDetailAble ) )
          throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Source object is not detailable; Class: '.get_class( $oView ) );
        $oView = $oView->getDetailView();
      }

      // Function
      if( !( $oView instanceof PHP_APE_Data_isUpdateAble ) )
        throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Source object is not updateable; Class: '.get_class( $oView ) );
      $oFunction = $oView->getUpdateFunction();

      // Prepare
      $this->prepareDetailView( $oView );
      $this->prepareUpdateFunction( $oFunction );

      // Variables
      $amPassthruVariables = array( '__VIEW' => PHP_APE_HTML_WorkSpace::useEnvironment()->encryptData( get_class( $oView ) ) );

      // Output
      $oHTML = $this->getFormView( $oFunction, $oView, $iQueryMeta, $amPassthruVariables );
      $sOutput .= $this->htmlContentTitle( $oFunction );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlSpacer();
      // ... errors
      $asErrors = $oHTML->getErrors();
      if( count( $asErrors ) )
      {
        $e = null;
        if( array_key_exists( '__GLOBAL', $asErrors ) ) $e = new PHP_APE_HTML_Data_Exception( null, $asErrors['__GLOBAL'] );
        $sOutput .= PHP_APE_HTML_Components::htmlDataException( $e, false, true );
      }
      // ... view
      $sOutput .= $oHTML->html( $bUseHeaderAndFooter, $bUsePopup, null, null, $bUseDisplayPreferences );
      break;

    case 'update':
      // View
      $oView_SOURCE = $oView;
      if( $sSource == 'list' )
      {
        if( !( $oView instanceof PHP_APE_Data_isDetailAble ) )
          throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Source object is not detailable; Class: '.get_class( $oView ) );
        $oView = $oView->getDetailView();
      }

      // Function
      if( !( $oView instanceof PHP_APE_Data_isUpdateAble ) )
        throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Source object is not updateable; Class: '.get_class( $oView ) );
      $oFunction = $oView->getUpdateFunction();

      // Try
      $rasFunctionErrors = null;
      try
      {
        // Execute
        $mPrimaryKey = $this->executeUpdateFunction( $oFunction, $rasFunctionErrors );

        // Redirect
        if( $bIsPopup ) 
        {
          PHP_APE_Util_BrowserControl::refresh( 'opener' );
          PHP_APE_Util_BrowserControl::close();
        }
        else
          PHP_APE_Util_BrowserControl::goto( $this->makeRequestURL( null, get_class( $oView ), 'detail', $mPrimaryKey ), null, true );
      }
      catch( PHP_APE_HTML_Data_Exception $e )
      {
        // Redirect
        PHP_APE_Util_BrowserControl::goto( $this->makeRequestURL( null, get_class( $oView_SOURCE ), $sSource, $mPrimaryKey, $this->useRequestData(), $rasFunctionErrors, $bIsPopup ), null, true );
      }
      break;

    case 'detail':
      // View
      if( $sSource == 'list' )
      {
        if( !( $oView instanceof PHP_APE_Data_isDetailAble ) )
          throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Source object is not detailable; Class: '.get_class( $oView ) );
        $oView = $oView->getDetailView();
      }

      // Prepare
      $this->prepareDetailView( $oView );

      // Variables
      $amPassthruVariables = array( '__VIEW' => PHP_APE_HTML_WorkSpace::useEnvironment()->encryptData( get_class( $oView ) ) );

      // Output
      $oHTML = $this->getDetailView( $oView, $iQueryMeta, $amPassthruVariables );
      $sOutput .= $this->htmlContentTitle( $oView );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlSpacer();
      // ... errors
      $asErrors = $oHTML->getErrors();
      if( count( $asErrors ) )
      {
        $e = null;
        if( array_key_exists( '__GLOBAL', $asErrors ) ) $e = new PHP_APE_HTML_Data_Exception( null, $asErrors['__GLOBAL'] );
        $sOutput .= PHP_APE_HTML_Components::htmlDataException( $e, false, true );
      }
      // ... view
      $sOutput .= $oHTML->html( $bUseHeaderAndFooter, $bUsePopup, $bUseDisplayPreferences, $bAllowReorder );
      break;

    case 'list':
    default:
      // View
      if( !empty( $sSource ) and $sSource != 'list' )
      {
        if( !( $oView instanceof PHP_APE_Data_isListAble ) )
          throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Source object is not listable; Class: '.get_class( $oView ) );
        $oView = $oView->getListView();
      }

      // Prepare
      $this->prepareListView( $oView );

      // Variables
      $amPassthruVariables = array( '__VIEW' => PHP_APE_HTML_WorkSpace::useEnvironment()->encryptData( get_class( $oView ) ) );

      // Output
      $oHTML = $this->getListView( $oView, $iQueryMeta, $amPassthruVariables );
      $sOutput .= $this->htmlContentTitle( $oView );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlSpacer();
      // ... errors
      $asErrors = $oHTML->getErrors();
      if( count( $asErrors ) )
      {
        $e = null;
        if( array_key_exists( '__GLOBAL', $asErrors ) ) $e = new PHP_APE_HTML_Data_Exception( null, $asErrors['__GLOBAL'] );
        $sOutput .= PHP_APE_HTML_Components::htmlDataException( $e, false, true );
      }
      // ... view
      $sOutput .= $oHTML->html( $bRequireSelectForInsert, $bUseControls, $bUseHeaderAndFooter, $bUsePopup, $bUseDisplayPreferences, $bUseOrderPreferences, $bAllowReorder );

    }

    // End
    return $sOutput;
  }

}
