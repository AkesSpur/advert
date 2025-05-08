<?php 

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
function setActive(array $route){
    if(is_array($route)){
       foreach($route as $r){
          if(Request::routeIs($r)){
             return 'active';
          }
       }
    }
 }
function isActiveRoute($routeName, $slug = null) {
    $targetUrl = $slug ? route($routeName, $slug) : route($routeName);
    $currentUrl = url()->current();
 
    if ($currentUrl === $targetUrl) {
        return 'text-white bg-[#6340FF] hover:bg-[#5737e7] transition';
    }
 
    return 'text-gray-400 hover:bg-gray-800 hover:text-white transition';
 }

 function isActiveTab($routeName, $slug = null) {
    $targetUrl = $slug ? route($routeName, $slug) : route($routeName);
    $currentUrl = url()->current();
 
    if ($currentUrl === $targetUrl) {
        return 'text-white';
    }
 
    return 'text-gray-400 hover:text-white transition';
 }

 function formatNumber($number) {
    $phone = preg_replace('/\D/', '', $number);
    $formatted = '+7 (' . substr($phone, 1, 3) . ') ' . substr($phone, 4, 3) . '-' . substr($phone, 7, 2) . '-' . substr($phone, 9, 2);
    return $formatted;
 }

 function text2Slug($string)
    {
        return Str::slug($string);
    }