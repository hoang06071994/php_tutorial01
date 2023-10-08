<?php
$data = [
    'pageTitle' => 'Groups',
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

// Search
$filter = '';
if (isGet()) {
    $body = getBody();
    if (!empty($body['keyword'])) {
        $keyword = $body['keyword'];
        $filter = "WHERE name LIKE '%$keyword%'";
    }
}
// Pagination
$allGroup = getRows("SELECT * FROM `groups` $filter");
$perPage = 6;
$maxPage = ceil($allGroup / $perPage);

if (!empty($body['page'])) {
    $page = $body['page'];
    if ($page < 1 || $page > $maxPage) {

        $page = 1;
    }
} else {
    $page = 1;
}

$offset = ($page -1) * $perPage;
// get data
$listGroup = getRaw("SELECT * FROM `groups` $filter ORDER BY create_at DESC LIMIT $offset, $perPage");

$queryString = null;
if (!empty($_SERVER['QUERY_STRING'])) {
    $queryString = $_SERVER['QUERY_STRING'];
    $queryString = str_replace('module=group', '', $queryString);
    $queryString = str_replace('&page='.$page, '', $queryString);
    $queryString = trim($queryString, '&');
    $queryString = '&'.$queryString;
}
$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <div class="col-3">
                <a href="<?php echo getLinkAdmin('group', 'add') ?>" class="btn btn-primary">+ Add new group</a>
            </div>
            <div class="col-6">
                <form action="" method="get" class="d-flex">
                    <input
                        type="search"
                        name="keyword"
                        placeholder="Search by name..."
                        class="form-control"
                        value="<?php echo (!empty($keyword)) ? $keyword : false; ?>"
                    >
                    <button type="submit" class="btn btn-primary ml-3">Search</button>
                    <input type="hidden" name="module" value="group">
                </form>
            </div>
        </div>
        <hr>
        <?php echo getMsg($msg, $msgType); ?>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th>Tên nhóm</th>
                    <th>Thời gian</th>
                    <th width="12%">Phân quyền</th>
                    <th width="8%">Sửa</th>
                    <th width="8%">Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if (!empty($listGroup)) :
                        foreach ($listGroup as $key => $item) :
                ?>
                <tr>
                    <td width="5%"><?php echo $key + 1; ?></td>
                    <td><a href="<?php echo getLinkAdmin('group', 'edit', ['id' => $item['id']]); ?>"><?php echo $item['name']; ?></a></td>
                    <td><?php echo getDateFormat($item['create_at'], 'd/m/Y H:y:s'); ?></td>
                    <td>
                        <a href="#" class="btn btn-primary">Permission</a>
                    </td>
                    <td>
                        <a href="<?php echo getLinkAdmin('group', 'edit', ['id' => $item['id']]); ?>" class="btn btn-warning">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                    </td>
                    <td>
                        <a href="<?php echo getLinkAdmin('group', 'delete', ['id' => $item['id']]); ?>" class="btn btn-danger" onclick="return confirm('Are you sure ?')">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; else : ?>
                    <tr>
                        <td colspan="6">No data</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation example" class="d-flex justify-content-end">
            <ul class="pagination pagination-sm">
                <li class="page-item <?php echo ($page == 1) ? 'disabled' : ''; ?>">
                    <?php
                    if ($page > 1) {
                        $prevPage = $page - 1;
                    }
                    ?>
                    <a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=group'.$queryString.'&page=' . $prevPage; ?>">
                        Previous
                    </a>
                </li>
                <?php
                    $begin = $page - 1;
                    if ($begin < 1) {
                        $begin = 1;
                    }
                    $pageEnd = $page + 1;
                    if ($pageEnd > $maxPage) {
                        $pageEnd = $maxPage;
                    }
                    for ($index = $begin; $index <= $pageEnd; $index++) { 
                ?>
                    <li class="page-item <?php echo ($index == $page) ? 'active' : false; ?>">
                        <a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=group'.$queryString.'&page=' . $index; ?>" tabindex="-1">
                            <?php echo $index; ?>
                        </a>
                    </li>
                <?php } ?>
                <li class="page-item <?php echo ($page == $maxPage) ? 'disabled' : ''; ?>">
                    <?php
                    if ($page < $maxPage) {
                        $nextPage = $page + 1;
                    }
                    ?>
                    <a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=group'.$queryString.'&page=' . $nextPage; ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
</div>
<!-- /.content -->
<?php
layout('footer', 'admin', $data);
