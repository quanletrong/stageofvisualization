const _ = {

    isImage: function (url_image) {
        return /\.(jpg|jpeg|png|webp|avif|gif|svg)$/.test(url_image.toLowerCase());
    },

    calculateNumLines: function (str, elTextarea) {

        const parseValue = (v) => v.endsWith('px') ? parseInt(v.slice(0, -2), 10) : 0;

        const textareaStyles = window.getComputedStyle(elTextarea);
        const font = `${textareaStyles.fontSize} ${textareaStyles.fontFamily}`;
        const paddingLeft = parseValue(textareaStyles.paddingLeft);
        const paddingRight = parseValue(textareaStyles.paddingRight);
        const textareaWidth = elTextarea.getBoundingClientRect().width - paddingLeft - paddingRight;


        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');
        context.font = font;

        const breaks = str.split(/\r\n|\r|\n/);
        
        let lineCount = 0;
        
        for (let j = 0; j < breaks.length; j++) {

            console.log( `j ${j}`, lineCount)
            lineCount ++;

            const words = breaks[j].split(' ');

            let currentLine = '';
            for (let i = 0; i < words.length; i++) {

                const wordWidth = context.measureText(words[i] + ' ').width;
                const lineWidth = context.measureText(currentLine).width;

                if (lineWidth + wordWidth > textareaWidth) {
                    // console.log(`i ${i}`, lineCount)
                    lineCount++;
                    currentLine = words[i] + ' ';
                } else {
                    currentLine += words[i] + ' ';
                }
            }
        }
        lineCount++;

        return lineCount;
    }
}

const CHAT = {
    open_close_chat: function (el) {
        $(`${el} .box_chat`).slideToggle('fast', 'swing');
        $(`${el} .small_chat`).toggleClass('d-none');
        $(`${el} .chat .content_discuss`).focus();
        $(`${el} .chat .list-chat`).scrollTop($(`${el} .chat .list-chat`)[0].scrollHeight);
        $(`${el} .small_chat .tin-nhan-moi`).text(0).hide();
    }
}