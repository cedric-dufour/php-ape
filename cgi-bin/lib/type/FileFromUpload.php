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
 * @package PHP_APE_Type
 * @subpackage Classes
 */

/** Upload (file name) type
 *
 * <P><B>NOTE:</B> PHP does NOT handle different types of <I>string</I> data, but other dataspaces DO.</P>
 *
 * @package PHP_APE_Type
 * @subpackage Classes
 */
class PHP_APE_Type_FileFromUpload
extends PHP_APE_Type_File
{

  /*
   * METHODS: upload
   ********************************************************************************/

  /** Returns the uploaded file information array, after checking its validity (<B>as reference</B>)
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Type_Exception</SAMP>.</P>
   *
   * @param mixed $mInputName HTML's input name (to look for in $_FILES super-global variable)
   * @param array|string $asExtensions List of allowed file extensions (case-insensitive; ignored if <SAMP>empty</SAMP>)
   * @param integer $iMaxByteSize Maximum allowed size (in bytes; ignored if lower-or-equal than <SAMP>0</SAMP>)
   * @return boolean
   */
  static public function &useUploadedFile( $mInputName, $asExtensions = null, $iMaxByteSize = null )
  {
    // Sanitize input
    $mInputName = PHP_APE_Type_Index::parseValue( $mInputName );
    $asExtensions = PHP_APE_Type_Array::parseValue( $asExtensions );
    if( count( $asExtensions ) > 0 ) $asExtensions = array_unique( explode( '|', strtolower( implode( '|', $asExtensions ) ) ) );
    $iMaxByteSize = PHP_APE_Type_Integer::parseValue( $iMaxByteSize );

    // Check uploaded file
    if( !array_key_exists( $mInputName, $_FILES ) )
      throw new PHP_APE_Type_Exception( __METHOD__, 'No matching file uploaded' );
    $roUploadedFile =& $_FILES[$mInputName];
    if( array_key_exists( 'error', $roUploadedFile ) and $roUploadedFile['error'] != 0 )
      throw new PHP_APE_Type_Exception( __METHOD__, 'File upload error; Code: '.$roUploadedFile['error'] );
    if( !array_key_exists( 'tmp_name', $roUploadedFile ) or !is_uploaded_file( $roUploadedFile['tmp_name'] ) )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Uploaded file not available' );
    $asFileInfo = pathinfo( $roUploadedFile['name'] );
    if( count( $asExtensions ) > 0 and ( !array_key_exists( 'extension', $asFileInfo ) or !in_array( strtolower( $asFileInfo['extension'] ), $asExtensions ) ) )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid file format/extension' );
    if( $iMaxByteSize > 0 and ( !array_key_exists( 'size', $roUploadedFile ) or $roUploadedFile['size'] > $iMaxByteSize ) )
      throw new PHP_APE_Type_Exception( __METHOD__, 'Invalid file size (too big)' );
    return $roUploadedFile;
  }

}
