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

/** (Basic) List view display object
 *
 * @package PHP_APE_HTML
 * @subpackage Page
 */
class PHP_APE_HTML_Data_BasicList
extends PHP_APE_HTML_Data_View
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Popup used to open view
   * @var boolean */
  protected $bIsPopup;

  /** Displayable fields
   * @var array|mixed */
  protected $amDisplayableKeys;

  /** Displayed fields
   * @var array|mixed */
  protected $amDisplayedKeys;

  /** Use selection checkbox
   * @var boolean */
  protected $bUseSelection;

  /** Require selection for insert
   * @var boolean */
  protected $bRequireSelectForInsert;

  /** Use browsing controls
   * @var boolean */
  protected $bUseControls;

  /** Use header element
   * @var boolean */
  protected $bUseHeader;

  /** Use footer element
   * @var boolean */
  protected $bUseFooter;

  /** Use scroller
   * @var boolean */
  protected $bUseScroller;

  /** Use order
   * @var boolean */
  protected $bUseOrder;

  /** Use filter
   * @var boolean */
  protected $bUseFilter;

  /** Use subset filter
   * @var boolean */
  protected $bUseSubsetFilter;

  /** Use avanced filter
   * @var boolean */
  protected $bUseAdvancedFilter;

  /** Use filter (self-)link
   * @var boolean */
  protected $bUseFilterLink;

  /** Use popup for form/input
   * @var boolean */
  protected $bUsePopup;

  /** Label for 'back' button
   * @var string */
  protected $sLabelBack;

  /** Tooltip for 'back' button
   * @var string */
  protected $sTooltipBack;

  /** Label for 'close' button
   * @var string */
  protected $sLabelClose;

  /** Tooltip for 'close' button
   * @var string */
  protected $sTooltipClose;

  /** Label for 'detail' button
   * @var string */
  protected $sLabelDetail;

  /** Tooltip for 'detail' button
   * @var string */
  protected $sTooltipDetail;

  /** Label for 'new' button
   * @var string */
  protected $sLabelNew;

  /** Tooltip for 'new' button
   * @var string */
  protected $sTooltipNew;

  /** Label for 'edit' button
   * @var string */
  protected $sLabelEdit;

  /** Tooltip for 'edit' button
   * @var string */
  protected $sTooltipEdit;

  /** Label for 'delete' button
   * @var string */
  protected $sLabelDelete;

  /** Tooltip for 'delete' button
   * @var string */
  protected $sTooltipDelete;


  /*
   * CONSTRUCTORS
   ********************************************************************************/

  /** Constructs a new list view instance
   *
   * @param PHP_APE_HTML_Controller $roController Associated controller
   * @param PHP_APE_Data_isListAbleResultSet $roResultSet Data result set
   * @param integer $iQueryMeta Query meta code (see {@link PHP_APE_Data_isQueryAbleResultSet} constants)
   * @param array|mixed $amPassthruVariables Hidden variables to include in <SAMP>FORM</SAMP> (associating: <I>name</I> => <I>value</I>)
   */
  public function __construct( PHP_APE_HTML_Controller $roController, PHP_APE_Data_isListAbleResultSet $roResultSet, $iQueryMeta = PHP_APE_Data_isQueryAbleResultSet::Query_Full, $amPassthruVariables = null )
  {
    // Construct view
    parent::__construct( $roController, $roResultSet->getName(), $roResultSet->getDescription() );
    $this->attachResultSet( $roResultSet, $iQueryMeta );
    $this->setPassthruVariables( $amPassthruVariables );

    // Preferences
    $this->bUseSelection = true;
    $this->bRequireSelectForInsert = false;
    $this->bUseControls = true;
    $this->bUseHeader = true;
    $this->bUseFooter = true;
    $this->bUseScroller = true;
    $this->bUseOrder = true;
    $this->bUseFilter = true;
    $this->bUseSubsetFilter = true;
    $this->bUseAdvancedFilter = true;
    $this->bUseFilterLink = true;
    $this->bUsePopup = PHP_APE_HTML_Data_View::$roEnvironment->getUserParameter( 'php_ape.html.popup.use' );

    // Check preferences
    $this->_prefCheck();

    // Text for buttons (null = localized internal default)
    $this->sLabelBack = null;
    $this->sTooltipBack = null;
    $this->sLabelClose = null;
    $this->sTooltipClose = null;
    $this->sLabelDetail = null;
    $this->sTooltipDetail = null;
    $this->sLabelNew = null;
    $this->sTooltipNew = null;
    $this->sLabelEdit = null;
    $this->sTooltipEdit = null;
    $this->sLabelDelete = null;
    $this->sTooltipDelete = null;
  }


  /*
   * METHODS: preferences
   ********************************************************************************/


  /** Sets this data list view's browsing controls (using request data)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   */
  protected function _prefCheck()
  {

    // Set data scroller
    if( $this->bUseScroller )
    {
      $this->bUseScroller = !( $this->iQueryMeta & PHP_APE_Data_isQueryAbleResultSet::Disable_ScrollerAble ) && ( $this->iQueryMeta & PHP_APE_Data_isQueryAbleResultSet::Query_Scroll ) && ( $this->roResultSet instanceof PHP_APE_Data_isScrollerAble );
      if( $this->bUseScroller )
      {
        if( !is_null( $oScroller = PHP_APE_HTML_Data_Scroller::parseRequest( $this->getID() ) ) )
          $this->roResultSet->setScroller( $oScroller );
      } else
        $this->iQueryMeta |= PHP_APE_Data_isQueryAbleResultSet::Disable_ScrollerAble;
    }

    // Set data order
    if( $this->bUseOrder )
    {
      $this->bUseOrder = !( $this->iQueryMeta & PHP_APE_Data_isQueryAbleResultSet::Disable_OrderAble ) && ( $this->iQueryMeta & PHP_APE_Data_isQueryAbleResultSet::Query_Order ) && ( $this->roResultSet instanceof PHP_APE_Data_isOrderAble );
      if( $this->bUseOrder )
      {
        if( !is_null( $oOrder = PHP_APE_HTML_Data_Order::parseRequest( $this->getID() ) ) )
          $this->roResultSet->setOrder( $oOrder );
      } else
        $this->iQueryMeta |= PHP_APE_Data_isQueryAbleResultSet::Disable_OrderAble;
    }

    // Set (user) data filter
    if( $this->bUseFilter )
    {
      $this->bUseFilter = !( $this->iQueryMeta & PHP_APE_Data_isQueryAbleResultSet::Disable_FilterAble ) && ( $this->iQueryMeta & PHP_APE_Data_isQueryAbleResultSet::Query_Filter ) && ( $this->roResultSet instanceof PHP_APE_Data_isFilterAble );
      if( $this->bUseFilter )
      {
        if( !is_null( $oFilter = PHP_APE_HTML_Data_Filter::parseRequest( $this->getID() ) ) )
          $this->roResultSet->setFilter( $oFilter );
      } else
        $this->iQueryMeta |= PHP_APE_Data_isQueryAbleResultSet::Disable_FilterAble;
    }

    // Set (system) subset data filter
    if( $this->bUseSubsetFilter )
    {
      $this->bUseSubsetFilter = ( $this->iQueryMeta & PHP_APE_Data_isQueryAbleResultSet::Query_Subset ) && ( $this->roResultSet instanceof PHP_APE_Data_isSubsetFilterAble );
      if( $this->bUseSubsetFilter )
      {
        if( !is_null( $oSubsetFilter = PHP_APE_HTML_Data_Filter::parseSubsetRequest( $this->getID() ) ) )
          $this->roResultSet->setSubsetFilter( $oSubsetFilter );
      }
    }

  }
  
  /** Set preference for selection checkbox
   * @param boolean $bUseSelection Use (show) selection
   * @param boolean $bRequireSelectForInsert Require selection to perform insertion */
  public function prefUseSelection( $bUseSelection, $bRequireSelectForInsert = false )
  {
    $this->bUseSelection = (boolean)$bUseSelection;
    $this->bRequireSelectForInsert = (boolean)$bRequireSelectForInsert;
  }
  
  /** Set preference for browsing controls
   * @param boolean $bUseControls Use (show) browsing controls */
  public function prefUseControls( $bUseControls )
  {
    $this->bUseControls = (boolean)$bUseControls;
  }
  
  /** Set preference for header element
   * @param boolean $bUseHeader Use (show) header element */
  public function prefUseHeader( $bUseHeader )
  {
    $this->bUseHeader = (boolean)$bUseHeader;
  }
  
  /** Set preference for footer element
   * @param boolean $bUseFooter Use (show) footer element */
  public function prefUseFooter( $bUseFooter )
  {
    $this->bUseFooter = (boolean)$bUseFooter;
  }
  
  /** Set preference for scroller usage
   * @param boolean $bUseScroller Use (show) scroller toolbar */
  public function prefUseScroller( $bUseScroller )
  {
    $this->bUseScroller = (boolean)$bUseScroller;
    $this->_prefCheck();
  }
  
  /** Set preference for order usage
   * @param boolean $bUseOrder Use (show) order toolbar */
  public function prefUseOrder( $bUseOrder )
  {
    $this->bUseOrder = (boolean)$bUseOrder;
    $this->_prefCheck();
  }
  
  /** Set preference for search/filter usage
   * @param boolean $bUseFilter Use (show) search/filter toolbar
   * @param boolean $bUseAdvancedFilter Use (show) advanced (field-based) filter (requires the header)
   * @param boolean $bUseFilterLink Use (show) an hyperlink to bookmark the applied filter */
  public function prefUseFilter( $bUseFilter, $bUseAdvancedFilter = true, $bUseFilterLink = true )
  {
    $this->bUseFilter = (boolean)$bUseFilter;
    $this->bUseAdvancedFilter = (boolean)$bUseAdvancedFilter;
    $this->bUseFilterLink = (boolean)$bUseFilterLink;
    $this->_prefCheck();
  }
  
  /** Set preference for popup usage
   * @param boolean $bUsePopup Use popup for form/input */
  public function prefUsePopup( $bUsePopup )
  {
    $this->bUsePopup = (boolean)$bUsePopup;
  }
  
  /** Set text for 'back' button
   * @param string $sLabel Button label
   * @param string $sTooltip Button tooltip */
  public function prefButtonBack( $sLabel, $sTooltip )
  {
    $this->sLabelBack = PHP_APE_Type_String::parseValue( $sLabel );
    $this->sTooltipBack = PHP_APE_Type_String::parseValue( $sTooltip );
  }
  
  /** Set text for 'close' button
   * @param string $sLabel Button label
   * @param string $sTooltip Button tooltip */
  public function prefButtonClose( $sLabel, $sTooltip )
  {
    $this->sLabelClose = PHP_APE_Type_String::parseValue( $sLabel );
    $this->sTooltipClose = PHP_APE_Type_String::parseValue( $sTooltip );
  }
  
  /** Set text for 'detail' button
   * @param string $sLabel Button label
   * @param string $sTooltip Button tooltip */
  public function prefButtonDetail( $sLabel, $sTooltip )
  {
    $this->sLabelDetail = PHP_APE_Type_String::parseValue( $sLabel );
    $this->sTooltipDetail = PHP_APE_Type_String::parseValue( $sTooltip );
  }
  
  /** Set text for 'new' button
   * @param string $sLabel Button label
   * @param string $sTooltip Button tooltip */
  public function prefButtonNew( $sLabel, $sTooltip )
  {
    $this->sLabelNew = PHP_APE_Type_String::parseValue( $sLabel );
    $this->sTooltipNew = PHP_APE_Type_String::parseValue( $sTooltip );
  }
  
  /** Set text for 'edit' button
   * @param string $sLabel Button label
   * @param string $sTooltip Button tooltip */
  public function prefButtonEdit( $sLabel, $sTooltip )
  {
    $this->sLabelEdit = PHP_APE_Type_String::parseValue( $sLabel );
    $this->sTooltipEdit = PHP_APE_Type_String::parseValue( $sTooltip );
  }
  
  /** Set text for 'delete' button
   * @param string $sLabel Button label
   * @param string $sTooltip Button tooltip */
  public function prefButtonDelete( $sLabel, $sTooltip )
  {
    $this->sLabelDelete = PHP_APE_Type_String::parseValue( $sLabel );
    $this->sTooltipDelete = PHP_APE_Type_String::parseValue( $sTooltip );
  }


  /*
   * METHODS: protected
   ********************************************************************************/

  /** Returns the data list view's <SAMP>JavaScript</SAMP>
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   *
   * @return string
   */
  protected function _htmlJavaScript()
  {
    static $bOutputOnce;

    // Output
    if( $bOutputOnce === true ) return null;
    $bOutputOnce = true;
    $sOutput = null;

    // Environment
    $sApplicationID = PHP_APE_HTML_Data_View::$roEnvironment->getVolatileParameter( 'php_ape.application.id' );
    $iPopUpWidth = PHP_APE_HTML_Data_View::$roEnvironment->getUserParameter( 'php_ape.html.popup.width' );
    $iPopUpHeight = PHP_APE_HTML_Data_View::$roEnvironment->getUserParameter( 'php_ape.html.popup.height' );
    
    // JavaScript
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FORM: PHP_APE_HTML_Data_BasicList::htmlJavaScript - BEGIN -->\r\n";
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    $sOutput .= '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript"><!--'."\r\n";
    $sOutput .= "function PHP_APE_DR_BasicList_setPK(rid,__PK)\r\n";
    $sOutput .= "{\r\n";
    $sOutput .= " form = document.forms['PHP_APE_DR_'+rid];\r\n";
    $sOutput .= " for( i=0; i<form.elements.length; i++ )\r\n";
    $sOutput .= " {\r\n";
    $sOutput .= "  elmt=form.elements[i];\r\n";
    $sOutput .= "  if( elmt.name=='__PK' )\r\n";
    $sOutput .= "  {\r\n";
    $sOutput .= "   type=elmt.type.toLowerCase();\r\n";
    $sOutput .= "   if( type=='checkbox' ) elmt.checked = (elmt.value==__PK);\r\n";
    $sOutput .= "   else elmt.disabled = (elmt.value!=__PK);\r\n";
    $sOutput .= "  }\r\n";
    $sOutput .= " }\r\n";
    $sOutput .= "}\r\n";
    $sOutput .= "function PHP_APE_DR_BasicList_allPK(rid,checked)\r\n";
    $sOutput .= "{\r\n";
    $sOutput .= " form = document.forms['PHP_APE_DR_'+rid];\r\n";
    $sOutput .= " for( i=0; i<form.elements.length; i++ )\r\n";
    $sOutput .= " {\r\n";
    $sOutput .= "  elmt=form.elements[i];\r\n";
    $sOutput .= "  if( elmt.name=='__PK' )\r\n";
    $sOutput .= "  {\r\n";
    $sOutput .= "   type=elmt.type.toLowerCase();\r\n";
    $sOutput .= "   if( type=='checkbox' ) elmt.checked = checked;\r\n";
    $sOutput .= "   else elmt.disabled = !checked;\r\n";
    $sOutput .= "  }\r\n";
    $sOutput .= " }\r\n";
    $sOutput .= "}\r\n";
    $sOutput .= "function PHP_APE_DR_BasicList_togglePK(rid)\r\n";
    $sOutput .= "{\r\n";
    $sOutput .= " form = document.forms['PHP_APE_DR_'+rid];\r\n";
    $sOutput .= " for( i=0; i<form.elements.length; i++ )\r\n";
    $sOutput .= " {\r\n";
    $sOutput .= "  elmt=form.elements[i];\r\n";
    $sOutput .= "  if( elmt.name=='__PK' )\r\n";
    $sOutput .= "  {\r\n";
    $sOutput .= "   type=elmt.type.toLowerCase();\r\n";
    $sOutput .= "   if( type=='checkbox' ) elmt.checked = !elmt.checked;\r\n";
    $sOutput .= "   else elmt.disabled = !elmt.disabled;\r\n";
    $sOutput .= "  }\r\n";
    $sOutput .= " }\r\n";
    $sOutput .= "}\r\n";
    $sOutput .= "function PHP_APE_DR_BasicList_countPK(rid)\r\n";
    $sOutput .= "{\r\n";
    $sOutput .= " form = document.forms['PHP_APE_DR_'+rid];\r\n";
    $sOutput .= " count = 0;\r\n";
    $sOutput .= " for( i=0; i<form.elements.length; i++ )\r\n";
    $sOutput .= " {\r\n";
    $sOutput .= "  elmt=form.elements[i];\r\n";
    $sOutput .= "  if( elmt.name=='__PK' )\r\n";
    $sOutput .= "  {\r\n";
    $sOutput .= "   type=elmt.type.toLowerCase();\r\n";
    $sOutput .= "   if( type=='checkbox' )\r\n";
    $sOutput .= "   {\r\n";
    $sOutput .= "    if( !elmt.disabled && elmt.checked ) ++count;\r\n";
    $sOutput .= "   }\r\n";
    $sOutput .= "   else if( !elmt.disabled ) ++count;\r\n";
    $sOutput .= "  }\r\n";
    $sOutput .= " }\r\n";
    $sOutput .= " return count;\r\n";
    $sOutput .= "}\r\n";
    $sOutput .= "function PHP_APE_DR_BasicList_check(rid,forceCount,forceDelete,requireSelectForInsert)\r\n";
    $sOutput .= "{\r\n";
    $sOutput .= " form = document.forms['PHP_APE_DR_'+rid];\r\n";
    $sOutput .= " fvar = form.elements['PHP_APE_DR_'+rid];\r\n";
    $sOutput .= " if( ( forceCount || ( !form.__TO.disabled && ( String('detail,edit,delete').indexOf(form.__TO.value)>=0 ) || ( requireSelectForInsert && String('insert').indexOf(form.__TO.value)>=0 ) ) ) && PHP_APE_DR_BasicList_countPK(rid)<=0 )\r\n";
    $sOutput .= " {\r\n";
    $sOutput .= "  window.alert('".PHP_APE_HTML_Data_View::$oDataSpace_JavaScript->encodeData( PHP_APE_HTML_Data_View::$asResources['message.selection.missing'] )."');\r\n";
    $sOutput .= "  return false;\r\n";
    $sOutput .= " }\r\n";
    $sOutput .= " if( forceDelete || ( !form.__TO.disabled && String('delete').indexOf(form.__TO.value)>=0 ) )\r\n";
    $sOutput .= " {\r\n";
    $sOutput .= "  if( !window.confirm('".PHP_APE_HTML_Data_View::$oDataSpace_JavaScript->encodeData( PHP_APE_HTML_Data_View::$asResources['message.delete.confirm'] )."') ) return false;\r\n";
    $sOutput .= " }\r\n";
    $sOutput .= " return true;\r\n";
    $sOutput .= "}\r\n";
    $sOutput .= "function PHP_APE_DR_BasicList_submit(rid,lock,popup,requireSelectForInsert)\r\n";
    $sOutput .= "{\r\n";
    $sOutput .= " if( lock==null ) lock=true;\r\n";
    $sOutput .= " form = document.forms['PHP_APE_DR_'+rid];\r\n";
    $sOutput .= " fvar = form.elements['PHP_APE_DR_'+rid];\r\n";
    $sOutput .= " if( !lock || fvar.disabled )\r\n";
    $sOutput .= " {\r\n";
    $sOutput .= "  if( PHP_APE_DR_BasicList_check(rid,false,false,requireSelectForInsert) && !form.__TO.disabled )\r\n";
    $sOutput .= "  {\r\n";
    $sOutput .= "   if( popup )\r\n";
    $sOutput .= "   {\r\n";
    $sOutput .= "    form.__POPUP.disabled = false;\r\n";
    $sOutput .= "    popup = window.open(PHP_APE_URL_addQuery(fvar.name+'='+PHP_APE_IN_Form_args(form,fvar,false),form.action),'".$sApplicationID."_popup','width=".$iPopUpWidth.",height=".$iPopUpHeight.",menubar=no,toolbar=no,location=no,scrollbars=yes',true);\r\n";
    $sOutput .= "    form.__POPUP.value = true;\r\n";
    $sOutput .= "   }\r\n";
    $sOutput .= "   else\r\n";
    $sOutput .= "    PHP_APE_IN_Form_get(form,fvar);\r\n";
    $sOutput .= "  }\r\n";
    $sOutput .= " }\r\n";
    $sOutput .= "}\r\n";
    $sOutput .= "function PHP_APE_DR_BasicList_do(rid,__TO,__PK,lock,popup,requireSelectForInsert)\r\n";
    $sOutput .= "{\r\n";
    $sOutput .= " form = document.forms['PHP_APE_DR_'+rid];\r\n";
    $sOutput .= " if( __TO==null ) return;\r\n";
    $sOutput .= " form.__TO.disabled = false;\r\n";
    $sOutput .= " form.__TO.value = __TO;\r\n";
    $sOutput .= " if( __PK!=null ) PHP_APE_DR_BasicList_setPK(rid,__PK);\r\n";
    $sOutput .= " if( lock==null ) lock=true;\r\n";
    $sOutput .= " PHP_APE_DR_BasicList_submit(rid,lock,popup,requireSelectForInsert);\r\n";
    $sOutput .= "}\r\n";
    $sOutput .= "--></SCRIPT>\r\n";
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FORM: PHP_APE_HTML_Data_BasicList::htmlJavaScript - END -->\r\n";
    return $sOutput;
  }

  /** Opens the data list view
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   *
   * @return string
   */
  protected function _htmlOpen()
  {
    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_BasicList::htmlOpen - BEGIN (".$this->sRID.") -->\r\n";
    $sOutput .= '<TABLE CLASS="APE-list" CELLSPACING="0">'."\r\n";
    return $sOutput;
  }

  /** Returns the data list view's header
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   *
   * @return string
   */
  protected function _htmlHeader()
  {
    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_BasicList::htmlHeader - HEADER (".$this->sRID.") -->\r\n";
    $sOutput .= '<TR CLASS="h">';
    $sOutput .= '<TH CLASS="c">';
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen();
    if( !$this->bIsPopup )
    {
      $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( is_null( $this->sLabelBack ) ? PHP_APE_HTML_Data_View::$asResources['label.back'] : $this->sLabelBack, 'S-back', "javascript:window.history.back();", is_null( $this->sTooltipBack ) ? PHP_APE_HTML_Data_View::$asResources['tooltip.back'] : $this->sTooltipBack, null, false, false );
    }
    else
    {
      $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( is_null( $this->sLabelClose ) ? PHP_APE_HTML_Data_View::$asResources['label.close'] : $this->sLabelClose, 'S-close', "javascript:window.close();", is_null( $this->sTooltipClose ) ? PHP_APE_HTML_Data_View::$asResources['tooltip.close'] : $this->sTooltipClose, null, false, false );
    }
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();
    $sOutput .= '</TH>';
    foreach( $this->amDisplayedKeys as $mKey )
    {
      $roElement = $this->useResultSet()->useElement( $mKey );
      $sOutput .= '<TH CLASS="l">';
      $sOutput .= '<TABLE CELLSPACING="0">';
      $sOutput .= '<TD CLASS="l"';
      if( ( $roElement instanceof PHP_APE_Data_hasDescription ) and $roElement->hasDescription() )
        $sOutput .= ' TITLE="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $roElement->getDescription() ).'" STYLE="CURSOR:help;"';
      $sOutput .= '>';
      if( ( $roElement instanceof PHP_APE_Data_hasName ) and $roElement->hasName() ) 
        $sOutput .= PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $roElement->getName() );
      else
        $sOutput .= PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $mKey );
      $sOutput .= '</TD>';
      if( ( $this->roResultSet instanceof PHP_APE_Data_isOrderAble ) and
          !( $this->iQueryMeta & PHP_APE_Data_isQueryAbleResultSet::Disable_OrderAble ) and
          ( !( $roElement instanceof PHP_APE_Data_hasMeta ) or ( $roElement->getMeta() & PHP_APE_Data_Field::Feature_OrderAble ) ) )
      {
        $sOutput .= '<TD CLASS="o"><A HREF="javascript:;" ONCLICK="javascript:PHP_APE_DO_go(\''.$this->sRID.'\',\''.PHP_APE_HTML_Data_View::$oDataSpace_JavaScript->encodeData( $mKey ).'\',+1);return false;"><DIV CLASS="a"></DIV></A></TD>';
        $sOutput .= '<TD CLASS="o"><A HREF="javascript:;" ONCLICK="javascript:PHP_APE_DO_go(\''.$this->sRID.'\',\''.PHP_APE_HTML_Data_View::$oDataSpace_JavaScript->encodeData( $mKey ).'\',-1);return false;"><DIV CLASS="d"></DIV></A></TD>';
      }
      else
        $sOutput .= '<TD CLASS="o" COLSPAN="2">&nbsp;</TD>';
      $sOutput .= '</TABLE>';
      $sOutput .= '</TH>';
    }
    $sOutput .= '</TR>'."\r\n";
    return $sOutput;
  }

  /** Returns the data list view's filter
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   *
   * @return string
   */
  protected function _htmlFilter()
  {

    // Check result set
    if( !( $this->roResultSet instanceof PHP_APE_Data_isFilterAble ) )
      return null;


    // Environment
    $bFilterOr = PHP_APE_HTML_Data_View::$roEnvironment->getUserParameter( 'php_ape.data.filter.or' );
    $asResources = PHP_APE_HTML_Data_View::$roEnvironment->loadProperties( 'PHP_APE_HTML_Data_Filter' );

    // Filter
    $oFilter = $this->roResultSet->hasFilter() ? $this->roResultSet->getFilter() : new PHP_APE_Data_Filter();
    $sGlobalCriteria = null;
    if( $oFilter->isElement( '__GLOBAL' ) )
      $sGlobalCriteria = $oFilter->useElement( '__GLOBAL' )->toString();

    // Output
    $mID = $this->getID();
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_BasicList::htmlFilter - FILTER (".$this->sRID.") -->\r\n";
    $sOutput .= '<TR CLASS="f">'."\r\n";

    // ... logical operator
    $sOutput .= '<TD CLASS="c">';
    $sOutput .= '<SELECT ONCHANGE="javascript:document.location.replace(this.value);"><OPTION VALUE="'.PHP_APE_HTML_Data_View::$roEnvironment->makePreferencesURL( array( 'php_ape.data.filter.or' => 0 ), $_SERVER['REQUEST_URI'] ).'"'.( !$bFilterOr ? ' SELECTED' : null ).'>'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $asResources['label.and'] ).'</OPTION><OPTION VALUE="'.PHP_APE_HTML_Data_View::$roEnvironment->makePreferencesURL( array( 'php_ape.data.filter.or' => 1 ), $_SERVER['REQUEST_URI'] ).'"'.( $bFilterOr ? ' SELECTED' : null ).'>'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $asResources['label.or'] ).'</OPTION></SELECT>';
    $sOutput .= '</TD>'."\r\n";

    // ... elements
    $sOutput .= PHP_APE_HTML_Data_Filter::htmlFormPrefix( $mID, $sGlobalCriteria );
    foreach( $this->amDisplayedKeys as $mKey )
    {
      // ... result set element
      $roElement = $this->roResultSet->useElement( $mKey );

      if( !( $roElement instanceof PHP_APE_Data_hasMeta ) or ( $roElement->getMeta() & PHP_APE_Data_Field::Feature_FilterAble ) )
      {
        // ... filter criteria
        if( $oFilter->isElement( $mKey ) )
          $sCriteria = $oFilter->useElement( $mKey )->toString();
        else 
          $sCriteria = null;

        // ... input
        $oContent = $roElement->getContent();
        if( $oContent instanceof PHP_APE_Type_Boolean )
        {
          $bSelectEmpty = strlen( $sCriteria ) == 0;
          $bSelectTrue = ( $sCriteria == '1' );
          $bSelectFalse = ( $sCriteria == '0' );
          $bSelectUnknown = !( $bSelectEmpty || $bSelectTrue || $bSelectFalse );
          $sOutput .= '<TD CLASS="v"><SELECT NAME="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $mKey ).'" ONKEYPRESS="javascript:PHP_APE_DF_onKeyPress(\''.$this->sRID.'\',event);">'.( $bSelectUnknown ? '<OPTION VALUE="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData($sCriteria).'" SELECTED>?</OPTION>' : null ).'<OPTION VALUE=""'.( $bSelectEmpty ? ' SELECTED' : null ).'>&nbsp;</OPTION><OPTION VALUE="1"'.( $bSelectTrue ? ' SELECTED' : null).'>'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->formatData( new PHP_APE_Type_Boolean( true ) ).'</OPTION><OPTION VALUE="0"'.( $bSelectFalse ? ' SELECTED' : null ).'>'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->formatData( new PHP_APE_Type_Boolean( false ) ).'</OPTION></SELECT></TD>';
        }
        else
          $sOutput .= '<TD CLASS="v"><INPUT CLASS="text" TYPE="text" NAME="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $mKey ).'" VALUE="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $sCriteria ).'" ONKEYPRESS="javascript:PHP_APE_DF_onKeyPress(\''.$this->sRID.'\',event);" /></TD>';
      }
      else
        $sOutput .= '<TD CLASS="v">&nbsp;</TD>';
    }
    $sOutput .= "\r\n";
    $sOutput .= PHP_APE_HTML_Data_Filter::htmlFormSuffix( $mID );
    $sOutput .= '</TR>'."\r\n";
    return $sOutput;
  }

  /** Returns the data list view's <SAMP>FORM</SAMP> prefix
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   *
   * @return string
   */
  protected function _htmlFormPrefix()
  {
    // Form
    $sQuery = preg_replace( '/&?PHP_APE_DR_'.$this->sRID.'=[^&]*/is', null, $_SERVER['QUERY_STRING'] );
    $sQuery = ltrim( $sQuery, '&' );
    $sURL = PHP_APE_Util_URL::addVariable( $this->roController->getFormURL(), $sQuery );

    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FORM: PHP_APE_HTML_Data_BasicList::htmlForm(Prefix) - BEGIN (".$this->sRID.") -->\r\n";
    $sOutput .= '<FORM NAME="PHP_APE_DR_'.$this->sRID.'" ACTION="'.$sURL.'" METHOD="get">'."\r\n";
    $sOutput .= '<INPUT TYPE="hidden" NAME="PHP_APE_DR_'.$this->sRID.'" DISABLED />'."\r\n";
    $sOutput .= '<INPUT TYPE="hidden" NAME="__KEY" VALUE="'.$this->sKey.'" />'."\r\n";
    if( $this->bUsePopup )
      $sOutput .= '<INPUT TYPE="hidden" NAME="__POPUP" VALUE="yes" DISABLED />'."\r\n";
    $sOutput .= '<INPUT TYPE="hidden" NAME="__FROM" VALUE="list" />'."\r\n";
    $sOutput .= '<INPUT TYPE="hidden" NAME="__TO" DISABLED />'."\r\n";
    if( is_array( $this->amPassthruVariables ) )
      foreach( $this->amPassthruVariables as $sName => $sValue )
        if( !is_null($sValue) ) $sOutput .= '<INPUT TYPE="hidden" NAME="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $sName ).'" VALUE="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $sValue ).'" />'."\r\n";
    return $sOutput;
  }
  
  /** Returns the data list view's content
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>, <SAMP>PHP_APE_Auth_AuthorizationException</SAMP>.</P>
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
    $bIconDisplay = PHP_APE_HTML_Data_View::$roEnvironment->getUserParameter( 'php_ape.html.display.icon' ) > PHP_APE_HTML_SmartTags::Icon_Hide;

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
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_BasicList::htmlData - DATA (".$this->sRID.") -->\r\n";

    // Data
    if( ( $this->roResultSet instanceof PHP_APE_Data_isExtendedResultSet ) and $this->roResultSet->countEntries() < 1 )
    { // NO (empty) data
      $sOutput .= '<TR CLASS="d"><TD CLASS="m" COLSPAN="100" TITLE="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( PHP_APE_HTML_Data_View::$asResources['tooltip.nodata'] ).'" STYLE="CURSOR:help;">'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( PHP_APE_HTML_Data_View::$asResources['label.nodata'] ).'</TD></TR>'."\r\n";
    } 
    else
    {
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

        // ... row
        $sOutput .= '<TR CLASS="d">';
        $sAltClassSuffix = ( $iRow++ % 2 ) ? '2' : null;

        // ... controls
        $sOutput .= '<TD CLASS="c'.$sAltClassSuffix.'">';
        $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen();
        if( $bAuthorizedDetail or $bAuthorizedUpdate or $bAuthorizedDelete or $bAuthorizedInsert )
        {
          // ... selection checkbox
          if( $this->bUseSelection )
            $sOutput .= '<INPUT TYPE="checkbox" CLASS="checkbox" NAME="__PK" VALUE="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $mPKValue ).'" />';
          else
            $sOutput .= '<INPUT TYPE="hidden" NAME="__PK" VALUE="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $mPKValue ).'" />';

          // ... insert button
          if( $bAuthorizeInsert )
          {
            $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
            if( $bAuthorizedInsert )
            {
              $sURL = "javascript:PHP_APE_DR_BasicList_do('".$this->sRID."','new','".PHP_APE_HTML_Data_View::$oDataSpace_JavaScript->encodeData( $mPKValue )."',".($this->bUsePopup?'false':'true').",".($this->bUsePopup?'true':'false').",".($this->bRequireSelectForInsert?'true':'false').");";
              $sTooltip = is_null( $this->sTooltipNew ) ? PHP_APE_HTML_Data_View::$asResources['tooltip.new'] : $this->sTooltipNew;
              $sOutput .= $bIconDisplay ? PHP_APE_HTML_SmartTags::htmlIcon( 'S-new', $sURL, $sTooltip, null, true ) : PHP_APE_HTML_Tags::htmlAnchor( $sURL, '+', $sTooltip, null, true );
            }
            else
              $sOutput .= $bIconDisplay ? PHP_APE_HTML_SmartTags::htmlIcon( 'S-lock', null, null, null, true ) : '&equiv;';
          }

          // ... delete button
          if( $bAuthorizeDelete )
          {
            $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
            if( $bAuthorizedDelete )
            {
              $sURL = "javascript:PHP_APE_DR_BasicList_do('".$this->sRID."','delete','".PHP_APE_HTML_Data_View::$oDataSpace_JavaScript->encodeData( $mPKValue )."',true,false,false);";
              $sTooltip = is_null( $this->sTooltipDelete ) ? PHP_APE_HTML_Data_View::$asResources['tooltip.delete'] : $this->sTooltipDelete;
              $sOutput .= $bIconDisplay ? PHP_APE_HTML_SmartTags::htmlIcon( 'S-delete', $sURL, $sTooltip, null, true ) : PHP_APE_HTML_Tags::htmlAnchor( $sURL, '&times;', $sTooltip, null, true );
            }
            else
              $sOutput .= $bIconDisplay ? PHP_APE_HTML_SmartTags::htmlIcon( 'S-lock', null, null, null, true ) : '&equiv;';
          }

          // ... update button
          if( $bAuthorizeUpdate )
          {
            $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
            if( $bAuthorizedUpdate )
            {
              $sURL = "javascript:PHP_APE_DR_BasicList_do('".$this->sRID."','edit','".PHP_APE_HTML_Data_View::$oDataSpace_JavaScript->encodeData( $mPKValue )."',".($this->bUsePopup?'false':'true').",".($this->bUsePopup?'true':'false').",false);";
              $sTooltip = is_null( $this->sTooltipEdit ) ? PHP_APE_HTML_Data_View::$asResources['tooltip.edit'] : $this->sTooltipEdit;
              $sOutput .= $bIconDisplay ? PHP_APE_HTML_SmartTags::htmlIcon( 'S-edit', $sURL, $sTooltip, null, true ) : PHP_APE_HTML_Tags::htmlAnchor( $sURL, '&part;', $sTooltip, null, true );
            }
            else
              $sOutput .= $bIconDisplay ? PHP_APE_HTML_SmartTags::htmlIcon( 'S-lock', null, null, null, true ) : '&equiv;';
          }

          // ... detail button
          if( $bAuthorizeDetail )
          {
            $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
            if( $bAuthorizedDetail )
            {
              $sURL = "javascript:PHP_APE_DR_BasicList_do('".$this->sRID."','detail','".PHP_APE_HTML_Data_View::$oDataSpace_JavaScript->encodeData( $mPKValue )."',false,false,false);";
              $sTooltip = is_null( $this->sTooltipDetail ) ? PHP_APE_HTML_Data_View::$asResources['tooltip.detail'] : $this->sTooltipDetail;
              if( $bIconDisplay )
              {
                $sOutput .= PHP_APE_HTML_SmartTags::htmlIcon( 'S-detail', $sURL, $sTooltip, null, true );
                $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'PADDING-LEFT:2px !important;', false );
              }
              $sOutput .= PHP_APE_HTML_Tags::htmlAnchor( $sURL, '&hellip;', $sTooltip, null, true );
            }
            else
              $sOutput .= $bIconDisplay ? PHP_APE_HTML_SmartTags::htmlLabel( null, 'S-lock', null, null, null, true ) : '&equiv;';
          }

        }
        else if( !is_null( $mPKValue ) and ( $bAuthorizeDetail or $bAuthorizeUpdate or $bAuthorizeDelete or $bAuthorizeInsert ) )
          // ... lock icon
          $sOutput .=  $bIconDisplay ? PHP_APE_HTML_SmartTags::htmlIcon( 'S-lock', null, null, null, true ) : '&equiv;';
        else
          // ... NO lock icon
          $sOutput .= '&nbsp;';
        $sOutput .=  PHP_APE_HTML_SmartTags::htmlAlignClose();
        $sOutput .= '</TD>';

        // ... elements
        foreach( $this->amDisplayedKeys as $mKey )
        {
          $sOutput .= '<TD CLASS="v'.$sAltClassSuffix.'">';
          $sOutput .= $this->_htmlElement( $mKey );
          $sOutput .= '</TD>';
        }
        $sOutput .= '</TR>'."\r\n";
      }
    }

    // End
    return $sOutput;
  }

  /** Returns the data list view's element
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   *
   * @param mixed $mKey Data element key
   * @return string
   */
  protected function _htmlElement( $mKey )
  {
    static $iTruncateLength;

    // Sanitize input
    $mKey = PHP_APE_Type_Index::parseValue( $mKey );

    // Retrieve element
    $roElement =& $this->roResultSet->useElement( $mKey );

    // Output
    $sOutput = null;

    // ... class
    $sClass = null;
    if( $roElement instanceof PHP_APE_Data_hasMeta )
    {
      $iMeta = $roElement->getMeta();
      if( $iMeta & PHP_APE_Data_Field::Type_PrimaryKey ) $sClass = 'pk';
      elseif( $iMeta & PHP_APE_Data_Field::Type_Key ) $sClass = 'fk';
      elseif( $iMeta & PHP_APE_Data_Field::Type_Identifier ) $sClass = 'id';
    }

    // ... HTML
    $sOutput .= '<SPAN';
    if( $sClass )
      $sOutput .= ' CLASS="'.$sClass.'"';
    $sOutput .= '>';

    // ... element
    $sOutput_element = null;
    if( is_null( $sOutput_element ) and ( $this->roResultSet instanceof PHP_APE_HTML_hasOutputHandler ) )
      $sOutput_element = $this->roResultSet->getHTMLOutput( $mKey );
    if( is_null( $sOutput_element ) and ( $roElement instanceof PHP_APE_HTML_hasOutputHandler ) )
      $sOutput_element = $roElement->getHTMLOutput();
    if( is_null( $sOutput_element ) )
    {
      if( is_null( $iTruncateLength ) )
        $iTruncateLength = (integer)PHP_APE_HTML_Data_View::$roEnvironment->getUserParameter( 'php_ape.html.data.list.truncate' );
      $sOutput_element .= PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( PHP_APE_Type_String::truncate( $roElement->useContent()->getValueFormatted( null, null ), $iTruncateLength ) );
    }
    $sOutput .= strlen( $sOutput_element ) > 0 ? $sOutput_element : '&nbsp;';

    // ... HTML
    $sOutput .= '</SPAN>';
    return $sOutput;
  }

  /** Returns the data list view's <SAMP>FORM</SAMP> suffix
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   *
   * @return string
   */
  protected function _htmlFormSuffix()
  {
    // Output
    $sOutput = null;
    $sOutput .= '</FORM>'."\r\n";
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FORM: PHP_APE_HTML_Data_BasicList::htmlForm(Suffix) - END (".$this->sRID.") -->\r\n";
    return $sOutput;
  }

  /** Returns the data list view's footer
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   *
   * @param boolean $bButtonDetail Display detail data retrieval button
   * @param boolean $bButtonUpdate Display data update button
   * @param boolean $bButtonDelete Display data deletion button
   * @param boolean $bButtonInsert Display data insertion button
   * @return string
   */
  protected function _htmlFooter( $bButtonDetail, $bButtonUpdate, $bButtonDelete, $bButtonInsert )
  {
    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_BasicList::htmlFooter - CONTROLS (".$this->sRID.") -->\r\n";
    if( $bButtonDetail or $bButtonUpdate or $bButtonDelete or $bButtonInsert )
    {
      $sOutput .= '<TR CLASS="c">';
      $sOutput .= '<TD CLASS="c" COLSPAN="100">';
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen();
      if( $this->bUseSelection ) {
        $sOutput .= '<INPUT TYPE="checkbox" CLASS="checkbox" ONCLICK="javascript:PHP_APE_DR_'.$this->sRID.'_allPK(this.checked);" />';
      }

      $iSeparator = 0;
      if( $bButtonInsert ) {
        $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( $iSeparator ? 'PADDING-LEFT:2px !important;' : null, $iSeparator++ );
        $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( is_null( $this->sLabelNew ) ? PHP_APE_HTML_Data_View::$asResources['label.new'] : $this->sLabelNew, 'S-new', "javascript:PHP_APE_DR_BasicList_do('".$this->sRID."','new',null,".($this->bUsePopup?'false':'true').",".($this->bUsePopup?'true':'false').",".($this->bRequireSelectForInsert?'true':'false').");", is_null( $this->sTooltipNew ) ? PHP_APE_HTML_Data_View::$asResources['tooltip.new'] : $this->sTooltipNew, null, true, false );
      }
      if( $this->bUseSelection and $bButtonDelete ) {
        $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( $iSeparator ? 'PADDING-LEFT:2px !important;' : null, $iSeparator++ );
        $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( is_null( $this->sLabelDelete ) ? PHP_APE_HTML_Data_View::$asResources['label.delete'] : $this->sLabelDelete, 'S-delete', "javascript:PHP_APE_DR_BasicList_do('".$this->sRID."','delete',null,true,false,false);", is_null( $this->sTooltipDelete ) ? PHP_APE_HTML_Data_View::$asResources['tooltip.delete'] : $this->sTooltipDelete, null, true, false );
      }
      if( $this->bUseSelection and $bButtonUpdate ) {
        $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( $iSeparator ? 'PADDING-LEFT:2px !important;' : null, $iSeparator++ );
        $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( is_null( $this->sLabelEdit ) ? PHP_APE_HTML_Data_View::$asResources['label.edit'] : $this->sLabelEdit, 'S-edit', "javascript:PHP_APE_DR_BasicList_do('".$this->sRID."','edit',null,".($this->bUsePopup?'false':'true').",".($this->bUsePopup?'true':'false').",false);", is_null( $this->sTooltipEdit ) ? PHP_APE_HTML_Data_View::$asResources['tooltip.edit'] : $this->sTooltipEdit, null, true, false );
      }
      if( $this->bUseSelection and $bButtonDetail )
      {
        $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( $iSeparator ? 'PADDING-LEFT:2px !important;' : null, $iSeparator++ );
        $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( is_null( $this->sLabelDetail ) ? PHP_APE_HTML_Data_View::$asResources['label.detail'] : $this->sLabelDetail, 'S-detail', "javascript:PHP_APE_DR_BasicList_do('".$this->sRID."','detail',null,false,false,false);", is_null( $this->sTooltipDetail ) ? PHP_APE_HTML_Data_View::$asResources['tooltip.detail'] : $this->sTooltipDetail, null, true, false );
      }

      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();
      $sOutput .= '</TD>';
      $sOutput .= '</TR>'."\r\n";
    }
    return $sOutput;
  }

  /** Closes the data list view
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   *
   * @return string
   */
  protected function _htmlClose()
  {
    // Output
    $sOutput = null;
    $sOutput .= '</TABLE>'."\r\n";
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_BasicList::htmlClose - END (".$this->sRID.") -->\r\n";
    return $sOutput;
  }

  /** Returns this data list view's controls
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   *
   * @return string
   */
  protected function _htmlControls()
  {
    // Output
    $mID = $this->getID();
    $sOutput = null;
    if( ( $this->bUseScroller and ( $this->roResultSet instanceof PHP_APE_Data_isExtendedResultSet ) ) or $this->bUseFilter ) 
    {
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen();

      if( $this->bUseScroller and ( $this->roResultSet instanceof PHP_APE_Data_isExtendedResultSet ) )
      {
        $oScroller = $this->roResultSet->getScroller();
        $iLimit = $oScroller->getLimit();
        $iOffset = $oScroller->getOffset();
        $iQuantity = $this->roResultSet->countAllEntries();
        $sOutput .= PHP_APE_HTML_Data_Scroller::htmlControls( $mID, $iLimit, $iOffset, $iQuantity );
      }

      if( ( $this->bUseScroller and ( $this->roResultSet instanceof PHP_APE_Data_isExtendedResultSet ) ) and $this->bUseFilter )
        $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();

      if( $this->bUseFilter )
      {
        $oFilter = $this->roResultSet->getFilter();
        $sGlobalCriteria = null;
        if( $oFilter->isElement( '__GLOBAL' ) )
          $sGlobalCriteria = $oFilter->useElement( '__GLOBAL' )->toString();
        $sOutput .= PHP_APE_HTML_Data_Filter::htmlControls( $mID, true, $sGlobalCriteria, $this->bUseHeader and $this->bUseAdvancedFilter, $this->bUseFilterLink );
      }

      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();
    }
    return $sOutput;
  }

  /** Sets this data list view's displayable elements
   */
  protected function _setDisplayedKeys()
  {
    if( !is_array( $this->amDisplayableKeys ) ) $this->setDisplayableKeys();
    $this->amDisplayedKeys = $this->amDisplayableKeys;
  }

  /*
   * METHODS: public
   ********************************************************************************/

  /** Sets this data list view's displayed elements
   *
   * @param array|mixed $amDisplayableKeys Displayed elements keys
   */
  public function setDisplayableKeys( $amDisplayableKeys = null )
  {
    // Sanitize input
    if( !is_array( $amDisplayableKeys ) )
      $amDisplayableKeys = $this->roResultSet->getElementsKeys();
    else
      $amDisplayableKeys = array_intersect( $amDisplayableKeys, $this->roResultSet->getElementsKeys() );

    // Save view parameters
    $this->amDisplayableKeys = $amDisplayableKeys;
  }

  /** Returns a data list view
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Auth_AuthorizazionException</SAMP>, <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   *
   * @return string
   */
  public function html()
  {
    // Check
    // ... authorization
    if( ( $this->roResultSet instanceof PHP_APE_Data_hasAuthorization ) and !$this->roResultSet->hasAuthorization() )
      throw new PHP_APE_Auth_AuthorizationException( __METHOD__, 'Permission denied' );

    // Set displayable elements (keys)
    $this->_setDisplayedKeys();

    // Permissions
    $bGlobalAuthorizedInsert = false;
    if( ( $this->roResultSet instanceof PHP_APE_Data_isInsertAble ) and
        !( $this->iQueryMeta & PHP_APE_Data_isQueryAbleResultSet::Disable_InsertAble ) )
      $bGlobalAuthorizedInsert = $this->roResultSet->isInsertAuthorized();
    $rbAuthorizedDetail = false;
    $rbAuthorizedUpdate = false;
    $rbAuthorizedDelete = false;
    $rbAuthorizedInsert = false;

    // Popup usage
    $this->bIsPopup = $this->roController->isPopup();

    // Query
    $bResetQuery = true;
    if( $this->roResultSet->isQueried() )
      $bResetQuery = false;
    else
      $this->roResultSet->queryEntries( $this->iQueryMeta );

    // Output
    $sOutput = null;

    // ... list
    $sOutput .= '<DIV CLASS="APE-list">'."\r\n";

    // ... controls
    if( $this->bUseControls )
    {
      $sOutput .= '<DIV CLASS="do-not-print">'."\r\n";
      $sOutput .= '<DIV CLASS="c">'."\r\n";
      $sOutput .= $this->_htmlControls();
      $sOutput .= '</DIV>'."\r\n";
      $sOutput .= '</DIV>'."\r\n";
    }

    // ... data
    $sOutput .= '<DIV CLASS="d">'."\r\n";
    $sOutput .= $this->_htmlJavaScript();
    $sOutput .= $this->_htmlOpen();
    if( $this->bUseHeader ) 
    {
      $sOutput .= $this->_htmlHeader();
      if( $this->bUseFilter && PHP_APE_HTML_Data_View::$roEnvironment->getUserParameter( 'php_ape.data.filter.advanced' ) ) $sOutput .= $this->_htmlFilter();
    }
    $sOutput .= $this->_htmlFormPrefix();
    $sOutput .= $this->_htmlData( $rbAuthorizedDetail, $rbAuthorizedUpdate, $rbAuthorizedDelete, $rbAuthorizedInsert );
    $sOutput .= $this->_htmlFormSuffix();
    if( $this->bUseFooter ) $sOutput .= $this->_htmlFooter( $rbAuthorizedDetail, $rbAuthorizedUpdate, $rbAuthorizedDelete, $bGlobalAuthorizedInsert || $rbAuthorizedInsert );
    $sOutput .= $this->_htmlClose();
    $sOutput .= '</DIV>'."\r\n";

    // ... controls
    if( $this->bUseControls )
    {
      $sOutput .= '<DIV CLASS="do-not-print">'."\r\n";
      $sOutput .= '<DIV CLASS="c">'."\r\n";
      $sOutput .= $this->_htmlControls();
      $sOutput .= '</DIV>'."\r\n";
      $sOutput .= '</DIV>'."\r\n";
    }

    // ... forms
    if( $this->bUseScroller ) $sOutput .= PHP_APE_HTML_Data_Scroller::htmlForm( $this->getID() );
    if( $this->bUseOrder ) $sOutput .= PHP_APE_HTML_Data_Order::htmlForm( $this->getID() );
    if( $this->bUseFilter )
    {
      $oFilter = $this->roResultSet->getFilter();
      $sGlobalCriteria = null;
      if( ( $oFilter instanceof PHP_APE_Data_Filter ) and $oFilter->isElement( '__GLOBAL' ) )
        $sGlobalCriteria = $oFilter->useElement( '__GLOBAL' )->toString();
      $sOutput .= PHP_APE_HTML_Data_Filter::htmlForm( $this->getID(), $sGlobalCriteria );
    }

    // ... list (end)
    $sOutput .= '</DIV>'."\r\n";

    // Reset query
    if( $bResetQuery )
      $this->roResultSet->resetQuery();

    // End
    return $sOutput;
  }

  /** Returns this data list view's submission JavaScript
   *
   * @param string $sDestination Submission destination (action)
   * @param mixed $mPrimaryKey Submission (data) primary key
   * @param boolean $bLockForm Lock form after action (and prevent multiple submissions)
   * @param boolean $bUsePopup Open destination as popup
   * @param boolean $bRequireSelectForInsert Require selection for insertion ('new' action)
   * @return string
   */
  public function getSubmitJScript( $sDestination = null, $mPrimaryKey = null, $bLockForm = true, $bUsePopup = false, $bRequireSelectForInsert = false )
  {
    // Output
    $sOutput = "PHP_APE_DR_BasicList_do('".$this->sRID."'";
    $sOutput .= ',';
    if( !empty( $sDestination ) )
    {
      $sDestination = PHP_APE_Type_Index::parseValue( $sDestination );
      $sOutput .= "'".PHP_APE_HTML_Data_View::$oDataSpace_JavaScript->encodeData( $sDestination )."'";
    }
    else
      $sOutput .= 'null';
    $sOutput .= ',';
    if( !is_null( $mPrimaryKey ) )
    {
      $mPrimaryKey = PHP_APE_Type_Scalar::parseValue( $mPrimaryKey );
      $sOutput .= "'".PHP_APE_HTML_Data_View::$oDataSpace_JavaScript->encodeData( $mPrimaryKey )."'";
    }
    else
      $sOutput .= 'null';
    $sOutput .= ','.($bLockForm?'true':'false');
    $sOutput .= ','.($bUsePopup?'true':'false');
    $sOutput .= ','.($bRequireSelectForInsert?'true':'false');
    $sOutput .= ')';

    // End
    return $sOutput;
  }

  /** Returns this data list view's check JavaScript
   *
   * @param boolean $bCheckDelete Issue deletion warning (message) check
   * @param boolean $bRequireSelectForInsert Require selection for insertion ('new' action)
   * @return string
   */
  public function getCheckJScript( $bCheckDelete = false )
  {
    // End
    return "PHP_APE_DR_BasicList_check('".$this->sRID."',true,".( $bCheckDelete ? 'true' : 'false' ).",".( $bRequireSelectForInsert ? 'true' : 'false' ).")";
  }

}
