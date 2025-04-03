<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
trait Filterable
{
    
    /**
     * scope search filter
     *
     * @param Builder $query
     * @param array $params
     * @param boolean $like
     * @return Builder
     */
    public function scopeSearch(Builder $query, array $params, bool $like = true): Builder
{
    $search = request()->input(key: "search");
    if (!$search) return $query;
    $search = $like ? "%$search%" : $search;

    return $query->where(column: function (Builder $q) use ($params, $search): void {
        collect($params)->each(function (string $param) use ($q, $search): void {
            if (strpos($param, '|') !== false) {
                
                $columns = explode('|', $param);
                $q->orWhereRaw(
                    "REPLACE(CONCAT(" . implode(', ', array_map(fn($col) => "`$col`", $columns)) . "), ' ', '') LIKE ?",
                    [str_replace(' ', '', $search)]
                );
            } else {
                $q->when(
                    value: (strpos($param, ':') !== false),
                    callback: fn(Builder $q): Builder => $this->searchRelationalData(query: $q, relations: $param, search: $search),
                    default: fn(Builder $q): Builder => $q->orWhere(column: $param, operator: 'LIKE', value: $search)
                );
            }
        });
    });
}

    /**
     * Scope filter
     *
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function scopeFilter(Builder $query,array $params): Builder{

        $filters   = array_keys(request()->all());

        collect(value: $params)->map(callback: function(string $param) use($query,$filters) : Builder{

            return $query->when(value: (strpos(haystack: $param, needle: ':') !== false),
                        callback: fn(Builder $q): Builder => 
                              $this->filterRelationalData(query: $query, relations: $param, filters: $filters),
                                    default: fn(Builder $query): Builder =>
                                        $query->when(value: in_array(needle: $param, haystack: $filters) && request()->input($param) !== null , 
                                            callback: fn(Builder $query): Builder => $query->when(value: gettype(value: request()->input(key: $param)) === 'array',
                                                callback: fn(Builder $query) : Builder => $query->whereIn(column: $param,  values: request()->input(key: $param)),
                                                   default: fn(Builder $query) : Builder =>  $query->where(column: $param, operator: request()->input(key: $param)))));
                        });

        return $query;


    }

    /**
     * Date Filter
     *
     * @param Builder $query
     * @param string $column
     * @return Builder
     */
    public function scopeDate(Builder $query, string $column = 'created_at') : Builder {
        try {
            if (!request()->input('date')) return $query;
    
            $dateRangeString = request()->input('date');
            $dateRangeString = preg_replace('/\s*to\s*/', ' - ', $dateRangeString);
            $start_date = $dateRangeString;
            $end_date = $dateRangeString;
    
            if (strpos(haystack: $dateRangeString, needle: ' - ') !== false) {
                list($start_date, $end_date) = explode(separator: " - ", string: $dateRangeString);
            }
    
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $start_date)) {
                $start_date = Carbon::createFromFormat('m/d/Y', $start_date)->format('Y-m-d');
            }
    
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $end_date)) {
                $end_date = Carbon::createFromFormat('m/d/Y', $end_date)->format('Y-m-d');
            }
            return $query->where(fn (Builder $query): Builder =>  
                $query->whereBetween(column: $column, values: [$start_date, $end_date])
                      ->orWhereDate(column: $column, operator: $start_date)
                      ->orWhereDate(column: $column, operator: $end_date)
            );
        } catch (\Throwable $th) {
            return $query;
        }
    }


    /**
     * Search relational data
     *
     * @param Builder $query
     * @param string  $relations
     * @param string $search
     * @return Builder
     */
    private function searchRelationalData(Builder $query,string $relations, string $search): Builder{

        list($relation, $keys) = explode(separator: ":", string: $relations); 
        collect(value: explode(separator: ',',string: $keys))->map(callback: fn(string $column): Builder => 
            $query->orWhereHas( relation: $relation , callback: fn (Builder $q)  : Builder =>  $q->where($column,'like', $search))
        );

        return $query;
    }


    /**
     * Filter relational data
     *
     * @param Builder $query
     * @param string $relations
     * @param array $filters
     * @return Builder
     */
    private function filterRelationalData(Builder $query,string $relations,array $filters): Builder{


        list($relation, $keys) = explode(separator: ":", string: $relations); 

        collect(value: explode(separator: ',', string: $keys))->map( callback: fn(string $column): Builder =>
                $query->when( in_array($relation, $filters) && request()->input( $relation) != null ,
                         fn(Builder $query) :Builder => $query->whereHas( $relation,
                                  fn(Builder $q) :Builder => $q->where($column,request()->input($relation)))));
        return $query;
    }
}