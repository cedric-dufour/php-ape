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
 * @subpackage WUI
 */

/** JavaScript date-chooser popup
 */

// Load APE
require_once( $_SERVER['PHP_APE_ROOT'].'/load.php' );
require_once( PHP_APE_ROOT.'/lib/html/load.php' );

// Data
// ... date target
$sTargetID = null;
if( isset( $_GET['target'] ) ) $sTargetID = PHP_APE_Type_Index::parseValue( $_GET['target'] );
if( isset( $_POST['target'] ) ) $sTargetID = PHP_APE_Type_Index::parseValue( $_POST['target'] );
// ... default date
$iDate = null;
if( isset( $_GET['date'] ) ) $iDate = PHP_APE_Type_Date::parseValue( $_GET['date'] );
if( isset( $_POST['date'] ) ) $iDate = PHP_APE_Type_Date::parseValue( $_POST['date'] );
else $iDate = PHP_APE_Type_Date::parseValue( time(), true, null, true );
$aiDate_parts = getdate( $iDate );

// Environment
$roEnvironment =& PHP_APE_HTML_WorkSpace::useEnvironment();
$asResources = $roEnvironment->loadProperties( 'PHP_APE_HTML_DateChooser' );
$iDateFormat = $roEnvironment->getUserParameter( 'php_ape.data.format.date' ) & ( PHP_APE_Type_Date::Format_YYMMDD | PHP_APE_Type_Date::Format_MMDDYY | PHP_APE_Type_Date::Format_DDMMYY );

// HTML
$oDataSpace_HTML = new PHP_APE_DataSpace_HTML();
$oDataSpace_JavaScript = new PHP_APE_DataSpace_JavaScript();
echo PHP_APE_HTML_Tags::htmlDocumentOpen();

// ... HEAD
echo PHP_APE_HTML_Tags::htmlHeadOpen();
echo PHP_APE_HTML_Tags::htmlHeadCharSet();
echo PHP_APE_HTML_Tags::htmlJavaScript( 'PHP-APE' );
echo PHP_APE_HTML_SmartTags::htmlCSS();
echo PHP_APE_HTML_Tags::htmlHeadTitle( $asResources['page.title'] );
echo PHP_APE_HTML_Tags::htmlHeadClose();

