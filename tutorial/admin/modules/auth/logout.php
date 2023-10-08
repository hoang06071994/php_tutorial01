<?php
if (!defined('_INCODE')) die('access deined ...');
echo 'test';
if (isLogin()) {
    $token = getSession('loginToken');
    delete('login_token', "token='$token'");
    removeSession('loginToken');
    redirect('?module=auth&action=login');
}