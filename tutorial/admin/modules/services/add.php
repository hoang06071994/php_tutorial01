<?php
$data = [
  'pageTitle' => 'Add new services',
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

if (isPost()) {
    $body = getBody();
    $errors = [];

    if (empty(trim($body['name']))) {
        $errors['name']['required'] = 'Please enter services name!';
    } else {
        if (strlen(trim($body['name'])) < 5) {
            $errors['name']['min'] = 'Services name must contain at least';
        } 
    }

    if (empty($errors)) {
        $dataInsert = [
            'name' => $body['name'],
            'create_at' => date('Y-m-d H:i:s'),
        ];
        $insertStatus = insert('services', $dataInsert);
        if ($insertStatus) {
            setFlashData('msg', 'Add success');
            setFlashData('msg_type', 'success');
            redirect('?module=services');
        } else {
            setFlashData('msg', 'He thong dang gap loi, vui long thu lai sau');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('error', $errors);
        setFlashData('old', $body);
        redirect('?module=services&action=add');
    }
}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('error');
$old = getFlashData('old');

?>

  <section class="content">
    <div class="container-fluid">
        <?php echo getMsg($msg, $msgType); ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="">Service name</label>
                <input
                    type="text"
                    class="form-control service-name"
                    name="name"
                    placeholder="Name..."
                    value="<?php echo getOldValue($old, 'name'); ?>"

                >
                <?php echo form_error('name', $errors, '<span class="text-danger">', '</span>'); ?>
            </div>
            <div class="form-group">
                <label for="">Slug</label>
                <input
                    type="text"
                    class="form-control service-slug"
                    name="slug"
                    placeholder="Slug..."
                    value="<?php echo getOldValue($old, 'slug'); ?>"
                >
                <?php echo form_error('slug', $errors, '<span class="text-danger">', '</span>'); ?>
                <p class="render-link"><b>Link:</b><span> <?php echo _WEB_HOST_ROOT; ?></span></p>
            </div>
            <div class="form-group">
                <label for="">Icon</label>
                <div class="row">
                    <div class="col-10">
                        <input
                            type="text"
                            class="form-control"
                            name="icon"
                            placeholder="Icon..."
                            value="<?php echo getOldValue($old, 'icon'); ?>"
                        >
                    </div>
                    <div class="col-2">
                        <button class="btn btn-success btn-block">Chọn ảnh</button>
                    </div>
                </div>
                <?php echo form_error('icon', $errors, '<span class="text-danger">', '</span>'); ?>
            </div>
            <div class="form-group">
                <label for="">Description</label>
                <textarea 
                    name="description"
                    class="form-control"
                    placeholder="Description..."
                    value="<?php echo getOldValue($old, 'description'); ?>"
                ></textarea>
            </div>
            <div class="form-group">
                <label for="">Content</label>
                <textarea
                    name="content"
                    class="form-control"
                    placeholder="Content..."
                    value="<?php echo getOldValue($old, 'content'); ?>"
                ></textarea>
            </div>
            <button type="submit" class="btn btn-primary"> + Add new services</button>
            <button class="btn btn-warning">
                <a href="<?php echo getLinkAdmin('services'); ?>" class="text-dark">Back</a>
            </button>
        </form>
    </div>
  </section>
</div>

<?php
layout('footer', 'admin', $data);
