<?php
$data = [
    'pageTitle' => 'Edit group',
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

$body = getBody('get');

if (!empty($body['id'])) {
    $groupId = $body['id'];
    $groups = firstRaw("SELECT * FROM `groups` WHERE id=$groupId");

    if (empty($groups)) {
        redirect('?module=group');
    }
} else {
    redirect('?module=group');
}

if (isPost()) {
    $body = getBody();
    $errors = [];

    if (empty(trim($body['name']))) {
        $errors['name']['required'] = 'Please enter group name';
    } else {
        if (strlen(trim($body['name'])) < 5) {
            $errors['name']['min'] = 'Group name must contain at least';
        }
    }

    if (empty($errors)) {
        $dataUpdate = [
            'name' => $body['name'],
            'update_at' => date('Y-m-d H:i:s'),
        ];
        $condition = "id=$groupId";
        $updateStatus = update('`groups`', $dataUpdate, $condition);
        if ($updateStatus) {
            setFlashData('msg', 'Update success');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'He thong dang gap loi, vui long thu lai sau');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Vul long kiem tra du lieu dau vao');
        setFlashData('msg_type', 'danger');
        setFlashData('error', $errors);
        setFlashData('old', $body);
    }
    redirect("?module=group&action=edit&id=$groupId");
}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('error');
$old = getFlashData('old');

if (empty($old) && !empty($groups)) {
    $old = $groups;
}
?>

    <section class="content">
        <div class="container-fluid">
            <?php getMsg($msg, $msgType); ?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="">Group name</label>
                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        placeholder="Group name..."
                        value="<?php echo getOldValue($old, 'name'); ?>"
                    >
                    <?php echo form_error('name', $errors, '<span class="text-danger">', '</span>'); ?>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <button class="btn btn-warning">
                    <a href="<?php echo getLinkAdmin('group'); ?>" class="text-dark">Black</a>
                </button>
                <input type="hidden" name='id' value="<?php echo $groupId; ?>">
            </form>
        </div>
    </section>
</div>
<?php
layout('footer', 'admin', $data);
