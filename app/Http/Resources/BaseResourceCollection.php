<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseResourceCollection extends ResourceCollection
{
    public function paginationInformation($request, $paginated, $default): array
    {
        unset($default['links']);
        unset($default['meta']['links']);
        unset($default['meta']['path']);

        return $default;
    }
}
