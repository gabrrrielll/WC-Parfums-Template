<?php
/**
 * Find Love Scent collection intro landing (shortcode output).
 */

defined( 'ABSPATH' ) || exit;

$uploads_base     = 'https://andradadan.com/axs/wp-content/uploads/2026/06';
$uploads_base_new = 'https://andradadan.com/axs/wp-content/uploads/2026/09';

$images = array(
	'hero_logo'       => $uploads_base . '/page_logo-hero.png',
	'intro_photo'     => $uploads_base . '/page_poza-sectiune-2-provizorie.png',
	'collection_badge' => $uploads_base . '/find-love-the-scent-web-page-72dpi.png',
	'bottom_logo'     => $uploads_base . '/page_logo-find-love-auriu.png',
);

$perfumes = array(
	array(
		'name'        => 'Siren Call',
		'header'      => $uploads_base . '/find-love-the-scent-web-page_sectiune-parfum-1-.png',
		'product'     => $uploads_base . '/AndradaDan_parfum_Siren-Call.png',
		'url'         => home_url( '/produs/parfum-siren-call/' ),
		'stagger_up'  => false,
	),
	array(
		'name'        => 'Siren Rock',
		'header'      => $uploads_base . '/find-love-the-scent-web-page_sectiune-parfum-2-.png',
		'product'     => $uploads_base . '/AndradaDan_parfum_Siren-Rock.png',
		'url'         => home_url( '/produs/parfum-siren-rock/' ),
		'stagger_up'  => true,
	),
	array(
		'name'        => 'Mad Love',
		'header'      => $uploads_base . '/find-love-the-scent-web-page_sectiune-parfum-3.png',
		'product'     => $uploads_base . '/AndradaDan_parfum_Mad-Love.png',
		'url'         => home_url( '/produs/parfum-mad-love/' ),
		'stagger_up'  => false,
	),
	array(
		'name'        => 'Inner Peace',
		'header'      => $uploads_base . '/find-love-the-scent-web-page_sectiune-parfum-4-.png',
		'product'     => $uploads_base . '/AndradaDan_parfum_Inner-Peace.png',
		'url'         => home_url( '/produs/parfum-inner-peace/' ),
		'stagger_up'  => false,
	),
	array(
		'name'        => 'Solar Kiss',
		'header'      => $uploads_base_new . '/Solar-Kiss-presentation-page-upper.png',
		'product'     => $uploads_base_new . '/Solar-Kiss-presentation-page-bottom.png',
		'url'         => home_url( '/produs/parfum-solar-kiss/' ),
		'stagger_up'  => true,
	),
	array(
		'name'        => 'Rose Pearl',
		'header'      => $uploads_base . '/find-love-the-scent-web-page_sectiune-parfum-5-.png',
		'product'     => $uploads_base . '/AndradaDan_parfum_Rose-Pearl.png',
		'url'         => home_url( '/produs/parfum-rose-pearl/' ),
		'stagger_up'  => false,
	),
);

$row_one = array_slice( $perfumes, 0, 3 );
$row_two = array_slice( $perfumes, 3, 3 );
?>

