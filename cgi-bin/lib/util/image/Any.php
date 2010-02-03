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
 * @package PHP_APE_Util
 * @subpackage Image
 */

/** Image-related core utility class
 *
 * @package PHP_APE_Util
 * @subpackage Image
 */
class PHP_APE_Util_Image_Any
{

  /*
   * METHODS: information
   ********************************************************************************/

  /** Returns the string representation for the given (numerical) format
   *
   * @param integer $iFormat Image format (see {@link getimagesize()})
   * @return string
   */
  public static function getFormatString( $iFormat )
  {
    // Output
    switch( $iFormat )
    {
    case 1: return 'gif';
    case 2: return 'jpeg';
    case 3: return 'png';
    case 4: return 'swf';
    case 5: return 'psd';
    case 6: return 'bmp';
    case 7: return 'tiff';
    case 8: return 'tiff';
    case 9: return 'jpc';
    case 10: return 'jp2';
    case 11: return 'jpx';
    case 12: return 'jb2';
    case 13: return 'swc';
    case 14: return 'iff';
    case 15: return 'wbmp';
    case 16: return 'xbm';
    }
    return null;
  }


  /*
   * METHODS: size
   ********************************************************************************/

  /** Returns the 2-dimensional <I>array</I> for the given width and height
   *
   * @param integer $iWidth Image width
   * @param integer $iHeight Image height (defaults to image width if <SAMP>null</SAMP>)
   * @return array|integer
   */
	public static function getDimension( $iWidth, $iHeight = null )
  {
		$iWidth = (integer)$iWidth;
		$iHeight = (integer)$iHeight; if( $iHeight <= 0 ) $iHeight = $iWidth;
    return array( $iWidth, $iHeight );
	}

  /** Returns whether the left-side dimension is bigger than the right-side dimension
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Util_Image_Exception</SAMP>.</P>
   *
   * @param array|integer $aiDimension-left Image dimension (comparison left-side)
   * @param array|integer $aiDimension_right Image dimension (comparison right-side)
   * @param boolean $bStrictlyBigger Both width AND height must be bigger (defaults: OR)
   * @return boolean
   */
	public static function isBiggerThan( $aiDimension_left, $aiDimension_right, $bStrictlyBigger = false )
  {
    // Check input
    if( !is_array( $aiDimension_left ) or count( $aiDimension_left ) < 2 )
      throw new PHP_APE_Util_Image_Exception( __METHOD__, 'Invalid (left-side) dimension' );
    if( !is_array( $aiDimension_right ) or count( $aiDimension_right ) < 2 )
      throw new PHP_APE_Util_Image_Exception( __METHOD__, 'Invalid (right-side) dimension' );

    // Output
    return
      $bStrictlyBigger ?
      ( $aiDimension_left[0] > $aiDimension_right[0] and $aiDimension_left[1] > $aiDimension_right[1] ) :
      ( $aiDimension_left[0] > $aiDimension_right[0] or $aiDimension_left[1] > $aiDimension_right[1] );
	}

  /** Returns whether the left-side dimension is smaller than the right-side dimension
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Util_Image_Exception</SAMP>.</P>
   *
   * @param array|integer $aiDimension-left Image dimension (comparison left-side)
   * @param array|integer $aiDimension_right Image dimension (comparison right-side)
   * @return boolean
   */
	public static function isSmallerThan( $aiDimension_left, $aiDimension_right )
  {
    // Check input
    if( !is_array( $aiDimension_left ) or count( $aiDimension_left ) < 2 )
      throw new PHP_APE_Util_Image_Exception( __METHOD__, 'Invalid (left-side) dimension' );
    if( !is_array( $aiDimension_right ) or count( $aiDimension_right ) < 2 )
      throw new PHP_APE_Util_Image_Exception( __METHOD__, 'Invalid (right-side) dimension' );

    // Output
    return ( $aiDimension_left[0] < $aiDimension_right[0] and $aiDimension_left[1] < $aiDimension_right[1] );
	}

