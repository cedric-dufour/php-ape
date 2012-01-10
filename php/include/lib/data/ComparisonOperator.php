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

/** Comparison operators handling class
 *
 * @package PHP_APE_Data
 * @subpackage Classes
 */
class PHP_APE_Data_ComparisonOperator
extends PHP_APE_Data_Operator
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** Operator's code binary mask
   * @var integer */
  const Mask = 240; // 0b........:1111....

  /** <SAMP>=</SAMP> (equal)
   * @var integer */
  const Equal = 0; // 0b........:0000....

  /** <SAMP><></SAMP> (not equal)
   * @var integer */
  const NotEqual = 128; // 0b........:1000....

  /** <SAMP>&lt;=</SAMP> (smaller or equal)
   * @var integer */
  const SmallerOrEqual = 16; // 0b........:0001....

  /** <SAMP>&lt;</SAMP> (smaller)
   * @var integer */
  const Smaller = 144; // 0b........:1001....

  /** <SAMP>&gt;=</SAMP> (bigger or equal)
   * @var integer */
  const BiggerOrEqual = 32; // 0b........:0010....

  /** <SAMP>&gt;</SAMP> (bigger)
   * @var integer */
  const Bigger = 160; // 0b........:1010....

  /** <SAMP>~</SAMP> (proportional)
   * @var integer */
  const Proportional = 48; // 0b........:0011....

  /** <SAMP>¬</SAMP> (included)
   * @var integer */
  const Included = 112; // 0b........:0111....


  /*
   * METHODS
   ********************************************************************************/

  /** Returns the <I>string</I> representation for the given operator
   *
   * @param integer $iOperator Operator code
   * @return string
   */
  public static function toString( $iOperator )
  {
    static $asOperators = 
      array(
            self::Equal => '=',
            self::NotEqual => '<>',
            self::SmallerOrEqual => '<=',
            self::Smaller => '<',
            self::BiggerOrEqual => '>=',
            self::Bigger => '>',
            self::Proportional => '~',
            self::Included => '¬'
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
            '=' => self::Equal,
            'eq' => self::Equal,
            '<>' => self::NotEqual,
            'neq' => self::NotEqual,
            '<' => self::Smaller,
            '<=' => self::SmallerOrEqual,
            '>' => self::Bigger,
            '>=' => self::BiggerOrEqual,
            '~' => self::Proportional,
            '¬' => self::Included,
            'in' => self::Included
            );

    $sString = strtolower( trim( $sString ) );
    if( !array_key_exists( $sString, $aiOperators ) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Invalid/unparsable operator; String: '.$sString );
    return $aiOperators[ $sString ];
  }

}
