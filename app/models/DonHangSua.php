<?php


class DonHangSua extends \Phalcon\Mvc\Model
{
    private $maDonSuaChua;
    private $maNguoiDung;
    private $thoiGianHen;
    private $maSP;
    private $tongTien;
    private $trangThai;

    /**
     * @return mixed
     */
    public function getMaDonSuaChua()
    {
        return $this->maDonSuaChua;
    }

    /**
     * @param mixed $maDonSuaChua
     */
    public function setMaDonSuaChua($maDonSuaChua)
    {
        $this->maDonSuaChua = $maDonSuaChua;
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
    public function getThoiGianHen()
    {
        return $this->thoiGianHen;
    }

    /**
     * @param mixed $thoiGianHen
     */
    public function setThoiGianHen($thoiGianHen)
    {
        $this->thoiGianHen = $thoiGianHen;
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


}