  /** Returns the dimension <I>array</I> corresponding to the given dimension multiplied by the given ration
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Util_Image_Exception</SAMP>.</P>
   *
   * @param array|integer $aiDimension Image dimension
   * @param float $fRatio Re-dimensioning ratio
   * @return array|integer
   */
	public static function getDimensionRatio( $aiDimension, $fRatio )
  {
    // Check input
    if( !is_array( $aiDimension ) or count( $aiDimension ) != 2 )
      throw new PHP_APE_Util_Image_Exception( __METHOD__, 'Invalid dimension' );

    // Output
		$fRatio = (float)$fRatio;
		return array( (integer)round( $aiDimension[0]*$fRatio ), (integer)round( $aiDimension[1]*$fRatio ) );
	}

  /** Returns the dimension <I>array</I> corresponding to the given dimension resized (up/down) to match the given gauge
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Util_Image_Exception</SAMP>.</P>
   *
   * @param array|integer $aiDimension Image dimension
   * @param array|integer $aiGauge Resizing gauge
   * @param boolean $bDownsize Allow down-sizing
   * @param boolean $bUpsize Allow up-sizing
   * @return array|integer
   */
	public static function getDimensionGauge( $aiDimension, $aiGauge, $bDownsize = true, $bUpsize = false )
  {
    // Check input
    if( !is_array( $aiDimension ) or count( $aiDimension ) != 2 )
      throw new PHP_APE_Util_Image_Exception( __METHOD__, 'Invalid dimension' );
    if( !is_array( $aiGauge ) or count( $aiGauge ) != 2 )
      throw new PHP_APE_Util_Image_Exception( __METHOD__, 'Invalid gauge' );

    // Output
		$fRatio_X = $aiDimension[0] ? $aiGauge[0] / $aiDimension[0] : 1;
		$fRatio_Y = $aiDimension[1] ? $aiGauge[1] / $aiDimension[1] : 1;
		if( self::isBiggerThan( $aiDimension, $aiGauge ) and $bDownsize )
			return self::getDimensionRatio( $aiDimension, $fRatio_X < $fRatio_Y ? $fRatio_X : $fRatio_Y );
		if( self::isSmallerThan( $aiDimension, $aiGauge) and $bUpsize )
			return self::getDimensionRatio( $aiDimension, $fRatio_X < $fRatio_Y ? $fRatio_X : $fRatio_Y );
    return $aiDimension;
	}

