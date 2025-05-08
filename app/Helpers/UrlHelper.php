<?php

namespace App\Helpers;

class UrlHelper
{
    /**
     * Convert a string to a URL-friendly slug
     *
     * @param string $text
     * @return string
     */
    public static function slugify($text)
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
    public static function deslugify($slug)
    {
        // Replace hyphens with spaces
        return str_replace('-', ' ', $slug);
    }
}