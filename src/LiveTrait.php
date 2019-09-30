<?php
namespace Lexiangla\Openapi;

Trait LiveTrait
{
    public function getLiveStaffs($id, $request = [])
    {
        return $this->get('lives/' . $id . '/staffs', $request);
    }

    public function getStaffLives($id, $request = [])
    {
        return $this->get('staffs/' . $id . '/lives', $request);
    }
}