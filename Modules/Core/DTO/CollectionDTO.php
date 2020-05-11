<?php

declare(strict_types = 1);

namespace Modules\Core\DTO;

use Illuminate\Support\Collection;

/**
 * Class CollectionDTO
 * @package Modules\Core\DTO
 */
class CollectionDTO extends DTO
{
    /**
     * @var Collection
     */
    private $collection;

    /**
     * CollectionDTO constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->collection = collect($data);
    }

    /**
     * @param DTO $item
     * @return $this
     */
    public function pushItem(DTO $item): CollectionDTO
    {
        $this->collection->push($item);

        return $this;
    }

    /**
     * @param string $key
     * @param DTO $item
     * @return $this
     */
    public function putItem(string $key, DTO $item): CollectionDTO
    {
        $this->collection->put($key, $item);

        return $this;
    }

    /**
     * @return array
     */
    protected function jsonData(): array
    {
        return $this->collection->toArray();
    }
}