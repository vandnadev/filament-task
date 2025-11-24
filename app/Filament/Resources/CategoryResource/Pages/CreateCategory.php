<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Category;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getRedirectUrl(): string
    {
        // Redirect to the list page after creating
        return $this->getResource()::getUrl('index');
    }


    protected function getFormSchema(): array
    {
        return [
            Repeater::make('categories')
                ->label('Categories')
                ->schema([
                    TextInput::make('name')
                        ->label('Category Name')
                        ->required()
                        ->maxLength(255),
                ])
                ->columns(1)
                ->createItemButtonLabel('Add More')
                ->minItems(1),
        ];
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        foreach ($data['categories'] as $category) {
            Category::create([
                'name' => $category['name'],
            ]);
        }
        // Return last created row
        return Category::latest()->first();
    }
}