  /** Resamples the given input image using the GD library
   *
   * <P><B>NOTE:</B> If no output file is supplied (left <SAMP>null</SAMP>), the resampled image will be output directly
   * to the browser, in which case the appropriate HTTP header is automatically added.</P>
   * <P><B>WARNING:</B> This method will fail if images get so big that their internal GD representation overflows PHP memory limit.</P>
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Util_Image_Exception</SAMP>.</P>
   *
   * @param string $sFilePath_in Input image file's (full) path
   * @param array|integer $aiDimension_out Output image dimension
   * @param string $sFilePath_out Output image file's (full) path
   * @param integer $iQuality Format-specific compression quality
   * @todo Add copyright (imagestring) and watermarking (imagecopymerge) features
   */
	public static function resampleWithGD( $sFilePath_in, $aiDimension_out, $sFilePath_out = null, $iQuality = null )
  {
    // Sanitize/check input
    $sFilePath_in = PHP_APE_Type_Path::parseValue( $sFilePath_in );
    if( !is_array( $aiDimension_out ) or count( $aiDimension_out ) < 2 )
      throw new PHP_APE_Util_Image_Exception( __METHOD__, 'Invalid (output) dimension' );
    $sFilePath_out = PHP_APE_Type_Path::parseValue( $sFilePath_out );
    $iQuality = (integer)$iQuality;

    // Retrieve image info
    $amImageInfo = @getImageSize( $sFilePath_in );
    if( !is_array( $amImageInfo ) )
      throw new PHP_APE_Util_Image_Exception( __METHOD__, 'Failed to obtain image data from file; Path: '.$sFilePath_in );
    $iWidth = $amImageInfo[0];
    $iHeight = $amImageInfo[1];
    $iFormat = $amImageInfo[2];

    // Resample
    $gdImage_out = null;
    $gdImage_in = null;
    try
    {

      // Create output image
      $gdImage_out = imagecreatetruecolor( $aiDimension_out[0], $aiDimension_out[1] );
      if( !$gdImage_out )
        throw new PHP_APE_Util_Image_Exception( __METHOD__, 'Failed to create output image' );

      // Resample
      switch( $iFormat )
      {

      case 1: // GIF
        // ... load input image
        $gdImage_in = imagecreatefromgif( $sFilePath_in );
        if( !$gdImage_in )
          throw new PHP_APE_Util_Image_Exception( __METHOD__, 'Failed to load input image; Path: '.$sFilePath_in );

        // ... resample
        if( !imagecopyresampled( $gdImage_out, $gdImage_in, 0, 0, 0, 0, $aiDimension_out[0], $aiDimension_out[1], $iWidth, $iHeight ) )
          throw new PHP_APE_Util_Image_Exception( __METHOD__, 'Failed to resample image; Path: '.$sFilePath_in );

        // ... output
        if( is_null( $sFilePath_out ) and !headers_sent() )
          header( 'Content-type: '.image_type_to_mime_type( $iFormat ) );
        imagegif( $gdImage_out, $sFilePath_out );
        break;

      case 2: // JPEG
        // ... load input image
        $gdImage_in = imagecreatefromjpeg( $sFilePath_in );
        if( !$gdImage_in )
          throw new PHP_APE_Util_Image_Exception( __METHOD__, 'Failed to load input image; Path: '.$sFilePath_in );

        // ... resample
        if( !imagecopyresampled( $gdImage_out, $gdImage_in, 0, 0, 0, 0, $aiDimension_out[0], $aiDimension_out[1], $iWidth, $iHeight ) )
          throw new PHP_APE_Util_Image_Exception( __METHOD__, 'Failed to resample image; Path: '.$sFilePath_in );

        // ... check parameters
        if( $iQuality == 0 ) $iQuality = 80;
        elseif( $iQuality < 20 ) $iQuality = 20;
        elseif( $iQuality > 100 ) $iQuality = 100;
        
        // ... output
        if( is_null( $sFilePath_out ) and !headers_sent() )
          header( 'Content-type: '.image_type_to_mime_type( $iFormat ) );
        imagejpeg( $gdImage_out, $sFilePath_out, $iQuality );
        break;

      case 3: // PNG
        // ... load input image
        $gdImage_in = imagecreatefrompng( $sFilePath_in );
        if( !$gdImage_in )
          throw new PHP_APE_Util_Image_Exception( __METHOD__, 'Failed to load input image; Path: '.$sFilePath_in );

        // ... resample
        if( !imagecopyresampled( $gdImage_out, $gdImage_in, 0, 0, 0, 0, $aiDimension_out[0], $aiDimension_out[1], $iWidth, $iHeight ) )
          throw new PHP_APE_Util_Image_Exception( __METHOD__, 'Failed to resample image; Path: '.$sFilePath_in );

        // ... output
        if( is_null( $sFilePath_out ) and !headers_sent() )
          header( 'Content-type: '.image_type_to_mime_type( $iFormat ) );
        imagepng( $gdImage_out, $sFilePath_out );
        break;

      default: // unsupported image format
        throw new PHP_APE_Util_Image_Exception( __METHOD__, 'Unsupported (input) image format; Format: '.self::getFormatString( $iFormat ) );

      }

      // Free resources
      @imagedestroy( $gdImage_out );
      @imagedestroy( $gdImage_in );

    }
    catch( PHP_APE_Util_Image_Exception $e )
    {
      // Free resources
      if( !is_null( $gdImage_out ) ) @imagedestroy( $gdImage_out );
      if( !is_null( $gdImage_in ) ) @imagedestroy( $gdImage_in );

      // Throw exception out
      throw $e;
    }
  
  }

