<?php

namespace Datashaman\Elasticsearch\Builder;

class BucketBuilder
{
    use BuildsBucketsAndMetrics;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var mixed
     */
    protected $config;

    /**
     * Create a bucket based on an aggregation filter of some sort
     *
     * @param string $type Filter type to create a bucket with
     * @param array $config Config for the type of filter chosen (refer to Elasticsearch documentation).
     */
    public function __construct($type, $config)
    {
        $this->type = $type;
        $this->config = $config;
    }

    /**
     * Generate an array for querying Elasticsearch for this bucket
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = [$this->type => $this->config];

        foreach ($this->buckets as $name => $bucket) {
            array_set($array, "aggs.$name", $bucket->toArray());
        }

        foreach ($this->metrics as $name => $metric) {
            array_set($array, "aggs.$name", $metric->toArray());
        }

        return $array;
    }
}
