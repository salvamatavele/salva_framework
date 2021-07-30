<?php 
namespace Src;

use CoffeeCode\Paginator\Paginator;

class Paginate
{
    /**
     * pagiantor
     *
     * @var Paginator
     */
    private $paginator;
    /**
     * Will paginate you data and return array
     *
     * @param  $model
     * @param integer $number_of_pages
     * @param integer $range
     * @return  array
     */
    public function paginate($model, int $number_of_pages = 5, int $range = 3, string $firstPageTag = null, string $lastPageTag = null,?string $terms = null, ?string $params = null, string $columns = "*")
    {
        if ($firstPageTag == null) {
            $firstPage = null;
        } else {
            $firstPage = ["Primeira página", $firstPageTag];
        }
        if ($lastPageTag == null) {
            $lastPage = null;
        } else {
            $lastPage = ["Última página", $lastPageTag];
        }

        $page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT);
        $paginator = new Paginator(null, null, $firstPage, $lastPage);
        $paginator->pager($model->find($terms,$params,$columns)->count(), $number_of_pages, $page, $range);

        $data = $model->find($terms,$params,$columns)->order("created_at DESC")->limit($paginator->limit())->offset($paginator->offset())->fetch(true);
        $this->paginator = $paginator;
        return $data;
    }

    public function paginator()
    {
        return $this->paginator;
    }
}