<div class="wcp-find-love-intro">
	<div id="find-love-scent-landing" class="fls-landing-container">

		<section class="fls-hero-section">
			<div class="fls-hero-content">
				<img
					class="fls-hero-logo"
					src="<?php echo esc_url( $images['hero_logo'] ); ?>"
					alt="<?php esc_attr_e( 'Find Love The Scent Logo', 'wc-parfums-template' ); ?>"
					loading="lazy"
				/>
				<a class="fls-btn-pill fls-hero-btn" href="#intro-section"><?php esc_html_e( 'Descoperă', 'wc-parfums-template' ); ?></a>
			</div>
		</section>

		<section id="intro-section" class="fls-intro-section">
			<div class="fls-intro-image-container">
				<img
					class="fls-intro-img"
					src="<?php echo esc_url( $images['intro_photo'] ); ?>"
					alt="<?php esc_attr_e( 'Andrada Dan prezentând parfumul', 'wc-parfums-template' ); ?>"
					loading="lazy"
				/>
			</div>
			<div class="fls-intro-text-container">
				<h2 class="fls-serif-title fls-intro-heading"><?php esc_html_e( 'Am o surpriză la care lucrez de mult timp… și a sosit momentul!', 'wc-parfums-template' ); ?></h2>
				<div class="fls-intro-subheading">
					<h3><?php esc_html_e( 'Find Love devine… parfum.', 'wc-parfums-template' ); ?></h3>
					<?php esc_html_e( 'Magnetism pur. Energie feminină pe piele.', 'wc-parfums-template' ); ?>
				</div>
				<div class="fls-intro-description">
					<h4 class="fls-serif-title fls-intro-product-title"><?php esc_html_e( 'Find Love the Scent', 'wc-parfums-template' ); ?></h4>
					<p><?php esc_html_e( 'Nu e doar un parfum. E momentul în care intri într-o cameră și energia se schimbă fără să spui nimic.', 'wc-parfums-template' ); ?></p>
					<p><?php esc_html_e( 'Nu te roagă să fii observată. Te face inevitabilă. Se așază pe piele ca o promisiune: mister, căldură, atracție pură.', 'wc-parfums-template' ); ?></p>
					<p><?php esc_html_e( 'E „acel ceva” pe care nu îl pot explica... dar îl simt. Și se întorc.', 'wc-parfums-template' ); ?></p>
				</div>
				<a class="fls-btn-pill" href="#perfumes-section"><?php esc_html_e( 'Comandă acum', 'wc-parfums-template' ); ?></a>
			</div>
		</section>

		<section class="fls-collection-section">
			<div class="fls-collection-text-container">
				<h2 class="fls-serif-title fls-collection-heading"><?php esc_html_e( 'Find Love: The Scent Collection', 'wc-parfums-template' ); ?></h2>
				<div class="fls-collection-points">
					<div class="fls-point-item fls-point-item--plain">
						<p class="fls-point-desc fls-point-desc--lead"><?php esc_html_e( 'O selecție de 6 arome ale atracției și magnetismului pur, la 20 ml, create special ca să îți activeze magnetismul și strălucirea. Formula sa intensă se activează la căldura pielii tale și rezistă întreaga zi, folosind esențe premium aduse direct din laboratoarele din Franța.', 'wc-parfums-template' ); ?></p>
					</div>
					<div class="fls-point-item">
						<h3 class="fls-point-title"><?php esc_html_e( 'Creat pentru poșeta de întâlniri:', 'wc-parfums-template' ); ?></h3>
						<p class="fls-point-desc"><?php esc_html_e( 'Formatul compact de 20 ml a fost gândit strategic pentru a fi mereu cu tine, oriunde te poartă pașii.', 'wc-parfums-template' ); ?></p>
					</div>
					<div class="fls-point-item">
						<h3 class="fls-point-title"><?php esc_html_e( 'Funcționează ca o ancoră neuro-olfactivă:', 'wc-parfums-template' ); ?></h3>
						<p class="fls-point-desc"><?php esc_html_e( 'Purtat ritualic, parfumul îi amintește instant minții tale să renunțe la control și să coboare în corp. Fiecare sticlă vine în propria sa cutie individuală, ca o amuletă olfactivă gata de purtat în poșetă.', 'wc-parfums-template' ); ?></p>
					</div>
					<div class="fls-point-item fls-point-item--quote">
						<p class="fls-point-desc fls-point-desc--quote"><?php esc_html_e( 'Acest set este ghidul tău olfactiv zilnic, conceput să te transforme într-un magnet viu pentru iubire și abundență radiind o energie contagioasă care aprinde flacăra pasiunii în ceilalți.', 'wc-parfums-template' ); ?></p>
					</div>
				</div>
				<a class="fls-btn-pill" href="#perfumes-section"><?php esc_html_e( 'Descoperă cele 6 arome ale atracției', 'wc-parfums-template' ); ?></a>
			</div>
			<div class="fls-collection-badge-container">
				<img
					class="fls-collection-badge"
					src="<?php echo esc_url( $images['collection_badge'] ); ?>"
					alt="<?php esc_attr_e( 'Magnetic Attraction by Find Love the Scent', 'wc-parfums-template' ); ?>"
					loading="lazy"
				/>
			</div>
		</section>

		<section id="perfumes-section" class="fls-perfumes-section">
			<div class="fls-perfumes-grid">
				<div class="fls-perfumes-row fls-perfumes-row-1">
					<?php foreach ( $row_one as $perfume ) : ?>
						<div class="fls-perfume-wrapper<?php echo $perfume['stagger_up'] ? ' fls-stagger-up' : ''; ?>">
							<div class="fls-perfume-card">
								<img
									class="fls-perfume-header-img"
									src="<?php echo esc_url( $perfume['header'] ); ?>"
									alt="<?php echo esc_attr( sprintf( '%s Header', $perfume['name'] ) ); ?>"
									loading="lazy"
								/>
								<img
									class="fls-perfume-card-img"
									src="<?php echo esc_url( $perfume['product'] ); ?>"
									alt="<?php echo esc_attr( $perfume['name'] ); ?>"
									loading="lazy"
								/>
							</div>
							<a class="fls-btn-pill fls-perfume-btn" href="<?php echo esc_url( $perfume['url'] ); ?>"><?php esc_html_e( 'Descoperă', 'wc-parfums-template' ); ?></a>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="fls-perfumes-row fls-perfumes-row-2">
					<?php foreach ( $row_two as $perfume ) : ?>
						<div class="fls-perfume-wrapper<?php echo $perfume['stagger_up'] ? ' fls-stagger-up' : ''; ?>">
							<div class="fls-perfume-card">
								<img
									class="fls-perfume-header-img"
									src="<?php echo esc_url( $perfume['header'] ); ?>"
									alt="<?php echo esc_attr( sprintf( '%s Header', $perfume['name'] ) ); ?>"
									loading="lazy"
								/>
								<img
									class="fls-perfume-card-img"
									src="<?php echo esc_url( $perfume['product'] ); ?>"
									alt="<?php echo esc_attr( $perfume['name'] ); ?>"
									loading="lazy"
								/>
							</div>
							<a class="fls-btn-pill fls-perfume-btn" href="<?php echo esc_url( $perfume['url'] ); ?>"><?php esc_html_e( 'Descoperă', 'wc-parfums-template' ); ?></a>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<div class="fls-bottom-logo-container">
			<img
				class="fls-bottom-logo"
				src="<?php echo esc_url( $images['bottom_logo'] ); ?>"
				alt="<?php esc_attr_e( 'Find Love The Scent Logo', 'wc-parfums-template' ); ?>"
				loading="lazy"
			/>
		</div>
	</div>
</div>
