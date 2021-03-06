<?php

namespace swisscoordinates;

/*
 * The MIT License (MIT)
 * 
 * // Copyright (c) 2014 Federal Office of Topography swisstopo, Wabern, CH
 * // Permission is hereby granted, free of charge, to any person obtaining a copy
 * // of this software and associated documentation files (the "Software"), to deal
 * // in the Software without restriction, including without limitation the rights
 * // to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * // copies of the Software, and to permit persons to whom the Software is
 * // furnished to do so, subject to the following conditions:
 * // 
 * // The above copyright notice and this permission notice shall be included in
 * // all copies or substantial portions of the Software.
 * // 
 * // THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * // IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * // FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * // AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * // LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * // OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * // THE SOFTWARE.
 * 
 * Please validate your results with NAVREF on-line service: http://www.swisstopo.admin.ch/internet/swisstopo/en/home/apps/calc/navref.html (difference ~ 1-2m)
 * 
 * Original Source Last Update: 9 dec 2014
 * 
 * @link http://www.swisstopo.admin.ch/internet/swisstopo/de/home/products/software/products/skripts.html
 * @link http://www.swisstopo.admin.ch/internet/swisstopo/en/home/topics/survey/sys/refsys/projections.html (see PDFs under "Documentation")
 */

/**
 * Convert WGS (Word Geodetic System) coordinates into CH (Swiss-Coordinates) and vice versa.
 * 
 * Informations about Coordinates:
 * 
 * + Lat/Long = Latitude And Longitude
 * + WGS = Word Geodetic System
 * + Lat = Y-Axis
 * + Long = X-Axis
 * 
 * Example swiss coordaintes:
 * 
 * + Rigi: Y = 679520, X = 212273
 * + Zürich-Seebach: Y = 684592, X = 252857
 * 
 * Usage example:
 * 
 * ```php
 * use swisscoordinates\Converter;
 * 
 * echo Converter::CHtoWGSlat(679520, 212273); // 47.056709
 * echo Converter::CHtoWGSlong(679520, 212273); // 8.485306
 * ```
 * 
 * @author Federal Office of Topography swisstopo, Wabern, CH
 * @author Basil Suter <basil@nadar.io>
 */
class Converter
{
    /**
     * @var string Version Number of Converter class.
     */
    const VERSION = '1.0.0';
    
    /**
     * Convert WGS lat/long (° dec) to CH y
     * 
     * @param unknown $lat
     * @param unknown $long
     * @return number
     */
    public static function WGStoCHy($lat, $long)
    {
        // Converts decimal degrees sexagesimal seconds
        $lat = DECtoSEX($lat);
        $long = DECtoSEX($long);
    
        // Auxiliary values (% Bern)
        $lat_aux = ($lat - 169028.66)/10000;
        $long_aux = ($long - 26782.5)/10000;
    
        // Process Y
        $y = 600072.37
        + 211455.93 * $long_aux
        -  10938.51 * $long_aux * $lat_aux
        -      0.36 * $long_aux * pow($lat_aux, 2)
        -     44.54 * pow($long_aux, 3);
         
        return $y;
    }
    
    /**
     * Convert WGS lat/long (° dec) to CH x
     * @param unknown $lat
     * @param unknown $long
     * @return number
     */
    public static function WGStoCHx($lat, $long)
    {
        // Converts decimal degrees sexagesimal seconds
        $lat = DECtoSEX($lat);
        $long = DECtoSEX($long);
    
        // Auxiliary values (% Bern)
        $lat_aux = ($lat - 169028.66)/10000;
        $long_aux = ($long - 26782.5)/10000;
    
        // Process X
        $x = 200147.07
        + 308807.95 * $lat_aux
        +   3745.25 * pow($long_aux, 2)
        +     76.63 * pow($lat_aux, 2)
        -    194.56 * pow($long_aux, 2) * $lat_aux
        +    119.79 * pow($lat_aux, 3);
         
        return $x;
    }
    
    /**
     * // Convert CH y/x to WGS lat
     * @param unknown $y
     * @param unknown $x
     * @return number
     */
    public static function CHtoWGSlat($y, $x)
    {
        // Converts military to civil and  to unit = 1000km
        // Auxiliary values (% Bern)
        $y_aux = ($y - 600000)/1000000;
        $x_aux = ($x - 200000)/1000000;
    
        // Process lat
        $lat = 16.9023892
        +  3.238272 * $x_aux
        -  0.270978 * pow($y_aux, 2)
        -  0.002528 * pow($x_aux, 2)
        -  0.0447   * pow($y_aux, 2) * $x_aux
        -  0.0140   * pow($x_aux, 3);
    
        // Unit 10000" to 1 " and converts seconds to degrees (dec)
        $lat = $lat * 100/36;
    
        return $lat;
    }
    
    /**
     * // Convert CH y/x to WGS long
     * 
     * @param unknown $y
     * @param unknown $x
     * @return number
     */
    public static function CHtoWGSlong($y, $x)
    {
        // Converts military to civil and  to unit = 1000km
        // Auxiliary values (% Bern)
        $y_aux = ($y - 600000)/1000000;
        $x_aux = ($x - 200000)/1000000;
    
        // Process long
        $long = 2.6779094
        + 4.728982 * $y_aux
        + 0.791484 * $y_aux * $x_aux
        + 0.1306   * $y_aux * pow($x_aux, 2)
        - 0.0436   * pow($y_aux, 3);
         
        // Unit 10000" to 1 " and converts seconds to degrees (dec)
        $long = $long * 100/36;
         
        return $long;
    }
    
    /**
     * Convert DEC angle to SEX DMS
     * @param unknown $angle
     * @return number
     */
    public static function DECtoSEX($angle)
    {
        // Extract DMS
        $deg = intval($angle);
        $min = intval(($angle-$deg)*60);
        $sec =  ((($angle-$deg)*60)-$min)*60;
    
        // Result in sexagesimal seconds
        return $sec + $min*60 + $deg*3600;
    }
}
