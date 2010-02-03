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
 * @package PHP_APE_Auth
 * @subpackage Handler
 */

/** User name and password-based authentication handler
 *
 * @package PHP_APE_Auth
 * @subpackage Handler
 */
abstract class PHP_APE_Auth_AuthenticationHandler_UserPass
extends PHP_APE_Auth_AuthenticationHandler
implements PHP_APE_HTML_hasSmarty
{

  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new authenticator
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   *
   */
  public function __construct()
  {
    // Resources
    $asResources = PHP_APE_WorkSpace::useEnvironment()->loadProperties( 'PHP_APE_Auth_Authentication' );

    // Parent constructor
    parent::__construct();

    // Set arguments
    $this->setArgumentSet( new PHP_APE_Data_ArgumentSet() );
    $roArgumentSet =& $this->useArgumentSet();

    // ... user name
    $roArgumentSet->setElement( new PHP_APE_Data_Argument( 'username',
                                                           new PHP_APE_Type_String( null, 0, 100 ),
                                                           PHP_APE_Data_Argument::Type_Data |
                                                           PHP_APE_Data_Argument::Feature_RequireInForm,
                                                           $asResources['name.username'],
                                                           $asResources['description.username']
                                                           ) );

    // ... password
    $roArgumentSet->setElement( new PHP_APE_Data_Argument( 'password',
                                                           new PHP_APE_Type_Password( null, 0, 100 ),
                                                           PHP_APE_Data_Argument::Type_Data |
                                                           PHP_APE_Data_Argument::Feature_RequireInForm,
                                                           $asResources['name.password'],
                                                           $asResources['description.password']
                                                           ) );
  }


  /*
   * METHODS: PHP_APE_HTML_hasSmarty - IMPLEMENT
   ********************************************************************************/

  public function hasSmarty()
  {
    return true;
  }

  public function &useSmarty()
  {
    static $oSmarty;
    if( !is_null( $oSmarty ) ) return $oSmarty;
    $oSmarty = new PHP_APE_HTML_Smarty( 'AuthenticationHandler_UserPass', 'smarty.tpl', dirname( __FILE__ ) );
    return $oSmarty;
  }

}
