<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CleanUrlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $path = $request->path();
        $queryString = $request->getQueryString();
        
        // Only process specific routes that might contain category slugs
        if ($this->shouldProcessPath($path)) {
            // Check if the path contains special characters that need cleaning
            $cleanedPath = $this->cleanUrlPath($path);
            
            // If the path has been cleaned, redirect to the clean version
            if ($cleanedPath !== $path) {
                $redirectUrl = url($cleanedPath);
                if ($queryString) {
                    $redirectUrl .= '?' . $queryString;
                }
                
                return redirect($redirectUrl, 301); // 301 for permanent redirect
            }
        }
        
        return $next($request);
    }
    
    /**
     * Check if the path should be processed for cleaning
     */
    public function shouldProcessPath($path)
    {
        // Process paths that contain deals, pages, blogs, or other content routes
        $patterns = [
            'deals/',
            'pages/',
            'blog/',
            'events/',
            'groups/',
            'marketplace/',
        ];
        
        foreach ($patterns as $pattern) {
            if (strpos($path, $pattern) !== false) {
                return true;
            }
        }
        
        // Also process restaurant/business page URLs that follow the pattern:
        // {city_slug}/{area_slug}/{category_slug}/{item_slug}
        $segments = explode('/', trim($path, '/'));
        if (count($segments) === 4) {
            // Check if the last segment (item_slug) ends with a numeric suffix
            $item_slug = end($segments);
            if (preg_match('/^(.+)-(\d+)$/', $item_slug)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Clean URL path by removing or replacing special characters
     */
    public function cleanUrlPath($path)
    {
        // Decode HTML entities first (like &amp; to &)
        $path = html_entity_decode($path, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Split the path into segments
        $segments = explode('/', $path);
        
        // Handle numeric suffix removal for restaurant/business page URLs
        if (count($segments) === 4) {
            $item_slug = end($segments);
            if (preg_match('/^(.+)-(\d+)$/', $item_slug, $matches)) {
                $segments[3] = $matches[1]; // Replace with base slug without numeric suffix
            }
        }
        
        // Clean each segment individually
        $cleanedSegments = [];
        foreach ($segments as $segment) {
            $cleanedSegments[] = $this->cleanSegment($segment);
        }
        
        // Rebuild the path
        $cleanedPath = implode('/', $cleanedSegments);
        
        // Remove multiple consecutive slashes
        $cleanedPath = preg_replace('/\/+/', '/', $cleanedPath);
        
        // Remove leading and trailing slashes
        $cleanedPath = trim($cleanedPath, '/');
        
        return $cleanedPath;
    }
    
    /**
     * Clean individual URL segment
     */
    private function cleanSegment($segment)
    {
        // Define special character replacements
        $replacements = [
            '&' => 'and',
            '+' => 'plus',
            '@' => 'at',
            '#' => 'hash',
            '$' => 'dollar',
            '%' => 'percent',
            '^' => 'power',
            '*' => 'star',
            '(' => '',
            ')' => '',
            '[' => '',
            ']' => '',
            '{' => '',
            '}' => '',
            '<' => '',
            '>' => '',
            '|' => '',
            '\\' => '',
            '?' => '',
            '!' => '',
            '.' => '',
            ',' => '',
            ';' => '',
            ':' => '',
            '"' => '',
            "'" => '',
            '`' => '',
            '~' => '',
            '=' => '',
        ];
        
        // Apply replacements
        $segment = str_replace(array_keys($replacements), array_values($replacements), $segment);
        
        // Remove any remaining special characters except letters, numbers, and hyphens
        $segment = preg_replace('/[^A-Za-z0-9\-]/', '', $segment);
        
        // Replace multiple consecutive hyphens with single hyphen
        $segment = preg_replace('/-+/', '-', $segment);
        
        // Remove leading and trailing hyphens
        $segment = trim($segment, '-');
        
        // Convert to lowercase
        $segment = strtolower($segment);
        
        return $segment;
    }
}
