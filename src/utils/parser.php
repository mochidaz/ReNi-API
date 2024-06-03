<?php
function parse_form_data($data)
{
    if (preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches)) {
        $boundary = $matches[1];
    } else {
        return [];
    }
    
    $parts = explode('--' . $boundary, $data);
    
    foreach ($parts as $part) {
        $part = trim($part);
        
        if (empty($part) || $part === '--') {
            continue;
        }
        
        list($headers, $content) = explode("\r\n\r\n", $part, 2);
        $content = trim($content);
        
        $headers = explode("\r\n", $headers);
        foreach ($headers as $header) {
            if (strpos($header, 'Content-Disposition') !== false) {
                if (preg_match('/name="([^"]+)"/', $header, $matches)) {
                    $name = $matches[1];
                    $formData[$name] = $content;
                }
            }
        }
    }
    
    return $formData;
}

?>