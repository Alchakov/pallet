const $ = jQuery.noConflict();

const throttle = (type, name, obj) => {
	let running = false;
	const object = obj || window;
	const func = () => {
		if (running) {
			return;
		}
		running = true;
		requestAnimationFrame(() => {
			object.dispatchEvent(new CustomEvent(name));
			running = false;
		});
	};

	object.addEventListener(type, func);
};

function deviceType() {
	let type = window
		.getComputedStyle(document.querySelector('body'), '::before')
		.getPropertyValue('content').replace(/'/g, '').replace(/"/g, '');

	if ($('html').hasClass('mobile-device') && type === 'desktop' ) {
		type = 'tablet';
	} 
	return type;
}
function checkDeviceType(MQ, isMobile, isTablet, isDesktop, arrCbs) {
	if (MQ === 'desktop' && isDesktop) {
		arrCbs[0]();
	} else if (MQ === 'tablet' && isTablet) {
		arrCbs[1]();
	} else if (MQ === 'mobile' && isMobile) {
		arrCbs[2]();
	}
	//console.log('checkDeviceType:' + MQ);
}

function staticInit(mq, firstFunc, otherFunc, secFunc) {
	if (mq === 'desktop') {
		firstFunc();
	}  else if (mq === 'tablet') {
		otherFunc();
	} else if (mq === 'mobile') {
		secFunc();
	}
	// console.log('staticInit:' + mq);
}

(() => {
	const $window = $(window);			
	const $main = $('.main');			
	const $header = $('.b-header');
	let headerHeight = $header.outerHeight();

	class App {
		constructor() {
			
		}

		init = () => {
			const self = this;

			this.mainMenu();	

			this.mobileMenu()

			this.fancybox();

			this.inputmaskArg();

			this.linkScroll();

			this.scrollToTop();

			this.initSliders();

			this.stickyHeader();

			this.priceTable();

			this.search();	

			this.breadcrumbs()

			this.iOsModalFix();
	

			$('input, textarea').placeholder();	

		    $('body').on('hidden.bs.modal', '.modal', function() {
			    $('[data-toggle="modal"]').blur();
			}); 

			$('.cookie-notification__close').on('click', function (e) {
				e.preventDefault();	
				$('.cookie-notification').remove();
			});

			$('.toggle-btn').click(function(e) {				
				e.preventDefault();				
				let destinationTarget = $($(this).attr('href'));	
				$(this).blur();			
				
				if (destinationTarget.length>0) {
					$(this).toggleClass('toggle-btn--active');
					destinationTarget.slideToggle();
				}
			});


			$('.dropdown')
				.on('show.bs.dropdown', function() {
					$(this).find('.dropdown-menu').first().stop(true, true).slideDown(150);
				})
				.on('hide.bs.dropdown', function() {
					$(this).find('.dropdown-menu').first().stop(true, true).slideUp(150);
				});
				

			$(".sidebar-sticky").stick_in_parent({
				offset_top: (isDesktop) ? 100 : 10
			});

			$('[data-target="#modal-get-wholesale-price"]').on('click', function() {
				$('form[name="form-modal-get-wholesale-price"]').find('input[name="product"]').val($(this).data('product'))
			})

			$('[data-target="#modal-product-cat-title-button"]').on('click', function() {
				$('#modal-product-cat-title-button').find('.modal-title').html($(this).text());
				$('form[name="form-modal-product-cat-title-button"]').find('input[name="button"]').val($(this).data('modal-title'));
			})
	
		};	



		inputmaskArg = () => {
			$('.input-phone').inputmask("+7 (999) 999 99 99");
		}

		fancybox = () => {

			var thumbnails = $(".content a:has(img)").not(".fancybox, .no-fancybox").filter(function() {
			        return /\.(jpe?g|png|gif|bmp)$/i.test($(this).attr('href'));
			    });
						
		    thumbnails.each(function(){
		    	$(this).addClass("fancybox");
       			if ($(this).attr('rel') == undefined)
			        $(this).attr("rel", "inner-img");			                            
			});  

		    $('.content .wp-block-gallery').each(function(g) {
			    $('a', this).attr('rel', function(i) {
			         return 'gallery-' + g;
			    });
			});

		    $('.fancybox').fancybox({
		        padding:0,
		        scrolling:"no",
		        openEffect: 'none',
		        closeEffect: 'none',
		        nextEffect: 'none',
		        prevEffect: 'none',
		        mouseWheel: false, 
		        margin : [20, 50, 20, 50],
		        helpers: {
		            overlay: {
		                locked: true
		            }
		        },
		        tpl : {
					closeBtn : '<a class="fancybox-item fancybox-close" href="javascript:;"><span class="icon-cross"></span></a>',
					prev : '<a class="fancybox-nav fancybox-prev" href="javascript:;"><span class="icon-arrow-left"></span></a>',
					next : '<a class="fancybox-nav fancybox-next" href="javascript:;"><span class="icon-arrow-right"></span></a>'
				}
		    });
			
		}

		scrollToTop = () => {
			const $sctollToTop = $( ".scroll-to-top" );
			$(window).scroll(function(){
				if ($(this).scrollTop() > 300) {
					$('.scroll-to-top').fadeIn();
				} else {
					$('.scroll-to-top').fadeOut();
				}
			});

			//Click event to scroll to top
			$sctollToTop.click(function(e){
				e.preventDefault();
				$('html, body').animate({scrollTop : 0},800);
				return false;
			});
		};

		mainMenu = () => {

			if ($window.width < 992 && $('html').hasClass('desktop-device')) {
            	$('#main-nav').addClass('main-menu--strict');
		     }

			const $menu = $('.hierarchical-menu'),
			 	  $menuLink =  $menu.find('a');
			
				
			$menuLink.click(function(e) {
				if (isTablet) {	
				    e.preventDefault();
				    let el = $(this);
				    let link = el.attr('href');
				    let parent = el.parent();

				    if (el.hasClass('activated')) {
				    	window.location.href = link;
				    } else if (parent.hasClass('menu-item-has-children')) {
						el.addClass('activated');
				    }
					else {
						window.location.href = link;
					}

					return false;
				}
			});				
		};

		mobileMenu = () => {	
			if (isMobile) {

				const $toggleButton = $('.mobile__menu-toggle'),
			    	  $menuWrap = $('.mobile__panel')
			    	;			    	

				// Hamburger button		
				$toggleButton.on('click', function() {
					let position = $window.scrollTop();
					const $body = $('body');
					const $body_html = $('html, body');
					if ($(this).hasClass('is-open')) {						
						$(this).removeClass('is-open');
						$body_html.removeClass('mobile-menu-open');
						$menuWrap.removeClass('mobile__panel--show');
						$body.css('top', '');		
						$body_html.scrollTop($body.data('top'));					
						$body.data('top', '');
					} else {	
						$(this).addClass('is-open');
						$body_html.addClass('mobile-menu-open');
						$menuWrap.addClass('mobile__panel--show');	
						$body.css('top', (-position));
						$body.data('top', position);				
					}			
				});

				const $mobileMenu = $('#mobile-nav');

				$mobileMenu.find('a').each(function( index ) {					 
					let el = $(this);	
					let parent = el.parent();	
					parent.removeClass('active');
					if (parent.hasClass('menu-item-has-children')) {	
						el.clone().wrap('<li class="cat-parent-highlight" />').parent().prependTo(el.next('ul'));
					}
				});


				const slinky = $mobileMenu.slinky({
					'theme':'slinky-theme'
				})

			}				
	    }

		equalHeight = (el, parent) => {
			let highestBox = 0;
			$(el, parent).each(function(){			        
				if($(this).height() > highestBox) {
					highestBox = $(this).height(); 
				}
			});  
			$(el, parent).height(highestBox);
		}


		linkScroll = () => {
			const $root = $('html, body');
			$('a.link-scroll').click(function () {
				let offset = (isMobile) ? 70 : 100;
			    $root.animate({
			        scrollTop: $( $.attr(this, 'href') ).offset().top - offset
			    }, 500);
			    return false;
			});
		}

		initSliders = () => {	

			$('.front-slideshow').slick({				
				slidesToShow: 1,
				slidesToScroll: 1,
				infinite: true,
				fade: true,
				//autoplay: true,
				//autoplaySpeed: 15000,
				appendArrows: $('.front-slideshow-arrows'),
				prevArrow: '<button type="button" class="slick-prev"><span class="icon-arrow-left-thin"></span></button>',
				nextArrow: '<button type="button" class="slick-next"><span class="icon-arrow-right-thin"></span></button>',					
			}).on('beforeChange', function(event, slick, currentSlide, nextSlide) {
				  $('.front-slide *').removeClass('animated delay-05s flipInX fadeIn fadeInDown');
				  $('.front-slide__title').eq(nextSlide).addClass('animated flipInX');
				  $('.front-slide__caption').eq(nextSlide).addClass('animated fadeInDown');				  
				  $('.front-slide__btn').eq(nextSlide).addClass('animated delay-05s fadeIn');
			});
			

			$('.slider-2').each(function() {
				let appendArrows = $('#'+ $(this).data('arrow-place'));
				$(this).slick({						
					slidesToShow: 2,
					slidesToScroll: 1,
					infinite: true,
					prevArrow: '<button type="button" class="slick-prev"><span class="icon-arrow-left"></span></button>',
					nextArrow: '<button type="button" class="slick-next"><span class="icon-arrow-right"></span></button>',
					appendArrows: appendArrows,
					responsive: [
					    {
					      breakpoint: 576,
					      settings: {		
							slidesToShow: 1,
					      }
					    }
				  	]	
				});		  	
			});	

			$('.slider-3').each(function() {
				let appendArrows = $('#'+ $(this).data('arrow-place'));
				$(this).slick({						
					slidesToShow: 3,
					slidesToScroll: 1,
					infinite: true,
					prevArrow: '<button type="button" class="slick-prev"><span class="icon-arrow-left"></span></button>',
					nextArrow: '<button type="button" class="slick-next"><span class="icon-arrow-right"></span></button>',
					appendArrows: appendArrows,
					responsive: [
					    {
					      breakpoint: 768,
					      settings: {		
							slidesToShow: 2,
					      }
					    },
					    {
					      breakpoint: 576,
					      settings: {		
							slidesToShow: 1,
					      }
					    }
				  	]	
				});		
			});	

			$('.slider-4').each(function() {
				let appendArrows = $('#'+ $(this).data('arrow-place'));
				$(this).slick({				
					slidesToShow: 4,
					slidesToScroll: 1,	
					prevArrow: '<button type="button" class="slick-prev"><span class="icon-arrow-left"></span></button>',
					nextArrow: '<button type="button" class="slick-next"><span class="icon-arrow-right"></span></button>',	
					appendArrows: appendArrows,
					responsive: [
					    {
					      breakpoint: 992,
					      settings: {		
							slidesToShow: 3,
					      }
					    },
					    {
					      breakpoint: 768,
					      settings: {		
							slidesToShow: 2,
					      }
					    },
					    {
					      breakpoint: 576,
					      settings: {		
							slidesToShow: 1,
					      }
					    }
				  	]		
				});	
			});				      

		};


		stickyHeader = () => {			
			if (isDesktop) {
				$('.header-middle, .header-bottom').clone().appendTo('.header-sticky');
				$('.header-sticky .header-btn .btn').removeClass('btn--transparent');				
				$(window).scroll(function(){
				if ($(this).scrollTop() > 2 * headerHeight) {
						$('.header-sticky').fadeIn(200);
					} else {
						$('.header-sticky').fadeOut(50);
					}
				});
			}		
		}

		priceTable = () => {
			let el = $('.price-table'),
			 	price = $('.price-table__price'),
			 	link = $('a.price-table__apply'),
				form = $('form[name="form-modal-apply"]');
			 	
		 	price.each(function(index) {
			    let text = $(this).text().split(' ');
			    text[0] = '<span class="fw-semibold">' + text[0] + '</span>';
			    $(this).html(text.join(' '));	
			});	

			link.on('click', function (e) {
				e.preventDefault();	
				let title = $(this).data('title'); 
				$('#modal-apply__title').html(title);							
				form.find('input[name="product"]').val(title);
			});
		}


		search = () => {
			const $searchForm = $('.header-search');
			const $searchTrigger = $searchForm.find('button[type="submit"]');
			const $searchInput = $searchForm.find('input[name="s"]');
			const $activeClass = 'is-opened';
			const $mobileSearch = $('.mobile-search-block');
			const $mobileToggle = $('.mobile-search-toggle');
			let clickCounter = 0;

			$searchTrigger.on('click', (e) => {
				if (clickCounter === 0) {
			    	e.preventDefault();
			     	$searchForm.addClass($activeClass);
		     		setTimeout( function() {
						$searchInput.focus();
					}, 200);			     
			    	clickCounter++;
			     	$(document).on('click', handleOutSearchClick);
			    } else {
			    	if ($searchInput.val().length > 0)
						$searchForm.submit();
			    }
			});

			const handleOutSearchClick = (e) => {
				    const isForm = !!$searchForm.has(e.target).length || $searchForm.is(e.target);
				    if (!isForm) {
				    	resetSearch();
				    }
				}

			const resetSearch = () => {
					$searchForm.removeClass($activeClass);
				    $searchInput.val('');
				    $('.dgwt-wcas-close').trigger('click');
				    clickCounter = 0;
				    $(document).off('click', handleOutSearchClick);
				}


			$mobileToggle.on('click', (e) => {
				e.preventDefault();
				$mobileToggle.toggleClass($activeClass);
				$mobileSearch.toggleClass($activeClass);
			});				

		};

		breadcrumbs = () => {
			if (isMobile) {
				let offset = 0;
				let breadcrumbs = $('.breadcrumbs ul');
				let li = breadcrumbs.find('li');
				li.each(function() {
					offset += $(this).outerWidth();
				});
	    		breadcrumbs.scrollLeft(offset);
			}
		}

		iOsModalFix = () => {

			if( navigator.userAgent.match(/iPhone|iPad|iPod/i) ) {
				$('.modal').on('show.bs.modal', function() {

					// Position modal absolute and bump it down to the scrollPosition
					$(this)
						.css({
							position: 'absolute',
							bottom: 'auto'
						});

					// Position backdrop absolute and make it span the entire page
					//
					// Also dirty, but we need to tap into the backdrop after Boostrap
					// positions it but before transitions finish.
					//
					setTimeout( function() {
						$('.modal-backdrop').css({
							position: 'absolute',
							top: 0,
							left: 0,
							width: '100%',
							height: Math.max(
								document.body.scrollHeight, document.documentElement.scrollHeight,
								document.body.offsetHeight, document.documentElement.offsetHeight,
								document.body.clientHeight, document.documentElement.clientHeight
							) + 'px'
						});
					}, 0);
				});
			}
		};

		handleLoad = () => {
			const self = this;	
		};

		switchToMobile = () => {
			console.log('switchToMobile: Mobile');			
		};

		switchToTablet = () => {
			console.log('switchToTablet: Tablet');
		};

		switchToDesktop = () => {
			console.log('switchToDesktop: Desktop');
		};

		handleResize = () => {
			const self = this;	
		};

		destroy = () => {};


		handleScroll = () => {			
			const self = this;		
		};

	}

	const projectApp = new App();
	const MQ = deviceType();
	let isMobile = false;
	let isTablet = false;
	let isDesktop = false;
	let isDesktopDevice = $('html').hasClass('desktop-device');

	throttle('resize', 'optimizedResize');

	function switchDeviceType(mq) {
		if (mq === 'desktop') {
			isDesktop = true;
			isTablet = false;
			isMobile = false;
		} else if (mq === 'tablet') {
			isMobile = false;
			isDesktop = false;
			isTablet = true;
		} else if (mq === 'mobile') {
			isMobile = true;
			isTablet = false;
			isDesktop = false;
		}
		//console.log('switchDeviceType: ' + mq);
	}

	const mq = deviceType();

	switchDeviceType(mq);

	staticInit(MQ, projectApp.switchToDesktop, projectApp.switchToTablet, projectApp.switchToMobile);

	$window.on('optimizedResize', () => {
		const mq = deviceType();

		checkDeviceType(
			mq,
			isMobile,
			isTablet,
			isDesktop,
			[projectApp.switchToDesktop, projectApp.switchToTablet, projectApp.switchToMobile]
		);

		switchDeviceType(mq);
		projectApp.handleResize()
	});

	$window
		.on('DOMContentLoaded', projectApp.init())
		.on('scroll', () => projectApp.handleScroll())
		.on('load', () => projectApp.handleLoad());
})();