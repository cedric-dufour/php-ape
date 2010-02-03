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

/** Properties handling class
 *
 * <P><B>USAGE:</B></P>
 * <P>Use this class to handle properties <I>strings</I> and create their <I>array</I> equivalent,
 * or store/retrieve them to/from persistent storage.</P>
 * <P>A properties <I>string</I> must comply to the following syntax:</P>
 * <P><SAMP>"[{]name1[=value1[,value2,...]][;name2=value4[,value4,...]][}]"</SAMP></P>
 * <P>where:</P>
 * <UL>
 * <LI><SAMP>{...}</SAMP> are optional enclosing braces</LI>
 * <LI><SAMP>name</SAMP> is the mandatory variable name</LI>
 * <LI><SAMP>value</SAMP> is the optional variable value...</LI>
 * <LI>...using optional <SAMP>'</SAMP> (single) or <SAMP>"</SAMP> (double) quotes</LI>
 * <LI>...using <SAMP>\</SAMP> (backslash) as the escape character</LI>
 * <LI>arrays are passed using the <SAMP>,</SAMP> (comma) separator</LI>
 * <LI>multiple variables are passed using the <SAMP>;</SAMP> (semi-colon) separator</LI>
 * </UL>
 *
 * <P><B>EXAMPLE:</B></P>
 * <P>A typical property <I>string</I> may look like:</P>
 * <P><SAMP>"{a=A1;b=B1,B2,B3;string='A string using the escape \'\\\' character'}"</SAMP></P>
 *
 * @package PHP_APE
 * @subpackage WorkSpace
 */
class PHP_APE_Properties
{

  /*
   * FIELDS: static
   ********************************************************************************/

  /** Properties cache
   * @var array|array|string */
  private static $aasCache = array();

  /** Properties cache attempts statistics */
  public static $iIOStatPropertiesCached = 0;

  /** Properties load attempts statistics */
  public static $iIOStatPropertiesAttempted = 0;

  /** Properties load statistics */
  public static $iIOStatPropertiesLoaded = 0;


  /*
   * METHODS
   ********************************************************************************/

  /** Escapes all applicable characters into their property-compatible representation
   *
   * @param mixed $mValue Property value
   * @param string $sQuote Enclosing (escaped) quote
   * @param boolean $bQuote Enclose output in quotes
   * @return string
   */
  public static function escapeValue( $mValue, $sQuote = '\'', $bQuote = true )
  {
    $sValue = str_replace($sQuote,'\\'.$sQuote,str_replace('\\','\\\\',$mValue));
    if( $bQuote ) $sValue = $sQuote.$sValue.$sQuote;
    return $sValue;
  }

