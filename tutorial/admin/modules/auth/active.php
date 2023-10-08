<?php
if (!defined('_INCODE')) die('access deined ...');
layout('headerLogin');
echo '<div class="container text-center">';
$token = getBody()['token'];
echo $token;
if (!empty($token)) {
    // truy vấn kiểm tra token với query
    $tokenQuery = firstRaw("SELECT id, fullName, email FROM users WHERE active_token='$token'");
   if (!empty($tokenQuery)) {
    $userId = $tokenQuery['id'];
    $dataUpdate = [
        'status' => 1,
        'active_token' => null,
    ];
    $updateStatus = update('users', $dataUpdate, "id=$userId");
    if ($updateStatus) {
        setFlashData('msg', 'kích hoạt tài khoản thành công ! Bạn có thể đăng nhập');
        setFlashData('msg_type', 'success');
            // tạo link login
        $loginLink = _WEB_HOST_ROOT.'?module=auth&action=login';
        // send email
        $subject = 'Kích hoạt tài khoản thành công';
        $content = 'Chúc mừng: '.$tokenQuery['fullName'].' đã kích hoạt tài khoản thành công.<br />';
        $content.='Bạn có thể đăng nhập bằng link sau: '.$loginLink .'<br />';
        $content.='Trân trọng';

        sendEmail($tokenQuery['email'], $subject, $content);
    } else {
        setFlashData('msg', 'Kích hoạt tài khoản thất bại, vui lòng kiểm liên hệ tổng đài');
        setFlashData('msg_type', 'danger');
    }
    redirect('?module=auth&action=login');
    } else {
        getMsg('liên kết không tồn tại hoặc đã hết hạn', 'danger');
    }
} else {
    getMsg('liên kết không tồn tại hoặc đã hết hạn', 'danger');
}
echo '<div />';
layout('headerFooter');