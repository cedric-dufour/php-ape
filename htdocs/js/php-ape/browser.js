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

/* BROWSER *****************************************************************/
function PHP_APE_cBrowser()
{ 
	this.ver=navigator.appVersion;
	this.name=navigator.appName;
	this.w3c=document.getElementById?1:0;
	this.code='unknown';
	if((this.ie6=(this.w3c&&document.documentElement&&this.ver.indexOf("MSIE 6")>-1)?1:0)) this.code='ie6';
	if((this.ie5=(this.w3c&&document.documentElement&&this.ver.indexOf("MSIE 5")>-1)?1:0)) this.code='ie5';
	if((this.ie4=(document.all&&!this.w3c)?1:0)) this.code='ie4';
	this.ie=(this.ie6||this.ie5||this.ie4);
	if((this.ns6=(this.w3c&&this.name.indexOf("Netscape")>-1&&parseInt(this.ver)==6)?1:0)) this.code='ns6';
	if((this.ns5=(this.w3c&&this.name.indexOf("Netscape")>-1&&parseInt(this.ver)==5)?1:0)) this.code='ns5';
	if((this.ns4=(document.layers&&!this.w3c)?1:0)) this.code = 'ns4';
	this.ns=(this.ns6||this.ns5||this.ns4);
	this.OK=(this.ie||this.ns);
	this.getAnyElementById = PHP_APE_cBrowser_getAnyElementById;
	return this;
} 

function PHP_APE_cBrowser_getAnyElementById(id)
{
	var obj=null;
	if(this.ie4) obj=document.all[id];
	else if(this.ns4) obj=PHP_APE_cBrowser_findNS4(top,id);
	else if(this.w3c) obj=PHP_APE_cBrowser_findW3C(top,id);
	return obj;
}

function PHP_APE_cBrowser_findNS4(win,id)
{
	var obj = null;
	for(var i=0;i<win.document.images.length;i++)
	{
		if( win.document.images[i].name == id )
		{ 
			obj = win.document.images[i];
			return obj;
		}
	}

	obj = eval('document.'+id);
	if(obj) return obj;

	for(var i=0;i<win.document.layers.length;i++)
	{
		if(win.document.layers[i].name==id) obj=win.document.layers[i];
		else obj=PHP_APE_cBrowser_findNS4(win.document.layers[i],id);
		if(obj) return obj;
	}

	for (var i=0;i<win.frames.length;i++)
	{
		if(win.frames[i].name==id) obj=win.frames[i];
		else obj=PHP_APE_cBrowser_findNS4(win.frames[i],id);
		if(obj) return obj;
	}
}

function PHP_APE_cBrowser_findW3C(win,id)
{
	for(var i=0;i<win.document.images.length;i++)
		if(win.document.images[i].name==id)
		{
			obj=win.document.images[i];
			return obj;
		}

	obj=win.document.getElementById(id);
	if(obj) return obj;

	for(var i=0;i<win.frames.length;i++)
	{
		if(win.frames[i].name==id) obj=win.frames[i];
		else obj = PHP_APE_cBrowser_findW3C(win.frames[i],id);
		if(obj) return obj;
	}
}

var PHP_APE_BROWSER = new PHP_APE_cBrowser();
