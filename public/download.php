<?php
declare(strict_types=1);

/* ---------------------------------------------------------------
 * 1️⃣  CONFIGURATION
 * --------------------------------------------------------------- */
define('UPLOAD_DIR', realpath(__DIR__ . '/../writable/uploads'));   // <-- adjusted for public directory
if (UPLOAD_DIR === false) {
    http_response_code(500);
    exit('Server mis‑configuration: UPLOAD_DIR could not be resolved.');
}

/* ---------------------------------------------------------------
 * 2️⃣  GET PARAMETER (raw URL‑encoded value)
 * --------------------------------------------------------------- */
if (!isset($_GET['file'])) {
    http_response_code(400);
    exit('Missing file parameter.');
}
$rawParam = $_GET['file'];
$decoded  = urldecode($rawParam);

/* ---------------------------------------------------------------
 * 3️⃣  Normalise ALL directory separators to the native one
 * --------------------------------------------------------------- */
$decoded = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $decoded);
$uploadDirNative = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, UPLOAD_DIR);

/* ---------------------------------------------------------------
 * 4️⃣  If the incoming string is an *absolute* path that starts with
 *     our upload folder, strip the folder part so we end up with a
 *     *relative* path.
 * --------------------------------------------------------------- */
if (stripos($decoded, $uploadDirNative) === 0) {
    // Remove the base folder plus the directory separator that follows it
    $relativePath = substr($decoded, strlen($uploadDirNative) + 1);
} else {
    // Otherwise we assume the caller already gave us a relative path
    $relativePath = ltrim($decoded, DIRECTORY_SEPARATOR); // just in case it starts with '/'
}

/* ---------------------------------------------------------------
 * 5️⃣  Resolve the absolute file location and run security checks
 * --------------------------------------------------------------- */
$fullPath = realpath($uploadDirNative . DIRECTORY_SEPARATOR . $relativePath);

if ($fullPath === false) {
    http_response_code(404);
    exit('File not found (realpath failed).');
}
if (strpos($fullPath, $uploadDirNative) !== 0) {
    http_response_code(403);
    exit('Access denied – attempted directory traversal.');
}
if (!is_file($fullPath) || !is_readable($fullPath)) {
    http_response_code(404);
    exit('File not found or unreadable.');
}

/* OPTIONAL – whitelist allowed extensions */
$allowedExt = ['xlsx','xls','pdf','doc','docx','png','jpg','jpeg','txt','eml'];
$ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
if (!in_array($ext, $allowedExt, true)) {
    http_response_code(403);
    exit('File type not allowed.');
}

/* ---------------------------------------------------------------
 * 6️⃣  Send download headers (forces “Save As”)
 * --------------------------------------------------------------- */
$filename = basename($fullPath);
$mimeType = mime_content_type($fullPath) ?: 'application/octet-stream';

header('Content-Description: File Transfer');
header('Content-Type: ' . $mimeType);
header('Content-Disposition: attachment; filename="' . rawurlencode($filename) . '"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($fullPath));

/* ---------------------------------------------------------------
 * 7️⃣  Stream the file (chunked for low memory footprint)
 * --------------------------------------------------------------- */
$chunkSize = 8192; // 8 KB; increase to 1 MB if you prefer fewer syscalls
$handle = fopen($fullPath, 'rb');
if ($handle === false) {
    http_response_code(500);
    exit('Could not open file for reading.');
}
while (!feof($handle)) {
    echo fread($handle, $chunkSize);
    flush();                // push data to client immediately
}
fclose($handle);
exit;
