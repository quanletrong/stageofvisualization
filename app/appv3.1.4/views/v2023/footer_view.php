<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view($template_f . 'component/footer/footer_view'); ?>

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
						$(quanlt_btn_upload).prop('title', 'Uploading...')
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
								toasts_danger(`${error_text} Image: ${value.name} `, 'Fail!')
							}
						}
					} else {
						toasts_danger('Sorry, image could not be saved!', 'Fail!')
					}

				} else {
					toasts_danger(response.error, 'Fail!')
				}

			} catch (error) {
				console.log(error)
				toasts_danger(`Sorry, there's a problem uploading photos!`, 'Fail!')
			}
		} catch (error) {
			console.log(error)
		}
	}
</script>
<!-- /.upload anh -->

<!-- paste image -->
<script>
	function quanlt_handle_paste_image(ev) {
		for (var i = 0; i < ev.clipboardData.items.length; i++) {
			var item = ev.clipboardData.items[i];
			if (item.type.indexOf("image") != -1) {
				quanlt_paste_submit_file_form(ev, item.getAsFile(), 'paste');
			} else {
				console.log("Discarding non-image paste data");
			}
		}
	}

	function quanlt_paste_submit_file_form(ev, file, type) {
		var extension = file.type.match(/\/([a-z0-9]+)/i)[1].toLowerCase();
		var formData = new FormData();
		formData.append('file', file, file.name);
		formData.append('extension', extension);
		formData.append("mimetype", file.type);
		formData.append('submission-type', type);

		var xhr = new XMLHttpRequest();
		xhr.responseType = "json";
		xhr.open('POST', 'upload/siglefile');
		xhr.onload = function() {
			if (xhr.status == 200) {
				try {
					let cb = ev.target.dataset.callback;
					let target = ev.target.dataset.target;
					let status = xhr.response.data.status
					let link_file = xhr.response.data.link
					let name_file = xhr.response.data.name

					if (status) {
						window[cb](link_file, target, name_file, ev.target);
					} else {
						alert(xhr.response.error)
					}
				} catch (error) {
					alert('Upload failed (ERR001)!');
					console.log(error)
				}
			} else {
				alert('Upload failed (ERR002)!');
				console.log(xhr.status)
			}
		};

		xhr.send(formData);
	}
</script>
<!-- /.paste image -->


<!-- drop file -->
<script>
	function quanlt_handle_drop_file(ev) {
		ev.preventDefault();

		if (ev.dataTransfer.items) {
			[...ev.dataTransfer.items].forEach((item, i) => {
				if (item.kind === "file") {
					const file = item.getAsFile();
					quanlt_drop_submit_file_form(ev, file, 'drop');
				}
			});
		} else {
			[...ev.dataTransfer.files].forEach((file, i) => {
				quanlt_drop_submit_file_form(ev, file, 'drop');
			});
		}
	}

	function quanlt_drop_submit_file_form(ev, file, type) {
		var extension = file.type.match(/\/([a-z0-9]+)/i)[1].toLowerCase();
		var formData = new FormData();
		formData.append('file', file, file.name);
		formData.append('extension', extension);
		formData.append("mimetype", file.type);
		formData.append('submission-type', type);

		var xhr = new XMLHttpRequest();
		xhr.responseType = "json";
		xhr.open('POST', 'upload/siglefile');
		xhr.onload = function() {
			if (xhr.status == 200) {
				try {
					let cb = ev.target.dataset.callback;
					let target = ev.target.dataset.target;
					let status = xhr.response.data.status
					let link_file = xhr.response.data.link
					let name_file = xhr.response.data.name

					if (status) {
						window[cb](link_file, target, name_file, ev.target);
					} else {
						alert(xhr.response.error)
					}
				} catch (error) {
					alert('Upload failed (ERR001)!');
					console.log(error)
				}
			} else {
				alert('Upload failed (ERR002)!');
				console.log(xhr.status)
			}
		};

		xhr.send(formData);
	}
</script>
<!-- /.drop file -->

<!-- CHAT BOX -->
<?php $this->load->view(TEMPLATE_FOLDER . 'component/_chat_customer_view.php');?>
<?php $this->load->view(TEMPLATE_FOLDER . 'component/_modal_login_view.php');?>
<!-- END CHAT BOX -->

</body>

</html>