<?php
//include function file
include('functions.php');
$_SESSION['errors'] = array();
$_SESSION['form'] = $_POST;
/*  validate each field
    [first_name] => Muhindo
    [last_name] => Mubaraka
    [email] => mubahood360@gmail.com
    [phone] => 0783204665
    [description1] => charity
    [amount] => 1000000
    [paymentmode] => UGAIRTELMO
    [donate] => Process Payment
*/
if ($_POST['first_name'] == null || strlen($_POST['first_name']) < 2) {
    $_SESSION['errors']['first_name'] = "Please enter your first name";
}
if ($_POST['last_name'] == null || strlen($_POST['last_name']) < 2) {
    $_SESSION['errors']['last_name'] = "Please enter your last name";
}
if ($_POST['email'] == null || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $_SESSION['errors']['email'] = "Please enter a valid email address";
}
if ($_POST['phone'] == null || strlen($_POST['phone']) < 10) {
    $_SESSION['errors']['phone'] = "Please enter your phone number";
}
if (empty($_POST['amount'])) {
    $_SESSION['errors']['amount'] = "Please enter an amount";
}
$amount = $_POST['amount'];
//chec if is greater than 500
if ($amount < 500) {
    $_SESSION['errors']['amount'] = "Please enter an amount greater than 500";
}
if (isset($_SESSION['errors'])) {
    if (count($_SESSION['errors']) > 0) {
        header("location:donate.php");
        die();
    }
}

$phone = prepare_phone_number($_POST['phone']);
if (!phone_number_is_valid($phone)) {
    $_SESSION['errors']['phone'] = "Please enter a valid ugandan phone number";
    header("location:donate.php");
}

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$description1 = $_POST['description1'];
$amount = $_POST['amount'];
$paymentmode = $_POST['paymentmode'];
$donate = $_POST['donate'];
$_SESSION['form']['phone'] = $phone;


?>
<!doctype html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0; maximum-scale=1">
    <meta name="keywords" content="Zakat and Waqf Uganda, Zakat, Waqf, Uganda, Muslims in Uganda, Muslims, Moslems, Africa, Waqf, Uganda, Islam, Islamic, Sadaq, Sadaaka, Zakah,Charity,salam,salamcharity,salam Charity  " />

    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.jpg">
    <title>SalamCharity</title>
    <!-- Reset CSS -->
    <link href="css/reset.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="css/fonts.css" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="assets/select2/css/select2.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- Magnific Popup -->
    <link href="assets/magnific-popup/css/magnific-popup.css" rel="stylesheet">
    <!-- Iconmoon -->
    <link href="assets/iconmoon/css/iconmoon.css" rel="stylesheet">
    <!-- Owl Carousel -->
    <link href="assets/owl-carousel/css/owl.carousel.min.css" rel="stylesheet">
    <!-- Animate -->
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom Style -->
    <link href="css/custom.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="js/html5shiv.min.js"></script>
        <script src="js/respond.min.js"></script>
        <![endif]-->
</head>

