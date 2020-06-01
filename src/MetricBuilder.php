<?php

namespace Datashaman\Elasticsearch\Builder;

class MetricBuilder
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var mixed
     */
    protected $config;

    /**
     * Create a metric builder (used by higher order class Builder).
     *
     * @param string $type Type of metric to build.
     * @param mixed $config Config for the metric to be built. Typically an array.
     */
    public function __construct(string $type, $config)
    {
        $this->type = $type;
        $this->config = $config;
    }

    /**
     * Return the built metric for use in the query
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = [$this->type => $this->config];

        return $array;
    }
}
