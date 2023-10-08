<?php
if (!defined('_INCODE')) die('access deined ...');
$body = getBody();
if (!empty($body['id'])) {
    $userId = $body['id'];
    $userDetailRows = getRows("SELECT id FROM users WHERE id=$userId");
    if ($userDetailRows > 0) {
        // delete login token (primary key)
        $deleteToken = delete('login_token', "user_id=$userId");
        if ($deleteToken) {
            
            $deleteUser = delete('users', "id=$userId");
            if ($deleteUser) {
                setFlashData('msg', 'delete success');
                setFlashData('msg_type', 'success');
            } else {
                setFlashData('msg', 'loi he thong');
                setFlashData('msg_type', 'danger');
            }
        }
    } else {
        setFlashData('msg', 'User not found');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Id not found');
    setFlashData('msg_type', 'danger');
}
redirect('?module=user');