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
 * @subpackage Classes
 */

/** Load Smarty class/library */
require_once( PHP_APE_HTML_WorkSpace::useEnvironment()->getVolatileParameter( 'php_ape.html.smarty.install.path' ).'/Smarty.class.php' );

/** HTML Smarty (template) object
 *
 * @package PHP_APE_HTML
 * @subpackage Classes
 */
class PHP_APE_HTML_Smarty
extends Smarty
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Template (file)
   * @var string */
  private $sTemplate;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new Smarty (template) instance
   *
   * @param string $sTemplateFile Template file's name (EXCLUDING extension)
   * @param string $sTemplateExtension Template file's extension
   * @param string $sTemplateDir Template (server) path (defaults to dirname( $_SERVER['SCRIPT_NAME'] ) if <SAMP>null</SAMP>)
   * @param boolean $bLocalized Localized template
   * @param string $sLanguage Language [default: <SAMP>php_ape.locale.language</SAMP> environment preference]
   * @param string $sCountry Country [default: <SAMP>php_ape.locale.country</SAMP> environment preference]
   */
  public function __construct( $sTemplateFile, $sTemplateExtension, $sTemplateDir = null, $bLocalized = true, $sLanguage = null, $sCountry = null )
  {
    // Check input
    $sTemplateFile = PHP_APE_Type_Path::parseValue( $sTemplateFile );
    $sTemplateExtension = ltrim( PHP_APE_Type_Path::parseValue( $sTemplateExtension ), '.' );
    if( strlen( $sTemplateExtension ) > 0 ) $sTemplateExtension = '.'.$sTemplateExtension;
    $sTemplateDir = PHP_APE_Type_Path::parseValue( $sTemplateDir );
    if( empty( $sTemplateDir ) ) $sTemplateDir = dirname( $_SERVER['SCRIPT_NAME'] );

    // Environment
    $roEnvironment =& PHP_APE_HTML_WorkSpace::useEnvironment();

    // Initialize Smarty
    $this->template_dir = $sTemplateDir;
    $this->config_dir = $roEnvironment->getVolatileParameter( 'php_ape.html.smarty.config.path' );
    $this->compile_dir = $roEnvironment->getVolatileParameter( 'php_ape.html.smarty.compile.path' );
    $this->compile_id = 'PHP_APE_HTML_Smarty#'.sha1( $sTemplateDir ).md5( $sTemplateDir );
    $this->cache_dir = $roEnvironment->getVolatileParameter( 'php_ape.html.smarty.cache.path' );

    // Initialize template file
    if( $bLocalized )
    {
      if( !is_scalar( $sLanguage ) or empty( $sLanguage ) ) $sLanguage = $roEnvironment->getUserParameter( 'php_ape.locale.language' );
      if( !is_scalar( $sCountry ) or empty( $sCountry ) ) $sCountry = $roEnvironment->getUserParameter( 'php_ape.locale.country' );
      // ... check fully localized template
      if( is_null( $this->sTemplate ) and $roEnvironment->getVolatileParameter( 'php_ape.localize.country' ) )
      {
        $sTemplate = $sTemplateFile.'.'.strtolower( $sLanguage ).'_'.strtoupper( $sCountry ).$sTemplateExtension;
        if( $this->template_exists( $sTemplate ) ) $this->sTemplate = $sTemplate;
      }
      // ... check partially localized template
      if( is_null( $this->sTemplate ) )
      {
        $sTemplate = $sTemplateFile.'.'.strtolower( $sLanguage ).$sTemplateExtension;
        if( $this->template_exists( $sTemplate ) ) $this->sTemplate = $sTemplate;
      }
    }
    // ... check unlocalized template
    if( is_null( $this->sTemplate ) )
    {
      $sTemplate = $sTemplateFile.$sTemplateExtension;
      if( $this->template_exists( $sTemplate ) ) $this->sTemplate = $sTemplate;
    }
    // ... eventually report failure to load any template
    if( is_null( $this->sTemplate ) )
      throw new PHP_APE_HTML_Exception( __METHOD__, 'Failed to retrieve template; Path: '.$sTemplateDir.'/'.$sTemplate );

  }


  /*
   * METHODS: Smarty - OVERRIDE
   ********************************************************************************/

  public function fetch()
  {
    return parent::fetch( $this->sTemplate );
  }

  public function display()
  {
    echo $this->fetch();
  }


  /*
   * METHODS: assign
   ********************************************************************************/

  public function assignElements( PHP_APE_Data_isDataSet $oDataSet, $amDisplayKeys = null, $asErrors = null, $asForceValues = null )
  {

    // Sanitize input
    $amDisplayKeys = PHP_APE_Type_Array::parseValue( $amDisplayKeys );
    $asErrors = PHP_APE_Type_Array::parseValue( $asErrors );
    $asForceValues = PHP_APE_Type_Array::parseValue( $asForceValues );

    // Environment
    $oDataSpace_HTML = new PHP_APE_DataSpace_HTML();

    // Fetch elements data
    $amName = array();
    $amDescription = array();
    $amChoices = array();
    $amValue = array();
    $amOutput = array();
    $amDefault = array();
    $amSample = array();
    $amConstraint = array();
    $amDisplay = array();
    $amError = array();
    foreach( $oDataSet->getElementsKeys() as $mKey )
    {
      $roElement = $oDataSet->useElement( $mKey );
      $roData = $roElement->useContent();
      $amName[$mKey] = ( ( $roElement instanceof PHP_APE_Data_hasName ) and $roElement->hasName() ) ? $roElement->getName() : $mKey;
      $amDescription[$mKey] = ( ( $roElement instanceof PHP_APE_Data_hasDescription ) and $roElement->hasDescription() ) ? $roElement->getDescription() : null;
      $amChoices[$mKey] = ( $roElement instanceof PHP_APE_Data_hasChoices ) ? $roElement->getChoices() : null;
      $amValue[$mKey] = array_key_exists( $mKey, $asForceValues ) ? $asForceValues[$mKey] : $roData->getValueFormatted();
      $sOutput = null;
      if( is_null( $sOutput ) and ( $oDataSet instanceof PHP_APE_HTML_hasOutputHandler ) )
        $sOutput = $oDataSet->getHTMLOutput( $mKey );
      if( is_null( $sOutput ) and ( $roElement instanceof PHP_APE_HTML_hasOutputHandler ) )
        $sOutput = $roElement->getHTMLOutput();
      if( is_null( $sOutput ) )
        $sOutput = $oDataSpace_HTML->formatData( $roData );
      $amOutput[$mKey] = $sOutput;
      $amDefault[$mKey] = ( ( $roData instanceof PHP_APE_Type_hasDefault ) and $roData->hasDefault() ) ? $roData->getDefaultValue() : null;
      $amSample[$mKey] = ( ( $roData instanceof PHP_APE_Type_hasSample ) and $roData->hasSample() ) ? $roData->getSampleString( null, true ) : null;
      $amConstraint[$mKey] = ( ( $roData instanceof PHP_APE_Type_hasConstraints ) and $roData->hasConstraints() ) ? $roData->getConstraintsString() : null;
      $amDisplay[$mKey] = in_array( $mKey, $amDisplayKeys ) ? 1 : 0;
      $amError[$mKey] = array_key_exists( $mKey, $asErrors ) ? $asErrors[$mKey] : null;
    }

    // Assign
    $this->assign( 'Name', $amName );
    $this->assign( 'Description', $amDescription );
    $this->assign( 'Choices', $amChoices );
    $this->assign( 'Value', $amValue );
    $this->assign( 'Output', $amOutput );
    $this->assign( 'Default', $amDefault );
    $this->assign( 'Sample', $amSample );
    $this->assign( 'Constraint', $amConstraint );
    $this->assign( 'Display', $amDisplay );
    $this->assign( 'Error', $amError );

  }

}
