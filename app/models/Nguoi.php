<?php
class Nguoi extends \Phalcon\Mvc\Model
{
    private $hoTen="";
    private $gioiTinh="";
    private $namSinh;
    private $sdt="";
    private $email="";

    /**
     * @return string
     */
    public function getHoTen()
    {
        return $this->hoTen;
    }

    /**
     * @param string $hoTen
     */
    public function setHoTen($hoTen)
    {
        $this->hoTen = $hoTen;
    }

    /**
     * @return string
     */
    public function getGioiTinh()
    {
        return $this->gioiTinh;
    }

    /**
     * @param string $gioiTinh
     */
    public function setGioiTinh($gioiTinh)
    {
        $this->gioiTinh = $gioiTinh;
    }

    /**
     * @return mixed
     */
    public function getNamSinh()
    {
        return $this->namSinh;
    }

    /**
     * @param mixed $namSinh
     */
    public function setNamSinh($namSinh)
    {
        $this->namSinh = $namSinh;
    }

    /**
     * @return string
     */
    public function getSdt()
    {
        return $this->sdt;
    }

    /**
     * @param string $sdt
     */
    public function setSdt($sdt)
    {
        $this->sdt = $sdt;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

}
?>