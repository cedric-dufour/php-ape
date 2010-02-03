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
 * @package PHP_APE_Util
 * @subpackage File
 */

/** File resultset class
 *
 * @package PHP_APE_Util
 * @subpackage File
 */
class PHP_APE_Util_File_ResultSet
extends PHP_APE_Data_ArrayResultSet
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Directory path
   * @var string */
  private $sDirectoryPath;

  /** File (base-)name filtering (PERL) regular expression
   * @var string */
  private $sBasenameRegEx;

  /** File primary key field
   * @var string */
  private $sPrimaryKeyField;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new result set instance
   *
   * @param mixed $mID Result set identifier (ID)
   * @param string $sDirectoryPath Directory path
   * @param string $sBasenameRegEx File (base-)name filtering (PERL) regular expression
   * @param string $sName Result set name (default to identifier if <SAMP>empty</SAMP>)
   * @param string $sDescription Result set description
   */
  public function __construct( $mID, $sDirectoryPath, $sBasenameRegEx = null, $sName = null, $sDescription = null )
  {
    // Sanitize input
    $sDirectoryPath = PHP_APE_Type_Path::parseValue( $sDirectoryPath );
    $sBasenameRegEx = PHP_APE_Type_String::parseValue( $sBasenameRegEx );

    // Parent constructor
    parent::__construct( $mID, null, $sName, $sDescription );

    // Initialize member fields
    $this->sDirectoryPath = $sDirectoryPath;
    $this->sBasenameRegEx = $sBasenameRegEx;
    $this->sPrimaryKeyField = 'name';

    // Initialize result set's fields
    foreach( $this->getFieldsTemplates() as $oField )
      $this->setElement( $oField );

    // Initialize result set's arguments
    $this->setArgumentSet( new PHP_APE_Data_ArgumentSet() );
    $roArgumentSet =& $this->useArgumentSet();
    foreach( $this->getArgumentsTemplates() as $oArgument )
      $roArgumentSet->setElement( $oArgument );
  }


  /*
   * METHODS: initialization
   ********************************************************************************/

  /** Retrieves the fields (template) objects
   *
   * @param string $sKeyPrefix Fields keys prefix
   * @return array|PHP_APE_Database_Field
   */
  public static function getFieldsTemplates( $sKeyPrefix = null )
  {
    // Sanitize input
    $sKeyPrefix = PHP_APE_Type_Index::parseValue( $sKeyPrefix );

    // Resources
    $asResources = PHP_APE_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_Util_File_Resources' );

    // Fields
    $aoFields =
      array(

            $sKeyPrefix.'pk' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'pk',
                                    new PHP_APE_Type_String(),
                                    PHP_APE_Data_Field::Type_PrimaryKey |
                                    PHP_APE_Data_Field::Feature_HideAlways,
                                    $asResources['name.pk'],
                                    $asResources['description.pk']
                                    ),

            $sKeyPrefix.'position' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'position',
                                    new PHP_APE_Type_Integer(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.position'],
                                    $asResources['description.position']
                                    ),

            $sKeyPrefix.'name' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'name',
                                    new PHP_APE_Type_String(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_ShowInForm |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_SearchAble,
                                    $asResources['name.name'],
                                    $asResources['description.name']
                                    ),

            $sKeyPrefix.'extension' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'extension',
                                    new PHP_APE_Type_String(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_CollapseInList |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.extension'],
                                    $asResources['description.extension']
                                    ),

            $sKeyPrefix.'size' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'size',
                                    new PHP_APE_Type_Integer(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.size'],
                                    $asResources['description.size']
                                    ),

            $sKeyPrefix.'mode' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'mode',
                                    new PHP_APE_Type_String(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_CollapseInList |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.mode'],
                                    $asResources['description.mode']
                                    ),

            $sKeyPrefix.'uid' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'uid',
                                    new PHP_APE_Type_Integer(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_CollapseInList |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.uid'],
                                    $asResources['description.uid']
                                    ),

            $sKeyPrefix.'user' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'user',
                                    new PHP_APE_Type_String(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_CollapseInList |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.user'],
                                    $asResources['description.user']
                                    ),

            $sKeyPrefix.'gid' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'gid',
                                    new PHP_APE_Type_Integer(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_CollapseInList |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.gid'],
                                    $asResources['description.gid']
                                    ),

            $sKeyPrefix.'group' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'group',
                                    new PHP_APE_Type_String(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_CollapseInList |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.group'],
                                    $asResources['description.group']
                                    ),

            $sKeyPrefix.'accessed' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'accessed',
                                    new PHP_APE_Type_Timestamp(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_CollapseInList |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.accessed'],
                                    $asResources['description.accessed']
                                    ),

            $sKeyPrefix.'modified' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'modified',
                                    new PHP_APE_Type_Timestamp(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_CollapseInList |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.modified'],
                                    $asResources['description.modified']
                                    ),

            $sKeyPrefix.'changed' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'changed',
                                    new PHP_APE_Type_Timestamp(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_CollapseInList |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.changed'],
                                    $asResources['description.changed']
                                    )

            );

    // End
    return $aoFields;
  }

  /** Retrieves the arguments (template) objects
   *
   * @param string $sKeyPrefix Arguments keys prefix
   * @return array|PHP_APE_Database_Argument
   */
  public static function getArgumentsTemplates( $sKeyPrefix = null )
  {
    // Sanitize input
    $sKeyPrefix = PHP_APE_Type_Index::parseValue( $sKeyPrefix );

    // Resources
    $asResources = PHP_APE_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_Util_File_Resources' );

    // Arguments
    $aoArguments =
      array(

            $sKeyPrefix.'pk' =>
            new PHP_APE_Data_Argument( $sKeyPrefix.'pk',
                                       new PHP_APE_Type_String(),
                                       PHP_APE_Data_Argument::Type_PrimaryKey |
                                       PHP_APE_Data_Argument::Feature_HideAlways,
                                       $asResources['name.pk'],
                                       $asResources['description.pk']
                                       )

            );

    // End
    return $aoArguments;
  }


  /*
   * METHODS: fields
   ********************************************************************************/

  /** Returns the directory path of this result set
   *
   * @return string
   */
  public function getDirectoryPath()
  {
    return $this->sDirectoryPath;
  }

  /** Returns the file (base-)name filtering (PERL) regular expression of this result set
   *
   * @return string
   */
  public function getBasenameRegEx()
  {
    return $this->sBasenameRegEx;
  }

  /** Sets the file primary key field
   *
   * @param string $sPrimaryKeyField Field to use as primary key
   */
  public function setPrimaryKeyField( $sPrimaryKeyField )
  {
    $sPrimaryKeyField = PHP_APE_Type_String::parseValue( $sPrimaryKeyField );
    $this->sPrimaryKeyField = $sPrimaryKeyField;
  }

  /** Returns the file primary key field
   *
   * @return string
   */
  public function getPrimaryKeyField()
  {
    return $this->sPrimaryKeyField;
  }


  /*
   * METHODS: PHP_APE_Data_isQueryAbleResultSet - OVERRIDE
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
    // Check underlying array (files info)
    if( !$this->_hasArray() )
    {

      // ... check directory path
      $sDirectoryPath_FS = PHP_APE_Util_File_Any::encodePath( $this->sDirectoryPath );
      if( !is_dir( $sDirectoryPath_FS ) )
        throw new PHP_APE_Data_Exception( __METHOD__, 'Invalid directory; Path: '.$this->sDirectoryPath );
      if( !is_readable( $sDirectoryPath_FS ) )
        throw new PHP_APE_Data_Exception( __METHOD__, 'Unreadable directory; Path: '.$this->sDirectoryPath );

      // ... environment
      $roEnvironment =& PHP_APE_WorkSpace::useEnvironment();
      $sCharSet_Data = $roEnvironment->getVolatileParameter( 'php_ape.data.charset' );
      $sCharSet_File = $roEnvironment->getVolatileParameter( 'php_ape.filesystem.charset' );

      // ... check cache usage
      $bUseCache = (boolean)( $iQueryType & PHP_APE_Data_isQueryAbleResultSet::Query_Cache );

      // ... retrieve data from cache
      $aamFileData = null;
      if( $bUseCache )
      {
        $bSaveCache = true;
        $sCacheSignature = $this->sDirectoryPath.$this->sBasenameRegEx;
        $sCachePath = PHP_APE_CACHE.'/PHP_APE_Util_File_ResultSet#'.sha1( $sCacheSignature ).md5( $sCacheSignature ).'.data';
        if( file_exists( $sCachePath ) and
            ( filemtime( $sCachePath ) > filectime( $sDirectoryPath_FS ) ) )
        {
          $aamFileData = unserialize( file_get_contents( $sCachePath, false ) );
          if( is_array( $aamFileData ) ) $bSaveCache = false;
        }
      }

      // ... load files info from directory
      if( !is_array( $aamFileData ) )
      {
        // ... loop through directory files
        $aamFileData = array( 'pk' => array(),
                              'position' => array(), 'name' => array(), 'extension' => array(),
                              'size' => array(), 'mode' => array(),
                              'uid' => array(), 'user' => array(),
                              'gid' => array(), 'group' => array(),
                              'accessed' => array(), 'modified' => array(), 'changed' => array()
                              );
        $iPosition = 0;
        foreach( glob( $sDirectoryPath_FS.'/*' ) as $sFilePath_FS )
        {
          // ... check file
          if( !is_readable( $sFilePath_FS ) ) continue;

          // ... file info
          $asFileInfo = pathinfo( $sFilePath_FS );

          // ... basename filtering
          $sBasename = array_key_exists( 'basename', $asFileInfo ) ? PHP_APE_Util_File_Any::decodePath( $asFileInfo['basename'] ) : null;
          if( !empty( $this->sBasenameRegEx ) and !preg_match( $this->sBasenameRegEx, $sBasename ) ) continue;

          // ... primary key
          switch( $this->getPrimaryKeyField() )
          {
          case 'position': array_push( $aamFileData['pk'], $iPosition ); break;
          default: array_push( $aamFileData['pk'], $sBasename );
          }

          // ... file position, name and extension
          array_push( $aamFileData['position'], $iPosition++ );
          array_push( $aamFileData['name'], $sBasename );
          array_push( $aamFileData['extension'], array_key_exists( 'extension', $asFileInfo ) ? PHP_APE_Util_File_Any::decodePath( $asFileInfo['extension'] ) : null );
          
          // ... file type and mode
          array_push( $aamFileData['size'], filesize( $sFilePath_FS ) );
          array_push( $aamFileData['mode'], PHP_APE_Util_File_Any::getPermsString( fileperms( $sFilePath_FS ) ) );
          
          // ... file user and group
          $iUID = fileowner( $sFilePath_FS );
          $sUser = @posix_getpwuid( $iUID ); $sUser = is_array( $sUser ) ? $sUser['name'] : null;
          array_push( $aamFileData['uid'], $iUID );
          array_push( $aamFileData['user'], $sUser );
          $iGID = filegroup( $sFilePath_FS );
          $sGroup = @posix_getgrgid( $iGID ); $sGroup = is_array( $sGroup ) ? $sGroup['name'] : null;
          array_push( $aamFileData['gid'], $iGID );
          array_push( $aamFileData['group'], $sGroup );

          // ... file times
          array_push( $aamFileData['accessed'], fileatime( $sFilePath_FS ) );
          array_push( $aamFileData['modified'], filemtime( $sFilePath_FS ) );
          array_push( $aamFileData['changed'], filectime( $sFilePath_FS ) );
        }

      }

      // ... save cache
      if( $bUseCache and $bSaveCache )
      {
        file_put_contents( $sCachePath, serialize( $aamFileData ), LOCK_EX );
        chmod( $sCachePath, 0600 );
      }

      // ... set underlying array (file data)
      $this->_setArray( $aamFileData );

    }

    // Parent method
    return PHP_APE_Data_ArrayResultSet::queryEntries( $iQueryType );
  }


  /*
   * METHODS: file
   ********************************************************************************/

  /** Returns the current entry's (file) primary key
   *
   * @return string
   */
  public function getPrimaryKey()
  {
    return $this->useElement( 'pk' )->useContent()->getValue();
  }

  /** Returns the current entry's (file) position
   *
   * @return integer
   */
  public function getPosition()
  {
    return $this->useElement( 'position' )->useContent()->getValue();
  }

  /** Returns the current entry's (file) basename
   *
   * @return string
   */
  public function getBasename()
  {
    return $this->useElement( 'name' )->useContent()->getValue();
  }

  /** Returns the current entry's (file) user (name)
   *
   * @return string
   */
  public function getUser()
  {
    return $this->useElement( 'user' )->useContent()->getValue();
  }

  /** Returns the current entry's (file) numeric (unique) user identifier (ID)
   *
   * @return string
   */
  public function getUID()
  {
    return $this->useElement( 'uid' )->useContent()->getValue();
  }

  /** Returns the current entry's (file) group (name)
   *
   * @return string
   */
  public function getGroup()
  {
    return $this->useElement( 'group' )->useContent()->getValue();
  }

  /** Returns the current entry's (file) numeric (unique) group identifier (ID)
   *
   * @return string
   */
  public function getGID()
  {
    return $this->useElement( 'gid' )->useContent()->getValue();
  }

}
