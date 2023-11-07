<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view($template_f . 'component/footer/footer_view'); ?>


<!-- upload anh -->
<form id="quanlt_frm_files" enctype="multipart/form-data" action="upload" method="post">
	<!-- <input type="file" id="quanlt_file_button" name="file[]" accept="image/*" multiple hidden /> -->
	<input type="file" id="quanlt_file_button" name="file[]" multiple hidden />
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
					beforeSend: function() {
						$(quanlt_btn_upload).html(`<i class="fas fa-sync fa-spin"></i>`);
						$(quanlt_btn_upload).prop('disabled', true)
					},
					success: function(response) {
						$(quanlt_btn_upload).html(quanlt_btn_upload_old);
						$(quanlt_btn_upload).prop('disabled', false)
						callback_upload_image(quanlt_cb, response, quanlt_input_target, quanlt_btn_upload)
					},
					data: formData,
					cache: false,
					contentType: false,
					processData: false
				});
				return false;
			});
		})
		var quanlt_cb; // tên hàm call back sau khi upload xong
		var quanlt_input_target;
		var quanlt_btn_upload;
		var quanlt_btn_upload_old;
		function quanlt_upload(e) {
			quanlt_cb = $(e).data('callback');
			quanlt_input_target = $(e).data('target');
			quanlt_btn_upload = e
			quanlt_btn_upload_old = $(e).html();
			attr_data = $(e).data();

			$('#quanlt_file_button').click();
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
									alert(`${error_text} Ảnh: ${value.name} `, 'Thất bại')
								}
							}
						} else {
							alert('Xin lỗi, không lưu được ảnh', 'Thất bại')
						}

					} else {
						alert(response.error, 'Thất bại')
					}

				} catch (error) {
					console.log(error)
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