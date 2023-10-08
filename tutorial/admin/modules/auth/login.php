<?php
if (!defined('_INCODE')) die('Access deined...');

$data = [
    'pageTitle' => 'Login'
];
layout('headerLogin', 'admin', $data); 

if (isLogin()) {
    redirect('?module=dashboard');
}
autoRemoveTokenLogin();

// xử lý đăng nhập
if (isPost()) {
    $body = getBody();
    if (!empty(trim($body['email'])) && !empty(trim($body['password']))) {
        $email = $body['email'];
        $password = $body['password'];

        // truy van lay thong tin use
        $userQuery = firstRaw("SELECT id, password FROM users WHERE email='$email' AND status=1");
        if (!empty($userQuery)) {
            $passwordHash = $userQuery['password'];
            $userId = $userQuery['id'];
            if (password_verify($password, $passwordHash)) {
                // tạo token login
                $tokenLogin = sha1(uniqid().time());
                // inser data
                $dataToken = [
                    'user_id' => $userId,
                    'token' => $tokenLogin,
                    'create_at' => date('Y-m-d H:i:s')
                ];
                $insetTokenStatus = insert('login_token', $dataToken);
                if ($insetTokenStatus) {
                    setSession('loginToken', $tokenLogin);
                    redirect('?module=dashboard');
                } else {
                    setFlashData('msg', 'loi he thong, ban khong the dang nhap vao luc nay');
                    setFlashData('msg_type', 'danger');
                }
            } else {
                setFlashData('msg', 'Mat khau khong chinh xac');
                setFlashData('msg_type', 'danger');
            }
        } else {
            setFlashData('msg', 'email khong ton tai hoặc chưa được kích hoạt');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Vui long nhap email va password');
        setFlashData('msg_type', 'danger');
    }
    redirect('?module=auth&action=login');
}
$msg = getFlashData('msg');
$msg_type = getFlashData('msg_type');
?>
<div class='row'>
    <div class='col-6' style="margin: 20px auto">
        <h3 class="text-center">LOGIN</h3>
        <?php getMsg($msg, $msg_type) ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Email">
            </div>
            <div class="form-group" style="margin: 10px 0 0 0;">
                <label for="">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password">
            </div>
            <div class="d-grid col-6 mx-auto" style="margin: 20px 0 0 0;">
                <button type="submit" class="btn btn-primary btn-block">LOGIN</button>
            </div>
            <hr>
            <p class="text-center"><a href="?module=auth&action=forgot">Forgot password</a></p>
        </form>
    </div>
</div>
<?php
layout('footerLogin', 'admin');
