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


<!-- sidebar-collapse -->
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
				<li class="nav-item dropdown">
					<a class="nav-link" data-toggle="dropdown" href="#" style="color: #161616;">
						<i class="fas fa-user" style="color: #858c93;"></i>
						Hi! <strong><?= $this->session->userdata('fullname'); ?></strong>
						<i class="fas fa-chevron-down" style="color: #858c93;"></i>
					</a>
					<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
						<a href="<?= ROOT_DOMAIN ?>" target="_blank" class="dropdown-item">
							<i class="fas fa-directions" style="color: #858c93;"></i> Trang người dùng
						</a>
						<div class="dropdown-divider"></div>

						<a href="user/info" class="dropdown-item">
							<i class="fas fa-user" style="color: #858c93;"></i> Thông tin cá nhân
						</a>
						<div class="dropdown-divider"></div>

						<a href="<?= ROOT_DOMAIN . 'logout' ?>" class="dropdown-item">
							<i class="fas fa-sign-out-alt" style="color: #858c93;"></i> Thoát khỏi hệ thống
						</a>
						<div class="dropdown-divider"></div>
					</div>
				</li>
			</ul>
		</nav>
		<!-- /.navbar -->

		<!-- Main Sidebar Container -->
		<aside class="main-sidebar sidebar-dark-primary elevation-4">

			<!-- Sidebar -->
			<div class="sidebar">
				<!-- Sidebar Menu -->
				<nav class="mt-2">
					<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
						<!-- QUẢN LÝ ĐƠN -->
						<li class="nav-header">QUẢN LÝ ĐƠN</li>
						<li class="nav-item">
							<a href="order" class="nav-link">
								<i class="nav-icon fas fa-home"></i>
								<p>
									QUẢN LÝ ĐƠN HÀNG
									<i class="fas fa-angle-left right"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">

								<li class="nav-item">
									<a href="order/index" class="nav-link">
										<i class="nav-icon fas fa-tasks"></i>
										<p>Danh sách đơn hàng</p>
									</a>
								</li>

								<?php if (in_array($role, [ADMIN, SALE])) { ?>
									<li class="nav-item">
										<a href="order/add_private" class="nav-link">
											<i class="nav-icon fas fa-folder-plus"></i>
											<p>Tạo đơn hàng nội bộ</p>
										</a>
									</li>
									<li class="nav-item">
										<a href="order/add_customer" class="nav-link">
											<i class="nav-icon fas fa-folder-plus"></i>
											<p>Tạo đơn hàng cho khách</p>
										</a>
									</li>
								<?php } ?>
							</ul>
						</li>

						<!-- QUẢN LÝ DANH MỤC -->
						<?php if (in_array($role, [ADMIN, SALE])) { ?>
							<li class="nav-header">QUẢN LÝ DANH MỤC</li>
							<?php if (in_array($role, [ADMIN])) { ?>
								<li class="nav-item">
									<a href="service" class="nav-link">
										<i class="nav-icon fas fa-palette"></i>
										<p>
											Dịch vụ thiết kế
											<!-- <i class="right fas fa-angle-left"></i> -->
										</p>
									</a>
								</li>

								<li class="nav-item">
									<a href="style" class="nav-link">
										<i class="nav-icon fas fa-icons"></i>
										<p>
											Phong cách thiết kế
										</p>
									</a>
								</li>

								<li class="nav-item">
									<a href="room" class="nav-link">
									<i class="nav-icon fas fa-person-booth"></i>
										<p>
											Loại phòng thiết kế
										</p>
									</a>
								</li>

								<li class="nav-item">
									<a href="library" class="nav-link">
										<i class="nav-icon fas fa-photo-video"></i>
										<p>
											Thư viện
										</p>
									</a>
								</li>
							<?php } ?>

							<li class="nav-item">
								<a href="voucher" class="nav-link">
									<i class="nav-icon fas fa-band-aid"></i>
									<p>
										Khuyến mại
										<!-- <i class="right fas fa-angle-left"></i> -->
									</p>
								</a>
							</li>
						<?php } ?>

						<!-- QUẢN LÝ NGƯỜI DÙNG -->

						<li class="nav-header">QUẢN LÝ NGƯỜI DÙNG</li>

						<li class="nav-item">
							<a href="chat" class="nav-link">
								<i class="nav-icon fas fa-comments"></i>
								<p>
									CHAT
								</p>
							</a>
						</li>

						<?php if (in_array($role, [ADMIN, SALE])) { ?>
							<li class="nav-item">
								<a href="kpi" class="nav-link">
									<i class="nav-icon fas fa-chart-line"></i>
									<p>
										KPI
									</p>
								</a>
							</li>
						<?php } ?>

						<?php if (in_array($role, [ADMIN])) { ?>
							<li class="nav-item">
								<a href="withdraw" class="nav-link">
									<i class="nav-icon fas fa-hand-holding-usd"></i>
									<p>
										Yêu cầu rút tiền
									</p>
								</a>
							</li>

							<li class="nav-item">
								<a href="user" class="nav-link">
									<i class="nav-icon fas fa-users"></i>
									<p>
										Danh sách tài khoản
									</p>
								</a>
							</li>
						<?php } ?>

						<?php if (in_array($role, [ADMIN, SALE])) { ?>
							<li class="nav-item">
								<a href="contact" class="nav-link">
									<i class="nav-icon fas fa-comment-medical"></i>
									<p>
										Phản hồi người dùng
									</p>
								</a>
							</li>
						<?php } ?>

						<!-- CÀI ĐẶT WEBSITE -->
						<?php if (in_array($role, [ADMIN])) { ?>
							<li class="nav-header">CÀI ĐẶT WEBSITE</li>
							<li class="nav-item">
								<a href="setting" class="nav-link">
									<i class="nav-icon fas fa-cogs"></i>
									<p>
										Cài đặt website
										<i class="fas fa-angle-left right"></i>
									</p>
								</a>
								<ul class="nav nav-treeview">
									<li class="nav-item">
										<a href="setting/max_order_working" class="nav-link">
											<i class="far fa-circle nav-icon"></i>
											<p>Điều kiện JOIN đơn mới</p>
										</a>
									</li>
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
									<!-- <li class="nav-item">
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
									</li> -->
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
						<?php } ?>

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