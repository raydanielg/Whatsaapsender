<?php

namespace App\Service\Admin\Core;

use App\Enums\StatusEnum;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CollectionService extends Collection
{
    public function collectionSearch(array $params, bool $like = true) {
        
        $search = request()->input("search");
    
        if (!$search) {
            return $this;
        }
    
        $search = $like ? "%$search%" : $search;
    
        return $this->filter(function ($item, $key) use ($params, $search, $like) {

            if ($this->matches($key, $search, $like)) {

                return true;
            }
            foreach ($params as $param) {

                if ($this->matches($item[$param] ?? null, $search, $like)) {

                    return true;
                }
            }
            return false;
        });
    }

    private function matches($value, $search, $like) {

        if (is_null($value)) {

            return false;
        }
        if ($like) {
            
            return stripos($value, trim($search, '%')) !== false;
        } else {

            return $value == $search;
        }
    }

    public function paginate($perPage = null, $page = null, $options = []) {

        $page  = $page ?: (LengthAwarePaginator::resolveCurrentPage() ?: 1);
        $items = $this->forPage($page, $perPage);
        return new LengthAwarePaginator($items, $this->count(), $perPage, $page, $options);
    }

    public function keyFilter($route, $value = null, $key = 'status') {
        
        if($route == 'index') {

            return $this;
            
        } else {

            return $this->filter(function ($item) use ($key, $value, $route) {

                if($route == 'active') {

                    return $item[$key] == StatusEnum::TRUE->status();
                    
                } elseif($route == 'inactive') {

                    return $item[$key] == StatusEnum::FALSE->status();
                } else {

                    return $item[$key] == $value;
                }
            });
        }
    }
}
