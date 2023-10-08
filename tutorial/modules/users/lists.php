<?php
if (!defined('_INCODE')) die('Access dined...');
/*list users
*/

$data = [
    'pageTitle' => 'list'
];
layout('header', $data);

$filter = '';
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
        
        if (!empty($body['keyword'])) {
            $keyword = $body['keyword'];
            if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
                $operator = 'AND';
            } else {
                $operator = 'WHERE';
            }
        }

        $filter.= "WHERE status=$statusSql";
    }
    // select by key word search
    if (!empty($body['keyword'])) {
        $keyword = $body['keyword'];
        if (!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        } else {
            $operator = 'WHERE';
        }
        $filter.= " $operator fullname LIKE '%$keyword%'";
    }
}

// pagination
$allUser = getRows("SELECT id FROM users $filter");
$perPage = 3;
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
$listAll = getRaw("SELECT * FROM users $filter ORDER BY create_at DESC LIMIT $offset, $perPage");

$queryString = null;
if (!empty($_SERVER['QUERY_STRING'])) {
    $queryString = $_SERVER['QUERY_STRING'];
    $queryString = str_replace('module=users', '', $queryString);
    $queryString = str_replace('&page='.$page, '', $queryString);
    $queryString = trim($queryString, '&');
    $queryString = '&'.$queryString;
}
$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
?>

<div class='container'>
    <h1>page user</h1>
    <div class="d-flex justify-content-between ">
        <p>
            <a href="?module=users&action=add" class="btn btn-success btn-lg">
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
                        <option value="2" <?php echo (!empty($status) && $status == 2) ? 'selected': false; ?>>No Active</option>
                    </select>
                </div>
            </div>
            <div class="col-8">
                <input type="search" class="form-control" name="keyword" placeholder="Search..." value="<?php echo (!empty($keyword)) ? $keyword : false; ?>">
            </div>
            <div class="col-2">
                <button type="submit" class="btn btn-primary btn-block">Search</button>
            </div>
            <input type="hidden" name="module" value="users">
        </div>
    </form>
    <table class="table table-boreder">
        <thead>
            <tr>
                <th whidth="5%">STT</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
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
                        <td><?php echo $item['fullname'] ?></td>
                        <td><?php echo $item['email'] ?></td>
                        <td><?php echo $item['phone'] ?></td>
                        <td>
                            <?php echo $item['status'] == 1
                                ? '<button type="button" class="btn btn-success btn-sm">Active</button>'
                                : '<button type="button" class="btn btn-secondary  btn-sm">No Active</button>';
                            ?>
                        </td>
                        <td>
                            <a href="<?php echo _WEB_HOST_ROOT.'?module=users&action=edit&id='.$item['id']; ?>" class="btn btn-warning btn-sm">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                        </td>
                        <td>
                            <a href="<?php echo _WEB_HOST_ROOT.'?module=users&action=delete&id='.$item['id']; ?>" onclick="return confirm('Are you sure ?')" class="btn btn-danger btn-sm">
                                <i class="fa-solid fa-trash"></i>
                            </a>
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
    <nav aria-label="Page navigation example">
        <ul class="pagination pagination-lg">
            <li class="page-item <?php echo ($page == 1) ? 'disabled' : ''; ?>">
                <?php
                if ($page > 1) {
                    $prevPage = $page - 1;
                }
                ?>
                <a class="page-link" href="<?php echo _WEB_HOST_ROOT . '?module=users'.$queryString.'&page=' . $prevPage; ?>">
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
                    <a class="page-link" href="<?php echo _WEB_HOST_ROOT . '?module=users'.$queryString.'&page=' . $index; ?>" tabindex="-1">
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
                <a class="page-link" href="<?php echo _WEB_HOST_ROOT . '?module=users'.$queryString.'&page=' . $nextPage; ?>">Next</a>
            </li>
        </ul>
    </nav>
</div>
<?php

layout('footer');
