<style>
  #pinned {
    padding: 5px 10px;
    border-radius: 10px;
    background-color: rgb(248, 248, 248);
  }

  #pinned:hover {
    background-color: rgb(240, 240, 240);
  }

  #modal-pinned-msg .pinned-item {
    margin-top: 20px;
    padding: 10px;
    background: rgb(243, 243, 243);
    border-radius: 15px;

    display: flex;
    justify-content: flex-start;
    gap: 10px;
  }

  #modal-pinned-msg .pinned-item .fullname {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px
  }
</style>

<div style="font-size: 13px; cursor: pointer; display: none;" id="pinned" onclick="$('#modal-pinned-msg').modal('show');">
  <div class="pinned-header" style="display: flex; gap:20px; align-items: center; justify-content: space-between;">
    <div class="d-none d-sm-block" style="color: blue;">
      Pinned Message (<span class=pinned-total>{{}}</span>)
    </div>
    <div style="display: flex; align-items: center; gap: 8px; color:rgb(122, 122, 122)">
      <i class="fas fa-thumbtack"></i>
      <span class="pinned-total d-block d-sm-none">{{}}</span>
    </div>
  </div>
  <div class="pinned-body d-none d-sm-block" style="color: #424242;">{{}}</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal-pinned-msg" style="display: none" aria-modal="true" role="dialog">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tin nhắn đã ghim</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="list-pinned"></div>
      </div>
    </div>
  </div>
</div>

<script>
  // setInterval thời gian gửi tin nhắn
  setInterval(() => {

    $('#modal-pinned-msg .time').each(function(i, obj) {
      let datetime = $(this).attr('title');
      if (moment(datetime).fromNow() !== $(this).text()) {
        $(this).html(moment(datetime).fromNow());
      }
    });

  }, 1000);

  // render html nút trả lời tin nhắn
  function render_btn_pin_msg(id_msg) {
    return `
    <div
        style="width:20px; cursor: pointer;" 
        onclick="click_pin_msg('${id_msg}')"
    >
        <div class="btn-pin-msg" style="display:none">
            <i class="fas fa-thumbtack" style="font-size: 0.85rem; color: gray"></i>
        </div>
    </div>`;
  }

  function click_pin_msg(id_msg) {
    $.ajax({
      url: `chat/ajax_set_pin/${id_msg}`,
      type: "POST",
      success: function(data, textStatus, jqXHR) {
        let kq = JSON.parse(data);

        if (kq.status) {

          // // emit cho các thành viên khác
          socket.emit('pinned', {
            id_gchat: kq.data.id_gchat,
            pinneds: kq.data.pinneds,
            member_ids: kq.data.member_ids,
          });
        } else {
          alert(kq.error);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log(data);
        alert('Error');
      }
    });
  }

  function ajax_list_pinned_msg(id_gchat) {

    $.ajax({
      url: `chat/ajax_list_pinned_msg/${id_gchat}`,
      type: "POST",
      success: function(data, textStatus, jqXHR) {
        let kq = JSON.parse(data);
        if (kq.status) {
          render_pinned_msg(kq.data);
        } else {
          alert(kq.error);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log(data);
        alert('Error');
      }
    });
  }

  function render_pinned_msg(data) {
    $('#pinned').hide();
    
    // pinner header
    let pinned_total = data.length;
    if (pinned_total) {
      $('#pinned').show();
      $('#pinned .pinned-total').html(pinned_total);

      if (data[0].content_msg == '') {
        $('#pinned .pinned-body').html('Đính kèm')
      } else {
        $('#pinned .pinned-body').html(_.truncateText(data[0].content_msg, 30));
      }
    }

    // pinned modal
    let html_item = ``;
    data.forEach(pin => {

      let timeSince = _.timeSince(pin.create_time_msg);
      let content_msg = pin.content_msg;

      let file_list_msg = pin.file_list_msg;
      let total_file = Object.keys(file_list_msg).length;
      let max_width_image_pc = total_file == 1 ? '350px' : '250px';
      let max_width_image_mb = '100%';
      let max_width = _.isMobile() ? max_width_image_mb : max_width_image_pc;

      let ratio_imgae = total_file > 1 ? 'aspect-ratio: 1;object-fit: cover;' : '';
      let html_list_file = render_files_msg(file_list_msg, timeSince, max_width, ratio_imgae)

      html_item += `
      <div class="pinned-item">
        <img 
          class="rounded-circle border avatar" 
          style="width:30px; aspect-ratio: 1;object-fit: cover;height: 30px;" 
          src="${pin.avatar_create_msg}">
        <div style="width: -webkit-fill-available">
          <div class="fullname">
            <small style="color:#7c7c7c;">${pin.fullname_create_msg}</small>
            <div class="pinned-btn-remove" style="cursor:pointer" onclick="ajax_remove_pinned(${pin.id_pinned})">
              <i class="fas fa-trash" style="font-size: 0.85rem; color: gray"></i>
            </div>
          </div>
          <div>
            <div>
              ${
                html_list_file != ''
                  ?`<div class="rounded d-flex mb-1" style="flex-wrap: wrap; gap:5px;">${html_list_file}</div>`
                  : ``
              }

              ${
                content_msg != ''
                  ? `
                  <div class="rounded mb-1" style="background: #e1f0ff; padding: 5px 10px; width: fit-content;">
                      <div style="white-space: pre-line;">${content_msg}</div>
                  </div>`
                  : ``
              }
            </div>
            <small style="color:#7c7c7c" class="time" title="${pin.create_time_msg}"></small>
          </div>
        </div>
      </div>`;
    })

    $('#modal-pinned-msg .list-pinned').html(html_item);
  }

  function ajax_remove_pinned(id_pinned) {

    $.ajax({
      url: `chat/ajax_remove_pinned/${id_pinned}`,
      type: "POST",
      success: function(data, textStatus, jqXHR) {
        let kq = JSON.parse(data);

        if (kq.status) {

          // emit cho các thành viên khác
          socket.emit('pinned', {
            id_gchat: kq.data.id_gchat,
            pinneds: kq.data.pinneds,
            member_ids: kq.data.member_ids,
          });
        } else {
          alert(kq.error);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log(data);
        alert('Error');
      }
    });
  }
</script>