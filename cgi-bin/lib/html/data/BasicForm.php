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

/** (Basic) Data form view object
 *
 * @package PHP_APE_HTML
 * @subpackage Page
 */
class PHP_APE_HTML_Data_BasicForm
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

  /** Allow upload
   * @var boolean */
  protected $bUploadAllow;

  /** Upload max size
   * @var integer */
  protected $iUploadMaxSize;

  /** Error flag
   * @var boolean */
  protected $bHasError;

  /** Required data flag
   * @var boolean */
  protected $bHasRequired;

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

  /** Label for 'list' button
   * @var string */
  protected $sLabelList;

  /** Tooltip for 'list' button
   * @var string */
  protected $sTooltipList;

  /** Label for 'insert' button
   * @var string */
  protected $sLabelInsert;

  /** Tooltip for 'insert' button
   * @var string */
  protected $sTooltipInsert;

  /** Label for 'update' button
   * @var string */
  protected $sLabelUpdate;

  /** Tooltip for 'update' button
   * @var string */
  protected $sTooltipUpdate;


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
   * @param array|mixed $amPassthruVariables Hidden variables to include in <SAMP>FORM</SAMP> (associating: <I>name</I> => <I>value</I>)
   */
  public function __construct( PHP_APE_HTML_Controller $roController, PHP_APE_Data_Function $roFunction, PHP_APE_Data_isDetailAbleResultSet $roResultSet = null, $mPrimaryKey = null, $iQueryMeta = PHP_APE_Data_isQueryAbleResultSet::Query_Full, $amPassthruVariables = null )
  {
    // Construct view
    parent::__construct( $roController, $roFunction->getName(), $roFunction->getDescription() );
    $this->attachFunction( $roFunction );
    if( !is_null( $roResultSet ) ) $this->attachResultSet( $roResultSet, $iQueryMeta, $mPrimaryKey );
    $this->setPassthruVariables( $amPassthruVariables );

    // Preferences
    $this->bUseHeader = true;
    $this->bUseFooter = true;
    $this->bUploadAllow = null;
    $this->iUploadMaxSize = null;

    // Text for buttons (null = localized internal default)
    $this->sLabelBack = null;
    $this->sTooltipBack = null;
    $this->sLabelClose = null;
    $this->sTooltipClose = null;
    $this->sLabelDetail = null;
    $this->sTooltipDetail = null;
    $this->sLabelList = null;
    $this->sTooltipList = null;
    $this->sLabelInsert = null;
    $this->sTooltipInsert = null;
    $this->sLabelUpdate = null;
    $this->sTooltipUpdate = null;
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
  
  /** Set preference for uploads
   * @param boolean $bUploadAllow Allow uploads and use upload-compatible form (use auto-detection if <SAMP>null</SAMP>)
   * @param integer $iUploadMaxSize Maximum uploadable file size [bytes; default <SAMP>php_ape.html.data.form.upload.maxsize</SAMP> environment setting] */
  public function prefUploads( $bUploadAllow, $iUploadMaxSize = null )
  {
    $this->bUploadAllow = (boolean)$bUploadAllow;
    $this->iUploadMaxSize = (integer)$iUploadMaxSize;
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
  
  /** Set text for 'list' button
   * @param string $sLabel Button label
   * @param string $sTooltip Button tooltip */
  public function prefButtonList( $sLabel, $sTooltip )
  {
    $this->sLabelList = PHP_APE_Type_String::parseValue( $sLabel );
    $this->sTooltipList = PHP_APE_Type_String::parseValue( $sTooltip );
  }
  
  /** Set text for 'insert' button
   * @param string $sLabel Button label
   * @param string $sTooltip Button tooltip */
  public function prefButtonInsert( $sLabel, $sTooltip )
  {
    $this->sLabelInsert = PHP_APE_Type_String::parseValue( $sLabel );
    $this->sTooltipInsert = PHP_APE_Type_String::parseValue( $sTooltip );
  }
  
  /** Set text for 'update' button
   * @param string $sLabel Button label
   * @param string $sTooltip Button tooltip */
  public function prefButtonUpdate( $sLabel, $sTooltip )
  {
    $this->sLabelUpdate = PHP_APE_Type_String::parseValue( $sLabel );
    $this->sTooltipUpdate = PHP_APE_Type_String::parseValue( $sTooltip );
  }


  /*
   * METHODS: protected
   ********************************************************************************/

  /** Returns the data form view's <SAMP>JavaScript</SAMP> script
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   *
   * @return string
   */
  protected function _htmlJavaScript()
  {
    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FORM: PHP_APE_HTML_Data_BasicForm::htmlJavaScript - BEGIN -->\r\n";
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    $sOutput .= '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript"><!--'."\r\n";
    $sOutput .= "function PHP_APE_DR_BasicForm_onKeyPress(rid,event,__TO)\r\n";
    $sOutput .= "{\r\n";
    $sOutput .= " if( __TO==null ) return;\r\n";
    $sOutput .= " if( event.keyCode==13 ) PHP_APE_DR_BasicForm_do(rid,__TO);\r\n";
    $sOutput .= " return true;\r\n";
    $sOutput .= "}\r\n";
    $sOutput .= "function PHP_APE_DR_BasicForm_submit(rid,lock)\r\n";
    $sOutput .= "{\r\n";
    $sOutput .= " if( lock==null ) lock=true;\r\n";
    $sOutput .= " form = document.forms['PHP_APE_DR_'+rid];\r\n";
    $sOutput .= " fvar = form.elements['PHP_APE_DR_'+rid];\r\n";
    $sOutput .= " if( !lock || fvar.disabled )\r\n";
    $sOutput .= "  PHP_APE_IN_Form_post(form,fvar,lock);\r\n";
    $sOutput .= "}\r\n";
    $sOutput .= "function PHP_APE_DR_BasicForm_do(rid,__TO,lock)\r\n";
    $sOutput .= "{\r\n";
    $sOutput .= " form = document.forms['PHP_APE_DR_'+rid];\r\n";
    $sOutput .= " if( __TO==null ) return;\r\n";
    $sOutput .= " form.__TO.disabled=false;\r\n";
    $sOutput .= " form.__TO.value = __TO;\r\n";
    $sOutput .= " if( lock==null ) lock=true;\r\n";
    $sOutput .= " PHP_APE_DR_BasicForm_submit(rid,lock);\r\n";
    $sOutput .= "}\r\n";
    $sOutput .= "--></SCRIPT>\r\n";
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FORM: PHP_APE_HTML_Data_BasicForm::htmlJavaScript - END -->\r\n";
    return $sOutput;
  }

  /** Opens the data form view
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   *
   * @return string
   */
  protected function _htmlOpen()
  {
    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_BasicForm::htmlOpen - BEGIN (".$this->sRID.") -->\r\n";
    $sOutput .= '<TABLE CLASS="APE-detail" CELLSPACING="0">'."\r\n";
    return $sOutput;
  }

  /** Returns the data form view's header
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   *
   * @param boolean $bShowPreferenceHideOptionalData Display 'Hide optional data' preference control
   * @return string
   */
  protected function _htmlHeader()
  {
    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_BasicForm::htmlHeader - HEADER (".$this->sRID.") -->\r\n";
    $sOutput .= '<TR CLASS="c">';
    $sOutput .= '<TD CLASS="c" COLSPAN="100">';
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen();
    $iSeparator = 0;
    if( !$this->bIsPopup )
    {
      $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( is_null( $this->sLabelBack ) ? PHP_APE_HTML_Data_View::$asResources['label.back'] : $this->sLabelBack, 'S-back', "javascript:window.history.back();", is_null( $this->sTooltipBack ) ? PHP_APE_HTML_Data_View::$asResources['tooltip.back'] : $this->sTooltipBack, null, false, false );
      if( ($this->roResultSet instanceof PHP_APE_Data_isDetailAble) and $this->roResultSet->isDetailAuthorized() )
      {
        $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
        $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( is_null( $this->sLabelDetail ) ? PHP_APE_HTML_Data_View::$asResources['label.detail'] : $this->sLabelDetail, 'S-detail', "javascript:PHP_APE_DR_BasicForm_do('".$this->sRID."','detail');", is_null( $this->sTooltipDetail ) ? PHP_APE_HTML_Data_View::$asResources['tooltip.detail'] : $this->sTooltipDetail, null, true, false );
      }
      if( ($this->roResultSet instanceof PHP_APE_Data_isListAble) and $this->roResultSet->isListAuthorized() )
      {
        $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
        $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( is_null( $this->sLabelList ) ? PHP_APE_HTML_Data_View::$asResources['label.list'] : $this->sLabelList, 'S-list', "javascript:PHP_APE_DR_BasicForm_do('".$this->sRID."','list');", is_null( $this->sTooltipList ) ? PHP_APE_HTML_Data_View::$asResources['tooltip.list'] : $this->sTooltipList, null, true, false );
      }
    }
    else
    {
      $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( is_null( $this->sLabelClose ) ? PHP_APE_HTML_Data_View::$asResources['label.close'] : $this->sLabelClose, 'S-close', "javascript:window.close();", is_null( $this->sTooltipClose ) ? PHP_APE_HTML_Data_View::$asResources['tooltip.close'] : $this->sTooltipClose, null, false, false );
    }
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();
    $sOutput .= '</TD>';
    $sOutput .= '</TR>'."\r\n";
    return $sOutput;
  }

  /** Returns the data form view's <SAMP>FORM</SAMP> prefix
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
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FORM: PHP_APE_HTML_Data_BasicForm::htmlForm(Prefix) - BEGIN (".$this->sRID.") -->\r\n";
    if( $this->bUploadAllow )
      $sOutput .= '<FORM NAME="PHP_APE_DR_'.$this->sRID.'" ACTION="'.$sURL.'" METHOD="post" ENCTYPE="multipart/form-data">'."\r\n";
    else
      $sOutput .= '<FORM NAME="PHP_APE_DR_'.$this->sRID.'" ACTION="'.$sURL.'" METHOD="post">'."\r\n";
    $sOutput .= '<INPUT TYPE="hidden" NAME="PHP_APE_DR_'.$this->sRID.'" DISABLED />'."\r\n";
    if( $this->bIsPopup )
      $sOutput .= '<INPUT TYPE="hidden" NAME="__POPUP" VALUE="yes" />'."\r\n";
    $sOutput .= '<INPUT TYPE="hidden" NAME="__PK" VALUE="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $this->mPrimaryKey ).'" />'."\r\n";
    $sOutput .= '<INPUT TYPE="hidden" NAME="__FROM" VALUE="'.$this->roController->getDestination().'" />'."\r\n";
    $sOutput .= '<INPUT TYPE="hidden" NAME="__TO" DISABLED />'."\r\n";
    if( is_array( $this->amPassthruVariables ) )
      foreach( $this->amPassthruVariables as $sName => $sValue )
        if( !is_null($sValue) ) $sOutput .= '<INPUT TYPE="hidden" NAME="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $sName ).'" VALUE="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $sValue ).'" />'."\r\n";
    return $sOutput;
  }

  /** Returns the data form view's content
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>, <SAMP>PHP_APE_Auth_AuthorizationException</SAMP>.</P>
   *
   * @return string
   */
  protected function _htmlData()
  {
    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_BasicForm::htmlData - DATA (".$this->sRID.") -->\r\n";

    // Data
    $this->bHasError = false;
    $this->bHasRequired = false;
    $iRow = 0;
    $roArgumentSet =& $this->roFunction->useArgumentSet();
    foreach( $this->amDisplayedKeys as $mKey )
    {
      $bIsArgument = false;

      // ... argument
      if( $roArgumentSet->isElementID( $mKey ) )
      {
        $sAltClassSuffix = ( $iRow++ % 2 ) ? '2' : null;
        $roArgument =& $roArgumentSet->useElementByID( $mKey );
        $iArgumentMeta = $roArgument->getMeta();
        if( !( $iArgumentMeta & PHP_APE_Data_Argument::Feature_HideInForm ) or
            ( $iArgumentMeta & ( PHP_APE_Data_Argument::Feature_ShowInForm | PHP_APE_Data_Argument::Feature_RequireInForm ) ) )
        {
          if( !( $iArgumentMeta & PHP_APE_Data_Argument::Value_Preset ) and 
              ( $this->roResultSet instanceof PHP_APE_Data_isDetailAbleResultSet ) and
              $this->roResultSet->isElement( $mKey ) )
            $roArgument->useContent()->setValue( $this->roResultSet->useElement( $mKey )->useContent()->getValue() );
          $sOutput .= '<TR CLASS="d">';
          $sOutput .= '<TD CLASS="l'.$sAltClassSuffix.'"';
          $sOutput .= ' TITLE="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $roArgument->getDescription() ).'" STYLE="CURSOR:help;"';
          $sOutput .= '>';
          $sOutput .= PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $roArgument->getName() );
          $sOutput .= '&nbsp;:</TD>';
          $sOutput .= '<TD CLASS="i'.$sAltClassSuffix.'">';
          $sOutput .= $this->_htmlArgument( $mKey );
          $sOutput .= '</TD>';
          $sOutput .= '</TR>'."\r\n";
          $bIsArgument = true;
        }
      }

      // ... field
      if( !$bIsArgument )
      {
        $roElement =& $this->roResultSet->useElement( $mKey );
        if( $roElement->useContent()->isEmpty() and ( $roElement instanceof PHP_APE_Data_hasMeta ) )
        {
          $iElementMeta = $roElement->getMeta();
          if( ( $iElementMeta & PHP_APE_Data_Field::Feature_HideIfEmpty ) and !( $iElementMeta & PHP_APE_Data_Field::Feature_ShowIfEmpty ) )
            continue;
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
    }

    // End
    return $sOutput;
  }

  /** Returns the data form view's element
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

  /** Returns the data form view's input argument
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   *
   * @param mixed $mKey Data element key
   * @return string
   */
  protected function _htmlArgument( $mKey )
  {
    // Retrieve argument/element
    $roArgumentSet =& $this->roFunction->useArgumentSet();
    $roArgument =& $roArgumentSet->useElementByID( $mKey );
    $mArgumentID = $roArgument->getID();
    $sArgumentRID = PHP_APE_HTML_Data_Any::makeRID( $mArgumentID );
    $iArgumentMeta = $roArgument->getMeta();
    $roData =& $roArgument->useContent();
    $rasRequestData =& $this->roController->useRequestData();
    if( array_key_exists( $mKey, $rasRequestData ) )
      $mArgumentValue = $mArgumentValueFormatted = $rasRequestData[$mKey];
    else
    {
      $mArgumentValue = $roData->getValue();
      $mArgumentValueFormatted = $roData->getValueFormatted();
    }

    // HTML
    $oDataSpace = new PHP_APE_DataSpace_HTML();

    // Class
    $sClass = null;
    $bIsError = false;
    $bIsRequired = false;
    if( $iArgumentMeta & PHP_APE_Data_Argument::Value_Lock )
      $sClass = 'locked';
    elseif( array_key_exists( $mArgumentID, $this->getErrors() ) )
    {
      $sClass = 'invalid';
      $bIsError = true;
    }
    elseif( $iArgumentMeta & PHP_APE_Data_Argument::Feature_RequireInForm )
    {
      $sClass = 'required';
      $bIsRequired = true;
    }

    // Output
    $sOutput = null;
    $bOutputHelp = false;
    $bOutputSampleAndConstraints = true;
    $bOutputError = false;
    $bOutputRequired = false;

    // ... override
    $sOutput_argument = null;
    if( is_null( $sOutput_argument ) and ( $this->roFunction instanceof PHP_APE_HTML_hasOutputHandler ) )
      $sOutput_argument = $this->roFunction->getHTMLOutput( $mKey );
    if( is_null( $sOutput_argument ) and ( $roArgument instanceof PHP_APE_HTML_hasOutputHandler ) )
      $sOutput_argument = $roArgument->getHTMLOutput();

    // ... input
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen( 'WIDTH:1px;', 'WIDTH:100% !important;' );
    if( is_null( $sOutput_argument ) )
    {
      if( ( $roArgument instanceof PHP_APE_Data_hasChoices ) or ( $roData instanceof PHP_APE_Type_Boolean ) )
      { // SELECT
        $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'WIDTH:auto; PADDING-RIGHT:2px !important; VERTICAL-ALIGN:top;', false );
        $sOutput .= '<SELECT ID="PHP_APE_DR_'.$this->sRID.'_'.$sArgumentRID.'_input"';
        if( $sClass )
          $sOutput .= ' CLASS="'.$sClass.'"';
        $sOutput .= ' NAME="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $mArgumentID ).'"';
        if( $iArgumentMeta & PHP_APE_Data_ChoicesAbleArgument::Value_Multiple )
          $sOutput .= ' MULTIPLE';
        if( $iArgumentMeta & PHP_APE_Data_Argument::Value_Lock )
          $sOutput .= ' DISABLED';
        else
        {
          if( $iArgumentMeta & PHP_APE_Data_Argument::Feature_SubmitOnChange )
            $sOutput .= ' ONCHANGE="javascript:PHP_APE_DR_BasicForm_onKeyPress('.$this->sRID.',event)"';
          $sOutput .= ' ONFOCUS="javascript:PHP_APE_EL_showDelayed(\'PHP_APE_DR_'.$this->sRID.'_'.$sArgumentRID.'_help\',350,3500)"';
          $sOutput .= ' ONBLUR="javascript:PHP_APE_EL_hideDelayed(\'PHP_APE_DR_'.$this->sRID.'_'.$sArgumentRID.'_help\',100)"';
          $bOutputHelp = true;
          $bOutputSampleAndConstraints = false;
        }
        $sOutput .= '>';
        if( $roArgument instanceof PHP_APE_Data_hasChoices )
        {
          $asChoices = $roArgument->getChoices();
          $mValue = $mArgumentValue;
        }
        else
        {
          $oBoolean_F = new PHP_APE_Type_Boolean( false );
          $oBoolean_T = new PHP_APE_Type_Boolean( true );
          $asChoices = array( 'false' => $oBoolean_F->getValueFormatted(), 'true' => $oBoolean_T->getValueFormatted() );
          $mValue = $mArgumentValue ? 'true' : 'false';
        }
        $bChoiceSelected = false;
        foreach( $asChoices as $mChoiceValue => $sChoiceName )
        {
          $sOutput .= '<OPTION VALUE="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $mChoiceValue ).'"';
          if( $mChoiceValue == $mValue )
          {
            $sOutput .= ' SELECTED';
            $bChoiceSelected = true;
          }
          $sOutput .= '>'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $sChoiceName ).'</OPTION>';
        }
        if( !$bChoiceSelected )
          $sOutput .= '<OPTION VALUE="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $mValue ).'" SELECTED>'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $mValue ).'</OPTION>';
        $sOutput .= '</SELECT>';
      }
      elseif( $roData instanceof PHP_APE_Type_Text )
      { // TEXTAREA
        $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'WIDTH:auto; PADDING-RIGHT:2px !important; VERTICAL-ALIGN:top;', false );
        $sOutput .= '<TEXTAREA ID="PHP_APE_DR_'.$this->sRID.'_'.$sArgumentRID.'_input"';
        $sOutput .= ' NAME="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $mArgumentID ).'"';
        if( $sClass )
          $sOutput .= ' CLASS="'.$sClass.'"';
        if( $roData->hasConstraints() )
        {
          $iMaxLength = $roData->getConstraints()->getElement( 'maximum' )->getContent()->getValue();
          if( is_numeric( $iMaxLength ) and $iMaxLength > 0 )
            $sOutput .= ' ONKEYPRESS="javascript:PHP_APE_IN_TextArea_contrainLength(this,'.$iMaxLength.')"';
        }
        if( $iArgumentMeta & PHP_APE_Data_Argument::Value_Lock )
          $sOutput .= ' DISABLED';
        else
        {
          $sOutput .= ' ONFOCUS="javascript:PHP_APE_EL_showDelayed(\'PHP_APE_DR_'.$this->sRID.'_'.$sArgumentRID.'_help\',350,3500)"';
          $sOutput .= ' ONBLUR="javascript:PHP_APE_EL_hideDelayed(\'PHP_APE_DR_'.$this->sRID.'_'.$sArgumentRID.'_help\',100)"';
          $bOutputHelp = true;
        }
        $sOutput .= '>';
        $sOutput .= PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $mArgumentValueFormatted );
        $sOutput .= '</TEXTAREA>';
      }
      else 
      { // INPUT
        if( $roData instanceof PHP_APE_Type_Date and !($iArgumentMeta & PHP_APE_Data_Argument::Value_Lock) )
        {
          $sOutput .= '<DIV';
          $sOutput .= ' STYLE="MARGIN-RIGHT:2px !important; BACKGROUND:transparent !important; CURSOR:pointer;"';
          $sOutput .= ' ONCLICK="javascript:'.PHP_APE_HTML_Components::javascriptDateChooser( 'PHP_APE_DR_'.$this->sRID.'_'.$sArgumentRID.'_input', $mArgumentValue ).'"';
          $sOutput .= '>';
          $sOutput .= PHP_APE_HTML_SmartTags::htmlIcon( 'S-date' );
          $sOutput .= '</DIV>';
        }

        $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'WIDTH:auto; PADDING-RIGHT:2px !important; VERTICAL-ALIGN:top;', false );
        if( ( $roData instanceof PHP_APE_Type_FileFromUpload ) and !is_null( $this->iUploadMaxSize ) )
          $sOutput .= '<INPUT TYPE="hidden" NAME="MAX_FILE_SIZE" VALUE="'.$this->iUploadMaxSize.'" />'."\r\n";
        $sOutput .= '<INPUT ID="PHP_APE_DR_'.$this->sRID.'_'.$sArgumentRID.'_input"';
        $sOutput .= ' NAME="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $mArgumentID ).'"';
        if( $sClass )
          $sOutput .= ' CLASS="'.$sClass.'"';
      
        if( $roData instanceof PHP_APE_Type_String )
        {
          if( $roData instanceof PHP_APE_Type_FileFromUpload )
            $sOutput .= ' TYPE="file"';
          else
          {
            if( $roData instanceof PHP_APE_Type_Password )
              $sOutput .= ' TYPE="password"';
            else
              $sOutput .= ' TYPE="text"';
            if( $roData->hasConstraints() )
            {
              $iMaxLength = $roData->getConstraints()->getElement( 'maximum' )->getContent()->getValue();
              if( is_numeric( $iMaxLength ) and $iMaxLength > 0 )
                $sOutput .= ' MAXLENGTH="'.$iMaxLength.'"';
            }
          }
        }
        else
          $sOutput .= ' TYPE="text"';

        $sOutput .= ' VALUE="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $mArgumentValueFormatted ).'"';
        if( $iArgumentMeta & PHP_APE_Data_Argument::Value_Lock )
          $sOutput .= ' DISABLED';
        else
        {
          if( $iArgumentMeta & PHP_APE_Data_Argument::Feature_SubmitOnChange )
            $sOutput .= ' ONCHANGE="javascript:PHP_APE_DR_BasicForm_onKeyPress('.$this->sRID.',event)"';
          $sOutput .= ' ONFOCUS="javascript:PHP_APE_EL_showDelayed(\'PHP_APE_DR_'.$this->sRID.'_'.$sArgumentRID.'_help\',350,3500)"';
          $sOutput .= ' ONBLUR="javascript:PHP_APE_EL_hideDelayed(\'PHP_APE_DR_'.$this->sRID.'_'.$sArgumentRID.'_help\',100)"';
          $bOutputHelp = true;
        }

        $sOutput .= ' />';
      }
    }
    else
    {
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'WIDTH:auto; PADDING-RIGHT:2px !important; VERTICAL-ALIGN:top;', false );
      $sOutput .= '<DIV';
      if( $sClass )
        $sOutput .= ' CLASS="'.$sClass.'"';
      $sOutput .= '>';
      $sOutput .= $sOutput_argument;
      $sOutput .= '</DIV>';
    }

    // ... error
    if( $bIsError )
    {
      $this->bHasError = true;
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'WIDTH:1px; PADDING-RIGHT:2px !important; VERTICAL-ALIGN:top;', false );
      $sOutput .= '<DIV';
      $sOutput .= ' STYLE="BACKGROUND:transparent !important; CURSOR:help;"';
      $sOutput .= ' ONMOUSEOVER="javascript:PHP_APE_EL_showDelayed(\'PHP_APE_DR_'.$this->sRID.'_'.$sArgumentRID.'_error\',100)"';
      $sOutput .= ' ONMOUSEOUT="javascript:PHP_APE_EL_hideDelayed(\'PHP_APE_DR_'.$this->sRID.'_'.$sArgumentRID.'_error\',100)"';
      $sOutput .= '>';
      $sOutput .= PHP_APE_HTML_SmartTags::htmlIcon( 'S-error' );
      $sOutput .= '</DIV>';
      $bOutputError = true;
    }
    elseif( $bIsRequired )
    {
      $this->bHasRequired = true;
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( 'WIDTH:1px; PADDING-RIGHT:2px !important; VERTICAL-ALIGN:top;', false );
      $sOutput .= '<DIV';
      $sOutput .= ' STYLE="BACKGROUND:transparent !important; CURSOR:help;"';
      $sOutput .= ' ONMOUSEOVER="javascript:PHP_APE_EL_showDelayed(\'PHP_APE_DR_'.$this->sRID.'_'.$sArgumentRID.'_required\',100)"';
      $sOutput .= ' ONMOUSEOUT="javascript:PHP_APE_EL_hideDelayed(\'PHP_APE_DR_'.$this->sRID.'_'.$sArgumentRID.'_required\',100)"';
      $sOutput .= '>';
      $sOutput .= PHP_APE_HTML_SmartTags::htmlIcon( 'S-required' );
      $sOutput .= '</DIV>';
      $bOutputRequired = true;
    }

    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();

    // ... help
    if( $bOutputHelp )
    {
      $sOutput .= '<DIV CLASS="ih" ID="PHP_APE_DR_'.$this->sRID.'_'.$sArgumentRID.'_help" STYLE="DISPLAY:none;">';
      $iSeparator = 0;
      if( ( $roArgument instanceof PHP_APE_Data_hasDescription ) and $roArgument->hasDescription() )
      {
        if( $iSeparator++ ) $sOutput .= '<BR/>';
        $sOutput .= '&middot;&nbsp;'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $roArgument->getDescription() );
      }
      if( $bOutputSampleAndConstraints )
      {
        if( $roData->hasSample() )
        {
          if( $iSeparator++ ) $sOutput .= '<BR/>';
          $sOutput .= ':&nbsp;'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $roData->getSampleString() );
        }
        if( $roData->hasConstraints() )
        {
          if( $iSeparator++ ) $sOutput .= '<BR/>';
          $sOutput .= '!&nbsp;'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $roData->getConstraintsString() );
        }
      }
      if( ( $roArgument instanceof PHP_APE_Data_hasChoices ) and ( $iArgumentMeta & PHP_APE_Data_ChoicesAbleArgument::Value_Multiple ) )
      {
        if( $iSeparator++ ) $sOutput .= '<BR/>';
        $sOutput .= '*&nbsp;'.PHP_APE_HTML_Data_View::$asResources['tooltip.selectmutiple'];
      }
      $sOutput .= '</DIV>';
    }

    // ... error
    if( $bOutputError )
    {
      $sOutput .= '<DIV CLASS="ie" ID="PHP_APE_DR_'.$this->sRID.'_'.$sArgumentRID.'_error" STYLE="DISPLAY:none;">';
      $sOutput .= PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $this->asErrors[$mArgumentID] );
      $sOutput .= '</DIV>';
    }

    // ... error
    if( $bOutputRequired )
    {
      $sOutput .= '<DIV CLASS="ir" ID="PHP_APE_DR_'.$this->sRID.'_'.$sArgumentRID.'_required" STYLE="DISPLAY:none;">';
      $sOutput .= PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( PHP_APE_HTML_Data_View::$asResources['tooltip.required'] );
      $sOutput .= '</DIV>';
    }

    // ... end
    return $sOutput;
  }

  /** Returns the data form view's <SAMP>FORM</SAMP> suffix
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
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- FORM: PHP_APE_HTML_Data_BasicForm::htmlForm(Suffix) - END (".$this->sRID.") -->\r\n";
    return $sOutput;
  }

  /** Returns the data form view's footer
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   *
   * @return string
   */
  protected function _htmlFooter()
  {
    // Output
    $sOutput = null;
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_BasicForm::htmlFooter - CONTROLS (".$this->sRID.") -->\r\n";
    $sOutput .= '<TR CLASS="c">';
    $sOutput .= '<TD CLASS="c" COLSPAN="100">';
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen();
    if( $this->roResultSet instanceof PHP_APE_Data_isDetailAbleResultSet )
      $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( is_null( $this->sLabelUpdate ) ? PHP_APE_HTML_Data_View::$asResources['label.update'] : $this->sLabelUpdate, 'S-update', "javascript:PHP_APE_DR_BasicForm_do('".$this->sRID."','update');", is_null( $this->sTooltipUpdate ) ? PHP_APE_HTML_Data_View::$asResources['tooltip.update'] : $this->sTooltipUpdate, null, true, false );
    else
      $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( is_null( $this->sLabelInsert ) ? PHP_APE_HTML_Data_View::$asResources['label.insert'] : $this->sLabelInsert, 'S-insert', "javascript:PHP_APE_DR_BasicForm_do('".$this->sRID."','insert');", is_null( $this->sTooltipInsert ) ? PHP_APE_HTML_Data_View::$asResources['tooltip.insert'] : $this->sTooltipInsert, null, true, false );
    if( $this->bHasRequired )
    {
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
      $sOutput .= '<INPUT CLASS="required" TYPE="text" STYLE="WIDTH:25px;" DISABLED />';
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( null, false );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( '/' );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( null, false );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( '= '.PHP_APE_HTML_Data_View::$asResources['label.helprequired'], 'S-required', null, PHP_APE_HTML_Data_View::$asResources['tooltip.helprequired'] );
    }
    if( $this->bHasError )
    {
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
      $sOutput .= '<INPUT CLASS="invalid" TYPE="text" STYLE="WIDTH:25px;" DISABLED />';
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( null, false );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( '/' );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( null, false );
      $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( '= '.PHP_APE_HTML_Data_View::$asResources['label.helperror'], 'S-error', null, PHP_APE_HTML_Data_View::$asResources['tooltip.helperror'] );
    }
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();
    $sOutput .= '</TD>';
    $sOutput .= '</TR>'."\r\n";
    return $sOutput;
  }

  /** Closes the data form view
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
    if( PHP_APE_DEBUG ) $sOutput .= "\r\n<!-- TABLE: PHP_APE_HTML_Data_BasicForm::htmlClose - END (".$this->sRID.") -->\r\n";
    return $sOutput;
  }

  /** Sets this data form view's displayable elements
   */
  protected function _setDisplayedKeys()
  {
    if( !is_array( $this->amDisplayableKeys ) ) $this->setDisplayableKeys();
    $this->amDisplayedKeys = $this->amDisplayableKeys;
  }

  /** Check uploads parameters
   */
  protected function _checkUploads()
  {
    // Usage
    if( is_null( $this->bUploadAllow ) )
    {
      $this->bUploadAllow = false;
      $roArgumentSet =& $this->roFunction->useArgumentSet();
      foreach( $this->amDisplayableKeys as $mKey )
        if( $roArgumentSet->isElementID( $mKey ) and ( $roArgumentSet->useElementbyID( $mKey )->useContent() instanceof PHP_APE_Type_FileFromUpload ) )
        {
          $this->bUploadAllow = true;
          break;
        }
    }

    // Maximum file size
    if( is_null( $this->iUploadMaxSize ) )
    {
      $this->iUploadMaxSize = (integer)PHP_APE_HTML_Data_View::$roEnvironment->getVolatileParameter( 'php_ape.html.data.form.upload.maxsize' );
    }
  }


  /*
   * METHODS: public
   ********************************************************************************/

  /** Sets this data form view's displayed elements
   *
   * @param array|mixed $amDisplayableKeys Displayed elements keys
   */
  protected function setDisplayableKeys( $amDisplayableKeys = null )
  {
    // Sanitize input
    $roArgumentSet =& $this->roFunction->useArgumentSet();

    // ... displayable elements
    $amArgumentIDs = $roArgumentSet->getElementsIDs(); // Argument set (ordered set) keys are purely numerical; we must use the variables IDs instead
    $amResultSetKeys = array();
    if( $this->roResultSet instanceof PHP_APE_Data_isDetailAbleResultSet )
      $amResultSetKeys = $this->roResultSet->getElementsKeys();
    $amDisplayedKeys = array_unique( array_merge( $amResultSetKeys, $amArgumentIDs ) );
    if( !is_array( $amDisplayableKeys ) )
      $amDisplayableKeys = $amDisplayedKeys;
    else
      $amDisplayableKeys = array_intersect( $amDisplayableKeys, $amDisplayedKeys );

    // Save view parameters
    $this->amDisplayableKeys = $amDisplayableKeys;
  }

  /** Returns the data form view
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Auth_AuthorizazionException</SAMP>, <SAMP>PHP_APE_HTML_Data_Exception</SAMP>.</P>
   *
   * @return string
   */
  public function html()
  {
    // Check

    // ... authorization
    if( ( $this->roFunction instanceof PHP_APE_Data_hasAuthorization ) and !$this->roFunction->hasAuthorization() )
      throw new PHP_APE_Auth_AuthorizationException( __METHOD__, 'Permission denied; Class: '.get_class( $this->roFunction ) );
    if( ( $this->roResultSet instanceof PHP_APE_Data_hasAuthorization ) and !$this->roResultSet->hasAuthorization() )
      throw new PHP_APE_Auth_AuthorizationException( __METHOD__, 'Permission denied; Class: '.get_class( $this->roResultSet ) );

    // ... arguments
    if( !$this->roFunction->hasArgumentSet() )
      throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'Missing (undefined) arguments; Class: '.get_class( $this->roFunction ) );

    // Set displayable elements (keys)
    $this->_setDisplayedKeys();

    // Check uploads parameters
    $this->_checkUploads();

    // Popup usage
    $this->bIsPopup = $this->roController->isPopup();

    // Query
    $bResetQuery = false;
    if( ( $this->roResultSet instanceof PHP_APE_Data_isDetailAbleResultSet ) and !$this->roResultSet->isQueried() )
    {
      $this->roResultSet->queryEntries( $this->iQueryMeta );
      if( !$this->roResultSet->nextEntry() )
        throw new PHP_APE_HTML_Data_Exception( __METHOD__, 'No entry found (empty result set)' );
      $bResetQuery = true;
    }

    // Output
    $sOutput = null;

    // ... form
    $sOutput .= '<DIV CLASS="APE-detail">'."\r\n";

    // ... data
    $sOutput .= '<DIV CLASS="d">'."\r\n";
    $sOutput .= $this->_htmlJavaScript();
    $sOutput .= $this->_htmlOpen();
    if( $this->bUseHeader ) $sOutput .= $this->_htmlHeader();
    $sOutput .= $this->_htmlFormPrefix();
    $sOutput .= $this->_htmlData();
    $sOutput .= $this->_htmlFormSuffix();
    if( $this->bUseFooter ) $sOutput .= $this->_htmlFooter();
    $sOutput .= $this->_htmlClose();
    $sOutput .= '</DIV>'."\r\n";

    // ... form (end)
    $sOutput .= '</DIV>'."\r\n";

    // Reset query
    if( $bResetQuery )
      $this->roResultSet->resetQuery();

    // End
    return $sOutput;
  }

  /** Returns this data form view's submission JavaScript
   *
   * @param string $sDestination Submission destination (action)
   * @param boolean $bLockForm Lock form after action (and prevent multiple submissions)
   * @return string
   */
  public function getSubmitJScript( $sDestination = null, $bLockForm = true )
  {
    // Output
    $sOutput = 'PHP_APE_DR_'.$this->sRID.'_do(';
    if( !empty( $sDestination ) )
    {
      $sDestination = PHP_APE_Type_Index::parseValue( $sDestination );
      $sOutput .= '\''.PHP_APE_HTML_Data_View::$oDataSpace_JavaScript->encodeData( $sDestination ).'\'';
    }
    else
      $sOutput .= 'null';
    $sOutput .= ','.($bLockForm?'true':'false').')';

    // End
    return $sOutput;
  }

}
