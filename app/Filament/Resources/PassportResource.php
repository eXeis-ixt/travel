<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\PassportResource\Pages;
use App\Filament\Resources\PassportResource\RelationManagers;
use App\Models\Passport;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;

class PassportResource extends Resource
{
    protected static ?string $model = Passport::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $recordTitleAttribute = 'name';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               Group::make()->schema([

                Section::make('Status')->schema([
                    ToggleButtons::make('status')
                    ->inline()
                    ->options(OrderStatus::class)
                    ->required(),
                ]),

                Forms\Components\Section::make()
                ->schema([
                    Forms\Components\TextInput::make('sl')
                    ->default('SL-' . random_int(100000, 999999))
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->maxLength(32)
                    ->unique(Passport::class, 'sl', ignoreRecord: true),

                    TextInput::make('name')->columnSpan('4'),
                    TextInput::make('passport_no')->columnSpan('4'),
                    TextInput::make('father_name')->columnSpan('4'),
                    TextInput::make('number')->columnSpan('4')->numeric()->maxLength(11)->prefix('+880')->minLength(10),
                    DatePicker::make('passport_expire')->columnSpan('4'),

                ])
                ,



               ])->columnSpanFull(),

               Section::make('Passport Details')->schema([
                FileUpload::make('passport')->image()->imageEditor()->downloadable('passport_no')->disk('public'),
                FileUpload::make('visa')->downloadable()->disk('public')->preserveFilenames()
               ]),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('sl')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('status')
                ->badge(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('number')->searchable()->sortable(),
                TextColumn::make('passport_no')->searchable()->sortable(),
                TextColumn::make('passport_expire')->searchable()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->after(function (Passport $record){
                    if ($record->passport) {
                        Storage::disk('public')->delete($record->passport);
                     }

                     if($record->visa){
                        Storage::disk('public')->delete($record->visa);
                     }


                }),
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

//     public static function getGlobalSearchResultTitle(Passport $record): string
// {
//     return $record->name;
// }

    // public static function getItemsRepeater(): Repeater{


    //    return Repeater::make('passport')->schema([

    //    ]);


    // }
    // public static function getDetailsFormSchema(): array
    // {
    //     return [


    //     ];
    // }





    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPassports::route('/'),
            'create' => Pages\CreatePassport::route('/create'),
            'view' => Pages\ViewPassport::route('/{record}'),
            'edit' => Pages\EditPassport::route('/{record}/edit'),
        ];
    }
}
