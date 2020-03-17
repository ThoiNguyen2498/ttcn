<?php

class DienThoaiController extends ControllerBase
{
	public function onConstruct(){
        parent::onConstruct();

        $headerCollection = $this->assets->collection('cssdt');
        $headerCollection->addCss('tmobile/css/css-content.css');
        $headerCollection->addCss('tmobile/css/pages/category.css');
        $headerCollection->addCss('tmobile/css/libs/lightslider.css');
        $headerCollection->addCss('tmobile/css/libs/lightgallery.min.css');
        $headerCollection->addCss('tmobile/css/pages/product.css');

        $footerCollection = $this->assets->collection('jsdt');
//         $footerCollection->addJs('tmobile/js/pages/category.js');
//         $footerCollection->addJs('tmobile/js/libs/jquery.simplePagination.js');
//         $footerCollection->addJs('tmobile/js/libs/lightslider.js');
//         $footerCollection->addJs('tmobile/js/libs/lightgallery-all.min.js');
//         $footerCollection->addJs('tmobile/js/pages/product.js');

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
                $sqlTemp ="loai = 0";
            }else{
                $sqlTemp = $sqlTemp." AND loai = 0";
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
                "loai = 0",
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
            $truyVan = $truyVan." loai = '0'";
        }else{
            $truyVan = $truyVan." AND loai = '0'";
        }


	    $tinMotTrang=20;
        $soTrang = $_GET['page'];
        $start = ($soTrang-1)*$tinMotTrang;
        $end = $start+$tinMotTrang-1;
//                var_dump($dieuKien);exit();
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
            $this->view->setVar("ketQua","Bạn đã xem hết các sản phẩm điện thoại");
        }
    }
    public function testAction(){
    	echo "ok";
    }
    public function sanPhamAction($maSP){
        $data= DienThoai::findFirst("maSP = '$maSP'");
        if ($data){
            $data= $data->toArray();

            $listMauDB = $data['mauSac'];
            $listMauDB = explode(",",$listMauDB);
            $i=0;
            foreach ($listMauDB as $item){
                if (strlen(trim($item)) > 0 ){
                    $listMau[$i] = str_replace("_"," ",$item);
                    $i++;
                }
            }

            $listGiaDB = $data["gia"];
            $listGiaDB = explode(",",$listGiaDB);
            $i=0;
            foreach ($listGiaDB as $item){
                if (strlen(trim($item)) > 0 ){
                    $listGia[$i] = $item;
                    $i++;
                }
            }

            $listHinhDB = $data["hinhAnh"];
            $listHinhDB = explode(",",$listHinhDB);
            $i=0;
            foreach ($listHinhDB as $item){
                if (strlen(trim($item)) > 0 ){
                    $listHinh[$i] = $item;
                    $i++;
                }
            }
            $this->view->setVar("data",$data);
            $this->view->setVar("listMau",$listMau);
            $this->view->setVar("listGia",$listGia);
            $this->view->setVar("listHinh",$listHinh);
        }

        $allDataGY=$this->goiYDT($listGia[0],$maSP);
        $i=0;
        foreach ($allDataGY as $item){
            $temp = $item['gia'];
            $temp= explode(",",$temp);
            $giaDaiDien = $temp[0];

            $temp = $item['hinhAnh'];
            $temp= explode(",",$temp);
            $hinhDaiDien = $temp[0];

            $dataGY[$i] = ([
                'maSP' => $item['maSP'],
                'tenSP' => $item['tenSP'],
                'hinhAnh' => $hinhDaiDien,
                'gia'=> $giaDaiDien
            ]);
            $i++;
        }
        $this->view->setVar("dataGY",$dataGY);

        $i=0;
        $allDataPKGY = $this->goiYPK($data['tenSP'],$data['jackTaiNghe'],$listGia[0]);
        foreach ($allDataPKGY as $item){
            $temp = $item['gia'];
            $temp= explode(",",$temp);
            $giaDaiDien = $temp[0];

            $temp = $item['hinhAnh'];
            $temp= explode(",",$temp);
            $hinhDaiDien = $temp[0];

            $dataPKGY[$i] = ([
                'maSP'=>$item['maSP'],
                'tenSP'=>$item['tenSP'],
                'gia'=>$giaDaiDien,
                'hinhAnh'=>$hinhDaiDien
            ]);
            $i++;
        }
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
    public function goiYDT($gia,$maSP){
	    $bienGia= $gia*20/100;

	    $giaMax = $gia+$bienGia;
	    $giaMin = $gia-$bienGia;

	    $dataDB = DienThoai::find([
	        "gia > '$giaMin' AND gia < '$giaMax' AND maSP != '$maSP'",
            'offset'=>0,
            'limit' => 3,
        ]);
	    if ($dataDB){
	        $dataDB = $dataDB->toArray();
	        if (count($dataDB) < 3){
                $dataDB = DienThoai::find([
                    "gia > '$gia' AND maSP != '$maSP'",
                    'offset'=>0,
                    'limit' => 3,
                ]);
                if ($dataDB){
                    $dataDB = $dataDB->toArray();
                }
            }
        }
	    return $dataDB;
    }
    public function goiYPK($tenSP,$jackTaiNghe,$gia){
        $cout = 4;
        $dataPK1 = PhuKien::findFirst([
            'conditions' => "tenSP LIKE '%$tenSP%'",
        ]);
        if ($dataPK1){
            $cout --;
            $dataPK1 = $dataPK1->toArray();
        }
        $cout2= $cout/2;
        $giaPKMax = $gia*15/100;
        $dataPK2 = PhuKien::find([
            'conditions' => "giaoTiep LIKE '%$jackTaiNghe%'",
            "gia < $giaPKMax",
            "order" => "gia DESC",
            'limit' => 2,
        ]);
        if ($dataPK2){
            $dataPK2 = $dataPK2->toArray();
            $cout= $cout - count($dataPK2);
        }else{
            $dataPK2 = PhuKien::find([
                'conditions' => "giaoTiep LIKE '%bluetooth%'",
                "gia < $giaPKMax",
                "order" => "gia DESC",
                'limit' => 2,
            ]);
            if ($dataPK2) {
                $dataPK2 = $dataPK2->toArray();
                $cout = $cout - count($dataPK2);
            }
        }
        $dataPK3 = PhuKien::find([
            "order" => "gia ASC",
            'limit' => $cout,
        ])->toArray();
        $k=0;
        if ($dataPK1){
            $dataPKGY[0] = ([
                'maSP'=>$dataPK1['maSP'],
                'tenSP'=>$dataPK1['tenSP'],
                'hinhAnh'=>$dataPK1['hinhAnh'],
                'gia'=>$dataPK1['gia'],
            ]);
            $k=1;
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

