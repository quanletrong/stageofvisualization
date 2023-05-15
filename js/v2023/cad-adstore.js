function chkvideo(){
    let chk = true;
    const urlvideo = $.trim($("#url-video").val());
    const reg_url_mp4 = /^(https:|http:).*?(?=.mp4)/;

    if(urlvideo != "") {
        if(reg_url_mp4.test(urlvideo)){
            $(".pre-video").show();
            $(".pre-videos source").attr("src", urlvideo);
            $(".pre-videos").load();

            init_duration();
        }else{
            chk = false;
            $(".pre-video").hide();
            $("#sp_duration").val("");
        }
    }else{
        $(".pre-video").hide();
        $("#sp_duration").val("");
    }

    if(chk){
        $("#err-urlvideo").hide();
    }else{
        $("#err-urlvideo").show();
    }

    return chk;
}

function chk_url_video(type){
    let chk = true;
    const urlvideo = $.trim($("#url-video").val());
    const urlscript = $.trim($("#url-script").val());
    const reg_url_mp4 = /^(https:|http:).*?(?=.mp4)/;
    const reg_url_html5 = /^(https|http):\/\/[^\s$.?#].[^\s]*(html|js)$/;
    $("#err-urlvideo").hide();
    $("#err-urlscript").hide();
    
    if(type == '1'){ // banner poster video
        if(urlvideo != "") {
            if(reg_url_mp4.test(urlvideo)){
                $(".pre-video").show();
                $(".pre-videos source").attr("src", urlvideo);
                $(".pre-videos").load();
                init_duration();
            } else {
                chk = false;
                $(".pre-video").hide();
                $("#sp_duration").val("");
                
                $("#err-urlvideo").show();
 
            }
        }else{
            $(".pre-video").hide();
            $("#sp_duration").val("");
        }
    } else if(type == '2'){ // banner poster image
        $(".pre-video").hide();
        $("#sp_duration").val("");
        if(urlscript != "") {
            if(!reg_url_html5.test(urlscript)){
                chk = false;
                $("#err-urlscript").show();
            }
        }
    }

    return chk;
}

function init_duration(){
    var videos = document.querySelector('video');
    getduration(videos, "#sp_duration");
}

function getduration(video, input){
    setTimeout(function(){
        if(video.readyState){
            $(input).val(Math.floor(video.duration));
            //clearInterval(getduration);
        }
    }, 1000);
}