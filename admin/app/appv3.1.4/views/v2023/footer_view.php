<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

		<!-- Control Sidebar -->
		<aside class="control-sidebar control-sidebar-dark">
			<!-- Control sidebar content goes here -->
			<div class="p-3">
				<h5>Title</h5>
				<p>Sidebar content</p>
			</div>
		</aside>
		<!-- /.control-sidebar -->

		<!-- Main Footer -->
		<footer class="main-footer">
			<!-- To the right -->
			<div class="float-right d-none d-sm-inline">
				Anything you want
			</div>
			<!-- Default to the left -->
			<strong>Copyright &copy; <?= date('Y')=='2023' ? '2023' : '2023-'.date('Y')?> <a href="<?=site_url()?>"><?= $this->config->item('product_name')?></a>.</strong> All rights reserved.
		</footer>
	</div>
	<!-- ./wrapper -->
	<script>
		<?php if ($this->session->flashdata('flsh_msg') != 'OK' && $this->session->flashdata('flsh_msg') != FALSE) { ?>
			$(document).Toasts('create', {
				class: 'bg-danger',
				title: 'Thất bại',
				subtitle: '',
				body: '<?= $this->session->flashdata('flsh_msg') ?>'
			})
		<?php } ?>

		<?php if ($this->session->flashdata('flsh_msg') == 'OK') { ?>
			$(document).Toasts('create', {
				class: 'bg-success',
				title: 'Thành công',
				subtitle: '',
				body: 'Cập nhật thành công!'
			})
		<?php } ?>
	</script>
</body>
</html>