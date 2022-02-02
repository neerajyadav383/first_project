<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->

<!-- Mirrored from html.dynamiclayers.net/te/organze/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 25 Oct 2021 08:44:25 GMT -->
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="E-Life">
        <meta name="author" content="E-Life">

        <title>E-Life</title>

		<link rel="shortcut icon" type="image/x-icon" href="{{asset('assetsweb/img/life.png')}}">

		<!-- Font Awesome CSS -->
        <link rel="stylesheet" href="{{asset('assetsweb/css/fontawesome.min.css')}}">

        <!-- Themify Icons CSS -->
        <link rel="stylesheet" href="{{asset('assetsweb/css/themify-icons.css')}}">
        <!-- Elegant Icons CSS -->
        <link rel="stylesheet" href="{{asset('assetsweb/css/elegant-font-icons.css')}}">
        <!-- Flat Icons CSS -->
        <link rel="stylesheet" href="{{asset('assetsweb/css/food-icon.css')}}">
        <!-- animate CSS -->
        <link rel="stylesheet" href="{{asset('assetsweb/css/animate.min.css')}}">
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{asset('assetsweb/css/bootstrap.min.css')}}">
        <!-- Slicknav CSS -->
        <link rel="stylesheet" href="{{asset('assetsweb/css/slicknav.min.css')}}">
        <!--Slick Slider-->
        <link rel="stylesheet" href="{{asset('assetsweb/css/slick.css')}}">
        <!--Slider CSS-->
        <link rel="stylesheet" href="{{asset('assetsweb/css/slider.css')}}">
        <!-- Venobox CSS -->
        <link rel="stylesheet" href="{{asset('assetsweb/css/venobox/venobox.css')}}">
		<!-- OWL-Carousel CSS -->
        <link rel="stylesheet" href="{{asset('assetsweb/css/owl.carousel.min.css')}}">
		<!-- Main CSS -->
        <link rel="stylesheet" href="{{asset('assetsweb/css/main.css')}}">
		<!-- Responsive CSS -->
        <link rel="stylesheet" href="{{asset('assetsweb/css/responsive.css')}}">

        <script src="{{asset('assetsweb/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js')}}"></script>
    </head>
    <body data-spy="scroll" data-target="#navmenu" data-offset="70">
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <div class="site-preloader-wrap">
            <div class="spinner"></div>
        </div><!-- Preloader -->

        <header id="header" class="header-section">
            <div class="container">
                <nav class="navbar ">
                    <a href="index.html" class="navbar-brand">
                        <img class="logo-dark" src="{{asset('assetsweb/img/life.png')}}" alt="Saasbiz"style="height:87px;">
                    </a>
                    <div class="d-flex menu-wrap">
                       <div id="mainmenu" class="mainmenu">
                           <ul class="nav">
                               <li><a data-scroll class="nav-link active" href="{{url('/')}}">Home<span class="sr-only">(current)</span></a>
                                </li>
                                <li><a href="{{url('/about')}}">About Us</a></li>
                                <!-- <li><a href="services.html">Services</a></li> -->
                                <li><a href="{{url('/product')}}">Product</a>
                                    <!-- <ul>
                                       <li><a href="shop.html">Shop Page</a></li>
                                       <li><a href="product-details.html">Product Details</a></li>
                                    </ul> -->
                                </li>
                                <li><a href="{{url('/gallery')}}">Gallery</a>
                                    <!-- <ul>
                                       <li><a href="gallery.html">Gallery</a></li>
                                       <li><a href="team.html">Our Team</a></li>
                                       <li><a href="testimonial.html">Testimonial</a></li>
                                       <li><a href="faq.html">FAQ's</a></li>
                                       <li><a href="404.html">404 Error</a></li>
                                    </ul> -->
                                </li>
                                <!-- <li><a href="#">Blog</a>
                                    <ul>
                                       <li><a href="blog-grid.html">Blog Grid</a></li>
                                       <li><a href="blog-classic.html">Blog Classic</a></li>
                                       <li><a href="blog-single.html">Blog Single</a></li>
                                    </ul>
                                </li> -->
                                <li><a href="{{url('/contact')}}">Contact</a></li>
                                
                                <li><a class="btn" href="{{url('/login')}}">LOGIN</a></li>
                                <li><a class="btn" href="{{url('/register')}}">REGISTER</a></li>
                            </ul>
                       </div>
                        {{-- <div class="header-right">
                             <a class="menu-btn btn-white" href="{{ url('/login') }}">LOGIN</a>
                        </div>
                        &nbsp
                        <div class="header-right">
                             <a class="menu-btn btn-white" href="{{ url('/register') }}">REGISTER</a>
                        </div> --}}
                    </div>
                </nav>
            </div>
		</header> <!--.header-section -->


