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

/** Logical operators handling class
 *
 * @package PHP_APE_Data
 * @subpackage Classes
 */
class PHP_APE_Data_LogicalOperator
extends PHP_APE_Data_Operator
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** Operator's code binary mask
   * @var integer */
  const Mask = 3; // 0b........:......11

  /** <SAMP>&</SAMP> (and)
   *
   * <P><B>NOTE:</B> Make sure to note the exact spelling for this keyword, "tweaked" so it does not conflict with PHP parser.</P>
   *
   * @var integer
   */
  const AAnd = 0; // 0b........:......00

  /** <SAMP>|</SAMP> (or)
   *
   * <P><B>NOTE:</B> Make sure to note the exact spelling for this keyword, "tweaked" so it does not conflict with PHP parser.</P>
   *
   * @var integer
   */
  const OOr = 1; // 0b........:......01

  /** <SAMP>!</SAMP> (not)
   *
   * <P><B>NOTE:</B> Make sure to note the exact spelling for this keyword, "tweaked" so it does not conflict with PHP parser.</P>
   *
   * @var integer
   */
  const NNot = 2; // 0b........:......10


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
            self::AAnd => '&',
            self::OOr => '|',
            self::NNot => '!'
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
            '&' => self::AAnd,
            'and' => self::AAnd,
            '|' => self::OOr,
            'or' => self::OOr,
            '!' => self::NNot,
            'not' => self::NNot
            );

    $sString = strtolower( trim( $sString ) );
    if( !array_key_exists( $sString, $aiOperators ) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Invalid/unparsable operator; String: '.$sString );
    return $aiOperators[ $sString ];
  }

}
