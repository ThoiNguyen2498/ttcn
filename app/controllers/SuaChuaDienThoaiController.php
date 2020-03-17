<?php

class SuaChuaDienThoaiController extends ControllerBase
{
	public function onConstruct(){
        parent::onConstruct();
        $headerCollection = $this->assets->collection('cssscdt');
        $headerCollection->addCss('tmobile/css/css-content.css');
        $headerCollection->addCss('tmobile/css/pages/service.css');
        $headerCollection->addCss('tmobile/css/pages/product_services.css');

        $footerCollection = $this->assets->collection('jsscdt');
        $footerCollection->addJs('tmobile/js/pages/product_services.js');
        $footerCollection->addJs('tmobile/js/pages/service.js');
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
            $allDataSC=SanPhamSuaChua::find($dk)->toArray();
        }else{
            $allDataSC= SanPhamSuaChua::find([
                'offset'=>0,
                'limit' => 20,
            ])->toArray();
        }
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
        $this->view->setVar("dataSC",$dataSC);
    }
    public function testAction(){
    	echo "ok";
    }
    public function sanPhamAction($maSP){
        $data= SanPhamSuaChua::findFirst("maSP = '$maSP'");
        if ($data) {
            $data = $data->toArray();

            $listMauDB = $data['mauSac'];
            $listMauDB = explode(",", $listMauDB);
            $i = 0;
            foreach ($listMauDB as $item) {
                if (strlen(trim($item)) > 0) {
                    $listMau[$i] = str_replace("_", " ", $item);
                    $i++;
                }
            }

            $listGiaDB = $data["gia"];
            $listGiaDB = explode(",", $listGiaDB);
            $i = 0;
            foreach ($listGiaDB as $item) {
                if (strlen(trim($item)) > 0) {
                    $listGia[$i] = $item;
                    $i++;
                }
            }

            $listHinhDB = $data["hinhAnh"];
            $listHinhDB = explode(",", $listHinhDB);
            $i = 0;
            foreach ($listHinhDB as $item) {
                if (strlen(trim($item)) > 0) {
                    $listHinh[$i] = $item;
                    $i++;
                }
            }

            $listModelDB = $data["model"];
            $listModelDB = explode(",", $listModelDB);
            $i = 0;
            foreach ($listModelDB as $item) {
                if (strlen(trim($item)) > 0) {
                    $listModel[$i] = $item;
                    $i++;
                }
            }

            $this->view->setVar("data", $data);
            $this->view->setVar("listMau", $listMau);
            $this->view->setVar("listGia", $listGia);
            $this->view->setVar("listHinh", $listHinh);
            $this->view->setVar("listModel", $listModel);
        }

        $dataSCGY = $this->goiYSC($maSP,$data['model']);
        $this->view->setVar("dataSCGY",$dataSCGY);

        $dataPKGY = $this->goiYPK($listModel);
        $this->view->setVar("dataPKGY",$dataPKGY);

        $dataBV = BaiViet::findFirst("maSP = '$maSP'");
        if ($dataBV){
            $dataBV = $dataBV->toArray();
        }else{
            $tenSP = $data['tenSP'];
            $dataBV = BaiViet::findFirst("tenBaiViet LIKE '$tenSP'");
            if ($dataBV){
                $dataBV = $dataBV->toArray();
            }
        }
        if (is_array($dataBV)){
            $maBaiViet = $dataBV['maBaiViet'];
            $listNoiDung = ChiTietBaiViet::find([
                "maBaiViet = '$maBaiViet'",
                "order"=> "idBaiViet ASC",
            ])->toArray();
            $this->view->setVar("tenBV",$dataBV['tenBaiViet']);
            $this->view->setVar("baiViet",$listNoiDung);
        }
        if ($this->taiDanhGia("1",$maSP) != null){
            $dataDanhGia = $this->taiDanhGia("1",$maSP);
            $this->view->setVar("dataDanhGia",$dataDanhGia);
        }
        $this->view->setVar("thongKeDG",$this->thongKeDanhGia($maSP));
    }
    public function goiYSC($maSP,$model){
        $dataSC1 = SanPhamSuaChua::find([
            'conditions' => "model LIKE '%$model%'",
            "maSP != '$maSP'",
            'offset'=>0,
            'limit' => 4,
        ]);
        if ($dataSC1){
            $dataSC1 = $dataSC1->toArray();
            $k=0;
            for ($i=0;$i<count($dataSC1);$i++){
                $dataSCGY[$k] = ([
                    'maSP'=>$dataSC1[$i]['maSP'],
                    'tenSP'=>$dataSC1[$i]['tenSP'],
                    'hinhAnh'=>$dataSC1[$i]['hinhAnh'],
                    'gia'=>$dataSC1[$i]['gia'],
                ]);
                $k++;
            }
            return $dataSCGY;
        }else{
            return false;
        }
    }
    public function goiYPK($model){
        $count =4;
        for ($i=0;$i< count($model);$i++){
            $modelSS= $model[$i];
            $dataPK[$i]=PhuKien::find([
                'conditions' => "model LIKE '%$modelSS%'",
                'offset'=>0,
                'limit' => 4,
            ])->toArray();
        }
        $j=0;
        for ($i=0 ; $i< count($dataPK);$i++){
            foreach ($dataPK[$i] as $item){
                $dataPKGY[$j] = ([
                    'maSP'=>$item['maSP'],
                    'tenSP'=>$item['tenSP'],
                    'hinhAnh'=>$item['hinhAnh'],
                    'gia'=>$item['gia'],
                ]);
                $j++;
                if ($j>=5){
                    break;
                }
            }
            if ($j>=5){
                break;
            }
        }
        if (count($dataPKGY) < 4){
            $end = $count - count($dataPKGY);
            $dataTemp = PhuKien::find([
                'offset'=>0,
                'limit' => $end,
            ])->toArray();
            $vt = count($dataPKGY);
            $j=0;
            for ($i=$vt;$i<4;$i++){
                if ($dataTemp[$j]['maSP'] != null){
                    $dataPKGY[$i] = ([
                        'maSP' => $dataTemp[$j]['maSP'],
                        'tenSP' => $dataTemp[$j]['tenSP'],
                        'hinhAnh' => $dataTemp[$j]['hinhAnh'],
                        'gia' => $dataTemp[$j]['gia'],
                    ]);
                    $j++;
                }
            }
        }
        return $dataPKGY;
    }
    public function loadListSPAction(){
        $this->view->setRenderLevel(1);

        $link= $_GET['link'];
        $link = explode("?",$link)[1];
        $truyVan = str_replace("%3C","<",$link);
        $truyVan = str_replace("%3E",">",$truyVan);
        $truyVan = str_replace("%27","'",$truyVan);
        $truyVan = str_replace("_"," ",$truyVan);
        $truyVan = explode("&order",$truyVan)[0];
        $truyVan = str_replace("&"," AND ",$truyVan);

        $tinMotTrang=20;
        $soTrang = $_GET['page'];
        $start = ($soTrang-1)*$tinMotTrang;
        $end = $start+$tinMotTrang-1;

        $allDataSC= SanPhamSuaChua::find([
            $truyVan,
            'offset'=>$start,
            'limit' => $end,
        ])->toArray();
        if (count($allDataSC) >0 ){
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
            $this->view->setVar("dataSC",$dataSC);
        }else{
            $this->view->setVar("ketQua","Bạn đã xem hết các sản phẩm phụ kiện");
        }
    }
    public function taiDanhGia($trang,$maSP){
        $start = ($trang -1)*5;
        $end = 5;

        $dataDG = BaiDanhGia::find([
            "maSP ='$maSP'",
            "offset"=>$start,
            "limit"=>$end,
        ]);
        if ($dataDG){
            $dataDG = $dataDG->toArray();
            return $dataDG;
        }else{
            return null;
        }
    }
    public function thongKeDanhGia($maSP){
        $tongDG = BaiDanhGia::count(["maSP = '$maSP'"]);
        $danhGia5Sao = BaiDanhGia::count([
            "maSP ='$maSP' AND soSao = '5'",
        ]);
        $danhGia4Sao = BaiDanhGia::count([
            "maSP ='$maSP' AND soSao = '4'",
        ]);
        $danhGia3Sao = BaiDanhGia::count([
            "maSP ='$maSP' AND soSao = '3'",
        ]);
        $danhGia2Sao = BaiDanhGia::count([
            "maSP ='$maSP' AND soSao = '2'",
        ]);
        $danhGia1Sao = BaiDanhGia::count([
            "maSP ='$maSP' AND soSao = '1'",
        ]);
        $dataThongKeDG = ([
            "tong"=>$tongDG,
            "5Sao"=>$danhGia5Sao,
            "4Sao"=>$danhGia4Sao,
            "3Sao"=>$danhGia3Sao,
            "2Sao"=>$danhGia2Sao,
            "1Sao"=>$danhGia1Sao,
        ]);
        return $dataThongKeDG;
    }
}

