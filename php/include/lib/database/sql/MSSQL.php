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
 * @package PHP_APE_Database
 * @subpackage SQL
 */

/** MS-SQL handling class
 *
 * @package PHP_APE_Database
 * @subpackage SQL
 */
class PHP_APE_Database_SQL_MSSQL
extends PHP_APE_Database_SQL_ANSI92
{

  /*
   * METHODS: parse/format - OVERRIDE
   ********************************************************************************/

  public function formatClause( $iClause, $sExpression )
  {
    switch( (integer)$iClause )
    {

    case PHP_APE_Database_SQL_Any::Limit:
    case PHP_APE_Database_SQL_Any::Offset:
      return null; // MS-SQL does NOT support LIMIT/OFFSET clauses

    }

    return formatClause( $iClause, $sExpression );
  }

}
