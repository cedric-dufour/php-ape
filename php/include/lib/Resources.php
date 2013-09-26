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

/** Resources handling facility
 *
 * <P><B>USAGE:</B></P>
 * <P>Use this class to ease the process of loading PHP resources, be it class/interface definitions or
 * properties (see {@link PHP_APE_Properties}).</P>
 * <P>This class and its <SAMP>{@link PHP_APE_Resources::loadDefinition() loadDefinition()}</SAMP> method is conveniently
 * used with the PHP <SAMP>__autoload</SAMP> function, which prevents the programmer
 * to <SAMP>include</SAMP> each and every classes being used.</P>
 *
 * <P><B>EXAMPLE:</B></P>
 * <P>First, a resource path must be specified for each resource prefix (the two being separated by a semi-colon),
 * either in the PHP-APE environment global configuration (properties) file (<B>/etc/php/ape/global.conf</B>):</P>
 * <CODE>
 * php_ape.resources.path=MY_CLASS_PREFIX:/my/path/to/my/class/prefix
 * </CODE>
 * <P>Or locally in the <B>PHP script</B>:</P>
 * <CODE>
 * <?php
 * // ...
 * PHP_APE_Resources::addPath( 'MY_CLASS_PREFIX', '/my/path/to/my/class/prefix' );
 * // ...
 * ?>
 * </CODE>
 * <P>Which then leads to the following example script:</P>
 * <CODE>
 * <?php
 * // Load resources
 * require_once( '<PHP_APE_ROOT>/load.php' );
 *
 * // Add a custom resource path for the current script
 * PHP_APE_Resources::addPath( 'MY_CLASS_PREFIX', '/my/path/to/my/class/prefix' );
 *
 * // Instantiate every prefixed classes without needing to include any additional files
 * $a = new MY_CLASS_PREFIX_ClassA; // defined in '/my/path/to/my/class/prefix/ClassA.php'
 * $b = new MY_CLASS_PREFIX_ClassB; // defined in '/my/path/to/my/class/prefix/ClassB.php'
 * ?>
 * </CODE>
 *
 * @package PHP_APE
 * @subpackage WorkSpace
 */
class PHP_APE_Resources
{

  /*
   * FIELDS: static
   ********************************************************************************/

  /** Resources paths */
  private static $asResourcesPaths = null;

  /** Definitions load attempts statistics */
  public static $iIOStatDefinitionsAttempted = 0;

  /** Definitions load statistics */
  public static $iIOStatDefinitionsLoaded = 0;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Initializes the PHP-APE resources
   *
   * <P><B>NOTE:</B> This methods uses the <SAMP>php_ape.resources.path</SAMP> properties defined in
   * {@link PHP_APE_CONF} to preset the resources path.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   */
  public static function init()
  {

    // Check exsiting resources paths
    if( is_array( self::$asResourcesPaths ) ) return;

    // Check cache file
    $bSaveCache = true;
    $sCachePath = PHP_APE_CACHE.'/PHP_APE_Resources.data';
    if( file_exists( $sCachePath ) and filemtime( $sCachePath ) > filemtime( PHP_APE_CONF ) )
    {
      self::$asResourcesPaths = unserialize( file_get_contents( $sCachePath, false ) );
      if( is_array( self::$asResourcesPaths ) ) $bSaveCache = false;
    }

    // Load and add resources paths
    if( !is_array( self::$asResourcesPaths ) )
    {
      // Initialize resources paths
      self::$asResourcesPaths = array();

      // Load configuration
      require( PHP_APE_CONF );

      // Parse configuration
      // USAGE: $_CONFIG['php_ape.resources.path'] = array( '<prefix>' => '<path>' );
      if( array_key_exists( 'php_ape.resources.path', $_CONFIG ) and is_array( $_CONFIG['php_ape.resources.path'] ) )
      {
        foreach( $_CONFIG['php_ape.resources.path'] as $sResourcePrefix => $sResourcePath )
          self::addPath( $sResourcePrefix, $sResourcePath );
      }
    }

    // Save file cache
    if( $bSaveCache )
    {
      file_put_contents( $sCachePath, serialize( self::$asResourcesPaths ), LOCK_EX );
      @chmod( $sCachePath, 0660 );
    }

  }


  /*
   * METHODS
   ********************************************************************************/

  /** Adds a resource path for the given resource name prefix
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   *
   * @param string $sResourcePrefix Resource name prefix
   * @param string $sResourcePath Resource path (where to find the resource definition file)
   */
  public static function addPath( $sResourcePrefix, $sResourcePath ) {
    // Check input
    if( !is_scalar( $sResourcePrefix ) )
      throw new PHP_APE_Exception( __METHOD__, 'Invalid (non-scalar) resource prefix' );
    if( empty( $sResourcePrefix ) )
      throw new PHP_APE_Exception( __METHOD__, 'Missing (empty) resource prefix' );
    if( !is_scalar( $sResourcePath ) )
      throw new PHP_APE_Exception( __METHOD__, 'Invalid (non-scalar) resource path' );
    if( empty( $sResourcePath ) )
      throw new PHP_APE_Exception( __METHOD__, 'Missing (empty) resource path' );

    self::$asResourcesPaths[ $sResourcePrefix ] = $sResourcePath;
    // Let's make sure to have the resources prefixes in reversed string order
    // (required for proper resource loading)
    krsort( self::$asResourcesPaths, SORT_STRING );
  }

