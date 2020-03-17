<?php
    class TimKiemController extends ControllerBase{
        public function onConstruct(){
            parent::onConstruct();

            $headerCollection = $this->assets->collection('cssdt');
            $headerCollection->addCss('tmobile/css/css-content.css');
            $headerCollection->addCss('tmobile/css/pages/category.css');
            $headerCollection->addCss('tmobile/css/libs/lightslider.css');
            $headerCollection->addCss('tmobile/css/libs/lightgallery.min.css');
            $headerCollection->addCss('tmobile/css/pages/product.css');

        }
        public function indexAction(){
            $keyword = $_GET['keyword'];
            $this->view->setVar("keyword",$keyword);

            $temp = DienThoai::find([
                "tenSP LIKE '%$keyword%' OR maSP LIKE '%$keyword%'"
            ])->toArray();
            foreach ($temp as $i => $item){
                $hinhDaiDien = explode(",",$item['hinhAnh'])[0];
                $giaDaiDien = explode(",",$item['gia'])[0];
                $dataDT[$i] = ([
                    'maSP'=>$item['maSP'],
                    'tenSP'=>$item['tenSP'],
                    'hinhAnh'=>$hinhDaiDien,
                    'gia'=>$giaDaiDien,
                ]);
            }
            $this->view->setVar("dataDT",$dataDT);

            $temp = PhuKien::find([
                "tenSP LIKE '%$keyword%' OR maSP LIKE '%$keyword%'"
            ])->toArray();
            foreach ($temp as $i => $item){
                $hinhDaiDien = explode(",",$item['hinhAnh'])[0];
                $giaDaiDien = explode(",",$item['gia'])[0];
                $dataPK[$i] = ([
                    'maSP'=>$item['maSP'],
                    'tenSP'=>$item['tenSP'],
                    'hinhAnh'=>$hinhDaiDien,
                    'gia'=>$giaDaiDien,
                ]);
            }
            $this->view->setVar("dataPK",$dataPK);

            $temp = SanPhamSuaChua::find([
                "tenSP LIKE '%$keyword%' OR maSP LIKE '%$keyword%'  OR model LIKE '%$keyword%'  OR hang LIKE '%$keyword%'"
            ])->toArray();
            foreach ($temp as $i => $item){
                $hinhDaiDien = explode(",",$item['hinhAnh'])[0];
                $giaDaiDien = explode(",",$item['gia'])[0];
                $dataSC[$i] = ([
                    'maSP'=>$item['maSP'],
                    'tenSP'=>$item['tenSP'],
                    'hinhAnh'=>$hinhDaiDien,
                    'gia'=>$giaDaiDien,
                ]);
            }
            $this->view->setVar("dataSC",$dataSC);
        }
    }
?>