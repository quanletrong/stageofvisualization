<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<nav class="navbar sticky-top navbar-expand-lg bg-body-tertiary py-1 shadow-sm bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img width="130" src="<?= $setting['logo_ngang_path'] ?>" alt="logo" class="d-inline-block align-text-top" srcset="">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 w-100 align-items-lg-center justify-content-lg-end">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        VIRTUAL STAGING
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="">Overview</a></li>
                        <li><a class="dropdown-item" href="<?= LINK_HIW ?>">How It Works</a></li>
                        <li><a class="dropdown-item" href="<?= LINK_PRICINGS ?>">Pricing</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="<?= LINK_LIBRARY ?>">LIBRARY</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= LINK_ORDER ?>">
                        <button type="button" class="btn btn-danger btn-sm">PLACE ORDER</button>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="tel:<?= $setting['phone'] ?>" style="font-weight: bold;color: #333333;"><?= $setting['phone'] ?></a>
                </li>

                <?php if ($isLogin) { ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            ACCOUNT
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if ($role == ADMIN || $role == SALE || $role == QC || $role == EDITOR) { ?>
                                <li><a class="dropdown-item" href="/admin">Go to ADMIN</a></li>
                            <?php } ?>
                            <li><a class="dropdown-item" href="<?= LINK_USER_ORDER ?>">My Orders and Listings</a></li>
                            <li><a class="dropdown-item" href="<?= LINK_USER_NOTICES ?>">Notifications </a></li>
                            <li><a class="dropdown-item" href="<?= LINK_USER_PROFILES ?>">Profile</a></li>
                            <li><a class="dropdown-item" href="<?= LINK_USER_SETTINGS ?>">Settings</a></li>
                            <li><a class="dropdown-item" href="<?= LINK_USER_TRANSACTIONS ?>">Transactions</a></li>
                            <li><a class="dropdown-item" href="<?= LINK_USER_LOGOUT ?>">Sign Out</a></li>
                        </ul>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?= LINK_USER_LOGIN ?>">LOGIN</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?= LINK_USER_REGISTER ?>">REGISTER</a>
                    </li>
                <?php } ?>


            </ul>
        </div>
    </div>
</nav>