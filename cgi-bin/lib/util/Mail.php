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
 * @subpackage Miscellaneous
 */

/** Load PEAR::Mail resources */
require_once( 'Mail.php' );
/** Load PEAR::Mail_Mime resources */
require_once( 'Mail/mime.php' );

/** (E-)Mail-related utilities
 *
 * @package PHP_APE_Util
 * @subpackage Miscellaneous
 */
class PHP_APE_Util_Mail
extends PHP_APE_Util_Any
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Subject
   * @var string */
  private $sSubject;

  /** Mailer object
   * @var Mail_mime */
  private $oMailer;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  public function __construct( $sTemplate, $asVariables = null )
  {
    // Mailer (PEAR::Mail_Mime) object
    $this->oMailer = new Mail_mime( "\n" );

    // Mail parts
    $asMailParts = self::parseTemplate( $sTemplate, $asVariables );
    if( isset( $asMailParts['subject'] ) )
      $this->sSubject = $asMailParts['subject'];
    if( isset( $asMailParts['text'] ) )
      $this->oMailer->setTXTBody( $asMailParts['text'] );
    if( isset( $asMailParts['html'] ) )
      $this->oMailer->setHTMLBody( $asMailParts['html'] );
  }


  /*
   * METHODS: Mailing
   ********************************************************************************/

  public function send( $sSender, $sRecipients, $asHeaders = null )
  {
    // Mail components
    // ... body
    $sBody = $this->oMailer->get();
    // ... headers
    if( !is_array( $asHeaders ) ) $asHeaders = array();
    if( !empty( $sSender ) ) $asHeaders['From'] = $sSender;
    if( !empty( $this->sSubject ) ) $asHeaders['Subject'] = $this->sSubject;
    if( empty( $asHeaders ) ) $asHeaders = null;
    $sHeaders = $this->oMailer->headers( $asHeaders );

    // Send
    $roMail =& Mail::factory( 'mail' );
    if( is_array( $sRecipients ) ) $sRecipients = implode( ';', $sRecipients ); // make sure we have a string
    foreach( array_unique( preg_split( '/[ ,;:]+/', $sRecipients ) ) as $sRecipient )
    {
      if( empty( $sRecipient ) ) continue;
      if( PHP_APE_DEBUG ) PHP_APE_WorkSpace::useEnvironment()->log( __METHOD__, 'Sending mail; Recipient: '.$sRecipient, E_USER_NOTICE );
      $roMail->send( $sRecipient, $sHeaders, $sBody );
    }
  }


  /*
   * METHODS: Static
   ********************************************************************************/

  /** Parses and returns the components of the supplied e-mail template
   *
   * <P><B>USAGE:</B> This function takes an e-mail template as argument, similar to
   * example below (extra white-spaces are automatically stripped off):</P>
   * <PRE>
   * #{SUBJECT}
   * Subject line (including variable: name => @{name})
   *
   * #{TEXT}
   * Text-formatted message body (including variable: name => @{name})
   *
   * #{HTML}
   * <P>HTML-formatted message body (including variable: name => @{name})</P>
   * </PRE>
   * <P><B>RETURNS:</B> An array associating:</P>
   * <UL>
   * <LI><SAMP>subject</SAMP> => subject line (<SAMP>null</SAMP> if none), including variables substitutions</LI>
   * <LI><SAMP>text</SAMP> => text-formatted message body (<SAMP>null</SAMP> if none), including variables substitutions</LI>
   * <LI><SAMP>html</SAMP> => HTML-formatted message body (<SAMP>null</SAMP> if none), including variables substitutions</LI>
   * </UL>
   *
   * @param string $sTemplate E-mail template
   * @param array|string $asVariables Substitution variables (associating <SAMP>name</SAMP> => <SAMP>value</SAMP>)
   * @return array|string
   */
  public static function parseTemplate( $sTemplate, $asVariables = null )
  {

    // Sanitize input
    if( !is_array( $asVariables ) )
      $asVariables = array();

    // Output
    $asOutput = array( 'subject' => null, 'text' => null, 'html' => null );
    
    // ... search patterns
    $asSearch = array_keys( $asVariables );
    foreach( $asSearch as &$roSearch ) $roSearch = '@{'.$roSearch.'}';

    // ... split content
    $iPosition_subject = stripos( $sTemplate, '#{subject}' );
    $iPosition_text = stripos( $sTemplate, '#{text}' );
    $iPosition_html = stripos( $sTemplate, '#{html}' );

    // ... subject
    if( $iPosition_subject !== false )
    {
      $iPosition_subject += 10;
      $iPosition_end = strlen( $sTemplate );
      if( $iPosition_text !== false and $iPosition_text > $iPosition_subject and $iPosition_text < $iPosition_end )
        $iPosition_end = $iPosition_text;
      if( $iPosition_html !== false and $iPosition_html > $iPosition_subject and $iPosition_html < $iPosition_end )
        $iPosition_end = $iPosition_html;
      $asOutput['subject'] = str_ireplace( $asSearch, $asVariables, trim( substr( $sTemplate, $iPosition_subject, $iPosition_end - $iPosition_subject ) ) );
      
    }

    // ... body
    if( $iPosition_text !== false )
    {
      $iPosition_text += 7;
      $iPosition_end = strlen( $sTemplate );
      if( $iPosition_text !== false and $iPosition_text > $iPosition_text and $iPosition_text < $iPosition_end )
        $iPosition_end = $iPosition_text;
      if( $iPosition_html !== false and $iPosition_html > $iPosition_text and $iPosition_html < $iPosition_end )
        $iPosition_end = $iPosition_html;
      $asOutput['text'] = str_ireplace( $asSearch, $asVariables, trim( substr( $sTemplate, $iPosition_text, $iPosition_end - $iPosition_text ) ) );
    }

    // ... body (HTML)
    if( $iPosition_html !== false )
    {
      $iPosition_html += 7;
      $iPosition_end = strlen( $sTemplate );
      if( $iPosition_subject !== false and $iPosition_subject > $iPosition_html and $iPosition_subject < $iPosition_end )
        $iPosition_end = $iPosition_subject;
      if( $iPosition_text !== false and $iPosition_text > $iPosition_html and $iPosition_text < $iPosition_end )
        $iPosition_end = $iPosition_text;
      $asOutput['html'] = str_ireplace( $asSearch, array_map( 'htmlentities', $asVariables ), trim( substr( $sTemplate, $iPosition_html, $iPosition_end - $iPosition_html ) ) );
    }

    // End
    return $asOutput;

  }

}
