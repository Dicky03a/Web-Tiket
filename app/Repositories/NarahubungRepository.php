<?php

namespace App\Repositories;

use App\Models\Narahubung;
use App\Repositories\Contracts\NarahubungRepositoryInterface;

class NarahubungRepository implements NarahubungRepositoryInterface
{
      public function getAllNarahubung()
      {
            return Narahubung::latest()->get();
      }
}