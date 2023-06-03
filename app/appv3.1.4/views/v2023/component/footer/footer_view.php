<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="container-fluid border-top">


    <div class="container py-5" id="footer">
        <div class="row">
            <div class="d-none d-lg-block col-12 col-lg-3 text-center">
                <img src="<?= $setting['logo_vuong_path'] ?>" alt="" width="200">
            </div>
            <div class="col-12 col-lg-3">
                <div data-bs-toggle="collapse" data-bs-target="#foote_virtual_staging" aria-expanded="false" aria-controls="foote_virtual_staging">
                    <strong>VIRTUAL STAGING</strong>
                </div>
                <div class="collapse collapse-horizontal show" id="foote_virtual_staging">
                    <div class="fw-light mb-2"><a href="">Overview</a></div>
                    <div class="fw-light mb-2"><a href="<?= LINK_HIW ?>">How it works</a></div>
                    <div class="fw-light mb-2"><a href="<?= LINK_PRICINGS ?>">Pricings</a></div>
                    <div class="fw-light mb-2"><a href="<?= LINK_LIBRARY ?>">Library</a></div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div data-bs-toggle="collapse" data-bs-target="#foote_company" aria-expanded="false" aria-controls="foote_company">
                    <strong>COMPANY</strong>
                </div>
                <div class="collapse collapse-horizontal show" id="foote_company">
                    <div class="fw-light mb-2"><a href="<?= LINK_ABOUT ?>">About</a></div>
                    <div class="fw-light mb-2"><a href="<?= LINK_CAREERS ?>">Careers</a></div>
                    <div class="fw-light mb-2"><a href="<?= LINK_POLICY ?>">Privacy Policy</a></div>
                    <div class="fw-light mb-2"><a href="<?= LINK_TERMS ?>">Terms of Use</a></div>
                    <div class="fw-light mb-2"><a href="<?= LINK_REFUND ?>">Refund Policy</a></div>
                </div>
            </div>

            <div class="col-12 col-lg-3">
                <div data-bs-toggle="collapse" data-bs-target="#foote_contact" aria-expanded="false" aria-controls="foote_contact">
                    <strong>CONTACT</strong>
                </div>
                <div class="collapse collapse-horizontal show" id="foote_contact">
                    <div class="fw-light mb-2"><a href="<?= LINK_CONTACT ?>">Contact</a></div>
                    <div class="fw-light mb-2"><a href="tel:<?= $setting['phone'] ?>">Call: <?= $setting['phone'] ?></a></div>
                    <div class="fw-light mb-2"><a href="maito:contact@email.com">Email: <?= $setting['email'] ?></a></div>
                </div>
            </div>
        </div>
        <div class="mt-5">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="d-flex d-none" style="gap:20px">
                        <div class="fs-small fw-light"><a href="<?= LINK_POLICY ?>">Privacy Policy</a></div>
                        <div class="fs-small fw-light"><a href="<?= LINK_TERMS ?>">Terms of Use</a></div>
                        <div class="fs-small fw-light"><a href="<?= LINK_REFUND ?>">Refund Policy</a></div>
                    </div>
                    <div class="fs-small fw-light mt-3">© 2023 Stuccco, LLC. All rights reserved.</div>
                </div>
                <div class="d-flex" style="gap:20px">
                    <a href="<?= $setting['link_facebook'] ?>"><i class="fa-brands fa-square-facebook fs-4"></i></a>
                    <a href="<?= $setting['link_instagram'] ?>"><i class="fa-brands fa-square-instagram fs-4"></i></a>
                    <a href="<?= $setting['link_youtube'] ?>"><i class="fa-brands fa-youtube fs-4"></i></a>
                    <a href="<?= $setting['link_linkedin'] ?>"><i class="fa-brands fa-linkedin fs-4"></i></a>



                </div>
            </div>

        </div>
    </div>
</div>