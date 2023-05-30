<div class="container">
    <center class="fw-bold mt-3" style="font-size: 2em;">Virtual Staging Library</center>
    <center class="fs-5 fw-light">See and shop from Stuccco's vast virtual staging design library</center>
</div>

<div class="container-fluid sticky-top bg-white" id="filter-library" style="z-index: 1019;">
    <div class="container bg-white">
        <div id="owl-filter-button" class="owl-carousel owl-theme mt-3 ">
            <?php foreach ($room as $id => $rm) { ?>
                <button class="btn btn-outline-secondary btn-sm text-uppercase rounded-0 fw-semibold btn-room-filter border border-2 border-secondary" onclick="$('.btn-room-filter').addClass('btn-outline-secondary').removeClass('btn-secondary');$(this).addClass('btn-secondary').removeClass('btn-outline-secondary'); filter()" data-room="<?= $id ?>" style="width: 190px;">
                    <?= $rm['name'] ?>
                </button>
            <?php } ?>
        </div>
        <div id="owl-filter-radio" class="owl-carousel mt-3 d-flex pb-2" style="gap:20px; width: 100%;">

            <button class="btn btn-outline-secondary btn-sm text-uppercase rounded-0 fw-semibold btn-style-filter border border-2 border-secondary" onclick="$('.btn-style-filter').addClass('btn-outline-secondary').removeClass('btn-secondary');$(this).addClass('btn-secondary').removeClass('btn-outline-secondary'); filter()" data-style="" style="width: 190px;">
                Tất cả phong cách
            </button>

            <?php foreach ($style as $id => $st) { ?>
                <button class="btn btn-outline-secondary btn-sm text-uppercase rounded-0 fw-semibold btn-style-filter border border-2 border-secondary" onclick="$('.btn-style-filter').addClass('btn-outline-secondary').removeClass('btn-secondary');$(this).addClass('btn-secondary').removeClass('btn-outline-secondary'); filter()" data-style="<?= $id ?>" style="width: 190px;">
                    <?= $st['name'] ?>
                </button>
            <?php } ?>
        </div>
    </div>
</div>

<div class="container-fluid py-3">
    <div class="container">
        <div class="row mt-3">

            <?php foreach ($library as $id => $lb) { ?>
                <div class="col-12 col-md-6 col-lg-3">
                    <img src="<?= $lb['image_path'] ?>" class="w-100 image-library lazy shadow" style="aspect-ratio: 16/9; object-fit: cover; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#exampleModal" data-room="<?= $lb['id_room'] ?>" data-style="<?= $lb['id_style'] ?>" data-name="<?= $lb['name'] ?>">
                    <p class="text-center mt-2"><strong><?= $lb['name'] ?></strong></strong></p>
                </div>
            <?php } ?>
        </div>

        <center class="mt-3 d-none">
            <button class="btn btn-outline-danger">View more...</button>
        </center>
    </div>
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
                <img src="" style="width: 100%; object-fit: cover; cursor: pointer;">
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


        $("#owl-filter-button").owlCarousel({
            autoplay: false,
            margin: 10,
            responsiveClass: true,
            nav: false,
            dots: false,
            autoWidth: true,
        });

        $("#owl-filter-radio").owlCarousel({
            autoplay: false,
            margin: 10,
            responsiveClass: true,
            nav: false,
            dots: false,
            autoWidth: true,
        });

        $('#exampleModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var src = button.attr('src');
            var name = button.data('name');
            var modal = $(this);

            modal.find('.modal-body img').attr('src', src);
            modal.find('.modal-body .image-name').text(name);
        })
    });

    filter();

    function filter() {
        let id_room = $('.btn-room-filter.btn-secondary').data('room');
        let id_style = $('.btn-style-filter.btn-secondary').data('style');

        $('.image-library').each(function() {
            let image_room = $(this).data('room');
            let image_style = $(this).data('style');

            if ((id_room == undefined || id_room == '' || id_room == image_room) && (id_style == undefined || id_style == '' || id_style == image_style)) {
                $(this).parent().show();
            } else {
                $(this).parent().hide();
            }
        })
    }
</script>