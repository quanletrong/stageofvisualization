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
        <a href="tel: 0987654321"><span class="link-color">1-833-788-2226</span></a>
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
<div class="container-fluid py-5" style="background-color: #edf1ee;">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="fw-bold" style="font-size: 2em;">Your Happiness is Guaranteed</div>
                <div class="mt-2 fs-5 fw-light" style="font-weight: 300; line-height: 1.5em;">
                    If you want your virtually staged photos to be revised, send us a message at the bottom of your
                    virtual staging order within 60 days of purchase and we will gladly revise your virtually staged
                    photos free of charge until you are completely happy.
                </div>
            </div>
            <div class="col-12 col-lg-6 text-center">
                <img src="images/happiness-guarantee.svg" alt="" width="250" height="250">
            </div>
        </div>
    </div>
</div>

<!-- Why Virtually Stage? -->
<?php $this->load->view('v2023/home/components/7_why.php'); ?>