@yield('content')

<footer class="widget-section">
           <div class="widget-wrap padding">
               <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-sm-6 sm-padding">
                            <div class="widget-content">
                                <a href="#"><img src="{{asset('assetsweb/img/life.png')}}" alt="brand" style="margin-top:-45px;"></a>

                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 sm-padding">
                            <div class="widget-content footer">
                                <h4>Company</h4>
                                <ul class="widget-links">
                                    <li><a href="{{url('/about')}}">About Us</a></li>
                                    <li><a href="#">Our Services</a></li>
                                    <li><a href="#">Clients Reviews</a></li>
                                    <li><a href="{url('/contact')}}">Contact Us</a></li>
                                    <li><a href="{{url('/login')}}">Login</a></li>
                                    <li><a href="{{ url('/register') }}">Register</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 sm-padding">
                            <div class="widget-content footer">
                                <h4>SHOP</h4>
                                <p>Shop No.F-8, ADARSH ESTATE NARODA,NARODA, AHEMDABAD-382330</p>
                                <span>info@elife.in</span>
                                <span>1234567890</span>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 sm-padding">
                            <div class="widget-content footer">
                                <h4>Newslatter Subscription</h4>
                                <p>Subscribe and get 10% off from our <br>architecture company.</p>
                                <div class="subscribe-box clearfix">
                                    <div class="subscribe-form-wrap">
                                        <form action="#" class="subscribe-form">
                                            <input type="email" name="email" id="subs-email" class="form-input" placeholder="Enter Your Email Address...">
                                            <button type="submit" class="submit-btn">Subscribe</button>
                                            <div id="subscribe-result">
                                                <p class="subscription-success"></p>
                                                <p class="subscription-error"></p>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-section align-center">
                <div class="container">
                    <p>&copy; </p>
                </div>
		    </div><!-- /.footer-section -->
        </footer><!--/.widget-section-->

		<a data-scroll href="#header" id="scroll-to-top"><i class="ti-arrow-up"></i></a>

		<!-- jQuery Lib -->
		<script src="{{asset('assetsweb/js/vendor/jquery-1.12.4.min.js')}}"></script>
        <script src="{{asset('assetsweb/js/vendor/bootstrap.min.js')}}"></script>
		<script src="{{asset('assetsweb/js/vendor/tether.min.js')}}"></script>
		<script src="{{asset('assetsweb/js/vendor/jquery.slicknav.min.js')}}"></script>
		<script src="{{asset('assetsweb/js/vendor/owl.carousel.min.js')}}"></script>
		<script src="{{asset('assetsweb/js/vendor/smooth-scroll.min.js')}}"></script>
		<script src="{{asset('assetsweb/js/vendor/jquery.isotope.v3.0.2.js')}}"></script>
		<script src="{{asset('assetsweb/js/vendor/imagesloaded.pkgd.min.js')}}"></script>
        <script src="{{asset('assetsweb/js/vendor/venobox.min.js')}}"></script>
		<script src="{{asset('assetsweb/js/vendor/jquery.ajaxchimp.min.js')}}"></script>
		<script src="{{asset('assetsweb/js/vendor/slick.min.js')}}"></script>
		<script src="{{asset('assetsweb/js/vendor/wow.min.js')}}"></script>
		<script src="{{asset('assetsweb/js/main.js')}}"></script>

    </body>

</html>
