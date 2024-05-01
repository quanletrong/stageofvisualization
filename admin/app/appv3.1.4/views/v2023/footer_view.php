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

				var upload_success = false;
				$.ajax({
					url: 'upload',
					type: 'POST',
					xhr: function() {

						var start_time = Date.now();
						var end_time = Date.now();

						var xhr = $.ajaxSettings.xhr();
						xhr.upload.onprogress = function(evt) {
							let percent = Math.round(evt.loaded / evt.total * 75);
							$(quanlt_btn_upload).html(`<i class="fas fa-upload mr-2"></i> <span style="color:red">${percent} %</span>`);

							end_time = Date.now();
						};

						xhr.upload.onload = function(evt) {

							var time_setInterval = 0;
							var time_onprogress = end_time - start_time;

							var myInterval = setInterval(() => {
								if (time_setInterval <= time_onprogress && upload_success == false) {
									time_setInterval += 500;
									let percent = Math.round(75 + (time_setInterval * 20) / time_onprogress);
									$(quanlt_btn_upload).html(`<i class="fas fa-upload mr-2"></i> <span style="color:red">${percent} %</span>`);
								} else {
									clearInterval(myInterval);
								}
							}, 500);
						};
						return xhr;
					},
					beforeSend: function() {
						$(quanlt_btn_upload).html(`<i class="fas fa-sync fa-spin"></i>`);
						$(quanlt_btn_upload).prop('disabled', true)
						$(quanlt_btn_upload).prop('title', 'Đang upload...')
					},
					success: function(response) {
						upload_success = true;
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

		var upload_success = false;
		var start_time = Date.now();
		var end_time = Date.now();

		var xhr = new XMLHttpRequest();
		xhr.responseType = "json";
		xhr.open('POST', 'upload/siglefile');
		xhr.progress = function(evt) {
			let percent = Math.round(evt.loaded / evt.total * 75);
			console.log(`progress ${percent}%`)
			// $(quanlt_btn_upload).html(`<i class="fas fa-upload mr-2"></i> <span style="color:red">${percent} %</span>`);

			end_time = Date.now();
		};
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

		let {
			cb,
			target,
			onbefore,
			onprogress,
			onsuccess,
			onerror
		} = ev.target.dataset;

		var extension = file.type.match(/\/([a-z0-9]+)/i)[1].toLowerCase();
		var formData = new FormData();
		formData.append('file', file, file.name);
		formData.append('extension', extension);
		formData.append("mimetype", file.type);
		formData.append('submission-type', type);

		var upload_success = false;
		var start_time = Date.now();
		var end_time = Date.now();

		var dropTo = Date.now();

		// co su kien before drop
		if(onbefore != undefined) {
			window[onbefore](ev, dropTo);
		}

		var xhr = new XMLHttpRequest();
		xhr.responseType = "json";

		xhr.upload.onprogress = function(e) {

			try {
				let percent = Math.round(e.loaded / e.total * 75);
				percent = percent == 100 ? percent - 1 : percent;
				end_time = Date.now();

				if(onprogress != undefined) {
					window[onprogress](ev, percent, dropTo);
				}

			} catch (error) {

			}
		};

		xhr.upload.onload = function(e) {

			var time_setInterval = 0;
			var time_onprogress = end_time - start_time;

			var myInterval = setInterval(() => {
				if (time_setInterval <= time_onprogress && upload_success == false) {
					time_setInterval += 500;
					let percent = Math.round(75 + (time_setInterval * 20) / time_onprogress);
					end_time = Date.now();

					if(onprogress != undefined) {
						window[onprogress](ev, percent, dropTo);
					}
				} else {
					clearInterval(myInterval);
				}
			}, 500);
		};

		xhr.open('POST', 'upload/siglefile');
		xhr.onload = function() {
			if (xhr.status == 200) {
				try {

					if (xhr.response.data.status) {
						if(onsuccess != undefined) {
							window[onsuccess](ev, xhr.response.data, dropTo);
						}
						upload_success = true;
					} else {
						window[onerror](ev, xhr.response.data, 'Upload failed (ERR001)!', dropTo);
					}
				} catch (error) {
					window[onerror](ev, xhr.response.data, 'Upload failed (ERR002)!', dropTo);
					console.log(error)
				}
			} else {
				window[onerror](ev, xhr.response.data, 'Upload failed (ERR003)!', dropTo);
				console.log(xhr.status)
			}
		};

		xhr.send(formData);
	}
</script>
<!-- /.drop file -->

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

<!-- CHAT  -->
<script type="module">
	const crr_uid = <?= $this->session->userdata('uid') ?>;
	const curr_page = '<?= $this->uri->rsegments[1] ?>';

	// init lan dau load page đếm số tin nhắn chưa đọc
	if (curr_page != 'chat') {
		let num_chat_chua_xem = await ajax_count_msg_chua_xem();
		update_msg_icon_badge(num_chat_chua_xem);
	} else {
		// page chat thì ẩn đếm số tin nhắn
		$('.sidebar .chat-menu-left .badge').hide();
	}

	// lắng nghe sự kiện thêm msg mới
	socket.on('add-msg-to-gchat', async data => {
		let {
			id_gchat,
			name_gchat,
			id_user,
			fullname,
			content,
			file_list,
			action_by
		} = data;

		// build toast
		if (curr_page != 'chat' && action_by != crr_uid) {

			var audio = new Audio('<?= ROOT_DOMAIN ?>images/Tieng-ting-www_tiengdong_com.mp3');
			audio.play();

			// toast
			let href = `admin/chat/index/${id_gchat}`;
			let content_show = content.length > 100 ? content.substring(0, 100) + '...' : content;
			let dinh_kem = isEmpty(file_list) ? '' : '<p><i>[Đính kèm]</i></p>';
			let fullname_show =
				`<p style="display: flex;justify-content: space-between;">
				<small>${fullname}</small>
				<small>Xem</small>
			</p>`;

			$.toast({
				heading: `<b>${name_gchat}</b>`,
				text: `<a style="text-decoration: none; border-bottom: none;" href="${href}">
					${content_show} 
					${dinh_kem}
					${fullname_show}
				</a>`,
				loader: true,
				hideAfter: 15000,
				bgColor: '#4CAF50',
				textColor: 'white'
			})

			// cập nhật số tin nhắn chưa đọc vào bên trái
			let num_chat_chua_xem = await ajax_count_msg_chua_xem();
			update_msg_icon_badge(num_chat_chua_xem);
		}
	})

	async function ajax_count_msg_chua_xem() {

		try {
			let result = await $.ajax({
				url: `chat/ajax_count_msg_chua_xem`,
			});

			let data = 0;
			let kq = JSON.parse(result);
			if (kq.status) {
				return parseInt(kq.data);
			} else {
				console.log(kq.error);
				return 0;
			}
		} catch (error) {
			console.error(error);
			return 0;
		}
	}

	function update_msg_icon_badge(num_chat_chua_xem) {

		// cập nhật số tin nhắn chưa đọc
		$('.sidebar .chat-menu-left .badge').html(num_chat_chua_xem);

		// nhấp nháy hiệu ứng
		if (num_chat_chua_xem > 0) {
			$('.sidebar .chat-menu-left i').addClass('zoom-in-out-box text-warning');
		}
	}
</script>
</body>

</html>