<?php // INDENTING (emacs/vi): -*- mode:php; tab-width:2; c-basic-offset:2; intent-tabs-mode:nil; -*- ex: set tabstop=2 expandtab:
/*
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
 */

/** TESTS
 *
 * <P><B>SYNOPSIS:</B> This script tests the definition and behaviour of the objects included in this package,
 * which should allow developers and users to verify the functionality of its resources,
 * and hopefully also gain a better understanding on how to implement or use them.</P>
 *
 * @package PHP_APE_RSS
 * @subpackage Tests
 */

/** Load PHP-APE */
//echo "<H1>APE tests</H1>\r\n";
//echo "<P><SPAN CLASS=\"important\">SYNOPSIS:</SPAN> This script tests the definition and behaviour of the objects included in this package,\r\n";
//echo "which should allow developers and users to verify the functionality of its resources,\r\n";
//echo "and hopefully also gain a better understanding on how to implement or use them.</P>\r\n";
require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
require_once( PHP_APE_ROOT.'/lib/rss/load.php' );

// Tests

// Create items
// ... nr.1
$oItem1 = new PHP_APE_RSS_Item();
$oItem1->setTitle( 'Item N°1' );
$oItem1->setLink( 'http://localhost.localdomain/link' );
$oItem1->setDescription( 'Description of item N°1' );
$oItem1->setAuthor( 'Author of item N°1' );
$oItem1->setPubDate( time() );
$oItem1->setGUID( sha1( 'Item N°1' ), false );
$oItem1->addcategory( 'First category of item N°1', 'http://localhost.localdomain/category_1' );
$oItem1->addcategory( 'Second category of item N°1', 'http://localhost.localdomain/category_2' );
$oItem1->setComments( 'http://localhost.localdomain/comments' );
$oItem1->setEnclosure( 'http://localhost.localdomain/enclosure', 1000, 'application/octet-stream' );
$oItem1->setSource( 'http://localhost.localdomain/rss', 'RSS test channel' );
// ... nr.2
$oItem2 = new PHP_APE_RSS_Item();
$oItem2->setTitle( 'Item N°2' );
$oItem2->setLink( 'http://localhost.localdomain/link' );
$oItem2->setDescription( 'Description of item N°2' );
$oItem2->setAuthor( 'Author of item N°2' );
$oItem2->setPubDate( time() );
$oItem2->setGUID( sha1( 'Item N°2' ), false );
$oItem2->addcategory( 'First category of item N°2', 'http://localhost.localdomain/category_1' );
$oItem2->addcategory( 'Second category of item N°2', 'http://localhost.localdomain/category_2' );
$oItem2->setComments( 'http://localhost.localdomain/comments' );
$oItem2->setEnclosure( 'http://localhost.localdomain/enclosure', 1000, 'application/octet-stream' );
$oItem2->setSource( 'http://localhost.localdomain/rss', 'RSS test channel' );

// Create channel
$oChannel = new PHP_APE_RSS_Channel();
$oChannel->setTitle( 'RSS test channel' );
$oChannel->setLink( 'http://localhost.localdomain/rss' );
$oChannel->setDescription( 'Description of RSS test channel' );
$oChannel->setLanguage( 'Language of RSS test channel' );
$oChannel->setCopyright( 'Copyright of RSS test channel' );
$oChannel->setManagingEditor( 'Managing editor of RSS test channel' );
$oChannel->setWebMaster( 'Webmaster of RSS test channel' );
$oChannel->setPubDate( time() );
$oChannel->setLastBuildDate( time()-3660 );
$oChannel->setPubDate( time() );
$oChannel->addcategory( 'First category of RSS test channel', 'http://localhost.localdomain/category_1' );
$oChannel->addcategory( 'Second category of RSS test channel', 'http://localhost.localdomain/category_2' );
$oChannel->setTTL( 60 );
$oChannel->setCloud( 'cloud.domain.tld', 80, '/RSS', 'subscribe', 'xml-rpc' );
$oChannel->setRating( 'PICS rating of RSS test channel' );
$oChannel->setImage( 'http://localhost.localdomain/image', 'Image of RSS test channel', 'http://localhost.localdomain/rss', 'Image description of RSS test channel', 100, 50 );
for( $iHour=0; $iHour<12; $iHour++ ) $oChannel->addSkipHour( $iHour );
$oChannel->addSkipDay( 'Saturday' );
$oChannel->addSkipDay( 'Sunday' );
$oChannel->addItem( $oItem1 );
$oChannel->addItem( $oItem2 );

// XML output
$sXML = $oChannel->export();
// ... DEBUG
//echo nl2br( htmlentities( $oChannel->export() ) ); exit;

// Parse adn re-export (double-check)
$oChannel->import( $sXML );
$sXML = $oChannel->export();
// ... DEBUG
//echo nl2br( htmlentities( $oChannel->export() ) ); exit;

// Output
if( !headers_sent() ) header( 'Content-type: text/xml; charset=iso-8859-1' );
echo $sXML;
