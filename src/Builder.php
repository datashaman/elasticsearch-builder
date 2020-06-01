<?php

namespace Datashaman\Elasticsearch\Builder;

class Builder
{
    /**
     * Sort order used in the query
     *
     * @var string|array
     */
    protected $sort = null;

    /**
     * Collection of filters used in the query
     *
     * @var array
     */
    protected $filters;

    /**
     * Collection of queries (text search) used in the query
     *
     * @var array
     */
    protected $queries;

    /**
     * @var AggsBuilder
     */
    public $aggs;

    /**
     * @var bool
     */
    protected $randomise = false;

    /**
     * Create a query builder for Elasticsearch
     */
    public function __construct()
    {
        $this->aggs = new AggsBuilder();
        $this->filters = [];
        $this->queries = [];
    }

    /**
     * Randomise the result set
     *
     * @param bool $randomise
     *
     * @return Builder
     */
    public function randomise(bool $randomise): self
    {
        $this->randomise = $randomise;

        return $this;
    }

    /**
     * Set the sort order for the query, and return the builder for Fluent interface
     *
     * @param array|string $sort Sort order to be used.
     *
     * @return Builder
     */
    public function sort($sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Add a filter of a specified type to the query
     *
     * @param string $type Type of filter to be used.
     * @param mixed $config Config for the filter used, usually an array.
     * @param string $bool Which part of the bool filter this should be added to, defaults to 'must'.
     *
     * @return Builder
     */
    public function filter(string $type, $config, string $bool = 'must'): self
    {
        $this->filters[] = [$bool, [$type => $config]];

        return $this;
    }

    /**
     * Add a query (text search) of a specified type to the query
     *
     * @param string $type Type of query to be used.
     * @param array $config Config array for the query used.
     * @param string $bool Which part of the bool filter this should be added to, defaults to 'must'.
     *
     * @return Builder
     */
    public function query(string $type, $config, string $bool = 'must'): self
    {
        $this->queries[] = [$bool, [$type => $config]];

        return $this;
    }

    /**
     * Generate an array for use with Elasticsearch (the main output of this class)
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = [];

        $filterBase = !$this->queries
            ? 'query.constant_score.'
            : 'query.bool.';
        $queryBase = 'query.bool.';

        foreach ($this->queries as $query) {
            list($bool, $defn) = $query;
            $dest = array_get($array, "{$queryBase}$bool", []);
            $dest[] = $defn;
            array_set($array, "{$queryBase}$bool", $dest);
        }

        foreach ($this->filters as $filter) {
            list($bool, $defn) = $filter;
            $dest = array_get($array, "{$filterBase}filter.bool.$bool", []);
            $dest[] = $defn;
            array_set($array, "{$filterBase}filter.bool.$bool", $dest);
        }

        if ($this->aggs->hasItems()) {
            array_set($array, 'aggs', $this->aggs->toArray());
        }

        if ($this->randomise) {
            $query = $array['query'] ?? [];
            $array['query'] = [
                'function_score' => [
                    'functions' => [
                        [
                            'random_score' => new \stdClass(),
                        ],
                    ],
                    'boost_mode' => 'replace',
                ],
            ];
            if ($query) {
                $array['query']['function_score']['query'] = $query;
            }
        } elseif ($this->sort) {
            $array['sort'] = $this->sort;
        }

        return $array;
    }

    /**
     * Helper class to generate a pretty JSON representation of the array generated.
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }
}
