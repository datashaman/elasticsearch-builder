<?php

namespace Datashaman\Elasticsearch\Builder;

trait BuildsBucketsAndMetrics
{
    /**
     * @var array
     */
    protected $buckets = [];

    /**
     * @var array
     */
    protected $metrics = [];

    /**
     * Add a bucket aggregation, and return the bucket builder (for nesting).
     *
     * @param string $name Name to be used in aggregations result
     * @param string $type Type of bucket aggregation to create.
     * @param mixed $config Config for the bucket to be used. Typically an array.
     *
     * @return BucketBuilder
     */
    public function bucket(string $name, string $type, $config): BucketBuilder
    {
        $bucket = new BucketBuilder($type, $config);
        $this->buckets[$name] = $bucket;

        return $bucket;
    }

    /**
     * Add a metric aggregation, and return the metric builder (for nesting).
     *
     * @param string $name Name to be used in aggregations result
     * @param string $type Type of metric aggregation to create.
     * @param mixed $config Config for the metric aggregation to be used. Typically an array.
     *
     * @return MetricBuilder
     */
    public function metric(string $name, string $type, $config): MetricBuilder
    {
        $metric = new MetricBuilder($type, $config);
        $this->metrics[$name] = $metric;

        return $metric;
    }

    /**
     * Return whether this builder has buckets or metrics added to it.
     *
     * @return bool
     */
    public function hasItems(): bool
    {
        return count($this->buckets) > 0 || count($this->metrics) > 0;
    }
}
