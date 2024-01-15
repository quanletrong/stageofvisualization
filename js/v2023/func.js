const _ = {

    isImage: function (url_image) {
        return /\.(jpg|jpeg|png|webp|avif|gif|svg)$/.test(url_image.toLowerCase());
    },

}

const CHAT = {
    open_close_chat: function (el) {
        $(`${el} .box_chat` ).slideToggle('fast', 'swing');
        $(`${el} .small_chat`).toggleClass('d-none');
        $(`${el} .chat .content_discuss`).focus();
        $(`${el} .chat .list-chat`).scrollTop($(`${el} .chat .list-chat`)[0].scrollHeight);
        $(`${el} .small_chat .tin-nhan-moi`).text(0).hide();
        console.log($(`${el} .small_chat .tin-nhan-moi`).length)
    }
}