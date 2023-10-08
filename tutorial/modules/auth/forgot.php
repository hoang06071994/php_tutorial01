<?php
if (!defined('_INCODE')) die('access deined ...');
$data = [
    'pageTitle' => 'Forgot password'
];
layout('headerLogin', $data);

$checkLogin = false;
if (getSession('loginToken')) {
    $tokenLogin = getSession('loginToken');
    $queryToken = firstRaw("SELECT userId FROM logintoken WHERE token='$tokenLogin'");
    if (!empty($queryToken)) {
        $checkLogin = true;
    } else {
        removeSession('loginToken');
    }
}
if (empty($checkLogin)) {
    redirect('?module=users');
}
// xử lý đăng nhập
if (isPost()) {
    $body = getBody();
    if (!empty(trim($body['email']))) {
        $email = $body['email'];
        $queryUser = firstRaw("SELECT id FROM users WHERE email='$email'");
        if (!empty($queryUser)) {
            $userId = $queryUser['id'];
            // tạo forgot token
            $forgotToken = sha1(uniqid().time());
            $dataUpdate = [
                'fogotToken' => $forgotToken
            ];
            $updateStatus = update('users', $dataUpdate, "id=$userId");
            if ($updateStatus) {
                // set up send email
                $linkReset = _WEB_HOST_ROOT.'?module=auth&action=reset&token='.$forgotToken;
                $subject = 'yeu cau thay doi mat khau';
                $content = 'Chao ban: '.$email.'</br>';
                $content.='Chung toi nhan duoc yeu cau khoi phuc mat khau tu ban. Vui long nhan vao link sau de khoi phuc mat khau <br/>';
                $content.=$linkReset.'<br/>';
                $content.='Tran trong';

                // send email
                $sendStatus = sendEmail($email, $subject, $content);
                if ($sendStatus) {
                    setFlashData('msg', 'vui long check email');
                    setFlashData('msg_type', 'success');
                } else {
                    setFlashData('msg', 'loi he thong');
                    setFlashData('msg_type', 'danger');
                }
            } else {
                setFlashData('msg', 'loi he thong');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'email khong ton tai');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'vui long nhap dia chi email');
        setFlashData('msg_type', 'danger');

    }
    redirect('?module=auth&action=forgot');
}
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>

<div class='row'>
    <div class='col-6' style="margin: 20px auto">
        <h3 class="text-center">FORGOT PASSWORD</h3>
        <?php getMsg($msg, $msg_type) ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Email">
            </div>
            <div class="d-grid col-6 mx-auto" style="margin: 20px 0 0 0;">
                <button type="submit" class="btn btn-primary btn-block">Submit</button>
            </div>
            <hr>
            <p class="text-center"><a href="?module=auth&action=login">Login</a></p>
            <p class="text-center"><a href="?module=auth&action=register">Register</a></p>
        </form>
    </div>
</div>
<?php
layout('footerLogin');