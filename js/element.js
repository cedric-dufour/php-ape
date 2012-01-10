/*
 * PHP Application Programming Environment (PHP-APE)
 * Copyright (C) 2005-2008 Cédric Dufour
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License,or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not,write to the Free Software Foundation,Inc.,
 * 51 Franklin Street,Fifth Floor,Boston,MA 02110-1301 USA.
 */

/* ELEMENT **********************************************************************/

function PHP_APE_EL_show(id,duration)
{
  if(typeof duration == 'undefined') duration=0;
	elmt=PHP_APE_BROWSER.getAnyElementById(id);
	if(typeof elmt.keephided!='undefined' && elmt.keephided) return;
	if(PHP_APE_BROWSER.ns4) elmt.visibility='show';
	else
  {
		elmt.style.visibility='visible';
		elmt.style.display='block';
	}
  if(duration>0) PHP_APE_EL_hideDelayed(id,duration)
}

function PHP_APE_EL_showDelayed(id,delay,duration)
{
  if(typeof delay == 'undefined') delay=0;
  if(typeof duration == 'undefined') duration=0;
	elmt=PHP_APE_BROWSER.getAnyElementById(id);
	if(typeof elmt.timeout!='undefined') window.clearTimeout(elmt.timeout);
	if(delay>0) elmt.timeout=window.setTimeout('PHP_APE_EL_show(\''+id+'\','+duration+');',delay); else PHP_APE_EL_show(id,duration);
}

function PHP_APE_EL_hide(id,keephided)
{
	elmt=PHP_APE_BROWSER.getAnyElementById(id);
	if(PHP_APE_BROWSER.ns4) elmt.visibility='hide';
	else{
		elmt.style.visibility='hidden';
		elmt.style.display='none';
	}
	if(typeof keephided!='undefined') elmt.keephided=keephided;
}

function PHP_APE_EL_hideDelayed(id,delay,keephided)
{
  if(typeof delay == 'undefined') delay=0;
	elmt=PHP_APE_BROWSER.getAnyElementById(id);
	if(typeof elmt.timeout!='undefined') window.clearTimeout(elmt.timeout);
	if(delay>0) elmt.timeout=window.setTimeout('PHP_APE_EL_hide(\''+id+'\');',delay); else PHP_APE_EL_hide(id);
	if(typeof keephided!='undefined') elmt.keephided=keephided;
}

function PHP_APE_EL_expand(id)
{
	elmt=PHP_APE_BROWSER.getAnyElementById(id);
	if(!PHP_APE_BROWSER.ns4) elmt.style.display='block';
}

function PHP_APE_EL_collapse(id)
{
	elmt=PHP_APE_BROWSER.getAnyElementById(id);
	if(!PHP_APE_BROWSER.ns4) elmt.style.display='none';
}
