<?php

namespace App\Filament\Resources\NarahubungResource\Pages;

use App\Filament\Resources\NarahubungResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNarahubung extends EditRecord
{
    protected static string $resource = NarahubungResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}