  /** Converts a properties <I>string</I> into a properties <I>array</I>
   *
   * <P><B>RETURNS:</B> the properties <I>array</I> on succes.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   *
   * @param string $sPropertyString Properties <I>string</I>
   * @param boolean $bLowerName Convert variable names to lowercase
   * @return array|string
   */
  public static function convertString2Array( $sPropertyString, $bLowerName = true )
  {
    if( !is_scalar( $sPropertyString ) )
      throw new PHP_APE_Exception( __METHOD__, 'Invalid (non-scalar) properties' );

    // Sanitize property string
    $sPropertyString = trim( $sPropertyString ); // remove surrounding whitespaces
    $sPropertyString = str_replace( "\r\n", "\n", $sPropertyString ); // convert DOS-style linefeeds to Unix-style (makes parsing easier)
    if( substr( $sPropertyString, 0, 1 ) == '{' ) $sPropertyString = substr( $sPropertyString, 1, strlen($sPropertyString)-1 ); // remove (optional) opening brace
    if( substr( $sPropertyString, -1 ) == '}' ) $sPropertyString = substr( $sPropertyString, 0, strlen($sPropertyString)-1 ); // remove (optional) closing brace
    $sPropertyString .= "\n"; // make sure we have an ending linefeed

    // Parse property string into array
    $asArray = array(); // property array
    $bEscape = false; // escaped sequence
    $bName = true; $sName = null; // property name (<name>=...)
    $bValue = false; $mValue = null; // property value (...=<value>)
    $bComment = false; // comment (...# <comment>)
    $bQuote = false; $sQuote = null; // quoted sequence
    $bError = false; $aError = array(); // error
    $iLine=1; $iOffset=0; // parser location
    for( $c = 0, $l = strlen( $sPropertyString ); $c < $l; $c++ ) {
      $iOffset++;
      $sChar = $sPropertyString[$c]; // retrieve character at the current parsing location

      if( $bEscape ) { $mValue .= $sChar; $bEscape = false; } // escaped character is added to value, no matter what
      else
      {
        $bSave = false;
        switch( $sChar ) {

        case '\\':
          // Escape character
          if( $bError ) break;
          if( $bName ) { $bError = true; array_push( $aError, array( $iLine, $iOffset ) ); } // escaping the property name is not allowed
          elseif( $bValue ) $bEscape = true;
          elseif( !$bComment ) { $bError = true; array_push( $aError, array( $iLine, $iOffset ) ); } // (logical) parsing error
          break;

        case '"':
        case "'":
          // Quoting character
          if( $bError ) break;
          if( $bName ) { $bError = true; array_push( $aError, array( $iLine, $iOffset ) ); } // quoting the property name is not allowed
          elseif( $bValue )
          { // currently parsing property value
            if( $bQuote )
            { // opened quote
              if( $sChar == $sQuote ) { $bValue = false; $bQuote = false; $sQuote = null; } // let's close it...
              else $mValue .= $sChar; // ...unless closing quote character does not match opening quote character
            }
            else
            { // no opened quote
              if( trim( $mValue ) ) $iError = $c; // (unescaped) quote character WITHIN a value is not allowed
              else { $mValue = null; $bQuote = true; $sQuote = $sChar; } // open quote
            }
          }
          elseif( !$bComment ) { $bError = true; array_push( $aError, array( $iLine, $iOffset ) ); } // (logical) parsing error
          break;

        case '=':
          // Assignement character
          if( $bError ) break;
          if( $bName )
          { // currently parsing property name
            $bName = false; $sName = $bLowerName ? strtolower( trim( $sName ) ) : trim( $sName ); // save property name
            if( !$sName ) { $bError = true; array_push( $aError, array( $iLine, $iOffset ) ); } // empty property name is not allowed
            else $bValue = true; // next data is value
          }
          elseif( $bValue )
          { // currently parsing property value
            if( $bQuote ) $mValue .= $sChar; // save character as part of value...
            else $iError = $c; // ... unless value is not quoted
          }
          elseif( !$bComment ) { $bError = true; array_push( $aError, array( $iLine, $iOffset ) ); } // (logical) parsing error
          break;

        case ',':
          // Multiple-values separator character
          if( $bError ) break;
          $bSave = false;
          if( $bName ) { $bError = true; array_push( $aError, array( $iLine, $iOffset ) ); } // multiple names are not allowed
          elseif( $bValue )
          { // currently parsing property value
            if( $bQuote ) $mValue .= $sChar; // save character as part of value...
            else
            { // ... unless value is not quoted
              $mValue = trim( $mValue );
              $bSave = true; // save value
            }
          }
          elseif( !$bComment ) { $bSave = true; $bValue = true; } // save empty value
          break;

        case ';':
          // Property separator character
          if( $bError ) { $bError = false; break; } // reset error
          if( $bName )
          { // currently parsing property name
            $sName = $bLowerName ? strtolower( trim( $sName ) ) : trim( $sName );
            $mValue = null;
            $bSave = true; // save empty value
          }
          elseif( $bValue )
          { // currently parsing property value
            if( $bQuote ) $mValue .= $sChar; // save character as part of value...
            else
            { // ... unless value is not quoted
              $mValue = trim( $mValue );
              $bSave = true; $bName = true; $bValue = false; // save value
            }
          }
          elseif( !$bComment ) { $bSave = true; $bName = true; } // save empty value
          break;

        case '#':
          // Comment character
          if( $bError ) break;
          $bSave = false;
          if( $bName )
          { // currently parsing property name
            $sName = $bLowerName ? strtolower( trim( $sName ) ) : trim( $sName );
            $mValue = null;
            $bSave = true; $bName = false; $bComment = true; // save empty value
          }
          elseif( $bValue )
          { // currently parsing property value
            if( $bQuote ) $mValue .= $sChar; // save character as part of value...
            else
            { // ... unless value is not quoted
              $mValue = trim( $mValue );
              $bSave = true; $bValue = false; $bComment = true; // save value
            }
          }
          elseif( !$bComment ) { $bSave = true; $bComment = true; } // save empty value
          break;

        case "\n":
          // Linefeed character
          $iLine++; $iOffset = 0; // update parsing location
          if( $bError ) { $bError = false; break; } // reset error
          if( $bName )
          { // currently parsing property name
            $sName = $bLowerName ? strtolower( trim( $sName ) ) : trim( $sName );
            $mValue = null;
            $bSave = true; // save empty value
          }
          elseif( $bValue )
          { // currently parsing property value
            if( $bQuote ) $mValue .= $sChar; // save character as part of value...
            else
            { // ... unless value is not quoted
              $mValue = trim( $mValue );
              $bSave = true; $bName = true; $bValue = false; // save value
            }
          }
          elseif( $bComment ) { $bName = true; $bComment = false; } // end comment
          else { $bSave = true; $bName = true; } // save empty value
          break;

        default:
          if( $bError ) break;
          if( $bName ) $sName .= $sChar; // save character as part of name
          elseif( $bValue ) $mValue .= $sChar; // save character as part of value
        }

        if( $bSave and $sName )
        { // save property in array
          if( array_key_exists( $sName, $asArray ) )
          { // property already exists -> let's make it an array
            if( !is_array( $asArray[$sName] ) ) $asArray[$sName] = array( $asArray[$sName] );
            array_push( $asArray[$sName] , $mValue );
          }
          else $asArray[$sName] = $mValue; // save property
          if( !$bValue ) $sName = null; // if not parsing multiple values (cf. multiple-values separator), reset name
          $mValue = null; // reset value
        }

        //echo "[DEBUG] Line:$iLine, Character:$iOffset, Symbol:$sChar, Name:$bName, Value:$bValue, Comment:$bComment, Error:$bError<BR />\r\n";
      }
    }

    // Check error
    if( count( $aError ) )
    { // log error
      PHP_APE_Logger::log( __METHOD__, 'Parsing error; Property String: '.$sPropertyString,E_USER_WARNING);
      foreach( $aError as $aErrorItem ) PHP_APE_Logger::log( __METHOD__, 'Line: '.($aErrorItem[0]).'; Offset: '.($aErrorItem[1]),E_USER_NOTICE);
    }

    // Return properties array
    return $asArray;
  }

