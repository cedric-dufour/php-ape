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

/* URL **************************************************************************/
function PHP_APE_URL_get(frame)
{
	if(!frame) frame=self;
	url=frame.location.href.toString();
	if((i=url.indexOf('?'))>=0) url=url.substring(0,i);
	if((i=url.indexOf('//'))>=0) url=url.substring(url.indexOf('/',i+2));
	return url;
}

function PHP_APE_URL_addQuery(query,url)
{
	if( typeof url=='undefined' ) url=self.location.href.toString();
	return url+( url.indexOf('?') >= 0 ? '&' : '?' )+query;
}

/* ANCHOR ***********************************************************************/
function PHP_APE_URL_Anchor_goto()
{
	if(document.location.hash)
	{
		id=document.location.hash.toString();
		if((i=id.indexOf('#'))>=0) id=id.substring(i+1);
		obj=PHP_APE_BROWSER.getAnyElementById(id);
		if(obj)
		{
			if(PHP_APE_BROWSER.ns) self.scrollTo(0,obj.y);
			else obj.scrollIntoView();
		}
	}
}
