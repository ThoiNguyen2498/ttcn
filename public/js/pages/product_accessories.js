$(document).ready(function(){openPopup('.buy-now','.popup');closePopup('.popup_close','.popup');$('#image-gallery').lightSlider({gallery:false,item:1,loop:true,slideMargin:0,enableDrag:false,controls:true,autoHeight:true,speed:600,keyPress:true,freeMove:true,enableDrag:true,enableTouch:true,currentPagerPosition:'middle',thumbItem:5,auto:true,onSliderLoad:function(el){el.lightGallery({download:false,});}});$('#product-detail-viewall').click(function(){$('.product-detail-show').css('position','relative');view_detail('auto','product-detail-viewall','product-detail-viewdefault','product-detail-content');});$('#product-detail-viewdefault').click(function(){$('.product-detail-show').css('position','absolute');view_detail('1000px','product-detail-viewdefault','product-detail-viewall','product-detail-content');});$('.rating-star').on('click','i',function(){value_star=$(this).data('value');$('#rating').val(value_star);$('.rating-star').empty();var j=0;for(var i=0;i<value_star;i++){j++;$('.rating-star').append('<i class="fa start fa-star" data-value="'+j+'" aria-hidden="true"></i>');}
for(var i=0;i<5-value_star;i++){j++;$('.rating-star').append('<i class="fa start fa-star-o" data-value="'+j+'" aria-hidden="true"></i>');}});var boxParentFullHeight=$('.product-content-right').outerHeight();var boxParentWidth=$('.product-content-right').width();var boxChildOffset=$('.product-summary').offset();var startFix=boxChildOffset.top+150;$(window).scroll(function(){var yaBox=$('.fix-content-right').outerHeight();var endFix=startFix+yaBox-boxParentFullHeight;var fromTop=jQuery(document).scrollTop();if(fromTop>startFix){if(fromTop>endFix){var stopFix=yaBox-boxParentFullHeight;$('.product-summary').css({'position':'absolute','top':stopFix});}else{$('.product-summary').css({'position':'fixed','top':'10px','width':boxParentWidth});}}else{$('.product-summary').css({'position':'relative','top':'0','width':'100%'});}});})