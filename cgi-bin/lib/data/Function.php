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

/** Function class
 *
 * @package PHP_APE_Data
 * @subpackage Classes
 */
abstract class PHP_APE_Data_Function
extends PHP_APE_Data_Variable
implements PHP_APE_Data_isArgumentSetAble
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Function arguments
   * @var PHP_APE_Data_ArgumentSet */
  private $oArgumentSet;

  /** Execution errors
   * @var array|string */
  protected $asErrors;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new function
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   *
   * @param mixed $mID Function identifier (ID)
   * @param PHP_APE_Type_Any $oResult Function's result associated object
   * @param string $sName Function name (defaults to identifier if <SAMP>empty</SAMP>)
   * @param string $sDescription Function description
   */
  public function __construct( $mID, PHP_APE_Type_Any $oResult, $sName = null, $sDescription = null )
  {
    parent::__construct( $mID, $oResult, $sName, $sDescription );
  }


  /*
   * METHODS: initialization
   ********************************************************************************/

  /** Resets the function
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   */
  public function reset()
  {
    $this->resetContent();
  }

  /** Reset (clears) this function's result content
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function resetContent()
  {
    $this->useContent()->resetValue();
  }


  /*
   * METHODS: PHP_APE_Data_hasArgumentSet - IMPLEMENT
   ********************************************************************************/

  /** Returns whether this function's arguments have been defined
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return boolean
   */
  final public function hasArgumentSet()
  {
    return !is_null( $this->oArgumentSet );
  }

  /** Returns this function's arguments
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return PHP_APE_Data_ArgumentSet
   */
  final public function getArgumentSet()
  {
    return clone( $this->oArgumentSet );
  }


  /*
   * METHODS: PHP_APE_Data_isArgumentSetAble - IMPLEMENT
   ********************************************************************************/

  /** Returns this function's arguments (<B>as reference</B>)
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return PHP_APE_Data_ArgumentSet
   */
  final public function &useArgumentSet()
  {
    return $this->oArgumentSet;
  }

  /** Sets this function's arguments
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @param PHP_APE_Data_ArgumentSet $oArgumentSet Function's arguments
   */
  public function setArgumentSet( PHP_APE_Data_ArgumentSet $oArgumentSet )
  {
    $this->oArgumentSet = clone( $oArgumentSet );
  }

  /** Unsets (clears) this function's arguments
   *
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   */
  final public function unsetArgumentSet()
  {
    $this->oArgumentSet = null;
  }


  /*
   * METHODS: execute
   ********************************************************************************/

  /** Executes this function
   *
   * <P><B>USAGE:</B> Once this method called, use the {@link PHP_APE_Data_Variable} class methods to further manipulate this function's result.</P> 
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   * <P><B>INHERITANCE:</B> This method <B>MUST be overridden</B>.</P>
   *
   * @return PHP_APE_Type_Any
   */
  abstract public function execute();

  /** Returns the execution errors array (associating: <I>(argument)id</I> => <I>(error)message</I>)
   *
   * <P><B>NOTE:</B> The <SAMP>__GLOBAL</SAMP> argument's identifier should be used to return any global (not argument-related) execution error.</P> 
   * <P><B>INHERITANCE:</B> This method is <B>FINAL</B>.</P>
   *
   * @return array|string
   */
  final public function getErrors()
  {
    return is_array( $this->asErrors ) ? $this->asErrors : array();
  }

}
