<?php


class ChiTietBaiViet extends \Phalcon\Mvc\Model
{
    private $stt;
    private $maBaiViet;
    private $idBaiViet;
    private $noiDung;

    /**
     * @return mixed
     */
    public function getMaBaiViet()
    {
        return $this->maBaiViet;
    }

    /**
     * @return mixed
     */
    public function getIdBaiViet()
    {
        return $this->idBaiViet;
    }

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