<?php
$data = [
  'pageTitle' => 'Profile',
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

if (isLogin()) {
    $userId = isLogin()['user_id'];
    $userInfo = getInfo($userId)[0];
    setFlashData('userInfo', $userInfo);
}


if (isPost()) {
    $body = getBody();
    $errors = [];
    // fullname
    if (empty(trim($body['fullname']))) {
        $errors['fullname']['required'] = 'Full name validation';
    } else {
        if (strlen(trim($body['fullname'])) < 8) {
            $errors['fullname']['min'] = 'Full name must contain at least 8 characters';
        }
    }

    // email
    if (empty(trim($body['email']))) {
        $errors['email']['required'] = 'Email validation';
    } else {
        if (!filter_var(trim($body['email']), FILTER_VALIDATE_EMAIL)) {
            $errors['email']['isEmail'] = 'invalid email';
        } else {
            $email = trim($body['email']);
            $sql = "SELECT id FROM users WHERE email='$email' AND id<>$userId";
            if (getRows($sql) > 0) {
                $errors['email']['unique'] = 'email already';
            }
        }
    }
    
    //phone
    if (empty(trim($body['phone']))) {
        $errors['phone']['required'] = 'Phone validation';
    } else {
        if (!isPhone(trim($body['phone']))) {
            $errors['phone']['isPhone'] = 'invalid phone';
        }
    }

    if (empty($errors)) {
        $dataUpdate = [
            'email' => $body['email'],
            'fullname' => $body['fullname'],
            'phone' => $body['phone'],
            'contact_face' => $body['contact_face'],
            'contact_pinterest' => $body['contact_pinterest'],
            'contact_linkedin' => $body['contact_linkedin'],
            'about_content' => $body['about_content'],
            'update_at' => date('Y-m-d H:i:s')
        ];
        $condition = "id=$userId";
        $updateStatus = update('users', $dataUpdate, $condition);

        if ($updateStatus) {
            setFlashData('msg', 'Update success !');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Hệ thông đang gặp sự cố! Xin vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }

    } else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('error', $errors);
        setFlashData('old', $body);
    }
    redirect('?module=user&action=profile');
}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('error');
$userInfo = getFlashData('userInfo');
$old = getFlashData('old');
if (!empty($userInfo) && empty($old)) {
    $old = $userInfo;
}
?>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
        <?php echo getMsg($msg, $msgType); ?>
        <form action="" method="post">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Fullname</label>
                        <input 
                            type="text"
                            class="form-control"
                            name="fullname"
                            placeholder="Fullname..."
                            value="<?php echo getOldValue($old, 'fullname'); ?>"
                        >
                        <?php echo form_error('fullname', $errors, '<span class="text-danger">', '</span>') ?>
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input
                            type="text"
                            class="form-control"
                            name="email"
                            placeholder="Email..."
                            value="<?php echo getOldValue($old, 'email'); ?>"
                        >
                        <?php echo form_error('email', $errors, '<span class="text-danger">', '</span>') ?>
                    </div>
                    <div class="form-group">
                        <label for="">Phone</label>
                        <input
                            type="text"
                            class="form-control"
                            name="phone"
                            placeholder="Phone..."
                            value="<?php echo getOldValue($old, 'phone'); ?>"
                        >
                        <?php echo form_error('phone', $errors, '<span class="text-danger">', '</span>') ?>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Facebook</label>
                        <input
                            type="text"
                            class="form-control"
                            name="contact_face"
                            placeholder="Facebook..."
                            value="<?php echo getOldValue($old, 'contact_face'); ?>"
                        >
                    </div>
                    <div class="form-group">
                        <label for="">Pinterest</label>
                        <input
                            type="text"
                            class="form-control"
                            name="contact_pinterest"
                            placeholder="Pinterest..."
                            value="<?php echo getOldValue($old, 'contact_pinterest'); ?>"
                        >
                    </div>
                    <div class="form-group">
                        <label for="">Linkedin</label>
                        <input
                            type="text"
                            class="form-control"
                            name="contact_linkedin"
                            placeholder="Linkedin..."
                            value="<?php echo getOldValue($old, 'contact_linkedin'); ?>"
                        >
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="">Introductory content</label>
                        <textarea
                            name="about_content"
                            class="form-control" rows="3"
                            placeholder="Introductory content..."
                        ><?php echo getOldValue($old, 'about_content'); ?></textarea>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary"> Update</button>
        </form>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content -->
<?php
layout('footer', 'admin', $data);
