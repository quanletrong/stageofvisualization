<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>

	<?php $this->load->view($template_f . 'component/header/header_meta_view'); ?>
	<?php $this->load->view($template_f . 'component/header/header_common_view'); ?>

	<!-- check load file css, js theo tung page -->
	<?php
	if (isset($header_page_css_js) && $header_page_css_js != '') {
		$this->load->view($template_f . 'component/header/pages/header_' . $header_page_css_js . '_view');
	}
	?>

</head>

<body class="hold-transition sidebar-mini layout-fixed d-none">
	<div class="wrapper">

		<!-- Navbar -->
		<nav class="main-header navbar navbar-expand navbar-white navbar-light">
			<!-- Left navbar links -->
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
				</li>

				<!-- <li class="nav-item d-none d-sm-inline-block">
					<a href="#" class="nav-link">Home</a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="bds/add" class="nav-link">Thêm bất động sản</a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="news/add" class="nav-link">Thêm tin tức</a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="auction/add" class="nav-link">Thêm lịch đấu giá</a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="document/add" class="nav-link">Thêm tài liệu luật</a>
				</li>

				<li class="nav-item d-none d-sm-inline-block">
					<a href="setting" class="nav-link">Cài đặt website</a>
				</li> -->

			</ul>

			<!-- Right navbar links -->
			<ul class="navbar-nav ml-auto">
				<!-- Navbar Search -->
				<li class="nav-item">
					<a class="nav-link" data-widget="navbar-search" href="#" role="button">
						<i class="fas fa-search"></i>
					</a>
					<div class="navbar-search-block">
						<form class="form-inline">
							<div class="input-group input-group-sm">
								<input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
								<div class="input-group-append">
									<button class="btn btn-navbar" type="submit">
										<i class="fas fa-search"></i>
									</button>
									<button class="btn btn-navbar" type="button" data-widget="navbar-search">
										<i class="fas fa-times"></i>
									</button>
								</div>
							</div>
						</form>
					</div>
				</li>

				<!-- Messages Dropdown Menu -->
				<li class="nav-item dropdown">
					<a class="nav-link" data-toggle="dropdown" href="#">
						<i class="far fa-comments"></i>
						<span class="badge badge-danger navbar-badge">3</span>
					</a>
					<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
						<a href="#" class="dropdown-item">
							<!-- Message Start -->
							<div class="media">
								<img src="dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
								<div class="media-body">
									<h3 class="dropdown-item-title">
										Brad Diesel
										<span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
									</h3>
									<p class="text-sm">Call me whenever you can...</p>
									<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
								</div>
							</div>
							<!-- Message End -->
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item">
							<!-- Message Start -->
							<div class="media">
								<img src="dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
								<div class="media-body">
									<h3 class="dropdown-item-title">
										John Pierce
										<span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
									</h3>
									<p class="text-sm">I got your message bro</p>
									<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
								</div>
							</div>
							<!-- Message End -->
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item">
							<!-- Message Start -->
							<div class="media">
								<img src="dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
								<div class="media-body">
									<h3 class="dropdown-item-title">
										Nora Silvester
										<span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
									</h3>
									<p class="text-sm">The subject goes here</p>
									<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
								</div>
							</div>
							<!-- Message End -->
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
					</div>
				</li>
				<!-- Notifications Dropdown Menu -->
				<li class="nav-item dropdown">
					<a class="nav-link" data-toggle="dropdown" href="#">
						<i class="far fa-bell"></i>
						<span class="badge badge-warning navbar-badge">15</span>
					</a>
					<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
						<span class="dropdown-header">15 Notifications</span>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item">
							<i class="fas fa-envelope mr-2"></i> 4 new messages
							<span class="float-right text-muted text-sm">3 mins</span>
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item">
							<i class="fas fa-users mr-2"></i> 8 friend requests
							<span class="float-right text-muted text-sm">12 hours</span>
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item">
							<i class="fas fa-file mr-2"></i> 3 new reports
							<span class="float-right text-muted text-sm">2 days</span>
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
					</div>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-widget="fullscreen" href="#" role="button">
						<i class="fas fa-expand-arrows-alt"></i>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
						<i class="fas fa-th-large"></i>
					</a>
				</li>
			</ul>
		</nav>
		<!-- /.navbar -->

		<!-- Main Sidebar Container -->
		<aside class="main-sidebar sidebar-dark-primary elevation-4">
			<!-- Brand Logo -->
			<a href="index3.html" class="brand-link">
				<img src="dist/img/AdminLTELogo.png" alt="Admin Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
				<span class="brand-text font-weight-light">Quản Trị Website</span>
			</a>

			<!-- Sidebar -->
			<div class="sidebar">
				<!-- Sidebar user panel (optional) -->
				<div class="user-panel mt-3 pb-3 mb-3 d-flex">
					<div class="image">
						<img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
					</div>
					<div class="info">
						<a href="#" class="d-block">Admin</a>
					</div>
				</div>

				<!-- SidebarSearch Form -->
				<div class="form-inline d-none">
					<div class="input-group" data-widget="sidebar-search">
						<input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
						<div class="input-group-append">
							<button class="btn btn-sidebar">
								<i class="fas fa-search fa-fw"></i>
							</button>
						</div>
					</div>
				</div>

				<!-- Sidebar Menu -->
				<nav class="mt-2">
					<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
						<!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
						<li class="nav-item">
							<a href="home" class="nav-link">
								<i class="nav-icon fas fa-tachometer-alt"></i>
								<p>
									Trang Chủ
								</p>
							</a>

						</li>
						<!-- QUẢN LÝ ĐƠN -->
						<li class="nav-header">QUẢN LÝ ĐƠN</li>
						<li class="nav-item">
							<a href="order" class="nav-link">
								<i class="nav-icon fas fa-th"></i>
								<p>
									Đơn
									<!-- <i class="right fas fa-angle-left"></i> -->
								</p>
							</a>
						</li>
						<!-- QUẢN LÝ DANH MỤC -->
						<li class="nav-header">QUẢN LÝ DANH MỤC</li>
						<li class="nav-item">
							<a href="service" class="nav-link">
								<i class="nav-icon fas fa-th"></i>
								<p>
									Dịch vụ thiết kế
									<!-- <i class="right fas fa-angle-left"></i> -->
								</p>
							</a>
						</li>

						<li class="nav-item">
							<a href="style" class="nav-link">
								<i class="nav-icon fas fa-th"></i>
								<p>
									Phong cách thiết kế
								</p>
							</a>
						</li>

						<li class="nav-item">
							<a href="room" class="nav-link">
								<i class="nav-icon fas fa-th"></i>
								<p>
									Loại phòng thiết kế
								</p>
							</a>
						</li>

						<li class="nav-item">
							<a href="library" class="nav-link">
								<i class="nav-icon fas fa-th"></i>
								<p>
									Thư viện
								</p>
							</a>
						</li>

						<!-- QUẢN LÝ NGƯỜI DÙNG -->
						<li class="nav-header">QUẢN LÝ NGƯỜI DÙNG</li>
						<li class="nav-item">
							<a href="user" class="nav-link">
								<i class="nav-icon fas fa-th"></i>
								<p>
									Người dùng
									<i class="fas fa-angle-left right"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<a href="user/index" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Danh sách người dùng</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="user/add" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Thêm người dùng</p>
									</a>
								</li>
							</ul>
						</li>

						<li class="nav-item">
							<a href="contact" class="nav-link">
								<i class="nav-icon fas fa-th"></i>
								<p>
									Phản hồi người dùng
								</p>
							</a>
						</li>

						<!-- CÀI ĐẶT WEBSITE -->
						<li class="nav-header">CÀI ĐẶT WEBSITE</li>
						<li class="nav-item">
							<a href="setting" class="nav-link">
								<i class="nav-icon fas fa-th"></i>
								<p>
									Cài đặt website
									<i class="fas fa-angle-left right"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<a href="setting/info" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Thông tin website</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="setting/home" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Trang chủ</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="setting/price" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Trang bảng giá</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="setting/order" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Trang tạo đơn</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="setting/privacy_policy" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Trang chính sách</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="setting/termsofuse" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Trang điều khoản</p>
									</a>
								</li>

								<li class="nav-item">
									<a href="setting/refund_policy" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Trang hoàn tiền</p>
									</a>
								</li>
								<!-- <li class="nav-item">
									<a href="setting/introduce" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Trang giới thiệu</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="setting/contact" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Trang liên hệ</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="setting/recruitment" class="nav-link">
										<i class="far fa-circle nav-icon"></i>
										<p>Trang tuyển dụng</p>
									</a>
								</li> -->
							</ul>
						</li>
						<li class="nav-header"></li>
						<li class="nav-header"></li>

					</ul>
				</nav>
				<!-- /.sidebar-menu -->
			</div>
			<!-- /.sidebar -->
		</aside>

		<script>
			$(document).ready(function() {
				let menu_current = '<?= $this->uri->rsegments[1] ?>';
				let sub1_current = '<?= $this->uri->rsegments[1] ?>/<?= $this->uri->rsegments[2] ?>';
				$('.nav-sidebar > .nav-item').each(function() {
					let menu = $(this).find('a').attr('href');
					if (menu == menu_current) {
						$(this).addClass('menu-open'); // mo menu
						$(this).find('a').eq(0).addClass('active'); // active menu

						$(this).find('ul.nav-treeview li').each(function() {
							let menusub = $(this).find('a').attr('href');
							if (menusub == sub1_current) {
								$(this).find('a').addClass('active');
							}
						})
					}
				})

				$('body').removeClass('d-none')
			});
		</script>