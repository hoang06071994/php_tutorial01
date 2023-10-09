<?php
$data = [
    'pageTitle' => 'Service',
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

// Search
$filter = '';
if (isGet()) {
    $body = getBody();

    // select by key word search
    if (!empty($body['keyword'])) {
        $keyword = $body['keyword'];
        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator name LIKE '%$keyword%'";
    }
    
    // search by group
    if (!empty($body['user_id'])) {
        $userId = $body['user_id'];
        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }

        $filter .= " $operator user_id=$userId";
    }

}
// Pagination
$allService = getRows("SELECT * FROM services $filter");
$perPage = 6;
$maxPage = ceil($allService / $perPage);

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
$listService = getRaw("SELECT services.id, services.name, icon, slug, services.create_at, fullname, users.id as user_id FROM services INNER JOIN users ON services.user_id=users.id $filter");

// get all users
$allUser = getRaw("SELECT id, fullname, email FROM users ORDER BY fullname");

$queryString = null;
if (!empty($_SERVER['QUERY_STRING'])) {
    $queryString = $_SERVER['QUERY_STRING'];
    $queryString = str_replace('module=services', '', $queryString);
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
                <a href="<?php echo getLinkAdmin('services', 'add') ?>" class="btn btn-primary">+ Add new service</a>
            </div>
            <div class="col-9">
                <form action="" method="get" class="d-flex">
                    <select name="user_id" id="" class="form-control col-3 mr-3">
                        <option value="0">Select user</option>
                        <?php 
                            if (!empty($allUser)) {
                                foreach ($allUser as $user) {
                                    ?>
                                    <option value="<?php echo $user['id']; ?>" <?php echo (!empty($userId) && $userId == $user['id']) ? 'selected' : false; ?>>
                                        <?php echo $user['fullname'].' ('.$user['email'].')'; ?>
                                    </option>
                                    <?php
                                }
                            }
                        ?>
                    </select>
                    <input
                        type="search"
                        name="keyword"
                        placeholder="Search by name..."
                        class="form-control"
                        value="<?php echo (!empty($keyword)) ? $keyword : false; ?>"
                    >
                    <button type="submit" class="btn btn-primary ml-3">Search</button>
                    <input type="hidden" name="module" value="services">
                </form>
            </div>
        </div>
        <hr>
        <?php echo getMsg($msg, $msgType); ?>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th width="10%">Icon</th>
                    <th>Tên dịch vụ</th>
                    <th width="15%">Đăng bởi</th>
                    <th width="12%">Slug</th>
                    <th width="15%">Thời gian</th>
                    <th width="8%">Sửa</th>
                    <th width="8%">Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if (!empty($listService)) :
                        foreach ($listService as $key => $item) :
                ?>
                <tr>
                    <td width="5%"><?php echo $key + 1; ?></td>
                    <td>
                        <?php echo isFontIcon($item['icon']) ? $item['icon'] : '<img src="'.$item['icon'].'" width="60" />'; ?>
                    </td>
                    <td><a href="<?php echo getLinkAdmin('services', 'edit', ['id' => $item['id']]); ?>"><?php echo $item['name']; ?></a></td>
                    <td>
                        <a href="?<?php echo getLinkQueryString($queryString, 'user_id', $item['user_id']); ?>&module=services"><?php echo $item['fullname']; ?></a>
                    </td>
                    <td><?php echo $item['slug']; ?></td>
                    <td>
                        <?php echo getDateFormat($item['create_at'], 'd/m/Y H:y:s'); ?>
                    </td>
                    <td>
                        <a href="<?php echo getLinkAdmin('services', 'edit', ['id' => $item['id']]); ?>" class="btn btn-warning">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                    </td>
                    <td>
                        <a href="<?php echo getLinkAdmin('services', 'delete', ['id' => $item['id']]); ?>" class="btn btn-danger" onclick="return confirm('Are you sure ?')">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; else : ?>
                    <tr>
                        <td colspan="8">No data</td>
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
                    <a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=services'.$queryString.'&page=' . $prevPage; ?>">
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
                        <a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=services'.$queryString.'&page=' . $index; ?>" tabindex="-1">
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
                    <a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=services'.$queryString.'&page=' . $nextPage; ?>">Next</a>
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
