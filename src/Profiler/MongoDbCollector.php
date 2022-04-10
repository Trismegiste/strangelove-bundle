<?php

/*
 * Strangelove
 */

namespace Trismegiste\Strangelove\Profiler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\DataCollector\AbstractDataCollector;

/**
 * Collector for this bundle
 */
class MongoDbCollector extends AbstractDataCollector
{

    protected $monitoring;

    public function __construct(CollectingSubscriber $subscriber)
    {
        $this->monitoring = $subscriber;
        \MongoDB\Driver\Monitoring\addSubscriber($subscriber);
    }

    public function collect(Request $request, Response $response, \Throwable $exception = null)
    {
        $this->data = [
            'succeed' => $this->monitoring->succeed,
            'failed' => $this->monitoring->failed,
            'started' => $this->monitoring->started,
        ];
    }

    public function getName(): string
    {
        return 'strangelove';
    }

    public function getSuccess(): int
    {
        return $this->data['succeed'];
    }

    public function getFail(): int
    {
        return $this->data['failed'];
    }

    public function getStart(): int
    {
        return $this->data['started'];
    }

}
