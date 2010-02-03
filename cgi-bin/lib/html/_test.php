<?php // INDENTING (emacs/vi): -*- mode:php; tab-width:2; c-basic-offset:2; intent-tabs-mode:nil; -*- ex: set tabstop=2 expandtab:
/*
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
 */

/** TESTS
 *
 * <P><B>SYNOPSIS:</B> This script tests the definition and behaviour of the objects included in this package,
 * which should allow developers and users to verify the functionality of its resources,
 * and hopefully also gain a better understanding on how to implement or use them.</P>
 *
 * @package PHP_APE_HTML
 * @subpackage Tests
 */

/** Load PHP-APE */
echo "<H1>APE tests</H1>\r\n";
echo "<P><SPAN CLASS=\"important\">SYNOPSIS:</SPAN> This script tests the definition and behaviour of the objects included in this package,\r\n";
echo "which should allow developers and users to verify the functionality of its resources,\r\n";
echo "and hopefully also gain a better understanding on how to implement or use them.</P>\r\n";
require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
require_once( PHP_APE_ROOT.'/lib/html/load.php' );

// Tests
echo PHP_APE_HTML_SmartTags::htmlCSS();
echo '<DIV CLASS="APE">'."\r\n";

// Workspace
$roEnvironment = PHP_APE_HTML_WorkSpace::useEnvironment();
$iControlPreference_WorkSpace = @$roEnvironment->getUserParameter( 'php_ape.html.display.element.workspace' );
if( is_null( $iControlPreference_WorkSpace ) or $iControlPreference_WorkSpace > PHP_APE_HTML_SmartTags::Display_Closed )
{
  echo PHP_APE_HTML_SmartTags::htmlSpacer();
  echo PHP_APE_HTML_SmartTags::htmlFrameOpen();
  echo PHP_APE_HTML_SmartTags::htmlFrameHeader( 'APE HTML workspace', PHP_APE_HTML_SmartTags::Display_Control_All, 'php_ape.html.display.element.workspace' );
  if( is_null( $iControlPreference_WorkSpace ) or $iControlPreference_WorkSpace > PHP_APE_HTML_SmartTags::Display_Minimized )
  {
    echo PHP_APE_HTML_SmartTags::htmlFrameContentBegin();

    // ... static parameters
    echo "<H3>Static parameters (properties)</H3>\r\n";
    echo "<BLOCKQUOTE>\r\n";
    echo "<P>\r\n";
    $asNames = $roEnvironment->getStaticParametersNames();
    foreach( $asNames as $sName )
    {
      $mParameter = $roEnvironment->getStaticParameter( $sName );
      if( is_array( $mParameter ) ) foreach( $mParameter as $sKey => $sValue ) echo $sName.'['.$sKey.']: '.$sValue."<BR/>\r\n";
      else echo $sName.': '.$mParameter."<BR/>\r\n";
    }
    echo "</P>\r\n";
    echo "</BLOCKQUOTE>\r\n";

    // ... persistent parameters
    echo "<H3>Persistent parameters (configuration)</H3>\r\n";
    echo "<BLOCKQUOTE>\r\n";
    echo "<P>\r\n";
    $asNames = $roEnvironment->getPersistentParametersNames();
    foreach( $asNames as $sName )
    {
      $mParameter = $roEnvironment->getPersistentParameter( $sName );
      if( is_array( $mParameter ) ) foreach( $mParameter as $sKey => $sValue ) echo $sName.'['.$sKey.']: '.$sValue."<BR/>\r\n";
      else echo $sName.': '.$mParameter."<BR/>\r\n";
    }
    echo "</P>\r\n";
    echo "</BLOCKQUOTE>\r\n";

    // ... volatile parameters
    echo "<H3>Volatile parameters (settings)</H3>\r\n";
    echo "<BLOCKQUOTE>\r\n";
    echo "<P>\r\n";
    $asNames = $roEnvironment->getVolatileParametersNames();
    foreach( $asNames as $sName )
    {
      $mParameter = $roEnvironment->getVolatileParameter( $sName );
      if( is_array( $mParameter ) ) foreach( $mParameter as $sKey => $sValue ) echo $sName.'['.$sKey.']: '.$sValue."<BR/>\r\n";
      else echo $sName.': '.$mParameter."<BR/>\r\n";
    }
    echo "</P>\r\n";
    echo "</BLOCKQUOTE>\r\n";

    // ... user parameters
    echo "<H3>User parameters (preferences)</H3>\r\n";
    echo "<BLOCKQUOTE>\r\n";
    echo "<P>\r\n";
    $asNames = $roEnvironment->getUserParametersNames();
    foreach( $asNames as $sName )
    {
      $mParameter = $roEnvironment->getUserParameter( $sName );
      if( is_array( $mParameter ) ) foreach( $mParameter as $sKey => $sValue ) echo $sName.'['.$sKey.']: '.$sValue."<BR/>\r\n";
      else echo $sName.': '.$mParameter."<BR/>\r\n";
    }
    echo "</P>\r\n";
    echo "</BLOCKQUOTE>\r\n";

    echo PHP_APE_HTML_SmartTags::htmlFrameContentEnd();
    echo PHP_APE_HTML_SmartTags::htmlFrameFooter();
  }
  echo PHP_APE_HTML_SmartTags::htmlFrameClose();
}

