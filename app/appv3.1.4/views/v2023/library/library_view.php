<div class="container">
    <center class="fw-bold mt-3" style="font-size: 2em;">Virtual Staging Library</center>
    <center class="fs-5 fw-light">See and shop from Stageofvisualization's vast virtual staging design library</center>
</div>
<style>
    .active-filter,
    .btn-room-filter:hover,
    .btn-style-filter:hover {
        border-color: #ee1c25 !important;
        color: #ee1c25
    }

    .fa-circle-chevron-right,
    .fa-circle-chevron-left {
        color: #d4d4d4;
        cursor: pointer;
        font-size: 40px;
    }

    .fa-circle-chevron-right:hover,
    .fa-circle-chevron-left:hover {
        color: #767676;
    }
</style>
<div class="container-fluid sticky-top" id="filter-library" style="z-index: 1019; background-color: #f0f0f0;">
    <div class="container">
        <div id="owl-filter-room" class="mt-3" style="display: flex; flex-wrap: wrap; justify-content: space-between;">
            <?php foreach ($room as $id => $rm) { ?>
                <button 
                    class="btn btn-sm text-uppercase rounded-0 fw-semibold btn-room-filter border border-2 border-secondary" 
                    onclick="click_room(this)" 
                    data-room="<?= $id ?>" 
                    style="width: 140px; padding: 2px 0; font-size: 0.8rem; font-weight: 500 !important;"
                >
                    <?= $rm['name'] ?>
                </button>
            <?php } ?>
        </div>
        <div id="owl-filter-style" class="mt-1 pb-2" style="display: flex; flex-wrap: wrap; gap:11px">
            <button
                id="style_0"
                class="btn btn-sm text-uppercase rounded-0 fw-semibold btn-style-filter border border-1 border-secondary"
                onclick="click_style(this)" 
                data-style="0" 
                style="width: 140px; padding: 0; font-size: 0.7rem; font-weight: 500 !important;"
            >
                Tất cả phong cách
            </button>

            <?php foreach ($style as $id => $st) { ?>
                <button 
                    id="style_<?= $id ?>" 
                    class="btn btn-sm text-uppercase rounded-0 fw-semibold btn-style-filter border border-1 border-secondary"     
                    onclick="click_style(this)" 
                    data-style="<?= $id ?>" 
                    style="width: 140px; padding: 0; font-size: 0.7rem;  font-weight: 500 !important;"
                >
                    <?= $st['name'] ?>
                </button>
            <?php } ?>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- <div class="container"> -->
        <div class="row mt-3" id="list_image">

            <?php foreach ($library as $id => $lb) { ?>
                <div class="col-12 col-md-6 col-lg-3">
                    <img data-src="<?= $lb['image_path_thumb'] ?>" data-image-thumb="<?= $lb['image_path_thumb'] ?>" data-image="<?= $lb['image_path'] ?>" class="w-100 image-library lazy shadow" style="aspect-ratio: 16/9; object-fit: cover; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#exampleModal" data-room="<?= $lb['id_room'] ?>" data-style="<?= $lb['id_style'] ?>" data-name="<?= $lb['name_show'] ?>" onclick="curr_active = $(this).parent().data('index')">
                    <p class="text-center mt-2"><strong><?= $lb['name_show'] ?></strong></strong></p>
                </div>
            <?php } ?>
        </div>

        <center class="mt-3 d-none">
            <button class="btn btn-outline-danger">View more...</button>
        </center>
    <!-- </div> -->
</div>

<!-- Button trigger modal -->
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="image" style="display: flex;align-items: center;justify-content: space-between;gap: 10px;height: 80vh;background-size: contain;background-repeat: no-repeat;background-position: center;">
                    <div onclick="back_image(this)"><i class="fa-solid fa-circle-chevron-left"></i></div>
                    <div onclick="next_image(this)"><i class="fa-solid fa-circle-chevron-right"></i></div>
                </div>

                <center>
                    <h4 class="image-name mt-2"></h4>
                </center>
            </div>
        </div>
    </div>
</div>

<style>
    #owl-filter-button .item {
        background: #42bdc2;
        padding: 30px 0px;
        margin: 5px;
        color: #FFF;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        text-align: center;
    }
</style>

