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
 * @package PHP_APE_RSS
 * @subpackage Classes
 */

/** RSS channel
 *
 * @package PHP_APE_RSS
 * @subpackage Classes
 */
class PHP_APE_RSS_Channel
extends PHP_APE_RSS_Any
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Channel's title
   * @var string */
  private $sTitle;

  /** Channel's link (URL)
   * @var string */
	private $sLink;

  /** Channel's description
   * @var string */
	private $sDescription;

  /** Channel's language
   * @var string */
	private $sLanguage;

  /** Channel's copyright
   * @var string */
	private $sCopyright;

  /** Channel's managing editor
   * @var string */
	private $sManagingEditor;

  /** Channel's web master
   * @var string */
	private $sWebMaster;

  /** Channel's publication date (UNIX timestamp)
   * @var float */
	private $fPubDate;

  /** Channel's last build date (UNIX timestamp)
   * @var float */
	private $fLastBuildDate;

  /** Item's categories
   * @var array|string */
	private $asCategories;

  /** Item's categories domains (URLs)
   * @var array|string */
	private $asCategoriesDomains;

  /** Channel's Time-To-Live (minutes)
   * @var integer */
	private $iTTL;

  /** Channel's cloud domain
   * @var string */
	private $sCloudDomain;

  /** Channel's cloud port
   * @var integer */
	private $iCloudPort;

  /** Channel's cloud path
   * @var string */
	private $sCloudPath;

  /** Channel's cloud procedure
   * @var string */
	private $sCloudProcedure;

  /** Channel's cloud protocol
   * @var string */
	private $sCloudProtocol;

  /** Channel's image (URL)
   * @var string */
	private $sImageURL;

  /** Channel's image title
   * @var string */
	private $sImageTitle;

  /** Channel's image link (URL)
   * @var string */
	private $sImageLink;

  /** Channel's image description
   * @var string */
	private $sImageDescription;

  /** Channel's image width (pixels)
   * @var integer */
	private $iImageWidth;

  /** Channel's image height (pixels)
   * @var integer */
	private $iImageHeight;

  /** Channel's (PICS) rating
   * @var string */
	private $sRating;

  /** Channel's skip hours
   * @var array|boolean */
	private $abSkipHours;

  /** Channel's skip days
   * @var array|boolean */
	private $abSkipDays;

  /** Channel's items
   * @var array|PHP_APE_RSS_Item */
	private $aoItems;



  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs an new channel instance
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param string|PHP_APE_Type_Any $sTitle Channel's title
   * @param string|PHP_APE_Type_Any $sLink Channel's link (URL)
   * @param string|PHP_APE_Type_Any $sDescription Channel's description
   * @param string|PHP_APE_Type_Any $sLanguage Channel's language
   * @param string|PHP_APE_Type_Any $sCopyright Channel's copyright
   * @param float|PHP_APE_Type_Any $fPubDate Channel's publication date (UNIX timestamp)
   */
  public function __construct( $sTitle = null, $sLink = null, $sDescription = null, $sLanguage = null, $sCopyright = null, $fPubDate = null )
  {
    // Construct channel
    $this->set( $sTitle, $sLink, $sDescription, $sLanguage, $sCopyright, $fPubDate );
  }

  /** Resets this channel's content
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   */
  public function reset()
  {
    $this->sTitle = null;
    $this->sLink = null;
    $this->sDescription = null;
    $this->sLanguage = null;
    $this->sCopyright = null;
    $this->sManagingEditor = null;
    $this->sWebMaster = null;
    $this->fPubDate = null;
    $this->fLastBuildDate = null;
    $this->asCategories = null;
    $this->asCategoriesDomains = null;
    $this->iTTL = null;
    $this->sCloudDomain = null;
    $this->iCloudPort = null;
    $this->sCloudPath = null;
    $this->sCloudProcedure = null;
    $this->sCloudProtocol = null;
    $this->sImageURL = null;
    $this->sImageTitle = null;
    $this->sImageLink = null;
    $this->sImageDescription = null;
    $this->iImageWidth = null;
    $this->iImageHeight = null;
    $this->sRating = null;
    $this->abSkipHours = null;
    $this->abSkipDays = null;
    $this->aoItems = null;
  }


  /*
   * METHODS: setters
   ********************************************************************************/

  /** Globally sets this channel's content
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sTitle Channel's title
   * @param string|PHP_APE_Type_Any $sLink Channel's link (URL)
   * @param string|PHP_APE_Type_Any $sDescription Channel's description
   * @param string|PHP_APE_Type_Any $sLanguage Channel's language
   * @param string|PHP_APE_Type_Any $sCopyright Channel's copyright
   * @param float|PHP_APE_Type_Any $fPubDate Channel's publication date (UNIX timestamp)
   */
  public function set( $sTitle = null, $sLink = null, $sDescription = null, $sLanguage = null, $sCopyright = null, $fPubDate = null )
  {
    $this->setTitle( $sTitle );
    $this->setLink( $sLink );
    $this->setDescription( $sDescription );
    $this->setLanguage( $sLanguage );
    $this->setCopyright( $sCopyright );
    $this->setPubDate( $fPubDate );
  }

  /** Sets this channel's title
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sTitle Channel's title
   */
  final public function setTitle( $sTitle )
  {
    $this->sTitle = PHP_APE_Type_String::parseValue( $sTitle, true );
  }

  /** Sets this channel's link
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sLink Channel's link (URL)
   */
  final public function setLink( $sLink )
  {
    $this->sLink = PHP_APE_Type_URL::parseValue( $sLink, true );
  }

  /** Sets this channel's description
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sDescription Channel's description
   */
  final public function setDescription( $sDescription )
  {
    $this->sDescription = PHP_APE_Type_String::parseValue( $sDescription, true );
  }

  /** Sets this channel's language
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sLanguage Channel's language
   */
  final public function setLanguage( $sLanguage )
  {
    $this->sLanguage = PHP_APE_Type_String::parseValue( $sLanguage, true );
  }

  /** Sets this channel's copyright
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sCopyright Channel's copyright
   */
  final public function setCopyright( $sCopyright )
  {
    $this->sCopyright = PHP_APE_Type_String::parseValue( $sCopyright, true );
  }

  /** Sets this channel's managing editor
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sManagingEditor Channel's managing editor
   */
  final public function setManagingEditor( $sManagingEditor )
  {
    $this->sManagingEditor = PHP_APE_Type_String::parseValue( $sManagingEditor, true );
  }

  /** Sets this channel's web master
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sWebMaster Channel's web master
   */
  final public function setWebMaster( $sWebMaster )
  {
    $this->sWebMaster = PHP_APE_Type_String::parseValue( $sWebMaster, true );
  }

  /** Sets this channel's publication date
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param float|PHP_APE_Type_Any $sPublication Date Channel's publication date
   */
  final public function setPubDate( $fPubDate )
  {
    $this->fPubDate = PHP_APE_Type_Timestamp::parseValue( $fPubDate, true, null, true );
  }

  /** Sets this channel's last build date
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param float|PHP_APE_Type_Any $sLast Build Date Channel's last build date
   */
  final public function setLastBuildDate( $fLastBuildDate )
  {
    $this->fLastBuildDate = PHP_APE_Type_Timestamp::parseValue( $fLastBuildDate, true, null, true );
  }

  /** Adds another category to this channel
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sCategory Channel's category
   * @param string|PHP_APE_Type_Any $sCategoryDomain Channel's category domain (URL)
   */
  final public function addCategory( $sCategory, $sCategoryDomain = null )
  {
    if( !is_array( $this->asCategories ) ) $this->asCategories = array();
    if( !is_array( $this->asCategoriesDomains ) ) $this->asCategoriesDomains = array();
    array_push( $this->asCategories, PHP_APE_Type_String::parseValue( $sCategory, true ) );
    array_push( $this->asCategoriesDomains, PHP_APE_Type_URL::parseValue( $sCategoryDomain, true ) );
  }

  /** Sets this channel's Time-To-Live (minutes)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $iTTL Channel's Time-To-Live (minutes)
   */
  final public function setTTL( $iTTL )
  {
    $this->iTTL = PHP_APE_Type_Integer::parseValue( $iTTL );
    if( $this->iTTL <= 0 )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid value; Value: '.$iTTL );
  }

  /** Sets this channel's cloud interface
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sCloudDomain Channel's cloud domain
   * @param integer|PHP_APE_Type_Any $iCloudPort Channel's cloud port
   * @param string|PHP_APE_Type_Any $sCloudPath Channel's cloud path
   * @param string|PHP_APE_Type_Any $sCloudProcedure Channel's cloud procedure
   * @param string|PHP_APE_Type_Any $sCloudProtocol Channel's cloud protocol
   */
  final public function setCloud( $sCloudDomain, $iCloudPort, $sCloudPath, $sCloudProcedure, $sCloudProtocol )
  {
    $this->sCloudDomain = PHP_APE_Type_String::parseValue( $sCloudDomain );
    $this->iCloudPort = PHP_APE_Type_Integer::parseValue( $iCloudPort );
    $this->sCloudPath = PHP_APE_Type_String::parseValue( $sCloudPath );
    $this->sCloudProcedure = PHP_APE_Type_String::parseValue( $sCloudProcedure );
    $this->sCloudProtocol = PHP_APE_Type_String::parseValue( $sCloudProtocol );
  }

  /** Sets this channel's rating
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sRating Channel's rating
   */
  final public function setRating( $sRating )
  {
    $this->sRating = PHP_APE_Type_String::parseValue( $sRating, true );
  }

  /** Sets this channel's image
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sImageURL Channel's image (URL)
   * @param string|PHP_APE_Type_Any $iImageTitle Channel's image title
   * @param string|PHP_APE_Type_Any $sImageLink Channel's image link (URL)
   * @param string|PHP_APE_Type_Any $sImageDescription Channel's image description
   * @param integer|PHP_APE_Type_Any $iImageWidth Channel's image width (pixels)
   * @param integer|PHP_APE_Type_Any $iImageHeight Channel's image height (pixels)
   */
  final public function setImage( $sImageURL, $sImageTitle, $sImageLink, $sImageDescription = null, $iImageWidth = null, $iImageHeight = null )
  {
    $this->sImageURL = PHP_APE_Type_URL::parseValue( $sImageURL );
    $this->sImageTitle = PHP_APE_Type_String::parseValue( $sImageTitle );
    $this->sImageLink = PHP_APE_Type_URL::parseValue( $sImageLink );
    $this->sImageDescription = PHP_APE_Type_String::parseValue( $sImageDescription );
    $this->iImageWidth = PHP_APE_Type_Integer::parseValue( $iImageWidth );
    if( !is_null( $this->iImageWidth ) and $this->iImageWidth <= 0 )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid (width) value; Value: '.$iImageWidth );
    $this->iImageHeight = PHP_APE_Type_Integer::parseValue( $iImageHeight );
    if( !is_null( $this->iImageHeight ) and $this->iImageHeight <= 0 )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid (height) value; Value: '.$iImageHeight );
  }

  /** Adds a skip hour to this channel's
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param integer|PHP_APE_Type_Any $iSkipHour Skip hour index ( 0 = midnight )
   */
  final public function addSkipHour( $iSkipHour )
  {
    $iSkipHour = PHP_APE_Type_Integer::parseValue( $iSkipHour );
    if( is_null( $iSkipHour ) or $iSkipHour < 0 or $iSkipHour > 23 )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid value; Value: '.$iSkipHour );
    if( !is_array( $this->abSkipHours ) ) $this->abSkipHours = array( 0 => false, 1 => false, 2 => false, 3 => false, 4 => false, 5 => false, 6 => false, 7 => false, 8 => false, 9 => false, 10 => false, 11 => false, 12 => false, 13 => false, 14 => false, 15 => false, 16 => false, 17 => false, 18 => false, 19 => false, 20 => false, 21 => false, 22 => false, 23 => false );
    $this->abSkipHours[ $iSkipHour ] = true;
  }

  /** Adds a skip day to this channel
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sSkipDay Skip day name or index ( 0 = Monday )
   */
  final public function addSkipDay( $sSkipDay )
  {
    $sSkipDay = PHP_APE_Type_String::parseValue( $sSkipDay );
    switch( strtolower( trim( $sSkipDay ) ) )
    {
    case 'monday': $iSkipDay = 0; break;
    case 'tuesday': $iSkipDay = 1; break;
    case 'wednesday': $iSkipDay = 2; break;
    case 'thursday': $iSkipDay = 3; break;
    case 'friday': $iSkipDay = 4; break;
    case 'saturday': $iSkipDay = 5; break;
    case 'sunday': $iSkipDay = 6; break;
    default: $iSkipDay = PHP_APE_Type_Integer::parseValue( $sSkipDay );
    }
    if( is_null( $iSkipDay ) or $iSkipDay < 0 or $iSkipDay > 6 )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid value; Value: '.$sSkipDay );
    if( !is_array( $this->abSkipDays ) ) $this->abSkipDays = array( 0 => false, 1 => false, 2 => false, 3 => false, 4 => false, 5 => false, 6 => false );
    $this->abSkipDays[ $iSkipDay ] = true;
  }

  /** Adds another item to this channel
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param PHP_APE_RSS_Item $sItem Channel's item
   */
  final public function addItem( PHP_APE_RSS_Item $oItem )
  {
    if( !is_array( $this->aoItems ) ) $this->aoItems = array();
    array_push( $this->aoItems, $oItem );
  }


  /*
   * METHODS: getters
   ********************************************************************************/

  /** Retrieves this channel's title
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getTitle()
  {
    return $this->sTitle;
  }

  /** Retrieves this channel's link (URL)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getLink()
  {
    return $this->sLink;
  }

  /** Retrieves this channel's description
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getDescription()
  {
    return $this->sDescription;
  }

  /** Retrieves this channel's language
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getLanguage()
  {
    return $this->sLanguage;
  }

  /** Retrieves this channel's copyright
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getCopyright()
  {
    return $this->sCopyright;
  }

  /** Retrieves this channel's managing editor
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getManagingEditor()
  {
    return $this->sManagingEditor;
  }

  /** Retrieves this channel's web master
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getWebMaster()
  {
    return $this->sWebMaster;
  }

  /** Retrieves this channel's publication date (UNIX timestamp)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return float
   */
  final public function getPubDate()
  {
    return $this->fPubDate;
  }

  /** Retrieves this channel's last build date (UNIX timestamp)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return float
   */
  final public function getLastBuildDate()
  {
    return $this->fLastBuildDate;
  }

  /** Retrieves this channel's categories quantities
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return integer
   */
  final public function countCategories()
  {
    return is_array( $this->asCategories ) ? count( $this->asCategories ) : 0;
  }

  /** Retrieves this channel's category
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getCategory( $iIndex = 0 )
  {
    if( !is_array( $this->asCategories ) ) return null;
    $iIndex = (integer)$iIndex;
    return array_key_exists( $iIndex, $this->asCategories ) ? $this->asCategories[$iIndex] : null;
  }

  /** Retrieves this channel's category domain (URL)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getCategoryDomain( $iIndex = 0 )
  {
    if( !is_array( $this->asCategoriesDomains ) ) return null;
    $iIndex = (integer)$iIndex;
    return array_key_exists( $iIndex, $this->asCategoriesDomains ) ? $this->asCategoriesDomains[$iIndex] : null;
  }

  /** Retrieves this channel's Time-To-Live (minutes)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return integer
   */
  final public function getTTL()
  {
    return $this->iTTL;
  }

  /** Retrieves this channel's cloud domain
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getCloudDomain()
  {
    return $this->sCloudDomain;
  }

  /** Retrieves this channel's cloud port
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return integer
   */
  final public function getCloudPort()
  {
    return $this->iCloudPort;
  }

  /** Retrieves this channel's cloud path
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getCloudPath()
  {
    return $this->sCloudPath;
  }

  /** Retrieves this channel's cloud procedure
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getCloudProcedure()
  {
    return $this->sCloudProcedure;
  }

  /** Retrieves this channel's cloud protocol
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getCloudProtocol()
  {
    return $this->sCloudProtocol;
  }

  /** Retrieves this channel's image (URL)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getImageURL()
  {
    return $this->sImageURL;
  }

  /** Retrieves this channel's image title
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getImageTitle()
  {
    return $this->sImageTitle;
  }

  /** Retrieves this channel's image link
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getImageLink()
  {
    return $this->sImageLink;
  }

  /** Retrieves this channel's image description
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getImageDescription()
  {
    return $this->sImageDescription;
  }

  /** Retrieves this channel's image width (pixels)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getImageWidth()
  {
    return $this->iImageWidth;
  }

  /** Retrieves this channel's image height (pixels)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getImageHeight()
  {
    return $this->iImageHeight;
  }

  /** Retrieves this channel's rating
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getRating()
  {
    return $this->sRating;
  }

  /** Retrieves this channel's skip hour
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param integer $iSkipHour Skip hour index ( 0 = midnight )
   * @return boolean
   */
  final public function getSkipHours( $iSkipHour )
  {
    if( !is_array( $this->abSkipHours ) ) return null;
    $iSkipHour = (integer)$iSkipHour;
    return array_key_exists( $iSkipHour, $this->abSkipHours ) ? $this->abSkipHours[ $iSkipHour ] : null;
  }

  /** Retrieves this channel's skip day
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param integer $iSkipDay Skip day index ( 0 = midnight )
   * @return boolean
   */
  final public function getSkipDays( $iSkipDay )
  {
    if( !is_array( $this->abSkipDays ) ) return null;
    $iSkipDay = (integer)$iSkipDay;
    return array_key_exists( $iSkipDay, $this->abSkipDays ) ? $this->abSkipDays[ $iSkipDay ] : null;
  }

  /** Retrieves this channel's items quantities
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return integer
   */
  final public function countItems()
  {
    return is_array( $this->aoItems ) ? count( $this->aoItems ) : 0;
  }

  /** Retrieves this channel's item
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return PHP_APE_RSS_Item
   */
  final public function getItem( $iIndex = 0 )
  {
    if( !is_array( $this->aoItems ) ) return null;
    $iIndex = (integer)$iIndex;
    return array_key_exists( $iIndex, $this->aoItems ) ? $this->aoItems[$iIndex] : null;
  }


  /*
   * METHODS: output
   ********************************************************************************/

  /** Outputs this channel in XML (RSS) format
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param float $fVersion RSS version
   * @param boolean $bUseUTF8 Use UTF8 encoding
   * @param string $sSelfURL Channel's URL
   * @return string
   */
	function format( $fVersion = 2.0, $bUseUTF8 = false, $sSelfURL = null )
  {
		// Sanitize input
		$fVersion = (float)$fVersion;
		$bUseUTF8 = (boolean)$bUseUTF8;

    // Output
    $roEnvironment =& PHP_APE_RSS_WorkSpace::useEnvironment();
    $oXML = new PHP_APE_DataSpace_XML();
		$sOutput=null;

    // Begin RSS
		if( $fVersion >= 2.0 and isset( $sSelfURL ) )
		  $sOutput .= '<rss version="'.sprintf($fVersion<1?'%.2F':'%.1F',$fVersion).'" xmlns:atom="http://www.w3.org/2005/Atom">'."\n";
    else
  		$sOutput .= '<rss version="'.sprintf($fVersion<1?'%.2F':'%.1F',$fVersion).'">'."\n";

		// Begin channel
		$sOutput .= '<channel>'."\n";

		// Output elements
    // ... self reference
		if( $fVersion >= 2.0 and isset( $sSelfURL ) )
		  $sOutput .= '<atom:link href="'.$sSelfURL.'" rel="self" type="application/rss+xml" />'."\n";

		// ... generator
		if( $fVersion >= 2.0 )
      $sOutput .= '<generator>PHP_APE/RSS/'.PHP_APE_VERSION.'</generator>'."\n";

		// ... documentation
		$sOutput .= '<docs>http://blogs.law.harvard.edu/tech/rss</docs>'."\n";

		// ... title
		if( isset( $this->sTitle ) )
      $sOutput .= '<title>'.$oXML->encodeData( $this->sTitle, true, $bUseUTF8 ).'</title>'."\n";

		// ... link
		if( isset( $this->sLink ) )
      $sOutput .= '<link>'.$oXML->encodeData( $this->sLink, true, $bUseUTF8 ).'</link>'."\n";

		// ... description
		if( isset( $this->sDescription ) )
      $sOutput .= '<description>'.$oXML->encodeData( $this->sDescription, true, $bUseUTF8 ).'</description>'."\n";

		// ... language
		if( isset( $this->sLanguage ) )
      $sOutput .= '<language>'.$oXML->encodeData(  $this->sLanguage, true, $bUseUTF8 ).'</language>'."\n"; 

		// ... copyright
		if( isset( $this->sCopyright ) )
      $sOutput .= '<copyright>'.$oXML->encodeData( $this->sCopyright, true, $bUseUTF8 ).'</copyright>'."\n";

		// ... managing editor
		if( isset( $this->sManagingEditor ) )
      $sOutput .= '<managingEditor>'.$oXML->encodeData( $this->sManagingEditor, true, $bUseUTF8 ).'</managingEditor>'."\n";
		// ... web master
		if( isset( $this->sWebMaster ) ) $sOutput .= '<webMaster>'.$oXML->encodeData( $this->sWebMaster, true, $bUseUTF8 ).'</webMaster>'."\n";

		// ... publication date
		if( $this->fPubDate > 0 )
      $sOutput .= '<pubDate>'.$oXML->encodeData( date( 'r', $this->fPubDate ), false, false ).'</pubDate>'."\n";

		// ... last build date
		if( $this->fLastBuildDate > 0 )
      $sOutput .= '<lastBuildDate>'.$oXML->encodeData( date( 'r', $this->fLastBuildDate ), false, false ).'</lastBuildDate>'."\n";

		// ... category
		if( $fVersion >= 0.92 and is_array( $this->asCategories ) and count( $this->asCategories) )
      foreach( $this->asCategories as $iIndex => $sCategory )
        if( isset( $sCategory ) )
        {
          $sCategoryDomain = $this->asCategoriesDomains[ $iIndex ];
          $sOutput .= '<category'.( isset( $sCategoryDomain ) ? ' domain="'.$oXML->encodeData( $sCategoryDomain, false, $bUseUTF8 ).'"' : '' ).'>'.$oXML->encodeData( $sCategory, true, $bUseUTF8 ).'</category>'."\n";
        }

		// ... TTL
		if( $fVersion >= 2.0 )
    {
      $iTTL = $this->iTTL > 0 ? $this->iTTL : $roEnvironment->getStaticParameter( 'php_ape.rss.ttl' );
      $sOutput .= '<ttl>'.$oXML->encodeData( $iTTL, false, false ).'</ttl>'."\n";
    }

		// ... cloud
		if( $fVersion>=0.92 and isset( $this->sCloudDomain, $this->iCloudPort, $this->sCloudPath, $this->sCloudProcedure, $this->sCloudProtocol ) )
      $sOutput .= '<cloud domain="'.$oXML->encodeData( $this->sCloudDomain, false, $bUseUTF8 ).'" port="'.$oXML->encodeData( $this->iCloudPort, false, $bUseUTF8 ).'" path="'.$oXML->encodeData( $this->sCloudPath, false, $bUseUTF8 ).'" registerProcedure="'.$oXML->encodeData( $this->sCloudProcedure, false, $bUseUTF8 ).'" protocol="'.$oXML->encodeData( $this->sCloudProtocol, false, $bUseUTF8 ).'" />'."\n";

		// ... image
		if( isset( $this->sImageURL, $this->sImageTitle, $this->sImageLink ) )
    {
      $sOutput .= '<image>'."\n";
      $sOutput .= '<url>'.$oXML->encodeData( $this->sImageURL, true, $bUseUTF8 ).'</url>'."\n";
      $sOutput .= '<title>'.$oXML->encodeData( $this->sImageTitle, true, $bUseUTF8 ).'</title>'."\n";
      $sOutput .= '<link>'.$oXML->encodeData( $this->sImageLink, true, $bUseUTF8 ).'</link>'."\n";
      if( isset( $this->sImageDescription ) )
        $sOutput .= '<description>'.$oXML->encodeData( $this->sImageDescription, true, $bUseUTF8 ).'</description>'."\n";
      if( $this->iImageWidth > 0 )
        $sOutput .= '<width>'.$oXML->encodeData( $this->iImageWidth, false, false ).'</width>'."\n";
      if( $this->iImageHeight > 0 )
        $sOutput .= '<height>'.$oXML->encodeData( $this->iImageHeight, false, false ).'</height>'."\n";
    }

		// ... rating
		if( isset( $this->sRating ) )
      $sOutput .= '<rating>'.$oXML->encodeData( $this->sRating, true, $bUseUTF8 ).'</rating>'."\n";

		// ... skip hours
		if( is_array( $this->abSkipHours ) )
    {
			$sOutput .= '<skipHours>'."\n";
			foreach( $this->abSkipHours as $iSkipHour => $bEnabled )
      {
				if( $bEnabled ) $sOutput .= '<hour>'.$oXML->encodeData( $iSkipHour, false, false ).'</hour>'."\n";
			}
			$sOutput .= '</skipHours>'."\n";
		}

		// ... skip days
		if( is_array( $this->abSkipDays ) )
    {
			$sOutput .= '<skipDays>'."\n";
			foreach( $this->abSkipDays as $iSkipDay => $bEnabled )
      {
				if( $bEnabled ) 
        {
          $sOutput .= '<day>';
          switch( $iSkipDay ) 
          {
          case 0: $sOutput .= $oXML->encodeData( 'Monday', false, false ); break;
          case 1: $sOutput .= $oXML->encodeData( 'Tuesday', false, false ); break;
          case 2: $sOutput .= $oXML->encodeData( 'Wednesday', false, false ); break;
          case 3: $sOutput .= $oXML->encodeData( 'Thursday', false, false ); break;
          case 4: $sOutput .= $oXML->encodeData( 'Friday', false, false ); break;
          case 5: $sOutput .= $oXML->encodeData( 'Saturday', false, false ); break;
          case 6: $sOutput .= $oXML->encodeData( 'Sunday', false, false ); break;
          }
          $sOutput .= '</day>'."\n";
        }
			}
			$sOutput .= '</skipDays>'."\n";
		}

		// ... cloud
		if( $fVersion>=0.92 and isset( $this->sCloudDomain, $this->sCloudPort, $this->sCloudPath, $this->sCloudProcedure, $this->sCloudProtocol ) )
      $sOutput .= '<cloud domain="'.$oXML->encodeData( $this->sCloudDomain, false, $bUseUTF8 ).'" port="'.$oXML->encodeData( $this->sCloudPort, false, $bUseUTF8 ).'" path="'.$oXML->encodeData( $this->sCloudPath, false, $bUseUTF8 ).'" registerProcedure="'.$oXML->encodeData( $this->sCloudProcedure, false, $bUseUTF8 ).'" protocol="'.$oXML->encodeData( $this->sCloudProtocol, false, $bUseUTF8 ).'" />'."\n";

		// ... items
		if( is_array($this->aoItems ) )
      foreach( $this->aoItems as $oItem )
        $sOutput .= $oItem->format( $fVersion, $bUseUTF8 );

		// End channel
		$sOutput .= '</channel>'."\n";

		// End RSS
		$sOutput .= '</rss>'."\n";

		// END
		return $sOutput;
  }

  /** Exports this channel to XML (RSS) document
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param float $fVersion RSS version
   * @param boolean $bUseUTF8 Use UTF8 encoding
   * @param string $sSelfURL Channel's URL
   * @return string
   */
	function export( $fVersion = 2.0, $bUseUTF8 = false, $sSelfURL = null )
  {
		// Sanitize input
		$fVersion = (float)$fVersion;
		$bUseUTF8 = (boolean)$bUseUTF8;

    // Output
    $roEnvironment =& PHP_APE_RSS_WorkSpace::useEnvironment();
    $oXML = new PHP_APE_DataSpace_XML();
		$sOutput = null;

		// XML/XSL
		$sCharset = $bUseUTF8 ? 'utf-8' : ini_get( 'default_charset' );
		if( empty( $sCharset ) ) $sCharset = version_compare( PHP_VERSION, '5.4' ) >= 0 ? 'utf-8' : 'iso-8859-1';
		$sOutput .= '<?xml version="1.0" encoding="'.$sCharset.'" ?>'."\n";
		$sOutput .= '<?xml-stylesheet type="text/xsl" href="'.$oXML->encodeData( str_replace( '%charset%', $sCharset, $roEnvironment->getStaticParameter( 'php_ape.rss.xsl.url' ) ), false, $bUseUTF8 ).'"?>'."\n";

		// RSS
		$sOutput .= $this->format( $fVersion, $bUseUTF8, $sSelfURL );

		// END
		return $sOutput;
	}


  /*
   * METHODS: parser
   ********************************************************************************/

  /** Parses this item from supplied XML (RSS) data
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>, <SAMP>PHP_APE_RSS_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sRSS XML (RSS) Data
   * @param boolean $bUseUTF8 Use UTF8 decoding
   */
	function parse( $sRSS, $bUseUTF8 = false )
  {
		// Sanitize input
		$sRSS = trim( PHP_APE_Type_String::parseValue( $sRSS ) );
		$bUseUTF8 = (boolean)$bUseUTF8;

    // Empty current content
    $this->reset();

		// Parse channel
    $oXML = new PHP_APE_DataSpace_XML();
		if( preg_match('/<rss.*?>(.+?)<\/rss\s*>/is', $sRSS, $asEntities ) )
      $sRSS = $asEntities[1];
		else
      throw new PHP_APE_RSS_Exception( __METHOD__, 'No RSS' );
		if( preg_match('/<channel\s*>(.+?)<\/channel\s*>/is', $sRSS, $asEntities ) )
      $sRSS = $asEntities[1];
		else
      throw new PHP_APE_RSS_Exception( __METHOD__, 'No channel' );

		// ... items (first, so we can strip them out and parse the rest faster)
		if( preg_match_all( '/<item\s*>.+?<\/item\s*>/is', $sRSS, $asEntities ) )
    {
			$oItem = new PHP_APE_RSS_Item();
			foreach( $asEntities[0] as $sItem )
      {
				$oItem->parse( $sItem, $bUseUTF8 );
				$this->addItem( $oItem );
			}
		}
		$sRSS = preg_replace( '/<item\s*>.*?<\/item\s*>/is', null, $sRSS );

		// ... inputs (not used)
		$sRSS = preg_replace('/<textInput\s*>.*?<\/textInput\s*>/is', null, $sRSS );

		// ... image (now, so we can strip out ambiguous fields)
		if( preg_match( '/<image\s*>(.+?)<\/image\s*>/is', $sRSS, $asEntities ) )
    {
			$sURL = preg_match( '/<url\s*>(.+?)<\/url\s*>/is', $asEntities[1], $asSubEntities ) ? $oXML->decodeData( $asSubEntities[1], true, $bUseUTF8 ) : null;
			$sTitle = preg_match( '/<title\s*>(.+?)<\/title\s*>/is', $asEntities[1], $asSubEntities ) ? $oXML->decodeData( $asSubEntities[1], true, $bUseUTF8 ) : null;
			$sLink = preg_match( '/<link\s*>(.+?)<\/link\s*>/is', $asEntities[1], $asSubEntities ) ? $oXML->decodeData( $asSubEntities[1], true, $bUseUTF8 ) : null;
			$sDescription = preg_match( '/<description\s*>(.+?)<\/description\s*>/is', $asEntities[1], $asSubEntities ) ? $oXML->decodeData( $asSubEntities[1], true, $bUseUTF8 ) : null;
			$sWidth = preg_match('/<width\s*>(.+?)<\/width\s*>/is', $asEntities[1], $asSubEntities ) ? $oXML->decodeData( $asSubEntities[1], true, $bUseUTF8 ) : null;
			$sHeight = preg_match('/<height\s*>(.+?)<\/height\s*>/is', $asEntities[1], $asSubEntities ) ? $oXML->decodeData( $asSubEntities[1], true, $bUseUTF8 ) : null;
			if( isset( $sURL, $sTitle, $sLink ) )
        $this->setImage( $sURL, $sTitle, $sLink, $sDescription, $sWidth, $sHeight );
		}
		$sRSS = preg_replace('/<image\s*>.*?<\/image\s*>/is', null, $sRSS );

		// ... title
		if( preg_match( '/<title\s*>(.+?)<\/title\s*>/is', $sRSS, $asEntities ) ) $this->setTitle( $oXML->decodeData( $asEntities[1], true, $bUseUTF8 ) );

		// ... link
		if( preg_match( '/<link\s*>(.+?)<\/link\s*>/is', $sRSS, $asEntities ) ) $this->setLink( $oXML->decodeData( $asEntities[1], true, $bUseUTF8 ) );

		// ... description
		if( preg_match( '/<description\s*>(.+?)<\/description\s*>/is', $sRSS, $asEntities ) ) $this->setDescription( $oXML->decodeData( $asEntities[1], true, $bUseUTF8 ) );

		// ... language
		if( preg_match( '/<language\s*>(.+?)<\/language\s*>/is', $sRSS, $asEntities ) ) $this->setLanguage( $oXML->decodeData( $asEntities[1], true, $bUseUTF8 ) );

		// ... copyright
		if( preg_match( '/<copyright\s*>(.+?)<\/copyright\s*>/is', $sRSS, $asEntities ) ) $this->setCopyright( $oXML->decodeData( $asEntities[1], true, $bUseUTF8 ) );

		// ... managing editor
		if( preg_match( '/<managingEditor\s*>(.+?)<\/managingEditor\s*>/is', $sRSS, $asEntities ) ) $this->setManagingEditor( $oXML->decodeData( $asEntities[1], true, $bUseUTF8 ) );

		// ... web master
		if( preg_match( '/<webMaster\s*>(.+?)<\/webMaster\s*>/is', $sRSS, $asEntities ) ) $this->setWebMaster( $oXML->decodeData( $asEntities[1], true, $bUseUTF8 ) );

		// ... publication date
		if( preg_match( '/<pubDate\s*>(.+?)<\/pubDate\s*>/is', $sRSS, $asEntities ) ) $this->setPubDate( strtotime( $oXML->decodeData( $asEntities[1], true, $bUseUTF8 ) ) );

		// ... last build date
		if( preg_match( '/<lastBuildDate\s*>(.+?)<\/lastBuildDate\s*>/is', $sRSS, $asEntities ) ) $this->setLastBuildDate( strtotime( $oXML->decodeData( $asEntities[1], true, $bUseUTF8 ) ) );

		// ... category
		if( preg_match_all('/<category(.*?)>(.+?)<\/category\s*>/is', $sRSS, $asEntities, PREG_SET_ORDER) )
      foreach( $asEntities as $sEntity )
      {
        $sDomain = preg_match( '/domain\s*=\s*"(.+?)"/is', $sEntity[1] , $asAttributes )? $oXML->decodeData( $asAttributes[1], false, $bUseUTF8 ) : null;
			$this->addCategory( $oXML->decodeData( $sEntity[2], true, $bUseUTF8 ), $sDomain );
      }

		// ... TTL
		if( preg_match( '/<ttl\s*>(.+?)<\/ttl\s*>/is', $sRSS, $asEntities ) ) $this->setTTL( $oXML->decodeData( $asEntities[1], true, $bUseUTF8 ) );

		// ... cloud
		if( preg_match( '/<cloud(.+?)\/\s*>/is', $sRSS, $asEntities ) )
    {
			$sDomain = preg_match( '/domain\s*=\s*"(.+?)"/is', $asEntities[1], $asAttributes ) ? $oXML->decodeData( $asAttributes[1], false, $bUseUTF8 ) : null;
			$sPort = preg_match( '/port\s*=\s*"(.+?)"/is', $asEntities[1], $asAttributes ) ? $oXML->decodeData( $asAttributes[1], false, $bUseUTF8 ) : null;
			$sPath = preg_match( '/path\s*=\s*"(.+?)"/is', $asEntities[1], $asAttributes ) ? $oXML->decodeData( $asAttributes[1], false, $bUseUTF8 ) : null;
			$sProcedure = preg_match( '/registerProcedure\s*=\s*"(.+?)"/is', $asEntities[1], $asAttributes ) ? $oXML->decodeData( $asAttributes[1], false, $bUseUTF8 ) : null;
			$sProtocol = preg_match( '/protocol\s*=\s*"(.+?)"/is', $asEntities[1], $asAttributes ) ? $oXML->decodeData( $asAttributes[1], false, $bUseUTF8 ) : null;
			if( isset( $sDomain,$sPort,$sPath,$sProcedure,$sProtocol ) ) $this->setCloud( $sDomain, $sPort, $sPath, $sProcedure, $sProtocol );
		}

		// ... rating
		if( preg_match( '/<rating\s*>(.+?)<\/rating\s*>/is', $sRSS, $asEntities ) ) $this->setRating( $oXML->decodeData( $asEntities[1], true, $bUseUTF8 ) );

		// ... skip hours
		if( preg_match( '/<skipHours\s*>(.+?)<\/skipHours\s*>/is', $sRSS, $asEntities ) and preg_match_all( '/<hour\s*>(.+?)<\/hour\s*>/is', $asEntities[1], $asSubEntities, PREG_SET_ORDER ) )
      foreach( $asSubEntities as $sSubEntity ) $this->addSkipHour( $oXML->decodeData( $sSubEntity[1], true, $bUseUTF8 ) );

		// ... skip days
		if( preg_match( '/<skipDays\s*>(.+?)<\/skipDays\s*>/is', $sRSS, $asEntities ) and preg_match_all( '/<day\s*>(.+?)<\/day\s*>/is', $asEntities[1], $asSubEntities, PREG_SET_ORDER ) )
      foreach( $asSubEntities as $sSubEntity ) $this->addSkipDay( $oXML->decodeData( $sSubEntity[1], true, $bUseUTF8 ) );
	}

  /** Import this item from supplied XML (RSS) document
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>, <SAMP>PHP_APE_RSS_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sXML XML (RSS) document
   */
	function import( $sXML )
  {
		// Sanitize input
		$sXML = PHP_APE_Type_String::parseValue( $sXML, true );

    // Output
    $oXML = new PHP_APE_DataSpace_XML();

		// Parse XML document
    $sXML = $oXML->explodeStream( $sXML );
		if( count( $sXML ) < 1 )
      throw new PHP_APE_RSS_Exception( __METHOD__, 'No XML document' );
    $sXML = $sXML[0]['data'];

		// Parse XML data
		$bUseUTF8 = preg_match( '/encoding\s*=\s*"utf-8"/i', $sXML );
		$this->parse( $sXML, $bUseUTF8 );
	}


}
