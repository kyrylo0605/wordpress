<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<title><?php bloginfo('name') ?></title>

	<!-- CSS -->
	<link href="wp-content/themes/resume-site/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="wp-content/themes/resume-site/assets/css/font-awesome.min.css" rel="stylesheet" media="screen">
	<link href="wp-content/themes/resume-site/assets/css/simple-line-icons.css" rel="stylesheet" media="screen">
	<link href="wp-content/themes/resume-site/assets/css/animate.css" rel="stylesheet">

	<!-- Custom styles CSS-->
	<link href="<?php bloginfo('stylesheet_url') ?>" rel="stylesheet" media="screen"> 

    <script src="wp-content/themes/resume-site/assets/js/modernizr.custom.js"></script>


</head>
<body>

	<!-- Preloader -->

	<div id="preloader">
		<div id="status"></div>
	</div>

	<!-- Home start -->

	<section id="home" class="pfblock-image screen-height">
        <div class="home-overlay"></div>
		<div class="intro">
			<div class="start">Доброго времени суток, меня зовут Гребенник Кирилл, и я</div>
			<h1>FRONT-END разработчик, фрилансер</h1>
			<div class="start">Создание современных отзывчивых WEB сайтов</div>
		</div>

        <a href="#education">
		<div class="scroll-down">
            <span>
                <i class="fa fa-angle-down fa-2x"></i>
            </span>
		</div>
        </a>

	</section> <!--ãîòîâî>

	<!-- Home end -->

	<!-- Navigation start -->

	<header class="header">

		<nav class="navbar navbar-custom" role="navigation">

			<div class="container">

				<div class="navbar-header">
					<?php get_sidebar() ?>
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#custom-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="index.html">Гребенник Кирилл</a>
				</div>

				<div class="collapse navbar-collapse" id="custom-collapse">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="#home">Главная</a></li>
						<li><a href="#education">Образование</a></li>
                        <li><a href="#skills">Навыки</a></li>
                        <li><a href="#qualities">Личные качества</a></li>
						<li><a href="#portfolio">Портфолио</a></li>
						<li><a href="#contact">Контакты</a></li>
					</ul>
				</div>

			</div><!-- .container -->

		</nav>

	</header> <!--ãîòîâî>

	<!-- Navigation end -->

    <!-- Services start -->

	<section id="education" class="pfblock pfblock-gray">
		<div class="container">
			<div class="row">

					<div class="pfblock-header wow fadeInUp">
						<h2 class="pfblock-title">Где я учился?</h2>
						<div class="pfblock-line"></div>
						<div class="pfblock-subtitle">
							<p>Основное образование:</p>
							<p>2004-2010 гг. Харьковский национальный университет "ХПИ"</p>
							<p>Механико-технологический факультет</p>
							<p>Специальность: Оборудование для обработки металлов давлением</p>
							<p>Диплом магистра</p>
						</div>
					</div>

			</div>


			</div><!-- .row -->
		</div><!-- .container -->
	</section><!--ãîòîâî>

	<!-- Services end -->

	<!-- Portfolio start -->


    <section class="pfblock" id="skills">

			<div class="container">

				<div class="row skills">

					<div class="row">

                        <div class="col-sm-6 col-sm-offset-3">

                            <div class="pfblock-header wow fadeInUp">
                                <h2 class="pfblock-title">Мои навыки</h2>
                                <div class="pfblock-line"></div>
                            </div>

                        </div>

                    </div><!-- .row -->

					<div class="col-sm-6 col-md-3 text-center">
						<span data-percent="80" class="chart easyPieChart" style="width: 140px; height: 140px; line-height: 140px;">
                            <span class="percent">80</span>
                        </span>
						<h3 class="text-center">Adobe Photoshop</h3>
					</div>
					<div class="col-sm-6 col-md-3 text-center">
						<span data-percent="90" class="chart easyPieChart" style="width: 140px; height: 140px; line-height: 140px;">
                            <span class="percent">90</span>
                        </span>
						<h3 class="text-center">HTML, CSS, JavaScript, jQuery</h3>
					</div>
					<div class="col-sm-6 col-md-3 text-center">
						<span data-percent="85" class="chart easyPieChart" style="width: 140px; height: 140px; line-height: 140px;">
                            <span class="percent">85</span>
                        </span>
						<h3 class="text-center">WEB Storm, Sublime Text, Notepad++</h3>
					</div>
					<div class="col-sm-6 col-md-3 text-center">
						<span data-percent="95" class="chart easyPieChart" style="width: 140px; height: 140px; line-height: 140px;">
                            <span class="percent">95</span>
                        </span>
						<h3 class="text-center">Верстка по стандартам W3C (валидный код), кроссбраузерность</h3>
					</div>

				</div><!--End row -->

			</div>

    </section> <!--ãîòîâî>

    <!-- Skills end -->

	<!-- CallToAction start -->

	<section id="qualities" class="pfblock pfblock-gray">
		<div class="container">
			<div class="row">

					<div class="pfblock-header wow fadeInUp">
						<h2 class="pfblock-title">Личные качества</h2>
						<div class="pfblock-line"></div>
						<div class="pfblock-subtitle">
							<ul>
								<p>Целеустремленность</p>
								<p>Активность</p>
								<p>Честноть</p>
								<p>Уравновешенность</p>
								<p>Усидчивость</p>
								<p>Исполнительность</p>
								<p>Организованность</p>
								<p>Коммуникабельность</p>
							</ul>
						</div>
					</div>

			</div><!-- .row -->

		</div><!-- .contaier -->

	</section>

	<!-- Portfolio end -->

	<!-- Skills start -->

	<section id="portfolio" class="pfblock">
		<div class="container">
			<div class="row">

				<div class="col-sm-6 col-sm-offset-3">

					<div class="pfblock-header wow fadeInUp">
						<h2 class="pfblock-title">Мои работы</h2>
						<div class="pfblock-line"></div>
						<div class="pfblock-subtitle">
							Здесь собрано самое лучшее
						</div>
					</div>

				</div>

			</div><!-- .row -->


			<div class="row">

				<div class="col-xs-12 col-sm-4 col-md-4">

					<div class="grid wow zoomIn">
						<figure class="effect-bubba">
							<img src="wp-content/themes/resume-site/assets/images/item-1.jpg" alt="img01"/>
							<figcaption>
								<h2>Crazy <span>Shark</span></h2>
								<p>Lily likes to play with crayons and pencils</p>
							</figcaption>
						</figure>
					</div>

				</div>

				<div class="col-xs-12 col-sm-4 col-md-4">

					<div class="grid wow zoomIn">
						<figure class="effect-bubba">
							<img src="wp-content/themes/resume-site/assets/images/item-2.jpg" alt="img02"/>
							<figcaption>
								<h2>Funny <span>Tortoise</span></h2>
								<p>Lily likes to play with crayons and pencils</p>
							</figcaption>
						</figure>
					</div>

				</div>

				<div class="col-xs-12 col-sm-4 col-md-4">

					<div class="grid wow zoomIn">
						<figure class="effect-bubba">
							<img src="wp-content/themes/resume-site/assets/images/item-3.jpg" alt="img03"/>
							<figcaption>
								<h2>The <span>Hat</span></h2>
								<p>Lily likes to play with crayons and pencils</p>
							</figcaption>
						</figure>
					</div>

				</div>

			</div>

		</div><!-- .contaier -->

	</section>

	<!-- Portfolio end -->

	<!-- Skills start -->

	<section id="contact" class="pfblock pfblock-gray">
		<div class="container">
			<div class="row">

					<div class="pfblock-header">
						<h2 class="pfblock-title">Мои координаты</h2>
						<div class="pfblock-line"></div>
						<div class="pfblock-subtitle">
							<p>Адрес: г.Харьков, Украина</p>
							<p>Тел: +38 (097) 110-06-81</p>
							<p>Skype: nana_514</p>
							<p>Email: kiril_1986@gmail.com</p>
						</div>
					</div>

			</div><!-- .row -->
		</div><!-- .container -->
	</section>

	<!-- Contact end -->

	<!-- Footer start -->

	<footer id="footer">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<p class="heart">
                        Сделано <span class="fa fa-heart fa-2x animated pulse"></span> в Украине
                    </p>
                    <p class="copyright">
                        © 2018 Гребенник Кирилл
					</p>
				</div>
			</div><!-- .row -->
		</div><!-- .container -->
	</footer>

	<!-- Footer end -->

	<!-- Scroll to top -->

	<div class="scroll-up">
		<a href="#home"><i class="fa fa-angle-up"></i></a>
	</div>

    <!-- Scroll to top end-->

	<!-- Javascript files -->

	<script src="wp-content/themes/resume-site/assets/js/jquery-1.11.1.js"></script>
	<script src="wp-content/themes/resume-site/assets/bootstrap/js/bootstrap.min.js"></script>
	<script src="wp-content/themes/resume-site/assets/js/jquery.parallax-1.1.3.js"></script>
	<script src="wp-content/themes/resume-site/assets/js/imagesloaded.pkgd.js"></script>
	<script src="wp-content/themes/resume-site/assets/js/jquery.sticky.js"></script>
	<script src="wp-content/themes/resume-site/assets/js/smoothscroll.js"></script>
	<script src="wp-content/themes/resume-site/assets/js/wow.min.js"></script>
    <script src="wp-content/themes/resume-site/assets/js/jquery.easypiechart.js"></script>
    <script src="wp-content/themes/resume-site/assets/js/waypoints.min.js"></script>
    <script src="wp-content/themes/resume-site/assets/js/jquery.cbpQTRotator.js"></script>
	<script src="wp-content/themes/resume-site/assets/js/custom.js"></script>

	

</body>
</html>
