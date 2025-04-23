<?php 

use Illuminate\Support\Facades\Request;

function isActiveRoute($routeName, $slug = null) {
    $targetUrl = $slug ? route($routeName, $slug) : route($routeName);
    $currentUrl = url()->current();
 
    if ($currentUrl === $targetUrl) {
        return 'text-indigo-600 border-indigo-600 font-bold scale-105 transition-all duration-300';
    }
 
    return 'border-transparent hover:text-indigo-400 hover:border-indigo-400 transition-all duration-300';
 }