// HTML tags
$iControlPreference_HTMLTags = @$roEnvironment->getUserParameter( 'php_ape.html.display.element.html_tags' );
if( is_null( $iControlPreference_HTMLTags ) or $iControlPreference_HTMLTags > PHP_APE_HTML_SmartTags::Display_Closed )
{
  echo PHP_APE_HTML_SmartTags::htmlSpacer();
  echo PHP_APE_HTML_SmartTags::htmlFrameOpen();
  echo PHP_APE_HTML_SmartTags::htmlFrameHeader( 'HTML Tags', PHP_APE_HTML_SmartTags::Display_Control_All, 'php_ape.html.display.element.html_tags' );
  if( is_null( $iControlPreference_HTMLTags ) or $iControlPreference_HTMLTags > PHP_APE_HTML_SmartTags::Display_Minimized )
  {
    echo PHP_APE_HTML_SmartTags::htmlFrameContentBegin();
      ?>
      <P>Hello World !</P>
         <P>This example/test page (hopefully) demonstrates the power of PHP-APE Smart Tags for producing graphically (visually) rich Graphical User Interfaces (GUIs) entirely controled through Cascading Style Sheet (CSS)</P>
                                                                                                            <P>Some floating data:</P>
                                                                                                            <DIV CLASS="APE-data">
                                                                                                            <DIV CLASS="label"><P CLASS="label">Label 1:</P></DIV>
                                                                                                            <DIV CLASS="value"><P CLASS="value">Value 1</P></DIV>
                                                                                                            <DIV CLASS="end"></DIV>
                                                                                                            <DIV CLASS="label"><P CLASS="label">Label 2 (this is long label, which should cause the value to automatically appear on the next line):</P></DIV>
                                                                                                            <DIV CLASS="value"><P CLASS="value">Value 2</P></DIV>
                                                                                                            <DIV CLASS="end"></DIV>
                                                                                                            </DIV>
                                                                                                            <P>Some <?php echo PHP_APE_HTML_Tags::htmlAnchor( "javascript:window.alert('Hello World !')", 'hyperlink', 'Click me !' )?></P>
                                                                                                            <P>Some icon: <?php echo PHP_APE_HTML_SmartTags::htmlIcon( 'S-magic' )?></P>
                                                                                                            <P>... with an hyperlink: <?php echo PHP_APE_HTML_SmartTags::htmlIcon( 'S-magic', "javascript:window.alert('Hello World !')", 'Click me !' )?></P>
                                                                                                            <P>Some label: <?php echo PHP_APE_HTML_SmartTags::htmlLabel( 'Hello World !', 'S-magic' )?></P>
                                                                                                            <P>... with an hyperlink: <?php echo PHP_APE_HTML_SmartTags::htmlLabel( 'Hello World !', 'S-magic', "javascript:window.alert('Hello World !')", 'Click me !' )?></P>
                                                                                                            <?php
                                                                                                            echo PHP_APE_HTML_SmartTags::htmlZoneOpen();
    echo PHP_APE_HTML_SmartTags::htmlButton( 'World !', 'S-magic', "javascript:window.alert('Hello World !')", 'Click me !' );
    echo PHP_APE_HTML_SmartTags::htmlButton( 'Hello...', null, "javascript:window.alert('Hello World !')", 'Click me !' );
    echo PHP_APE_HTML_SmartTags::htmlZoneClose();
    echo PHP_APE_HTML_SmartTags::htmlFrameContentEnd();
    echo PHP_APE_HTML_SmartTags::htmlFrameFooter();
  }
  echo PHP_APE_HTML_SmartTags::htmlFrameClose();
}

