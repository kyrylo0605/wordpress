<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package lesson
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
<head>
	<meta charset="UTF-8">
	<title>BlueProj</title>
	<link rel="stylesheet" href="css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Exo+2:400,500,600,700,800" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<div class="menu-overlay"></div>
	<div class="smallopenmenu">
		<ul>
			<li><a href="about.html">О компании</a></li>
					<li><a href="tovar.html">Продукция</a></li>
					<li><a href="projects.html">Наши проекты</a></li>
					<li><a href="price.html">Прайсы</a></li>
					<li><a href="productiongroup.html">Документация</a></li>
					<li><a href="">Вакансии</a></li>
					<li><a href="">Контакты</a></li>
		</ul>
	</div>
	<div class="topSmallPoint">
		<div class="container">
			<div class="contactBlock">
				<div class="preCalculate">
						<img src="img/PlusBl.png" alt="PlusBl">
					<a href="">
						оставить  заявку на просчет
					</a>
				</div>
				<div class="phoneCalc">
					<a href=""><img src="img/phone.png" alt="phone"> +7 (961) 000 00 00</a>	
				</div>
				<div class="phoneCalc">
					<p><img src="img/geo.png" alt="geo"> Москва, ул. Тестовая</p>
				</div>
			</div>
		</div>
	</div>
	<div class="mainHeader">
		<div class="container">
			<div class="wrappMainPLogo">
				<div class="logo-wrapper">
				<img src="img/logo.png" alt="logo">
			</div>
			<div class="wrapperMainMenu">
				<ul>
					<li><a href="about.html">О компании</a></li>
					<li><a href="">Продукция</a></li>
					<li><a href="projects.html">Наши проекты</a></li>
					<li><a href="price.html">Прайсы</a></li>
					<li><a href="">Документация</a></li>
					<li><a href="">Вакансии</a></li>
					<li><a href="">Контакты</a></li>
				</ul>
			</div>
			<div class="burgerMenu">
				<img src="img/burger.png" alt="burger">
			</div>
			</div>
		</div>
	</div>
	<div class="mainBlock">
		<div class="container">
			<div class="outerA">	
			<div class="lefta">
				<div class="whiteArrow">	
				<img src="img/whitea.png" alt="arrow"></div>
				<h1>Оказываем полный спектр услуг</h1>
				<div class="whiteArrow bottomArrowMain">	
				<img src="img/whitea.png" alt="arrow"></div>
			</div>
			<div class="righta">
				<div class="rightaImg">	
					<img src="img/glue.png" alt="glue">
				</div>
				<p>1 <span>Проектирование</span></p>
				<p>2 <span>Производство и поставка оборудования</span></p>
				<p>3 <span>Строительно-монтажные работы</span></p>
			</div></div>
		</div>
	</div>
	<div class="smallBlocks">	
		<div class="container">	
			<div class="outerSmallBlock">	
				<div class="innerSmBlock">	
					<img src="img/small1.png" alt="small">
					<p>Собственное <br> производство</p>
				</div>
				<div class="innerSmBlock">	
					<img src="img/small2.png" alt="small">
					<p>Современное <br> оборудование</p>
				</div>
				<div class="innerSmBlock">	
					<img src="img/small3.png" alt="small">
					<p>Бесплатный просчёт</p>
				</div>
				<div class="innerSmBlock">	
					<img src="img/small4.png" alt="small">
					<p>Отличный клиентский сервис</p>
				</div>
				<div class="innerSmBlock">	
					<img src="img/small5.png" alt="small">
					<p>Полный спектр услуг</p>
				</div>
			</div>
		</div>
	</div>
	<div class="productionBlock">	
			<div class="container">	
				<h2>Продукция</h2>
				<div class="productionOuter">	
					<div class="innerProduct">
						<a href="">
							<h5>Ливневые очистные сооружения <img src="img/rightArr.png" alt="rightArrow"></h5>
						<div class="wrappInnImg bigMarg">
							<img src="img/blockQ1.png" alt="blockQ1">
						</div>
						</a>
					</div>
					<div class="innerProduct">
						<a href="">
							<h5>Насосные станции <img src="img/rightArr.png" alt="rightArrow"></h5>
						<div class="wrappInnImg smallMarg">
							<img src="img/blockQ2.png" alt="blockQ1">
						</div>
						</a>
					</div>
					<div class="innerProduct">
						<a href="">
							<h5>Хозбытовые стоки <img src="img/rightArr.png" alt="rightArrow"></h5>
						<div class="wrappInnImg middleBl">
							<img src="img/blockQ3.png" alt="blockQ1">
						</div>
						</a>
					</div>
					<div class="innerProduct">
						<a href="">
							<h5>Колодцы <img src="img/rightArr.png" alt="rightArrow"></h5>
						<div class="wrappInnImg">
							<img src="img/blockQ4.png" alt="blockQ1">
						</div>
						</a>
					</div>
					<div class="innerProduct">
						<a href="">
							<h5>Оборотные системы очистки <img src="img/rightArr.png" alt="rightArrow"></h5>
						<div class="wrappInnImg bigMarg">
							<img src="img/blockQ5.png" alt="blockQ1">
						</div>
						</a>
					</div>
					<div class="innerProduct">
						<a href="">
							<h5>Емкости <img src="img/rightArr.png" alt="rightArrow"></h5>
						<div class="wrappInnImg smallMarg">
							<img src="img/blockQ6.png" alt="blockQ1">
						</div>
						</a>
					</div>
					<div class="innerProduct">
						<a href="">
							<h5>Стеклопластиковые трубы <img src="img/rightArr.png" alt="rightArrow"></h5>
						<div class="wrappInnImg smallMarg">
							<img src="img/blockQ6.png" alt="blockQ1">
						</div>
						</a>
					</div>
					<div class="innerProduct">
						<a href="">
							<h5>Фасонные изделия <img src="img/rightArr.png" alt="rightArrow"></h5>
						<div class="wrappInnImg middleBl">
							<img src="img/blockQ3.png" alt="blockQ1">
						</div>
						</a>
					</div>
				</div>
			</div>
	</div>
	<div class="aboutBlock">
		<div class="container">
			<h2>О компании</h2>
			<div class="textAbP">
				<p>Компания BIOPROJECT была создана в 2016 году группой специалистов в области водоподготовки и водоочистки. Более двух лет ведущие компании и физические лица доверяют нам проектирование, производство и монтаж систем отчиски воды.
