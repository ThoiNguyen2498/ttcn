<?php
class Banner extends \Phalcon\Mvc\Model
{
    private $stt;
    private $hinhAnh;
    private $link;
    private $vt;

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

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return mixed
     */
    public function getVt()
    {
        return $this->vt;
    }

    /**
     * @param mixed $vt
     */
    public function setVt($vt)
    {
        $this->vt = $vt;
    }


}