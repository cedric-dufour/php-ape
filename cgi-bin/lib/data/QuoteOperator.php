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
 * @package PHP_APE_Data
 * @subpackage Classes
 */

/** Quoting/nesting operators handling class
 *
 * @package PHP_APE_Data
 * @subpackage Classes
 */
class PHP_APE_Data_QuoteOperator
extends PHP_APE_Data_Operator
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** Operator's code binary mask
   * @var integer */
  const Mask = 1792; // 0b.....111:.......

  /** none
   * @var integer */
  const None = 0; // 0b.....000:........

  /** <SAMP>'...'</SAMP> (single quote)
   * @var integer */
  const Single = 256; // 0b.....001:........

  /** <SAMP>"..."</SAMP> (double quote)
   * @var integer */
  const Double = 512; // 0b.....010:........

  /** <SAMP>[...]</SAMP> (bracket)
   * @var integer */
  const Bracket = 768; // 0b.....011:........

  /** <SAMP>{...}</SAMP> (brace)
   * @var integer */
  const Brace = 1024; // 0b.....100:........

  /** <SAMP>(...)</SAMP> (parenthesis) => nesting
   * @var integer */
  const Parenthesis = 1792; // 0b.....111:........


  /*
   * METHODS
   ********************************************************************************/

  /** Returns the opening <I>string</I> representation for the given operator
   *
   * @param integer $iOperator Operator code
   * @return string
   */
  public static function toOpeningString( $iOperator )
  {
    static $asOperators = 
      array(
            self::None => '',
            self::Single => '\'',
            self::Double => '"',
            self::Bracket => '[',
            self::Brace => '{',
            self::Parenthesis => '(',
            );

    $iOperator = (integer)$iOperator & self::Mask;
    if( !array_key_exists( $iOperator, $asOperators ) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Invalid operator; Code: '.$iOperator );
    return $asOperators[ $iOperator ];
  }

  /** Returns the closing <I>string</I> representation for the given operator
   *
   * @param integer $iOperator Operator code
   * @return string
   */
  public static function toClosingString( $iOperator )
  {
    static $asOperators = 
      array(
            self::None => '',
            self::Single => '\'',
            self::Double => '"',
            self::Bracket => ']',
            self::Brace => '}',
            self::Parenthesis => ')',
            );

    $iOperator = (integer)$iOperator & self::Mask;
    if( !array_key_exists( $iOperator, $asOperators ) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Invalid operator; Code: '.$iOperator );
    return $asOperators[ $iOperator ];
  }

  /** Returns the operator code corresponding to the given <I>string</I>-represented operator
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   *
   * @param string $sString Input string
   * @return integer
   */
  public static function fromString( $sString )
  {
    static $aiOperators = 
      array(
            '' => self::None,
            '\'' => self::Single,
            '"' => self::Double,
            '[' => self::Bracket,
            ']' => self::Bracket,
            '{' => self::Brace,
            '}' => self::Brace,
            '(' => self::Parenthesis,
            ')' => self::Parenthesis
            );

    $sString = trim( $sString );
    if( !array_key_exists( $sString, $aiOperators ) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Invalid/unparsable operator; String: '.$sString );
    return $aiOperators[ $sString ];
  }

}
