<?php
    class NguoiDung extends Nguoi{
        private $maNguoiDung;

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
        public function taoMaNguoiDung(){
        }

    }