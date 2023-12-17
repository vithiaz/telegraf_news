<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridColumns;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;

final class NewstListTable extends PowerGridComponent
{
    public function setUp(): array
    {
        return [
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Post::with(['category', 'user']);
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('id')
            ->addColumn('title')
            ->addColumn('title_slug')
            ->addColumn('category', fn (Post $model) => $model->category != null ? $model->category->name : '')
            ->addColumn('user', fn (Post $model) => $model->user->first_name)
            ->addColumn('created_at')
            ->addColumn('created_at_formatted', fn (Post $model) => translate_date($model->created_at));
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->searchable()
                ->hidden(),

            Column::make('Tanggal', 'created_at')
                ->hidden(),

            Column::make('Tanggal', 'created_at_formatted', 'created_at')
                ->searchable(),
            
            Column::make('Judul', 'title'),
            
            Column::make('Kategori', 'category'),
            
            Column::make('Oleh', 'user'),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            // Filter::inputText('name'),
            Filter::datepicker('created_at_formatted', 'created_at'),
        ];
    }

    #[\Livewire\Attributes\On('edit')]
    public function edit($rowId): void
    {
        $this->js('alert('.$rowId.')');
    }

    public function actions(\App\Models\Post $row): array
    {
        return [
            Button::add('edit')
                ->slot('Edit')
                // ->id()
                ->class('edit-post-button')
                ->route('admin-edit-post', ['target' => strval($row->id) . '-' . strval($row->title_slug)]),
        ];
    }

    /*
    public function actionRules(\App\Models\Post $row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
