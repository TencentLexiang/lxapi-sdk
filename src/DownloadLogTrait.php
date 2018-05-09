<?php
namespace Lexiangla\Openapi;

Trait DownloadLogTrait
{
    public function getDownloadLogs($request = [])
    {
        return $this->get('download-logs', $request);
    }
}