  /** Converts a properties <I>array</I> into a properties <I>string</I>
   *
   * <P><B>RETURNS:</B> the properties <I>string</I> on succes.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   *
   * @param array|string $asArray Properties <I>array</I>
   * @param boolean $bBrace Add braces
   * @param boolean $bNewLine New lines after each statement
   * @return string
   */
  public static function convertArray2String( $asArray, $bBrace = true, $bNewLine = false )
  {
    // Check input
    if( !is_array($asArray) )
      throw new PHP_APE_Exception( __METHOD__, 'Invalid (non-array) properties' );

    // Build properties string
    $sPropertyString=null;
    foreach( $asArray as $sName => $mValue )
    { // loop through array
      $sDataItem=null;
      if( is_array( $mValue ) )
      { // value is an array
        foreach( $mValue as $mValue_sub ) $sDataItem .= ($sDataItem?',':null).self::escapeValue( $mValue_sub );
      }
      else
      { // value is scalar
        $sDataItem = self::escapeValue( $mValue );
      }
      // add property to string
      $sPropertyString .= ($sPropertyString?';'.($bNewLine?"\n":null):null).$sName.'='.$sDataItem;
    }
    // add braces
    if( $bBrace ) $sPropertyString = '{'.$sPropertyString.'}';
    
    // Return properties string
    return $sPropertyString;
  }

