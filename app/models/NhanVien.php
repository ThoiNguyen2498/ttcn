<?php
    class NhanVien extends Nguoi
    {
        private $maNV;
        private $username="";
        private $password="";
        private $lv="";

        /**
         * @return mixed
         */
        public function getMaNV()
        {
            return $this->maNV;
        }

        /**
         * @param mixed $maNV
         */
        public function setMaNV($maNV)
        {
            $this->maNV = $maNV;
        }

        /**
         * @return string
         */
        public function getUsername()
        {
            return $this->username;
        }

        /**
         * @param string $username
         */
        public function setUsername($username)
        {
            $this->username = $username;
        }

        /**
         * @return string
         */
        public function getPassword()
        {
            return $this->password;
        }

        /**
         * @param string $password
         */
        public function setPassword($password)
        {
            $this->password = $password;
        }

        /**
         * @return string
         */
        public function getLv()
        {
            return $this->lv;
        }

        /**
         * @param string $lv
         */
        public function setLv($lv)
        {
            $this->lv = $lv;
        }

        public function maHoaMK($passRaw){
            $password = md5($passRaw);
            return $password;
        }
    }
?>