// Statistics
$iControlPreference_Statistics = @$roEnvironment->getUserParameter( 'php_ape.html.display.element.statistics' );
if( is_null( $iControlPreference_Statistics ) or $iControlPreference_Statistics > PHP_APE_HTML_SmartTags::Display_Closed )
{
  echo PHP_APE_HTML_SmartTags::htmlSpacer();
  echo PHP_APE_HTML_SmartTags::htmlFrameOpen();
  echo PHP_APE_HTML_SmartTags::htmlFrameHeader( 'Statistics', PHP_APE_HTML_SmartTags::Display_Control_All, 'php_ape.html.display.element.statistics' );
  if( is_null( $iControlPreference_Statistics ) or $iControlPreference_Statistics > PHP_APE_HTML_SmartTags::Display_Minimized )
  {
    echo PHP_APE_HTML_SmartTags::htmlFrameContentBegin();
      ?>
      <DIV CLASS="APE-data">
         <DIV CLASS="label"><P CLASS="label">Definitions load attempts:</P></DIV>
         <DIV CLASS="value"><P CLASS="value"><?php echo PHP_APE_Resources::$iIOStatDefinitionsAttempted?></P></DIV>
         <DIV CLASS="end"></DIV>
         <DIV CLASS="label"><P CLASS="label">Definitions load successes:</P></DIV>
         <DIV CLASS="value"><P CLASS="value"><?php echo PHP_APE_Resources::$iIOStatDefinitionsLoaded?></P></DIV>
         <DIV CLASS="end"></DIV>
         <DIV CLASS="label"><P CLASS="label">Properties cache attempts:</P></DIV>
         <DIV CLASS="value"><P CLASS="value"><?php echo PHP_APE_Properties::$iIOStatPropertiesCached?></P></DIV>
         <DIV CLASS="end"></DIV>
         <DIV CLASS="label"><P CLASS="label">Properties load attempts:</P></DIV>
         <DIV CLASS="value"><P CLASS="value"><?php echo PHP_APE_Properties::$iIOStatPropertiesAttempted?></P></DIV>
         <DIV CLASS="end"></DIV>
         <DIV CLASS="label"><P CLASS="label">Properties load successes:</P></DIV>
         <DIV CLASS="value"><P CLASS="value"><?php echo PHP_APE_Properties::$iIOStatPropertiesLoaded?></P></DIV>
         <DIV CLASS="end"></DIV>
         <?if( function_exists( 'memory_get_usage' ) ) {?>
                                                        <DIV CLASS="label"><P CLASS="label">Peak memory usage:</P></DIV>
                                                        <DIV CLASS="value"><P CLASS="value"><?php echo @memory_get_peak_usage()?></P></DIV>
                                                        <DIV CLASS="end"></DIV>
                                                        <DIV CLASS="label"><P CLASS="label">Current memory usage:</P></DIV>
                                                        <DIV CLASS="value"><P CLASS="value"><?php echo @memory_get_usage()?></P></DIV>
                                                        <DIV CLASS="end"></DIV>
                                                        <?}?>
         </DIV>
             <?php
             echo PHP_APE_HTML_SmartTags::htmlFrameContentEnd();
    echo PHP_APE_HTML_SmartTags::htmlFrameFooter();
  }
  echo PHP_APE_HTML_SmartTags::htmlFrameClose();
}

