<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view($template_f . 'component/footer/footer_view'); ?>


<!-- upload anh -->
<form id="quanlt_frm_files" enctype="multipart/form-data" action="upload" method="post">
	<input type="file" id="quanlt_file_button" name="file[]" accept="image/*" multiple hidden />
	<script>
		$(function() {
			$("#quanlt_frm_files").on('change', '#quanlt_file_button', function(e) {
				e.preventDefault();
				var formData = new FormData($(this).parents('form')[0]);

				$.ajax({
					url: 'upload',
					type: 'POST',
					xhr: function() {
						var myXhr = $.ajaxSettings.xhr();
						return myXhr;
					},
					success: function(response) {
						quanlt_callback_upload_image(quanlt_cb, response, quanlt_input_target)
					},
					data: formData,
					cache: false,
					contentType: false,
					processData: false
				});
				return false;
			});
		})
		var quanlt_cb;
		var quanlt_input_target;

		function quanlt_upload(e) {
			quanlt_cb = $(e).data('callback');
			quanlt_input_target = $(e).data('target');

			$('#quanlt_file_button').click();
		}

		function quanlt_callback_upload_image(cb, response, input_target) {
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
											window[cb](value.link, input_target, value.name);
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
									// toasts_danger(`${error_text} Ảnh: ${value.name} `, 'Thất bại')
									alert(`${error_text} Ảnh: ${value.name} `, 'Thất bại')
								}
							}
						} else {
							// toasts_danger('Xin lỗi, không lưu được ảnh', 'Thất bại')
							alert('Xin lỗi, không lưu được ảnh', 'Thất bại')
						}

					} else {
						// toasts_danger(response.error, 'Thất bại')
						alert(response.error, 'Thất bại')
					}

				} catch (error) {
					console.log(error)
					// toasts_danger('Xin lỗi, upload ảnh đang gặp vấn đề!', 'Thất bại')
					alert('Xin lỗi, upload ảnh đang gặp vấn đề!', 'Thất bại')
				}
			} catch (error) {
				console.log(error)
			}
		}
	</script>
</form>
<!-- /.upload anh -->
</body>

</html>