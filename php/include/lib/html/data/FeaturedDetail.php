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

/** (Meta-)Featured detail view display object
 *
 * @package PHP_APE_HTML
 * @subpackage Page
 */
class PHP_APE_HTML_Data_FeaturedDetail
extends PHP_APE_HTML_Data_BasicDetail
{

  /*
   * FIELDS
   ********************************************************************************/

  /** Use display preferences
   * @var boolean */
  protected $bUseDisplayPreferences;

  /** Allow fields re-ordering (using drag 'n drop)
   * @var boolean */
  protected $bAllowReorder;


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
    // Parent constructor
    parent::__construct( $roController, $roResultSet, $mPrimaryKey, $iQueryMeta, $amPassthruVariables );

    // Preferences
    $this->bUseDisplayPreferences = true;
    $this->bAllowReorder = true;
  }


  /*
   * METHODS: preferences
   ********************************************************************************/
  
  /** Set preference for displayable fields
   * @param boolean $bUseDisplayPreferences Use display preferences (and display corresponding HTML element)
   * @param boolean $bAllowReorder Allow fields re-ordering (using drag 'n drop) */
  public function prefUseDisplayPreferences( $bUseDisplayPreferences, $bAllowReorder = true )
  {
    $this->bUseDisplayPreferences = (boolean)$bUseDisplayPreferences;
    $this->bAllowReorder = (boolean)$bAllowReorder;
  }
  
  /** Set preference for fields order
   * @param boolean $bUseOrderPreferences Use order preferences (and display corresponding HTML element) */
  public function prefUseOrderPreferences( $bUseOrderPreferences )
  {
    $this->bUseOrderPreferences = (boolean)$bUseOrderPreferences;
  }


  /*
   * METHODS: protected
   ********************************************************************************/

  /** Returns the detail's elements display preferences
   *
   * <P><B>USAGE:</B> This HTML element allows the user to choose which element to display, and to order them using drag-n-drop.
   * The chosen settings are then saved as preferences (and persistent between page calls).</P>
   *
   * @return string
   */
  protected function _htmlPreferencesDisplay()
  {
    // Retrieve resource identifier (ID)
    $sResultSetRID = PHP_APE_HTML_Data_Any::makeRID( get_class( $this->roResultSet ) );

    // Display preferences
    $amDisplayableKeys = is_array( $this->amDisplayableKeys ) ? $this->amDisplayableKeys : $this->roResultSet->getElementsKeys();
    if( $this->roResultSet instanceof PHP_APE_Data_isMetaDataSet )
      $amDisplayableKeys = array_diff( $amDisplayableKeys,
                                       array_diff( $this->roResultSet->getElementsKeysPerMeta( PHP_APE_Data_Field::Feature_HideInDetail ),
                                                   $this->roResultSet->getElementsKeysPerMeta( PHP_APE_Data_Field::Feature_RequireInDetail ) ) );

    // ... enable element
    $sPreferenceID_Enabled = 'php_ape.html.data.detail.display.enabled.'.$sResultSetRID;
    $amDisplayableKeys_Enabled = @PHP_APE_HTML_Data_View::$roEnvironment->getUserParameter( $sPreferenceID_Enabled );
    if( !is_null( $amDisplayableKeys_Enabled ) )
      $amDisplayableKeys_Enabled = explode( ':', $amDisplayableKeys_Enabled );
    elseif( $this->roResultSet instanceof PHP_APE_Data_isMetaDataSet )
      $amDisplayableKeys_Enabled = array_diff( $amDisplayableKeys, $this->roResultSet->getElementsKeysPerMeta( PHP_APE_Data_Field::Feature_CollapseInDetail ) );
    else
      $amDisplayableKeys_Enabled = $amDisplayableKeys;

    // ... element order
    $sPreferenceID_Ordered = 'php_ape.html.data.detail.display.ordered.'.$sResultSetRID;
    $amDisplayableKeys_Ordered = @PHP_APE_HTML_Data_View::$roEnvironment->getUserParameter( $sPreferenceID_Ordered );
    if( !is_null( $amDisplayableKeys_Ordered ) )
      $amDisplayableKeys_Ordered = explode( ':', $amDisplayableKeys_Ordered );
    if( is_array( $amDisplayableKeys_Ordered ) )
    {
      $amDisplayableKeys_Ordered = array_intersect( $amDisplayableKeys_Ordered, $amDisplayableKeys );
      $amDisplayableKeys = array_merge( $amDisplayableKeys_Ordered, array_diff( $amDisplayableKeys, $amDisplayableKeys_Ordered) );
    }

    // Form
    $sPreferenceID_Enabled = PHP_APE_HTML_Data_View::$roEnvironment->encryptData( $sPreferenceID_Enabled );
    $sPreferenceID_Ordered = PHP_APE_HTML_Data_View::$roEnvironment->encryptData( $sPreferenceID_Ordered );
    $sQuery = $_SERVER['QUERY_STRING'];

    // Output
    $sOutput = null;
    $sOutput .= '<DIV CLASS="APE-dragsort">'."\r\n";
    $sOutput .= '<FORM NAME="PHP_APE_PD_'.$this->sRID.'" ACTION="'.$_SERVER['PHP_SELF'].( $sQuery ? '?'.$sQuery : null ).'" METHOD="get">'."\r\n";
    $sOutput .= '<INPUT TYPE="hidden" NAME="PHP_APE_P" DISABLED />'."\r\n";
    $sOutput .= '<INPUT TYPE="hidden" NAME="'.$sPreferenceID_Enabled.'" />'."\r\n";
    $sOutput .= '<INPUT TYPE="hidden" NAME="'.$sPreferenceID_Ordered.'" />'."\r\n";
    if( $this->bAllowReorder ) $sOutput .= '<UL ID="PD_'.$this->sRID.'_list">'."\r\n";
    else $sOutput .= '<UL CLASS="disabled" ID="PD_'.$this->sRID.'_list">'."\r\n";
    foreach( $amDisplayableKeys as $mKey ) {
      $oElement = $this->roResultSet->getElement( $mKey );
      $sOutput .= '<LI INDEX="'.$mKey.'">';
      $sOutput .= '<INPUT TYPE="checkbox" CLASS="checkbox"'.( in_array( $mKey, $amDisplayableKeys_Enabled ) ? ' CHECKED' : null ).'/>&nbsp;&middot;&nbsp;<SPAN CLASS="label"';
      if( ( $oElement instanceof PHP_APE_Data_hasDescription ) and $oElement->hasDescription() )
        $sOutput .= 'TITLE="'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $oElement->getDescription() ).'" STYLE="CURSOR:help;"';
      $sOutput .= '>';
      if( ( $oElement instanceof PHP_APE_Data_hasName ) and $oElement->hasName() )
        $sOutput .= PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $oElement->getName() );
      else
        $sOutput .= PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData( $mKey );
      $sOutput .= '</SPAN>';
      $sOutput .= '</LI>'."\r\n";
    }
    $sOutput .= '</UL>'."\r\n";
    $sOutput .= '</FORM>'."\r\n";
    $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
    if( $this->bAllowReorder )
      $sOutput .= PHP_APE_HTML_Tags::htmlJavaScript( 'ToolMan' );
    $sOutput .= '<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript"><!--'."\r\n";
    $sOutput .= 'function PHP_APE_PD_'.$this->sRID.'_submit()'."\r\n";
    $sOutput .= '{'."\r\n";
    $sOutput .= ' form = document.PHP_APE_PD_'.$this->sRID.';'."\r\n";
    $sOutput .= ' if( form.PHP_APE_P.disabled )'."\r\n";
    $sOutput .= ' {'."\r\n";
    $sOutput .= '  inputEnable = form.elements[\''.$sPreferenceID_Enabled.'\'];'."\r\n";
    $sOutput .= '  inputOrder = form.elements[\''.$sPreferenceID_Ordered.'\'];'."\r\n";
    $sOutput .= '  list = document.getElementById(\'PD_'.$this->sRID.'_list\');'."\r\n";
    $sOutput .= '  items = list.getElementsByTagName(\'LI\');'."\r\n";
    $sOutput .= '  inputOrder.value = \'\';'."\r\n";
    $sOutput .= '  for( i=0; i<items.length; i++ )'."\r\n";
    $sOutput .= '  {'."\r\n";
    $sOutput .= '   mElementKey = items[i].getAttribute(\'INDEX\');'."\r\n";
    $sOutput .= '   inputOrder.value += (inputOrder.value?\':\':\'\')+mElementKey;'."\r\n";
    $sOutput .= '   inputEnableCheckbox = items[i].getElementsByTagName(\'INPUT\')[0];'."\r\n";
    $sOutput .= '   if( inputEnableCheckbox.checked ) inputEnable.value += (inputEnable.value?\':\':\'\')+mElementKey;'."\r\n";
    $sOutput .= '   inputEnableCheckbox.disabled = true;'."\r\n";
    $sOutput .= '  }'."\r\n";
    $sOutput .= '  PHP_APE_IN_Form_get( form, form.PHP_APE_P );'."\r\n";
    $sOutput .= ' }'."\r\n";
    $sOutput .= '}'."\r\n";
    if( $this->bAllowReorder )
    {
      $sOutput .= 'function PD_'.$this->sRID.'_hook(item) { item.toolManDragGroup.verticalOnly(); }'."\r\n";
      $sOutput .= 'dragsort.makeListSortable(document.getElementById(\'PD_'.$this->sRID.'_list\'),PD_'.$this->sRID.'_hook);'."\r\n";
    }
    $sOutput .= '--></SCRIPT>'."\r\n";
    $sOutput .= '</DIV>'."\r\n";
    $sOutput .= PHP_APE_HTML_SmartTags::htmlZoneOpen();
    $sOutput .= PHP_APE_HTML_SmartTags::htmlButton( PHP_APE_HTML_Data_View::$asResources['label.commit'], 'S-commit', "javascript:PHP_APE_PD_".$this->sRID."_submit();", PHP_APE_HTML_Data_View::$asResources['tooltip.commit'], null, true, false, false )."\r\n";
    $sOutput .= PHP_APE_HTML_SmartTags::htmlZoneClose();
    return $sOutput;
  }

  /** Returns the HTML data detail's preferences frame (preferences control elements)
   *
   * @return string
   */
  protected function _htmlPreferencesFrame()
  {
    // Preferences identifiers
    $iControl_Display = @PHP_APE_HTML_Data_View::$roEnvironment->getUserParameter( 'php_ape.html.data.detail.control.preferences.display' );

    // Check display
    if( !$this->bUseDisplayPreferences or ( !is_null( $iControl_Display ) and $iControl_Display < PHP_APE_HTML_SmartTags::Display_Minimized ) )
      return null;

    // Output
    $sOutput = null;
    $sOutput .= '<DIV CLASS="do-not-print">'."\r\n";
    $sOutput .= '<DIV CLASS="APE-detail">'."\r\n";
    $sOutput .= '<DIV CLASS="p">'."\r\n";

    // ... elements (enable/order)
    if( $this->bUseDisplayPreferences and
        ( is_null( $iControl_Display ) or $iControl_Display > PHP_APE_HTML_SmartTags::Display_Closed ) )
    {
      $sOutput .= PHP_APE_HTML_SmartTags::htmlFrameOpen();
      $sOutput .= PHP_APE_HTML_SmartTags::htmlFrameHeader( PHP_APE_HTML_Data_View::$asResources['title.preferences.display'], PHP_APE_HTML_SmartTags::Display_Control_All, 'php_ape.html.data.detail.control.preferences.display' );
      if( is_null( $iControl_Display ) or $iControl_Display > PHP_APE_HTML_SmartTags::Display_Minimized )
      {
        $sOutput .= PHP_APE_HTML_SmartTags::htmlFrameContentBegin();
        $sOutput .= PHP_APE_HTML_SmartTags::htmlZoneOpen();
        $sOutput .= PHP_APE_HTML_SmartTags::htmlLabel( PHP_APE_HTML_Data_View::$asResources['label.preferences.display.help'], 'S-help', null, PHP_APE_HTML_Data_View::$asResources['tooltip.preferences.display.help'], null, true, true )."\r\n";
        $sOutput .= PHP_APE_HTML_SmartTags::htmlZoneClose();
        $sOutput .= $this->_htmlPreferencesDisplay();
        $sOutput .= PHP_APE_HTML_SmartTags::htmlFrameContentEnd();
        $sOutput .= PHP_APE_HTML_SmartTags::htmlFrameFooter();
      }
      $sOutput .= PHP_APE_HTML_SmartTags::htmlFrameClose();
    }

    // End
    $sOutput .= '</DIV>'."\r\n";
    $sOutput .= '</DIV>'."\r\n";
    $sOutput .= '</DIV>'."\r\n";
    return $sOutput;
  }

  /** Returns the HTML data detail's preferences bar (frame activation controls)
   *
   * @return string
   */
  protected function _htmlPreferencesBar()
  {
    // Preferences identifiers
    $iControl_Display = @PHP_APE_HTML_Data_View::$roEnvironment->getUserParameter( 'php_ape.html.data.detail.control.preferences.display' );

    // Check display
    if( !$this->bUseDisplayPreferences or ( !is_null( $iControl_Display ) and $iControl_Display > PHP_APE_HTML_SmartTags::Display_Closed ) )
      return null;

    // Output
    $sOutput = null;
    $sOutput .= '<DIV CLASS="do-not-print">'."\r\n";
    $sOutput .= PHP_APE_HTML_SmartTags::htmlSeparator();
    $sOutput .= '<DIV STYLE="FLOAT:right;PADDING-RIGHT:2px;">'."\r\n";
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignOpen();
    $bColumnAdd = false;

    // ... elements (enable/order)
    if( $this->bUseDisplayPreferences and !is_null( $iControl_Display ) and $iControl_Display < PHP_APE_HTML_SmartTags::Display_Minimized )
    {
      if( $bColumnAdd ) $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd();
      $bColumnAdd = true;
      $sOutput .= '<P STYLE="WHITE-SPACE:nowrap;">&nbsp;'.PHP_APE_HTML_Data_View::$oDataSpace_HTML->encodeData(PHP_APE_HTML_Data_View::$asResources['label.preferences.display.show']).'&nbsp;</P>';
      $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignAdd( null, false );
      $sOutput .= PHP_APE_HTML_Components::htmlControlDisplay( 'php_ape.html.data.detail.control.preferences.display', PHP_APE_HTML_SmartTags::Display_Control_CloseOpen );
    }

    // End
    $sOutput .= PHP_APE_HTML_SmartTags::htmlAlignClose();
    $sOutput .= '</DIV>'."\r\n";
    $sOutput .= '<DIV STYLE="CLEAR:both;"></DIV>'."\r\n";
    $sOutput .= '</DIV>'."\r\n";
    return $sOutput;
  }

  /** Sets this data detail view's displayable elements (according to preferences)
   */
  protected function _setDisplayedKeys()
  {
    // Call parent method
    parent::_setDisplayedKeys();

    // Retrieve resource identifier (ID)
    $sResultSetRID = PHP_APE_HTML_Data_Any::makeRID( get_class( $this->roResultSet ) );

    // Display elements

    // ... elements
    $amRequiredKeys = array();
    $amHiddenKeys = array();
    if( $this->roResultSet instanceof PHP_APE_Data_isMetaDataSet )
    {
      $amRequiredKeys = $this->roResultSet->getElementsKeysPerMeta( PHP_APE_Data_Field::Feature_RequireInDetail );
      $amHiddenKeys = $this->roResultSet->getElementsKeysPerMeta( PHP_APE_Data_Field::Feature_HideInDetail );
    }
    $this->amDisplayedKeys = array_diff( $this->amDisplayedKeys, array_diff( $amHiddenKeys, $amRequiredKeys ) );

    // ... preferences
    if( $this->bUseDisplayPreferences )
    {
      // ... keys enable/hide
      $amDisplayableKeys_Enabled = @PHP_APE_HTML_Data_View::$roEnvironment->getUserParameter( 'php_ape.html.data.detail.display.enabled.'.$sResultSetRID );
      if( !is_null( $amDisplayableKeys_Enabled ) )
        $amDisplayableKeys_Enabled = explode( ':', $amDisplayableKeys_Enabled );
      elseif( $this->roResultSet instanceof PHP_APE_Data_isMetaDataSet )
        $amDisplayableKeys_Enabled = array_diff( $this->amDisplayedKeys, $this->roResultSet->getElementsKeysPerMeta( PHP_APE_Data_Field::Feature_CollapseInDetail ) );
      $amHiddenKeys = array_unique( array_merge( $amHiddenKeys, array_diff( $this->amDisplayedKeys, $amDisplayableKeys_Enabled ) ) );
      
      // ... keys order
      $amDisplayableKeys_Ordered = @PHP_APE_HTML_Data_View::$roEnvironment->getUserParameter( 'php_ape.html.data.detail.display.ordered.'.$sResultSetRID );
      if( !is_null( $amDisplayableKeys_Ordered ) )
        $amDisplayableKeys_Ordered = explode( ':', $amDisplayableKeys_Ordered );
      if( is_array( $amDisplayableKeys_Ordered ) )
        $this->amDisplayedKeys = array_intersect( array_merge( $amDisplayableKeys_Ordered, array_diff( $this->amDisplayedKeys, $amDisplayableKeys_Ordered) ), $this->amDisplayedKeys );
    }

    // ... all put together
    $this->amDisplayedKeys = array_diff( $this->amDisplayedKeys, array_diff( $amHiddenKeys, $amRequiredKeys ) );
  }


  /*
   * METHODS: public
   ********************************************************************************/

  /** Returns the meta-featured data detail view, including control elements
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

    // Output
    $sOutput = null;
    $sOutput .= PHP_APE_HTML_SmartTags::htmlColumnOpen( 'WIDTH:auto;' );

    // ... detail
    $sOutput .= parent::html();

    // ... preferences
    $sOutput .= PHP_APE_HTML_SmartTags::htmlColumnAdd( 'WIDTH:1px;' );
    $sOutput .= $this->_htmlPreferencesFrame();

    // ... detail (end)
    $sOutput .= PHP_APE_HTML_SmartTags::htmlColumnClose();

    // ... display controls
    $sOutput .= $this->_htmlPreferencesBar();

    // End
    return $sOutput;
  }

}
