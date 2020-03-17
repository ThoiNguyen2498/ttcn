<?php
    class PhuKien extends SanPham{
        private $giaoTiep;
        private $mauSac;
        private $hinhAnh;
        private $loai;

        /**
         * @return mixed
         */
        public function getLoai()
        {
            return $this->loai;
        }

        /**
         * @param mixed $loai
         */
        public function setLoai($loai)
        {
            $this->loai = $loai;
        }


        /**
         * @return mixed
         */
        public function getGiaoTiep()
        {
            return $this->giaoTiep;
        }

        /**
         * @param mixed $giaoTiep
         */
        public function setGiaoTiep($giaoTiep)
        {
            $this->giaoTiep = $giaoTiep;
        }

        /**
         * @return mixed
         */
        public function getMauSac()
        {
            return $this->mauSac;
        }

        /**
         * @param mixed $mauSac
         */
        public function setMauSac($mauSac)
        {
            $this->mauSac = $mauSac;
        }

        /**
         * @return mixed
         */
        public function getHinhAnh()
        {
            return $this->hinhAnh;
        }

        /**
         * @param mixed $hinhAnh
         */
        public function setHinhAnh($hinhAnh)
        {
            $this->hinhAnh = $hinhAnh;
        }

    }
?>