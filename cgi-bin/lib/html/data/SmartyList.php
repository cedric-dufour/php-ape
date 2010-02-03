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

/** Smartyfied list view display object
 *
 * @package PHP_APE_HTML
 * @subpackage Page
 */
class PHP_APE_HTML_Data_SmartyList
extends PHP_APE_HTML_Data_FeaturedList
{


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new list view instance
   *
   * @param PHP_APE_HTML_Controller $roController Associated controller
   * @param PHP_APE_Data_isListAbleResultSet $roResultSet Data result set
   * @param integer $iQueryMeta Query meta code (see {@link PHP_APE_Data_isQueryAbleResultSet} constants)
   * @param array|mixed $this->amPassthruVariables Hidden variables to include in <SAMP>FORM</SAMP> (associating: <I>name</I> => <I>value</I>)
   */
  public function __construct( PHP_APE_HTML_Controller $roController, PHP_APE_Data_isListAbleResultSet $roResultSet, $iQueryMeta = PHP_APE_Data_isQueryAbleResultSet::Query_Full, $amPassthruVariables = null )
  {
    // Check

    // ... Smarty
    if( !( $roResultSet instanceof PHP_APE_HTML_hasSmarty ) or !$roResultSet->hasSmarty() )
      throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Result set has no attached Smarty template; Class: '.get_class( $roResultSet ) );

    // Construct view
    parent::__construct( $roController, $roResultSet, $iQueryMeta, $amPassthruVariables );
    $this->bAllowReorder = false;
  }


  /*
   * METHODS: protected
   ********************************************************************************/

  /** Returns the data list view's (Smartyfied) content
   *
   * @param boolean $rbAuthorizedDetail Global detail data retrieval authorization flag (<B>returned through reference</B>)
   * @param boolean $rbAuthorizedUpdate Global data update authorization flag (<B>returned through reference</B>)
   * @param boolean $rbAuthorizedDelete Global data delete authorization flag (<B>returned through reference</B>)
   * @param boolean $rbAuthorizedInsert Global data insert authorization flag (<B>returned through reference</B>)
   * @return string
   */
  protected function _htmlData( &$rbAuthorizedDetail = false, &$rbAuthorizedUpdate = false, &$rbAuthorizedDelete = false, &$rbAuthorizedInsert = false )
  {
    // Environment
    $bGlobalHideEmpty = PHP_APE_HTML_Data_View::$roEnvironment->getUserParameter( 'php_ape.html.data.hide.empty' );

    // Primary key
    $mPKey = null;
    if( $this->roResultSet instanceof PHP_APE_Data_isMetaDataSet )
    {
      $mPKey = $this->roResultSet->getElementsKeysPerMeta( PHP_APE_Data_Field::Type_PrimaryKey );
      if( is_array( $mPKey ) and count( $mPKey ) == 1 ) $mPKey = $mPKey[0];
      else $mPKey = null;
    }

    // Permissions
    $bAuthorizeDetail = false;
    if( ( $this->roResultSet instanceof PHP_APE_Data_isDetailAble ) and !( $this->iQueryMeta & PHP_APE_Data_isQueryAbleResultSet::Disable_DetailAble ) )
      $bAuthorizeDetail = true;
    $bAuthorizeInsert = false;
    if( $this->bRequireSelectForInsert and
        ( $this->roResultSet instanceof PHP_APE_Data_isInsertAble ) and !( $this->iQueryMeta & PHP_APE_Data_isQueryAbleResultSet::Disable_InsertAble ) )
      $bAuthorizeInsert = true;
    $bAuthorizeUpdate = false;
    if( ( $this->roResultSet instanceof PHP_APE_Data_isUpdateAble ) and !( $this->iQueryMeta & PHP_APE_Data_isQueryAbleResultSet::Disable_UpdateAble ) )
      $bAuthorizeUpdate = true;
    $bAuthorizeDelete = false;
    if( ( $this->roResultSet instanceof PHP_APE_Data_isDeleteAble ) and !( $this->iQueryMeta & PHP_APE_Data_isQueryAbleResultSet::Disable_DeleteAble ) )
      $bAuthorizeDelete = true;

    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_SmartyList::htmlData - DATA (".$this->sRID.") -->\r\n";

    // Smarty
    $roSmarty =& $this->roResultSet->useSmarty();
    $roSmarty->assign( 'Global', array( 'RootURL' => PHP_APE_HTML_Data_View::$roEnvironment->getVolatileParameter( 'php_ape.html.htdocs.url' ),
                                        'RID' => $this->sRID,
                                        'Form' => 'PHP_APE_DR_'.$this->sRID,
                                        'RequireSelectForInsert' => (integer)$this->bRequireSelectForInsert,
                                        'UsePopup' => (integer)$this->bUsePopup,
                                        'HideEmpty' => (integer)$bGlobalHideEmpty,
                                        'RowLast' => $this->roResultSet->countEntries()-1 ) );

    // Data
    if( ( $this->roResultSet instanceof PHP_APE_Data_isExtendedResultSet ) and $this->roResultSet->countEntries() < 1 )
    { // NO (empty) data
      $sOutput .= '<P CLASS="nodata" TITLE="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( PHP_APE_HTML_Data_View::$asResources['tooltip.nodata'] ).'" STYLE="CURSOR:help;">'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( PHP_APE_HTML_Data_View::$asResources['label.nodata'] ).'</P>'."\r\n";
    } 
    else
    {
      // ... Begin
      if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_SmartyList::htmlData - BEGIN (".$this->sRID.") -->\r\n";
      $sOutput .= '<TR><TD CLASS="smarty" COLSPAN="100">'."\r\n";

      // ... Content
      $iRow = 0;
      while( $this->roResultSet->nextEntry() )
      {
        // ... primary key
        $mPKValue = null;
        if( !is_null( $mPKey ) ) $mPKValue = $this->roResultSet->useElement( $mPKey )->useContent()->getValue();

        // ... permissions (detail)
        $bAuthorizedDetail = false;
        if( !is_null( $mPKValue ) and $bAuthorizeDetail )
        {
          $bAuthorizedDetail = $this->roResultSet->isDetailAuthorized();
          $rbAuthorizedDetail = $rbAuthorizedDetail || $bAuthorizedDetail;
        }

        // ... permissions (insert)
        $bAuthorizedInsert = false;
        if( !is_null( $mPKValue ) and $bAuthorizeInsert )
        {
          $bAuthorizedInsert = $this->roResultSet->isInsertAuthorized();
          $rbAuthorizedInsert = $rbAuthorizedInsert || $bAuthorizedInsert;
        }

        // ... permissions (update)
        $bAuthorizedUpdate = false;
        if( !is_null( $mPKValue ) and $bAuthorizeUpdate )
        {
          $bAuthorizedUpdate = $this->roResultSet->isUpdateAuthorized();
          $rbAuthorizedUpdate = $rbAuthorizedUpdate || $bAuthorizedUpdate;
        }

        // ... permissions (delete)
        $bAuthorizedDelete = false;
        if( !is_null( $mPKValue ) and $bAuthorizeDelete )
        {
          $bAuthorizedDelete = $this->roResultSet->isDeleteAuthorized();
          $rbAuthorizedDelete = $rbAuthorizedDelete || $bAuthorizedDelete;
        }

        // ... Smarty
        $roSmarty->assign( 'Entry', array( 'Row' => $iRow++,
                                           'PrimaryKey' => $mPKValue,
                                           'AuthorizedList' => 0,
                                           'AuthorizedDetail' => (integer)$bAuthorizedDetail,
                                           'AuthorizedInsert' => (integer)$bAuthorizedInsert,
                                           'AuthorizedUpdate' => (integer)$bAuthorizedUpdate,
                                           'AuthorizedDelete' => (integer)$bAuthorizedDelete ) );
        $roSmarty->assignElements( $this->roResultSet, $this->amDisplayedKeys, $this->asErrors );

        // ... output
        $sOutput .= $roSmarty->fetch();
      }

      // ... End
      $sOutput .= "\r\n".'</TD></TR>'."\r\n";
      if( PHP_APE_DEBUG ) $sOutput .= "<!-- TABLE: PHP_APE_HTML_Data_SmartyList::htmlData - END (".$this->sRID.") -->\r\n";
    }

    // End
    return $sOutput;
  }

}
