<?php

namespace App\Filament\Resources\AprovedResource\Pages;

use App\Filament\Resources\AprovedResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAproveds extends ListRecords
{
    protected static string $resource = AprovedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
