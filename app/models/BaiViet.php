<?php


class BaiViet extends \Phalcon\Mvc\Model
{
    private $maBaiViet;
    private $maSP;
    private $model;

    /**
     * @return mixed
     */
    public function getMaBaiViet()
    {
        return $this->maBaiViet;
    }

    /**
     * @param mixed $maBaiViet
     */
    public function setMaBaiViet($maBaiViet)
    {
        $this->maBaiViet = $maBaiViet;
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
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }
}