  /** Saves a properties <I>array</I> to file
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   *
   * @param array|string $asArray Properties <I>array</I>
   * @param string $sFilePath File full path
   */
  public static function saveArray2File( $asArray, $sFilePath )
  {
    unset( self::$aasCache[ $sFilePath ] ); // clear cache
    if( false === file_put_contents( $sFilePath, self::convertsArray2String( $asArray, false, true )."\n" ) )
      throw new PHP_APE_Exception( __METHOD__, 'Failed to save properties to file; Filepath: '.$sFilePath );
  }

  /** Retrieves a properties <I>array</I> from file
   *
   * <P><B>RETURNS:</B> the properties <I>array</I> on succes.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Exception</SAMP>.</P>
   *
   * <P><B>NOTE:</B></P>
   * <OL>
   * <LI>Properties names are converted to lowercase;</LI>
   * <LI>This method uses an optional (memory) caching mechanism, in order to limit filesystem access.</LI>
   * <LI>This method uses an integrated (file) caching mechanism, in order to limit properties parsing/processing.</LI>
   * </OL>
   *
   * @param string $sFilePath File full path
   * @param boolean $bGracefulError Handle errors gracefully (do not throw exception)
   * @param boolean $bUseMemoryCache Use (memory) caching mechanism
   * @param string $sFileCachePath Use (file) caching mechanism with this path
   * @return array|string
   */
  public static function loadFile2Array( $sFilePath, $bGracefulError = false, $bUseMemoryCache = true, $sFileCachePath = null )
  {
    // Check memory cache
    if( $bUseMemoryCache and array_key_exists( $sFilePath, self::$aasCache ) ) return self::$aasCache[ $sFilePath ];

    // Check file cache
    $asProperties = null;
    $bUseFileCache = !empty( $sFileCachePath );
    if( $bUseFileCache )
    {
      ++self::$iIOStatPropertiesCached;
      $bSaveFileCache = true;
      $sFileCachePath .= '/PHP_APE_Properties#'.sha1( $sFilePath ).md5( $sFilePath ).'.data';
      if( file_exists( $sFileCachePath ) and
          ( !file_exists( $sFilePath ) or filemtime( $sFileCachePath ) > filemtime( $sFilePath ) ) )
      {
        $asProperties = unserialize( file_get_contents( $sFileCachePath, false ) );
        if( is_array( $asProperties ) ) $bSaveFileCache = false;
      }
    }

    // Load and parse properties
    if( !is_array( $asProperties ) )
    {
      ++self::$iIOStatPropertiesAttempted;

      // Check properties file
      if( file_exists( $sFilePath ) )
      {
        $asProperties = file_get_contents( $sFilePath, false );
        if( $asProperties === false )
        {
          if( !$bGracefulError )
            throw new PHP_APE_Exception( __METHOD__, 'Failed to load properties from file; Filepath: '.$sFilePath );
          $asProperties = array();
        }
        else
        {
          ++self::$iIOStatPropertiesLoaded;
          $asProperties =& self::convertString2Array( $asProperties, true );
        }
      }
      else
      {
        if( !$bGracefulError )
          throw new PHP_APE_Exception( __METHOD__, 'Missing (unexisting) properties file; Filepath: '.$sFilePath );
        elseif( PHP_APE_DEBUG )
          PHP_APE_Logger::log( __METHOD__, 'Missing (unexisting) properties file; Filepath: '.$sFilePath, E_USER_WARNING );
        $asProperties = array();
      }
    }

    // Save file cache
    if( $bUseFileCache and $bSaveFileCache )
    {
      file_put_contents( $sFileCachePath, serialize( $asProperties ), LOCK_EX );
      @chmod( $sFileCachePath, 0660 );
    }

    // Save memory cache
    if( $bUseMemoryCache )
      self::$aasCache[ $sFilePath ] =& $asProperties;

    // End
    return $asProperties;
  }
}
