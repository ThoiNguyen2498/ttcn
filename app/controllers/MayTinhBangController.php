<?php

class MayTinhBangController extends ControllerBase
{
	public function onConstruct(){
        parent::onConstruct();
        $headerCollection = $this->assets->collection('cssmtb');
        $headerCollection->addCss('tmobile/css/css-content.css');
        $headerCollection->addCss('tmobile/css/pages/category.css');

        $footerCollection = $this->assets->collection('jsmtb');
        // $footerCollection->addJs('tmobile/js/pages/category.js');
    }
    public function indexAction()
    {
        $get = $this->request->get();
        if (count($get) >1){
            $i=0;
            unset($get['_url']);
            $sqlTemp = "";
            $order="";
            if (isset($get['order'])){
                $order = $get['order'];
                $order = str_replace("_"," ",$order);
                unset($get['order']);
            }
            foreach ($get as $key=>$value){
                $key =  str_replace("_"," ",$key);
                if (strlen($sqlTemp) > 0){
                    if (strlen($value)<=0){
                        $sqlTemp = $sqlTemp." AND $key";
                    }else{
                        $sqlTemp = $sqlTemp." AND $key = '$value'";
                    }

                }else{
                    if (strlen($value)<=0){
                        $sqlTemp ="$key";
                    }else{
                        $sqlTemp ="$key = '$value'";
                    }
                }

            }
            if (strlen($sqlTemp)<=0){
                $sqlTemp ="loai = 1";
            }else{
                $sqlTemp = $sqlTemp." AND loai = 1";
            }
            $keyOrder="";
            if (strlen($order)>0){
                $keyOrder="order";
            }
            $dk =([
                $sqlTemp,
                "$keyOrder"=>"$order",
                'offset'=>0,
                'limit' => 20,
            ]);
            $this->view->setVar("test",$dk);
            $allDataDT=DienThoai::find($dk)->toArray();
        }else{
            $allDataDT= DienThoai::find([
                "loai = 1",
                'offset'=>0,
                'limit' => 20,
            ])->toArray();
        }
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
        $this->view->setVar("dataDT",$dataDT);
    }
    public function testAction(){
    	echo "ok";
    }
    public function loadListSPAction(){
        $this->view->setRenderLevel(1);

        $link= $_GET['link'];
        $link = explode("?",$link)[1];
        $truyVan = str_replace("%3C","<",$link);
        $truyVan = str_replace("%3E",">",$truyVan);
        $truyVan = str_replace("%27","'",$truyVan);
        $truyVan = str_replace("_"," ",$truyVan);
        $truyVan = explode("&order=",$truyVan);
        if (count($truyVan) <=1){
            $sapXep= $truyVan[0];
            $temp = explode("=",$sapXep);
            $order = $temp[0];
            $dieuKien = $temp[1];
            $truyVan="";
        }else{
            $order = "order";
            $dieuKien = $truyVan[1];
            $truyVan=$truyVan[0];
        }
        $truyVan = str_replace("&"," AND ",$truyVan);
        if (strlen($truyVan) <= 0){
            $truyVan = $truyVan." loai = '1'";
        }else{
            $truyVan = $truyVan." AND loai = '1'";
        }
        $tinMotTrang=20;
        $soTrang = $_GET['page'];
        $start = ($soTrang-1)*$tinMotTrang;
        $end = $start+$tinMotTrang-1;

        $allDataDT= DienThoai::find([
            $truyVan,
            $order=>$dieuKien,
            'offset'=>$start,
            'limit' => $end,
        ])->toArray();
        if (count($allDataDT) >0 ){
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
            $this->view->setVar("dataDT",$dataDT);
        }else{
            $this->view->setVar("ketQua","Bạn đã xem hết các sản phẩm máy tính bảng");
        }
    }
}

