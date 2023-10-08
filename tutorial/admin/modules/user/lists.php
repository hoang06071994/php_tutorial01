<?php
if (!defined('_INCODE')) die('Access dined...');
/*list users
*/

$data = [
    'pageTitle' => 'List user'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

$filter = '';
$operator = '';
$userId = isLogin()['user_id'];
echo $userId;

if (isGet()) {
    $body = getBody();
    // select by status
    if (!empty($body['status'])) {
        $status = $body['status'];

        if ($status == 2) {
            $statusSql = 0;
        } else {
            $statusSql = $status;
        }
        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        } 

        $filter .= " $operator status=$statusSql";
    }
    // select by key word search
    if (!empty($body['keyword'])) {
        $keyword = $body['keyword'];
        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter .= " $operator fullname LIKE '%$keyword%'";
    }

    // search by group
    if (!empty($body['group_id'])) {
        $group_id = $body['group_id'];
        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }

        $filter .= " $operator group_id=$group_id";
    }
}

// pagination
$allUser = getRows("SELECT id FROM users $filter");
$perPage = 6;
$maxPage = ceil($allUser / $perPage);

if (!empty(getBody()['page'])) {
    $page = getBody()['page'];
    if ($page < 1 || $page > $maxPage) {
        $page = 1;
    }
} else {
    $page = 1;
}

/**
 * $page = 1 => offset = 0 => ($page - 1) * $perPage = (1-1)*3 = 0
 * $page = 2 => offset = 3
 * $page = 3 => offset = 6
 */
$offset = ($page - 1) * $perPage;

// get data
$listAll = getRaw("SELECT users.id, fullname, email, phone, status, users.create_at, groups.name as group_name 
                    FROM users INNER JOIN `groups` ON users.group_id=`groups`.id $filter ORDER BY users.create_at DESC LIMIT $offset, $perPage"
                );

// get all groups
$listGroup = getRaw("SELECT id, name FROM `groups` ORDER BY name");

$queryString = null;
if (!empty($_SERVER['QUERY_STRING'])) {
    $queryString = $_SERVER['QUERY_STRING'];
    $queryString = str_replace('module=users', '', $queryString);
    $queryString = str_replace('&page=' . $page, '', $queryString);
    $queryString = trim($queryString, '&');
    $queryString = '&' . $queryString;
}
$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
?>
<section class="content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between ">
            <p>
                <a href="<?php echo getLinkAdmin('user', 'add'); ?>" class="btn btn-success btn-lg">
                    + add user
                </a>
            </p>
        </div>
        <?php echo getMsg($msg, $msgType); ?>
        <form action="" method="get">
            <div class="row mt-5">
                <div class="col-2">
                    <div class="form-group">
                        <select name="status" id="" class="form-control">
                            <option value="0">Select</option>
                            <option value="1" <?php echo (!empty($status) && $status == 1) ? 'selected' : false; ?>>Active</option>
                            <option value="2" <?php echo (!empty($status) && $status == 2) ? 'selected' : false; ?>>No Active</option>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <select name="group_id" id="" class="form-control">
                            <option value="0">Select group</option>
                            <?php
                            if (!empty($listGroup)):
                                foreach($listGroup as $group):
                            ?>
                            <option 
                                value="<?php echo $group['id'] ?>"
                                <?php echo (!empty($group_id) && $group_id == $group['id']) ? 'selected' : false; ?>
                            ><?php echo $group['name'] ?></option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <input type="search" class="form-control" name="keyword" placeholder="Search..." value="<?php echo (!empty($keyword)) ? $keyword : false; ?>">
                </div>
                <div class="col-2">
                    <button type="submit" class="btn btn-primary btn-block">Search</button>
                </div>
                <input type="hidden" name="module" value="user">
            </div>
        </form>
        <table class="table table-boreder">
            <thead>
                <tr>
                    <th whidth="5%">STT</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Group</th>
                    <th>Status</th>
                    <th width="5%">Update</th>
                    <th width="5%">Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($listAll)) :
                    $count = 0;
                    foreach ($listAll as $item) :
                        $count++;
                ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td>
                                <a href="<?php echo getLinkAdmin('user', 'edit', ['id' => $item['id']]) ?>">
                                    <?php echo $item['fullname'] ?>
                                </a>
                            </td>
                            <td><?php echo $item['email'] ?></td>
                            <td><?php echo $item['phone'] ?></td>
                            <td><?php echo $item['group_name'] ?></td>
                            <td>
                                <?php echo $item['status'] == 1
                                    ? '<button type="button" class="btn btn-success btn-sm">Active</button>'
                                    : '<button type="button" class="btn btn-secondary  btn-sm">No Active</button>';
                                ?>
                            </td>
                            <td>
                                <a href="<?php echo getLinkAdmin('user', 'edit', ['id' => $item['id']]); ?>" class="btn btn-warning btn-sm">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                            </td>
                            <td>
                                <?php if ($item['id'] != $userId): ?>
                                    <a href="<?php echo getLinkAdmin('user', 'delete', ['id' => $item['id']]); ?>" onclick="return confirm('Are you sure ?')" class="btn btn-danger btn-sm">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach;
                else : ?>
                    <tr>
                        <td colspan="7">
                            <div class="alert alert-danger text-center ">
                                no user
                            </div>
                        </td>
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
                    <a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=user' . $queryString . '&page=' . $prevPage; ?>">
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
                        <a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=user' . $queryString . '&page=' . $index; ?>" tabindex="-1">
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
                    <a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN . '?module=user' . $queryString . '&page=' . $nextPage; ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</section>
</div>
<?php

layout('footer', 'admin', $data);
