<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    public function onConstruct(){
        $headerCollection = $this->assets->collection('header');
        $headerCollection->addCss('tmobile/css/libs/normalize.css');
        $headerCollection->addCss('tmobile/css/libs/component.css');
        $headerCollection->addCss('tmobile/css/style.css');
        $headerCollection->addCss('tmobile/css/libs/owl.carousel.min.css');
        $headerCollection->addCss('tmobile/css/pages/product-item.css');
        $headerCollection->addCss('tmobile/css/pages/service-item.css');
        $headerCollection->addCss('tmobile/css/pages/index_page.css');
        $headerCollection->addCss('tmobile/css/responsive.css');
        $headerCollection->addCss('tmobile/font-awesome/css/font-awesome.min.css');

        $footerCollection = $this->assets->collection('footer');
        $footerCollection->addJs('tmobile/js/libs/jquery.min.js');
        $footerCollection->addJs('tmobile/js/function.js');
        $footerCollection->addJs('tmobile/js/libs/modernizr.custom.js');
        $footerCollection->addJs('tmobile/js/libs/jquery.lazyload.min.js');
        $footerCollection->addJs('tmobile/js/libs/jquery.bpopup.min.js');
        $footerCollection->addJs('tmobile/js/libs/mlpushmenu.js');
        $footerCollection->addJs('tmobile/js/libs/classie.js');
        $footerCollection->addJs('tmobile/js/main.js');
        $footerCollection->addJs('tmobile/js/libs/owl.carousel.js');
        $footerCollection->addJs('tmobile/js/pages/category.js');

        $footerCollection->addJs('tmobile/js/libs/jquery.simplePagination.js');
        $footerCollection->addJs('tmobile/js/libs/lightslider.js');
        $footerCollection->addJs('tmobile/js/libs/lightgallery-all.min.js');
        $footerCollection->addJs('tmobile/js/pages/product.js');

        $footerCollection->addJs('tmobile/js/pages/product_accessories.js');

    }
}
