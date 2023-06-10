@extends('layouts.master')
@section('css')
@endsection
@section('content')
<section class="bg-gray">
	<div class="inner-header">
		<div class="container">
			<div class="pull-left">
				<h6 class="inner-title">Page Not Found</h6>
			</div>
			<div class="pull-right">
				<div class="beta-breadcrumb font-large">
					<a href="#">Pages</a> / <span>Page 404</span>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	
	<div class="container">
		<div id="content" class="space-top-none space-bottom-none">
			<div class="abs-fullwidth bg-gray">
				<div class="space100">&nbsp;</div>
				<div class="space80">&nbsp;</div>
				<div class="container text-center">
					<h2>Oops! That Page Can’t Be Found!</h2>
					<div class="space40">&nbsp;</div>
					<img src="source/assets/dest/images/404.jpg" alt="">
					<div class="space30">&nbsp;</div>
					<p>It looks like nothing was found at this location. Maybe try to use a search?</p>
					<form role="search" method="get" id="searchform" action="/">
				        <input type="text" value="" name="s" id="s" placeholder="Search entire store here..." />
				        <button class="fa fa-search" type="submit" id="searchsubmit"></button>
					</form>
				</div>
				<div class="space100">&nbsp;</div>
				<div class="space30">&nbsp;</div>
			</div>
		</div> <!-- #content -->
	</div> <!-- .container -->
</section>
@endsection
@section('js')
@endsection
	<script type="text/javascript">
    $(function() {
        // this will get the full URL at the address bar
        var url = window.location.href;

        // passes on every "a" tag
        $(".main-menu a").each(function() {
            // checks if its the same on the address bar
            if (url == (this.href)) {
                $(this).closest("li").addClass("active");
				$(this).parents('li').addClass('parent-active');
            }
        });
    });   


</script>
<script>
	 jQuery(document).ready(function($) {
                'use strict';
				
// color box

//color
      jQuery('#style-selector').animate({
      left: '-213px'
    });

    jQuery('#style-selector a.close').click(function(e){
      e.preventDefault();
      var div = jQuery('#style-selector');
      if (div.css('left') === '-213px') {
        jQuery('#style-selector').animate({
          left: '0'
        });
        jQuery(this).removeClass('icon-angle-left');
        jQuery(this).addClass('icon-angle-right');
      } else {
        jQuery('#style-selector').animate({
          left: '-213px'
        });
        jQuery(this).removeClass('icon-angle-right');
        jQuery(this).addClass('icon-angle-left');
      }
    });
				});
	</script>