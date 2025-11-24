<?php

namespace App\Filament\Resources\SubcategoryResource\Pages;

use App\Filament\Resources\SubcategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSubcategory extends CreateRecord
{
    protected static string $resource = SubcategoryResource::class;

    protected function getRedirectUrl(): string
    {
        // Redirect to the list page after creating
        return $this->getResource()::getUrl('index');
    }
}
