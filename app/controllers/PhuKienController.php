<?php

class PhuKienController extends ControllerBase
{
	public function onConstruct(){
        parent::onConstruct();
        $headerCollection = $this->assets->collection('csspk');
        $headerCollection->addCss('tmobile/css/pages/accessories.css');
        $headerCollection->addCss('tmobile/css/css-content.css');
        $headerCollection->addCss('tmobile/css/libs/lightslider.css');
        $headerCollection->addCss('tmobile/css/libs/lightgallery.min.css');
        $headerCollection->addCss('tmobile/css/pages/product_accessories.css');

        $headerCollection->addCss('tmobile/css/pages/category.css');;

        $footerCollection = $this->assets->collection('jspk');
        $footerCollection->addJs('tmobile/js/pages/accessories.js');
//        $footerCollection->addJs('tmobile/js/pages/service.js');
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
            $allDataPK=PhuKien::find($dk)->toArray();
        }else{
            $allDataPK= PhuKien::find([
                'offset'=>0,
                'limit' => 20,
            ])->toArray();
        }

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
        $this->view->setVar("dataPK",$dataPK);
    }
    public function testAction(){
    	echo "ok";
    }
    public function sanPhamAction($maSP){
        $data= PhuKien::findFirst("maSP = '$maSP'");
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
            $this->view->setVar("data", $data);
            $this->view->setVar("listMau", $listMau);
            $this->view->setVar("listGia", $listGia);
            $this->view->setVar("listHinh", $listHinh);
        }

        $dataPKGY = $this->goiYPK($data['giaoTiep'],$data['model'],$listGia[0],$maSP);
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
    public function goiYPK($giaoTiep,$model,$gia,$maSP){
	    $count =4;

        $dataPK1 = PhuKien::find([
            "maSP != '$maSP'",
            'conditions' => "model LIKE '%$model%'",
            'offset'=>0,
            'limit' => 2,
        ]);
        if ($dataPK1){
            $dataPK1 = $dataPK1->toArray();
            $count = $count - count($dataPK1);
        }

        if ($count >= 3){
            $soL = 2;
        }else{
            $soL =1;
        }
        $dataPK2 = PhuKien::find([
            "maSP != '$maSP'",
            'conditions' => "giaoTiep LIKE '%$giaoTiep%'",
            'offset'=>0,
            'limit' => $soL,
        ]);
        if ($dataPK2){
            $dataPK2 = $dataPK2->toArray();
            $count = $count - count($dataPK2);
        }

        $bienGia= $gia*50/100;

        $giaMax = $gia+$bienGia;
        $giaMin = $gia-$bienGia;

        $dataPK3 = PhuKien::find([
            "gia > '$giaMin' AND gia < '$giaMax' AND maSP != '$maSP'",
            'offset'=>0,
            'limit' => $count,
        ]);
        if ($dataPK3){
            $dataPK3 = $dataPK3->toArray();
            $count = $count - count($dataPK3);
            if ($count >0){
                $dataPK3 = PhuKien::find([
                    "gia > '$gia' AND maSP != '$maSP'",
                    'offset'=>0,
                    'limit' => $count,
                ]);
                if ($dataPK3){
                    $dataPK3 = $dataPK3->toArray();
                }
            }
        }
        $k=0;
        for ($i=0;$i<count($dataPK1);$i++){
            $dataPKGY[$k] = ([
                'maSP'=>$dataPK1[$i]['maSP'],
                'tenSP'=>$dataPK1[$i]['tenSP'],
                'hinhAnh'=>$dataPK1[$i]['hinhAnh'],
                'gia'=>$dataPK1[$i]['gia'],
            ]);
            $k++;
        }
        for ($i=0;$i<count($dataPK2);$i++){
            $dataPKGY[$k] = ([
                'maSP'=>$dataPK2[$i]['maSP'],
                'tenSP'=>$dataPK2[$i]['tenSP'],
                'hinhAnh'=>$dataPK2[$i]['hinhAnh'],
                'gia'=>$dataPK2[$i]['gia'],
            ]);
            $k++;
        }
        for ($i=0;$i<count($dataPK3);$i++){
            $dataPKGY[$k] = ([
                'maSP'=>$dataPK3[$i]['maSP'],
                'tenSP'=>$dataPK3[$i]['tenSP'],
                'hinhAnh'=>$dataPK3[$i]['hinhAnh'],
                'gia'=>$dataPK3[$i]['gia'],
            ]);
            $k++;
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
        $tinMotTrang=20;
        $soTrang = $_GET['page'];
        $start = ($soTrang-1)*$tinMotTrang;
        $end = $start+$tinMotTrang-1;

        $allDataPK= PhuKien::find([
            $truyVan,
            $order=>$dieuKien,
            'offset'=>$start,
            'limit' => $end,
        ])->toArray();
        if (count($allDataPK) >0 ){
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
            $this->view->setVar("dataPK",$dataPK);
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

