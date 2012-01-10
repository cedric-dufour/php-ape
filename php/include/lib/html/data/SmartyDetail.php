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

/** Smartyfied detail view display object
 *
 * @package PHP_APE_HTML
 * @subpackage Page
 */
class PHP_APE_HTML_Data_SmartyDetail
extends PHP_APE_HTML_Data_FeaturedDetail
{


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new detail view instance
   *
   * @param PHP_APE_HTML_Controller $roController Associated controller
   * @param PHP_APE_Data_isDetailAbleResultSet $roResultSet Data result set
   * @param mixed $mPrimaryKey Data primary key
   * @param integer $iQueryMeta Query meta code (see {@link PHP_APE_Data_isQueryAbleResultSet} constants)
   * @param array|mixed $this->amPassthruVariables Hidden variables to include in <SAMP>FORM</SAMP> (associating: <I>name</I> => <I>value</I>)
   */
  public function __construct( PHP_APE_HTML_Controller $roController, PHP_APE_Data_isDetailAbleResultSet $roResultSet, $mPrimaryKey, $iQueryMeta = PHP_APE_Data_isQueryAbleResultSet::Query_Full, $amPassthruVariables = null )
  {
    // Check

    // ... Smarty
    if( !( $roResultSet instanceof PHP_APE_HTML_hasSmarty ) or !$roResultSet->hasSmarty() )
      throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Result set has no attached Smarty template; Class: '.get_class( $roResultSet ) );

    // Construct view
    parent::__construct( $roController, $roResultSet, $mPrimaryKey, $iQueryMeta, $amPassthruVariables );
    $this->bAllowReorder = false;
  }


  /*
   * METHODS: protected
   ********************************************************************************/

  /** Returns the data detail view's (Smartyfied) content
   *
   * @return string
   */
  protected function _htmlData()
  {
    // Environment
    $bGlobalHideEmpty = PHP_APE_HTML_Data_View::$roEnvironment->getUserParameter( 'php_ape.html.data.hide.empty' );

    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_SmartyDetail::htmlData - DATA (".$this->sRID.") -->\r\n";

    // Smarty
    $roSmarty =& $this->roResultSet->useSmarty();
    $roSmarty->assign( 'Global', array( 'RootURL' => PHP_APE_HTML_Data_View::$roEnvironment->getVolatileParameter( 'php_ape.html.htdocs.url' ),
                                        'RID' => $this->sRID,
                                        'Form' => 'PHP_APE_DR_'.$this->sRID,
                                        'UsePopup' => (integer)$this->bUsePopup,
                                        'HideEmpty' => (integer)$bGlobalHideEmpty,
                                        'RowLast' => 0 ) );

    // Permissions
    $bAuthorizedList = false;
    if( ( $this->roResultSet instanceof PHP_APE_Data_isListAble ) and !( $this->iQueryMeta & PHP_APE_Data_isQueryAbleResultSet::Disable_ListAble ) )
      $bAuthorizedList = $this->roResultSet->isListAuthorized();
    $bAuthorizedInsert = false;
    if( ( $this->roResultSet instanceof PHP_APE_Data_isInsertAble ) and !( $this->iQueryMeta & PHP_APE_Data_isQueryAbleResultSet::Disable_InsertAble ) )
      $bAuthorizedInsert = $this->roResultSet->isInsertAuthorized();
    $bAuthorizedUpdate = false;
    if( ( $this->roResultSet instanceof PHP_APE_Data_isUpdateAble ) and !( $this->iQueryMeta & PHP_APE_Data_isQueryAbleResultSet::Disable_UpdateAble ) )
      $bAuthorizedUpdate = $this->roResultSet->isUpdateAuthorized();
    $bAuthorizedDelete = false;
    if( ( $this->roResultSet instanceof PHP_APE_Data_isDeleteAble ) and !( $this->iQueryMeta & PHP_APE_Data_isQueryAbleResultSet::Disable_DeleteAble ) )
      $bAuthorizedDelete = $this->roResultSet->isDeleteAuthorized();

    // Smarty
    $roSmarty->assign( 'Entry', array( 'Row' => 0,
                                       'PrimaryKey' => $this->mPrimaryKey,
                                       'AuthorizedList' => (integer)$bAuthorizedList,
                                       'AuthorizedDetail' => 0,
                                       'AuthorizedInsert' => (integer)$bAuthorizedInsert,
                                       'AuthorizedUpdate' => (integer)$bAuthorizedUpdate,
                                       'AuthorizedDelete' => (integer)$bAuthorizedDelete ) );
    $roSmarty->assignElements( $this->roResultSet, $this->amDisplayedKeys, $this->asErrors );

    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_SmartyDetail::htmlData - BEGIN (".$this->sRID.") -->\r\n";
    $sOutput .= '<TR><TD CLASS="smarty" COLSPAN="100">'."\r\n";
    $sOutput .= $roSmarty->fetch();
    $sOutput .= "\r\n".'</TD></TR>'."\r\n";
    if( PHP_APE_DEBUG ) $sOutput .= "<!-- TABLE: PHP_APE_HTML_Data_SmartyDetail::htmlData - END (".$this->sRID.") -->\r\n";

    // End
    return $sOutput;
  }

}