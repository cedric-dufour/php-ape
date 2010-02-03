
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

/** (Basic) Detail view display utilities
 *
 * @package PHP_APE_HTML
 * @subpackage Page
 */
class PHP_APE_HTML_Data_BasicDetail
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

  /** Use header element
   * @var boolean */
  protected $bUseHeader;

  /** Use footer element
   * @var boolean */
  protected $bUseFooter;

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

  /** Label for 'list' button
   * @var string */
  protected $sLabelList;

  /** Label for 'new' button
   * @var string */
  protected $sLabelNew;

  /** Tooltip for 'new' button
   * @var string */
  protected $sTooltipNew;

  /** Tooltip for 'list' button
   * @var string */
  protected $sTooltipList;

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

  /** Constructs a new detail view instance
   *
   * @param PHP_APE_HTML_Controller $roController Associated controller
   * @param PHP_APE_Data_isDetailAbleResultSet $roResultSet Data result set
   * @param mixed $mPrimaryKey Data primary key
   * @param integer $iQueryMeta Query meta code (see {@link PHP_APE_Data_isQueryAbleResultSet} constants)
   * @param array|mixed $amPassthruVariables Hidden variables to include in <SAMP>FORM</SAMP> (associating: <I>name</I> => <I>value</I>)
   */
  public function __construct( PHP_APE_HTML_Controller $roController, PHP_APE_Data_isDetailAbleResultSet $roResultSet, $mPrimaryKey, $iQueryMeta = PHP_APE_Data_isQueryAbleResultSet::Query_Full, $amPassthruVariables = null )
  {
    // Construct view
    parent::__construct( $roController, $roResultSet->getName(), $roResultSet->getDescription() );
    $this->attachResultSet( $roResultSet, $iQueryMeta, $mPrimaryKey );
    $this->setPassthruVariables( $amPassthruVariables );

    // Preferences
    $this->bUseHeader = true;
    $this->bUseFooter = true;
    $this->bUsePopup = PHP_APE_HTML_Data_View::$roEnvironment->getUserParameter( 'php_ape.html.popup.use' );

    // Text for buttons (null = localized internal default)
    $this->sLabelBack = null;
    $this->sTooltipBack = null;
    $this->sLabelClose = null;
    $this->sTooltipClose = null;
    $this->sLabelList = null;
    $this->sTooltipList = null;
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
  
  /** Set text for 'list' button
   * @param string $sLabel Button label
   * @param string $sTooltip Button tooltip */
  public function prefButtonList( $sLabel, $sTooltip )
  {
    $this->sLabelList = PHP_APE_Type_String::parseValue( $sLabel );
    $this->sTooltipList = PHP_APE_Type_String::parseValue( $sTooltip );
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

  /** Returns the data detail view's <SAMP>JavaScript</SAMP>
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

    // Form
    $sQuery = preg_replace( '/&?PHP_APE_DR_'.$this->sRID.'=[^&]*/is', null, $_SERVER['QUERY_STRING'] );
    $sQuery = ltrim( $sQuery, '&' );
    
    // JavaScript
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FORM: PHP_APE_HTML_Data_BasicDetail::htmlJavaScript - BEGIN -->\r\n";
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    $sOutput .= '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript"><!--'."\r\n";
    $sOutput .= "function PHP_APE_DR_BasicDetail_check(rid,forceDelete)\r\n";
    $sOutput .= "{\r\n";
    $sOutput .= " form = document.forms['PHP_APE_DR_'+rid];\r\n";
    $sOutput .= " if( forceDelete || ( !form.__TO.disabled && String('delete').indexOf(form.__TO.value)>=0 ) )\r\n";
    $sOutput .= " {\r\n";
    $sOutput .= "  if( !window.confirm('".PHP_APE_HTML_Data_View::$oDataSpace_JavaScript->encodeData( PHP_APE_HTML_Data_View::$asResources['message.delete.confirm'] )."') ) return false;\r\n";
    $sOutput .= " }\r\n";
    $sOutput .= " return true;\r\n";
    $sOutput .= "}\r\n";
    $sOutput .= "function PHP_APE_DR_BasicDetail_submit(rid,lock,popup)\r\n";
    $sOutput .= "{\r\n";
    $sOutput .= " if( lock==null ) lock=true;\r\n";
    $sOutput .= " form = document.forms['PHP_APE_DR_'+rid];\r\n";
    $sOutput .= " fvar = form.elements['PHP_APE_DR_'+rid];\r\n";
    $sOutput .= " if( fvar.disabled )\r\n";
    $sOutput .= " {\r\n";
    $sOutput .= "  if( PHP_APE_DR_BasicDetail_check(rid) && !form.__TO.disabled )\r\n";
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
    $sOutput .= "function PHP_APE_DR_BasicDetail_do(rid,__TO,lock,popup)\r\n";
    $sOutput .= "{\r\n";
    $sOutput .= " form = document.forms['PHP_APE_DR_'+rid];\r\n";
    $sOutput .= " if( __TO==null ) return;\r\n";
    $sOutput .= " form.__TO.disabled=false;\r\n";
    $sOutput .= " form.__TO.value = __TO;\r\n";
    $sOutput .= " if( lock==null ) lock=true;\r\n";
    $sOutput .= " PHP_APE_DR_BasicDetail_submit(rid,lock,popup);\r\n";
    $sOutput .= "}\r\n";
    $sOutput .= "--></SCRIPT>\r\n";
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FORM: PHP_APE_HTML_Data_BasicDetail::htmlJavaScript - END -->\r\n";
    return $sOutput;
  }

  /** Opens the data detail view
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   *
   * @return string
   */
  protected function _htmlOpen()
  {
    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_BasicDetail::htmlOpen - BEGIN (".$this->sRID.") -->\r\n";
    $sOutput .= '<TABLE CLASS="APE-detail" CELLSPACING="0">'."\r\n";
    return $sOutput;
  }

  /** Returns the data detail view's header
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   *
   * @return string
   */
  protected function _htmlHeader()
  {
    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_BasicDetail::htmlHeader - HEADER (".$this->sRID.") -->\r\n";
    $sOutput .= '<TR CLASS="c">';
    $sOutput .= '<TD CLASS="c" COLSPAN="100">';
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen(); $bSeparator = false;
    if( !$this->bIsPopup )
    {
      $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( is_null( $this->sLabelBack ) ? PHP_APE_HTML_Data_View::$asResources['label.back'] : $this->sLabelBack, 'S-back', "javascript:window.history.back();", is_null( $this->sTooltipBack ) ? PHP_APE_HTML_Data_View::$asResources['tooltip.back'] : $this->sTooltipBack, null, false, false );
    }
    else
    {
      $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( is_null( $this->sLabelClose ) ? PHP_APE_HTML_Data_View::$asResources['label.close'] : $this->sLabelClose, 'S-close', "javascript:window.close();", is_null( $this->sTooltipClose ) ? PHP_APE_HTML_Data_View::$asResources['tooltip.close'] : $this->sTooltipClose, null, false, false );
    }
    if( ( $this->roResultSet instanceof PHP_APE_Data_isListAble ) and
        !( $this->iQueryMeta & PHP_APE_Data_isQueryAbleResultSet::Disable_ListAble ) and
        $this->roResultSet->isListAuthorized() )
    {
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
      $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( is_null( $this->sLabelList ) ? PHP_APE_HTML_Data_View::$asResources['label.list'] : $this->sLabelList, 'S-list', "javascript:PHP_APE_DR_BasicDetail_do('".$this->sRID."','list',false,false);", is_null( $this->sTooltipList ) ? PHP_APE_HTML_Data_View::$asResources['tooltip.list'] : $this->sTooltipList, null, true, false );
    }
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();
    $sOutput .= '</TD>';
    $sOutput .= '</TR>'."\r\n";
    return $sOutput;
  }
  
  /** Returns the data detail view's content
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>, <SAMP>PHP_APE_Auth_AuthorizationException</SAMP>.</P>
   *
   * @return string
   */
  protected function _htmlData()
  {
    // Environment
    $bGlobalHideEmpty = PHP_APE_HTML_Data_View::$roEnvironment->getUserParameter( 'php_ape.html.data.hide.empty' );

    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_BasicDetail::htmlData - DATA (".$this->sRID.") -->\r\n";

    // Data
    $iRow = 0;
    foreach( $this->amDisplayedKeys as $mKey )
    {
      $roElement =& $this->roResultSet->useElement( $mKey );
      if( $roElement->useContent()->isEmpty() )
      {
        if( $roElement instanceof PHP_APE_Data_hasMeta )
        {
          $iElementMeta = $roElement->getMeta();
          if( !( $iElementMeta & PHP_APE_Data_Field::Feature_ShowIfEmpty ) and
              ( ( $iElementMeta & PHP_APE_Data_Field::Feature_HideIfEmpty ) or $bGlobalHideEmpty ) )
            continue;
        }
        elseif( $bGlobalHideEmpty ) continue;
      }
      $sAltClassSuffix = ( $iRow++ % 2 ) ? '2' : null;
      $sOutput .= '<TR CLASS="d">';
      $sOutput .= '<TD CLASS="l'.$sAltClassSuffix.'"';
      if( ( $roElement instanceof PHP_APE_Data_hasDescription ) and $roElement->hasDescription() )
        $sOutput .= ' TITLE="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $roElement->getDescription() ).'" STYLE="CURSOR:help;"';
      $sOutput .= '>';
      if( ( $roElement instanceof PHP_APE_Data_hasName ) and $roElement->hasName() ) 
        $sOutput .= PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $roElement->getName() );
      else
        $sOutput .= PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $mKey );
      $sOutput .= '&nbsp;:</TD>';
      $sOutput .= '<TD CLASS="v'.$sAltClassSuffix.'">';
      $sOutput .= $this->_htmlElement( $mKey );
      $sOutput .= '</TD>';
      $sOutput .= '</TR>'."\r\n";
    }

    // Hyperlinks
    if( ( $this->roResultSet instanceof PHP_APE_Data_hasHyperlinks ) and count( $aoHyperlinks = PHP_APE_Type_Array::parseValue( $this->roResultSet->getHyperlinks() ) ) > 0 )
    {
      $sAltClassSuffix = ( $iRow++ % 2 ) ? '2' : null;
      $sOutput .= '<TR CLASS="d">';
      $sOutput .= '<TD CLASS="l'.$sAltClassSuffix.'" TITLE="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( PHP_APE_HTML_Data_View::$asResources['tooltip.hyperlinks'] ).'" STYLE="CURSOR:help;">'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( PHP_APE_HTML_Data_View::$asResources['label.hyperlinks'] ).'&nbsp;:</TD>';
      $sOutput .= '<TD CLASS="v'.$sAltClassSuffix.'">';
      foreach( $aoHyperlinks as $oHyperlink )
        $sOutput .= '<P CLASS="a">'.PHP_APE_HTML_Tags::htmlAnchor( $oHyperlink->getContent()->getValue(), $oHyperlink->getName(), $oHyperlink->getDescription() ).'</P>';
      $sOutput .= '</TD>';
      $sOutput .= '</TR>'."\r\n";
    }


    // End
    return $sOutput;
  }

  /** Returns the data detail view's element
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   *
   * @param mixed $mKey Data element key
   * @return string
   */
  protected function _htmlElement( $mKey )
  {
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
      $sOutput_element .= PHP_APE_HTML_Data_View::$oDataSpace_HTML->formatData( $roElement->useContent() );
    $sOutput .= strlen( $sOutput_element ) > 0 ? $sOutput_element : '&nbsp;';

    // ... HTML
    $sOutput .= '</SPAN>';
    return $sOutput;
  }

  /** Returns the data detail view's <SAMP>FORM</SAMP>
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   *
   * @return string
   */
  protected function _htmlForm()
  {
    // Form
    $sQuery = preg_replace( '/&?PHP_APE_DR_'.$this->sRID.'=[^&]*/is', null, $_SERVER['QUERY_STRING'] );
    $sQuery = ltrim( $sQuery, '&' );
    $sURL = PHP_APE_Util_URL::addVariable( $this->roController->getFormURL(), $sQuery );

    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FORM: PHP_APE_HTML_Data_BasicDetail::htmlForm - BEGIN (".$this->sRID.") -->\r\n";
    $sOutput .= '<FORM NAME="PHP_APE_DR_'.$this->sRID.'" ACTION="'.$sURL.'" METHOD="get">'."\r\n";
    $sOutput .= '<INPUT TYPE="hidden" NAME="PHP_APE_DR_'.$this->sRID.'" DISABLED />'."\r\n";
    $sOutput .= '<INPUT TYPE="hidden" NAME="__POPUP" VALUE="yes" DISABLED />'."\r\n";
    $sOutput .= '<INPUT TYPE="hidden" NAME="__PK" VALUE="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $this->mPrimaryKey ).'" />'."\r\n";
    $sOutput .= '<INPUT TYPE="hidden" NAME="__FROM" VALUE="detail" />'."\r\n";
    $sOutput .= '<INPUT TYPE="hidden" NAME="__TO" DISABLED />'."\r\n";
    if( is_array( $this->amPassthruVariables ) )
      foreach( $this->amPassthruVariables as $sName => $sValue )
        if( !is_null($sValue) ) $sOutput .= '<INPUT TYPE="hidden" NAME="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $sName ).'" VALUE="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $sValue ).'" />'."\r\n";
    $sOutput .= '</FORM>'."\r\n";
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FORM: PHP_APE_HTML_Data_BasicDetail::htmlForm - END (".$this->sRID.") -->\r\n";
    return $sOutput;
  }

  /** Returns the data detail view's footer
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   *
   * @param boolean $bButtonUpdate Display data update button
   * @param boolean $bButtonDelete Display data deletion button
   * @param boolean $bButtonInsert Display data insertion button
   * @return string
   */
  protected function _htmlFooter( $bButtonUpdate, $bButtonDelete, $bButtonInsert )
  {
    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_BasicDetail::htmlFooter - CONTROLS (".$this->sRID.") -->\r\n";
    if( $bButtonUpdate or $bButtonDelete or $bButtonInsert )
    {
      $sOutput .= '<TR CLASS="c">';
      $sOutput .= '<TD CLASS="c" COLSPAN="100">';
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen();

      $iSeparator = 0;
      if( $bButtonUpdate )
      {
        if( $iSeparator++ ) $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
        $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( is_null( $this->sLabelEdit ) ? PHP_APE_HTML_Data_View::$asResources['label.edit'] : $this->sLabelEdit, 'S-edit', "javascript:PHP_APE_DR_BasicDetail_do('".$this->sRID."','edit',".($this->bUsePopup?'false':'true').",".($this->bUsePopup?'true':'false').");", is_null( $this->sTooltipEdit ) ? PHP_APE_HTML_Data_View::$asResources['tooltip.edit'] : $this->sTooltipEdit, null, true, false );
      }
      if( $bButtonDelete )
      {
        if( $iSeparator++ ) $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
        $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( is_null( $this->sLabelDelete ) ? PHP_APE_HTML_Data_View::$asResources['label.delete'] : $this->sLabelDelete, 'S-delete', "javascript:PHP_APE_DR_BasicDetail_do('".$this->sRID."','delete',true,false);", is_null( $this->sTooltipDelete ) ? PHP_APE_HTML_Data_View::$asResources['tooltip.delete'] : $this->sTooltipDelete, null, true, false );
      }
      if( $bButtonInsert )
      {
        if( $iSeparator++ ) $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
        $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( is_null( $this->sLabelNew ) ? PHP_APE_HTML_Data_View::$asResources['label.new'] : $this->sLabelNew, 'S-new', "javascript:PHP_APE_DR_BasicDetail_do('".$this->sRID."','new',".($this->bUsePopup?'false':'true').",".($this->bUsePopup?'true':'false').");", is_null( $this->sTooltipNew ) ? PHP_APE_HTML_Data_View::$asResources['tooltip.new'] : $this->sTooltipNew, null, true, false );
      }

      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();
      $sOutput .= '</TD>';
      $sOutput .= '</TR>'."\r\n";
    }
    return $sOutput;
  }

  /** Closes the data detail view
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
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_BasicDetail::htmlClose - END (".$this->sRID.") -->\r\n";
    return $sOutput;
  }

  /** Sets this data detail view's displayable elements
   */
  protected function _setDisplayedKeys()
  {
    if( !is_array( $this->amDisplayableKeys ) ) $this->setDisplayableKeys();
    $this->amDisplayedKeys = $this->amDisplayableKeys;
  }


  /*
   * METHODS: public
   ********************************************************************************/

  /** Sets this data detail view's displayed elements
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

  /** Returns a data detail view
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
      throw new PHP_APE_Auth_AuthorizationException( __METHOD__, 'Permission denied; Class: '.get_class( $this->roResultSet ) );

    // Set displayable elements (keys)
    $this->_setDisplayedKeys();

    // Query
    $bResetQuery = true;
    if( $this->roResultSet->isQueried() )
      $bResetQuery = false;
    else
    {
      $this->roResultSet->queryEntries( $this->iQueryMeta );
      if( !$this->roResultSet->nextEntry() )
        throw new PHP_APE_Data_Exception( __METHOD__, 'No entry found (empty result set)' );
    }

    // Permissions
    $bAuthorizedInsert = false;
    if( ( $this->roResultSet instanceof PHP_APE_Data_isInsertAble ) and !( $this->iQueryMeta & PHP_APE_Data_isQueryAbleResultSet::Disable_InsertAble ) )
      $bAuthorizedInsert = $this->roResultSet->isInsertAuthorized();
    $bAuthorizedUpdate = false;
    if( ( $this->roResultSet instanceof PHP_APE_Data_isUpdateAble ) and !( $this->iQueryMeta & PHP_APE_Data_isQueryAbleResultSet::Disable_UpdateAble ) )
      $bAuthorizedUpdate = $this->roResultSet->isUpdateAuthorized();
    $bAuthorizedDelete = false;
    if( ( $this->roResultSet instanceof PHP_APE_Data_isDeleteAble ) and !( $this->iQueryMeta & PHP_APE_Data_isQueryAbleResultSet::Disable_DeleteAble ) )
      $bAuthorizedDelete = $this->roResultSet->isDeleteAuthorized();

    // Popup usage
    $this->bIsPopup = $this->roController->isPopup();

    // Output
    $sOutput = null;

    // ... detail
    $sOutput .= '<DIV CLASS="APE-detail">'."\r\n";

    // ... data
    $sOutput .= '<DIV CLASS="d">'."\r\n";
    $sOutput .= $this->_htmlJavaScript();
    $sOutput .= $this->_htmlOpen();
    if( $this->bUseHeader ) $sOutput .= $this->_htmlHeader();
    $sOutput .= $this->_htmlData();
    $sOutput .= $this->_htmlForm();
    if( $this->bUseFooter ) $sOutput .= $this->_htmlFooter( $bAuthorizedUpdate, $bAuthorizedDelete, $bAuthorizedInsert );
    $sOutput .= $this->_htmlClose();
    $sOutput .= '</DIV>'."\r\n";

    // ... detail (end)
    $sOutput .= '</DIV>'."\r\n";

    // Reset query
    if( $bResetQuery )
      $this->roResultSet->resetQuery();

    // End
    return $sOutput;
  }

  /** Returns this data detail view's submission JavaScript
   *
   * @param string $sDestination Submission destination (action)
   * @param boolean $bLockForm Lock form after action (and prevent multiple submissions)
   * @param boolean $bUsePopup Open destination as popup
   * @return string
   */
  public function getSubmitJScript( $sDestination = null, $bLockForm = true, $bUsePopup = false )
  {
    // Output
    $sOutput = "PHP_APE_DR_BasicDetail_do('".$this->sRID."'";
    $sOutput .= ',';
    if( !empty( $sDestination ) )
    {
      $sDestination = PHP_APE_Type_Index::parseValue( $sDestination );
      $sOutput .= "'".PHP_APE_HTML_Data_View::$oDataSpace_JavaScript->encodeData( $sDestination )."'";
    }
    else
      $sOutput .= 'null';
    $sOutput .= ','.($bLockForm?'true':'false');
    $sOutput .= ','.($bUsePopup?'true':'false');
    $sOutput .= ')';

    // End
    return $sOutput;
  }

  /** Returns this data detail view's check JavaScript
   *
   * @param boolean $bCheckDelete Issue deletion warning (message) check
   * @return string
   */
  public function getCheckJScript( $bCheckDelete = false )
  {
    // End
    return "PHP_APE_DR_BasicDetail_check('".$this->sRID."',".( $bCheckDelete ? 'true' : 'false' ).')';
  }

}
