<?php

use Carbon\Carbon;
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



 function slugify($text)
    {
        // Transliterate non-Latin characters to Latin
        $text = transliterator_transliterate('Any-Latin; Latin-ASCII; [^\\w\\s-] remove; Lower()', $text);
        
        // Replace spaces with hyphens
        $text = str_replace(' ', '-', $text);
        
        // Remove any remaining non-alphanumeric characters except hyphens
        $text = preg_replace('/[^\w\-]+/', '', $text);
        
        // Remove duplicate hyphens
        $text = preg_replace('/-+/', '-', $text);
        
        // Trim hyphens from beginning and end
        return trim($text, '-');
    }
    
    /**
     * Convert a slug back to its original form (approximate)
     *
     * @param string $slug
     * @return string
     */
 function deslugify($slug)
    {
        // Replace hyphens with spaces
        return str_replace('-', ' ', $slug);
    }

     /**

     */
     function formatLastActive($lastActive)
    {
        if (!$lastActive) {
            return 'недавно';
        }
        
        $now = Carbon::now();
        
         // Convert string to Carbon instance if necessary
    if (!($lastActive instanceof Carbon)) {
        $lastActive = Carbon::parse($lastActive);
    }

        // If active within the last 5 minutes
        if ($lastActive->diffInMinutes($now) < 5) {
            return 'онлайн';
        }
        
        // If active today
        if ($lastActive->isToday()) {
            return 'сегодня, ' . $lastActive->format('H:i');
        }
        
        // If active yesterday
        if ($lastActive->isYesterday()) {
            return 'вчера, ' . $lastActive->format('H:i');
        }
        
        // If active within the last week
        if ($lastActive->diffInDays($now) < 7) {
            return $lastActive->locale('ru')->dayName . ', ' . $lastActive->format('H:i');
        }
        
        // Otherwise return the date
        return $lastActive->format('d.m.Y');
    }