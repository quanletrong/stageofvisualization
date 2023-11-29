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
	<strong>Copyright &copy; <?= date('Y') == '2023' ? '2023' : '2023-' . date('Y') ?> <a href="<?= site_url() ?>"><?= $this->config->item('product_name') ?></a>.</strong> All rights reserved.
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

	// for bootstrap-switch
	$("input[data-bootstrap-switch]").each(function() {
		$(this).bootstrapSwitch('state', $(this).prop('checked'));
	})
</script>

<!-- upload anh -->
<script>
	function quanlt_upload(e) {
		var quanlt_cb = $(e).data('callback');
		var quanlt_input_target = $(e).data('target');
		var quanlt_btn_upload = e;
		var quanlt_btn_upload_old = $(e).html();

		let form_id = `quanlt_frm_files_${Date.now()}`;

		if ($(`#${form_id}`).length == 0) {

			$('body').append(
				`<form id="${form_id}" enctype="multipart/form-data" action="upload" method="post">
					<input type="file" class="quanlt_file_button" name="file[]" multiple hidden />
				</form>`
			);

			$(`#${form_id} .quanlt_file_button`).on('change', function(e) {
				e.preventDefault();
				var formData = new FormData($(this).parents('form')[0]);

				$.ajax({
					url: 'upload',
					type: 'POST',
					xhr: function() {
						var xhr = $.ajaxSettings.xhr();
						xhr.upload.onprogress = function(evt) {
							let percent = Math.round(evt.loaded / evt.total * 100);
							percent = percent == 100 ? percent - 1 : percent;
							$(quanlt_btn_upload).html(`<i class="fas fa-upload"></i> ${percent} %`);
						};
						xhr.upload.onload = function() {
							console.log('DONE!')
						};
						return xhr;
					},
					beforeSend: function() {
						$(quanlt_btn_upload).html(`<i class="fas fa-sync fa-spin"></i>`);
						$(quanlt_btn_upload).prop('disabled', true)
						$(quanlt_btn_upload).prop('title', 'Đang upload...')
					},
					success: function(response) {
						$(quanlt_btn_upload).html(quanlt_btn_upload_old);
						$(quanlt_btn_upload).prop('disabled', false)
						callback_upload_image(quanlt_cb, response, quanlt_input_target, quanlt_btn_upload)
						$(quanlt_btn_upload).prop('title', '');
					},
					data: formData,
					cache: false,
					contentType: false,
					processData: false
				});
				return false;
			});
		}

		$(`#${form_id} .quanlt_file_button`).click();
	}

	function callback_upload_image(cb, response, input_target, btn_upload) {
		try {
			try {
				response = JSON.parse(response);

				if (response.status) {

					if (Object.keys(response.data).length) {
						for (const [key, value] of Object.entries(response.data)) {
							//upload ok
							if (value.status) {

								// gán dữ liệu vào thẻ input target
								$(input_target).val(value.link);

								// gọi call back nếu có
								if (cb != '') {
									try {
										window[cb](value.link, input_target, value.name, btn_upload);
									} catch (error) {
										console.log(error)
									}
								}

							}
							//upload lỗi
							else {
								let error_text = '';
								for (const [key, error] of Object.entries(value.error)) {
									error_text += '- ' + error + '<br/>';
								}
								toasts_danger(`${error_text} Ảnh: ${value.name} `, 'Thất bại')
							}
						}
					} else {
						toasts_danger('Xin lỗi, không lưu được ảnh', 'Thất bại')
					}

				} else {
					toasts_danger(response.error, 'Thất bại')
				}

			} catch (error) {
				console.log(error)
				toasts_danger('Xin lỗi, upload ảnh đang gặp vấn đề!', 'Thất bại')
			}
		} catch (error) {
			console.log(error)
		}
	}
</script>
<!-- /.upload anh -->

<!-- tooltipTriggerList -->
<script>
    tooltipTriggerList('body');
    function tooltipTriggerList(body) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll(`${body} [data-bs-toggle="tooltip"]`))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    }
</script>
</body>

</html>