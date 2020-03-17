<?php

class TinTucController extends ControllerBase
{
	public function onConstruct(){
        parent::onConstruct();
        $headerCollection = $this->assets->collection('csstt');
        $headerCollection->addCss('tmobile/css/css-content.css');
        $headerCollection->addCss('tmobile/css/pages/news.css');

        $footerCollection = $this->assets->collection('jstt');
        $footerCollection->addJs('tmobile/js/pages/news.js');
    }

    public function indexAction()
    {
    }
    public function testAction(){
    	echo "ok";
    }
}

