<?php

declare(strict_types = 1);

namespace App\DTO\Abstracts;

use Illuminate\Support\Collection;

/**
 * Class PaginateDTO
 * @package App\DTO\Abstracts
 */
class PaginateDTO extends DTO
{
    /**
     * @var Collection
     */
    protected $content;

    /**
     * PaginateDTO constructor.
     */
    public function __construct()
    {
        $this->content = collect();
    }

    public function setData(?DTO $data = null): PaginateDTO
    {
        if ($data === null) {
            $data = [];
        }

        $this->content->put('items', $data);

        return $this;
    }

    public function setCurrentPage(int $currentPage): PaginateDTO
    {
        $this->content->put('current_page', $currentPage);

        return $this;
    }

    public function setTotal(int $total): PaginateDTO
    {
        $this->content->put('total', $total);

        return $this;
    }

    public function setPerPage(int $perPage): PaginateDTO
    {
        $this->content->put('per_page', $perPage);

        return $this;
    }

    /**
     * @return array
     */
    protected function jsonData(): array
    {
        return $this->content->toArray();
    }
}