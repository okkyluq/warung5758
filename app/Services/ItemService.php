<?php

namespace App\Services;
use use Illuminate\Pagination\LengthAwarePaginator;

class ItemService
{
    public function arrayPaginator($array, $request, $perpage)
    {
        $page = $request->input("page", 1);
        $per_page = $perpage;
        $offset = ($page * $per_page) - $per_page;

        return new LengthAwarePaginator(array_slice($array, $offset, $per_page, true), count($array), $per_page, $page,
            ['path' => $request->url(), 'query' => $request->query()]);
    }
}
