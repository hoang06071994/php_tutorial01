<?php

date_default_timezone_set('Asia/Ho_Chi_Minh');
const _MODULE_DEFAULT = 'home';
const _ACTION_DEFAULT = 'lists';

// Thiết lập hằng số cho admin
const _MODULE_DEFAULT_ADMIN = 'dashboard';

// Ngăn chặn hành vi truy cập trực tiếp vào file
const _INCODE = true;

// Thiết lập host
define('_WEB_HOST_ROOT', 'http://'.$_SERVER['HTTP_HOST'].'/php/tutorial');
define('_WEB_HOST_TEMPLATE', _WEB_HOST_ROOT.'/templates/client');

define('_WEB_HOST_ROOT_ADMIN', _WEB_HOST_ROOT.'/admin');
// define('_WEB_HOST_ROOT_ADMIN_HOME', _WEB_HOST_ROOT.'/admin/?module=dashboard');
define('_WEB_HOST_ADMIN_TEMPLATE', _WEB_HOST_ROOT.'/templates/admin');

// setup path
define('_WEB_PATH_ROOT', __DIR__);
define('_WEB_PATH_TEMPLATE', _WEB_PATH_ROOT.'/templates');

const _HOST = 'localhost';
const _USER = 'root';
const _PASS = '';
const _DB = 'radix';
const _DRIVER = 'mysql';

// Thiết lập số lượng ghi trên một trang
const _PER_PAGE = 10;
