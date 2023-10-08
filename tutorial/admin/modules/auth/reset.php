<?php
if (!defined('_INCODE')) die('access deined ...');
layout('headerLogin', 'admin');
echo '<div class="container">';
$token = getBody()['token'];

if (!empty($token)) {
    $tokenQuery = firstRaw("SELECT id, fullName, email FROM users WHERE forget_token='$token'");
    if (!empty($tokenQuery)) {
        $userId = $tokenQuery['id'];
        $email = $tokenQuery['email'];
        if (isPost()) {
            $body = getBody();
            // print_r($body); die;
            $errors = [];
            if (empty(trim($body['new_password']))) {
                $errors['new_password']['required'] = 'new_password validation';
            } else {
                if (strlen(trim($body['new_password'])) < 8) {
                    $errors['new_password']['min'] = 'new_password phải chứa ít nhất 8 ký tự';
                }
            }
            if (empty(trim($body['confirm_new_password']))) {
                $errors['confirm_new_password']['required'] = 'confirm_new_password password validation';
            } else {
                if (trim($body['confirm_new_password']) !== trim($body['new_password'])) {
                    $errors['confirm_new_password']['match'] = '2 mật khẩu không khớp nhau';
                }
            }
            
            if (empty($errors)) {
                $passwordHash = password_hash($body['new_password'], PASSWORD_DEFAULT);
                $dataUpdate = [
                    'password'=> $passwordHash,
                    'update_at' => date('Y-m-d H:i:s'),
                    'forget_token' => null,
                ];
                $updateStatus = update('users', $dataUpdate, "id=$userId");
                if ($updateStatus) {
                    setFlashData('msg', 'Thay doi mat khau thanh cong');
                    setFlashData('msg_type', 'success');
                    // send email
                    $subject = 'Bạn vừa đổi mật khẩu';
                    $content = 'Chúc mừng bạn đã đổi mật khẩu thành công !';
                    sendEmail($email, $subject, $content);

                    redirect('?module=auth&action=login');
                } else {
                    setFlashData('msg', 'loi he thong, ban khong the thay doi password');
                    setFlashData('msg_type', 'danger');
                    redirect('?module=auth&action=reset&token='.$token);
                }

            } else {
                setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
                setFlashData('msg_type', 'danger');
                setFlashData('error', $errors);
                redirect('?module=auth&action=reset&token='.$token);
            }
        }
        $msg = getFlashData('msg');
        $msgType = getFlashData('msg_type');
        $errors = getFlashData('error');
?>
        <div class='row'>
            <div class='col-6' style="margin: 20px auto">
                <h3 class="text-center">RESET PASSWORD</h3>
                <?php getMsg($msg, $msgType) ?>
                <form action="" method="post">
                    <div class="form-group" style="margin: 10px 0 0 0;">
                        <label for=""> New Password</label>
                        <input type="password" name="new_password" class="form-control" placeholder="New Password">
                        <?php echo form_error('new_password', $errors, '<span class="error">', '</span>'); ?>
                    </div>
                    <div class="form-group" style="margin: 10px 0 0 0;">
                        <label for="">Confirm New Password</label>
                        <input type="password" name="confirm_new_password" class="form-control" placeholder="Confirm New Password">
                        <?php echo form_error('confirm_new_password', $errors, '<span class="error">', '</span>'); ?>
                    </div>
                    <div class="d-grid col-6 mx-auto" style="margin: 20px 0 0 0;">
                        <button type="submit" class="btn btn-primary btn-block">SUBMIT</button>
                    </div>
                    <hr>
                    <p class="text-center"><a href="?module=auth&action=login">login</a></p>
                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                </form>
            </div>
        </div>
<?php
    } else {
        getMsg('liên kết không tồn tại hoặc đã hết hạn', 'danger');
    }
} else {
    getMsg('liên kết không tồn tại hoặc đã hết hạn', 'danger');
}

echo '</div>';
layout('headerFooter', 'admin');
