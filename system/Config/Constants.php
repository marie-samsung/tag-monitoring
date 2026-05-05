<?php
/**
 * Minimal Constants.php – only the constants required for bootstrapping.
 * Replace it with the full file as soon as you can.
 */

if (! defined('ROOTPATH')) {
    // ROOTPATH is normally defined by the real bootstrap (index.php)
    // If you are loading this file directly, adjust the path accordingly.
    define('ROOTPATH', realpath(__DIR__ . '/../../') . DIRECTORY_SEPARATOR);
}

// Core path constants -------------------------------------------------
define('SYSTEMPATH', ROOTPATH . 'system' . DIRECTORY_SEPARATOR);
define('APPPATH',    ROOTPATH . 'app' . DIRECTORY_SEPARATOR);
define('WRITEPATH',  ROOTPATH . 'writable' . DIRECTORY_SEPARATOR);
define('SUPPORTPATH', SYSTEMPATH . 'Support' . DIRECTORY_SEPARATOR);

// Other optional constants (you may omit them for now)
define('SKIP_PATH',  ROOTPATH . 'public' . DIRECTORY_SEPARATOR);
