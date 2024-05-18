<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;  
use Filament\Forms\Components\Card;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Modal\Actions\Action;
use App\Filament\Resources\CustomerResource\Pages;
use AnourValar\EloquentSerialize\Tests\Models\Post;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CustomerResource\RelationManagers;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Collections';

    protected static ?string $navigationLabel = 'Customer';

    protected static ?int $navigationSort = 0;

    public static function getNavigationBadge(): ?string
    {
        return number_format(static::getModel()::count());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                ->schema(([
                    TextInput::make('name')
                        ->placeholder('Enter a title')
                        ->live(true)
                        ->afterStateUpdated(function (Get $get, Set $set, string $operation, ?string $old, ?string $state) {
                            if (($get('slug') ?? '') !== Str::slug($old) || $operation !== 'create') {
                                return;
                            }

                            $set('slug', Str::slug($state));
                        })
                        ->required()
                        ->maxLength(255)
                        ->autofocus(),
                    TextInput::make('slug')
                        ->placeholder('Enter a slug')
                        ->alphaDash()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                ])), 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Customer')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('Slug Customer')
                    ->sortable()
            ])
            ->filters([
                //
            ])
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}