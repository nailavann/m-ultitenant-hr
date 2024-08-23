<?php

namespace App\Http\Controllers\Tenant\Api\News;

use App\Http\Resources\Tenant\Api\News\NewsResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use SimpleXMLElement;

class NewsController extends Controller
{
    private string $url = 'https://www.trthaber.com/xml_mobile.php';

    public function allNews(Request $request)
    {
        try {
            $data = Cache::remember('fetchNews' . $request->category, 3600, function () use ($request) {
                return $this->fetchXmlData([
                    'tur' => 'xml_genel',
                    'kategori' => $request->category,
                    'adet' => 100
                ]);
            });

            $page = $request->page;
            $perPage = 20;
            $offset = ($page - 1) * $perPage;
            $limitedData = array_slice($data, $offset, $perPage);
            $totalItems = count($data);
            $hasNextPage = $offset + $perPage < $totalItems;


            return NewsResource::collection($limitedData)->additional([
                'current_page' => $page,
                'per_page' => $perPage,
                'total_pages' => ceil($totalItems / $perPage),
                'hasNextPage' => $hasNextPage
            ]);
        } catch (\Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    public function newsDetail($newsId)
    {
        try {
            $data = Cache::remember('fetchNewsDetail' . $newsId, 3600, function () use ($newsId) {
                return $this->fetchXmlData([
                    'tur' => 'xml_genel',
                    'id' => $newsId
                ], true);
            });

            $data->haber_aciklama = $this->cleanText($data->haber_aciklama);
            $data->haber_metni = $this->cleanText($data->haber_metni);

            return NewsResource::make($data);
        } catch (\Throwable $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    private function fetchXmlData($params, $isDetail = false)
    {
        $response = Http::get($this->url, $params);
        $xmlContent = $response->body();
        $simpleXml = simplexml_load_string($xmlContent, SimpleXMLElement::class, LIBXML_NOCDATA);

        if ($simpleXml === false) {
            throw new \Exception('Failed parse XML');
        }

        return $this->xmlToJson($simpleXml, $isDetail);
    }

    private function xmlToJson(SimpleXMLElement $simpleXml, $isDetail)
    {
        $json = json_decode(json_encode($simpleXml));

        if ($isDetail) {
            return $json->haber;
        }
        foreach ($json->haber as $item) {
            $item->haber_aciklama = $this->cleanText($item->haber_aciklama);
            $item->haber_metni = $this->cleanText($item->haber_metni);
        }

        return $json->haber;
    }

    private function cleanText($text)
    {
        $replaceArray = ["\n", "\t"];
        foreach ($replaceArray as $replace) {
            $text = str_replace($replace, "", strip_tags($text));
        }
        return $text;
    }

}