<body>

    <!-- Start Preloader -->
    <div id="loading">
        <div class="element">
            <div class="sk-folding-cube">
                <div class="sk-cube1 sk-cube"></div>
                <div class="sk-cube2 sk-cube"></div>
                <div class="sk-cube4 sk-cube"></div>
                <div class="sk-cube3 sk-cube"></div>
            </div>
        </div>
    </div>
    <!-- End Preloader -->

    <!-- Start Header -->
    <header>
        <!-- Start Header top Bar -->
        <div class="header-top">
            <div class="container clearfix">
                <ul class="follow-us hidden-xs">
                    <li><a href="https://twitter.com/charitysalam?lang=en"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                    <li><a href="https://www.facebook.com/pages/category/Charity-Organization/Salam-Charity-828122000855141/"><i class="fa fa-facebook-official" aria-hidden="true"></i></a></li>
                    <li><a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                    <li><a href="#"><i class="fa fa-youtube-play" aria-hidden="true"></i></a></li>
                    <li><a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                </ul>
                <div class="right-block clearfix">
                    <ul class="top-nav hidden-xs">
                        <li><a href="#">Register</a></li>
                        <li><a href="#">Apply Online</a></li>
                        <li><a href="#">Webmail</a></li>
                        <li><a href="#">FAQs</a></li>
                    </ul>
                    <div class="lang-wrapper">
                        <!--<div class="select-lang">
                                <select id="currency_select">
                                    <option value="usd">USD</option>
                                    <option value="aud">AUD</option>
                                    <option value="gbp">GBP</option>
                                </select>
                            </div>-->
                        <div class="select-lang2">
                            <select class="custom_select">
                                <option value="en">English</option>
                                <option value="fr">Arabic</option>
                                <option value="fr">French</option>
                                <option value="de">German</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Header top Bar -->
        <!-- Start Header Middle -->
        <div class="container header-middle">
            <div class="row"> <span class="col-xs-6 col-sm-3"><a href="/"><img src="images/logo.png" class="img-responsive" alt=""></a></span>
                <div class="col-xs-6 col-sm-3"></div>
                <div class="col-xs-6 col-sm-9">
                    <div class="contact clearfix">
                        <ul class="hidden-xs">
                            <li> <span>Email</span> <a href="mailto:salamcf@salamcharity.ug">salamcf@salamcharity.ug</a> </li>
                            <li> <span>TEL</span> +256 786 230 711 </li>
                        </ul>
                        <a href="#" class="login">Member Login <span class="icon-more-icon"></span></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Header Middle -->
        <!-- Start Navigation -->
        <nav class="navbar navbar-inverse">
            <div class="container">
                <div class="navbar-header">
                    <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                </div>
                <div class="navbar-collapse collapse" id="navbar">
                    <ul class="nav navbar-nav">
                        <li> <a href="index-3.html">Home</a></li>

                        <li> <a href="about.html">About</a></li>

                        <li class="dropdown"> <a data-toggle="dropdown" href="#">Projects <i class="fa fa-angle-down" aria-hidden="true"></i></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">Ongoing Projects</a></li>
                                <li><a href="#">Finished Projects</a></li>

                            </ul>
                        </li>
                        <li> <a href="gallery.html">Gallery</a></li>
                        <li class="dropdown"> <a data-toggle="dropdown" href="#">Resources <i class="fa fa-angle-down" aria-hidden="true"></i></a>
                            <ul class="dropdown-menu">

                                <li><a href="docs/SALAAMREPORT.pdf">Charity Profile</a></li>
                                <li><a href="%40.html">Activity Report</a></li>

                            </ul>
                        </li>
                        <li> <a href="zakah.html">Zakah & Waqf</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- End Navigation -->
    </header>
    <!-- end Header -->

    <div class="container mb-0 pb-0" style="margin-top: 3rem; margin-bottom: 1rem!important; padding-bottom: 2rem">
        <div class="row">
            <div class="col-sm-12 ">
                <h1 class="text-center ">Confirm Payment Information</h1>
            </div>
        </div>
    </div>
    <!-- End Banner -->
    <!-- Start About -->
    <section class="about inner padding-lg " style="margin-top: 0rem!important; padding-top: 0rem!important">
        <div class="container ">
            <div class="row">
                <div class="col-md-12 left-block">
                    <div class="cnt-block">
                        <form action="donate-pay.php" method="POST" name="donateform">

                            <div class="form-row">

                                <p style="padding: 0rem; margin: 0rem"><b>First Name:</b> <?php echo $first_name; ?></p>
                                <p style="padding: 0rem; margin: 0rem"><b>Last Name:</b> <?php echo $last_name; ?></p>
                                <p style="padding: 0rem; margin: 0rem"><b>Email:</b> <?php echo $email; ?></p>
                                <p style="padding: 0rem; margin: 0rem"><b>Phone:</b> <?php echo $phone; ?></p>
                                <p style="padding: 0rem; margin: 0rem"><b>Amount:</b> <?php echo $amount; ?></p>

                                <div class="form-row mt-2">
                                    <br>
                                    <div class="form-group col-12 mt-3">
                                        <input type="submit" name="donate" value="Pay Now" class="btn btn-primary btn-block  btn-lg w-100" data-loading-text="Loading...">
                                    </div>
                                    <p class="text-center">
                                        <a href="donate.php" class="">Edit Information</a>
                                    </p>

                                </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End About -->



    <!-- Start Testimonial -->
    <!--<section class="testimonial padding-lg">
            <div class="container">
                <div class="wrapper">
                    <h2>Alumini Testimonials</h2>
                    <ul class="testimonial-slide">
                        <li>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley...<a href="#">Read more</a></p>
                            <span>Thomas, <span>London</span></span> </li>
                        <li>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley...<a href="#">Read more</a></p>
                            <span>Thomas, <span>London</span></span> </li>
                        <li>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley...<a href="#">Read more</a></p>
                            <span>Thomas, <span>London</span></span> </li>
                    </ul>
                    <div id="bx-pager"> <a data-slide-index="0" href="#"><img src="images/testimonial-thumb1.jpg" class="img-circle" alt=""/></a> <a data-slide-index="1" href="#"><img src="images/testimonial-thumb2.jpg" class="img-circle" alt="" /></a> <a data-slide-index="2" href="#"><img src="images/testimonial-thumb3.jpg" class="img-circle" alt="" /></a> </div>
                </div>
            </div>
        </section>-->
    <!-- End Testimonial -->

    <!-- Start Footer -->
    <footer class="footer">
        <!-- Start Footer Top -->
        <footer class="footer">
            <!-- Start Footer Top -->
            <div class="container">
                <div class="row row1">
                    <div class="col-sm-9 clearfix">
                        <div class="foot-nav">
                            <h3>About US</h3>
                            <ul>
                                <li><a href="#">Who we are</a></li>
                                <li><a href="#">Charity Projects</a></li>
                                <li><a href="#">Zakah Collection</a></li>
                                <li><a href="#">Waqf</a></li>

                            </ul>
                        </div>
                        <div class="foot-nav">
                            <h3>Reports</h3>
                            <ul>
                                <li><a href="#">Annual Plane</a></li>
                                <li><a href="#">Project Reports</a></li>
                                <li><a href="#">Annual Reports</a></li>
                                <li><a href="#">Financial Reports</a></li>

                            </ul>
                        </div>
                        <div class="foot-nav">
                            <h3>Why Salam Charity</h3>
                            <ul>
                                <li><a href="#">Our Core Values</a></li>
                                <li><a href="#">Completed Projects</a></li>
                                <li><a href="#">Ongoing Projects</a></li>
                                <li><a href="#">Future Projects </a></li>

                            </ul>
                        </div>
                        <!-- <div class="foot-nav">
                            <h3>Learning Experience</h3>
                            <ul>
                                <li><a href="#">Course Preparations</a></li>
                                <li><a href="#">Guided lessons</a></li>
                                <li><a href="#">Interactive Practice</a></li>
                                <li><a href="#">Virtual Classroom</a></li>
                                <li><a href="#">Peer Learning</a></li>
                            </ul>
                        </div>-->
                    </div>
                    <div class="col-sm-3">
                        <div class="footer-logo hidden-xs"><a href="/"><img src="images/footer-logo.png" class="img-responsive" alt=""></a></div>
                        <p>© 2019 <span>SalamCharity</span>. All rights reserved</p>
                        <ul class="terms clearfix">
                            <li><a href="#">TERMS OF USE</a></li>
                            <li><a href="#">PRIVACY POLICY</a></li>
                            <li><a href="#">SITEMAP</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- End Footer Top -->
            <!-- Start Footer Bottom -->
            <div class="bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="connect-us">
                                <h3>Connect with Us</h3>
                                <ul class="follow-us clearfix">
                                    <li><a href="https://www.facebook.com/pages/category/Charity-Organization/Salam-Charity-828122000855141/"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                    <li><a href="https://twitter.com/charitysalam?lang=en"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>

                                    <li><a href="#"><i class="fa fa-youtube-play" aria-hidden="true"></i></a></li>

                                </ul>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="subscribe">
                                <h3>Subscribe with Us</h3>
                                <!-- Begin MailChimp Signup Form -->
                                <div id="mc_embed_signup">
                                    <form action="/" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                                        <div id="mc_embed_signup_scroll">
                                            <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="enter your email address" required>
                                            <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                                            <div style="position: absolute; left: -5000px;" aria-hidden="true">
                                                <input type="text" name="b_cd5f66d2922f9e808f57e7d42_ec6767feee" tabindex="-1" value="">
                                            </div>
                                            <div class="clear">
                                                <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!--End mc_embed_signup-->
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <!-- <div class="instagram">
                                <h3>@INSTAGRAM</h3>
                                <ul class="clearfix">
                                    <li><a href="#"><figure><img src="images/insta-img1.jpg" class="img-responsive" alt=""></figure></a></li>
                                    <li><a href="#"><figure><img src="images/insta-img2.jpg" class="img-responsive" alt=""></figure></a></li>
                                    <li><a href="#"><figure><img src="images/insta-img3.jpg" class="img-responsive" alt=""></figure></a></li>
                                    <li><a href="#"><figure><img src="images/insta-img4.jpg" class="img-responsive" alt=""></figure></a></li>
                                    <li><a href="#"><figure><img src="images/insta-img5.jpg" class="img-responsive" alt=""></figure></a></li>
                                    <li><a href="#"><figure><img src="images/insta-img6.jpg" class="img-responsive" alt=""></figure></a></li>
                                </ul>
                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Footer Bottom -->
        </footer>
        <!-- End Footer -->

        <!-- Scroll to top -->
        <a href="#" class="scroll-top"><i class="fa fa-chevron-up" aria-hidden="true"></i></a>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="js/jquery.min.js"></script>
        <!-- Bootsrap JS -->
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <!-- Select2 JS -->
        <script src="assets/select2/js/select2.min.js"></script>
        <!-- Match Height JS -->
        <script src="assets/matchHeight/js/matchHeight-min.js"></script>
        <!-- Bxslider JS -->
        <script src="assets/bxslider/js/bxslider.min.js"></script>
        <!-- Waypoints JS -->
        <script src="assets/waypoints/js/waypoints.min.js"></script>
        <!-- Counter Up JS -->
        <script src="assets/counterup/js/counterup.min.js"></script>
        <!-- Owl Carousal JS -->
        <script src="assets/owl-carousel/js/owl.carousel.min.js"></script>
        <!-- Magnific Popup JS -->
        <script src="assets/magnific-popup/js/magnific-popup.min.js"></script>
        <!-- Custom JS -->
        <script src="js/custom.js"></script>





</body>

</html>