  /** Retrieves the <SAMP>prefix</SAMP>, <SAMP>path</SAMP> and <SAMP>filename</SAMP> components for the given resource
   *
   * <P><B>RETURNS:</B> the associative components <I>array</I> on success.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   *
   * @param string $sResourceName Resource name
   * @return string
   */
  public static function getPathComponents( $sResourceName ) {
    // Check input
    if( !is_scalar( $sResourceName ) )
      throw new PHP_APE_Exception( __METHOD__, 'Invalid (non-scalar) resource name' );
    if( empty( $sResourceName ) )
      throw new PHP_APE_Exception( __METHOD__, 'Missing (empty) resource name' );

    // Find resource path
    foreach( self::$asResourcesPaths as $sResourcePrefix => $sResourcePath )
    { // loop through configured resources paths
      if( $sResourcePrefix.'_' != substr( $sResourceName, 0, strlen( $sResourcePrefix )+1 ) ) continue; // prefix does not match
      else
      { // resource path found
        $sResourceFilename = substr( $sResourceName, strlen( $sResourcePrefix ) ); // remove resource prefix
        $sResourceFilename = trim( $sResourceFilename, '_' ); // remove surrounding 'separator' characters
        $asPathComponents =
          array( 'prefix' => $sResourcePrefix,
                 'path' => $sResourcePath,
                 'filename' => $sResourceFilename );
        return $asPathComponents;
      }
    }
    return null;
  }

  /** Loads a PHP definition from its corresponding file (according to configured resources paths)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   *
   * @param string $sResourceName Resource name
   */
  public static function loadDefinition( $sResourceName ) {
    // Check input
    if( !is_scalar( $sResourceName ) )
      throw new PHP_APE_Exception( __METHOD__, 'Invalid (non-scalar) resource name' );
    if( empty( $sResourceName ) )
      throw new PHP_APE_Exception( __METHOD__, 'Missing (empty) resource name' );

    // Find resource components
    $asPathComponents = self::getPathComponents( $sResourceName );
    if( !is_array( $asPathComponents ) )
      throw new PHP_APE_Exception( __METHOD__, 'No matching resource path; Resource: '.$sResourceName );

    // Increment statistics
    ++self::$iIOStatDefinitionsAttempted;

    // Load resource definition
    $sFilePath = $asPathComponents['path'].'/'.$asPathComponents['filename'].'.php';
    include_once( $sFilePath );

    // Check resource has been successfully loaded
    if( !class_exists( $sResourceName, false ) and !interface_exists( $sResourceName, false ) )
      throw new PHP_APE_Exception( __METHOD__, 'Failed to load resource definition; Filepath: '.$sFilePath );

    // Increment statistics
    ++self::$iIOStatDefinitionsLoaded;
  }

  /** Loads properties from their corresponding file (according to configured resources paths)
   *
   * <P><B>RETURNS:</B> the properties <I>array</I> on succes.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   *
   * <P><B>NOTE:</B></P>
   * <P>This methods will fail if no unlocalized can be found, no matter what.</P>
   * <P>On the other hand, localized properties will be handled in a fail-safe manner:</P>
   * <UL>
   * <LI>first, unlocalized properties are loaded;</LI>
   * <LI>then, localized properties are loaded and merged (see PHP <SAMP>array_merge</SAMP>) with their
   * unlocalized counterpart;</LI>
   * <LI>if no localized properties are found, only unlocalized properties are returned.</LI>
   * </UL>
   * <P>This way of doing things allows to manage localization in a flexible and fail-safe way (though
   * some unlocalized data may pop-up, in case of discrepancies between localized and unlocalized properties).</P>
   * <P>Non-ASCII characters shall be encoded using HTML entities (<SAMP>&amp;...;</SAMP>).</P>
   *
   * @param string $sResourceName Resource name
   * @param string $sLanguage Language (ignored if <SAMP>null</SAMP>)
   * @param string $sCountry Country (ignored if <SAMP>null</SAMP>)
   * @return array|string
   */
  public static function loadProperties( $sResourceName, $sLanguage = null, $sCountry = null ) {
    // Check input
    if( !is_scalar( $sResourceName ) )
      throw new PHP_APE_Exception( __METHOD__, 'Invalid (non-scalar) resource name' );
    if( empty( $sResourceName ) )
      throw new PHP_APE_Exception( __METHOD__, 'Missing (empty) resource name' );

    // Find resource components
    $asPathComponents = self::getPathComponents( $sResourceName );
    if( !is_array( $asPathComponents ) )
      throw new PHP_APE_Exception( __METHOD__, 'No matching resource path; Resource: '.$sResourceName );

    // Load resource properties
    $sFilePath = $asPathComponents['path'].'/'.$asPathComponents['filename'].'.res';
    try
    {
      $asProperties = PHP_APE_Properties::loadFile2Array( $sFilePath, false, true, PHP_APE_CACHE );
    }
    catch( PHP_APE_Exception $e )
      {
        throw new PHP_APE_Exception( __METHOD__, 'Failed to load (unlocalized) resource properties; Filepath: '.$sFilePath );
      }

    // Load localized resource properties
    if( !empty( $sLanguage ) and is_scalar( $sLanguage ) )
    {

      // ... using language
      $sFilePath = $asPathComponents['path'].'/'.$asPathComponents['filename'].'.'.strtolower( $sLanguage ).'.res';
      $asProperties = array_merge( $asProperties, PHP_APE_Properties::loadFile2Array( $sFilePath, true, true, PHP_APE_CACHE ) );

      // ... using language + country
      if( !empty( $sCountry ) and is_scalar( $sCountry ) )
      {
        $sFilePath = $asPathComponents['path'].'/'.$asPathComponents['filename'].'.'.strtolower( $sLanguage ).'_'.strtolower( $sCountry ).'.res';
        $asProperties = array_merge( $asProperties, PHP_APE_Properties::loadFile2Array( $sFilePath, true, true, PHP_APE_CACHE ) );
      }

    }

    // End
    return array_map( 'html_entity_decode', $asProperties );
  }

}
