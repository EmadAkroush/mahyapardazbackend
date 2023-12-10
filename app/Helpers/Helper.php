<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request as FacadesRequest;

if (!function_exists('usr')) {
    function usr()
    {
        $currentUserID = Auth::user()->id;
        return User::find($currentUserID);
    }
}

if (!function_exists('token')) {
    function token()
    {
        return Auth::user();
    }
}

if (!function_exists('user')) {
    function user()
    {
        return Auth::user();
    }
}

if (!function_exists('getOriginalBaseUrl')) {
    function getOriginalBaseUrl()
    {
        return request()->getSchemeAndHttpHost();
    }
}

if (!function_exists('textToUTF8')) {
    function textToUTF8($text)
    {
        return mb_convert_encoding($text, "Windows-1252", "UTF-8");
    }
}

if (!function_exists('textToDibaEncoding')) {
    function textToDibaEncoding($text)
    {
        $text = mb_convert_encoding($text, "UTF-8", "Windows-1252");
        return str_replace('�', '', $text);
    }
}

if (!function_exists('parseDibaSelects')) {
    function parseDibaSelects($data)
    {
        return preg_split('/\r\n|\r|\n/', $data);
    }
}

if (!function_exists('formatURL')) {
    function formatURL($base, $uri)
    {
        $uri = preg_replace('/%/i', 'درصد', $uri);
        return $base . urlencode($uri);
    }
}

if (!function_exists('ptu')) {
    function ptu($uri)
    {
        $uri = preg_replace('/%/i', 'درصد', $uri);
        $uri = preg_replace('/\//i', '-', $uri);
        $uri = str_replace(' ', '-', strtolower($uri));
        return urlencode($uri);
    }
}

if (!function_exists('makeSlug')) {
    function makeSlug($uri)
    {
        // return Str::slug($uri);
        $uri = preg_replace('/%/i', 'درصد', $uri);
        $uri = preg_replace('/\//i', '-', $uri);
        return $uri;
    }
}

if (!function_exists('imgResize')) {
    function imgResize($uri, $size)
    {
        $file = pathinfo($uri);
        return $file['dirname'] . '/' . $file['filename'] . "-$size." . $file['extension'];
    }
}

if (!function_exists('pn2en')) {
    function pn2en($string)
    {
        $newNumbers = range(0, 9);
        // 1. Persian HTML decimal
        $persianDecimal = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');
        // 2. Arabic HTML decimal
        $arabicDecimal = array('&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;');
        // 3. Arabic Numeric
        $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
        // 4. Persian Numeric
        $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

        $string =  str_replace($persianDecimal, $newNumbers, $string);
        $string =  str_replace($arabicDecimal, $newNumbers, $string);
        $string =  str_replace($arabic, $newNumbers, $string);
        return str_replace($persian, $newNumbers, $string);
    }
}

if (!function_exists('isCurrentRouteByName')) {
    function isCurrentRouteByName($routeName)
    {
        // return FacadesRequest::route()->getName();
        // print_r(FacadesRequest::route()->getName());
        return FacadesRequest::route()->getName() == $routeName;
    }
}
