<?php

namespace App\Services;

use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\NarahubungRepositoryInterface;

class FrontService
{
      protected $categoryRepository;
      protected $ticketRepository;
      protected $narahubungRepository;

      public function __construct(
            TicketRepositoryInterface $ticketRepository,
            CategoryRepositoryInterface $categoryRepository,
            NarahubungRepositoryInterface $narahubungRepository
      ) {
            $this->categoryRepository = $categoryRepository;
            $this->ticketRepository = $ticketRepository;
            $this->narahubungRepository = $narahubungRepository;
      }

      // App\Services\FrontService.php
      public function getFrontPageData()
      {
            $categories = $this->categoryRepository->getAllCategories();
            $narahubung = $this->narahubungRepository->getAllNarahubung();
            $popularTickets = $this->ticketRepository->getPopularTickets(4);
            $newTickets = $this->ticketRepository->getAllNewTickets();

            return compact('categories', 'narahubung', 'popularTickets', 'newTickets');
      }
}
