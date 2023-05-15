var lsideBarPerfectScroll = null;
var lsideBarMenuObj = null;

function show_hide_scroll_left_menu() {

    let body_height = $('body').height();
    let window_height = $(window).height();
    let headerBar_height = $('#headerBar').outerHeight();
    let footerBar_height = $('#footerBar').outerHeight();

    let scroll_top = $(window).scrollTop();
    let scroll_bottom = scroll_top + window_height;
    let lsideBarMenuBox = $('#lsidebarMenuBox');
    let lsideBarMenu = $('#lsidebar-menu');
    if(scroll_top > $('#headerBar').height()){
        lsideBarMenuBox.css('top', 0);
        lsideBarMenuBox.css('height', window_height - footerBar_height + 'px');
        lsideBarMenu.css('top', '60px');
    }else{
        lsideBarMenuBox.css('top', '');
        lsideBarMenuBox.css('height', window_height - footerBar_height - headerBar_height + 'px');
        lsideBarMenu.css('top', 60 - scroll_top + 'px');
    }

    let add = footerBar_height - (body_height-scroll_bottom);
    if(add > 0){
        lsideBarMenuBox.css('height', window_height - add - 1 + 'px');
    }else{
        lsideBarMenuBox.css('height', window_height - 1 + 'px');
    }

    if((body_height - headerBar_height - footerBar_height) < (window_height) && scroll_top < 60) {
        lsideBarMenuBox.css('height', body_height - footerBar_height - headerBar_height + 'px');
    }

    if((body_height - footerBar_height - headerBar_height - scroll_top) > (window_height - headerBar_height)) {
        $('#lsidebar-toggle').css('top', ((window_height - headerBar_height) / 2) + 24 + 'px');
    }else{
        $('#lsidebar-toggle').css('top', '50%');
    }
    lsideBarPerfectScroll.update();

    /*
    if($('.lmn-collapse').length){
        lsideBarMenuBox.css({ overflowY: '' });
    }else{
        lsideBarMenuBox.css({ overflowY: 'scroll' });
    }
    */
}


$(document).ready(function() {
    lsideBarMenuObj = document.querySelector('#lsidebarMenuBox');
    lsideBarPerfectScroll = new PerfectScrollbar('#lsidebarMenuBox', {
        wheelSpeed: 0.5
    });
    
    lsideBarMenuObj.addEventListener('ps-scroll-y', () => {
        if($('#lsidebarMenuBox > div.lsidebar-menu-content > ul > li').is('[class*="active"]')) 
        {
            let liMenu = $('#lsidebarMenuBox > div.lsidebar-menu-content > ul > li.active');
            let showSubMenu = liMenu.find("ul.lsidebar-mn-content:first");
            let position = liMenu.position();
            let position_top = position.top - parseInt(lsideBarPerfectScroll.scrollbarYRail.style.top);
            showSubMenu.css('top', position_top);
        }

        if($('#lsidebarMenuBox > div.lsidebar-menu-content > ul > li > div.active-tt').is('[class*="active-tt"]')) 
        {
            let ttMenu = $('#lsidebarMenuBox > div.lsidebar-menu-content > ul > li > div.active-tt');
            let parentTTMenu = ttMenu.parent();
            let positionTT = parentTTMenu.position();
            let position_top_tt = positionTT.top - parseInt(lsideBarPerfectScroll.scrollbarYRail.style.top);
            ttMenu.css('top', position_top_tt);
        }
    });
    
    //scroll event
    $( window ).scroll(function() {
        show_hide_scroll_left_menu();
    });
   
    //resize event
    $(window).bind('resize load', function() {
        show_hide_scroll_left_menu();

        var winW = $(window).width();
        var winH = $(window).height();
        let headerBarH = $('#headerBar').outerHeight();
        let footerBarH = $('#footerBar').outerHeight();
        let lsideBarContentH = winH - footerBarH - headerBarH - 1;
        $('#lsidebar-menu').css({ minHeight: lsideBarContentH });
    
        if (winW < 1199.98) {
            $('body').removeClass('lmn-expand').addClass('lmn-collapse');
        } else {
            $('body').removeClass('lmn-collapse').addClass('lmn-expand');
        }
    
        auto_open_submenu_when_resize_windown();
        show_hide_scroll_left_menu();

        // init scroll bar for collapse sub menu
        //chi ap dung truong hop collapse
        //if ($("body").hasClass('lmn-collapse')) {
            //new PerfectScrollbar(document.querySelector('#lsidebarMenuBox ul.lsidebar-mn-content.psScrollBar'), {
                //wheelSpeed: 0.5
            //});
        //}
    });

    //expend click menu
    $('.has-sub > a').click(function () {
        let menu = $(this).parent();
        on_menu(menu)
    })

    $('#lsidebar-toggle').click(function() {

        $('body').toggleClass('lmn-expand lmn-collapse');
        auto_open_submenu_when_click_lsidebar();
        show_hide_scroll_left_menu();

        // init scroll bar for collapse sub menu
        //chi ap dung truong hop collapse
        //if ($("body").hasClass('lmn-collapse')) {
            //new PerfectScrollbar(document.querySelector('#lsidebarMenuBox ul.lsidebar-mn-content.psScrollBar'), {
                //wheelSpeed: 0.5
            //});
        //}
    });

    //collapse hover menu
    $("#lsidebarMenuBox > .lsidebar-menu-content > ul.ul-sidebar-menu > li > a, #lsidebarMenuBox > .lsidebar-menu-content > .lsidebar-support > .lsidebar-support-content > li > div").mouseover(function() {
    //$("#lsidebarMenuBox > .lsidebar-menu-content > ul.ul-sidebar-menu > li > a, #lsidebarMenuBox > .lsidebar-menu-content > .lsidebar-support > .lsidebar-support-content > li > div").click(function() {
        if($(".lmn-collapse").length){
            //remove class active all menu
            let menu = $(this).parent();
            menu.siblings().removeClass('active');
            $('div.lsidebar-mn-tooltips').addClass('hidden-it-config');
            $('div.lsidebar-mn-tooltips').removeClass('active-tt');

            //remove class active all menu support
            $('.ul-sidebar-menu > li').removeClass('active');
            $('.lsidebar-support-content > li').removeClass('active');

            //show sub menu
            on_menu(menu);
        }
    })

    $("#lsidebarMenuBox > .lsidebar-menu-content > ul.ul-sidebar-menu > li").mouseout(function() {
        $('div.lsidebar-mn-tooltips').addClass('hidden-it-config');
        $('div.lsidebar-mn-tooltips').removeClass('active-tt');
    })

    $(document).mouseup(function(e) {
        if ($(".lmn-collapse").length) {

            var all_menu = $('.ul-sidebar-menu > li, .lsidebar-support > ul > li');
            var sub = $(".has-sub.active > ul");
            var menu = sub.siblings('a')

            var out_sub = !sub.is(e.target) && sub.has(e.target).length === 0;
            var out_menu = !menu.is(e.target) && menu.has(e.target).length === 0;

            if (out_sub && out_menu) {
                all_menu.removeClass('active');
            }
        }
    });
});

