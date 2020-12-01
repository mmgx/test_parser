<?php
namespace App\Service;

use App\Contracts\ParseServiceContract;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Dom\Node\Collection;
use PHPHtmlParser\Dom\Node\HtmlNode;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Psr\Http\Client\ClientExceptionInterface;

class ParseService extends Base\BaseService implements ParseServiceContract
{
    /**
     * Получить домен сайта для парсинга
     * @return mixed
     */
    public function getSiteHost()
    {
        return env('PARSED_SITE', 'www.tdsevcable.ru');
    }

    /**
     * Получить протокол для сайта
     * @return string
     */
    public function getSiteProtocol()
    {
        return 'https://';
    }

    /**
     * Получить домен сайта вместе с
     * @return string
     */
    public function getSiteUrl()
    {
        return $this->getSiteProtocol() . $this->getSiteHost();
    }

    /**
     * Путь к странице с категориями
     * @return string
     */
    public function getCategoryPage()
    {
        return $this->getSiteUrl() .'/catalog.html';
    }

    /**
     * Получить категории сайта со всеми вложенными DOM-элементами
     * @return mixed|Dom\Node\Collection|null
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws StrictException
     * @throws ClientExceptionInterface
     */
    public function getCategoriesWithFullChild()
    {
        $dom = new Dom;
        $dom->loadFromUrl($this->getCategoryPage());
        try {
            return $dom->getElementsByClass('catalog_item');

        } catch (ChildNotFoundException $e) {
            throw new ChildNotFoundException('ChildNotFoundException');
        } catch (NotLoadedException $e) {
            throw new ChildNotFoundException('NotLoadedException');
        }
    }

    /**
     * Получить имя категории
     * @param HtmlNode $node
     * @return mixed
     * @throws ChildNotFoundException
     */
    public function getCategoryTitle(HtmlNode $node)
    {
        return $node->find('.catalog_item_title_body text')->text;
    }

    /**
     * Получить изображения категорий
     * @param HtmlNode $node
     * @return mixed
     * @throws ChildNotFoundException
     */
    public function getCategoryPicture(HtmlNode $node)
    {
        return $node->find('.catalog_item_img_body img')->src;
    }

    /**
     * Получить ссылку на раздел категории
     * @param HtmlNode $node
     * @return mixed
     * @throws ChildNotFoundException
     */
    public function getCategoryLink(HtmlNode $node)
    {
        return $node->find('.catalog_item_title_body a')->href;
    }

    /**
     * Получить массив с элементами (заголовок, изображение, ссылка)
     * @param Collection $node
     * @return array
     * @throws ChildNotFoundException
     */
    public function getArrayItems(Collection $node): array
    {
        $arr = [];
        foreach ($node as $item)
        {
            $arr[] = [
                'title' => $this->getCategoryTitle($item),
                'picture' => $this->getCategoryPicture($item),
                'url' => $this->getCategoryLink($item),
            ];
        }
        return $arr;
    }

    /**
     * Получить массив с информацией о главных категориях сайта
     * @return array
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ClientExceptionInterface
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws StrictException
     */
    public function getCategoryItems(): array
    {
        return $this->getArrayItems($this->getCategoriesWithFullChild());
    }
}
