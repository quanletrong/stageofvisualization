<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!-- banner -->
<?php $this->load->view('v2023/home/components/1_banner_view.php'); ?>

<div class="container mt-3 mb-3 d-flex flex-column align-items-center">
    <div class="text-color fs-5">America's #1 Virtual Staging Service</div>
    <div class="" style="font-size: 2.7em; font-weight: bold;">Virtual Staging for Real Estate</div>
    <div class="mt-2">Send Your Property Photos to Get Them Staged in 12 to 24 Hours</div>
    <a href="<?= site_url(LINK_ORDER) ?>" class="btn btn-danger btn-lg mt-2 px-4 text-white">Place order</a>
    <div class="mt-2">
        Questions? Call
        <a href="tel: <?=$setting['phone']?>"><span class="link-color"><?=$setting['phone']?></span></a>
    </div>
</div>


<!-- DANH SACH DICH VU -->
<?php $this->load->view('v2023/home/components/2_service_view.php'); ?>

<!-- HOW IT WORKS -->
<?php $this->load->view('v2023/home/components/3_hiw.php'); ?>


<!-- PHONG CACH THIET KE -->
<?php $this->load->view('v2023/home/components/4_phong_cach.php'); ?>

<!-- ĐỐI TÁC -->
<?php $this->load->view('v2023/home/components/5_doi_tac.php'); ?>

<!-- REVIEW -->
<?php $this->load->view('v2023/home/components/6_review.php'); ?>

<!-- Your Happiness is Guaranteed -->
<div class="container-fluid py-5" style="background-color: #e1e7e2;">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="fw-bold" style="font-size: 2em;"><?=$happy_guaranteed['title']?></div>
                <div class="mt-2 fs-5 fw-light" style="font-weight: 300; line-height: 1.5em;"><?=$happy_guaranteed['sapo']?></div>
            </div>
            <div class="col-12 col-lg-6 text-center">
                <img src="<?=$happy_guaranteed['image_path']?>" alt="" width="250" height="250">
            </div>
        </div>
    </div>
</div>

<!-- Why Virtually Stage? -->
<?php $this->load->view('v2023/home/components/7_why.php'); ?>
<script>
    $(document).ready(function() {

        $('.lazy').lazy();
    })
</script>