<?php

class NhanVienController extends ControllerBase
{
	public function onConstruct(){
		$this->view->setRenderLevel(4);
		$headerCollection = $this->assets->collection('admincss');
        $headerCollection->addCss('tmobile/css/admin/bootstrap.css');
        $headerCollection->addCss('tmobile/css/admin/cssCharts.css');
		$headerCollection->addCss('tmobile/css/admin/custom-styles.css');
        $headerCollection->addCss('tmobile/css/admin/fonts.googleapis.css');
		$headerCollection->addCss('tmobile/css/admin/morris-0.4.3.min.css');
        $headerCollection->addCss('tmobile/font-awesome/css/font-awesome.min.css');

        $footerCollection = $this->assets->collection('adminjs');
        $footerCollection->addJs('tmobile/js/admin/jquery-1.10.2.js');
        $footerCollection->addJs('tmobile/js/admin/bootstrap.min.js');
        $footerCollection->addJs('tmobile/js/admin/jquery.metisMenu.js');
        $footerCollection->addJs('tmobile/js/admin/raphael-2.1.0.min.js');
        $footerCollection->addJs('tmobile/js/admin/morris.js');
        $footerCollection->addJs('tmobile/js/admin/easypiechart.js');
        $footerCollection->addJs('tmobile/js/admin/easypiechart-data.js');
        $footerCollection->addJs('tmobile/js/admin/jquery.chart.js');
        $footerCollection->addJs('tmobile/js/admin/custom-scripts.js');
		$footerCollection->addJs('tmobile/js/admin/Chart.min.js');
		$footerCollection->addJs('tmobile/js/admin/chartjs.js');

        $footerCollection = $this->assets->collection('js');
        $footerCollection->addJs('tmobile/js/admin/libs/jquery.min.js');

        $soDonMoi = DonHang::count([
            "trangThai = 'Chờ Duyệt'",
        ]);
        $this->view->setVar("soDonMoi",$soDonMoi);

    }
    public function indexAction()
    {
    	$this->checkLogin();
    	$this->checkLv();

        $soLuongDH = $this->thongKeAllDonHang();

        $this->view->setVar("soLuongDH",$soLuongDH);

        $thongKe6Thang = $this->thongKeDonHangTheoThang();

        $this->view->setVar("thongKe6Thang",$thongKe6Thang);
        $listTK = $this->thongKeSPBanChayNhat();
        $i=0;
        $top10 = ([]);
        foreach ($listTK as $x=>$item){
            $data = DienThoai::findFirst("maSP = '$x'");
            if ($data){
                $data= $data->toArray();
            }else{
                $data = PhuKien::findFirst("maSP = '$x'");
                if ($data){
                    $data =$data->toArray();
                }else{
                    $data = SanPhamSuaChua::findFirst("maSP = '$x'")->toArray();
                }
            }
            $gia = explode(",",$data['gia']);
            $top10[$i] =([
                'maSP'=>$x,
                'tenSP'=>$data['tenSP'],
                'gia'=>$gia[0],
                'soLuongBan'=>$item,
            ]);
            if ($i >=9){
                break;
            }
            $i++;
        }
        $this->view->setVar("top10",$top10);

        $thongKeHT = $this->thongKeHoanThanhDonHang();
        $this->view->setVar("thongKeHT",$thongKeHT);
    }
    public function thongKeAllDonHang(){
        $count['all'] = DonHang::count("maDonHang != ''");
        $count['dienThoai'] = DonHang::count("danhMuc = 'DienThoai'");
        $count['phuKien'] = DonHang::count("danhMuc = 'PhuKien'");
        $count['suaChua'] = DonHang::count("danhMuc = 'SanPhamSuaChua'");
        return $count;
    }
    public function thongKeDonHangTheoThang(){

        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $now = getdate();
        for ($i=1;$i<=6;$i++){
            $timeMinInt = mktime(0,00,00,$now['mon']-(6-$i),1,$now['year']);
            $timeMaxInt = mktime(23,59,59,$now['mon']-(6-($i+1)),1,$now['year']);
            $timeMin = date('Y-m-d H:i:s', $timeMinInt);
            $timeMax = date('Y-m-d H:i:s', $timeMaxInt);

            $thang = date('m', $timeMinInt);
            $data[$thang] = ([
                'dienThoai'=>DonHang::count("danhMuc = 'DienThoai' AND ngayDat >'$timeMin' AND ngayDat <'$timeMax'"),
                'phuKien'=>DonHang::count("danhMuc = 'PhuKien' AND ngayDat >'$timeMin' AND ngayDat <'$timeMax'"),
                'suaChua'=>DonHang::count("danhMuc = 'SanPhamSuaChua' AND ngayDat >'$timeMin' AND ngayDat <'$timeMax'"),
            ]);
//            if ($i == 2){
//                var_dump($timeMax);exit();
//            }
        }
//        var_dump($data);exit();
        return $data;

    }
    public function thongKeSPBanChayNhat(){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $now = getdate();
        $timeMinInt = mktime($now['hours'],$now['minutes'],$now['seconds'],$now['mon']-1,$now['mday'],$now['year']);

        $timeMin = date('Y-m-d H:i:s', $timeMinInt);
        $timeMax = date('Y-m-d H:i:s', $now[0]);

        $dataAll = ChiTietDonHang::find(["ngayDat > '$timeMin' AND ngayDat < '$timeMax'"])->toArray();
        $listTenSP=([]);
        $i=0;
        $listTK=([]);
        foreach ($dataAll as $item){
            if (!in_array($item['maSP'],$listTenSP)){
                array_push($listTenSP,$item['maSP']);
                $listTK[$item['maSP']] =1;
            }else{
                $listTK[$item['maSP']]++;
            }
        }
        arsort($listTK);
        return $listTK;
    }
    public function thongKeHoanThanhDonHang(){
        $data['thanhCong'] = DonHang::count("trangThai = 'Xong'");
        $data['thatBai'] = DonHang::count("trangThai = 'Thất Bại'");
        $data['huy'] = DonHang::count("trangThai = 'Đã Hủy'");

        return $data;
    }
    public function testAction(){
    	echo "ok";
    }
    public function loginAction(){
    	$headerCollection = $this->assets->collection('css');
        $headerCollection->addCss('tmobile/css/admin/style.min.css');
        $headerCollection->addCss('tmobile/css/admin/style.css');
        $headerCollection->addCss('tmobile/font-awesome/css/font-awesome.min.css');

        $footerCollection = $this->assets->collection('js');
        $footerCollection->addJs('tmobile/js/admin/libs/jquery.min.js');
        $footerCollection->addJs('tmobile/js/admin/libs/bootstrap.min.js');
        $footerCollection->addJs('tmobile/js/admin/libs/popper.min.js');

        $this->view->setRenderLevel(2);

        $post = $this->request->getPost();
        if ($this->session->get('acc') != null){
            header("location: http://localhost/tmobile/nhan-vien");
        }
        if (isset($post["login"])) {
            $username = $post["username"];
            $password = $post["password"];
            $nhanVien = new NhanVien();
            $passwordMH= $nhanVien->maHoaMK($password);
            $dataDB = NhanVien::findFirst("username ='$username'");
            if ($dataDB){
                $dataNV = $dataDB->toArray();
                if ($dataNV["password"] == $passwordMH){
                    $acc = [
                        'username'=>$username,
                        'lv'=>$dataNV["lv"]
                    ];
                    $this->session->set('acc',$acc);
                    header("location: http://localhost/tmobile/nhan-vien");
                }else{
                    $this->view->setVar('ketQua',"*Tài khoản hoặc mật khẩu không chính xác!");
                }
            }else{
                $this->view->setVar('ketQua',"*Tài khoản hoặc mật khẩu không chính xác!");
            }
        }
    }
    public function themSPAction(){
        $this->checkLogin();
        $this->checkLv();

        $footerCollection = $this->assets->collection('js');
        $footerCollection->addJs('tmobile/js/admin/libs/jquery.min.js');

        $post = $this->request->getPost();
        if (isset($post["themSPDT"])){
            $xauMau= $post['mauSac'];
            $hinhAnh ="";
            if (strlen($xauMau)<=0){
                $duoiAnh = explode("/",$_FILES['file']['type']);
                $hinhAnh = $post['maSP'].".".$duoiAnh[1];
                move_uploaded_file($_FILES['file']['tmp_name'],"files/hinh-sp/".$hinhAnh);
                $this->view->setVar("ketQua",$duoiAnh[1]);
            }else{
                $listMauRaw= explode(",",$xauMau);
                $j=0;
                for($i=0;$i<count($listMauRaw);$i++){
                    if (strlen(trim($listMauRaw[$i]))>=1){
                        $listMau[$j] = str_replace(" ","_",trim($listMauRaw[$i])) ;
                        $j++;
                    }
                }
                for ($i=0; $i< count($listMau);$i++){
                    $tenThe = "file_".$listMau[$i];
                    $duoiAnh = explode("/",$_FILES[$tenThe]['type']);
                    $tenAnh = $post['maSP']."_".$listMau[$i].".".$duoiAnh[1];
                    move_uploaded_file($_FILES[$tenThe]['tmp_name'],"files/hinh-sp/".$tenAnh);
                    $hinhAnh = $hinhAnh.$tenAnh.",";
                }
                for ($i=0;$i<count($listMau);$i++){
                    $mauSac = $mauSac.$listMau[$i].",";
                }
            }
            $phanKhucGia = explode(",",$post['gia']);
            $phanKhucGia = $phanKhucGia[0];
            $data = array(
                'maSP' => $post["maSP"],
                'tenSP' => $post["tenSP"],
                'loai'=> $post['loai'],
                'hang' => $post["hang"],
                'soLuong' => (int)$post["soLuong"],
                'manHinh' => $post["manHinh"],
                'doRongManHinh'=> $post['doRongManHinh'],
                'heDieuHanh'=> $post["heDieuHanh"],
                'cameraSau' => $post["cameraSau"],
                'cameraTruoc'=> $post["cameraTruoc"],
                'cpu' => $post["cpu"],
                'ram'=> $post["ram"],
                'boNho' => $post["boNho"],
                'theSim'=> $post["theSim"],
                'pin' => $post["pin"],
                'ketNoi4G'=> $post["ketNoi4G"],
                'wifi' => $post["wifi"],
                'congSac'=> $post["congSac"],
                'jackTaiNghe' => $post["jackTaiNghe"],
                'mauSac'=> $mauSac,
                'gia' => $post["gia"],
                'phanKhuc'=>$phanKhucGia,
                'hinhAnh'=> $hinhAnh,
                'linkVideo'=>$post['linkVideo'],
            );
            $dt = new DienThoai();
            if ($dt->save($data)){
                $this->view->setVar("ketQua","Thêm mới thành công");
            }else{
                $this->view->setVar("ketQua","Có lỗi! Hãy thử nhập đầy đủ thông tin hơn.");
            }

        }


    }
    public function themPhuKienAction(){
        $this->checkLogin();
        $this->checkLv();

        $footerCollection = $this->assets->collection('js');
        $footerCollection->addJs('tmobile/js/admin/libs/jquery.min.js');

        $post = $this->request->getPost();
        if (isset($post["themSPPK"])){
            $xauMau= $post['mauSac'];
            $hinhAnh ="";
            if (strlen($xauMau)<=0){
                $duoiAnh = explode("/",$_FILES['file']['type']);
                $hinhAnh = 'pk_'.$post['maSP'].".".$duoiAnh[1];
                move_uploaded_file($_FILES['file']['tmp_name'],"files/hinh-sp/".$hinhAnh);
                $this->view->setVar("ketQua",$duoiAnh[1]);
            }else{
                $listMauRaw= explode(",",$xauMau);
                $j=0;
                for($i=0;$i<count($listMauRaw);$i++){
                    if (strlen(trim($listMauRaw[$i]))>=1){
                        $listMau[$j] = str_replace(" ","_",trim($listMauRaw[$i])) ;
                        $j++;
                    }
                }
                for ($i=0; $i< count($listMau);$i++){
                    $tenThe = "file_".$listMau[$i];
                    $duoiAnh = explode("/",$_FILES[$tenThe]['type']);
                    $tenAnh = 'pk_'.$post['maSP']."_".$listMau[$i].".".$duoiAnh[1];
                    move_uploaded_file($_FILES[$tenThe]['tmp_name'],"files/hinh-sp/".$tenAnh);
                    $hinhAnh = $hinhAnh.$tenAnh.",";
                }
                for ($i=0;$i<count($listMau);$i++){
                    $mauSac = $mauSac.$listMau[$i].",";
                }
            }
            $data = array(
                'maSP' => $post["maSP"],
                'tenSP' => $post["tenSP"],
                'loai'=> $post['loai'],
                'hang' => $post["hang"],
                'model'=> $post['model'],
                'soLuong' => (int)$post["soLuong"],
                'giaoTiep' => $post["giaoTiep"],
                'mauSac'=> $mauSac,
                'gia' => $post["gia"],
                'hinhAnh'=> $hinhAnh,
            );
            $pk = new PhuKien();
            if ($pk->save($data)){
                $this->view->setVar("ketQua","Thêm mới thành công");
            }else{
                $this->view->setVar("ketQua","Có lỗi! Hãy thử nhập đầy đủ thông tin hơn.");
            }

        }
    }
    public function themSuaChuaAction(){
        $this->checkLogin();
        $this->checkLv();

        $footerCollection = $this->assets->collection('js');
        $footerCollection->addJs('tmobile/js/admin/libs/jquery.min.js');

        $post = $this->request->getPost();
        if (isset($post["themSPSC"])){
            $xauMau= $post['mauSac'];
            $hinhAnh ="";
            if (strlen($xauMau)<=0){
                $duoiAnh = explode("/",$_FILES['file']['type']);
                $hinhAnh = 'sc_'.$post['maSP'].".".$duoiAnh[1];
                move_uploaded_file($_FILES['file']['tmp_name'],"files/hinh-sp/".$hinhAnh);
                $this->view->setVar("ketQua",$duoiAnh[1]);
            }else{
                $listMauRaw= explode(",",$xauMau);
                $j=0;
                for($i=0;$i<count($listMauRaw);$i++){
                    if (strlen(trim($listMauRaw[$i]))>=1){
                        $listMau[$j] = str_replace(" ","_",trim($listMauRaw[$i])) ;
                        $j++;
                    }
                }
                for ($i=0; $i< count($listMau);$i++){
                    $tenThe = "file_".$listMau[$i];
                    $duoiAnh = explode("/",$_FILES[$tenThe]['type']);
                    $tenAnh = 'sc_'.$post['maSP']."_".$listMau[$i].".".$duoiAnh[1];
                    move_uploaded_file($_FILES[$tenThe]['tmp_name'],"files/hinh-sp/".$tenAnh);
                    $hinhAnh = $hinhAnh.$tenAnh.",";
                }
                for ($i=0;$i<count($listMau);$i++){
                    $mauSac = $mauSac.$listMau[$i].",";
                }
            }
            $data = array(
                'maSP' => $post["maSP"],
                'tenSP' => $post["tenSP"],
                'model' => $post["model"],
                'hang' => $post["hang"],
                'soLuong' => (int)$post["soLuong"],
                'mauSac'=> "1",
                'gia' => (int)$post["gia"],
                'hinhAnh'=> $hinhAnh,
            );
            $spsc = new SanPhamSuaChua();
            if ($spsc->save($data)){
                $this->view->setVar("ketQua","Thêm mới thành công");
            }else{
                $this->view->setVar("ketQua","Có lỗi! Hãy thử nhập đầy đủ thông tin hơn.");
            }

        }
    }
    public function themSPExcelAction(){
        $this->checkLogin();
        $this->checkLv();
        require_once (__DIR__ . '/../library/Classes/PHPExcel.php');

        $post = $this->request->getPost();
        if (isset($post['themSP'])){
            $danhMuc = $post['danhMuc'];
            $file = $_FILES['fileUpload']['tmp_name'];
            $obj =PHPExcel_IOFactory::createReaderForFile($file);
            $obj->setLoadSheetsOnly("Sheet1");
            $excel = $obj->load($file);
            $data = $excel->getActiveSheet()->toArray();
            $imax = $excel->setActiveSheetIndex()->getHighestRow();

            $listSPLoi =([]);
            $soSPTC = 0;
            if ($danhMuc == "DienThoai"){
                for ($i=1;$i<$imax;$i++){
                    $maSP = $data[$i][0];
                    if (DienThoai::findFirst("maSP = '$maSP'")){
                        $maSP = $maSP."-01";
                    }
                    $phanKhucGia = explode(",",$data[$i][20]);
                    $phanKhucGia = $phanKhucGia[0];
                    $dataNew = array(
                        'maSP' => $maSP,
                        'tenSP' => $data[$i][1],
                        'loai'=> $data[$i][2],
                        'hang' => $data[$i][3],
                        'soLuong' => (int)$data[$i][4],
                        'manHinh' => $data[$i][5],
                        'doRongManHinh'=> $data[$i][6],
                        'heDieuHanh'=> $data[$i][7],
                        'cameraSau' => $data[$i][8],
                        'cameraTruoc'=> $data[$i][9],
                        'cpu' => $data[$i][10],
                        'ram'=> $data[$i][11],
                        'boNho' => $data[$i][12],
                        'theSim'=> $data[$i][13],
                        'pin' => $data[$i][14],
                        'ketNoi4G'=> $data[$i][15],
                        'wifi' => $data[$i][16],
                        'congSac'=> $data[$i][17],
                        'jackTaiNghe' => $data[$i][18],
                        'mauSac'=> $data[$i][19],
                        'gia' => $data[$i][20],
                        'phanKhuc'=>$phanKhucGia,
                        'hinhAnh'=> $data[$i][21],
                        'linkVideo'=>$data[$i][22],
                    );
                    $dt = new DienThoai();
                    if ($dt->save($dataNew)){
                        $soSPTC++;
                    }else{
                        array_push($listSPLoi,$dataNew);
                    }
                }
            }else{
                if ($danhMuc == "PhuKien"){
                    for ($i=1;$i<$imax;$i++){
                        $maSP = $data[$i][0];
                        if (PhuKien::findFirst("maSP = '$maSP'")){
                            $maSP = $maSP."-01";
                        }
                        $dataNew = array(
                            'maSP' => $maSP,
                            'tenSP' => $data[1],
                            'loai'=> $data[2],
                            'hang' => $data[3],
                            'model'=> $data[4],
                            'soLuong' => (int)$data[5],
                            'giaoTiep' => $data[6],
                            'mauSac'=> $data[7],
                            'gia' => $data[8],
                            'hinhAnh'=> $data[9],
                        );
                        $pk = new PhuKien();
                        if ($pk->save($dataNew)){
                            $soSPTC++;
                        }else{
                            array_push($listSPLoi,$dataNew);
                        }
                    }
                }else{
                    for ($i=1;$i<$imax;$i++){
                        $maSP = $data[$i][0];
                        if (SanPhamSuaChua::findFirst("maSP = '$maSP'")){
                            $maSP = $maSP."-01";
                        }
                        $dataNew = array(
                            'maSP' => $maSP,
                            'tenSP' => $data[$i][1],
                            'model' => $data[$i][2],
                            'hang' => $data[$i][3],
                            'soLuong' => (int)$data[$i][4],
                            'mauSac'=> $data[$i][5],
                            'gia' => $data[$i][6],
                            'hinhAnh'=> $data[$i][7],
                        );
//                        var_dump($dataNew);exit();
                        $spsc = new SanPhamSuaChua();
                        if ($spsc->save($dataNew)){
                            $soSPTC++;
                        }else{
                            array_push($listSPLoi,$dataNew);
                        }
                    }
                }
            }
            $tong = $imax-1;
            $ketQua = "Thêm vào thành công ".$soSPTC." sản phẩm trong tổng số ".$tong." sản phẩm.";
            $this->view->setVar("ketQua",$ketQua);
            $this->view->setVar("listSPLoi",$listSPLoi);
        }
    }
    public function showSPAction(){
        $this->checkLogin();
        $this->checkLv();

        if (isset($_GET['danhMuc'])){
            $danhMuc = $_GET["danhMuc"];
        }else{
            $danhMuc = "DienThoai";
            $indexMenu="DT";
        }
        if ($danhMuc == "PhuKien"){
            $indexMenu="PK";
        }
        if ($danhMuc == "SanPhamSuaChua"){
            $indexMenu="SC";
        }
        $ob= new $danhMuc;
        $dataALl = $danhMuc::find([
            'offset'=>0,
            'limit' => 25,
        ])->toArray();
        $i=0;
        foreach ($dataALl as $item){
            $listImg = explode(",",$item['hinhAnh']);
            $data[$i]= ([
                'hinhAnh'=> $listImg[0],
                'maSP'=> $item['maSP'],
                'tenSP'=> $item['tenSP'],
                'soLuong'=> $item['soLuong'],
                'gia'=> $item['gia'],
            ]);
            $i++;
        }
        $this->view->setVar("data",$data);
        $this->view->setVar("indexMenu",$indexMenu);
    }
    public function showTaiKhoanAction(){
        $this->checkLogin();
        $this->checkLv();
        $data = NhanVien::find([
            "lv > 1"
        ])->toArray();
        $this->view->setVar("data",$data);

    }
    public function taoTaiKhoanAction(){
        $this->checkLogin();
        $this->checkLv();

        $post = $this->request->getPost();
        if (isset($post['taoTaiKhoan'])){
            $qtv = new NhanVien();
            $passwordQTV =$qtv->maHoaMK($post['passwordQTV']);
            $ses = $this->session->get('acc');
            $username= $ses['username'];
            $data = NhanVien::findFirst("username = '$username'");
            if ($data){
                $data = $data->toArray();
                if ($passwordQTV == $data['password']){
                    $dataAcc = ([
                        'tenNV'=>$post['tenNV'],
                        'namSinh'=>$post['namSinh'],
                        'gioiTinh'=>$post['gioiTinh'],
                        'sdt'=>$post['sdt'],
                        'email'=>$post['email'],
                        'lv'=>2,
                        'username'=>$post['username'],
                        'password'=>$qtv->maHoaMK($post['password'])
                    ]);
                    $nv = new NhanVien();
                    if ($nv->save($dataAcc)){
                        $this->view->setVar("ketQua","*Tạo tài khoản mới thành công!");
                    }else{
                        $this->view->setVar("ketQua","*Có lỗi nào đó xảy ra");
                    }
                }else{
                    $this->view->setVar("ketQua","*Mật khẩu quản trị viên không chính xác!");
                }
            }

        }
    }
    public function xemThongKeAction(){
        $this->checkLogin();
        $this->checkLv();

    }
    public function showAllDonHangAction(){
        $this->checkLogin();
        $data = DonHang::find([
            'offset'=>0,
            'limit' => 30,
            "order"=>"ngayDat DESC",
        ])->toArray();
        foreach ($data as $x => $item){
            $maDonHang = $item["maDonHang"];
            $danhMuc = $item['danhMuc'];
            $dataCTDH = ChiTietDonHang::findFirst("maDonHang = '$maDonHang'")->toArray();
            $maSP= $dataCTDH['maSP'];
            $dataSP = $danhMuc::findFirst("maSP = '$maSP'")->toArray();
            $maNguoiDung = $item['maNguoiDung'];
            $dataND = NguoiDung::findFirst("maNguoiDung = '$maNguoiDung'")->toArray();

            $dataDH[$x] = ([
                'maDonHang'=>$maDonHang,
                'tenSP'=>$dataSP['tenSP'],
                'soLuong'=>$dataCTDH['soLuong'],
                'tongTien'=>$item['tongTien'],
                'diaChi'=>$dataND['diaChi'],
                'tenNguoiDung'=>$dataND['tenNguoiDung'],
                'trangThai'=>$item['trangThai'],
            ]);
        }
        $this->view->setVar("dataDH",$dataDH);
    }
    public function showDonHangMoiAction(){
        $this->checkLogin();
        $data = DonHang::find([
            "trangThai = 'Chờ duyệt'",
            'offset'=>0,
            'limit' => 30,
            "order"=>"ngayDat DESC",
        ])->toArray();
        foreach ($data as $x => $item){
            $maDonHang = $item["maDonHang"];
            $danhMuc = $item['danhMuc'];
            $dataCTDH = ChiTietDonHang::findFirst("maDonHang = '$maDonHang'")->toArray();
            $maSP= $dataCTDH['maSP'];
            $dataSP = $danhMuc::findFirst("maSP = '$maSP'")->toArray();
            $maNguoiDung = $item['maNguoiDung'];
            $dataND = NguoiDung::findFirst("maNguoiDung = '$maNguoiDung'")->toArray();

            $dataDH[$x] = ([
                'maDonHang'=>$maDonHang,
                'tenSP'=>$dataSP['tenSP'],
                'soLuong'=>$dataCTDH['soLuong'],
                'tongTien'=>$item['tongTien'],
                'diaChi'=>$dataND['diaChi'],
                'tenNguoiDung'=>$dataND['tenNguoiDung'],
            ]);
        }
        $this->view->setVar("dataDH",$dataDH);
    }
    public function showDonDaNhanAction(){
        $this->checkLogin();
        $data = DonHang::find([
            "trangThai = 'Đã nhận'",
            'offset'=>0,
            'limit' => 30,
            "order"=>"ngayDat DESC",
        ])->toArray();
        foreach ($data as $x => $item){
            $maDonHang = $item["maDonHang"];
            $danhMuc = $item['danhMuc'];
            $dataCTDH = ChiTietDonHang::findFirst("maDonHang = '$maDonHang'")->toArray();
            $maSP= $dataCTDH['maSP'];
            $dataSP = $danhMuc::findFirst("maSP = '$maSP'")->toArray();
            $maNguoiDung = $item['maNguoiDung'];
            $dataND = NguoiDung::findFirst("maNguoiDung = '$maNguoiDung'")->toArray();

            $dataDH[$x] = ([
                'maDonHang'=>$maDonHang,
                'tenSP'=>$dataSP['tenSP'],
                'soLuong'=>$dataCTDH['soLuong'],
                'tongTien'=>$item['tongTien'],
                'diaChi'=>$dataND['diaChi'],
                'tenNguoiDung'=>$dataND['tenNguoiDung'],
            ]);
        }
        $this->view->setVar("dataDH",$dataDH);
    }
    public function showDonDangGiaoAction(){
        $this->checkLogin();
        $data = DonHang::find([
            "trangThai = 'Đang giao'",
            'offset'=>0,
            'limit' => 30,
            "order"=>"ngayDat DESC",
        ])->toArray();
        foreach ($data as $x => $item){
            $maDonHang = $item["maDonHang"];
            $danhMuc = $item['danhMuc'];
            $dataCTDH = ChiTietDonHang::findFirst("maDonHang = '$maDonHang'")->toArray();
            $maSP= $dataCTDH['maSP'];
            $dataSP = $danhMuc::findFirst("maSP = '$maSP'")->toArray();
            $maNguoiDung = $item['maNguoiDung'];
            $dataND = NguoiDung::findFirst("maNguoiDung = '$maNguoiDung'")->toArray();

            $dataDH[$x] = ([
                'maDonHang'=>$maDonHang,
                'tenSP'=>$dataSP['tenSP'],
                'soLuong'=>$dataCTDH['soLuong'],
                'tongTien'=>$item['tongTien'],
                'diaChi'=>$dataND['diaChi'],
                'tenNguoiDung'=>$dataND['tenNguoiDung'],
            ]);
        }
        $this->view->setVar("dataDH",$dataDH);
    }
    public function showDonDaGiaoAction(){
        $this->checkLogin();
        $data = DonHang::find([
            "trangThai = 'Xong'",
            'offset'=>0,
            'limit' => 30,
            "order"=>"ngayDat DESC",
        ])->toArray();
        foreach ($data as $x => $item){
            $maDonHang = $item["maDonHang"];
            $danhMuc = $item['danhMuc'];
            $dataCTDH = ChiTietDonHang::findFirst("maDonHang = '$maDonHang'")->toArray();
            $maSP= $dataCTDH['maSP'];
            $dataSP = $danhMuc::findFirst("maSP = '$maSP'")->toArray();
            $maNguoiDung = $item['maNguoiDung'];
            $dataND = NguoiDung::findFirst("maNguoiDung = '$maNguoiDung'")->toArray();

            $dataDH[$x] = ([
                'maDonHang'=>$maDonHang,
                'tenSP'=>$dataSP['tenSP'],
                'soLuong'=>$dataCTDH['soLuong'],
                'tongTien'=>$item['tongTien'],
                'diaChi'=>$dataND['diaChi'],
                'tenNguoiDung'=>$dataND['tenNguoiDung'],
            ]);
        }
        $this->view->setVar("dataDH",$dataDH);

    }
    public function showDonThatBaiAction(){
        $this->checkLogin();
        $data = DonHang::find([
            "trangThai = 'Thất bại'",
            'offset'=>0,
            'limit' => 30,
            "order"=>"ngayDat DESC",
        ])->toArray();
        foreach ($data as $x => $item){
            $maDonHang = $item["maDonHang"];
            $danhMuc = $item['danhMuc'];
            $dataCTDH = ChiTietDonHang::findFirst("maDonHang = '$maDonHang'")->toArray();
            $maSP= $dataCTDH['maSP'];
            $dataSP = $danhMuc::findFirst("maSP = '$maSP'")->toArray();
            $maNguoiDung = $item['maNguoiDung'];
            $dataND = NguoiDung::findFirst("maNguoiDung = '$maNguoiDung'")->toArray();

            $dataDH[$x] = ([
                'maDonHang'=>$maDonHang,
                'tenSP'=>$dataSP['tenSP'],
                'soLuong'=>$dataCTDH['soLuong'],
                'tongTien'=>$item['tongTien'],
                'diaChi'=>$dataND['diaChi'],
                'tenNguoiDung'=>$dataND['tenNguoiDung'],
            ]);
        }
        $this->view->setVar("dataDH",$dataDH);
    }
    public function showDonDaHuyAction(){
        $this->checkLogin();
        $data = DonHang::find([
            "trangThai = 'Đã Hủy'",
            'offset'=>0,
            'limit' => 30,
            "order"=>"ngayDat DESC",
        ])->toArray();
        foreach ($data as $x => $item){
            $maDonHang = $item["maDonHang"];
            $danhMuc = $item['danhMuc'];
            $dataCTDH = ChiTietDonHang::findFirst("maDonHang = '$maDonHang'")->toArray();
            $maSP= $dataCTDH['maSP'];
            $dataSP = $danhMuc::findFirst("maSP = '$maSP'")->toArray();
            $maNguoiDung = $item['maNguoiDung'];
            $dataND = NguoiDung::findFirst("maNguoiDung = '$maNguoiDung'")->toArray();

            $dataDH[$x] = ([
                'maDonHang'=>$maDonHang,
                'tenSP'=>$dataSP['tenSP'],
                'soLuong'=>$dataCTDH['soLuong'],
                'tongTien'=>$item['tongTien'],
                'diaChi'=>$dataND['diaChi'],
                'tenNguoiDung'=>$dataND['tenNguoiDung'],
            ]);
        }
        $this->view->setVar("dataDH",$dataDH);
    }
    public function xemTaiLieuAction(){
        $this->checkLogin();

    }
    public function suaThongTinDTAction(){
        $maSP= $_GET['maSP'];
        $data = DienThoai::findFirst("maSP='$maSP'")->toArray();
        $this->view->setVar("data",$data);

        $post= $this->request->getPost();
        if (isset($post['luuThongTin'])){

            $xauMau= $post['mauSac'];
            $hinhAnh ="";
            if (strlen($xauMau)<=0){
                if (strlen($_FILES['file']['type'])>0){
                    $duoiAnh = explode("/",$_FILES['file']['type']);
                    $hinhAnh = $data['maSP'].".".$duoiAnh[1];
                    move_uploaded_file($_FILES['file']['tmp_name'],"files/hinh-sp/".$hinhAnh);
                }else{
                    $hinhAnh = $data['hinhAnh'];
                }
            }else{
                $listMauRaw= explode(",",$xauMau);
                $j=0;
                for($i=0;$i<count($listMauRaw);$i++){
                    if (strlen(trim($listMauRaw[$i]))>=1){
                        $listMau[$j] = str_replace(" ","_",trim($listMauRaw[$i])) ;
                        $j++;
                    }
                }
                for ($i=0; $i< count($listMau);$i++){
                    $tenThe = "file_".$listMau[$i];
                    if (strlen($_FILES[$tenThe]['type'])>0){
                        $duoiAnh = explode("/",$_FILES[$tenThe]['type']);
                        $tenAnh = $data['maSP']."_".$listMau[$i].".".$duoiAnh[1];
                        move_uploaded_file($_FILES[$tenThe]['tmp_name'],"files/hinh-sp/".$tenAnh);
                        $hinhAnh = $hinhAnh.$tenAnh.",";
                    }else{
                        $hinhAnh = $data['hinhAnh'];
                    }
                }
                for ($i=0;$i<count($listMau);$i++){
                    $mauSac = $mauSac.$listMau[$i].",";
                }
            }
            $phanKhucGia = explode(",",$post['gia']);
            $phanKhucGia = $phanKhucGia[0];
            $dataNew = array(
                'maSP' => $data['maSP'],
                'tenSP' => $post["tenSP"],
                'loai'=> $post['loai'],
                'hang' => $post["hang"],
                'soLuong' => (int)$post["soLuong"],
                'manHinh' => $post["manHinh"],
                'doRongManHinh'=> $post['doRongManHinh'],
                'heDieuHanh'=> $post["heDieuHanh"],
                'cameraSau' => $post["cameraSau"],
                'cameraTruoc'=> $post["cameraTruoc"],
                'cpu' => $post["cpu"],
                'ram'=> $post["ram"],
                'boNho' => $post["boNho"],
                'theSim'=> $post["theSim"],
                'pin' => $post["pin"],
                'ketNoi4G'=> $post["ketNoi4G"],
                'wifi' => $post["wifi"],
                'congSac'=> $post["congSac"],
                'jackTaiNghe' => $post["jackTaiNghe"],
                'mauSac'=> $mauSac,
                'gia' =>$post["gia"],
                'phanKhuc'=>$phanKhucGia,
                'hinhAnh'=> $hinhAnh,
                'linkVideo'=>$post['linkVideo']
            );
            $dt = new DienThoai();
            if ($dt->save($dataNew)){
                $this->view->setVar("ketQua","Chỉnh sửa thành công");
            }else{
                $this->view->setVar("ketQua","Có lỗi! Hãy thử nhập đầy đủ thông tin hơn.");
            }
        }
    }
    public function suaThongTinPKAction(){
        $maSP= $_GET['maSP'];
        $data = PhuKien::findFirst("maSP='$maSP'")->toArray();
        $this->view->setVar("data",$data);

        $post= $this->request->getPost();
        if (isset($post['luuThongTin'])){

            $xauMau= $post['mauSac'];
            $hinhAnh ="";
            if (strlen($xauMau)<=0){
                if (strlen($_FILES['file']['type'])>0){
                    $duoiAnh = explode("/",$_FILES['file']['type']);
                    $hinhAnh = 'pk_'.$data['maSP'].".".$duoiAnh[1];
                    move_uploaded_file($_FILES['file']['tmp_name'],"files/hinh-sp/".$hinhAnh);
                }else{
                    $hinhAnh = $data['hinhAnh'];
                }
            }else{
                $listMauRaw= explode(",",$xauMau);
                $j=0;
                for($i=0;$i<count($listMauRaw);$i++){
                    if (strlen(trim($listMauRaw[$i]))>=1){
                        $listMau[$j] = str_replace(" ","_",trim($listMauRaw[$i])) ;
                        $j++;
                    }
                }
                for ($i=0; $i< count($listMau);$i++){
                    $tenThe = "file_".$listMau[$i];
                    if (strlen($_FILES[$tenThe]['type'])>0){
                        $duoiAnh = explode("/",$_FILES[$tenThe]['type']);
                        $tenAnh = 'pk_'.$data['maSP']."_".$listMau[$i].".".$duoiAnh[1];
                        move_uploaded_file($_FILES[$tenThe]['tmp_name'],"files/hinh-sp/".$tenAnh);
                        $hinhAnh = $hinhAnh.$tenAnh.",";
                    }else{
                        $hinhAnh = $data['hinhAnh'];
                    }
                }
                for ($i=0;$i<count($listMau);$i++){
                    $mauSac = $mauSac.$listMau[$i].",";
                }
            }
            $dataNew = array(
                'maSP' => $data['maSP'],
                'tenSP' => $post["tenSP"],
                'loai' => $post['loai'],
                'hang' => $post["hang"],
                'model'=> $post['model'],
                'soLuong' => (int)$post["soLuong"],
                'giaoTiep' => $post["giaoTiep"],
                'mauSac'=> $mauSac,
                'gia' => (int)$post["gia"],
                'hinhAnh'=> $hinhAnh,
            );
            $dt = new PhuKien();
            if ($dt->save($dataNew)){
                $this->view->setVar("ketQua","Chỉnh sửa thành công");
            }else{
                $this->view->setVar("ketQua","Có lỗi! Hãy thử nhập đầy đủ thông tin hơn.");
            }
        }
    }
    public function suaThongTinSCAction(){
        $maSP= $_GET['maSP'];
        $data = SanPhamSuaChua::findFirst("maSP='$maSP'")->toArray();
        $this->view->setVar("data",$data);

        $post= $this->request->getPost();
        if (isset($post['luuThongTin'])){

            $xauMau= $post['mauSac'];
            $hinhAnh ="";
            if (strlen($xauMau)<=0){
                if (strlen($_FILES['file']['type'])>0){
                    $duoiAnh = explode("/",$_FILES['file']['type']);
                    $hinhAnh = 'sc_'.$data['maSP'].".".$duoiAnh[1];
                    move_uploaded_file($_FILES['file']['tmp_name'],"files/hinh-sp/".$hinhAnh);
                }else{
                    $hinhAnh = $data['hinhAnh'];
                }
            }else{
                $listMauRaw= explode(",",$xauMau);
                $j=0;
                for($i=0;$i<count($listMauRaw);$i++){
                    if (strlen(trim($listMauRaw[$i]))>=1){
                        $listMau[$j] = str_replace(" ","_",trim($listMauRaw[$i])) ;
                        $j++;
                    }
                }
                for ($i=0; $i< count($listMau);$i++){
                    $tenThe = "file_".$listMau[$i];
                    if (strlen($_FILES[$tenThe]['type'])>0){
                        $duoiAnh = explode("/",$_FILES[$tenThe]['type']);
                        $tenAnh = 'sc_'.$data['maSP']."_".$listMau[$i].".".$duoiAnh[1];
                        move_uploaded_file($_FILES[$tenThe]['tmp_name'],"files/hinh-sp/".$tenAnh);
                        $hinhAnh = $hinhAnh.$tenAnh.",";
                    }else{
                        $hinhAnh = $data['hinhAnh'];
                    }
                }
                for ($i=0;$i<count($listMau);$i++){
                    $mauSac = $mauSac.$listMau[$i].",";
                }
            }
            $dataNew = array(
                'maSP' => $data['maSP'],
                'tenSP' => $post["tenSP"],
                'model' => $post["model"],
                'hang' => $post["hang"],
                'soLuong' => (int)$post["soLuong"],
                'mauSac'=> $mauSac,
                'gia' => (int)$post["gia"],
                'hinhAnh'=> $hinhAnh,
            );
            $dt = new SanPhamSuaChua();
            if ($dt->save($dataNew)){
                $this->view->setVar("ketQua","Chỉnh sửa thành công");
            }else{
                $this->view->setVar("ketQua","Có lỗi! Hãy thử nhập đầy đủ thông tin hơn.");
            }
        }
    }
    public function xoaSPAction(){
	    $maSP= $_GET['maSP'];
	    $danhMuc =$_GET['danhMuc'];
	    if ($danhMuc == "DT"){
	        $ob= DienThoai::findFirst("maSP = '$maSP'");
	        if ($ob){
                if ($ob->delete()){
                    $this->view->setVar("ketQua","Xóa thành công!");
                    $_SERVER['HTTP_REFERER'];
                }else{
                    $this->view->setVar("ketQua","");
                }
            }else{
                $this->view->setVar("ketQua","Không tìm thấy mã sản phẩm cần xóa!");
            }

        }else{
            if ($danhMuc == "PK"){
                $ob= PhuKien::findFirst("maSP = '$maSP'");
                if ($ob){
                    if ($ob->delete()){
                        $this->view->setVar("ketQua","Xóa thành công!");
                        $_SERVER['HTTP_REFERER'];
                    }else{
                        $this->view->setVar("ketQua","");
                    }
                }else{
                    $this->view->setVar("ketQua","Không tìm thấy mã sản phẩm cần xóa!");
                }
            }else{
                $ob= SanPhamSuaChua::findFirst("maSP = '$maSP'");
                if ($ob){
                    if ($ob->delete()){
                        $this->view->setVar("ketQua","Xóa thành công!");
                        $_SERVER['HTTP_REFERER'];
                    }else{
                        $this->view->setVar("ketQua","");
                    }
                }else{
                    $this->view->setVar("ketQua","Không tìm thấy mã sản phẩm cần xóa!");
                }
            }
        }
    }
    public function logoutAction(){
        $this->checkLogin();
	    $this->session->destroy('acc');
        header("location: http://localhost/tmobile/nhan-vien/login");
    }
    public function checkLogin(){
	    $ses = $this->session->get('acc');
        if ($ses == null){
            header("location: http://localhost/tmobile/nhan-vien/login");
            return 0;
        }
    }
    public function checkLv(){
        $ses = $this->session->get('acc');
        if ($ses["lv"] > 1){
            header("location: http://localhost/tmobile/nhan-vien/showAllDonHang");
            return 0;
        }
    }
    public function checkMaSPAction(){
        $maSP = $_GET['maSP'];
        $loaiSP = $_GET['loaiSP'];
        $this->view->setRenderLevel(1);
        if ($loaiSP == "Điện thoại"){
            $kt = DienThoai::findFirst("maSP='$maSP'");
            if ($kt){
                echo "*Mã điện thoại đã tổn tại";
            }
        }else{
            if ($loaiSP == "Phụ kiện"){
                $kt = PhuKien::findFirst("maSP='$maSP'");
                if ($kt){
                    echo "*Mã phụ kiện đã tổn tại";
                }
            }else{
                $kt = SanPhamSuaChua::findFirst("maSP='$maSP'");
                if ($kt) {
                    echo "*Mã sản phẩm sửa chữa đã tổn tại";
                }
            }
        }
    }
    public function checkUsernameAction(){
        $this->view->setRenderLevel(1);
	    $username = $_GET['username'];
	    $dataCheck = NhanVien::findFirst("username = '$username'");
        if ($dataCheck){
            echo "*Tên tài khoản đã tồn tại!";
        }
    }
    public function xoaNVAction(){
        $this->view->setRenderLevel(1);
	    $username = $_GET['username'];
	    $ses = $this->session->get('acc');
	    if ($ses['lv'] <=1){
	        $taiKhoan = NhanVien::findFirst("username = '$username'");
	        if ($taiKhoan ->delete()){
                echo "Xóa thành công";
                header("location: http://localhost/tmobile/nhan-vien/showTaiKhoan");
            }else{
                echo "Có lỗi xảy ra!";
            }
        }
    }
    public function xuLyDonHangAction(){
	    $this->checkLogin();

	    $maDonHang= $_GET['maDonHang'];
	    $trangThai=$_GET['trangThai'];
	    $link=$_GET['link'];
	    $data = DonHang::findFirst("maDonHang ='$maDonHang'");
	    if ($data){
	        $data= $data->toArray();
	        $dataNew= ([
	            'maDonHang'=>$maDonHang,
                'maNguoiDung'=>$data['maNguoiDung'],
                'ngayDat'=>$data['ngayDat'],
                'tongTien'=>$data['tongTien'],
                'danhMuc'=>$data['danhMuc'],
                'ghiChu'=>$data['ghiChu'],
                'trangThai'=>$trangThai,
            ]);
	        $dh = new DonHang();
	        if ($dh->save($dataNew)){
	            header("location: $link");
            }else{
	            echo 'Thất bại! Có lỗi xảy ra <br>';
	            var_dump($dataNew);
                echo "<br>".$link;exit();
            }

        }
    }
    public function xemThongTinDonHangAction(){
        $maDonHang = $_GET['maDonHang'];
        $dataDH = DonHang::findFirst("maDonHang ='$maDonHang'")->toArray();
//        var_dump($dataDH);exit();
        $maNguoiDung = $dataDH['maNguoiDung'];
        $dataND = NguoiDung::findFirst("maNguoiDung ='$maNguoiDung'")->toArray();
        $dataCTDH = ChiTietDonHang::findFirst("maDonHang ='$maDonHang'")->toArray();
        $danhMuc = $dataDH['danhMuc'];
        $maSP = $dataCTDH['maSP'];
        $dataSP = $danhMuc::findFirst("maSP ='$maSP'")->toArray();

        $strMauSac = $dataSP['mauSac'];
        $listMau = explode(",",$strMauSac);
        $strAnh = $dataSP['hinhAnh'];
        $listAnh = explode(",",$strAnh);
        $strGia = $dataSP['gia'];
        $listGia = explode(",",$strGia);

        foreach ($listMau as $i => $item){
            if ($item == $dataCTDH['mauSac']){
                $hinhAnh = $listAnh[$i];
                for($j = $i;$j>=0;$j--){
                    $gia = $listGia[$j];
                    if ($gia>0){
                        break;
                    }
                }
                break;
            }
        }
        $dataSPMua = ([
            'hinhAnh'=>$hinhAnh,
            'tenSP'=>$dataSP['tenSP'],
            'maSP'=>$maSP,
            'mauSac'=>$dataCTDH['mauSac'],
            'gia'=>$gia,
            'soLuong'=>$dataCTDH['soLuong'],
            'baoHanh'=>$dataCTDH['cheDoBaoHanh']
        ]);

        $this->view->setVar("dataDH",$dataDH);
        $this->view->setVar("dataND",$dataND);
        $this->view->setVar("dataSPMua",$dataSPMua);
    }
    public function themBaiVietAction(){
	    $post = $this->request->getPost();
	    if (isset($post['themBV'])){
            $soDoan = (int)$post['soDoan'];
            $ctbv = ([]);
            for ($i=0;$i<$soDoan;$i++){
                $maDoan = "ctbv".$i;
                $noiDungDoan = $post[$maDoan];
                $list = explode("||",$noiDungDoan);
                $ctbv[$i] =([
                    "loai"=>$list[0],
                    "noiDung"=>$list[1],
                ]);
            }
            if (strlen($ctbv[0]['noiDung'])){
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $day = date ("Y-m-d H:i:s", $timestamp = time());
                $baiViet = ([
                    "maBaiViet"=>$post['maBV'],
                    "tenBaiViet"=>$post['tenBV'],
                    "maSP"=>$post['maSP'],
                    "ngayDang"=>$day,
                ]);
                $bv = new BaiViet($baiViet);
                if ($bv->save($baiViet)){
                    $ketQua = "Thêm thành công";
                }else{
                    echo "Có lỗi! Thêm thất bại";exit();
                }
            }
            foreach ( $ctbv as $i=> $item){
                if (strlen($item['noiDung'])>0){
                    $dataCTBV =([
                        'stt'=> "",
                        "maBaiViet"=>$post['maBV'],
                        'idBaiViet'=>$i,
                        'loai'=>$item['loai'],
                        'noiDung'=>$item['noiDung'],
                    ]);
                    $ct = new ChiTietBaiViet();
                    if ($ct->save($dataCTBV)){
                        $ketQua = "Thêm thành công";
                    }else{
                        echo "Có lỗi! Thêm thất bại";exit();
                    }
                }
            }

        }
	    $this->view->setVar("ketQua",$ketQua);
    }
    public function showAllBaiVietAction(){
        $data = BaiViet::find([
            "offset"=>0,
            "limit"=>30,
        ])->toArray();
        $this->view->setVar("data",$data);
    }
    public function suaThongTinBaiVietAction(){
	    $maBaiViet = $_GET['maBaiViet'];
	    $data= BaiViet::findFirst([
	        "maBaiViet = '$maBaiViet'",
        ])->toArray();
	    $dataCT = ChiTietBaiViet::find([
            "maBaiViet = '$maBaiViet'",
            "order"=>"idBaiViet ASC"
        ])->toArray();
	    $this->view->setVar("data",$data);
	    $this->view->setVar("dataCT",$dataCT);

	    $post = $this->request->getPost();
	    if (isset($post['luuBaiViet'])){
//	        var_dump($post);exit();
	        $soDoan = count($dataCT);
//            var_dump($post);exit();
            for ($i=0;$i<$soDoan;$i++){
                $name = "ctbv".$i;
                if (strlen($post[$name]) > 0){
                    $dataCTBV =([
                        'stt'=> $post['stt'.$i],
                        "maBaiViet"=>$maBaiViet,
                        'idBaiViet'=>$i,
                        'loai'=>$dataCT[$i]['loai'],
                        'noiDung'=>$post[$name],
                    ]);
                    $ct = new ChiTietBaiViet();
                    if ($ct->save($dataCTBV)){
                        $ketQua = "Lưu thành công";
                    }else{
                        var_dump("Có lỗi! Lưu thất bại");exit();
                    }
                }
            }

            if (strlen($post['ctbv0']) > 0){
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $day = date ("Y-m-d H:i:s", $timestamp = time());
                $baiViet = ([
                    "maBaiViet"=>$maBaiViet,
                    "tenBaiViet"=>$post['tenBaiViet'],
                    "maSP"=>$post['maSP'],
                    "ngayDang"=>$day,
                ]);
                $bv = new BaiViet($baiViet);
                if ($bv->save($baiViet)){
                    $ketQua = "Lưu thành công";
                }else{
                    echo "Có lỗi! Lưu thất bại";exit();
                }
            }
            $this->view->setVar("ketQua",$ketQua);
        }

    }
    public function xoaBaiVietAction(){
	    $maBaiViet = $_GET['maBaiViet'];
	    $data= BaiViet::findFirst("maBaiViet = '$maBaiViet'");
	    $dataCT = ChiTietBaiViet::find("maBaiViet = '$maBaiViet'");
	    if ($dataCT->delete() && $data->delete()){
//	        var_dump("ok");exit();
        }else{
	        var_dump("Có lỗi! Đề nghị fix");exit();
        }
    }
    public function doiMatKhauAction(){
        $taiKhoan = $this->session->get('acc');
        $username = $taiKhoan['username'];
        $dataNV = NhanVien::findFirst("username = '$username'")->toArray();
        $this->view->setVar("dataTK",$dataNV);
        $post = $this->request->getPost();
        if (isset($post['btnDoiMatKhau'])){
            $matKhauCu = $post['matKhau'];
            $matKhauMoi = $post['matKhauMoi'];
            if ($dataNV['password'] == md5($matKhauCu)){
                $dataNew =([
                    "tenNV"=>$dataNV['tenNV'],
                    "namSinh"=>$dataNV['namSinh'],
                    "gioiTinh"=>$dataNV['gioiTinh'],
                    "sdt"=>$dataNV['sdt'],
                    "email"=>$dataNV['email'],
                    "lv"=>$dataNV['lv'],
                    "username"=>$username,
                    "password"=>md5($matKhauMoi),
                ]);
//                var_dump($dataNew);exit();
                $nv = New NhanVien();
                if ($nv->save($dataNew)){
                    $this->view->setVar("ketQua","* Đổi mật khẩu thành công!");
                }else{
                    $this->view->setVar("ketQua","* Nhập sai mật khẩu. Xin thử lại");
                }
            }

        }
    }
    public function suaThongTinTaiKhoanAction(){
        $username = $this->session->get('acc');
        $username = $username["username"];
        $data = NhanVien::findFirst("username= '$username'")->toArray();
        $this->view->setVar("data",$data);

        $post = $this->request->getPost();
        if (isset($post['luuThongTin'])){
            $dataNew =([
                "tenNV"=>$post['tenNV'],
                "namSinh"=>$post['namSinh'],
                "gioiTinh"=>$post['gioiTinh'],
                "sdt"=>$post['sdt'],
                "email"=>$post['email'],
                "lv"=>$data['lv'],
                "username"=>$data['username'],
                "password"=>$data['password'],
            ]);
            $nv = New NhanVien();
            if ($nv->save($dataNew)){
                $this->view->setVar("ketQua","* Lưu thông tin thành công.");
            }else{
                $this->view->setVar("ketQua","* Có lỗi! Lưu thông tin thất bại");
            }
        }
    }
    public function timSPAction(){
	    $this->view->setRenderLevel(1);

	    $keyWord = $_GET['keyWord'];
	    $danhMuc = $_GET['danhMuc'];

	    switch ($danhMuc){
            case "DienThoai":
                $data = DienThoai::find([
                    "tenSP LIKE '%$keyWord%' OR maSP LIKE '%$keyWord%' OR hang LIKE '%$keyWord%'"
                ]);
                $keyDanhMuc ="DT";
                if ($data){
                    $i=0;
                    $dataAll=$data->toArray();
                    foreach ($dataAll as $item){
                        $listImg = explode(",",$item['hinhAnh']);
                        $dataSP[$i]= ([
                            'hinhAnh'=> $listImg[0],
                            'maSP'=> $item['maSP'],
                            'tenSP'=> $item['tenSP'],
                            'soLuong'=> $item['soLuong'],
                            'gia'=> $item['gia'],
                        ]);
                        $i++;
                    }
//                    var_dump($dataSP);exit();
                }
                break;
            case "PhuKien":
                $data = PhuKien::find([
                    "tenSP LIKE '%$keyWord%' OR maSP LIKE '%$keyWord%' OR hang LIKE '%$keyWord%' OR model LIKE '%$keyWord%'"
                ]);
                $keyDanhMuc ="PK";
                if ($data){
                    $i=0;
                    $dataAll=$data->toArray();
                    foreach ($dataAll as $item){
                        $listImg = explode(",",$item['hinhAnh']);
                        $dataSP[$i]= ([
                            'hinhAnh'=> $listImg[0],
                            'maSP'=> $item['maSP'],
                            'tenSP'=> $item['tenSP'],
                            'soLuong'=> $item['soLuong'],
                            'gia'=> $item['gia'],
                        ]);
                        $i++;
                    }
                }
                break;
            case "SanPhamSuaChua":
                $data = SanPhamSuaChua::find([
                    "tenSP LIKE '%$keyWord%' OR maSP LIKE '%$keyWord%' OR hang LIKE '%$keyWord%' OR model LIKE '%$keyWord%'"
                ]);
                $keyDanhMuc ="SC";
                if ($data){
                    $i=0;
                    $dataAll=$data->toArray();
                    foreach ($dataAll as $item){
                        $listImg = explode(",",$item['hinhAnh']);
                        $dataSP[$i]= ([
                            'hinhAnh'=> $listImg[0],
                            'maSP'=> $item['maSP'],
                            'tenSP'=> $item['tenSP'],
                            'soLuong'=> $item['soLuong'],
                            'gia'=> $item['gia'],
                        ]);
                        $i++;
                    }
                }
                break;
        }
        if (is_array($dataSP)){
//            var_dump($dataSP);exit();
            $this->view->setVar("data",$dataSP);
            $this->view->setVar("keyDanhMuc",$keyDanhMuc);
        }
    }
    public function loadListSPAction(){
	    $this->view->setRenderLevel(1);

	    $soTrang = $_GET['soTrang'];
        $danhMuc = $_GET['danhMuc'];
        $soTin1Trang = 25;
        $start = ($soTrang-1)*$soTin1Trang;
        $end = $soTin1Trang;

        $dataAll = $danhMuc::find([
            "offset"=>$start,
            "limit"=>$end,
        ])->toArray();
        $i=0;
        foreach ($dataAll as $item){
            $listImg = explode(",",$item['hinhAnh']);
            $data[$i]= ([
                'hinhAnh'=> $listImg[0],
                'maSP'=> $item['maSP'],
                'tenSP'=> $item['tenSP'],
                'soLuong'=> $item['soLuong'],
                'gia'=> $item['gia'],
            ]);
            $i++;
        }
        switch ($danhMuc){
            case "DienThoai":
                $keyDanhMuc = "DT";
                break;
            case "PhuKien":
                $keyDanhMuc = "PK";
                break;
            case "SanPhamSuaChua":
                $keyDanhMuc = "SC";
                break;
        }
        $this->view->setVar("data",$data);
        $this->view->setVar("keyDanhMuc",$keyDanhMuc);
//        var_dump($data);exit();
    }
    public function loadListDHAction(){
	    $this->view->setRenderLevel(1);
        $trangThai =$_GET['trangThai'];
        $soTrang = $_GET['soTrang'];
        $soDon1Trang=30;
        $start = ($soTrang-1)*$soDon1Trang;
        $end = $soDon1Trang;
        if ($trangThai == "all"){
            $data = DonHang::find([
                'offset'=>$start,
                'limit' => $end,
                "order"=>"ngayDat DESC",
            ])->toArray();
        }else{
            $data = DonHang::find([
                'offset'=>$start,
                'limit' => $end,
                "trangThai"=>$trangThai,
                "order"=>"ngayDat DESC",
            ])->toArray();
        }

        foreach ($data as $x => $item){
            $maDonHang = $item["maDonHang"];
            $danhMuc = $item['danhMuc'];
            $dataCTDH = ChiTietDonHang::findFirst("maDonHang = '$maDonHang'")->toArray();
            $maSP= $dataCTDH['maSP'];
            $dataSP = $danhMuc::findFirst("maSP = '$maSP'")->toArray();
            $maNguoiDung = $item['maNguoiDung'];
            $dataND = NguoiDung::findFirst("maNguoiDung = '$maNguoiDung'")->toArray();

            $dataDH[$x] = ([
                'maDonHang'=>$maDonHang,
                'tenSP'=>$dataSP['tenSP'],
                'soLuong'=>$dataCTDH['soLuong'],
                'tongTien'=>$item['tongTien'],
                'diaChi'=>$dataND['diaChi'],
                'tenNguoiDung'=>$dataND['tenNguoiDung'],
                'trangThai'=>$item['trangThai'],
            ]);
        }
        $this->view->setVar("dataDH",$dataDH);
    }
    public function themBannerAction(){
        $post = $this->request->getPost();
        if (isset($post['luuThongTin'])){
            $file1 = $_FILES['file1'];
            $file2 = $_FILES['file2'];
            $file3 = $_FILES['file3'];
            $file4 = $_FILES['file4'];
            if (strlen($file1['name'][0]) >0){
                $listLink = explode(",",$post['link1']);
                for ($i=0;$i<count($file1['name']);$i++){
                    if (strlen($file1['name'][$i]) >0){
                        move_uploaded_file($file1['tmp_name'][$i],"files/slideshow/".$file1['name'][$i]);
                        $data1 = ([
                            "stt"=>"",
                            "hinhAnh"=>$file1['name'][$i],
                            "link"=>$listLink[$i],
                            "viTri"=>1,
                        ]);
                        $bn = new Banner();
                        if ($bn->save($data1)){
                            $ketQua = "Thay đổi Banner thành công";
                        }else{
                            $ketQua= "Có lỗi xảy ra! Thay đổi thất bại";
                        }
                    }
                }
            }
            if (strlen($file2['name'][0]) >0){
                $listLink = explode(",",$post['link2']);
                for ($i=0;$i<count($file2['name']);$i++){
                    if (strlen($file2['name'][$i]) >0){
                        move_uploaded_file($file2['tmp_name'][$i],"files/slideshow/".$file2['name'][$i]);
                        $data1 = ([
                            "stt"=>"",
                            "hinhAnh"=>$file2['name'][$i],
                            "link"=>$listLink[$i],
                            "viTri"=>2,
                        ]);
                        $bn = new Banner();
                        if ($bn->save($data1)){
                            $ketQua = "Thay đổi Banner thành công";
                        }else{
                            $ketQua= "Có lỗi xảy ra! Thay đổi thất bại";
                        }
                    }
                }
            }
            if (strlen($file3['name'][0]) >0){
                $listLink = explode(",",$post['link3']);
                for ($i=0;$i<count($file3['name']);$i++){
                    if (strlen($file3['name'][$i]) >0){
                        move_uploaded_file($file3['tmp_name'][$i],"files/slideshow/".$file3['name'][$i]);
                        $data1 = ([
                            "stt"=>"",
                            "hinhAnh"=>$file3['name'][$i],
                            "link"=>$listLink[$i],
                            "viTri"=>3,
                        ]);
                        $bn = new Banner();
                        if ($bn->save($data1)){
                            $ketQua = "Thay đổi Banner thành công";
                        }else{
                            $ketQua= "Có lỗi xảy ra! Thay đổi thất bại";
                        }
                    }
                }

            }
            if (strlen($file4['name'][0]) >0){
                $listLink = explode(",",$post['link4']);
                for ($i=0;$i<count($file4['name']);$i++){
                    if (strlen($file4['name'][$i]) >0){
                        move_uploaded_file($file4['tmp_name'][$i],"files/slideshow/".$file4['name'][$i]);
                        $data1 = ([
                            "stt"=>"",
                            "hinhAnh"=>$file4['name'][$i],
                            "link"=>$listLink[$i],
                            "viTri"=>4,
                        ]);
                        $bn = new Banner();
                        if ($bn->save($data1)){
                            $ketQua = "Thay đổi Banner thành công";
                        }else{
                            $ketQua= "Có lỗi xảy ra! Thay đổi thất bại";
                        }
                    }
                }
            }
            $this->view->setVar("ketQua",$ketQua);
        }
    }
    public function showAllBannerAction(){
	    $data = Banner::find()->toArray();
	    $this->view->setVar("data",$data);
    }
    public function xoaBannerAction(){
	    $stt = $_GET['stt'];
	    $data = Banner::find("stt = '$stt'");
	    if ($data->delete()){
	        header("loaction: http://localhost/tmobile/nhan-vien/showAllBanner");
        }else{
            var_dump("Có lỗi! Đề nghị fix");exit();
        }
	    ;
    }
    public function suaBannerAction(){
	    $stt = $_GET['stt'];
	    $data = Banner::findFirst("stt = '$stt'")->toArray();
	    $this->view->setVar("data",$data);

	    $post = $this->request->getPost();
	    if (isset($post['luuThongTin'])){
            if (strlen($_FILES['file']['type'])>0){
                $hinhAnh = $_FILES['file']['name'];
                move_uploaded_file($_FILES['file']['tmp_name'],"files/slideshow/".$hinhAnh);
            }else{
                $hinhAnh = $data['hinhAnh'];
            }
            $dataNew = ([
                "stt"=>$data['stt'],
                "hinhAnh"=>$hinhAnh,
                "link"=>$post['link'],
                "viTri"=>$post['viTri'],
            ]);
            $bn = new Banner();
            if ($bn->save($dataNew)){
                $ketQua = "* Lưu thông tin thành công!";
            }else{
                $ketQua = "* Có lỗi! Lưu thông tin thất bại!";
            }
            $this->view->setVar("ketQua",$ketQua);
        }
    }

}

