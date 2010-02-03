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

/** RSS item
 *
 * @package PHP_APE_RSS
 * @subpackage Classes
 */
class PHP_APE_RSS_Item
extends PHP_APE_RSS_Any
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Item's title
   * @var string */
  private $sTitle;

  /** Item's link (URL)
   * @var string */
	private $sLink;

  /** Item's description
   * @var string */
	private $sDescription;

  /** Item's author
   * @var string */
	private $sAuthor;

  /** Item's publication date (UNIX timestamp)
   * @var float */
	private $fPubDate;

  /** Item's Globally Unique IDentifier
   * @var string */
	private $sGUID;

  /** Item's GUID is a permanent link
   * @var boolean */
	private $bGUIDPermaLink;

  /** Item's categories
   * @var array|string */
	private $asCategories;

  /** Item's categories domains (URLs)
   * @var array|string */
	private $asCategoriesDomains;

  /** Item's comments (URL)
   * @var string */
	private $sComments;

  /** Item's enclosure (URL)
   * @var string */
	private $sEnclosureURL;

  /** Item's enclosure length
   * @var integer */
	private $iEnclosureLength;

  /** Item's enclosure type
   * @var string */
	private $sEnclosureType;

  /** Item's source (URL)
   * @var string */
	private $sSourceURL;

  /** Item's source title
   * @var string */
	private $sSourceTitle;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs an new item instance
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param string|PHP_APE_Type_Any $sTitle Item's title
   * @param string|PHP_APE_Type_Any $sLink Item's link (URL)
   * @param string|PHP_APE_Type_Any $sDescription Item's description
   * @param string|PHP_APE_Type_Any $sAuthor Item's author
   * @param float|PHP_APE_Type_Any $fPubDate Item's publication date (UNIX timestamp)
   */
  public function __construct( $sTitle = null, $sLink = null, $sDescription = null, $sAuthor = null, $fPubDate = null )
  {
    // Construct item
    $this->set( $sTitle, $sLink, $sDescription, $sAuthor, $fPubDate );
  }

  /** Resets this item's content
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   */
  public function reset()
  {
    $this->sTitle = null;
    $this->sLink = null;
    $this->sDescription = null;
    $this->sAuthor = null;
    $this->fPubDate = null;
    $this->sGUID = null;
    $this->bGUIDPermaLink = null;
    $this->asCategories = null;
    $this->asCategoriesDomains = null;
    $this->sComments = null;
    $this->sEnclosureURL = null;
    $this->iEnclosureLength = null;
    $this->sEnclosureType = null;
    $this->sSourceURL = null;
    $this->sSourceTitle = null;
  }


  /*
   * METHODS: setters
   ********************************************************************************/

  /** Globally sets this item's content
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sTitle Item's title
   * @param string|PHP_APE_Type_Any $sLink Item's link (URL)
   * @param string|PHP_APE_Type_Any $sDescription Item's description
   * @param string|PHP_APE_Type_Any $sAuthor Item's author
   * @param float|PHP_APE_Type_Any $fPubDate Item's publication date (UNIX timestamp)
   */
  public function set( $sTitle = null, $sLink = null, $sDescription = null, $sAuthor = null, $fPubDate = null )
  {
    $this->setTitle( $sTitle );
    $this->setLink( $sLink );
    $this->setDescription( $sDescription );
    $this->setAuthor( $sAuthor );
    $this->setPubDate( $fPubDate );
  }

  /** Sets this item's title
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sTitle Item's title
   */
  final public function setTitle( $sTitle )
  {
    $this->sTitle = PHP_APE_Type_String::parseValue( $sTitle, true );
  }

  /** Sets this item's link
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sLink Item's link (URL)
   */
  final public function setLink( $sLink )
  {
    $this->sLink = PHP_APE_Type_URL::parseValue( $sLink, true );
  }

  /** Sets this item's description
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sDescription Item's description
   */
  final public function setDescription( $sDescription )
  {
    $this->sDescription = PHP_APE_Type_String::parseValue( $sDescription, true );
  }

  /** Sets this item's author
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sAuthor Item's author
   */
  final public function setAuthor( $sAuthor )
  {
    $this->sAuthor = PHP_APE_Type_String::parseValue( $sAuthor, true );
  }

  /** Sets this item's publication date
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param float|PHP_APE_Type_Any $sPublication Date Item's publication date
   */
  final public function setPubDate( $fPubDate )
  {
    $this->fPubDate = PHP_APE_Type_Timestamp::parseValue( $fPubDate, true, null, true );
  }

  /** Sets this item's GUID
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sGUID Item's Globally Unique IDentifier
   * @param boolean|PHP_APE_Type_Any $bPermaLink Item's GUID is a permanent link
   */
  final public function setGUID( $sGUID, $bPermaLink = false )
  {
    $this->sGUID = PHP_APE_Type_String::parseValue( $sGUID, true );
    $this->bGUIDPermaLink = PHP_APE_Type_Boolean::parseValue( $bPermaLink, true );
  }

  /** Adds another category to this item
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sCategory Item's category
   * @param string|PHP_APE_Type_Any $sCategoryDomain Item's category domain (URL)
   */
  final public function addCategory( $sCategory, $sCategoryDomain = null )
  {
    if( !is_array( $this->asCategories ) ) $this->asCategories = array();
    if( !is_array( $this->asCategoriesDomains ) ) $this->asCategoriesDomains = array();
    array_push( $this->asCategories, PHP_APE_Type_String::parseValue( $sCategory, true ) );
    array_push( $this->asCategoriesDomains, PHP_APE_Type_URL::parseValue( $sCategoryDomain, true ) );
  }

  /** Sets this item's comments
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sComments Item's comments (URL)
   */
  final public function setComments( $sComments )
  {
    $this->sComments = PHP_APE_Type_URL::parseValue( $sComments );
  }

  /** Sets this item's enclosure
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sEnclosureURL Item's enclosure (URL)
   * @param integer|PHP_APE_Type_Any $iEnclosureLength Item's enclosure length
   * @param string|PHP_APE_Type_Any $sEnclosureType Item's enclosure type
   */
  final public function setEnclosure( $sEnclosureURL, $iEnclosureLength, $sEnclosureType )
  {
    $this->sEnclosureURL = PHP_APE_Type_URL::parseValue( $sEnclosureURL );
    $this->iEnclosureLength = PHP_APE_Type_Integer::parseValue( $iEnclosureLength );
    if( $this->iEnclosureLength <= 0 )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid value; Value: '.$iEnclosureLength );
    $this->sEnclosureType = PHP_APE_Type_String::parseValue( $sEnclosureType );
  }

  /** Sets this item's source
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @param string|PHP_APE_Type_Any $sSourceURL Item's source (URL)
   * @param string|PHP_APE_Type_Any $sSourceTitle Item's source title
   */
  final public function setSource( $sSourceURL, $sSourceTitle )
  {
    $this->sSourceURL = PHP_APE_Type_URL::parseValue( $sSourceURL );
    $this->sSourceTitle = PHP_APE_Type_String::parseValue( $sSourceTitle );
  }


  /*
   * METHODS: getters
   ********************************************************************************/

  /** Retrieves this item's title
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getTitle()
  {
    return $this->sTitle;
  }

  /** Retrieves this item's link (URL)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getLink()
  {
    return $this->sLink;
  }

  /** Retrieves this item's description
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getDescription()
  {
    return $this->sDescription;
  }

  /** Retrieves this item's author
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getAuthor()
  {
    return $this->sAuthor;
  }

  /** Retrieves this item's publication date (UNIX timestamp)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return float
   */
  final public function getPubDate()
  {
    return $this->fPubDate;
  }

  /** Retrieves this item's Globally Unique IDentifier
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getGUID()
  {
    return $this->sGUID;
  }

  /** Retrieves whether this item's GUID is a permanent link
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return boolean
   */
  final public function isGUIDPermaLink()
  {
    return $this->bGUIDPermaLink;
  }

  /** Retrieves this item's categories quantities
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return integer
   */
  final public function countCategories()
  {
    return is_array( $this->asCategories ) ? count( $this->asCategories ) : 0;
  }

  /** Retrieves this item's category
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

  /** Retrieves this item's category domain (URL)
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

  /** Retrieves this item's comments (URL)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getComments()
  {
    return $this->sComments;
  }

  /** Retrieves this item's enclosure (URL)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getEnclosureURL()
  {
    return $this->sEnclosureURL;
  }

  /** Retrieves this item's enclosure length
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return integer
   */
  final public function getEnclosureLength()
  {
    return $this->iEnclosureLength;
  }

  /** Retrieves this item's enclosure type
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getEnclosureType()
  {
    return $this->sEnclosureType;
  }

  /** Retrieves this item's source (URL)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getSourceURL()
  {
    return $this->sSourceURL;
  }

  /** Retrieves this item's source title
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return string
   */
  final public function getSourceTitle()
  {
    return $this->sSourceTitle;
  }


  /*
   * METHODS: output
   ********************************************************************************/

  /** Outputs this item in XML (RSS) format
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param float $fVersion RSS version
   * @param boolean $bUseUTF8 Use UTF8 encoding
   * @return string
   */
	function format( $fVersion = 2.0, $bUseUTF8 = false )
  {
		// Sanitize input
		$fVersion = (float)$fVersion;
		$bUseUTF8 = (boolean)$bUseUTF8;

    // Output
    $oXML = new PHP_APE_DataSpace_XML();
		$sOutput=null;

		// Begin item
		$sOutput .= '<item>'."\n";

		// Output elements

		// ... title
		if( isset( $this->sTitle ) )
      $sOutput .= '<title>'.$oXML->encodeData( $this->sTitle, true, $bUseUTF8 ).'</title>'."\n";

		// ... link
		if( isset( $this->sLink ) )
      $sOutput .= '<link>'.$oXML->encodeData( $this->sLink, true, $bUseUTF8 ).'</link>'."\n";

		// ... description
		if( isset( $this->sDescription) )
      $sOutput .= '<description>'.$oXML->encodeData( $this->sDescription, true, $bUseUTF8 ).'</description>'."\n";

		// ... author
		if( $fVersion >= 2.0 and isset( $this->sAuthor ) )
      $sOutput .= '<author>'.$oXML->encodeData( $this->sAuthor, true, $bUseUTF8 ).'</author>'."\n";

		// ... publication date
		if( $fVersion >= 2.0 and $this->fPubDate > 0 )
      $sOutput .= '<pubDate>'.$oXML->encodeData( date( 'r' , $this->fPubDate ), false, false ).'</pubDate>'."\n";

		// ... GUID
		if( $fVersion >= 2.0 and isset( $this->sGUID ) )
      $sOutput .= '<guid isPermaLink="'.( $this->bGUIDPermaLink ? 'true' : 'false' ).'">'.$oXML->encodeData( $this->sGUID, true, $bUseUTF8 ).'</guid>'."\n";

		// ... category
		if( $fVersion >= 0.92 and is_array( $this->asCategories ) and count( $this->asCategories) )
      foreach( $this->asCategories as $iIndex => $sCategory )
        if( isset( $sCategory ) )
        {
          $sCategoryDomain = $this->asCategoriesDomains[ $iIndex ];
          $sOutput .= '<category'.( isset( $sCategoryDomain ) ? ' domain="'.$oXML->encodeData( $sCategoryDomain, false, $bUseUTF8 ).'"' : '' ).'>'.$oXML->encodeData( $sCategory, true, $bUseUTF8 ).'</category>'."\n";
        }

		// ... comments
		if( $fVersion >= 2.0 and isset( $this->sComments ) )
      $sOutput .= '<comments>'.$oXML->encodeData( $this->sComments, true, $bUseUTF8 ).'</comments>'."\n";

		// ... enclosure
		if( $fVersion >= 0.92 and isset( $this->sEnclosureURL, $this->iEnclosureLength, $this->sEnclosureType ) )
      $sOutput .= '<enclosure url="'.$oXML->encodeData( $this->sEnclosureURL, false, $bUseUTF8 ).'" length="'.$oXML->encodeData( $this->iEnclosureLength, false, $bUseUTF8 ).'" type="'.$oXML->encodeData( $this->sEnclosureType, false, $bUseUTF8 ).'" />'."\n";

		// ... source
		if( $fVersion >= 0.92 and isset( $this->sSourceTitle, $this->sSourceURL ) )
      $sOutput .= '<source url="'.$oXML->encodeData( $this->sSourceURL, false, $bUseUTF8 ).'">'.$oXML->encodeData( $this->sSourceTitle, true, $bUseUTF8).'</source>'."\n";

		// End item
		$sOutput .= '</item>'."\n";


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

		// Parse item
    $oXML = new PHP_APE_DataSpace_XML();
		if( preg_match( '/<item\s*>(.+?)<\/item\s*>/is', $sRSS, $asEntities) )
      $sRSS = $asEntities[1];
		else
      throw new PHP_APE_RSS_Exception( __METHOD__, 'No items' );

		// ... title
		if( preg_match( '/<title\s*>(.+?)<\/title\s*>/is', $sRSS, $asEntities ) )
      $this->setTitle( $oXML->decodeData( $asEntities[1], true, $bUseUTF8 ) );

		// ... link
		if( preg_match( '/<link\s*>(.+?)<\/link\s*>/is', $sRSS, $asEntities ) )
      $this->setLink( $oXML->decodeData( $asEntities[1], true, $bUseUTF8 ) );

		// ... description
		if( preg_match( '/<description\s*>(.+?)<\/description\s*>/is', $sRSS, $asEntities ) )
      $this->setDescription( $oXML->decodeData( $asEntities[1], true, $bUseUTF8 ) );

		// ... author
		if( preg_match( '/<author\s*>(.+?)<\/author\s*>/is', $sRSS, $asEntities ) )
      $this->setAuthor( $oXML->decodeData( $asEntities[1], true, $bUseUTF8 ) );

		// ... publication date
		if( preg_match( '/<pubDate\s*>(.+?)<\/pubDate\s*>/is', $sRSS, $asEntities ) )
      $this->setPubDate( strtotime( $oXML->decodeData( $asEntities[1], true, $bUseUTF8 ) ) );

		// ... GUID
		if( preg_match( '/<guid\s*>(.+?)<\/guid\s*>/is', $sRSS, $asEntities ) )
      $this->setGUID( $oXML->decodeData( $asEntities[1], true, $bUseUTF8 ) );

		// ... category
		if( preg_match_all('/<category(.*?)>(.+?)<\/category\s*>/is', $sRSS, $asEntities, PREG_SET_ORDER) )
      foreach( $asEntities as $sEntity )
      {
        $sDomain = preg_match( '/domain\s*=\s*"(.+?)"/is', $sEntity[1] , $asAttributes )? $oXML->decodeData( $asAttributes[1], false, $bUseUTF8 ) : null;
			$this->addCategory( $oXML->decodeData( $sEntity[2], true, $bUseUTF8 ), $sDomain );
      }

		// ... comments
		if( preg_match( '/<comments\s*>(.+?)<\/comments\s*>/is', $sRSS, $asEntities ) )
      $this->setComments( $oXML->decodeData( $asEntities[1], true, $bUseUTF8 ) );

		// ... enclosure
		if( preg_match( '/<enclosure(.+?)\/\s*>/is', $sRSS, $asEntities ) )
    {
			$sURL = preg_match( '/url\s*=\s*"(.+?)"/is', $asEntities[1], $asAttributes ) ? $oXML->decodeData( $asAttributes[1], false, $bUseUTF8 ) : null;
			$sLength = preg_match( '/length\s*=\s*"(.+?)"/is', $asEntities[1], $asAttributes ) ? $oXML->decodeData( $asAttributes[1], false, $bUseUTF8 ) : null;
			$sType = preg_match( '/type\s*=\s*"(.+?)"/is', $asEntities[1], $asAttributes ) ? $oXML->decodeData( $asAttributes[1], false, $bUseUTF8 ) : null;
			if( isset( $sURL, $sLength, $sType ) ) $this->setEnclosure( $sURL, $sLength, $sType );
		}

		// ... source
		if( preg_match( '/<source(.+?)>(.+?)<\/source\s*>/is', $sRSS, $asEntities ) )
    {
			$sURL = preg_match( '/url\s*=\s*"(.+?)"/is', $asEntities[1], $asAttributes ) ? $oXML->decodeData( $asAttributes[1], false, $bUseUTF8 ) : null;
			if( isset( $sURL ) ) $this->setSource( $sURL, $oXML->decodeData( $asEntities[2], true, $bUseUTF8 ) );
		}

	}


}
