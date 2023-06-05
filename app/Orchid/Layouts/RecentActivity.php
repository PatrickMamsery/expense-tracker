<?php

namespace App\Orchid\Layouts;

use Illuminate\Database\Eloquent\Collection;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class RecentActivity extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'data';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('title', 'Title')
                ->sort()
                ->render(function ($data) {
                    return $data->title;
                }),

            TD::make('type', 'Type')
                ->render(function ($data) {
                    return $data->type;
                }),

            TD::make('category', 'Category')
                ->render(function ($data) {
                    return ucfirst($data->category->name);
                }),

            TD::make('amount', 'Amount')
                ->render(function ($data) {
                    return number_format($data->amount);
                }),

            TD::make('entry_date', 'Entry Date')
                ->render(function ($data) {
                    return $data->entry_date->toFormattedDateString();
                })
        ];
    }
}
