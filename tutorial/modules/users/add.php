<?php
if (!defined('_INCODE')) die('access deined ...');

$data = [
    'pageTitle' => 'add user'
];
layout('header', $data);
// add user
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
        $dataInsert = [
            'email' => $body['email'],
            'fullname' => $body['fullname'],
            'phone' => $body['phone'],
            'password' => password_hash($body['password'], PASSWORD_DEFAULT),
            'status' => $body['status'],
            'create_at' => date('Y-m-d H:i:s')
        ];

        $insertStatus = insert('users', $dataInsert);
        if ($insertStatus) {
            setFlashData('msg', 'Thêm mới thành công !');
            setFlashData('msg_type', 'success');
            redirect('?module=users');
        } else {
            setFlashData('msg', 'Hệ thông đang gặp sự cố! Xin vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
            redirect('?module=users&actione=add');
        }

    } else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('error', $errors);
        setFlashData('old', $body);
        redirect('?module=users&action=add');
    }
}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('error');
$old = getFlashData('old');

?>
<div class="container">
    <h3><?php echo $data['pageTitle']; ?></h3>
    <?php echo getMsg($msg, $msgType); ?>
    <form action="" method="post">
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="">Full name</label>
                    <input
                        type="text"
                        name="fullname"
                        placeholder="Full name..."
                        class="form-control"
                        value="<?php echo getOldValue($old, 'fullname'); ?>"
                    >
                    <?php echo form_error('fullname', $errors, '<span class="error">', '</span>'); ?>
                </div>
                <div class="form-group">
                    <label for="">Email</label>
                    <input
                        type="text"
                        name="email"
                        placeholder="Email..."
                        class="form-control"
                        value="<?php echo getOldValue($old, 'email'); ?>"
                    >
                    <?php echo form_error('email', $errors, '<span class="error">', '</span>'); ?>
                </div>
                <div class="form-group">
                    <label for="">Phone</label>
                    <input
                        type="text"
                        name="phone"
                        placeholder="Phone..."
                        class="form-control"
                        value="<?php echo getOldValue($old, 'phone'); ?>"
                    >
                    <?php echo form_error('phone', $errors, '<span class="error">', '</span>'); ?>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="">Password</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="Password..."
                        class="form-control"
                        value="<?php echo getOldValue($old, 'password'); ?>"
                    >
                    <?php echo form_error('password', $errors, '<span class="error">', '</span>'); ?>
                </div>
                <div class="form-group">
                    <label for="">Confirm password</label>
                    <input
                        type="password"
                        name="confirm_password"
                        placeholder="Confirm password"
                        class="form-control"
                        value="<?php echo getOldValue($old, 'confirm_password'); ?>"
                    >
                    <?php echo form_error('confirm_password', $errors, '<span class="error">', '</span>'); ?>
                </div>
                <div class="form-group">
                    <label for="">Status</label>
                    <select name="status" class="form-control">
                        <option value="0" <?php echo (!empty($body['status']) && $body['status'] == 0) ? 'selected' : false; ?>>No active</option>
                        <option value="1" <?php echo (!empty($body['status']) && $body['status'] == 1) ? 'selected' : false; ?>>Active</option>
                    </select>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col">
                    <button type="submit" class="btn btn-primary block">Add user</button>
                    <a href="?module=users" class="btn btn-success">Back</a>
                </div>
            </div>
        </div>
    </form>
</div>
<?php

layout('footer');