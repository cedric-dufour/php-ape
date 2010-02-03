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
 * @subpackage Image
 */

/** Image's result set class
 *
 * @package PHP_APE_Util
 * @subpackage Image
 */
class PHP_APE_Util_Image_ResultSet
extends PHP_APE_Util_File_ResultSet
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Allow only Web-compatibles images (GIF, JPEG and PNG)
   * @var boolean */
  private $bAllowOnlyWebCompatible;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new result set instance
   *
   * @param mixed $mID Result set identifier (ID)
   * @param string $sDirectoryPath Directory path
   * @param string $sBasenameRegEx File (base-)name filtering (PERL) regular expression
   * @param boolean $bAllowOnlyWebCompatible Allow only Web-compatibles images (GIF, JPEG and PNG)
   * @param string $sName Result set name (default to identifier if <SAMP>empty</SAMP>)
   * @param string $sDescription Result set description
   */
  public function __construct( $mID, $sDirectoryPath, $sBasenameRegEx = null, $bAllowOnlyWebCompatible = true, $sName = null, $sDescription = null )
  {
    // Parent constructor
    parent::__construct( $mID, $sDirectoryPath, $sBasenameRegEx, $sName, $sDescription );

    // Initialize member fields
    $this->bAllowOnlyWebCompatible = (boolean)$bAllowOnlyWebCompatible;
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
    $asResources = PHP_APE_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_Util_Image_Resources' );

    // Fields
    $aoFields =
      array(

            $sKeyPrefix.'format' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'format',
                                    new PHP_APE_Type_String(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.format'],
                                    $asResources['description.format']
                                    ),

            $sKeyPrefix.'width' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'width',
                                    new PHP_APE_Type_Integer(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.width'],
                                    $asResources['description.width']
                                    ),

            $sKeyPrefix.'height' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'height',
                                    new PHP_APE_Type_Integer(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.height'],
                                    $asResources['description.height']
                                    ),

            $sKeyPrefix.'exif_timestamp' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'exif_timestamp',
                                    new PHP_APE_Type_String(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_CollapseInList |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.timestamp'],
                                    $asResources['description.timestamp']
                                    ),

            $sKeyPrefix.'exif_camerabrand' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'exif_camerabrand',
                                    new PHP_APE_Type_String(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_CollapseInList |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.camerabrand'],
                                    $asResources['description.camerabrand']
                                    ),

            $sKeyPrefix.'exif_cameramodel' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'exif_cameramodel',
                                    new PHP_APE_Type_String(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_CollapseInList |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.cameramodel'],
                                    $asResources['description.cameramodel']
                                    ),

            $sKeyPrefix.'exif_exposure' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'exif_exposure',
                                    new PHP_APE_Type_String(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_CollapseInList |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.exposure'],
                                    $asResources['description.exposure']
                                    ),

            $sKeyPrefix.'exif_aperture' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'exif_aperture',
                                    new PHP_APE_Type_String(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_CollapseInList |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.aperture'],
                                    $asResources['description.aperture']
                                    ),

            $sKeyPrefix.'exif_sensitivity' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'exif_sensitivity',
                                    new PHP_APE_Type_String(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_CollapseInList |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.sensitivity'],
                                    $asResources['description.sensitivity']
                                    ),

            $sKeyPrefix.'iptc_name' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'iptc_name',
                                    new PHP_APE_Type_String(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_CollapseInList |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.name'],
                                    $asResources['description.name']
                                    ),

            $sKeyPrefix.'iptc_headline' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'iptc_headline',
                                    new PHP_APE_Type_String(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_HideInList,
                                    $asResources['name.headline'],
                                    $asResources['description.headline']
                                    ),

            $sKeyPrefix.'iptc_caption' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'iptc_caption',
                                    new PHP_APE_Type_String(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_HideInList,
                                    $asResources['name.caption'],
                                    $asResources['description.caption']
                                    ),

            $sKeyPrefix.'iptc_author' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'iptc_author',
                                    new PHP_APE_Type_String(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_CollapseInList |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.author'],
                                    $asResources['description.author']
                                    ),

            $sKeyPrefix.'iptc_copyright' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'iptc_copyright',
                                    new PHP_APE_Type_String(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_CollapseInList |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.copyright'],
                                    $asResources['description.copyright']
                                    ),

            $sKeyPrefix.'iptc_category' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'iptc_category',
                                    new PHP_APE_Type_String(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_CollapseInList |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.category'],
                                    $asResources['description.category']
                                    ),

            $sKeyPrefix.'iptc_subcategories' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'iptc_subcategories',
                                    new PHP_APE_Type_String(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_CollapseInList |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.subcategories'],
                                    $asResources['description.subcategories']
                                    ),

            $sKeyPrefix.'iptc_keywords' =>
            new PHP_APE_Data_Field( $sKeyPrefix.'iptc_keywords',
                                    new PHP_APE_Type_String(),
                                    PHP_APE_Data_Field::Type_Data |
                                    PHP_APE_Data_Field::Feature_HideInForm |
                                    PHP_APE_Data_Field::Feature_CollapseInList |
                                    PHP_APE_Data_Field::Feature_OrderAble |
                                    PHP_APE_Data_Field::Feature_FilterAble,
                                    $asResources['name.keywords'],
                                    $asResources['description.keywords']
                                    )

            );

    // End
    return array_merge( PHP_APE_Util_File_ResultSet::getFieldsTemplates( $sKeyPrefix ), $aoFields );
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
      $sDirectoryPath_FS = PHP_APE_Util_File_Any::encodePath( $this->getDirectoryPath() );
      if( !is_dir( $sDirectoryPath_FS ) )
        throw new PHP_APE_Data_Exception( __METHOD__, 'Invalid directory; Path: '.$this->getDirectoryPath() );
      if( !is_readable( $sDirectoryPath_FS ) )
        throw new PHP_APE_Data_Exception( __METHOD__, 'Unreadable directory; Path: '.$this->getDirectoryPath() );

      // ... environment
      $roEnvironment =& PHP_APE_WorkSpace::useEnvironment();
      $sCharSet_Data = $roEnvironment->getVolatileParameter( 'php_ape.data.charset' );
      $sCharSet_Image = $roEnvironment->getVolatileParameter( 'php_ape.filesystem.charset' );

      // ... check cache usage
      $bUseCache = (boolean)( $iQueryType & PHP_APE_Data_isQueryAbleResultSet::Query_Cache );

      // ... retrieve data from cache
      $aamImageData = null;
      if( $bUseCache )
      {
        $bSaveCache = true;
        $sCacheSignature = $this->getDirectoryPath().$this->getBasenameRegEx();
        $sCachePath = PHP_APE_CACHE.'/PHP_APE_Util_Image_ResultSet#'.sha1( $sCacheSignature ).md5( $sCacheSignature ).'.data';
        if( file_exists( $sCachePath ) and
            ( filemtime( $sCachePath ) > filectime( $sDirectoryPath_FS ) ) )
        {
          $aamImageData = unserialize( file_get_contents( $sCachePath, false ) );
          if( is_array( $aamImageData ) ) $bSaveCache = false;
        }
      }

      // ... load files info from directory
      if( !is_array( $aamImageData ) )
      {
        // ... loop through directory files
        $aamImageData = array( 'pk' => array(), 
                               'position' => array(), 'name' => array(), 'extension' => array(),
                               'size' => array(), 'mode' => array(),
                               'uid' => array(), 'user' => array(),
                               'gid' => array(), 'group' => array(),
                               'accessed' => array(), 'modified' => array(), 'changed' => array(),
                               'format' => array(), 'width' => array(), 'height' => array(),
                               'exif_timestamp' => array(),
                               'exif_camerabrand' => array(), 'exif_cameramodel' => array(),
                               'exif_exposure' => array(), 'exif_aperture' => array(), 'exif_sensitivity' => array(),
                               'iptc_name' => array(), 'iptc_headline' => array(), 'iptc_caption' => array(),
                               'iptc_author' => array(), 'iptc_copyright' => array(),
                               'iptc_category' => array(), 'iptc_subcategories' => array(), 'iptc_keywords' => array()
                               );
        $iPosition = 0;
        foreach( glob( $sDirectoryPath_FS.'/*' ) as $sFilePath_FS )
        {
          // ... check file
          if( !is_readable( $sFilePath_FS ) ) continue;
          if( is_dir( $sFilePath_FS ) ) continue;

          // ... file info
          $asFileInfo = pathinfo( $sFilePath_FS );

          // ... image info
          $amImageInfo = null;
          $amImageInfo = @getImageSize( $sFilePath_FS );
          if( !is_array( $amImageInfo ) ) continue; // file is not parsable as an image
          if( $this->bAllowOnlyWebCompatible and $amImageInfo[2] > 3 ) continue; // web-compatible = GIF, JPEG and PNG

          // ... basename filtering
          $sBasenameRegEx = $this->getBasenameRegEx();
          $sBasename = array_key_exists( 'basename', $asFileInfo ) ? PHP_APE_Util_File_Any::decodePath( $asFileInfo['basename'] ) : null;
          if( !empty( $sBasenameRegEx ) and !preg_match( $sBasenameRegEx, $sBasename ) ) continue;

          // ... primary key
          switch( $this->getPrimaryKeyField() )
          {
          case 'position': array_push( $aamImageData['pk'], $iPosition ); break;
          default: array_push( $aamImageData['pk'], $sBasename );
          }

          // ... file position, name and extension
          array_push( $aamImageData['position'], $iPosition++ );
          array_push( $aamImageData['name'], $sBasename );
          array_push( $aamImageData['extension'], array_key_exists( 'extension', $asFileInfo ) ? PHP_APE_Util_File_Any::decodePath( $asFileInfo['extension'] ) : null );
          
          // ... file type and mode
          array_push( $aamImageData['size'], filesize( $sFilePath_FS ) );
          array_push( $aamImageData['mode'], PHP_APE_Util_File_Any::getPermsString( fileperms( $sFilePath_FS ) ) );
          
          // ... file user and group
          $iUID = fileowner( $sFilePath_FS );
          $sUser = @posix_getpwuid( $iUID ); $sUser = is_array( $sUser ) ? $sUser['name'] : null;
          array_push( $aamImageData['uid'], $iUID );
          array_push( $aamImageData['user'], $sUser );
          $iGID = filegroup( $sFilePath_FS );
          $sGroup = @posix_getgrgid( $iGID ); $sGroup = is_array( $sGroup ) ? $sGroup['name'] : null;
          array_push( $aamImageData['gid'], $iGID );
          array_push( $aamImageData['group'], $sGroup );

          // ... file times
          array_push( $aamImageData['accessed'], fileatime( $sFilePath_FS ) );
          array_push( $aamImageData['modified'], filemtime( $sFilePath_FS ) );
          array_push( $aamImageData['changed'], filectime( $sFilePath_FS ) );

          // ... image format and size
          array_push( $aamImageData['format'], PHP_APE_Util_Image_Any::getFormatString( $amImageInfo[2] ) );
          array_push( $aamImageData['width'], $amImageInfo[0] );
          array_push( $aamImageData['height'], $amImageInfo[1] );

          // ... JPEG meta-data
          if( $amImageInfo[2] == 2 )
          {
            // ... load
            $roJPEGMetaData =& new PHP_APE_Util_Image_JPEG( $sFilePath_FS );

            // ... EXIF
            $mValue = $roJPEGMetaData->getExifField( 'DatetimeOriginal' );
            array_push( $aamImageData['exif_timestamp'], $mValue ? $mValue : null );
            $mValue = $roJPEGMetaData->getExifField( 'Make' );
            array_push( $aamImageData['exif_camerabrand'], $mValue ? $mValue : null );
            $mValue = $roJPEGMetaData->getExifField( 'Model' );
            array_push( $aamImageData['exif_cameramodel'], $mValue ? $mValue : null );
            $mValue = $roJPEGMetaData->getExifField( 'ExposureTime' );
            array_push( $aamImageData['exif_exposure'], $mValue['val'] ? '1/'.round( 1 / $mValue['val'] ) : null );
            $mValue = $roJPEGMetaData->getExifField( 'FNumber' );
            array_push( $aamImageData['exif_aperture'], $mValue['val'] ? $mValue['val'] : null );
            $mValue = $roJPEGMetaData->getExifField( 'ISOSpeedRatings' );
            array_push( $aamImageData['exif_sensitivity'], $mValue ? $mValue : null );

            // ... IPTC
            $mValue = $roJPEGMetaData->getIPTCField( 'ObjectName' );
            array_push( $aamImageData['iptc_name'], $mValue ? $mValue : null );
            $mValue = $roJPEGMetaData->getIPTCField( 'Headline' );
            array_push( $aamImageData['iptc_headline'], $mValue ? $mValue : null );
            $mValue = $roJPEGMetaData->getIPTCField( 'Caption' );
            array_push( $aamImageData['iptc_caption'], $mValue ? $mValue : null );
            $mValue = $roJPEGMetaData->getIPTCField( 'Byline' );
            array_push( $aamImageData['iptc_author'], $mValue ? $mValue : null );
            $mValue = $roJPEGMetaData->getIPTCField( 'CopyrightNotice' );
            array_push( $aamImageData['iptc_copyright'], $mValue ? $mValue : null );
            $mValue = $roJPEGMetaData->getIPTCField( 'Category' );
            array_push( $aamImageData['iptc_category'], $mValue ? $mValue : null );
            $mValue = $roJPEGMetaData->getIPTCField( 'SuplementalCategories' );
            if( is_array( $mValue ) and count( $mValue ) > 0 ) $mValue = implode( '; ', $mValue );
            array_push( $aamImageData['iptc_subcategories'], $mValue ? $mValue : null );
            $mValue = $roJPEGMetaData->getIPTCField( 'Keywords' );
            if( is_array( $mValue ) and count( $mValue ) > 0 ) $mValue = implode( '; ', $mValue );
            array_push( $aamImageData['iptc_keywords'], $mValue ? $mValue : null );
          }
          else
          {
            // ... EXIF
            array_push( $aamImageData['exif_timestamp'], null );
            array_push( $aamImageData['exif_camerabrand'], null );
            array_push( $aamImageData['exif_cameramodel'], null );
            array_push( $aamImageData['exif_exposure'], null );
            array_push( $aamImageData['exif_aperture'], null );
            array_push( $aamImageData['exif_sensitivity'], null );

            // ... IPTC
            array_push( $aamImageData['iptc_name'], null );
            array_push( $aamImageData['iptc_headline'], null );
            array_push( $aamImageData['iptc_caption'], null );
            array_push( $aamImageData['iptc_author'], null );
            array_push( $aamImageData['iptc_copyright'], null );
            array_push( $aamImageData['iptc_category'], null );
            array_push( $aamImageData['iptc_subcategories'], null );
            array_push( $aamImageData['iptc_keywords'], null );
          }

        }

      }

      // ... save cache
      if( $bUseCache and $bSaveCache )
      {
        file_put_contents( $sCachePath, serialize( $aamImageData ), LOCK_EX );
        chmod( $sCachePath, 0600 );
      }

      // ... set underlying array (image data)
      $this->_setArray( $aamImageData );

    }

    // Parent method
    return PHP_APE_Data_ArrayResultSet::queryEntries( $iQueryType );
  }


  /*
   * METHODS: size
   ********************************************************************************/

	public function getFormat()
  {
    return $this->useElement( 'format' )->useContent()->getValue();
	}

	public function getDimension()
  {
    return array( $this->useElement( 'width' )->useContent()->getValue(), $this->useElement( 'height' )->useContent()->getValue() );
	}

	public function isBiggerThan( $aiDimension, $bStrictlyBigger = false )
  {
    return PHP_APE_Util_Image_Any::isBiggerThan( $this->getDimension(), $aiDimension, $bStrictlyBigger );
	}

	public function isSmallerThan( $aiDimension )
  {
    return PHP_APE_Util_Image_Any::isSmallerThan( $this->getDimension(), $aiDimension );
	}

	public function getDimensionRatio( $fRatio )
  {
    return PHP_APE_Util_Image_Any::getDimensionRatio( $this->getDimension(), $fRatio );
	}

	public function getDimensionGauge( $aiGauge, $bDownsize = true, $bUpsize = false )
  {
    return PHP_APE_Util_Image_Any::getDimensionGauge( $this->getDimension(), $aiGauge, $bDownsize, $bUpsize );
	}

}
