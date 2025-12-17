<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Get;
use Filament\Forms\Set;
use FilamentTiptapEditor\TiptapEditor;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Artikel';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Group::make()->schema([
                Forms\Components\Section::make('Konten Artikel')->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Set $set, ?string $state) => 
                            $set('slug', Str::slug($state))
                        ),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(ignoreRecord: true),

                    Forms\Components\Textarea::make('excerpt')
                        ->label('Ringkasan')
                        ->rows(3)
                        ->maxLength(300)
                        ->helperText('Ringkasan singkat untuk preview'),

                    // TiptapEditor - WYSIWYG yang powerful
                    TiptapEditor::make('content')
                        ->label('Konten')
                        ->required()
                        ->profile('default') // atau 'simple', 'full'
                        ->tools([
                            'heading',
                            'bullet-list',
                            'ordered-list',
                            'bold',
                            'italic',
                            'underline',
                            'strike',
                            'link',
                            'media',
                            'align-left',
                            'align-center',
                            'align-right',
                            'blockquote',
                            'code-block',
                            'hr',
                            'undo',
                            'redo',
                        ])
                        ->disk('public') // untuk upload image
                        ->directory('articles/images')
                        ->columnSpanFull(),
                ])->columns(2),

                Forms\Components\Section::make('SEO')->schema([
                    Forms\Components\TextInput::make('meta_title')
                        ->label('Meta Title')
                        ->maxLength(60)
                        ->helperText('Kosongkan untuk menggunakan judul artikel'),

                    Forms\Components\Textarea::make('meta_description')
                        ->label('Meta Description')
                        ->rows(2)
                        ->maxLength(160)
                        ->helperText('Kosongkan untuk menggunakan ringkasan'),
                ])->columns(2)->collapsed(),
            ])->columnSpan(2),

            Forms\Components\Group::make()->schema([
                Forms\Components\Section::make('Publishing')->schema([
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'draft' => 'Draft',
                            'published' => 'Published',
                        ])
                        ->required()
                        ->default('draft')
                        ->live(),

                    Forms\Components\DateTimePicker::make('published_at')
                        ->label('Tanggal Publish')
                        ->default(now())
                        ->visible(fn (Get $get) => $get('status') === 'published'),

                    Forms\Components\Select::make('author_id')
                        ->label('Penulis')
                        ->relationship('author', 'name')
                        ->default(auth()->id())
                        ->required()
                        ->searchable(),
                ]),

                Forms\Components\Section::make('Kategori & Tag')->schema([
                    Forms\Components\Select::make('category_id')
                        ->label('Kategori')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, Set $set) => 
                                    $set('slug', Str::slug($state))
                                ),
                            Forms\Components\TextInput::make('slug')->required(),
                            Forms\Components\Textarea::make('description'),
                        ]),

                    Forms\Components\Select::make('tags')
                        ->label('Tags')
                        ->relationship('tags', 'name')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, Set $set) => 
                                    $set('slug', Str::slug($state))
                                ),
                            Forms\Components\TextInput::make('slug')->required(),
                        ]),
                ]),

                Forms\Components\Section::make('Gambar Utama')->schema([
                    Forms\Components\FileUpload::make('featured_image')
                        ->label('Gambar')
                        ->image()
                        ->directory('articles')
                        ->maxSize(2048)
                        ->imageEditor(),

                    Forms\Components\TextInput::make('image_alt')
                        ->label('Alt Text')
                        ->helperText('Deskripsi gambar untuk SEO'),
                ]),
            ])->columnSpan(1),
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Gambar')
                    ->circular()
                    ->getStateUsing(fn ($record) =>
                        $record->featured_image
                            ? asset('storage/' . $record->featured_image)
                            : null
                    ),


                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('info')
                    ->searchable(),

                Tables\Columns\TextColumn::make('author.name')
                    ->label('Penulis')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'published',
                    ]),

                Tables\Columns\TextColumn::make('views')
                    ->label('Views')
                    ->sortable()
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Publish')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ]),

                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}