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

/** PostgreSQL handling class
 *
 * @package PHP_APE_Database
 * @subpackage SQL
 */
class PHP_APE_Database_SQL_PostgreSQL
extends PHP_APE_Database_SQL_ANSI92
{

  /*
   * METHODS: decode/encode - OVERRIDE
   ********************************************************************************/

  public function decodeData( $sInput )
  {
    return preg_replace( '/\'?(.*?)\'?(::\w+)?/Am', '${1}', parent::decodeData( $sInput ) );
  }


  /*
   * METHODS: parse/format - OVERRIDE
   ********************************************************************************/

  public function formatCast( $sExpression, PHP_APE_Type_Scalar $oData )
  {
    if( $oData instanceof PHP_APE_Type_Boolean )
      return $sExpression;

    if( $oData instanceof PHP_APE_Type_Integer )
    {
      if( $oData instanceof PHP_APE_Type_Int8 )
        return 'CAST('.$sExpression.' AS int8)';

      if( $oData instanceof PHP_APE_Type_Int2 )
        return 'CAST('.$sExpression.' AS int2)';

      if( $oData instanceof PHP_APE_Type_Byte )
        return 'CAST('.$sExpression.' AS int2)';

      return $sExpression;
    }

    if( $oData instanceof PHP_APE_Type_Float )
      return $sExpression;

    if( $oData instanceof PHP_APE_Type_Date )
      return 'CAST(\''.$sExpression.'\' AS date)';

    if( $oData instanceof PHP_APE_Type_Time )
      return 'CAST(\''.$sExpression.'\' AS time)';

    if( $oData instanceof PHP_APE_Type_Timestamp )
    {
      if( $oData instanceof PHP_APE_Type_Angle )
        throw new PHP_APE_Database_SQL_Exception( __METHOD__, 'Invalid/unsupported data/type; Data/Type: '.get_class( $oData ) );

      return 'CAST(\''.$sExpression.'\' AS datetime)';
    }

    if( $oData instanceof PHP_APE_Type_String )
      return '\''.$sExpression.'\'';

    throw new PHP_APE_Database_SQL_Exception( __METHOD__, 'Invalid/unsupported data/type; Data/Type: '.get_class( $oData ) );
  }

  public function formatView( PHP_APE_Database_View $oView, $bAlias = false )
  {
    $sView = $oView->getNamespace();
    $sView .= ( $sView ? '.' : null ).$oView->getExpression();
    if( $oView->hasArgumentSet() )
    {
      $sArguments = null;
      $roArgumentSet =& $oView->useArgumentSet();
      $amKeys = $roArgumentSet->getElementsKeys();
      if( $roArgumentSet instanceof PHP_APE_Data_isMetaDataSet )
        $amKeys = array_diff( $amKeys, $roArgumentSet->getElementsKeysPerMeta( PHP_APE_Data_Argument::Feature_ExcludeInCall ) );
      foreach( $amKeys as $mKey )
        $sArguments .= ( $sArguments ? ', ' : null ).$this->formatData( $roArgumentSet->useElement( $mKey )->useContent() );
      $sView .= '( '.$sArguments.' )';
    }
    if( $bAlias ) $sView .= $this->formatClause( PHP_APE_Database_SQL_Any::From_As, $this->formatViewAs( $oView ) );
    return $sView;
  }


  /*
   * METHODS: Character set - OVERRIDE
   ********************************************************************************/

  public function initCharset( $rDatabaseConnection = null )
  {
    // Retrieve character set matching PHP's defined character set
    $sCharset = null;
    switch( strtoupper( PHP_APE_WorkSpace::useEnvironment()->getStaticParameter( 'php_ape.data.charset' ) ) )
    {
    case 'ASCII': $sCharset = 'SQL_ASCII'; break;
    case 'UTF8': $sCharset = 'UTF8'; break;
    case 'ISO-8859-1': $sCharset = 'LATIN1'; break;
    case 'ISO-8859-2': $sCharset = 'LATIN2'; break;
    case 'ISO-8859-3': $sCharset = 'LATIN3'; break;
    case 'ISO-8859-4': $sCharset = 'LATIN4'; break;
    case 'ISO-8859-5': $sCharset = 'ISO_8859_5'; break;
    case 'ISO-8859-6': $sCharset = 'ISO_8859_6'; break;
    case 'ISO-8859-7': $sCharset = 'ISO_8859_7'; break;
    case 'ISO-8859-8': $sCharset = 'ISO_8859_8'; break;
    case 'ISO-8859-9': $sCharset = 'ISO_8859_9'; break;
    case 'ISO-8859-10': $sCharset = 'LATIN5'; break;
    case 'ISO-8859-11': $sCharset = 'LATIN6'; break;
    case 'ISO-8859-13': $sCharset = 'LATIN7'; break;
    case 'ISO-8859-14': $sCharset = 'LATIN8'; break;
    case 'ISO-8859-15': $sCharset = 'LATIN9'; break;
    case 'ISO-8859-16': $sCharset = 'LATIN10'; break;
    case 'EUC-CN': $sCharset = 'EUC_CN'; break;
    case 'EUC-JP': $sCharset = 'EUC_JP'; break;
    case 'EUC-KR': $sCharset = 'EUC_KR'; break;
    case 'EUC-TW': $sCharset = 'EUC_TW'; break;
    }

    // Set character set
    if( !is_null( $sCharset ) )
    {
      if( is_null( $rDatabaseConnection ) ) pg_set_client_encoding( $sCharset );
      else pg_set_client_encoding( $rDatabaseConnection, $sCharset );
    }
  }

}
