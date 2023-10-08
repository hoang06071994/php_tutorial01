<?php
$data = [
  'pageTitle' => 'BLogs',
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);
?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th width="5%">STT</th>
          <th>Tiêu đề</th>
          <th>Danh mục</th>
          <th>Danh mục</th>
          <th>Thời gian</th>
          <th>Sửa</th>
          <th>Xóa</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td width="5%">1</td>
          <td>Tiêu đề</td>
          <td>Danh mục</td>
          <td>Danh mục</td>
          <td>Thời gian</td>
          <td>Sửa</td>
          <td>Xóa</td>
        </tr>
      </tbody>
    </table>
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
<!-- /.content -->
<?php
layout('footer', 'admin', $data);
