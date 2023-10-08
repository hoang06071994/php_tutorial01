<?php 
  if (!isLogin()) {
    redirect('?module=auth&action=login');
  } else {
    $userId = isLogin()['user_id'];
    $userInfo = getInfo($userId);
  }
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?php echo getLinkAdmin('dashboard'); ?>" class="brand-link">
    <img src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">AdminLTE 3</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/img/avatar5.png" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="<?php echo getLinkAdmin('user', 'profile'); ?>" class="d-block"><?php echo $userInfo[0]['fullname']; ?></a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- dash board -->
        <li class="nav-item">
          <a href="<?php echo getLinkAdmin('dashboard');?>" class="nav-link <?php echo setActiveSidebar('dashboard') ? 'active' : false;?>">
            <i  class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Tổng quan
            </p>
          </a>
        </li>
        <!-- blog -->
        <li class="nav-item has-treeview <?php echo setActiveSidebar('blog') ? 'menu-open' : '';?>">
          <a href="#" class="nav-link <?php echo setActiveSidebar('blog') ? 'active' : '';?>">
            <i class="nav-icon fas fa-blog"></i>
            <p>
              Blog
              <i class="fas fa-angle-left right"></i>
              <span class="badge badge-info right">2</span>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo getLinkAdmin('blog');?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Lists blog</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo getLinkAdmin('blog', 'add');?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add new blog</p>
              </a>
            </li>
          </ul>
        </li>
        <!-- group -->
        <li class="nav-item has-treeview <?php echo setActiveSidebar('group') ? 'menu-open' : '';?>">
          <a href="#" class="nav-link <?php echo setActiveSidebar('group') ? 'active' : '';?>">
            <i class="nav-icon fas fa-group"></i>
            <p>
              Group
              <i class="fas fa-angle-left right"></i>
              <span class="badge badge-info right">2</span>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo getLinkAdmin('group');?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Lists group</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo getLinkAdmin('group', 'add');?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add new group</p>
              </a>
            </li>
          </ul>
        </li>
        <!-- user -->
        <li class="nav-item has-treeview <?php echo setActiveSidebar('user') ? 'menu-open' : '';?>">
          <a href="#" class="nav-link <?php echo setActiveSidebar('user') ? 'active' : '';?>">
            <i class="nav-icon fas fa-user"></i>
            <p>
              User
              <i class="fas fa-angle-left right"></i>
              <span class="badge badge-info right">2</span>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo getLinkAdmin('user');?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Lists user</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo getLinkAdmin('user', 'add');?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add new user</p>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>