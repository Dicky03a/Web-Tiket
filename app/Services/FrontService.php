<?php

namespace App\Services;

use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\SellerRepositoryInterface;

class FrontService
{
      protected $categoryRepository;
      protected $ticketRepository;
      protected $sellerRepository;

      public function __construct(
            TicketRepositoryInterface $ticketRepository,
            CategoryRepositoryInterface $categoryRepository,
            SellerRepositoryInterface $sellerRepository
      ) {
            $this->categoryRepository = $categoryRepository;
            $this->ticketRepository = $ticketRepository;
            $this->sellerRepository = $sellerRepository;
      }

      // App\Services\FrontService.php
      public function getFrontPageData()
      {
            $categories = $this->categoryRepository->getAllCategories();
            $sellers = $this->sellerRepository->getAllSellers();
            $popularTickets = $this->ticketRepository->getPopularTickets(4);
            $newTickets = $this->ticketRepository->getAllNewTickets(4);

            return compact('categories', 'sellers', 'popularTickets', 'newTickets');
      }
}
