<?php
namespace App\Contracts;

interface ParseServiceContract
{
    /**
     * @return mixed
     */
    public function getSiteHost();

    public function getSiteProtocol();

    public function getSiteUrl();

    public function getCategoryPage();
}
