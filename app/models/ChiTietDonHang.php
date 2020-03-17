<?php
    class ChiTietDonHang extends \Phalcon\Mvc\Model{
        private $stt;
        private $maDonHang;
        private $maSP;
        private $soLuong;

        /**
         * @return mixed
         */
        public function getStt()
        {
            return $this->stt;
        }

        /**
         * @param mixed $stt
         */
        public function setStt($stt)
        {
            $this->stt = $stt;
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
        public function getMaSP()
        {
            return $this->maSP;
        }

        /**
         * @param mixed $maSP
         */
        public function setMaSP($maSP)
        {
            $this->maSP = $maSP;
        }

        /**
         * @return mixed
         */
        public function getSoLuong()
        {
            return $this->soLuong;
        }

        /**
         * @param mixed $soLuong
         */
        public function setSoLuong($soLuong)
        {
            $this->soLuong = $soLuong;
        }


    }
?>