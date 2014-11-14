<?php

/**
 * @author schivei
 */
// TODO: check include path
ini_set('include_path',
        ini_get('include_path') . PATH_SEPARATOR . dirname(__FILE__) .
        '/usr/share/php/PHPUnit');

// put your code here

define('APPPATH', 'tester');

require_once __DIR__ . '/../swl/swl.php';
define('INCPATH', __DIR__ . '/files_to_test/');
require_once '/var/features/phpunit-runner-teamcity/phpunit-tc.php';