<script>
    function click_room(e) {
        $('.btn-room-filter').removeClass('active-filter');
        $(e).addClass('active-filter');
        filter(1);
    }

    function click_style(e) {

        $('.btn-style-filter').removeClass('active-filter');
        $(e).addClass('active-filter');
        filter(2)
    }

    $(document).ready(function() {

        $('.lazy').lazy();

        $(window).on("scroll", function() {
            let heightMenu = $('.navbar').outerHeight();
            $('#filter-library').css('top', heightMenu + 'px');
        });

        $(window).resize(function() {
            if ($(window).width() < 992) {
                $('#filter-library button').addClass('btn-sm')
            } else {
                $('#filter-library button').removeClass('btn-sm')
            }
        });


        // $("#owl-filter-room").owlCarousel({
        //     autoplay: false,
        //     margin: 10,
        //     responsiveClass: true,
        //     nav: false,
        //     dots: false,
        //     autoWidth: true,
        // });

        // $("#owl-filter-style").owlCarousel({
        //     autoplay: false,
        //     margin: 10,
        //     responsiveClass: true,
        //     nav: false,
        //     dots: false,
        //     autoWidth: true,
        // });

        init_width_room();
        init_width_style();


        function init_width_room() {
            let width_owl = $('#owl-filter-room').width();
            let count_room = $('#owl-filter-room button').length;
            let width_room = width_owl / count_room;
            width_room = width_room < 110 ? 110 : width_room; // min width = 110
            $('#owl-filter-room button').css('width', (width_room - 10) + 'px');
        }

        function init_width_style() {
            let width_owl = $('#owl-filter-style').width();
            let count_style = $('#owl-filter-style button').length;
            let width_style = width_owl / count_style;
            console.log(width_owl, count_style, width_style)
            width_style = width_style < 110 ? 110 : width_style; // min width = 110
            $('#owl-filter-style button').css('width', (width_style - 10) + 'px');
        }

        $('#exampleModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var image_thumb = button.data('image-thumb');
            var image = button.data('image');
            var name = button.data('name');
            var modal = $(this);

            modal.find('.modal-body .image').css('background-image', `url('${image}'), url('${image_thumb}')`);
            modal.find('.modal-body .image-name').text(name);
        })
    });

    var list_active = [];
    var curr_active = [];
    filter();

    function filter(type) {
        list_active = [];
        let id_room = $('.btn-room-filter.active-filter').data('room');
        let id_style = '';
        if (type == 1) {
            $('.btn-style-filter').removeClass('active-filter');
        } else {
            id_style = $('.btn-style-filter.active-filter').data('style');
        }

        $('.btn-style-filter').hide();
        $('#style_0').show();

        let index = 0;
        $('.image-library').each(function() {
            let image_room = $(this).data('room');
            let image_style = $(this).data('style');

            // hiện thị button style
            if(id_room == undefined || id_room == '' || id_room == image_room) {
                $(`#style_${image_style}`).show();
            }

            let ok_room = id_room == undefined || id_room == '' || id_room == image_room;
            let ok_style = id_style == undefined || id_style == '' || id_style == image_style;
            if (ok_room && ok_style) {
                $(this).parent().show();
                $(this).parent().data('index', index++)
                let image_thumb = $(this).data('image-thumb');
                let image = $(this).data('image');
                let name = $(this).data('name');
                // list_active.push()
                list_active = [...list_active, {
                    'image_thumb': image_thumb,
                    'image': image,
                    'name': name
                }]
            } else {
                $(this).parent().hide();
            }
        })

        curr_active = 0;

        scroll_to('#owl-filter-room');
    }

    function back_image(e) {
        if (curr_active > 0) {
            let back_image = list_active[curr_active - 1];
            curr_active = curr_active - 1
            $('#exampleModal .image').css('background-image', `url('${back_image.image}'), url('${back_image.image_thumb}')`);
            $('#exampleModal .image-name').text(back_image.name);
        }
    }

    function next_image(e) {
        if (curr_active + 1 < list_active.length) {
            let next_image = list_active[curr_active + 1];
            curr_active = curr_active + 1
            $('#exampleModal .image').css('background-image', `url('${next_image.image}'), url('${next_image.image_thumb}')`);
            $('#exampleModal .image-name').text(next_image.name);
        }
    }

    document.onkeydown = function(e) {
        switch (e.which) {
            case 37:
                back_image();
                break;

            case 38: // up
                break;

            case 39:
                next_image()
                break;

            case 40: // down
                break;

            default:
                return; // exit this handler for other keys
        }
        e.preventDefault(); // prevent the default action (scroll / move caret)
    };
</script>