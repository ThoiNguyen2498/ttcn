<?php
    class NguoiDungController extends ControllerBase{
        public function indexAction(){

        }
        public function datHangAction(){
            $ketQua=0;

            $post = $this->request->getPost();
            $this->view->setVar("post",$post);
            $maNguoiDung = $this->taoMaNguoiDung();
            $nguoiDung = ([
                'maNguoiDung'=>$maNguoiDung,
                'tenNguoiDung'=>$post['ten'],
                'sdt'=>$post['sdt'],
                'email'=>$post['email'],
                'diaChi'=>$post['diaChiNhanHang'],
            ]);
            $nd = new NguoiDung();
            if ($nd -> save($nguoiDung)){
            }else{
                $ketQua++;
            }

            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $day = date ("Y-m-d H:i:s", $timestamp = time());
            $danhMuc = $post['danhMuc'];
            $maSP= $post['maSP'];
            $dataSP=$danhMuc::findFirst("maSP = '$maSP'")->toArray();
            $maDonHang = "dh_".$maNguoiDung;
            $idMau = $post['mauSac'];
            $listGia = $dataSP['gia'];
            $listGia = explode(",",$listGia);
            $giaDT=0;
            for ($i=$idMau;$i>=0;$i--){
                $giaDT = (int)$listGia[$i];
                if ($giaDT > 0){
                    break;
                }
            }
            $baoHanh = (int)$post['cheDoBH'];
            $tongTien = $giaDT+$baoHanh;
            $donHang = ([
                'maDonHang'=>$maDonHang,
                'maNguoiDung'=>$maNguoiDung,
                'ngayDat'=>$day,
                'tongTien'=>$tongTien,
                'danhMuc'=>$post['danhMuc'],
                'ghiChu'=>$post['ghiChu'],
                'trangThai'=>"Chờ Duyệt"
            ]);
            $dh = new DonHang();
            $dh ->save($donHang);
            if (true){
            }else{
                $ketQua++;
            }

            $listMau = $dataSP['mauSac'];
            $listMau = explode(",",$listMau);
            $chiTietdh = ([
                'stt'=>"",
                'maDonHang'=>$maDonHang,
                'maSP'=>$maSP,
                'mauSac'=>$listMau[$idMau],
                'soLuong'=>1,
                'baoHanh'=>$baoHanh,
                'ngayDat'=>$day,
            ]);
            $ctdh = new ChiTietDonHang();
            if ($ctdh->save($chiTietdh)){
            }else{
                $ketQua++;
            }
            if ($ketQua == 0){
                $this->view->setVar("ketQua",$day);
            }
        }
        public function taoMaNguoiDung(){
            $allC="0123456789abcdefghijklmnopqrstuvwxyz";
            $maNguoiDung="";
            while (true){
                $maNguoiDung="nd_".substr(str_shuffle($allC), 0, 10);
                if (!NguoiDung::findFirst("maNguoiDung='$maNguoiDung'")){
                    break;
                }
            }
            return $maNguoiDung;
        }
        public function danhGiaAction(){
            $this->view->setRenderLevel(1);
            $soLuotMua =0;
            $post = $this->request->getPost();
            $sdt = (int)$post['sdt'];
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $now = getdate();
            $timeDG = date ("Y-m-d H:i:s", $now[0]);
            $timeMinInt = mktime($now['hours'],$now['minutes'],$now['seconds'],$now['mon'],$now['mday']-10,$now['year']);
            $timeMin = date ("Y-m-d H:i:s", $timeMinInt);
            $dataDN = DonHang::find([
                "ngayDat >= '$timeMin' AND trangThai = 'Xong'",
            ])->toArray();
            foreach ($dataDN as $item){
                $maNguoiDung = $item['maNguoiDung'];
                $temp = NguoiDung::findFirst("maNguoiDung = '$maNguoiDung'")->toArray();
                if ($sdt == $temp['sdt']){
                    $soLuotMua++;
                }
            }
            $soLuotDanhGia = BaiDanhGia::count("sdt ='$sdt'");
            if ($soLuotDanhGia < $soLuotMua){
                $dataNew =([
                    "stt"=>"",
                    "maSP"=>$post['maSP'],
                    "soSao"=>$post['soSao'],
                    "hoTen"=>$post['hoTen'],
                    "sdt"=>$sdt,
                    "noiDung"=>$post['noiDung'],
                    "ngayDang"=>$timeDG,
                ]);
                $bdg = new BaiDanhGia();
                if ($bdg ->save($dataNew)){
                    echo "Chúc mừng bạn đã đánh giá thành công!";
                }else{
                    echo "Có lỗi xảy ra. Hãy phản hồi ngay!";
                }
            }else{
                echo "Bạn đã đánh giá lượt mua gần nhất nên không thể thực hiện đánh giá!";
            }

        }
        public function datLichSuaAction(){
            $maSP = $_GET['maSP'];
            $data = SanPhamSuaChua::findFirst("maSP = '$maSP'")->toArray();
            $this->view->setVar("data",$data);

            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $day = date ("Y-m-d H:i:s", $timestamp = time());

            $post = $this->request->getPost();
            if (isset($post['datLich'])){
                $ngay = $post['ngay'];
                $gio = $post['gio'];
                $thoiGian = $ngay." ".$gio;

                $maNguoiDung = $this->taoMaNguoiDung();
                $dataND=([
                    "maNguoiDung"=>$maNguoiDung,
                    "tenNguoiDung"=>$post['tenNguoiDung'],
                    "sdt"=>$post['sdt'],
                    "email"=>$post['email'],
                ]);
                $nd = new NguoiDung();
                $nd->save($dataND);
                $dataDH=([
                    "maDonHangSua"=>"dt_dhs_".$maNguoiDung,
                    "maNguoiDung"=>$maNguoiDung,
                    "thoiGianHen"=>$thoiGian,
                    "maSP"=>$maSP,
                    "tongTien"=>$data['gia'],
                    "thoiGianDat"=>$day,
                    "trangThai"=>"Chờ Duyệt",
                ]);
                $dhs = new DonHangSua();
                if ($dhs->save($dataDH)){
                    $ketQua = "* Đặt lịch sửa chữa thành công!";
                }else{
                    $ketQua = "* Có lỗi! Đặt lịch sửa chữa thất bại!";
                }
                $this->view->setVar("ketQua",$ketQua);
            }
        }
        public function datGiuMayAction(){
            $maSP = $_GET['maSP'];
            $data = DienThoai::findFirst("maSP = '$maSP'")->toArray();
            $gia = explode(",",$data['gia'])[0];
            $hinhAnh = explode(",",$data['hinhAnh'])[0];
//            var_dump($gia);exit();
            $dataDT =([
                'tenSP'=>$data['tenSP'],
                'hinhAnh'=>$hinhAnh,
                'gia'=>$gia,
            ]);
            $this->view->setVar("data",$dataDT);

            $post = $this->request->getPost();
            if (isset($post['datGiuMay'])){
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $day = date ("Y-m-d H:i:s", $timestamp = time());

                $ngay = $post['ngay'];
                $gio = $post['gio'];
                $thoiGian = $ngay." ".$gio;

                $maNguoiDung = $this->taoMaNguoiDung();
                $dataND=([
                    "maNguoiDung"=>$maNguoiDung,
                    "tenNguoiDung"=>$post['tenNguoiDung'],
                    "sdt"=>$post['sdt'],
                    "email"=>$post['email'],
                ]);
                $nd = new NguoiDung();
                $nd->save($dataND);

                $dataDGM =([
                    "maDonGiuMay"=>"dgm_".$maNguoiDung,
                    "maNguoiDung"=>$maNguoiDung,
                    "thoiGianHen"=>$thoiGian,
                    "maSP"=>$maSP,
                    "tongTien"=>$gia,
                    "thoiGianDat"=>$day,
                    "trangThai"=>"Chờ Duyệt"
                ]);
                $dgm = new DonGiuMay();
                if ($dgm->save($dataDGM)){
                    $ketQua="* Đặt giữ máy thành công!";
                }else{
                    $ketQua="* Có lỗi! Đặt giữ máy thất bại!";
                }
                $this->view->setVar("ketQua",$ketQua);
            }
        }
    }
?>