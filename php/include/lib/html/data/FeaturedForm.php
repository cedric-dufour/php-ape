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

/** (Meta-)Featured data form view object
 *
 * @package PHP_APE_HTML
 * @subpackage Page
 */
class PHP_APE_HTML_Data_FeaturedForm
extends PHP_APE_HTML_Data_BasicForm
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Use display preferences
   * @var boolean */
  protected $bUseDisplayPreferences;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new form view instance
   *
   * @param PHP_APE_HTML_Controller $roController Associated controller
   * @param PHP_APE_Data_Function $roFunction Data function
   * @param PHP_APE_Data_isDetailAbleResultSet $roResultSet Data result set
   * @param mixed $mPrimaryKey Data primary key
   * @param integer $iQueryMeta Query meta code (see {@link PHP_APE_Data_isQueryAbleResultSet} constants)
   * @param array|mixed $amPassthruVariables Hidden variables to include in <SAMP>FORM</SAMP> (associating: <I>name</I> => <I>value</I>)
   */
  public function __construct( PHP_APE_HTML_Controller $roController, PHP_APE_Data_Function $roFunction, PHP_APE_Data_isDetailAbleResultSet $roResultSet = null, $mPrimaryKey = null, $iQueryMeta = PHP_APE_Data_isQueryAbleResultSet::Query_Full, $amPassthruVariables = null )
  {
    // Parent constructor
    parent::__construct( $roController, $roFunction, $roResultSet, $mPrimaryKey, $iQueryMeta, $amPassthruVariables );

    // Preferences
    $this->bUseDisplayPreferences = true;
  }


  /*
   * METHODS: preferences
   ********************************************************************************/
  
  /** Set preference for displayable fields
   * @param boolean $bUseDisplayPreferences Use display preferences (and display corresponding HTML element) */
  public function prefUseDisplayPreferences( $bUseDisplayPreferences )
  {
    $this->bUseDisplayPreferences = (boolean)$bUseDisplayPreferences;
  }


  /*
   * METHODS: protected
   ********************************************************************************/

  /** Sets this data detail view's displayable elements (according to preferences)
   */
  protected function _setDisplayedKeys()
  {
    // Call parent method
    parent::_setDisplayedKeys();

    // Environments
    $bGlobalHideOptional = PHP_APE_HTML_Data_View::$roEnvironment->getUserParameter( 'php_ape.html.data.hide.optional' );

    // Display arguments/elements
    
    // ... arguments
    $roArgumentSet =& $this->roFunction->useArgumentSet();
    $amArgumentAllIDs = $roArgumentSet->getElementsIDs(); // Argument set (ordered set) keys are purely numerical; we must use the variables IDs instead
    $amArgumentIDs = $amArgumentAllIDs;
    $amRequiredKeys = $roArgumentSet->getElementsKeysPerMeta( PHP_APE_Data_Argument::Feature_ShowInForm | PHP_APE_Data_Argument::Feature_RequireInForm );
    if( $bGlobalHideOptional )
      $amHiddenKeys = array_diff( $roArgumentSet->getElementsKeys(), $amRequiredKeys );
    else
      $amHiddenKeys = $roArgumentSet->getElementsKeysPerMeta( PHP_APE_Data_Argument::Feature_HideInForm );

    // ... translate arguments keys to elements keys
    $amArgumentIDs = array_values( $amArgumentIDs );
    $amRequiredKeys = array_values( array_intersect_key( $amArgumentAllIDs, array_flip( $amRequiredKeys ) ) );
    $amHiddenKeys = array_values( array_intersect_key( $amArgumentAllIDs, array_flip( $amHiddenKeys ) ) );

    // ... elements
    $amResultSetKeys = array();
    if( $this->roResultSet instanceof PHP_APE_Data_isDetailAbleResultSet )
    {
      $amResultSetKeys = $this->roResultSet->getElementsKeys();
      if( $this->roResultSet instanceof PHP_APE_Data_isMetaDataSet )
        $amHiddenKeys = array_unique( array_merge( $amHiddenKeys, array_diff( $this->roResultSet->getElementsKeysPerMeta( PHP_APE_Data_Field::Feature_HideInForm ), $amArgumentIDs ) ) );
    }

    // ... preferences
    if( ( $this->roResultSet instanceof PHP_APE_Data_isDetailAbleResultSet ) and $this->bUseDisplayPreferences )
    {
      $sResultSetRID = PHP_APE_HTML_Data_Any::makeRID( get_class( $this->roResultSet ) );

      // ... keys enable/hide
      $amResultSetKeys_Enabled = @PHP_APE_HTML_Data_View::$roEnvironment->getUserParameter( 'php_ape.html.data.detail.display.enabled.'.$sResultSetRID );
      if( !is_null( $amResultSetKeys_Enabled ) )
        $amResultSetKeys_Enabled = explode( ':', $amResultSetKeys_Enabled );
      elseif( $this->roResultSet instanceof PHP_APE_Data_isMetaDataSet )
        $amResultSetKeys_Enabled = array_diff( $amResultSetKeys, $this->roResultSet->getElementsKeysPerMeta( PHP_APE_Data_Field::Feature_CollapseInForm ) );
      $amHiddenKeys = array_unique( array_merge( $amHiddenKeys, array_diff( $amResultSetKeys, $amResultSetKeys_Enabled ) ) );
    }

    // ... all put together
    $this->amDisplayedKeys = array_diff( array_unique( array_merge( $amResultSetKeys, $amArgumentIDs ) ), array_diff( $amHiddenKeys, $amRequiredKeys ) );
  }


  /*
   * METHODS: public
   ********************************************************************************/

  /** Returns the meta-featured data form view
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Auth_AuthorizazionException</SAMP>, <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   *
   * @return string
   */
  public function html()
  {
    // Check

    // ... authorization
    if( ( $this->roFunction instanceof PHP_APE_Data_hasAuthorization ) and !$this->roFunction->hasAuthorization() )
      throw new PHP_APE_Auth_AuthorizationException( __METHOD__, 'Permission denied; Class: '.get_class( $this->roFunction ) );
    if( ( $this->roResultSet instanceof PHP_APE_Data_hasAuthorization ) and !$this->roResultSet->hasAuthorization() )
      throw new PHP_APE_Auth_AuthorizationException( __METHOD__, 'Permission denied; Class: '.get_class( $this->roResultSet ) );

    // ... arguments
    if( !$this->roFunction->hasArgumentSet() )
      throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Missing (undefined) arguments; Class: '.get_class( $this->roFunction ) );

    // Set displayable elements (keys)
    $this->_setDisplayedKeys();

    // Query
    $bResetQuery = false;
    if( ( $this->roResultSet instanceof PHP_APE_Data_isDetailAbleResultSet ) and !$this->roResultSet->isQueried() )
    {
      $this->roResultSet->queryEntries( $this->iQueryMeta );
      if( !$this->roResultSet->nextEntry() )
        throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'No entry found (empty result set)' );
      $bResetQuery = true;
    }

    // Hidden arguments
    $amPassthruArguments = array();
    $roArgumentSet = $this->roFunction->useArgumentSet();
    foreach( array_diff( array_values( $roArgumentSet->getElementsIDs() ), $this->amDisplayedKeys ) as $mKey )
    {
      $roArgument =& $roArgumentSet->useElementByID( $mKey );
      if( !( $roArgument->getMeta() & PHP_APE_Data_Argument::Value_Preset ) and 
          ( $this->roResultSet instanceof PHP_APE_Data_isDetailAbleResultSet ) and
          $this->roResultSet->isElement( $mKey ) )
        $roArgument->useContent()->setValue( $this->roResultSet->useElement( $mKey )->useContent()->getValue() );
      $amPassthruArguments[ $mKey ] = $roArgumentSet->useElementByID( $mKey )->useContent()->getValue();
    }
    if( is_array( $this->amPassthruVariables ) )
      $this->amPassthruVariables = array_merge( $amPassthruArguments, $this->amPassthruVariables );
    else
      $this->amPassthruVariables = $amPassthruArguments;

    // Output
    $sOutput = null;

    // ... form
    $sOutput .= PHP_APE_HTML_SmartTags::htmlColumnOpen( 'WIDTH:auto;' ); // we use columns so things appear the same as in FeaturedList.php or FeaturedDetail.php
    $sOutput .= parent::html();
    $sOutput .= PHP_APE_HTML_SmartTags::htmlColumnAdd( 'WIDTH:1px;' ); // we add column so things appear the same as in FeaturedList.php or FeaturedDetail.php
    $sOutput .= PHP_APE_HTML_SmartTags::htmlColumnClose();

    // Reset query
    if( $bResetQuery )
      $this->roResultSet->resetQuery();

    // End
    return $sOutput;
  }

}
