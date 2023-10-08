<?php
$data = [
  'pageTitle' => 'Add new group',
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

if (isPost()) {
    $body = getBody();
    $errors = [];

    if (empty(trim($body['name']))) {
        $errors['name']['required'] = 'Please enter group name!';
    } else {
        if (strlen(trim($body['name'])) < 5) {
            $errors['name']['min'] = 'Group name must contain at least';
        } 
    }

    if (empty($errors)) {
        $dataInsert = [
            'name' => $body['name'],
            'create_at' => date('Y-m-d H:i:s'),
        ];
        $insertStatus = insert('`groups`', $dataInsert);
        if ($insertStatus) {
            setFlashData('msg', 'Add success');
            setFlashData('msg_type', 'success');
            redirect('?module=group');
        } else {
            setFlashData('msg', 'He thong dang gap loi, vui long thu lai sau');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('error', $errors);
    }
}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('error');

?>

  <section class="content">
    <div class="container-fluid">
        <?php echo getMsg($msg, $msgType); ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="">Group name</label>
                <input type="text" class="form-control" name="name" placeholder="Name...">
                <?php echo form_error('name', $errors, '<span class="text-danger">', '</span>'); ?>
            </div>
            <button type="submit" class="btn btn-primary"> + Add new group</button>
            <button class="btn btn-warning">
                <a href="<?php echo getLinkAdmin('group'); ?>" class="text-dark">Back</a>
            </button>
        </form>
    </div>
  </section>
</div>

<?php
layout('footer', 'admin', $data);
