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
 * @package PHP_APE_Data
 * @subpackage Classes
 */

/** Filter class
 *
 * @package PHP_APE_Data
 * @subpackage Classes
 */
class PHP_APE_Data_Filter
extends PHP_APE_Data_AssociativeSet
{

  /*
   * METHODS: magic - OVERRIDE
   ********************************************************************************/

  /** Returns a <I>string</I> representation of this object
   *
   * <P><B>INHERITANCE:</B> This method <B>MAY be overridden</B>.</P>
   *
   * @return string
   */
  public function __toString()
  {
    return $this->toProperties();
  }


  /*
   * METHODS: data - OVERRIDE
   ********************************************************************************/

  /** Sets (adds) the given criteria to this filter
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   *
   * @param PHP_APE_Data_FilterCriteria $oCriteria Filter criteria
   * @param mixed $sKey Element's association key (data identifier if <SAMP>null</SAMP>)
   * @param boolean $bAllowOverwrite Allow to overwrite existing data with the same identifier
   */
  public function setElement( PHP_APE_Data_Any $oCriteria, $mKey = null, $bAllowOverwrite = false )
  {
    if( !($oCriteria instanceof PHP_APE_Data_FilterCriteria) )
      throw new PHP_APE_Data_Exception( __METHOD__, 'Invalid criteria object; Class: '.get_class( $oCriteria ) );
    parent::setElement( $oCriteria, $mKey, $bAllowOverwrite );
  }


  /*
   * METHODS: import/export
   ********************************************************************************/
  
  /** Parses (creates) this filter from the given (human-friendly) properties <I>string</I> representation
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   *
   * @param string $sProperties Filter properties string
   * @see PHP_APE_Properties
   */
  public function fromProperties( $sProperties )
  {
    $this->unsetAllElements();
    $asProperties = PHP_APE_Properties::convertString2Array( $sProperties, false );
    foreach( $asProperties as $mID => $sFilterString )
    {
      $oFilter = new PHP_APE_Data_Filter( $mID );
      $oFilter->fromString( $sFilterString );
      $this->setElement( new PHP_APE_Data_FilterCriteria( $mID, $oFilter ) );
    }
  }

  /** Returns the (human-friendly) properties <I>string</I> representation corresponding to this filter
   *
   * @return string
   * @see PHP_APE_Properties
   */
  public function toProperties()
  {
    $asProperties = array();
    foreach( $this->getElementsArray() as $oCriteria ) $asProperties[ $oCriteria->getID() ] = $oCriteria->toString();
    return PHP_APE_Properties::convertArray2String( $asProperties );
  }
  
  /** Parses (creates) this filter from the given (human-friendly) <I>string</I> representation
   *
   * <P><B>THROWS:</B> <SAMP>PHP_APE_Data_Exception</SAMP>.</P>
   *
   * @param string $sFilterString Filter string
   */
  public function fromString( $sFilterString )
  {

    // Sanitize input string
    $sFilterString = trim( $sFilterString, " \t\n\r\0" );

    // Clear filter criteria
    $this->unsetAllElements();
    if( strlen( $sFilterString ) == 0 ) return;

    // Parse string
    $mData = null;
    $bEscape = false;
    $sComparisonOperator = null;
    $iLogical = null;
    $iComparison = null;
    $iQuote = null;
    $iQuoteNesting = 0;
    $bSave = false;
    for( $c = 0, $l = strlen( $sFilterString ), $i = 0; $c < $l; $c++ )
    {
      $sChar = $sFilterString[$c];
      if( $bEscape )
      { // escape character preceded
        if( $iQuote == PHP_APE_Data_QuoteOperator::Parenthesis ) $mData .= '\\'; // escape character is added when not in escaping context
        $mData .= $sChar;
        $bEscape = false;
      }
      else
      {

        switch( $sChar )
        {
        case '\\':
          $bEscape = true;
          break;

        case '\'':
        case '"':
          if( is_null( $iQuote ) )
          { // NOT within quote context
            if( strlen( trim( $mData ) ) == 0 )
            { // open quote
              $iQuote = PHP_APE_Data_QuoteOperator::fromString( $sChar );
              $iQuoteNesting = 1;
              $mData = '';
            }
            else
            { // criteria string is not empty; quote cannot be opened
              $mData .= $sChar;
            }
          }
          elseif( $iQuote == PHP_APE_Data_QuoteOperator::fromString( $sChar ) )
          { // WITHIN quote context; MATCHING quote; close quote
            $bSave = true;
            $iQuoteNesting = 0;
          }
          else
          { // WITHIN quote context; NON-matching quote
            $mData .= $sChar;
          }
          break;

        case '[':
        case '{':
        case '(':
          if( is_null( $iQuote ) )
          { // NOT within quote context
            if( strlen( trim( $mData ) ) == 0 )
            { // open quote
              $iQuote = PHP_APE_Data_QuoteOperator::fromString( $sChar );
              $iQuoteNesting = 1;
              $mData = null;
            }
            else
            { // criteria string is not empty; quote cannot be opened
              $mData .= $sChar;
            }
          }
          else
          { // WITHIN quote context
            if( $iQuote == PHP_APE_Data_QuoteOperator::fromString( $sChar ) ) $iQuoteNesting++; // increase nesting count fro matching quote
            $mData .= $sChar;
          }
          break;

        case ']':
        case '}':
        case ')':
          if( is_null( $iQuote ) )
          { // NOT within quote context (unmatched closing quote)
            $mData .= $sChar;
          }
          elseif( $iQuote == PHP_APE_Data_QuoteOperator::fromString( $sChar ) )
          { // WITHIN quote context; MATCHING quote
            $iQuoteNesting--; // decrease nesting count
            if( $iQuoteNesting <= 0 )
            { // NOT within nested context; close quote
              $bSave = true;
            }
            else
            { // WITHIN nested context
              $mData .= $sChar;
            }
          }
          else
          { // WITHIN quote context; NON-matching quote
            $mData .= $sChar;
          }
          break;

        case '!':
        case '-':
        case '&':
        case '+':
        case '|':
        case '/':
          if( is_null( $iLogical ) and is_null( $iQuote ) and strlen( trim( $mData ) ) == 0 )
          { // Logical operator NOT defined and parsing NOT within quote or criteria context
            $sChar = strtr( $sChar, '-+/', '!&|' ); // translate commonly-used logical operator into their PHP-APE equivalent
            $iLogical = PHP_APE_Data_LogicalOperator::fromString( $sChar );
            $mData = null;
          }
          else
          {
            $mData .= $sChar;
          }
          break;

        case '<':
        case '>':
          if( is_null( $iComparison ) and is_null( $iQuote ) and strlen( trim( $mData ) ) == 0 )
          { // Comparison operator NOT defined and parsing NOT within quote or criteria context
            if( !is_null( $sComparisonOperator ) )
            { // WITHIN operator context (NOTE: comparison operators can be two characters long)
              $sComparisonOperator .= $sChar;
              $iComparison = PHP_APE_Data_ComparisonOperator::fromString( $sComparisonOperator );
              $sComparisonOperator = null;
              $mData = null;
            }
            else
            { // NOT within operator context
              $sComparisonOperator = $sChar;
            }
          }
          else
          {
            $mData .= $sChar;
          }
          break;

        case '=':
        case '~':
        case '¬':
          if( is_null( $iComparison ) and is_null( $iQuote ) and strlen( trim( $mData ) ) == 0 )
          { // Comparison operator NOT defined and parsing NOT within quote or criteria context
            $sComparisonOperator .= $sChar;
            $iComparison = PHP_APE_Data_ComparisonOperator::fromString( $sComparisonOperator );
            $sComparisonOperator = null;
            $mData = null;
          }
          else
          {
            $mData .= $sChar;
          }
          break;

        case "\t":
        case "\n":
        case "\r":
        case "\0":
          if( is_null( $iQuote ) )
          { // NOT within quote context; save criteria
            if( strlen( trim( $mData ) ) > 0 )
            { // WITHIN criteria context
              $bSave = true;
              $mData = trim( $mData, "\t\n\r\0" );
            }
            // ELSE: ignore blank
          }
          else
          {
            // WITHIN quote context
            $mData .= $sChar;
          }
          break;

        default:
          $mData .= $sChar;

        }

      }

      // Save criteria (if appropriate)
      if( $bSave or ( $c == $l-1 )  )
      {
        $bSave = true;

        // Close unbalanced quote
        if( !is_null( $iQuote ) )
        {
          while( --$iQuoteNesting > 0 ) $mData .= PHP_APE_Data_QuoteOperator::toClosingString( $iQuote );
        }

        // Check nesting quote
        if( !is_null( $iQuote ) and $iQuote == PHP_APE_Data_QuoteOperator::Parenthesis )
        {
          $oFilter = new PHP_APE_Data_Filter( $i );
          $oFilter->fromString( $mData );
          $mData = $oFilter;
        }

        // Check comparison
        if( !is_null( $sComparisonOperator ) ) $iComparison = PHP_APE_Data_ComparisonOperator::fromString( $sComparisonOperator );

        // Check criteria (do not save empty and unquoted criteria)
        if( ($mData instanceof PHP_APE_Data_Filter) )
        {
          if( $mData->countElements() <= 0 )
            $bSave = false;
        }
        else
        {
          if( is_null( $iQuote ) )
            $mData = trim( $mData );
          if( strlen( $mData ) > 0 or !is_null( $iQuote ) )
          {
            if( is_null( $iComparison ) and preg_match( '/[a-z]/i', $mData ) )
            { // criteria contains alphabetic character
              // defaults comparison to proportional comparison
              $iComparison = PHP_APE_Data_ComparisonOperator::Proportional;
            }
          }
          else
            $bSave = false;
        }

        // Save criteria
        if( $bSave )
        {
          $oCriteria = new PHP_APE_Data_FilterCriteria( $i++, $mData );
          if( !is_null( $iLogical ) ) $oCriteria->setOperator( $iLogical );
          if( !is_null( $iComparison ) ) $oCriteria->setOperator( $iComparison );
          if( !is_null( $iQuote ) ) $oCriteria->setOperator( $iQuote );
          $this->setElement( $oCriteria );
        }

        // Reset parsing state
        $oCriteria = null;
        $mData = null;
        $bEscape = false;
        $sComparisonOperator = null;
        $iLogical = null;
        $iComparison = null;
        $iQuote = null;
        $iQuoteNesting = 0;
        $bSave = false;

      }

    }

  }

  /** Returns the (human-friendly) <I>string</I> representation corresponding to this filter
   *
   * @return string
   */
  public function toString()
  {

    // Check content
    if( $this->countElements() <=0 ) return null;

    // Loop through criterias
    $sFilterString = null;
    foreach( $this->getElementsKeys() as $mKey )
    {
      // Retrieve criteria
      $oCriteria = $this->getElement( $mKey );

      // Add space
      if( strlen( $sFilterString ) > 0 ) $sFilterString .= ' ';

      // Add criteria
      $sFilterString .= $oCriteria->toString();
    }

    // END
    return $sFilterString;
  }

}