Компания BIOPROJECT один из ведущих производителей готового оборудования на Российском рынке. Мы объединили практический опыт и знания специалистов с более чем 20-летним стажем в этой отрасли, чтобы предложить Вам качественную продукцию напрямую от производителя.Компания BIOPROJECT была создана в 2016 году группой специалистов в области водоподготовки и водоочистки. Более двух лет ведущие компании и физические лица доверяют нам проектирование, производство и монтаж систем отчиски воды.</p>
				<p>Компания BIOPROJECT один из ведущих производителей готового оборудования на Российском рынке. Мы объединили практический опыт и знания специалистов с более чем 20-летним стажем в этой отрасли, чтобы предложить Вам качественную продукцию напрямую от производителя.Компания BIOPROJECT была создана в 2016 году группой специалистов в области водоподготовки и водоочистки. Более двух лет ведущие компании и физические лица доверяют нам проектирование, производство и монтаж систем отчиски воды.</p>
				<p>Компания BIOPROJECT один из ведущих производителей готового оборудования на Российском рынке. Мы объединили практический опыт и знания специалистов с более чем 20-летним стажем в этой отрасли, чтобы предложить Вам качественную продукцию напрямую от производителя.</p>
			</div>
		</div>
	</div>
	<div class="workWithUs">
		<div class="container">
			<h2>С нами работают</h2>
			<div class="outerLogo">
				<div class="innerLogo">
					<img src="img/logo1.png" alt="logo">
				</div>

				<div class="innerLogo">
					<img src="img/logo2.png" alt="logo">
				</div>

				<div class="innerLogo">
					<img src="img/logo3.png" alt="logo">
				</div>

				<div class="innerLogo">
					<img src="img/logo4.png" alt="logo">
				</div>

				<div class="innerLogo">
					<img src="img/logo5.png" alt="logo">
				</div>

				<div class="innerLogo">
					<img src="img/logo6.png" alt="logo">
				</div>
			</div>
		</div>
	</div>
	<div class="ourWorks">
		<div class="container">
			<h2>Наши работы</h2>
			<p>За х лет работы нами было произведено более y единиц   <br> оборудования в крупнейших городах страны</p>
			<div class="mapBlock">
				<img src="img/map.png" alt="map">
			</div>
		</div>
	</div>
	<footer>
		<div class="container">
			<div class="topPart">
				<div class="footerImg">
					<a href="">
						<img src="img/footLogo.png" alt="footer">
					</a>
				</div>
				<div class="footerMenu">
					<ul>
						<li><a href="">О компании</a></li>
						<li><a href="">Продукция</a></li>
						<li><a href="">Наши проекты</a></li>
						<li><a href="">Прайсы</a></li>
						<li><a href="">Документация</a></li>
						<li><a href="">Вакансии</a></li>
						<li><a href="">Контакты</a></li>
					</ul>
				</div>
			</div>
			<div class="bottomPart">
				<div class="copyright">
					<p>2018 &copy; BIOPROJECT</p>
				</div>
				<div class="contactBlock">
					<div class="preCalculate">
							<img src="img/PlusBl.png" alt="PlusBl">
						<a href="">
							оставить  заявку на просчет
						</a>
					</div>
					<div class="phoneCalc">
						<a href=""><img src="img/greyPh.png" alt="phone"> +7 (961) 123 45 67</a>	
					</div>
					<div class="phoneCalc">
						<p><img src="img/geoFoot.png" alt="geo"> Москва, ул. Тестовая,д. 10, офис 1</p>
					</div>
				</div>
				<div class="linksBlock">
					<ul>
						<li><a href=""><i class="fab fa-facebook-f"></i></a></li>
						<li><a href=""><i class="fab fa-instagram"></i></a></li>
						<li><a href=""><i class="fab fa-twitter"></i></a></li>
					</ul>
				</div>
			</div>
		</div>
	</footer>
	<script
  src="https://code.jquery.com/jquery-3.3.1.js"
  integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
  crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script src="js/script.js"></script>
</body>


		<?php
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) :
				?>
				<header>
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
				<?php
			endif;

			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_type() );

			endwhile;

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
