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
 * @package PHP_APE_Type
 * @subpackage Classes
 */

/** Core type
 *
 * @package PHP_APE_Type
 * @subpackage Classes
 */
abstract class PHP_APE_Type_Any
{

  /*
   * CONSTANTS
   ********************************************************************************/

  /** Default data format <I>magic</I> value
   * @var integer */
  const Format_Default = -1;

  /** Passthru data format <I>magic</I> value
   * @var integer */
  const Format_Passthru = -2;


  /*
   * FIELDS
   ********************************************************************************/

  /** Data format (preferred representation)
   * @var integer */
  protected $iFormat;


  /*
   * METHODS: value
   ********************************************************************************/

  /** Sets this object's data value
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * @param mixed $mValue New value
   */
  abstract public function setValue( $mValue );

  /** Returns this object's data value
   *
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * @return mixed
   */
  abstract public function getValue();

  /** Resets this object's data value to its <I>blank</I> status
   *
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   */
  abstract public function resetValue();


  /*
   * METHODS: format
   ********************************************************************************/

  /** Sets the data format
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param integer $iFormat New format
   */
  public function setFormat( $iFormat )
  {
    $this->iFormat = $iFormat;
  }

  /** Returns the data format
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return integer
   */
  public function getFormat()
  {
    return $this->iFormat;
  }


  /*
   * METHODS: parse/format
   ********************************************************************************/

  /** Parses this object's data from their <I>mixed</I> representation and according to their format
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>SHOULD be overridden</B>.</P>
   *
   * @param mixed $mValue Input value
   * @param boolean $bStrict Strict data parsing (<SAMP>null</SAMP> remains <SAMP>null</SAMP>)
   * @param integer $iFormat Data format (retrieved from the data object itself if <SAMP>null</SAMP>)
   */
  public function setValueParsed( $mValue, $bStrict = true, $iFormat = null )
  {
    $this->setValue( $mValue );
  }

  /** Formats this object's data into their <I>string</I> representation and according to their format
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>SHOULD be overridden</B>.</P>
   *
   * @param string $sIfNull Output for <SAMP>null</SAMP> value
   * @param integer $iFormat Data format (retrieved from the data object itself if <SAMP>null</SAMP>)
   * @return string
   */
  public function getValueFormatted( $sIfNull = null, $iFormat = null )
  {
    return (string)$this->mValue;
  }

}