// ... BODY
echo PHP_APE_HTML_Tags::htmlBodyOpen( 'APE' );
echo '<DIV CLASS="APE">'."\r\n";
?>
<FORM NAME="PHP_APE_DateChooser" ACTION="" TYPE="">
<TABLE CLASS="APE-datechooser" CELLSPACING="0">
<TR>
<TD CLASS="yp"><?php echo PHP_APE_HTML_SmartTags::htmlIcon( 'S-previous', 'javascript:if(PHP_APE_DateChooser_setYear(parseInt(document.PHP_APE_DateChooser.Year.value)-1)) PHP_APE_DateChooser_refresh();' );?></TD>
<TD CLASS="yi" COLSPAN="2"><INPUT NAME="Year" TYPE="text" CLASS="y" MAXLENGTH="4" VALUE="<?php echo $aiDate_parts['year']?>" ONCHANGE="javascript:if(PHP_APE_DateChooser_setYear(this.value)) PHP_APE_DateChooser_refresh();"/></TD>
<TD CLASS="yn"><?php echo PHP_APE_HTML_SmartTags::htmlIcon( 'S-next', 'javascript:if(PHP_APE_DateChooser_setYear(parseInt(document.PHP_APE_DateChooser.Year.value)+1)) PHP_APE_DateChooser_refresh();' );?></TD>
</TR>
<TR>
<TD CLASS="mp"><?php echo PHP_APE_HTML_SmartTags::htmlIcon( 'S-previous', 'javascript:if(PHP_APE_DateChooser_setMonth(parseInt(document.PHP_APE_DateChooser.Month.value)-1)) PHP_APE_DateChooser_refresh();' );?></TD>
<TD CLASS="mi">
<SELECT NAME="Month" CLASS="m" ONCHANGE="javascript:if(PHP_APE_DateChooser_setMonth(this.value)) PHP_APE_DateChooser_refresh();">
<?php for( $i=1; $i<=12; $i++ ) {?><OPTION VALUE="<?php echo $i;?>" <?php if($i==$aiDate_parts['mon']) echo 'SELECTED';?>><?php echo substr('0'.$i,-2);?></OPTION><?php } ?>
</SELECT>
</TD>
<TD CLASS="mt"><INPUT NAME="MonthName" TYPE="text" CLASS="m" VALUE="" DISABLED/></TD>
<TD CLASS="mn"><?php echo PHP_APE_HTML_SmartTags::htmlIcon( 'S-next', 'javascript:if(PHP_APE_DateChooser_setMonth(parseInt(document.PHP_APE_DateChooser.Month.value)+1)) PHP_APE_DateChooser_refresh();' );?></TD>
</TR>
<TR><TD CLASS="cal" COLSPAN="4">
<TABLE CLASS="cal" CELLSPACING="0">
<TR>
<TH><?php echo $oDataSpace_HTML->encodeData($asResources['day.sunday']);?></TH>
<TH><?php echo $oDataSpace_HTML->encodeData($asResources['day.monday']);?></TH>
<TH><?php echo $oDataSpace_HTML->encodeData($asResources['day.tuesday']);?></TH>
<TH><?php echo $oDataSpace_HTML->encodeData($asResources['day.wednesday']);?></TH>
<TH><?php echo $oDataSpace_HTML->encodeData($asResources['day.thursday']);?></TH>
<TH><?php echo $oDataSpace_HTML->encodeData($asResources['day.friday']);?></TH>
<TH><?php echo $oDataSpace_HTML->encodeData($asResources['day.saturday']);?></TH>
</TR>
<?php for( $y=0; $y<6; $y++ ) { ?>
<TR>
<?php for( $x=0; $x<7; $x++ ) { ?>
<TD CLASS="d"><INPUT NAME="<?php echo 'Cell_'.$x.'_'.$y;?>" TYPE="text" CLASS="d" STYLE="DISPLAY:none;" VALUE="" ONCLICK="javascript:PHP_APE_DateChooser_choice(this.value);" DISABLED/></TD>
<?php } ?>
</TR>
<?php } ?>
</TABLE>
</TD></TR>
</TABLE>
</FORM>
<SCRIPT TYPE="text/javascript"><!--
function PHP_APE_DateChooser_getDefaultDate()
{
  oDefault = new Date( <?php echo $aiDate_parts['year'];?>, <?php echo $aiDate_parts['mon']-1;?>, <?php echo $aiDate_parts['mday'];?> );
  eInput = opener.document.getElementById('<?php echo $oDataSpace_JavaScript->encodeData($sTargetID);?>');
  asParts = eInput.value.toString().split(/[-./]/,3 );
  if( asParts.length < 3 ) return oDefault;
<?php if( $iDateFormat == PHP_APE_Type_Date::Format_DDMMYY ) { ?>
  iYear = asParts[2];
  iMonth = asParts[1];
  iDay = asParts[0];
<?php } elseif( $iDateFormat == PHP_APE_Type_Date::Format_MMDDYY ) { ?>
  iYear = asParts[2];
  iMonth = asParts[0];
  iDay = asParts[1];
<?php } else { ?>
  iYear = asParts[0];
  iMonth = asParts[1];
  iDay = asParts[2];
<?php } ?>
  if( iYear < 1902 || iYear > 2069 ) return oDefault;
  if( iMonth < 1 || iMonth > 12 ) return oDefault;
  if( iDay < 1 || iDay > 31 ) return oDefault;
  return new Date( iYear, iMonth-1, iDay );
}
function PHP_APE_DateChooser_setYear( iYear )
{
  iYear = parseInt( iYear );
  if( iYear < 1902 ) return false;
  if( iYear > 2069 ) return false;
  document.PHP_APE_DateChooser.Year.value = iYear;
  return true;
}
function PHP_APE_DateChooser_setMonth( iMonth )
{
  iMonth = parseInt( iMonth );
  if( iMonth < 0 ) return false;
  if( iMonth > 13 ) return false;
  if( iMonth == 0 )
  {
    if( !PHP_APE_DateChooser_setYear(parseInt(document.PHP_APE_DateChooser.Year.value)-1) ) return false;
    iMonth = 12;
  }
  else if( iMonth == 13 )
  {
    if( !PHP_APE_DateChooser_setYear(parseInt(document.PHP_APE_DateChooser.Year.value)+1) ) return false;
    iMonth = 1;
  }
  document.PHP_APE_DateChooser.Month.value = iMonth;
  return true;
}
function PHP_APE_DateChooser_initialize()
{
  oDefault = PHP_APE_DateChooser_getDefaultDate();
  document.PHP_APE_DateChooser.Year.value = oDefault.getFullYear();
  document.PHP_APE_DateChooser.Month.value = oDefault.getMonth()+1;
  PHP_APE_DateChooser_refresh();
}
function PHP_APE_DateChooser_refresh()
{
  var asMonths = [ '<?php echo $oDataSpace_JavaScript->encodeData($asResources['month.january']);?>', '<?php echo $oDataSpace_JavaScript->encodeData($asResources['month.february']);?>', '<?php echo $oDataSpace_JavaScript->encodeData($asResources['month.march']);?>', '<?php echo $oDataSpace_JavaScript->encodeData($asResources['month.april']);?>', '<?php echo $oDataSpace_JavaScript->encodeData($asResources['month.may']);?>', '<?php echo $oDataSpace_JavaScript->encodeData($asResources['month.june']);?>', '<?php echo $oDataSpace_JavaScript->encodeData($asResources['month.july']);?>', '<?php echo $oDataSpace_JavaScript->encodeData($asResources['month.august']);?>', '<?php echo $oDataSpace_JavaScript->encodeData($asResources['month.september']);?>', '<?php echo $oDataSpace_JavaScript->encodeData($asResources['month.october']);?>', '<?php echo $oDataSpace_JavaScript->encodeData($asResources['month.november']);?>', '<?php echo $oDataSpace_JavaScript->encodeData($asResources['month.december']);?>' ];

  // Constants
  oToday = new Date(); oToday = new Date( oToday.getFullYear(), oToday.getMonth(), oToday.getDate() );
  oDefault = PHP_APE_DateChooser_getDefaultDate();

  // Retrieve year and month
  iYear = document.PHP_APE_DateChooser.Year.value;
  iMonth = document.PHP_APE_DateChooser.Month.value;

  // Set month name
  document.PHP_APE_DateChooser.MonthName.value = asMonths[ iMonth-1 ];

  // Get first day of month
  oDate = new Date( iYear, iMonth-1, 1 );
  iDayOfWeek = oDate.getDay(); // 0-6

  // Display calendar

  // ... reset cells
  for( y=0; y<6; y++ )
  {
    for( x=0; x<7; x++ )
    {
      e = document.PHP_APE_DateChooser['Cell_'+x+'_'+y ];
      e.className="d";
      e.clear = true;
    }
  }

  // ... previous month
  oDate = new Date( new Date( iYear, iMonth-1, 1 ) - 1 );
  iDay = oDate.getDate();
  for( x=iDayOfWeek-1; x>=0; x-- )
  {
    oDate = 
    e = document.PHP_APE_DateChooser['Cell_'+x+'_0' ];
    e.value = iDay--;
    e.disabled = true;
    e.clear = false;
  }

  // ... current month
  x = iDayOfWeek; y = 0;
  iDay = 1;
  oDate = new Date( iYear, iMonth-1, iDay );
  do
  {
    e = document.PHP_APE_DateChooser['Cell_'+x+'_'+y ];
    e.value = iDay;
    e.disabled = false;
    e.clear = false;
    if( oDate.getTime() == oToday.getTime() )
      e.className="td";
    else if( oDate.getTime() == oDefault.getTime() )
      e.className="df";
    oDate = new Date( iYear, iMonth-1, ++iDay );
    if( ++x >= 7 ) { x = 0; ++y; }
  }
  while( oDate.getMonth() == iMonth-1 ); // loop until last day of month


  // ... next month
  if( x != 0 )
  {
    iDay = 1;
    while( x < 7 )
    {
      e = document.PHP_APE_DateChooser['Cell_'+x+'_'+y ];
      e.value = iDay++;
      e.disabled = true;
      e.clear = false;
      ++x;
    }
  }

  // ... display cells
  for( y=0; y<6; y++ )
  {
    for( x=0; x<7; x++ )
    {
      e = document.PHP_APE_DateChooser['Cell_'+x+'_'+y ];
      e.style.display = e.clear ? 'none' :  'block';
    }
  }
}
function PHP_APE_DateChooser_choice( iDay )
{
  sDay = '0'+iDay; sDay = sDay.substring(sDay.length-2);
  sMonth = '0'+document.PHP_APE_DateChooser.Month.value; sMonth = sMonth.substring(sMonth.length-2);
  sYear = '000'+document.PHP_APE_DateChooser.Year.value; sYear = sYear.substring(sYear.length-4);
<?php if( $iDateFormat == PHP_APE_Type_Date::Format_DDMMYY ) { ?>
  sDate = sDay+'.'+sMonth+'.'+sYear;
<?php } elseif( $iDateFormat == PHP_APE_Type_Date::Format_MMDDYY ) { ?>
  sDate = sMonth+'/'+sDay+'/'+sYear;
<?php } else { ?>
  sDate = sYear+'-'+sMonth+'-'+sDay;
<?php } ?>
  e = opener.document.getElementById('<?php echo $oDataSpace_JavaScript->encodeData($sTargetID);?>');
  e.value = sDate.toString();
  window.close();
}
PHP_APE_DateChooser_initialize();
--></SCRIPT>
</DIV>
<?php
echo PHP_APE_HTML_Tags::htmlBodyClose();
echo PHP_APE_HTML_Tags::htmlDocumentClose();
