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
 * @package PHP_APE_HTML
 * @subpackage Page
 */

/** Smartyfied data form view object
 *
 * @package PHP_APE_HTML
 * @subpackage Page
 */
class PHP_APE_HTML_Data_SmartyForm
extends PHP_APE_HTML_Data_FeaturedForm
{


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new form view instance
   *
   * @param PHP_APE_HTML_Controller $roController Associated controller
   * @param PHP_APE_Data_Function $roFunction Data function
   * @param PHP_APE_Data_isDetailAbleResultSet $roResultSet Data result set
   * @param mixed $mPrimaryKey Data primary key
   * @param integer $iQueryMeta Query meta code (see {@link PHP_APE_Data_isQueryAbleResultSet} constants)
   * @param array|mixed $this->amPassthruVariables Hidden variables to include in <SAMP>FORM</SAMP> (associating: <I>name</I> => <I>value</I>)
   */
  public function __construct( PHP_APE_HTML_Controller $roController, PHP_APE_Data_Function $roFunction, PHP_APE_Data_isDetailAbleResultSet $roResultSet = null, $mPrimaryKey = null, $iQueryMeta = PHP_APE_Data_isQueryAbleResultSet::Query_Full, $amPassthruVariables = null )
  {
    // Check

    // ... Smarty
    if( !( $roFunction instanceof PHP_APE_HTML_hasSmarty ) or !$roFunction->hasSmarty() )
      throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Function has no attached Smarty template; Class: '.get_class( $roFunction ) );

    // Construct view
    parent::__construct( $roController, $roFunction, $roResultSet, $mPrimaryKey, $iQueryMeta, $amPassthruVariables );
  }


  /*
   * METHODS: protected
   ********************************************************************************/

  /** Returns the data form view's content
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>, <SAMP>PHP_APE_Auth_AuthorizationException</SAMP>.</P>
   *
   * @return string
   */
  protected function _htmlData()
  {
    // Environment
    $bGlobalHideEmpty = PHP_APE_HTML_Data_View::$roEnvironment->getUserParameter( 'php_ape.html.data.hide.empty' );
    $bGlobalHideOptional = PHP_APE_HTML_Data_View::$roEnvironment->getUserParameter( 'php_ape.html.data.hide.optional' );

    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_SmartyForm::htmlData - DATA (".$this->sRID.") -->\r\n";

    // Smarty
    $roSmarty =& $this->roFunction->useSmarty();
    $roSmarty->assign( 'Global', array( 'RootURL' => PHP_APE_HTML_Data_View::$roEnvironment->getVolatileParameter( 'php_ape.html.htdocs.url' ),
                                        'RID' => $this->sRID,
                                        'Form' => 'PHP_APE_DR_'.$this->sRID,
                                        'IsPopup' => (integer)$this->bIsPopup,
                                        'HideEmpty' => (integer)$bGlobalHideEmpty,
                                        'HideOptional' => (integer)$bGlobalHideOptional ) );

    // Elements
    $oElements = new PHP_APE_Data_AssociativeSet();

    // ... arguments
    $this->bHasError = false;
    $this->bHasRequired = false;
    $rasRequestData =& $this->roController->useRequestData();
    $roArgumentSet =& $this->roFunction->useArgumentSet();
    foreach( $roArgumentSet->getElementsIDs() as $mKey )
    {
      $roArgument =& $roArgumentSet->useElementByID( $mKey );
      $mArgumentID = $roArgument->getID();
      $iArgumentMeta = $roArgument->getMeta();
      if( !( $iArgumentMeta & PHP_APE_Data_Argument::Value_Preset ) and
              ( $this->roResultSet instanceof PHP_APE_Data_isDetailAbleResultSet ) and
              $this->roResultSet->isElement( $mKey ) )
        $roArgument->useContent()->setValue( $this->roResultSet->useElement( $mKey )->useContent()->getValue() );
      $oElements->setElement( $roArgument, $mKey, true );
      if( array_key_exists( $mArgumentID, $this->getErrors() ) ) $this->bHasError = true;
      if( $iArgumentMeta & PHP_APE_Data_Argument::Feature_RequireInForm ) $this->bHasRequired = true;
    }

    // ... fields
    if( $this->roResultSet instanceof PHP_APE_Data_isDetailAbleResultSet )
    {
      foreach( $this->roResultSet->getElementsKeys() as $mKey )
      {
        try { $oElements->setElement( $this->roResultSet->useElement( $mKey ), $mKey, false ); }
        catch( PHP_APE_Data_Exception $e ) {} // ignore exception thrown if result set's field conflicts with function's argument
      }
    }

    // Smarty
    $roSmarty->assign( 'Entry', array( 'PrimaryKey' => $this->mPrimaryKey ) );
    $roSmarty->assignElements( $oElements, $this->amDisplayedKeys, $this->asErrors, $rasRequestData );

    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_SmartyForm::htmlData - BEGIN (".$this->sRID.") -->\r\n";
    $sOutput .= '<TR><TD CLASS="smarty" COLSPAN="100">'."\r\n";
    $sOutput .= $roSmarty->fetch();
    $sOutput .= "\r\n".'</TD></TR>'."\r\n";
    if( PHP_APE_DEBUG ) $sOutput .= "<!-- TABLE: PHP_APE_HTML_Data_SmartyForm::htmlData - END (".$this->sRID.") -->\r\n";

    // End
    return $sOutput;
  }

}
