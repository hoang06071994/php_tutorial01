<?php
if (!defined('_INCODE')) die('access deined ...');

$data = [
    'pageTitle' => 'Register'
];
layout('headerLogin', $data);

// register

if (isPost()) {
    // validate form
    $body = getBody(); //get value
    $errors = [];
    // validate full name
    if (empty(trim($body['fullname']))) {
        $errors['fullname']['required'] = 'Full name validation!';
    } else {
        if (strlen(trim($body['fullname'])) < 8) {
            $errors['fullname']['min'] = 'Full name must contain at least 8 characters';
        }
    }
    // phone
    if (empty(trim($body['phone']))) {
        $errors['phone']['required'] = 'phone validation';
    } else {
        if (!isPhone(trim($body['phone']))) {
            $errors['phone']['isPhone'] = 'invalid phone';
        }
    }
    // email
    if (empty(trim($body['email']))) {
        $errors['email']['required'] = 'Email validation';
    } else {
        if (!isEmail(trim($body['email']))) {
            $errors['email']['isEmail'] = 'invalid email';
        } else {
            $email = trim($body['email']);
            $sql = "SELECT id FROM users WHERE email = '$email'";
            if (getRows($sql) > 0) {
                $errors['email']['unique'] = 'email already';
            }
        }
    }
    // password
    if (empty(trim($body['password']))) {
        $errors['password']['required'] = 'password validation';
    } else {
        if (strlen(trim($body['password'])) < 8) {
            $errors['password']['min'] = 'password phải chứa ít nhất 8 ký tự';
        }
    }
    // confirm password
    if (empty(trim($body['confirm_password']))) {
        $errors['confirm_password']['required'] = 'confirm password validation';
    } else {
        if (trim($body['password']) !== trim($body['confirm_password'])) {
            $errors['confirm_password']['match'] = '2 mật khẩu không khớp nhau';
        }
    }
    // kiểm tra $error
    if (empty($errors)) {
        // setFlashData('msg', 'Validate seccess');
        // setFlashData('msg_type', 'success');
        $activeToken = sha1(uniqid().time());
        $dataInsert = [
            'email' => $body['email'],
            'fullname' => $body['fullname'],
            'phone' => $body['phone'],
            'password' => password_hash($body['password'], PASSWORD_DEFAULT),
            'active_token' => $activeToken,
            'create_at' => date('Y-m-d H:i:s')
        ];

        $insertStatus = insert('users', $dataInsert);
        if ($insertStatus) {
            $linkActive =_WEB_HOST_ROOT.'?module=auth&action=active&token='.$activeToken;
            // send email
            $subject = $body['fullname'].'Vui lòng kích hoạt tài khoản';
            $content = 'Chào bạn '.$body['fullname'].'<br/>';
            $content.='Vui lòng click vào link dưới đây để kích hoạt tài khoản: <br />';
            $content.=$linkActive.'<br/>';

            $senStatus = sendEmail($body['email'], $subject, $content);
            if ($senStatus) {
                setFlashData('msg', 'Đăng ký tài khoản thành công, Vui lòng kiểm tra Email để kích hoạt tài khoản');
                setFlashData('msg_type', 'success');
            } else  {
                setFlashData('msg', 'Hệ thông đang gặp sự cố! Xin vui lòng thử lại sau');
                setFlashData('msg_type', 'danger');
            }

        } else {
            setFlashData('msg', 'Hệ thông đang gặp sự cố! Xin vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
        
        redirect('?module=auth&action=register');
        
    } else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('error', $errors);
        setFlashData('old', $body);
        redirect('?module=auth&action=register');
    }
}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('error');
$old = getFlashData('old');

?>
<div class='row'>
    <div class='col-6' style="margin: 20px auto">
        <h3 class="text-center">REGISTER</h3>
        <?php 
            getMsg($msg, $msgType);
        ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="">FullName</label>
                <input 
                    type="text"
                    class="form-control"
                    name="fullname" placeholder="Full name"
                    value="<?php echo getOldValue($old, 'fullname') ?>"
                >
                <?php echo form_error('fullname', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group" style="margin: 10px 0 0 0;">
                <label for="">Phone</label>
                <input
                    type="text"
                    class="form-control"
                    name="phone"
                    placeholder="Phone number"
                    value="<?php echo getOldValue($old, 'phone') ?>"
                >
                <?php echo form_error('phone', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group" style="margin: 10px 0 0 0;">
                <label for="">Email</label>
                <input
                    type="text"
                    class="form-control"
                    name="email"
                    placeholder="Email"
                    value="<?php echo getOldValue($old, 'email') ?>"
                >
                <?php echo form_error('email', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group" style="margin: 10px 0 0 0;">
                <label for="">Password</label>
                <input
                    type="password"
                    class="form-control"
                    name="password"
                    placeholder="Password"
                    value="<?php echo getOldValue($old, 'password') ?>"
                >
                <?php echo form_error('password', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group" style="margin: 10px 0 0 0;">
                <label for="">Confirm password</label>
                <input
                    type="password"
                    class="form-control"
                    name="confirm_password"
                    placeholder="Confrim password"
                    value="<?php echo getOldValue($old, 'confirm_password') ?>"
                >
                <?php echo form_error('confirm_password', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="d-grid col-6 mx-auto" style="margin: 20px 0 0 0;">
                <button type="submit" class="btn btn-primary btn-block">REGISTER</button>
            </div>
            <hr>
            <p class="text-center"><a href="?module=auth&action=login">LOGIN</a></p>
        </form>
    </div>
</div>
<?php
layout('footerLogin');