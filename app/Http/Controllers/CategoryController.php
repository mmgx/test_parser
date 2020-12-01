<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\BaseController;
use App\Service\ParseService;
use Illuminate\Http\Request;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\StrictException;
use Psr\Http\Client\ClientExceptionInterface;

class CategoryController extends BaseController
{
    /**
     * @var ParseService
     */
    protected $parserService;

    /**
     * CategoryController constructor.
     * @param ParseService $parserService
     */
    public function __construct(ParseService $parserService)
    {
        $this->parserService = $parserService;
    }

    /**
     * Дамп массива с категориями
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws StrictException
     * @throws ClientExceptionInterface
     */
    public function index()
    {
        return dd($this->parserService->getCategoryItems());
    }
}
