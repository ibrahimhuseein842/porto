<?php
// helpers.php - Helper Functions

// Helper function to get text in current language
if (!function_exists('getText')) {
    function getText($item, $field, $lang) {
        if (!$item || !is_array($item)) {
            return '';
        }
        return $item[$field . '_' . $lang] ?? $item[$field . '_en'] ?? '';
    }
}

// Helper function to get site setting
if (!function_exists('getSetting')) {
    function getSetting($key, $lang, $settings) {
        if (!$settings || !is_array($settings)) {
            return '';
        }
        return $settings[$key . '_' . $lang] ?? $settings[$key . '_en'] ?? '';
    }
}

// Helper function to safely output HTML
if (!function_exists('safeOutput')) {
    function safeOutput($text) {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}

// Helper function to check if user is logged in
if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }
}
?>