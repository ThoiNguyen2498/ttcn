<?php
    class DonHang extends \Phalcon\Mvc\Model{
        private $maDonHang;
        private $maNguoiDung;
        private $ngayDat;
        private $tongTien;
        private $trangThai;

        /**
         * @return mixed
         */
        public function getTrangThai()
        {
            return $this->trangThai;
        }

        /**
         * @param mixed $trangThai
         */
        public function setTrangThai($trangThai)
        {
            $this->trangThai = $trangThai;
        }

        /**
         * @return mixed
         */
        public function getTongTien()
        {
            return $this->tongTien;
        }

        /**
         * @param mixed $tongTien
         */
        public function setTongTien($tongTien)
        {
            $this->tongTien = $tongTien;
        }

        /**
         * @return mixed
         */
        public function getMaDonHang()
        {
            return $this->maDonHang;
        }

        /**
         * @param mixed $maDonHang
         */
        public function setMaDonHang($maDonHang)
        {
            $this->maDonHang = $maDonHang;
        }

        /**
         * @return mixed
         */
        public function getMaNguoiDung()
        {
            return $this->maNguoiDung;
        }

        /**
         * @param mixed $maNguoiDung
         */
        public function setMaNguoiDung($maNguoiDung)
        {
            $this->maNguoiDung = $maNguoiDung;
        }

        /**
         * @return mixed
         */
        public function getNgayDat()
        {
            return $this->ngayDat;
        }

        /**
         * @param mixed $ngayDat
         */
        public function setNgayDat($ngayDat)
        {
            $this->ngayDat = $ngayDat;
        }


    }
?>