<?php

namespace App\Filament\Resources\AprovedResource\Pages;

use App\Filament\Resources\AprovedResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAproved extends EditRecord
{
    protected static string $resource = AprovedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