function on_menu(menu_element) {

    let menu = $(menu_element);
    let menu_sub = menu.find("ul.lsidebar-mn-content:first");
    let menu_tooltip = menu.find("div.lsidebar-mn-tooltips");
    let position = menu.position();
    let position_top = position.top;

    //truong hop menu dang exand
    if ($(".lmn-expand").length) {

        menu_element.siblings().removeClass('active');
        menu_element.siblings().find("a").removeClass('active');
        menu.toggleClass('active');
        menu.find("a").toggleClass('active');
    }

    //truong hop menu dang collapse
    if ($(".lmn-collapse").length) {
        menu.toggleClass('active');
        menu_sub.css('top', position_top - parseInt(lsideBarPerfectScroll.scrollbarYRail.style.top));
        if(menu_tooltip.length) {
            menu_tooltip.removeClass('hidden-it-config');
            menu_tooltip.addClass('active-tt');
            menu_tooltip.css('top', position_top - parseInt(lsideBarPerfectScroll.scrollbarYRail.style.top));
        }
    }
}

function auto_open_submenu_when_click_lsidebar() {

    let menu_active = $('#lsidebarMenuBox > div.lsidebar-menu-content > ul.ul-sidebar-menu > li.has-sub.current:first');
    
    if (menu_active.length == 0) {
        return;
    }else{

        let all_menu = $('#lsidebarMenuBox > div.lsidebar-menu-content > ul.ul-sidebar-menu > li');
        let sub = menu_active.find("ul.lsidebar-mn-content:first");
        let sub_first_item = sub.find('li:first');
    
        all_menu.removeClass('active');
        
        if ($("body").hasClass('lmn-expand')) {
            sub_first_item.hide();
        }
        let position = menu_active.position();
        let position_top = position.top - parseInt(lsideBarPerfectScroll.scrollbarYRail.style.top);
        sub.css('top', position_top);

        menu_active.addClass('active');

    }
    
}

function auto_open_submenu_when_resize_windown() {
    auto_open_submenu_when_click_lsidebar();
}

function on_click_sub_menu(sub1_elm) {
    let sub1 = $(sub1_elm);
    let sub2_ul = sub1.siblings("ul.lsidebar-mn-content");
    sub2_ul.slideToggle();
}

function on_click_menu_support(menu_element) {

    $('#lsidebar-menu .lsidebar-support-content').find('ul.lsidebar-mn-content ').hide();
    $('#lsidebar-menu .lsidebar-support-content > li').removeClass('active');
    let menu = $(menu_element);
    let menu_sub = menu.find("ul.lsidebar-mn-content");

    let position = menu.position();
    let position_top = position.top;

    //chi ap dung truong hop collapse
    if ($("body").hasClass('lmn-collapse')) {
        
        menu.toggleClass('active');
        menu_sub.css('top', position_top - parseInt(lsideBarPerfectScroll.scrollbarYRail.style.top));
    }
}