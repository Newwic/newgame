<?php
// auth.php - tiny helper to validate admin bearer token
// WARNING: This is a lightweight example. For production, use real auth (sessions/JWT) and secure token storage.

function get_authorization_header() {
    // Try common server variables
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        return trim($_SERVER['HTTP_AUTHORIZATION']);
    }
    // Nginx or fastcgi
    if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        return trim($_SERVER['REDIRECT_HTTP_AUTHORIZATION']);
    }
    // Try apache_request_headers if available
    if (function_exists('apache_request_headers')) {
        $headers = apache_request_headers();
        if (!empty($headers['Authorization'])) {
            return trim($headers['Authorization']);
        }
        // some servers use lowercase
        foreach ($headers as $key => $value) {
            if (strtolower($key) === 'authorization') return trim($value);
        }
    }
    return null;
}

function is_admin_request() {
    // Configure admin token here. Keep it secret in production (env var or config file)
    $ADMIN_TOKEN = 'admin-secret-token';

    $hdr = get_authorization_header();
    if (!$hdr) return false;
    if (stripos($hdr, 'bearer ') === 0) {
        $token = substr($hdr, 7);
        return hash_equals($ADMIN_TOKEN, $token);
    }
    return false;
}
