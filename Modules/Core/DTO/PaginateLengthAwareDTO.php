<?php

declare(strict_types = 1);

namespace Modules\Core\DTO;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class PaginateLengthAwareDTO
 * @package Modules\Core\DTO
 */
class PaginateLengthAwareDTO extends PaginateDTO
{
    /**
     * @var LengthAwarePaginator
     */
    private $paginator;

    /**
     * PaginateLengthAwareDTO constructor.
     * @param LengthAwarePaginator $paginator
     */
    public function __construct(LengthAwarePaginator $paginator)
    {
        parent::__construct();

        $this->paginator = $paginator;

        $this->setDefaultData();
    }

    /**
     * @return void
     */
    private function setDefaultData(): void
    {
        $lastPage = $this->paginator->lastPage();

        $this->setCurrentPage($this->paginator->currentPage())
            ->setData()
            ->setTotal($this->paginator->total())
            ->setPerPage($this->paginator->perPage())
            ->setFirstPageUrl($this->paginator->url(1))
            ->setLastPageUrl($this->paginator->url($lastPage))
            ->setNextPageUrl($this->paginator->nextPageUrl())
            ->setPrevPageUrl($this->paginator->previousPageUrl());
    }
}