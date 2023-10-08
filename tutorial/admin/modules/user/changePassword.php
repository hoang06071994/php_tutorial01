<?php
$data = [
  'pageTitle' => 'Change password',
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

if (isLogin()) {
  $userId = isLogin()['user_id'];
  $userInfo = getInfo($userId)[0];
}

if (isPost()) {
  $body = getBody();
  $errors = [];

  if (empty(trim($body['old_password']))) {
    $errors['old_password']['required'] = 'Old password validation';
  } else {
    $oldPassword = trim($body['old_password']);
    $hashPassword = $userInfo['password'];
    if (!password_verify($oldPassword, $hashPassword)) {
      $errors['old_password']['match'] = 'Old password is incorrect';
    }
  }

  if (empty(trim($body['new_password']))) {
    $errors['new_password']['required'] = 'New password validation';
  } else {
    if (strlen(trim($body['new_password'])) < 8) {
      $errors['new_password']['min'] = 'new_password phải chứa ít nhất 8 ký tự';
    }
  }

  // confirm password
  if (empty(trim($body['confirm_password']))) {
    $errors['confirm_password']['required'] = 'confirm password validation';
  } else {
    if (trim($body['new_password']) !== trim($body['confirm_password'])) {
      $errors['confirm_password']['match'] = 'Confirm new password and new pasword do not match';
    }
  }

  // echo '<pre>';
  // print_r($errors);
  // echo '</pre>';

  if (empty($errors)) {
    $dataUpdate = [
      'password' => password_hash($body['new_password'], PASSWORD_DEFAULT),
      'update_at' => date('Y-m-d H:i:s')
    ];
    $condition = "id=$userId";
    $updateStatus = update('users', $dataUpdate, $condition);

    if ($updateStatus) {
      setFlashData('msg', 'Change password success !');
      setFlashData('msg_type', 'success');
      // send email
      $subject = 'Thay đổi mật khẩu';
      $content = 'Chào bạn ' . $userInfo['fullname'] . '<br/>';
      $content .= 'Bạn đã thay đổi mật khẩu thành công. <br />';
      $content .= 'Nếu không phải là bạn, vui lòng liên hệ ngay với chúng tôi ! <br />';
      $content .= 'Trân trọng ! <br />';

      $senStatus = sendEmail($userInfo['email'], $subject, $content);
      redirect('?module=auth&action=logout');
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
}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('error');

?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <?php echo getMsg($msg, $msgType); ?>
    <form action="" method="post">
      <div class="form-group">
        <label for="">Old password</label>
        <input type="password" class="form-control" name="old_password" placeholder="Old password...">
        <?php echo form_error('old_password', $errors, '<span class="text-danger">', '</span>') ?>
      </div>
      <div class="form-group">
        <label for="">New password</label>
        <input type="password" class="form-control" name="new_password" placeholder="New password...">
        <?php echo form_error('new_password', $errors, '<span class="text-danger">', '</span>') ?>
      </div>
      <div class="form-group">
        <label for="">Confirm new password</label>
        <input type="password" class="form-control" name="confirm_password" placeholder="New password...">
        <?php echo form_error('confirm_password', $errors, '<span class="text-danger">', '</span>') ?>
      </div>
      <button class="btn btn-primary">Update</button>
    </form>
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
<!-- /.content -->
<?php
layout('footer', 'admin', $data);
