$(document).ready(function(){$('.owl-carousel').owlCarousel({items:1,loop:true,autoplay:true,autoplayTimeout:4000,autoplayHoverPause:true,lazyLoad:true,});$('#slider_video_home li:not(:first)').each(function(){var ifa=$(this).attr('data-iframe');var thum=getThumbYoutube(getUrlYoutube(ifa),'small');$(this).append("<img class='lazy' data-original='"+thum+"' alt='Video MobileCity'>");});$('#slider_video_home li:first-child').each(function(){var ifa=$(this).attr('data-iframe');var videoid=getIdVideo(ifa);var replacement='<iframe src="https://www.youtube.com/embed/'+videoid[1]+'" frameborder="0" allowfullscreen webkitallowfullscreen mozallowfullscreen></iframe>';$(this).html(replacement);});$('#slider_video_home li').on('click',function(){var embed=getIdYoutube($(this).attr('data-iframe'));$('#video').html(embed);});go_link_on_select("#order_phone");go_link_on_select("#go_phone_made");});