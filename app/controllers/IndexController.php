<?php

class IndexController extends ControllerBase
{
	public function onConstruct(){
		parent::onConstruct();
        $footerCollection = $this->assets->collection('jsindex');
        $footerCollection->addJs('tmobile/js/pages/index_page.js');
        
    }

    public function indexAction()
    {
        $allDataDT= DienThoai::find([
            'offset'=>0,
            'limit' => 15,
        ])->toArray();
        $i=0;
        foreach ($allDataDT as $item){
            $imgDaiDien = explode(",",$item['hinhAnh']);
            $giaDaiDien= explode(",",$item['gia']);
            $dataDT[$i]=([
                'maSP'=>$item['maSP'],
                'hinhAnh'=>$imgDaiDien[0],
                'tenSP'=>$item['tenSP'],
                'gia'=>$giaDaiDien[0],
            ]);
            $i++;
        }

        $allDataPK= PhuKien::find([
            'offset'=>0,
            'limit' => 16,
        ])->toArray();
        $i=0;
        foreach ($allDataPK as $item){
            $imgDaiDien = explode(",",$item['hinhAnh']);
            $giaDaiDien= explode(",",$item['gia']);
            $dataPK[$i]=([
                'maSP'=>$item['maSP'],
                'hinhAnh'=>$imgDaiDien[0],
                'tenSP'=>$item['tenSP'],
                'gia'=>$giaDaiDien[0],
            ]);
            $i++;
        }

        $allDataSC= SanPhamSuaChua::find([
            'offset'=>0,
            'limit' => 15,
        ])->toArray();
        $i=0;
        foreach ($allDataSC as $item){
            $imgDaiDien = explode(",",$item['hinhAnh']);
            $giaDaiDien= explode(",",$item['gia']);
            $dataSC[$i]=([
                'maSP'=>$item['maSP'],
                'hinhAnh'=>$imgDaiDien[0],
                'tenSP'=>$item['tenSP'],
                'gia'=>$giaDaiDien[0],
            ]);
            $i++;
        }
        $this->view->setVar("dataDT",$dataDT);
        $this->view->setVar("dataPK",$dataPK);
        $this->view->setVar("dataSC",$dataSC);

        $dataBN = Banner::find([
            "order"=>"stt DESC",
        ])->toArray();
        $this->view->setVar("dataBN",$dataBN);
    }
    public function testAction(){
    	echo "ok";
    }
}

