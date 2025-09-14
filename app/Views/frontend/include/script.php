<!-- JavaScripts
	============================================= -->
<script src="<?= base_url(); ?>/frontend/js/jquery.js"></script>
<script src="<?= base_url(); ?>/frontend/js/plugins.min.js"></script>

<!-- Bootstrap Data Table Plugin -->
<script src="<?= base_url(); ?>/frontend/js/components/bs-datatable.js"></script>
<!-- Bootstrap Select Plugin -->
<script src="<?= base_url(); ?>/frontend/js/components/bs-select.js"></script>

<!-- Footer Scripts
	============================================= -->
<script src="<?= base_url(); ?>/frontend/js/functions.js"></script>

<script>
	$(document).ready(function() {
		$('#datatable1').dataTable();
		$('#datatable2').dataTable();
	});
</script>




<script>
	// hanya sekali saja
	AOS.init({
		once: true,
	});
</script>

<script>
	$(document).ready(function() {
		$('#flex-slider-carousel').flexslider({
			selector: ".slider-wrap > .slide",
			animation: "slide",
			controlNav: false,
			animationLoop: false,
			slideshow: false,
			itemWidth: 120,
			itemMargin: 4,
			asNavFor: '#flex-slider',
			start: function(slider) {
				jQuery('.flex-prev').html('<i class="icon-angle-left"></i>');
				jQuery('.flex-next').html('<i class="icon-angle-right"></i>');
			}
		});

		$('#flex-slider').flexslider({
			selector: ".slider-wrap > .slide",
			animation: "slide",
			controlNav: false,
			animationLoop: false,
			slideshow: false,
			sync: "#flex-slider-carousel",
			start: function(slider) {
				jQuery('.flex-prev').html('<i class="icon-angle-left"></i>').addClass('small');
				jQuery('.flex-next').html('<i class="icon-angle-right"></i>');
			}
		});
	});

	$(document).ready(function() {
		setTimeout(function() {
			$('.flex-control-thumbs img').each(function(index) {
				let slides = $('.flexslider .slide img');
				if (slides[index]) {
					$(this).attr('alt', slides.eq(index).attr('alt'));
				}
			});
		}, 500);
	});
</script>