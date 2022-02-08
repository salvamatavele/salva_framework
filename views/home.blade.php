@extends('layouts.app')
@section('content')
    <!-- TOP -->
		<div class="top-wrap uk-position-relative uk-light uk-background-secondary">
			
			
			<div class="uk-cover-container uk-light uk-flex uk-flex-middle top-wrap-height">
				
				<!-- TOP CONTAINER -->
				<div class="uk-container uk-flex-auto top-container uk-position-relative uk-margin-medium-top" data-uk-parallax="y: 0,50; easing:0; opacity:0.2">
					<div class="uk-width-1-2@s" data-uk-scrollspy="cls: uk-animation-slide-right-medium; target: > *; delay: 150">
						<h6 class="uk-text-primary uk-margin-small-bottom"></h6>
						<h1 class="uk-margin-remove-top">Innovation in your hands.</h1>
						<p class="subtitle-text uk-visible@s">Wellcome to DRY CODE mini framework.If you can read this page, it means that this site is working properly. This mini framework was created with php, mvc, php-fig.</p>
						<a href="#" title="Learn More" class="uk-button uk-button-primary uk-border-pill" data-uk-scrollspy-class="uk-animation-fade">Documentetion</a>
					</div>
				</div>
				<!-- /TOP CONTAINER -->
				<!-- TOP IMAGE -->
				<img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" data-srcset="https://i.ibb.co/PrXFrdH/DRY-CODE-logos.jpg 640w,
				https://i.ibb.co/PrXFrdH/DRY-CODE-logos.jpg 960w,
				https://i.ibb.co/PrXFrdH/DRY-CODE-logos.jpg 1200w,
				https://i.ibb.co/PrXFrdH/DRY-CODE-logos.jpg 2000w"
				data-sizes="100vw"
				data-src="https://i.ibb.co/PrXFrdH/DRY-CODE-logos.jpg" alt="" data-uk-cover data-uk-img data-uk-parallax="opacity: 1,0.1; easing:0"
				>
				<!-- /TOP IMAGE -->
			</div>
			<div class="uk-position-bottom-center uk-position-medium uk-position-z-index uk-text-center">
				<a href="#content" data-uk-scroll="duration: 500" data-uk-icon="icon: arrow-down; ratio: 2"></a>
			</div>
		</div>
		<!-- /TOP -->
		
		<!-- FOOTER -->
		<footer class="uk-section uk-section-secondary uk-padding-remove-bottom">
			
			<div class="uk-text-center uk-padding uk-padding-remove-horizontal">
				<span class="uk-text-small uk-text-muted">© 2022 All right reserved - <a href="https://github.com/salvamatavele/salva_framework"> GitHub</a> | Built with <a href="http://getuikit.com" title="Visit UIkit 3 site" target="_blank" data-uk-tooltip><span data-uk-icon="uikit"></span></a></span>
			</div>
		</footer>
		<!-- /FOOTER -->
		
@endsection