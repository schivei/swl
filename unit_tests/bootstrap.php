<?php

/**
 * @author schivei
 */
error_reporting(E_ALL | ~E_NOTICE);
ini_set('display_errors', true);

/**
 * @codeCoverageIgnore
 */
ini_set('include_path',
        ini_get('include_path') . PATH_SEPARATOR . dirname(__FILE__) .
        '/usr/share/php/PHPUnit');

// put your code here

define('APPPATH', __DIR__);
/**
 * @codeCoverageIgnore
 */
require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/../swl/swl.php';
/**
 * @codeCoverageIgnore
 */
define('INCPATH', __DIR__ . '/files_to_test/');
/**
 * @codeCoverageIgnore
 */
require_once __DIR__ . '/phpunit-tc.php';