  /** Resamples the given input image using Image Magick
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Util_Image_Exception</SAMP>.</P>
   *
   * @param string $sFilePath_in Input image file's (full) path
   * @param array|integer $aiDimension_out Output image dimension
   * @param string $sFilePath_out Output image file's (full) path
   * @param integer $iQuality Format-specific compression quality
   * @param boolean $bRemoveProfile Remove image's embedded color profile
   * @param string $sCopyright Copyright string
   * @param string $sWatermarkPath Watermark image file's (full) path
   */
	public static function resampleWithIM( $sFilePath_in, $aiDimension_out, $sFilePath_out, $iQuality = null, $bRemoveProfile = true, $sCopyright = null, $sWatermarkPath = null )
  {
    // Sanitize/check input
    $sFilePath_in = PHP_APE_Type_Path::parseValue( $sFilePath_in );
    if( !is_array( $aiDimension_out ) or count( $aiDimension_out ) < 2 )
      throw new PHP_APE_Util_Image_Exception( __METHOD__, 'Invalid (output) dimension' );
    $sFilePath_out = PHP_APE_Type_Path::parseValue( $sFilePath_out );
    $iQuality = (integer)$iQuality;
    $bRemoveProfile = (boolean)$bRemoveProfile;
    $sCopyright = PHP_APE_Type_String::parseValue( $sCopyright );
    $sWatermarkPath = PHP_APE_Type_Path::parseValue( $sWatermarkPath );

    // Retrieve image info
    $amImageInfo = @getImageSize( $sFilePath_in );
    if( !is_array( $amImageInfo ) )
      throw new PHP_APE_Util_Image_Exception( __METHOD__, 'Failed to obtain image data from file; Path: '.$sFilePath_in );
    $iWidth = $amImageInfo[0];
    $iHeight = $amImageInfo[1];
    $iFormat = $amImageInfo[2];

    // Check format
    $sOption_Quality = null;
    switch( $iFormat )
    {

      case 1: // GIF
        // OK
        break;

      case 2: // JPEG
        // ... check parameter
        if( $iQuality == 0 ) $iQuality = 80;
        elseif( $iQuality < 20 ) $iQuality = 20;
        elseif( $iQuality > 100 ) $iQuality = 100;
        $sOption_Quality = '-quality '.$iQuality;
        break;

      case 3: // PNG
        // ... check parameter
        if( $iQuality == 0 ) $iQuality = 8;
        elseif( $iQuality < 2 ) $iQuality = 2;
        elseif( $iQuality > 10 ) $iQuality = 10;
        $sOption_Quality = '-quality '.$iQuality;
        break;

      default: // unsupported image format
        throw new PHP_APE_Util_Image_Exception( __METHOD__, 'Unsupported (input) image format; Format: '.self::getFormatString( $iFormat ) );

    }


    // Build parameters

    // ... resize
		$sOption_Resize = '-size '.escapeshellarg( $aiDimension_out[0].'x'.$aiDimension_out[1] ).' -resize '.escapeshellarg( $aiDimension_out[0].'x'.$aiDimension_out[1].'>' );

    // ... profile
    $sOption_Profile = null;
		if( $bRemoveProfile )
      $sOption_Profile ='+profile '.escapeshellarg( '*' );

    // ... copyright
    $sOption_Copyright = null;
		if( is_null( $sWatermarkPath ) and strlen( $sCopyright ) > 0 )
    {
			$X0 = 10;
      $Y0 = $aiDimension_out[1]-30;
			$X1 = $aiDimension_out[0]-10;
      $Y1 = $aiDimension_out[1]-10;
			$sOption_Copyright = '-stroke black';
      $sOption_Copyright .= ' -fill white -draw '.escapeshellarg( 'rectangle '.$X0.','.$Y0.' '.$X1.','.$Y1 );
      $sOption_Copyright .= ' -fill black -draw '.escapeshellarg( 'gravity South text 0,27 "'.addCSlashes( $sCopyright,'"' ).'"'  );
		}

    // ... command / watermark
    $sCommand = 'convert';
		if( !is_null( $sWatermarkPath ) )
    {
			// ... detect watermarking location and type from file name
			$sWatermarkName = basename( $sWatermarkPath );

			// ... location
			$sOption_Gravity = 'center';
			if( preg_match( '/[-.]north[-.]/i', $sWatermarkName ) ) $sOption_Gravity = 'north';
			elseif( preg_match( '/[-.]northeast[-.]/i', $sWatermarkName ) ) $sOption_Gravity = 'northeast';
			elseif( preg_match( '/[-.]east[-.]/i', $sWatermarkName ) ) $sOption_Gravity = 'east';
			elseif( preg_match( '/[-.]southeast[-.]/i', $sWatermarkName ) ) $sOption_Gravity = 'southeast';
			elseif( preg_match( '/[-.]south[-.]/i', $sWatermarkName ) ) $sOption_Gravity = 'south';
			elseif( preg_match( '/[-.]southwest[-.]/i', $sWatermarkName ) ) $sOption_Gravity = 'southwest';
			elseif( preg_match( '/[-.]west[-.]/i', $sWatermarkName ) ) $sOption_Gravity = 'west';
			elseif( preg_match( '/[-.]northwest[-.]/i', $sWatermarkName ) ) $sOption_Gravity = 'northwest';

			// ... composition type
			$sOption_Compose = 'multiply';
			if( preg_match( '/[-.]over[-.]/i', $sWatermarkName ) ) $sOption_Compose = 'over';
			elseif( preg_match( '/[-.]in[-.]/i', $sWatermarkName ) ) $sOption_Compose = 'in';
			elseif( preg_match( '/[-.]out[-.]/i', $sWatermarkName ) ) $sOption_Compose = 'out';
			elseif( preg_match( '/[-.]atop[-.]/i', $sWatermarkName ) ) $sOption_Compose = 'atop';
			elseif( preg_match( '/[-.]xor[-.]/i', $sWatermarkName ) ) $sOption_Compose = 'xor';
			elseif( preg_match( '/[-.]plus[-.]/i', $sWatermarkName ) ) $sOption_Compose = 'plus';
			elseif( preg_match( '/[-.]minus[-.]/i', $sWatermarkName ) ) $sOption_Compose = 'minus';
			elseif( preg_match( '/[-.]difference[-.]/i', $sWatermarkName ) ) $sOption_Compose = 'difference';
			elseif( preg_match( '/[-.]bumpmap[-.]/i', $sWatermarkName ) ) $sOption_Compose = 'bumpmap';

			// ... command
			$sCommand = 'composite -gravity '.$sOption_Gravity.' -compose '.$sOption_Compose.' '.escapeshellarg( $sWatermarkPath );
		}

		// Execute command
    $sCommand .= ' '.$sOption_Resize;
    $sCommand .= ' '.$sOption_Copyright;
    $sCommand .= ' '.$sOption_Profile;
    $sCommand .= ' '.$sOption_Quality;
    $sCommand .= ' '.escapeshellarg( $sFilePath_in );
    $sCommand .= ' '.escapeshellarg( $sFilePath_out );
		umask(0111);
		exec( $sCommand, $asOutput, $iExit );
		if( $iExit != 0 )
      throw new PHP_APE_Util_Image_Exception( __METHOD__, 'Failed to execute command; Command: '.$sCommand.( isset( $asOutput[0] ) ? '; Output: '.$asOutput[0] : null ) );

  }

}
