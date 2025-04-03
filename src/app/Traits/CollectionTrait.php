<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

trait CollectionTrait
{
    /**
     * Wrap data in a Collection instance.
     *
     * @param mixed $items
     * @return \Illuminate\Support\Collection
     */
     public function collect($items): Collection
     {
          return new Collection($items);
     }

   
     /**
      * searchCollection
      *
      * @param Collection $collection
      * @param array $params
      * @param bool $like
      * 
      * @return Collection
      */
     public function searchCollection(Collection $collection, array $params = [], bool $like = true): Collection
     {
          $search = request()->input("search");

          if (!$search) {
               return $collection;
          }

          $search = $like ? "%$search%" : $search;

          return $collection->filter(function ($item, $key) use ($params, $search, $like) {
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

     /**
      * Check if a value matches the search term.
      *
      * @param mixed $value
      * @param string $search
      * @param bool $like
      * @return bool
      */
     private function matches($value, $search, $like): bool
     {
          if (is_null($value)) {
               return false;
          }
          return $like ? stripos($value, trim($search, '%')) !== false : $value == $search;
     }

     /**
      * paginate
      *
      * @param Collection $collection
      * @param null $perPage
      * @param null $page
      * @param array $options
      * 
      * @return LengthAwarePaginator
      */
     public function paginate(Collection $collection, $perPage = null, $page = null, $options = []): LengthAwarePaginator
     {
          $page = $page ?: (LengthAwarePaginator::resolveCurrentPage() ?: 1);
          $items = $collection->forPage($page, $perPage);
          return new LengthAwarePaginator($items, $collection->count(), $perPage, $page, $options);
     }

     /**
      * filterByKey
      *
      * @param Collection $collection
      * @param mixed $route
      * @param null $value
      * @param string $key
      * 
      * @return Collection
      */
     public function filterByKey(Collection $collection, $route, $value = null, $key = 'status'): Collection
     {
          if ($route == 'index') {
               return $collection;
          }

          return $collection->filter(function ($item) use ($key, $value, $route) {
               if ($route == 'active') {
                    return $item[$key] == \App\Enums\StatusEnum::TRUE->status();
               } elseif ($route == 'inactive') {
                    return $item[$key] == \App\Enums\StatusEnum::FALSE->status();
               } else {
                    return $item[$key] == $value;
               }
          });
     }
}