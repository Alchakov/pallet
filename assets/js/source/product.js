const $ = jQuery.noConflict();


(() => {
	const $window = $(window);	


	class PLT_Product {
		constructor() {
			
		}

		init = () => {
			const self = this;

			this.gallery();				
			this.buyAction();			
			this.quantity();
		
		};	


		quantity = () => {		
			
			//$('.product-qty input').styler();

			$(document.body).on('change keyup', '.product-qty input', function (e) {
	            let input = $(this),
	            	qty = input.val(),
	            	price = input.data('price'),
	            	retail = input.data('retail') || price,
	            	limit = input.data('limit') || qty+1,
	            	result = 0;

	          	if (qty < 1) qty = 0;

	          	result = (qty < limit) ? retail*qty : price*qty;
        	
	          	$('.buy-card__total').text(result.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " "));
	          	$('form[name="form-modal-buy"]').find('input[name="product_total"]').val(result);	


	        });

		}


		gallery = () => {		

			let show_arrow = ($window.width() < 768) ? true : false;

			$('.single-product__gallery-main-track').slick({				
				slidesToShow: 1,
				slidesToScroll: 1,
				infinite: true,
				fade: true,
			  	arrows: show_arrow,
				asNavFor: '.single-product__gallery-thumb-track',
				prevArrow: '<button type="button" class="slick-prev"><span class="icon-arrow-left"></span></button>',
				nextArrow: '<button type="button" class="slick-next"><span class="icon-arrow-right"></span></button>',
				/*responsive: [
				    {
				      breakpoint: 768,
				      settings: {
			  			arrows: true,
				      }
				    }
			  	]*/
			});	

			$('.single-product__gallery-thumb-track').slick({
				slidesToShow: 3,
			 	slidesToScroll: 1,
			  	infinite: true,
			  	asNavFor: '.single-product__gallery-main-track',
				prevArrow: '<button type="button" class="slick-prev"><span class="icon-arrow-left"></span></button>',
				nextArrow: '<button type="button" class="slick-next"><span class="icon-arrow-right"></span></button>', 
  				focusOnSelect: true,
			  	responsive: [
			  		{
				      breakpoint: 1200,
				      settings: {		
						slidesToShow: 3,
				      }
				    },
			  	 	{
				      breakpoint: 992,
				      settings: {		
						slidesToShow: 2,
				      }
				    }
			  	]		
			});	

			$('.single-product__gallery').css('opacity', 1);
			

			$('.single-product__upsells-slider').slick({				
				slidesToShow: 3,
				slidesToScroll: 1,
				infinite: true,
				appendArrows: $('.single-product__upsells-arrow'),
				prevArrow: '<button type="button" class="slick-prev"><span class="icon-arrow-left"></span></button>',
				nextArrow: '<button type="button" class="slick-next"><span class="icon-arrow-right"></span></button>',
				responsive: [
				    {
				      breakpoint: 768,
				      settings: {		
						slidesToShow: 2,
				      }
				    }
			  	]	
			});	
		};

		  buyAction = (form, formConfig) => {

		  	let buyActionXhr = null;

		  	$('a.buy-action').click(function (e) {
				e.preventDefault();	

				if ( buyActionXhr !== null ) {
		            buyActionXhr.abort();
		        }
			
				let el = $(this);			
				let product = el.data('product');		
				let data = {
					'action'  : 'buy_action',
					'product' : product
				};

				buyActionXhr = $.post({
	                url:'/wp-admin/admin-ajax.php', 
	                data: data, 
	                cache: false,
	                beforeSend: function() {
	                    el.block({ 
	                        message: null,
	                        overlayCSS: {
	                            backgroundColor: '#fff',
	                            opacity: 0.5
	                        }
	                    }); 
	                }
	            }).done(function(respond) {
	                if (respond.success === true) {
	            		let form = $('form[name="form-modal-buy"]');
	            		form.find('#form-buy__card').html(respond.data.card);
						form.find('.product-qty input').styler();
						$('.modal').modal('hide');
						$("#modal-buy").modal('show');
						$('#modal-buy').on('hidden.bs.modal', function (e) {
							form.find('#form-buy__card').html('');
						});          
	                    
	                } else {
	                    alert(respond.data);
	                }
	            }).fail(function(jqXHR, textStatus, errorThrown) {
	                alert("Ошибка");
	            }).always(function(data) {
	                el.unblock();
	                buyActionXhr = null;
	            });				
					
			});
    
          
        } 
	}
			

	const PLT_PRODUCT = new PLT_Product();

	$window
		.on('DOMContentLoaded',  () => PLT_PRODUCT.init())
		;
			

})();