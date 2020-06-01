<?php

namespace Datashaman\Elasticsearch\Builder;

class AggsBuilder
{
    use BuildsBucketsAndMetrics;

    /**
     * Return an array of the aggregations from buckets and metrics defined
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = [];

        foreach ($this->buckets as $name => $bucket) {
            $array[$name] = $bucket->toArray();
        }

        foreach ($this->metrics as $name => $metric) {
            $array[$name] = $metric->toArray();
        }

        return $array;
    }
}
