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
 * @package PHP_APE
 * @subpackage WorkSpace
 */

/** Core workspace
 *
 * <P><B>USAGE:</B></P>
 * <P>The following static parameters (properties) are provisioned by this workspace:</P>
 * <UL>
 * <LI><SAMP>php_ape.require</SAMP>: additional required dependencies (array) [default: '<SAMP>empty</SAMP>']</LI>
 * <LI><SAMP>php_ape.admin.name</SAMP>: administrator name [default: '<SAMP>Administrator</SAMP>']</LI>
 * <LI><SAMP>php_ape.admin.email</SAMP>: administrator e-mail address [default: '<SAMP>webmaster</SAMP>']</LI>
 * <LI><SAMP>php_ape.admin.phone</SAMP>: administrator phone number [default: <SAMP>null</SAMP>]</LI>
 * <LI><SAMP>php_ape.application.id</SAMP>: application identifier (ID) [default: '<SAMP>PHP_APE</SAMP>']</LI>
 * <LI><SAMP>php_ape.application.name</SAMP>: application name [default: '<SAMP>PHP Application Programming Environment (PHP-APE)</SAMP>']</LI>
 * <LI><SAMP>php_ape.data.charset</SAMP>: data character set (encoding) [default: '<SAMP>ISO-8859-1</SAMP>']</LI>
 * <LI><SAMP>php_ape.data.filter.advanced</SAMP>: advanced data filter usage [default: <SAMP>false</SAMP>]</LI>
 * <LI><SAMP>php_ape.data.filter.or</SAMP>: data filter OR logical mixing [default: <SAMP>false</SAMP>]</LI>
 * <LI><SAMP>php_ape.data.format.boolean</SAMP>: boolean format [default: <SAMP>true / false</SAMP>]</LI>
 * <LI><SAMP>php_ape.data.format.numeric</SAMP>: numeric format [default: <SAMP>Raw</SAMP>]</LI>
 * <LI><SAMP>php_ape.data.format.date</SAMP>: date format [default: <SAMP>ISO</SAMP>]</LI>
 * <LI><SAMP>php_ape.data.format.time</SAMP>: time format [default: <SAMP>ISO</SAMP>]</LI>
 * <LI><SAMP>php_ape.data.format.angle</SAMP>: angle format [default: <SAMP>LITERAL</SAMP>]</LI>
 * <LI><SAMP>php_ape.data.query.cachesize</SAMP>: data query cache size (rows count) [default: <SAMP>1000</SAMP>]</LI>
 * <LI><SAMP>php_ape.data.query.minsize</SAMP>: data query minimum size (rows count) [default: <SAMP>10</SAMP>]</LI>
 * <LI><SAMP>php_ape.data.query.maxsize</SAMP>: data query maximum size (rows count) [default: <SAMP>100</SAMP>]</LI>
 * <LI><SAMP>php_ape.data.query.size</SAMP>: data query preferred size (rows count) [default: <SAMP>25</SAMP>]</LI>
 * <LI><SAMP>php_ape.data.query.page</SAMP>: data query visible pages [default: <SAMP>10</SAMP>]</LI>
 * <LI><SAMP>php_ape.dataspace.input</SAMP>: input dataspace [default: '<SAMP>Text</SAMP>']</LI>
 * <LI><SAMP>php_ape.dataspace.output</SAMP>: output dataspace [default: '<SAMP>HTML</SAMP>']</LI>
 * <LI><SAMP>php_ape.locale.language</SAMP>: language [default: '<SAMP>en</SAMP>']</LI>
 * <LI><SAMP>php_ape.locale.language.list</SAMP>: available languages (array) [default: '<SAMP>en</SAMP>','<SAMP>fr</SAMP>']</LI>
 * <LI><SAMP>php_ape.locale.language</SAMP>: language [default: '<SAMP>en</SAMP>']</LI>
 * <LI><SAMP>php_ape.locale.country.list</SAMP>: available countries (array) [default: '<SAMP>CH</SAMP>']</LI>
 * <LI><SAMP>php_ape.locale.country</SAMP>: country [default: '<SAMP>CH</SAMP>']</LI>
 * <LI><SAMP>php_ape.locale.currency.list</SAMP>: available currencies (array) [default: '<SAMP>EUR</SAMP>']</LI>
 * <LI><SAMP>php_ape.locale.currency</SAMP>: currency [default: '<SAMP>EUR</SAMP>']</LI>
 * <LI><SAMP>php_ape.localize.resource</SAMP>: localize resource/property files [default: <SAMP>false</SAMP>]</LI>
 * <LI><SAMP>php_ape.localize.country</SAMP>: localize resources using country preference [default: <SAMP>false</SAMP>]</LI>
 * <LI><SAMP>php_ape.filesystem.charset</SAMP>: filesystem character set (encoding) [default: '<SAMP>UTF-8</SAMP>']</LI>
 * </UL>
 *
 * @package PHP_APE
 * @subpackage WorkSpace
 */
