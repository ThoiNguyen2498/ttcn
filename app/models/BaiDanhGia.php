<?php
    class BaiDanhGia extends \Phalcon\Mvc\Model{
        private $stt;
        private $maSP;
        private $soSao;
        private $hoTen;
        private $sdt;
        private $noiDung;

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
        public function getSoSao()
        {
            return $this->soSao;
        }

        /**
         * @param mixed $soSao
         */
        public function setSoSao($soSao)
        {
            $this->soSao = $soSao;
        }

        /**
         * @return mixed
         */
        public function getHoTen()
        {
            return $this->hoTen;
        }

        /**
         * @param mixed $hoTen
         */
        public function setHoTen($hoTen)
        {
            $this->hoTen = $hoTen;
        }

        /**
         * @return mixed
         */
        public function getSdt()
        {
            return $this->sdt;
        }

        /**
         * @param mixed $sdt
         */
        public function setSdt($sdt)
        {
            $this->sdt = $sdt;
        }

        /**
         * @return mixed
         */
        public function getNoiDung()
        {
            return $this->noiDung;
        }

        /**
         * @param mixed $noiDung
         */
        public function setNoiDung($noiDung)
        {
            $this->noiDung = $noiDung;
        }

    }
?>