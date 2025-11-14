<?php

namespace App\Filament\Resources\NarahubungResource\Pages;

use App\Filament\Resources\NarahubungResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNarahubung extends ListRecords
{
    protected static string $resource = NarahubungResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}