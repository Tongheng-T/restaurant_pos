<?php require_once "restaurant/resources/config.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="THPOS, thpos.store, ប្រព័ន្ធគ្រប់គ្រងទិន្នន័យ, Cambodia, Sales Management System, ប្រព័ន្ធគ្រប់គ្រងហាងកាហ្វេ ,ប្រព័ន្ធគ្រប់គ្រងភោជនីយដ្ឋាន,Restaurant Management System">
    <title>THPOS | By:THPOS</title>
    <link rel='shortcut icon' href="ui/logo/b256.ico" type="image/x-icon">
    <link rel="icon" href="ui/logo/b32.ico" sizes="32x32">
    <link rel="icon" href="ui/logo/b48.ico" sizes="48x48">
    <link rel="icon" href="ui/logo/b96.ico" sizes="96x96">
    <link rel="icon" href="ui/logo/b256.ico" sizes="144x144">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.min.css">
    <link rel="stylesheet" href="dist/css/ab/styledoo.css">

    <title>THPOS</title>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-QRFPMKNEVL"></script>
    <script src="dist/js/scriptskk.js"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-QRFPMKNEVL');
    </script>
</head>
<style>
    @font-face {
        font-family: "OSbattambang";
        src: url(fone/KhmerOSbattambang.ttf)format("truetype");
    }

    .fonkh {
        font-family: "OSbattambang";
    }
</style>

<body>

    <nav>
        <div class="nav-logo">
            <a href="#">
                <img src="resources/images/logo/ff.png">
            </a>
        </div>

        <ul class="nav-links">
            <li class="link"><a href="#">Home</a></li>
            <li id="link1" class="link"><a href="#">Service</a></li>
            <li id="link2" class="link"><a href="#">Price</a></li>
            <li id="link3" class="link"><a href="#">About</a></li>
        </ul>
        <!-- <button class="btn">Hire Me</button> -->
    </nav>


    <header class="container">
        <div class="content">
            <span class="blur"></span>
            <span class="blur"></span>
            <h4>CREATE YOUR SITE LIKE A PRO</h4>
            <H1>Welcome to <span>TH POS</span>, <p class="fonkh">ប្រព័ន្ធគ្រប់គ្រងការលក់</p>
            </H1>
            <p>
                ...
            </p>
            <button id="link8" class="btn">Get Started</button>
        </div>
        <div class="image">
            <img src="resources/images/header.png">
        </div>
    </header>

    <section class="container">
        <h2 class="header">SERVICE</h2>
        <div class="features">
            <div class="card">
                <span><i class="ri-restaurant-fill"></i></span>
                <h4>Free Tier</h4>
                <p class="fonkh">
                    ប្រព័ន្ធគ្រប់គ្រងភោជនីយដ្ឋាន។
                    Restaurant Management System.
                </p>
                <a href="restaurant/api">Login Now <i class="ri-arrow-right-line"></i></a>
            </div>
            <div class="card">
                <span><i class="ri-cup-fill"></i></span>
                <h4 class="fonkh">Free Tier</h4>
                <p class="fonkh">
                    ប្រព័ន្ធគ្រប់គ្រងហាងកាហ្វេ។
                    Coffee Shop Management System.

                </p>
                <a href="coffee/">Login Now <i class="ri-arrow-right-line"></i></a>
            </div>
            <div class="card">
                <span><i class="ri-store-line"></i></span>
                <h4 class="fonkh">Free Tier</h4>
                <p class="fonkh">
                    ប្រព័ន្ធគ្រប់គ្រងម៉ាត។ <br>
                    Mart Shop Management System.

                </p>
                <a href="posbarcode/">Login Now <i class="ri-arrow-right-line"></i></a>
            </div>
            <div class="card">
                <span><i class="x-mdi-application"></i></span>
                <h4 class="fonkh">Free Tier</h4>
                <p class="fonkh">
                    ប្រព័ន្ធគ្រប់គ្រងទឹក។ <br>
                    Water management system.

                </p>
                <a href="Water_management_system/">Login Now <i class="ri-arrow-right-line"></i></a>
            </div>

        </div>
    </section>
    <?php


    ?>

    <section class="container">
        <h2 class="header">PROJECT PRICING PLANS</h2>
        <p class="sub-header">
            .
        </p>
        <div class="pricing">
            <?php service_list_dom() ?>


        </div>
    </section>

    <footer class="container">
        <span class="blur"></span>
        <span class="blur"></span>
        <div class="column">
            <div class="logo">
                <img src="resources/images/logo/logo1.png">
            </div>
            <p>
                The company itself is a very successful company.
            </p>
            <div class="socials">
                <!-- <a href="#"><i class="ri-youtube-line"></i></a> -->
                <a href="https://web.facebook.com/TonghengCoding/" target="_blank"><i class="ri-facebook-circle-fill"></i></a>
                <a href="https://t.me/THPOS_store" target="_blank"><i class="ri-telegram-fill"></i></a>
            </div>
        </div>
        <!-- <div class="column">
            <h4>Company</h4>
            <a href="#">Business</a>
            <a href="#">Partnership</a>
            <a href="#">Network</a>
        </div>
        <div class="column">
            <h4>About Us</h4>
            <a href="#">Blogs</a>
            <a href="#">Channels</a>
            <a href="#">Sponsors</a>
        </div> -->
        <!-- <div class="column">
            <h4>Contact</h4>
            <p><i class="ri-phone-fill"></i> 071 89 89 726</p>
        </div> -->
    </footer>

    <div class="copyright">
        Copyright © 2024 THPOS.store. All Rights Reserved.
    </div>

    <script src="dist/js/scriptskk.js"></script>
    <script src="dist/css/ab/scriptdol.js"></script>
    
</body>

</html>