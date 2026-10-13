<?php

function amp_sanitize_html($html) {
    if (empty($html)) return $html;

    // Remove script, style, iframe, form, textarea, select tags and their contents
    $tags_to_remove = array('script', 'style', 'iframe', 'form', 'textarea', 'select');
    foreach ($tags_to_remove as $tag) {
        $html = preg_replace('/<' . $tag . '\b[^>]*>(.*?)<\/' . $tag . '>/is', '', $html);
    }
    
    // Remove <input> tags 
    $html = preg_replace('/<input\b[^>]*>/is', '', $html);
    $html = preg_replace('/<\/input>/is', '', $html);

    // Strip inline style attributes from all tags
    $html = preg_replace('/(<[^>]+)\s+style=(["\']).*?\2/is', '$1', $html);

    // Replace <img ...> with <amp-img ...></amp-img>
    $html = preg_replace_callback('/<img\b([^>]*)>/is', function($matches) {
        $attrs = trim($matches[1]);
        // Remove trailing slash if present
        $attrs = rtrim($attrs, '/');
        $attrs = rtrim($attrs);
        
        return '<amp-img ' . $attrs . '></amp-img>';
    }, $html);

    return $html;
}
