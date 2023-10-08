<?php
$data = [
  'pageTitle' => 'Add new blog',
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);
?>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
        <form action="" method="post">
            <div class="form-group">
                <label for="">Name</label>
                <input type="text" class="form-control" name="name" placeholder="Name...">
            </div>
            <div class="form-group">
                <label for="">Content</label>
                <input type="text" class="form-control" name="content" placeholder="Content...">
            </div>
            <button class="btn btn-primary"> + Add</button>
        </form>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content -->
<?php
layout('footer', 'admin', $data);
