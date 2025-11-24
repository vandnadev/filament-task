<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Columns\ImageColumn;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'User';
    protected static ?string $modelLabel = 'Users';
    protected static ?string $pluralModelLabel = 'Users';


    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                ->required()
                ->maxLength(255),

            TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),

            // Image upload
            FileUpload::make('image')
                ->label('Profile Image')
                ->image()
                ->directory('user-images')
                ->disk('public')  // important!
                ->required(fn(string $context) => $context === 'create'),

            // Gender selection
            Select::make('gender')
                ->options([
                    'male' => 'Male',
                    'female' => 'Female',
                    'other' => 'Other',
                ])
                ->required(),

            // Address input
            TextInput::make('address')
                ->label('Address')
                ->maxLength(255)
                ->required(),

            //  Multiple hobbies
            CheckboxList::make('hobbies')
                ->label('Hobbies')
                ->options([
                    'reading' => 'Reading',
                    'traveling' => 'Traveling',
                    'sports' => 'Sports',
                    'music' => 'Music',
                ])
                ->columns(2)
                ->required()
                ->afterStateHydrated(function ($component, $state) {
                    if (is_array($state)) {
                        $component->state($state); // already an array
                    } elseif (is_string($state)) {
                        $component->state(explode(',', $state)); // convert string to array
                    } else {
                        $component->state([]); // default empty
                    }
                })
                ->dehydrateStateUsing(fn($state) => implode(',', $state ?? [])),

            TextInput::make('password')
                ->password()
                ->required(fn(string $context): bool => $context === 'create')
                ->dehydrateStateUsing(fn($state) => !empty($state) ? bcrypt($state) : null)
                ->hidden(fn(string $context) => $context === 'edit') // hide on edit
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                ImageColumn::make('image')
                    ->disk('public')
                    ->label('Profile Image')
                    ->rounded(),
                TextColumn::make('gender')->searchable(),
                TextColumn::make('address')
                    ->label('Address')
                    ->searchable()
                    ->view('filament.components.address-read-more'),

                TextColumn::make('hobbies')->searchable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([])

            // Disable row click by setting recordUrl to null
            ->recordUrl(fn($record) => null)

            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