class PHP_APE_WorkSpace
extends PHP_APE_Environment
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** <I>Magic</I> value: default input dataspace
   * @var string */
  const DataSpace_Input = '#INPUT#';

  /** <I>Magic</I> value: default output dataspace
   * @var string */
  const DataSpace_Output = '#OUTPUT#';


  /*
   * FIELDS: static
   ********************************************************************************/

  /** Work space singleton
   * @var PHP_APE_WorkSpace */
  private static $oWORKSPACE;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs the environment
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   */
  protected function __construct()
  {
    // Call the parent constructor
    parent::__construct();
  }


  /*
   * METHODS: factory
   ********************************************************************************/

  /** Returns the (singleton) environment instance (<B>as reference</B>)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   *
   * @return PHP_APE_WorkSpace
   */
  public static function &useEnvironment()
  {
    if( is_null( self::$oWORKSPACE ) ) self::$oWORKSPACE = new PHP_APE_WorkSpace();
    return self::$oWORKSPACE;
  }


  /*
   * METHODS: verification
   ********************************************************************************/

  /** Verify and sanitize the supplied parameters
   *
   * @param array|string $rasParameters Input/output parameters (<B>as reference</B>)
   */
  protected function _verifyParameters( &$rasParameters )
  {
    // Parent environment
    parent::_verifyParameters( $rasParameters );

    // Additional required files
    if( array_key_exists( 'php_ape.require', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.require' ];
      $rValue = PHP_APE_Type_Array::parseValue( $rValue );
    }

    // Administrator name
    if( array_key_exists( 'php_ape.admin.name', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.admin.name' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = 'Administrator';
    }

    // Administrator e-mail address
    if( array_key_exists( 'php_ape.admin.email', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.admin.email' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = 'webmaster';
    }

    // Administrator phone number
    if( array_key_exists( 'php_ape.admin.phone', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.admin.phone' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = null;
    }

    // Application identifier (ID)
    if( array_key_exists( 'php_ape.application.id', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.application.id' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = 'PHP_APE';
    }

    // Application name
    if( array_key_exists( 'php_ape.application.name', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.application.name' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = 'PHP Application Programming Environment (PHP-APE)';
    }

    // Data character set
    if( array_key_exists( 'php_ape.data.charset', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.data.charset' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = 'ISO-8859-1';
    }

    // Advanced data filter usage
    if( array_key_exists( 'php_ape.data.filter.advanced', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.data.filter.advanced' ];
      $rValue = PHP_APE_Type_Boolean::parseValue( $rValue );
      if( is_null( $rValue ) )
        $rValue = false;
    }

    // Data filter OR logical mixing
    if( array_key_exists( 'php_ape.data.filter.or', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.data.filter.or' ];
      $rValue = PHP_APE_Type_Boolean::parseValue( $rValue );
      if( is_null( $rValue ) )
        $rValue = false;
    }

    // Boolean format
    if( array_key_exists( 'php_ape.data.format.boolean', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.data.format.boolean' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( empty( $rValue ) or $rValue < 0 )
        $rValue = PHP_APE_Type_Boolean::Format_TrueFalse;
    }

    // Numeric format
    if( array_key_exists( 'php_ape.data.format.numeric', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.data.format.numeric' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( empty( $rValue ) or $rValue < 0 )
        $rValue = PHP_APE_Type_Numeric::Format_Raw;
    }

    // Date format
    if( array_key_exists( 'php_ape.data.format.date', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.data.format.date' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( empty( $rValue ) or $rValue < 0 )
        $rValue = PHP_APE_Type_Date::Format_ISO;
    }

    // Time format
    if( array_key_exists( 'php_ape.data.format.time', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.data.format.time' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( empty( $rValue ) or $rValue < 0 )
        $rValue = PHP_APE_Type_Time::Format_ISO;
    }

    // Angle format
    if( array_key_exists( 'php_ape.data.format.angle', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.data.format.angle' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( empty( $rValue ) or $rValue < 0 )
        $rValue = PHP_APE_Type_Angle::Format_Literal;
    }

    // Data query cache size (rows count)
    if( array_key_exists( 'php_ape.data.query.cachesize', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.data.query.cachesize' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( empty( $rValue ) )
        $rValue = 1000;
      elseif( $rValue < 1000 )
        $rValue = 1000;
      elseif( $rValue > 10000 )
        $rValue = 10000;
    }

    // Data query minimum size (rows count)
    if( array_key_exists( 'php_ape.data.query.minsize', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.data.query.minsize' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( empty( $rValue ) )
        $rValue = 5;
      elseif( $rValue < 5 )
        $rValue = 5;
      elseif( $rValue > 100 )
        $rValue = 100;
    }

    // Data query maximum size (rows count)
    if( array_key_exists( 'php_ape.data.query.maxsize', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.data.query.maxsize' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( empty( $rValue ) )
        $rValue = 1000;
      elseif( $rValue < $rasParameters[ 'php_ape.data.query.minsize' ] )
        $rValue = $rasParameters[ 'php_ape.data.query.minsize' ];
      elseif( $rValue > 1000 )
        $rValue = 1000;
    }

    // Data query preferred size (rows count)
    if( array_key_exists( 'php_ape.data.query.size', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.data.query.size' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( empty( $rValue ) )
        $rValue = 10;
      elseif( $rValue < $rasParameters[ 'php_ape.data.query.minsize' ] )
        $rValue = $rasParameters[ 'php_ape.data.query.minsize' ];
      elseif( $rValue > $rasParameters[ 'php_ape.data.query.maxsize' ] )
        $rValue = $rasParameters[ 'php_ape.data.query.maxsize' ];
    }

    // Data query visible pages count
    if( array_key_exists( 'php_ape.data.query.page', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.data.query.page' ];
      $rValue = PHP_APE_Type_Integer::parseValue( $rValue );
      if( empty( $rValue ) )
        $rValue = 10;
      elseif( $rValue < 10 )
        $rValue = 10;
      elseif( $rValue > 100 )
        $rValue = 100;
    }

    // Input dataspace
    if( array_key_exists( 'php_ape.dataspace.input', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.dataspace.input' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = 'Text';
    }

    // Output dataspace
    if( array_key_exists( 'php_ape.dataspace.output', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.dataspace.output' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = 'HTML';
    }

    // Available languages (ISO639 + ISO3166)
    if( array_key_exists( 'php_ape.locale.language.list', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.locale.language.list' ];
      $rValue = PHP_APE_Type_Array::parseValue( $rValue );
      if( empty( $rValue ) )
        $rValue = array( 'en', 'fr' );
    }


    // Language (ISO639 + ISO3166)
    if( array_key_exists( 'php_ape.locale.language', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.locale.language' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = 'en';
    }

    // Available countries (ISO3166)
    if( array_key_exists( 'php_ape.locale.country.list', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.locale.country.list' ];
      $rValue = PHP_APE_Type_Array::parseValue( $rValue );
      if( empty( $rValue ) )
        $rValue = array( 'CH' );
    }

    // Country (ISO3166)
    if( array_key_exists( 'php_ape.locale.country', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.locale.country' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = 'CH';
    }

    // Available currencies (ISO4217)
    if( array_key_exists( 'php_ape.locale.currency.list', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.locale.currency.list' ];
      $rValue = PHP_APE_Type_Array::parseValue( $rValue );
      if( empty( $rValue ) )
        $rValue = array( 'EUR' );
    }

    // Currency (ISO4217)
    if( array_key_exists( 'php_ape.locale.currency', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.locale.currency' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = 'EUR';
    }

    // Localize resources
    if( array_key_exists( 'php_ape.localize.resource', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.localize.resource' ];
      $rValue = PHP_APE_Type_Boolean::parseValue( $rValue );
      if( is_null( $rValue ) )
        $rValue = false;
    }

    // Localize resources using country preference
    if( array_key_exists( 'php_ape.localize.country', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.localize.country' ];
      $rValue = PHP_APE_Type_Boolean::parseValue( $rValue );
      if( is_null( $rValue ) )
        $rValue = false;
    }

    // Filesystem character set
    if( array_key_exists( 'php_ape.filesystem.charset', $rasParameters ) )
    {
      $rValue =& $rasParameters[ 'php_ape.filesystem.charset' ];
      $rValue = trim( PHP_APE_Type_String::parseValue( $rValue ) );
      if( empty( $rValue ) )
        $rValue = 'UTF-8';
    }

  }


  /*
   * METHODS: cookie - OVERRIDE
   ********************************************************************************/

  public function _getCookieName()
  {
    return 'PHP_APE_Environment';
  }


  /*
   * METHODS: static parameters - OVERRIDE
   ********************************************************************************/

  /**
   * @uses PHP_APE_CONF
   */
  protected function _getStaticParametersFilepath()
  {
    return PHP_APE_CONF;
  }

  protected function _mandatoryStaticParameters()
  {
    return array_merge( parent::_mandatoryStaticParameters(),
                        array(
                              'php_ape.require' => null,
                              'php_ape.admin.name' => null, 'php_ape.admin.email' => null, 'php_ape.admin.phone' => null,
                              'php_ape.application.id' => null, 'php_ape.application.name' => null,
                              'php_ape.data.charset' => null,
                              'php_ape.data.filter.advanced' => null, 'php_ape.data.filter.or' => null,
                              'php_ape.data.format.boolean' => null, 'php_ape.data.format.numeric' => null, 'php_ape.data.format.date' => null, 'php_ape.data.format.time' => null, 'php_ape.data.format.angle' => null,
                              'php_ape.data.query.cachesize' => null, 'php_ape.data.query.minsize' => null, 'php_ape.data.query.maxsize' => null,  'php_ape.data.query.size' => null, 'php_ape.data.query.page' => null,
                              'php_ape.dataspace.input' => null, 'php_ape.dataspace.output' => null,
                              'php_ape.locale.language.list' => null, 'php_ape.locale.language' => null,
                              'php_ape.locale.country.list' => null, 'php_ape.locale.country' => null,
                              'php_ape.locale.currency.list' => null, 'php_ape.locale.currency' => null,
                              'php_ape.localize.resource' => null, 'php_ape.localize.country' => null,
                              'php_ape.filesystem.charset' => null
                              )
                        );
  }


  /*
   * METHODS: user parameters - OVERRIDE
   ********************************************************************************/

  protected function _getUserParametersFilepath()
  {
    return PHP_APE_DATA.'/PHP_APE_Environment.USER#'.$this->_getUserKey().'.data';
  }


  /*
   * METHODS: properties
   ********************************************************************************/

  /** Loads properties from their corresponding file
   *
   * <P><B>RETURNS:</B> the properties <I>array</I> on succes.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   *
   * @param string $sResourceName Resource name
   * @param boolean $bLocalized Localized properties [default: <SAMP>php_ape.localize.resource</SAMP> environment preference]
   * @param string $sLanguage Language [default: <SAMP>php_ape.locale.language</SAMP> environment preference]
   * @param string $sCountry Country [default: <SAMP>php_ape.locale.country</SAMP> environment preference]
   * @return array|string
   * @see PHP_APE_Resources::loadProperties()
   */
  public function loadProperties( $sResourceName, $bLocalized = null, $sLanguage = null, $sCountry = null )
  {
    if( is_null( $bLocalized ) ) $bLocalized = $this->getUserParameter( 'php_ape.localize.resource' );
    if( $bLocalized )
    {
      if( !is_scalar( $sLanguage ) or empty( $sLanguage ) ) $sLanguage = $this->getUserParameter( 'php_ape.locale.language' );
      if( !is_scalar( $sCountry ) or empty( $sCountry ) ) $sCountry = $this->getUserParameter( 'php_ape.locale.country' );
      // ... load fully localized properties
      if( $this->getVolatileParameter( 'php_ape.localize.country' ) )
        return PHP_APE_Resources::loadProperties( $sResourceName, $sLanguage, $sCountry );
      // ... load partially localized properties
      else
        return PHP_APE_Resources::loadProperties( $sResourceName, $sLanguage );
    }
    else
    {
      // ... load unlocalized properties
      return PHP_APE_Resources::loadProperties( $sResourceName );
    }
  }


  /*
   * METHODS: include
   ********************************************************************************/

  /** Includes the given (optionally localized) file
   *
   * <P><B>USAGE:</B> the included resource MUST return non-<SAMP>false</SAMP> as its last statement if successfull.</P>
   * <P><B>RETURNS:</B> the included resource's return value (always non-<SAMP>false</SAMP> for localized resources).</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   *
   * @param string $sFilePath File path (excluding any extension)
   * @param string $sFileExtension File extension
   * @param boolean $bLocalized Localized properties [default: <SAMP>php_ape.localize.resource</SAMP> environment preference]
   * @param string $sLanguage Language [default: <SAMP>php_ape.locale.language</SAMP> environment preference]
   * @param string $sCountry Country [default: <SAMP>php_ape.locale.country</SAMP> environment preference]
   * @return mixed
   */
  public function includeFile( $sFilePath, $sFileExtension = 'inc.php', $bLocalized = null, $sLanguage = null, $sCountry = null )
  {
    $sFilePath = PHP_APE_Type_Path::parseValue( $sFilePath );
    $sFileExtension = ltrim( $sFileExtension, '.' );
    if( strlen( $sFileExtension ) > 0 ) $sFileExtension = '.'.$sFileExtension;
    if( is_null( $bLocalized ) ) $bLocalized = $this->getUserParameter( 'php_ape.localize.resource' );
    $mIncludedValue = false;
    if( $bLocalized )
    {
      if( !is_scalar( $sLanguage ) or empty( $sLanguage ) ) $sLanguage = $this->getUserParameter( 'php_ape.locale.language' );
      if( !is_scalar( $sCountry ) or empty( $sCountry ) ) $sCountry = $this->getUserParameter( 'php_ape.locale.country' );
      // ... try to include fully localized file
      if( $mIncludedValue === false and $this->getVolatileParameter( 'php_ape.localize.country' ) )
        $mIncludedValue = PHP_APE_DEBUG ? include( $sFilePath.'.'.strtolower( $sLanguage ).'_'.strtoupper( $sCountry ).$sFileExtension ) : @include( $sFilePath.'.'.strtolower( $sLanguage ).'_'.strtoupper( $sCountry ).$sFileExtension );
      // ... try to include partially localized file
      if( $mIncludedValue === false )
        $mIncludedValue = PHP_APE_DEBUG ? include( $sFilePath.'.'.strtolower( $sLanguage ).$sFileExtension ) : @include( $sFilePath.'.'.strtolower( $sLanguage ).$sFileExtension );
    }
    // ... try to include unlocalized file
    if( $mIncludedValue === false )
      $mIncludedValue = PHP_APE_DEBUG ? include( $sFilePath.$sFileExtension ) : @include( $sFilePath.$sFileExtension );
    // ... eventually report failure to load any file
    if( $bLocalized and $mIncludedValue === false )
      throw new PHP_APE_Exception( __METHOD__, 'Failed to include file; Path: '.$sFilePath.$sFileExtension );
    return $mIncludedValue;
  }


  /*
   * METHODS: file content
   ********************************************************************************/

  /** Retrieves the given (optionally localized) file content
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   *
   * @param string $sFilePath File path (EXCLUDING extension)
   * @param string $sFileExtension File extension
   * @param boolean $bLocalized Localized properties [default: <SAMP>php_ape.localize.resource</SAMP> environment preference]
   * @param string $sLanguage Language [default: <SAMP>php_ape.locale.language</SAMP> environment preference]
   * @param string $sCountry Country [default: <SAMP>php_ape.locale.country</SAMP> environment preference]
   */
  public function getFileContent( $sFilePath, $sFileExtension = null, $bLocalized = null, $sLanguage = null, $sCountry = null )
  {
    $sFilePath = PHP_APE_Type_Path::parseValue( $sFilePath );
    $sFileExtension = ltrim( PHP_APE_Type_Path::parseValue( $sFileExtension ), '.' );
    if( strlen( $sFileExtension ) > 0 ) $sFileExtension = '.'.$sFileExtension;
    if( is_null( $bLocalized ) ) $bLocalized = $this->getUserParameter( 'php_ape.localize.resource' );
    $mContent = false;
    if( $bLocalized )
    {
      if( !is_scalar( $sLanguage ) or empty( $sLanguage ) ) $sLanguage = $this->getUserParameter( 'php_ape.locale.language' );
      if( !is_scalar( $sCountry ) or empty( $sCountry ) ) $sCountry = $this->getUserParameter( 'php_ape.locale.country' );
      // ... try to file_get_contents fully localized file
      if( $mContent === false and $this->getVolatileParameter( 'php_ape.localize.country' ) )
      {
        $sFile = $sFilePath.'.'.strtolower( $sLanguage ).'_'.strtoupper( $sCountry ).$sFileExtension;
        $mContent = PHP_APE_DEBUG ? file_get_contents( $sFile, false ) : @file_get_contents( $sFile, false );
      }
      // ... try to file_get_contents partially localized file
      if( $mContent === false )
      {
        $sFile = $sFilePath.'.'.strtolower( $sLanguage ).$sFileExtension;
        $mContent = PHP_APE_DEBUG ? file_get_contents( $sFile, false ) : @file_get_contents( $sFile, false );
      }
    }
    // ... try to file_get_contents unlocalized file
    if( $mContent === false )
    {
      $sFile = $sFilePath.$sFileExtension;
      $mContent = PHP_APE_DEBUG ? file_get_contents( $sFile, false ) : @file_get_contents( $sFile, false );
    }
    // ... eventually report failure to load any file
    if( $mContent === false )
      throw new PHP_APE_Exception( __METHOD__, 'Failed to retrieve file contents; Path: '.$sFile );

    // End
    return $mContent;
  }


  /*
   * METHODS: dataspace
   ********************************************************************************/

  /** Returns a dataspace object
   *
   * <P><B>NOTE:</B> the following <I>magic</I> dataspace names are also available:</P>
   * <UL>
   * <LI><SAMP>{@link DataSpace_Input}</SAMP>: Environment-specified (volatile parameter) input dataspace</LI>
   * <LI><SAMP>{@link DataSpace_Output}</SAMP>: Environment-specified (volatile parameter) output dataspace</LI>
   * </UL>
   *
   * @param string $sDataspaceName Dataspace name; WARNING: case-sensitive !
   * @param string $sDataspaceClassPrefix The dataspace class prefix to add to the dataspace name to obtain its corresponding class name
   * @return PHP_APE_DataSpace
   */
  public function getDataspace( $sDataspaceName, $sDataspaceClassPrefix = 'PHP_APE_DataSpace_' )
  {
    // Check 'magic' names
    if( $sDataspaceName == self::DataSpace_Input )
      $sDataspaceName == $this->getVolatileParameter( 'php_ape.dataspace.input' );
    elseif( $sDataspaceName == self::DataSpace_Output )
      $sDataspaceName == $this->getVolatileParameter( 'php_ape.dataspace.output' );

    // Build dataspace class name
    $sDataspace_class = $sDataspaceClassPrefix.$sDataspaceName;

    // End
    return new $sDataspace_class();
  }

}
