<?php 

use Illuminate\Support\Facades\Request;

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