// Display controls
if( ( !is_null( $iControlPreference_WorkSpace ) and $iControlPreference_WorkSpace < PHP_APE_HTML_SmartTags::Display_Minimized ) or
    ( !is_null( $iControlPreference_HTMLTags ) and $iControlPreference_HTMLTags < PHP_APE_HTML_SmartTags::Display_Minimized ) or
    ( !is_null( $iControlPreference_Statistics ) and $iControlPreference_Statistics < PHP_APE_HTML_SmartTags::Display_Minimized ) )
{
  echo PHP_APE_HTML_SmartTags::htmlSeparator();
  echo '<DIV STYLE="FLOAT:right;PADDING-RIGHT:2px;">'."\r\n";
  echo PHP_APE_HTML_SmartTags::htmlAlignOpen();
  $bColumnAdd = false;
  if( !is_null( $iControlPreference_WorkSpace ) and $iControlPreference_WorkSpace < PHP_APE_HTML_SmartTags::Display_Minimized )
  {
    if( $bColumnAdd ) echo PHP_APE_HTML_SmartTags::htmlAlignAdd();
    $bColumnAdd = true;
    echo '<P STYLE="WHITE-SPACE:nowrap;">&nbsp;Show WorkSpace window&nbsp;</P>';
    echo PHP_APE_HTML_SmartTags::htmlAlignAdd( null, false );
    echo PHP_APE_HTML_Components::htmlControlDisplay( 'php_ape.html.display.element.workspace', PHP_APE_HTML_SmartTags::Display_Control_CloseOpen );
  }
  if( !is_null( $iControlPreference_HTMLTags ) and $iControlPreference_HTMLTags < PHP_APE_HTML_SmartTags::Display_Minimized )
  {
    if( $bColumnAdd ) echo PHP_APE_HTML_SmartTags::htmlAlignAdd();
    $bColumnAdd = true;
    echo '<P STYLE="WHITE-SPACE:nowrap;">&nbsp;Show HTML Tags window&nbsp;</P>';
    echo PHP_APE_HTML_SmartTags::htmlAlignAdd( null, false );
    echo PHP_APE_HTML_Components::htmlControlDisplay( 'php_ape.html.display.element.html_tags', PHP_APE_HTML_SmartTags::Display_Control_CloseOpen );
  }
  if( !is_null( $iControlPreference_Statistics ) and $iControlPreference_Statistics < PHP_APE_HTML_SmartTags::Display_Minimized )
  {
    if( $bColumnAdd ) echo PHP_APE_HTML_SmartTags::htmlAlignAdd();
    $bColumnAdd = true;
    echo '<P STYLE="WHITE-SPACE:nowrap;">&nbsp;Show Statistics window&nbsp;</P>';
    echo PHP_APE_HTML_SmartTags::htmlAlignAdd( null, false );
    echo PHP_APE_HTML_Components::htmlControlDisplay( 'php_ape.html.display.element.statistics', PHP_APE_HTML_SmartTags::Display_Control_CloseOpen );
  }
  echo PHP_APE_HTML_SmartTags::htmlAlignClose();
  echo '</DIV>'."\r\n";
  echo '<DIV STYLE="CLEAR:both;"></DIV>'."\r\n";
}

// Preferences
echo PHP_APE_HTML_SmartTags::htmlSeparator();
echo PHP_APE_HTML_SmartTags::htmlAlignOpen();
echo PHP_APE_HTML_Components::htmlPreferenceLanguage();
echo PHP_APE_HTML_SmartTags::htmlAlignAdd();
echo PHP_APE_HTML_Components::htmlPreferenceCountry();
echo PHP_APE_HTML_SmartTags::htmlAlignAdd();
echo PHP_APE_HTML_Components::htmlPreferenceCurrency();
echo PHP_APE_HTML_SmartTags::htmlAlignAdd();
echo PHP_APE_HTML_Components::htmlPreferenceBoolean();
echo PHP_APE_HTML_SmartTags::htmlAlignAdd();
echo PHP_APE_HTML_Components::htmlPreferenceDate();
echo PHP_APE_HTML_SmartTags::htmlAlignAdd();
echo PHP_APE_HTML_Components::htmlPreferenceTime();
echo PHP_APE_HTML_SmartTags::htmlAlignAdd();
echo PHP_APE_HTML_Components::htmlPreferenceAngle();
echo PHP_APE_HTML_SmartTags::htmlAlignClose();

// End
echo '</DIV>'."\r\n";
