<?php

//
// This file allow to profile PHP code using XDebug profiler.
//
// Usage is simple:
// 0. Download XDebug DLL for your PHP version: http://www.xdebug.org/, then
//    add to php.ini:
//      zend_extension_ts="/usr/local/php/extensions/php_xdebug-2.0.0rc1-4.4.1.dll"
//      xdebug.profiler_enable_trigger=On
//      ; xdebug.profiler_output_name="pid" - for old xdebug versions - uncomment this line!
// 1. Copy current file somewhere (e.g. /usr/local/apache2/errors/xdebug.php).
// 2. Modify httpd.conf:
//      php_value auto_prepend_file /usr/local/apache2/errors/xdebug.php
// 3. Install WinCacheGrind (http://sourceforge.net/projects/wincachegrind/)
//
// To start profiling of ANY PHP script:
// 1. Open some script in the browser and append "?XDEBUG_PROFILE=1" to its URL.
// 2. "Save as..." window will open, you should choose "Open with application..."
//    and add association between cachegrind.out (*.out) and WinCacheGrind.
// 3. WinCacheGrind will be started, and you may watch profiling results in
//    superior interface! If you will not close WinCacheGrind, you may open more
//    and more profiling results in the same window (different tabs).
//

function __xdebug_profiler_helper()
{
    // Do we need to dump cachegrind.out?
    $profiling = isset($_GET['XDEBUG_PROFILE']);
    $outdir = ini_get('xdebug.trace_output_dir');
    if ($outdir) {
        if ($pid = @$_GET['XDEBUG_PID']) {
            $cachegrind = "cachegrind.out";
            $path = "$outdir/$cachegrind.$pid";
            header("Content-type: application/octet-stream");
            header("Content-disposition: attachment; filename=\"$cachegrind\"");
            ob_start('ob_gzhandler'); // great speedup!
            echo file_get_contents($path);
            unlink($path);
            exit();
        }
    }

    // Drop "Save as" window if needed.
    if ($outdir && $profiling) {
        function __xdebug_profiler_saveas()
        {
            $pid = getmypid();
            echo "<script language='JavaScript'>setTimeout(function() { document.location = '?XDEBUG_PID=$pid' }, 100)</script>";
        }
        register_shutdown_function('__xdebug_profiler_saveas');
    }
}

__xdebug_profiler_helper();