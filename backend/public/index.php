<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../vendor/autoload.php';
use App\Core\Router;
// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
// Get the request path
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = rtrim($path, '/');
// Remove .php extension if present
$path = preg_replace('/\.php$/', '', $path);

// Handle static files
if (strpos($path, '/admin/assets/') === 0) {
    $filePath = __DIR__ . '/..' . $path;
    if (file_exists($filePath)) {
        // Get file extension
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        
        // Set content type
        switch ($ext) {
            case 'css':
                header('Content-Type: text/css');
                break;
            case 'js':
                header('Content-Type: application/javascript');
                break;
            case 'png':
                header('Content-Type: image/png');
                break;
            case 'jpg':
            case 'jpeg':
                header('Content-Type: image/jpeg');
                break;
            case 'gif':
                header('Content-Type: image/gif');
                break;
        }
        
        readfile($filePath);
        exit();
    }
}

// If root URL, redirect to admin login
if ($path === '' || $path === '/admin') {
    header('Location: /admin/login');
    exit();
}

// Simple router for admin pages
if ($path === '/admin/login') {
    require __DIR__ . '/../admin/login.php';
    exit();
}

// Handle login processing
if ($path === '/admin/process_login') {
    require __DIR__ . '/../admin/process_login.php';
    exit();
}

// Handle dashboard
if ($path === '/admin/dashboard') {
    require __DIR__ . '/../admin/dashboard.php';
    exit();
}

// Handle courses
if ($path === '/admin/courses') {
    require __DIR__ . '/../admin/courses.php';
    exit();
}

// Handle teachers
if ($path === '/admin/teachers') {
    require __DIR__ . '/../admin/teachers.php';
    exit();
}

// Handle consultation
if ($path === '/admin/consult') {
    require __DIR__ . '/../admin/consult.php';
    exit();
}

// Handle logout
if ($path === '/admin/logout') {
    require __DIR__ . '/../admin/logout.php';
    exit();
}

// Handle API routes
if (strpos($path, '/api/') === 0) {
    require __DIR__ . '/../app/routes/api.php';
    $router->handle();
    exit();
}

// Debug output
error_log("Requested path: " . $path);

// Handle 404
http_response_code(404);
echo "404 Not Found - Path: " . htmlspecialchars($path);
exit();
