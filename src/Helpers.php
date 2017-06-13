<?php

if (!function_exists('g_is_valid_lng')) {
    function g_is_valid_lng($lng = null)
    {
        $lng = (float)$lng;
        return $lng >= -180 && $lng <= 180;
    }
}

if (!function_exists('g_is_valid_lat')) {
    function g_is_valid_lat($lat = null)
    {
        $lat = (float)$lat;
        return $lat >= -90 && $lat <= 90;
    }
}

if (!function_exists('g_is_point')) {
    function g_is_point($data = null)
    {
        if (!empty($data)) {
            $json = is_array($data) ? $data : json_encode($data, true);
            $cond1 = is_array($json) && !empty($json['type']) && strtolower($json['type']) == 'point';
            $cond2 = !empty($json['coordinates']) && is_array($json['coordinates']) && count($json['coordinates']) == 2;
            $cond3 = g_is_valid_lng($json['coordinates'][0]) && g_is_valid_lat($json['coordinates'][1]);
            if ($cond1 && $cond2 && $cond3) {
                return $json;
            }
        }

        return false;
    }
}

if (!function_exists('g_is_polygon')) {
    function g_is_polygon($data = null)
    {
        if (!empty($data)) {
            $json = is_array($data) ? $data : json_encode($data, true);
            dd($json);
            $cond1 = is_array($json) && !empty($json['type']) && strtolower($json['type']) == 'point';
            $cond2 = !empty($json['coordinates']) && is_array($json['coordinates']) && count($json['coordinates']) == 2;
            $cond3 = g_is_valid_lng($json['coordinates'][0]) && g_is_valid_lat($json['coordinates'][1]);
            if ($cond1 && $cond2 && $cond3) {
                return $json;
            }
        }

        return false;
    }
}

if (!function_exists('g_point')) {
    function g_point($lng, $lat = null)
    {

        return new \Mammutgroup\Database\Geometries\Point($lng, $lat);
    }
}

if (!function_exists('g_polygon')) {
    function g_polygon($lineString)
    {
        if(is_object($lineString) && is_a($lineString,\Mammutgroup\Database\Geometries\Polygon::class)){
            return $lineString;
        }
        return new \Mammutgroup\Database\Geometries\Polygon($lineString);
    }
}
if (!function_exists('g_linestring')) {
    function g_linestring($points)
    {

        return new \Mammutgroup\Database\Geometries\LineString($points);
    }
}
if (!function_exists('g_linestrings')) {
    function g_linestrings($lineStrings)
    {
        if (is_array($lineStrings)) {
            return new \Mammutgroup\Database\Geometries\MultiLineString($lineStrings);
        }

        return $lineStrings;
    }
}
