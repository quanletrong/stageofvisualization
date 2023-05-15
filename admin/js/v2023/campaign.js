$( document ).ready(function() {

    //for table list campaign
    $(window).bind("scroll", function() {
        var tblBannerOffsetTop = $('.tbl-data-outer').offset().top;
        var offset = $(this).scrollTop();
        var headerBarHeight = $('#headerBar').outerHeight();
        if(offset > tblBannerOffsetTop)
        {
            $('#thead-fixed').css({top: (offset - tblBannerOffsetTop + headerBarHeight) });
        }
        else
        {
            $('#thead-fixed').css({top: 0});
        }

    });
});