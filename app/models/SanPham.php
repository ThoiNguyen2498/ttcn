<?php
    class SanPham extends \Phalcon\Mvc\Model
    {
        private $maSP;
        private $tenSP;
        private $soLuong;
        private $hangSX;
        private $gia;

        /**
         * @return mixed
         */
        public function getGia()
        {
            return $this->gia;
        }

        /**
         * @param mixed $gia
         */
        public function setGia($gia)
        {
            $this->gia = $gia;
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
        public function getTenSP()
        {
            return $this->tenSP;
        }

        /**
         * @param mixed $tenSP
         */
        public function setTenSP($tenSP)
        {
            $this->tenSP = $tenSP;
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

        /**
         * @return mixed
         */
        public function getHangSX()
        {
            return $this->hangSX;
        }

        /**
         * @param mixed $hangSX
         */
        public function setHangSX($hangSX)
        {
            $this->hangSX = $hangSX;
        }

    }
?>