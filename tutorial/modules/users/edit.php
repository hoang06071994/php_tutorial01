<?php
if (!defined('_INCODE')) die('access deined ...');

$data = [
    'pageTitle' => 'Edit user'
];
layout('header', $data);

$body = getBody();
if (!empty($body['id'])) {
    $userId = $body['id'];
    $userDetail = firstRaw("SELECT * FROM users WHERE id='$userId'");
    if (!empty($userDetail)) {
        setFlashData('userDetail', $userDetail);
    } else {
        redirect('?module=users');
    }
} else {
    redirect('?module=users');
}

if (isPost()) {
    // validate form
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
            $sql = "SELECT id FROM users WHERE email='$email' AND id<>$userId";
            if (getRows($sql) > 0) {
                $errors['email']['unique'] = 'email already';
            }
        }
    }
    if (!empty(trim($body['password']))) {
        // validate confirm password if password
        if (empty(trim($body['confirm_password']))) {
            $errors['confirm_password']['required'] = 'confirm password validation';
        } else {
            if (trim($body['password']) !== trim($body['confirm_password'])) {
                $errors['confirm_password']['match'] = '2 mật khẩu không khớp nhau';
            }
        }
    }

    // kiểm tra $error
    if (empty($errors)) {
        $dataUpdate = [
            'email' => $body['email'],
            'fullname' => $body['fullname'],
            'phone' => $body['phone'],
            'status' => $body['status'],
            'update_at' => date('Y-m-d H:i:s')
        ];
        if (!empty(trim($body['password']))) {
            $dataUpdate['password'] = password_hash($body['password'], PASSWORD_DEFAULT);
        }
        $condition = "id=$userId";
        $updateStatus = update('users', $dataUpdate, $condition);
        if ($updateStatus) {
            setFlashData('msg', 'Update success !');
            setFlashData('msg_type', 'success');
            redirect('?module=users&action=edit&id='.$userId);
        } else {
            setFlashData('msg', 'Hệ thông đang gặp sự cố! Xin vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
            redirect('?module=users&action=edit&id='.$userId);
        }

    } else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('error', $errors);
        setFlashData('old', $body);
        redirect('?module=users&action=edit&id='.$userId);
    }
}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('error');
$userDetail = getFlashData('userDetail');
if (!empty($userDetail)) {
    $old = $userDetail;
}

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
                        placeholder="Password (Khong nhap neu khong thay doi)"
                        class="form-control"
                    >
                    <?php echo form_error('password', $errors, '<span class="error">', '</span>'); ?>
                </div>
                <div class="form-group">
                    <label for="">Confirm password</label>
                    <input
                        type="password"
                        name="confirm_password"
                        placeholder="Confirm password (Khong nhap neu khong thay doi)"
                        class="form-control"
                    >
                    <?php echo form_error('confirm_password', $errors, '<span class="error">', '</span>'); ?>
                </div>
                <div class="form-group">
                    <label for="">Status</label>
                    <select name="status" class="form-control">
                        <option value="0" <?php echo (!empty($old['status']) && $old['status'] == 0) ? 'selected' : false; ?>>No active</option>
                        <option value="1" <?php echo (!empty($old['status']) && $old['status'] == 1) ? 'selected' : false; ?>>Active</option>
                    </select>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col">
                    <button type="submit" class="btn btn-primary block">Save</button>
                    <a href="?module=users" class="btn btn-success">Back</a>
                    <input type="hidden" name="id" value="<?php echo $userId; ?>">
                </div>
            </div>
        </div>
    </form>
</div>
<?php

layout('footer');