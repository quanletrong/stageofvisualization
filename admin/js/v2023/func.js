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

            lineCount++;

            const words = breaks[j].split(' ');

            let currentLine = '';
            for (let i = 0; i < words.length; i++) {

                const wordWidth = context.measureText(words[i] + ' ').width;
                const lineWidth = context.measureText(currentLine).width;

                if (lineWidth + wordWidth > textareaWidth) {
                    lineCount++;
                    currentLine = words[i] + ' ';
                } else {
                    currentLine += words[i] + ' ';
                }
            }
        }
        lineCount++;

        return lineCount;
    },
    isIPv4: function (ip) {
        if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ip)) {
            return (true)
        }
        return (false)
    },
    downloadURI: function (uri, name) {
        var link = document.createElement("a");
        link.download = name;
        link.href = uri;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        delete link;
    },
    auto_update_time_since: function (el_target, datetime) {
        setInterval(() => {
            let seconds = moment(Date.now()).unix() - moment(datetime).unix();
            let interval = Math.floor(seconds / 31536000);
            if (interval >= 1) {
                $(el_target).html(interval + " năm");
                return
            }
            interval = Math.floor(seconds / 2592000);
            if (interval >= 1) {
                $(el_target).html(interval + " tháng");
                return
            }
            interval = Math.floor(seconds / 86400);
            if (interval >= 1) {
                $(el_target).html(interval + " ngày");
                return
            }
            interval = Math.floor(seconds / 3600);
            if (interval >= 1) {
                $(el_target).html(interval + " giờ");
                return
            }
            interval = Math.floor(seconds / 60);
            if (interval >= 1) {
                $(el_target).html(interval + " phút");
                return
            }
            $(el_target).html(interval + " giây");
            return
        }, 1000);
    },
    
    findBootstrapEnvironment: function () {
        let envs = ['xs', 'sm', 'md', 'lg', 'xl'];

        let el = document.createElement('div');
        document.body.appendChild(el);

        let curEnv = envs.shift();

        for (let env of envs.reverse()) {
            el.classList.add(`d-${env}-none`);

            if (window.getComputedStyle(el).display === 'none') {
                curEnv = env;
                break;
            }
        }

        document.body.removeChild(el);
        return curEnv;
    },

    isMobile: function() {
        let env = _.findBootstrapEnvironment();
        let isMobile = ['xs', 'sm'].includes(env);

        return isMobile;
    },
    timeSince: function (datetime) {
        if (datetime != undefined || datetime == '') {
            let seconds = moment(Date.now()).unix() - moment(datetime).unix();
            let interval = Math.floor(seconds / 31536000);
            if (interval >= 1) {
                // return (interval + " năm");
                return moment(datetime).format('H:mm DD/MM/YYYY');
            }
            interval = Math.floor(seconds / 2592000);
            if (interval >= 1) {
                // return (interval + " tháng");
                return moment(datetime).format('H:mm DD/MM/YYYY');
            }
            interval = Math.floor(seconds / 86400);
            if (interval >= 1) {
                // return (interval + " ngày");
                return moment(datetime).format('H:mm DD/MM/YYYY');
            }
            interval = Math.floor(seconds / 3600);
            if (interval >= 1) {
                return (interval + " giờ");
            }
            interval = Math.floor(seconds / 60);
            if (interval >= 1) {
                return (interval + " phút");
            }
            return ('Vừa xong');
        }
    },

    getRandomInt: function(max=9999999999) {
        return Math.floor(Math.random() * max);
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