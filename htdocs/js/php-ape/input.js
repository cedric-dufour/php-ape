/*
 * PHP Application Programming Environment (PHP-APE)
 * Copyright (C) 2005-2008 Cédric Dufour
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

/* FORM *************************************************************************/
function PHP_APE_IN_Form_args(form,args,lock)
{
	if( !args.disabled ) return args.value;
	if( lock==null ) lock=false;
	args.disabled = true;
	args.value = '';
	for( i=0; i<form.elements.length; i++ )
	{
		elmt=form.elements[i];
		if( elmt.name==args.name ) continue;
		if( elmt.disabled ) continue;
		type=elmt.type.toLowerCase();
		if( type=='file' ) continue;
		if( (type=='radio' || type=='checkbox') && !elmt.checked ) continue;
    if( type=='select-multiple' )
    {
      for( j=0; j<elmt.options.length; j++ )
        if( elmt.options[j].selected ) args.value += (args.value?';':'')+elmt.name+'=\''+elmt.options[j].value.replace(/(\\|\')/g,'\\$1')+'\'';
    }
    else
      args.value += (args.value?';':'')+elmt.name+'=\''+elmt.value.replace(/(\\|\')/g,'\\$1')+'\'';
		elmt.disabled = lock;
	}
	args.value = '{'+args.value+'}';
  args.disabled = !lock;
	return args.value;
}

function PHP_APE_IN_Form_post(form,args,lock)
{
	if( lock==null ) lock=true;
	if( args!=null ) PHP_APE_IN_Form_args(form,args,lock);
	args.disabled = false;
	form.method = 'post';
	form.submit();
}

function PHP_APE_IN_Form_get(form,args,target,replace)
{
	if( target==null ) target=document;
	if( replace==null ) replace=false;
	if( form.action ) url=form.action; else url=PHP_APE_URL_get(self);
	if( url.indexOf('?')>=0 ) sep='&'; else sep='?';
	if( args!=null ) url=url+sep+args.name+'='+escape(PHP_APE_IN_Form_args(form,args,false));
	else for( i=0; i<form.elements.length; i++ )
	{
		elmt=form.elements[i];
		type=elmt.type.toLowerCase();
		if( !elmt.disabled && (((type!='radio') && (type!='checkbox')) || elmt.checked) )
		{
			url=url+sep+elmt.name+'='+escape(elmt.value);
			sep='&';
		}
	}
	if( replace ) target.location.replace(url); else target.location.href=url;
}

/* SELECT ************************************************************************/
function PHP_APE_IN_Select_setValue(select,value)
{
	for( i=0; i<select.options.length; i++ )
		if( select.options[i].value==value ) select.options[i].selected=true;
}

/* TEXTAREA **********************************************************************/
function PHP_APE_IN_TextArea_contrainLength(textarea,maxlength)
{
	if( textarea.value.length>=maxlength ) textarea.value=textarea.value.substring(0,maxlength-1);
}

/* RADIO ************************************************************************/
function PHP_APE_IN_Radio_getIndex(radio)
{
	for( i=0; i<radio.length; i++ )
		if( radio[i].checked ) return i;
	return 0;
}

function PHP_APE_IN_Radio_getValue(radio)
{
	for( i=0; i<radio.length; i++ )
		if( radio[i].checked ) return radio[i].value;
	return null;
}

function PHP_APE_IN_Radio_setValue(radio,value)
{
	for( i=0; i<radio.length; i++ )
		if( radio[i].value==value ) radio[i].checked=true;
}

/* INTEGER **********************************************************************/
function PHP_APE_IN_Integer_isOK(str)
{
	if( str=='' ) return 0; // Empty
	if( parseInt(str)!=str ) return -1; // Error
	return 1; // OK
}

/* FLOAT ************************************************************************/
function PHP_APE_IN_Float_isOK(str)
{
	if( str=='' ) return 0; // Empty
	if( parseFloat(str)!=str ) return -1; // Error
	return 1; // OK
}

/* MONEY ************************************************************************/
function PHP_APE_IN_Money_isOK(str)
{
	if( str=='' ) return 0; // Empty
	if( parseFloat(str)!=str ) return -1; // Error
	if( Math.round(100*str)/100!=str ) return -1; //Error
	return 1; // OK
}

/* E-MAIL ***********************************************************************/
function PHP_APE_IN_Email_isOK(str)
{
	re=/^s*(\w+[-_.])*\w+@(\w+[-_.])*\w+\.\w+\s*$/;
	return re.test(str);
}

/* URL **************************************************************************/
function PHP_APE_IN_URL_isOK(str,strict)
{
	if( strict==null )strict=false;
	if( strict ) re=/^\s*(https?|ftps?):\/\/(\w+[-_.])*\w+\.\w+(\/(\w+[-_.])*\w+)*\/?(\?.*)?\s*$/;
	else re=/^\s*(https?|ftps?):\/\/(\w+[-_.])*\w+\.\w+\/?/;
	return re.test(str);
}

/* DATE *************************************************************************/
function PHP_APE_IN_Date_isOK(year,month,day)
{
	if( !year && parseInt(year)!=0 && !month && parseInt(month)!=0 && !day && parseInt(day)!=0) return 0; // Empty
	if( (!year && parseInt(year)!=0) || (!month && parseInt(month)!=0) || (!day && parseInt(day)!=0) ) return -1; // Incomplete
	test = new Date(year,month-1,day);
	if( test.getFullYear()!=year || test.getMonth()!=month-1 || test.getDate()!=day ) return -2; // Error
	else return test.valueOf(); // OK
}

function PHP_APE_IN_Date_check(year,month,day)
{
	return PHP_APE_IN_Date_isOK(year.value,month.value,day.value);
}

function PHP_APE_IN_Date_clear(year,month,day)
{
	year.value='';month.value='';day.value='';
}

function PHP_APE_IN_Date_onYearChange(year,month,day)
{
	if( !year.value ) PHP_APE_IN_Date_clear(year,month,day);
}

function PHP_APE_IN_Date_onMonthChange(year,month,day)
{
	if( !month.value ) PHP_APE_IN_Date_clear(year,month,day);
}

function PHP_APE_IN_Date_onDayChange(year,month,day)
{
	if( !day.value ) PHP_APE_IN_Date_clear(year,month,day);
}

/* date range *******************************************************************/
function PHP_APE_IN_Date_onRangeChange(year_from,month_from,day_from,year_to,month_to,day_to)
{
	testDate_from=PHP_APE_IN_Date_isOK(year_from.value,month_from.value,day_from.value);
	testDate_to=PHP_APE_IN_Date_isOK(year_to.value,month_to.value,day_to.value);
	if((testDate_from>0)&&(testDate_to>0)&&(testDate_to<testDate_from))
	{
		year_to.value=year_from.value;
		minute_to.value=minute_from.value;
		day_to.value=day_from.value;
	}
}

function PHP_APE_IN_Date_onFromYearChange(year_from,month_from,day_from,year_to,month_to,day_to)
{
	if(!year_from.value) PHP_APE_IN_Date_clear(year_from,month_from,day_from);
	else PHP_APE_IN_Date_onRangeChange(year_from,month_from,day_from,year_to,month_to,day_to);
}

function PHP_APE_IN_Date_onFromMonthChange(year_from,month_from,day_from,year_to,month_to,day_to)
{
	if(!month_from.value) PHP_APE_IN_Date_clear(year_from,month_from,day_from);
	else PHP_APE_IN_Date_onRangeChange(year_from,month_from,day_from,year_to,month_to,day_to);
}

function PHP_APE_IN_Date_onFromDayChange(year_from,month_from,day_from,year_to,month_to,day_to)
{
	if(!day_from.value) PHP_APE_IN_Date_clear(year_from,month_from,day_from);
	else PHP_APE_IN_Date_onRangeChange(year_from,month_from,day_from,year_to,month_to,day_to);
}

function PHP_APE_IN_Date_onToYearChange(year_from,month_from,day_from,year_to,month_to,day_to)
{
	if(!year_to.value) PHP_APE_IN_Date_clear(year_from,month_from,day_from);
	else PHP_APE_IN_Date_onRangeChange(year_from,month_from,day_from,year_to,month_to,day_to);
}

function PHP_APE_IN_Date_onToMonthChange(year_from,month_from,day_from,year_to,month_to,day_to)
{
	if(!month_from.value) PHP_APE_IN_Date_clear(year_from,month_from,day_from);
	else PHP_APE_IN_Date_onRangeChange(year_from,month_from,day_from,year_to,month_to,day_to);
}

function PHP_APE_IN_Date_onToDayChange(year_from,month_from,day_from,year_to,month_to,day_to)
{
	if(!day_to.value) PHP_APE_IN_Date_clear(year_from,month_from,day_from);
	else PHP_APE_IN_Date_onRangeChange(year_from,month_from,day_from,year_to,month_to,day_to);
}

/* TIME *************************************************************************/
function PHP_APE_IN_Time_HMS_isOK(hour,minute,second)
{
	if(!hour&&(parseInt(hour)!=0)&&!minute&&(parseInt(minute)!=0)&&!second&&(parseInt(second)!=0)) return 0; // Empty
	if((!hour&&(parseInt(hour)!=0))||(!minute&&(parseInt(minute)!=0))||(!second&&(parseInt(second)!=0))) return 1; // Incomplete
	test=new Date(0,0,0,hour,minute,second);
	if((test.getHours()!=hour)||(test.getMinutes()!=minute)||(test.getSeconds()!=second)) return 2; // Error
	else return test.valueOf(); // OK (NB: Negative value!)
}

function PHP_APE_IN_Time_HM_isOK(hour,minute)
{
	if(!hour&&(parseInt(hour)!=0)&&!minute&&(parseInt(minute)!=0)) return 0; // Empty
	if((!hour&&(parseInt(hour)!=0))||(!minute&&(parseInt(minute)!=0))) return 1; // Incomplete
	test=new Date(0,0,0,hour,minute);
	if((test.getHours()!=hour)||(test.getMinutes()!=minute)) return 2; // Error
	else return test.valueOf(); // OK (NB: Negative value!)
}

function PHP_APE_IN_Time_check(hour,minute,second)
{
	return second?PHP_APE_IN_Time_HMS_isOK(hour.value,minute.value,second.value):PHP_APE_IN_Time_HM_isOK(hour.value,minute.value);
}

function PHP_APE_IN_Time_clear(hour,minute,second)
{
	hour.value="";minute.value="";if(second)second.value="";
}

function PHP_APE_IN_Time_onHourChange(hour,minute,second)
{
	if(!hour.value) PHP_APE_IN_Time_clear(hour,minute,second);
}

function PHP_APE_IN_Time_onMinuteChange(hour,minute,second)
{
	if(!minute.value) PHP_APE_IN_Time_clear(hour,minute,second);
}

function PHP_APE_IN_Time_onSecondChange(hour,minute,second)
{
	if(!second.value) PHP_APE_IN_Time_clear(hour,minute,second);
}

/* time range *******************************************************************/
function PHP_APE_IN_Time_onRangeChange(hour_from,minute_from,second_from,hour_to,minute_to,second_to)
{
	testTime_from=second_from?PHP_APE_IN_Time_HMS_isOK(hour_from.value,minute_from.value,second_from.value):PHP_APE_IN_Time_HM_isOK(hour_from.value,minute_from.value);
	testTime_to=second_to?PHP_APE_IN_Time_HMS_isOK(hour_to.value,minute_to.value,second_to.value):PHP_APE_IN_Time_HM_isOK(hour_to.value,minute_to.value);
	if((testTime_from<0)&&(testTime_to<0)&&(testTime_to<testTime_from))
	{
		hour_to.value=hour_from.value;
		minute_to.value=minute_from.value;
		if(second_from&&second_to)second_to.value=second_from.value;
	}
}

function PHP_APE_IN_Time_onFromHourChange(hour_from,minute_from,second_from,hour_to,minute_to,second_to)
{
	if(!hour_from.value) PHP_APE_IN_Time_clear(hour_from,minute_from,second_from);
	else PHP_APE_IN_Time_onRangeChange(hour_from,minute_from,second_from,hour_to,minute_to,second_to);
}

function PHP_APE_IN_Time_onFromMinuteChange(hour_from,minute_from,second_from,hour_to,minute_to,second_to)
{
	if(!minute_from.value) PHP_APE_IN_Time_clear(hour_from,minute_from,second_from);
	else PHP_APE_IN_Time_onRangeChange(hour_from,minute_from,second_from,hour_to,minute_to,second_to);
}

function PHP_APE_IN_Time_onFromSecondChange(hour_from,minute_from,second_from,hour_to,minute_to,second_to)
{
	if(!second_from.value) PHP_APE_IN_Time_clear(hour_from,minute_from,second_from);
	else PHP_APE_IN_Time_onRangeChange(hour_from,minute_from,second_from,hour_to,minute_to,second_to);
}

function PHP_APE_IN_Time_onToHourChange(hour_from,minute_from,second_from,hour_to,minute_to,second_to)
{
	if(!hour_to.value) PHP_APE_IN_Time_clear(hour_from,minute_from,second_from);
	else PHP_APE_IN_Time_onRangeChange(hour_from,minute_from,second_from,hour_to,minute_to,second_to);
}

function PHP_APE_IN_Time_onToMinuteChange(hour_from,minute_from,second_from,hour_to,minute_to,second_to)
{
	if(!minute_from.value) PHP_APE_IN_Time_clear(hour_from,minute_from,second_from);
	else PHP_APE_IN_Time_onRangeChange(hour_from,minute_from,second_from,hour_to,minute_to,second_to);
}

function PHP_APE_IN_Time_onToSecondChange(hour_from,minute_from,second_from,hour_to,minute_to,second_to)
{
	if(!second_to.value) PHP_APE_IN_Time_clear(hour_from,minute_from,second_from);
	else PHP_APE_IN_Time_onRangeChange(hour_from,minute_from,second_from,hour_to,minute_to,second_to);
}

/* ANGLE *************************************************************************/
function PHP_APE_IN_Angle_DMS_isOK(degree,minute,second)
{
	if(!degree&&(parseInt(degree)!=0)&&!minute&&(parseInt(minute)!=0)&&!second&&(parseInt(second)!=0)) return 0; // Empty
	if((!degree&&(parseInt(degree)!=0))||(!minute&&(parseInt(minute)!=0))||(!second&&(parseInt(second)!=0))) return -1; // Incomplete
	if(minute<0||minute>59||second<0||second>59) return -2; // Error
	else return 1; // OK
}

function PHP_APE_IN_Angle_DM_isOK(degree,minute)
{
	if(!degree&&(parseInt(degree)!=0)&&!minute&&(parseInt(minute)!=0)) return 0; // Empty
	if((!degree&&(parseInt(degree)!=0))||(!minute&&(parseInt(minute)!=0))) return -1; // Incomplete
	if(minute<0||minute>59) return -2; // Error
	else return 1; // OK
}

function PHP_APE_IN_Angle_D_isOK(degree)
{
	if(!degree&&(parseInt(degree)!=0)) return 0; // Empty
	else return 1; // OK
}

function PHP_APE_IN_Angle_check(degree,minute,second)
{
	return second?PHP_APE_IN_Angle_DMS_isOK(degree.value,minute.value,second.value):(minute?PHP_APE_IN_Angle_DM_isOK(degree.value,minute.value):PHP_APE_IN_Angle_D_isOK(degree.value));
}

function PHP_APE_IN_Angle_clear(degree,minute,second)
{
	degree.value="";if(minute)minute.value="";if(second)second.value="";
}

function PHP_APE_IN_Angle_onDegreeChange(degree,minute,second)
{
	if(!degree.value) PHP_APE_IN_Angle_clear(degree,minute,second);
}

function PHP_APE_IN_Angle_onMinuteChange(degree,minute,second)
{
	if(!minute.value) PHP_APE_IN_Angle_clear(degree,minute,second);
}

function PHP_APE_IN_Angle_onSecondChange(degree,minute,second)
{
	if(!second.value) PHP_APE_IN_Angle_clear(degree,minute,second);
}
