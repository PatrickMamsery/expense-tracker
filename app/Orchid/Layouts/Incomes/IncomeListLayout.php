<?php

namespace App\Orchid\Layouts\Incomes;

use App\Models\Income;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class IncomeListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'incomes';

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
                ->filter(TD::FILTER_TEXT)
                ->render(function (Income $income) {
                    return Link::make($income->title)
                        ->route('platform.incomes.edit', $income);
                }),

            TD::make('category', 'Category')
                ->render(function (Income $income) {
                    return ucfirst($income->category->name);
                }),

            TD::make('amount', 'Amount')
                ->render(function (Income $income) {
                    return number_format($income->amount);
                }),

            TD::make('entry_date', 'Entry Date')
                ->render(function (Income $income) {
                    return $income->entry_date->toFormattedDateString();
                }),

            TD::make('actions', 'Actions')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Income $income) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            Link::make('Edit')
                                ->route('platform.incomes.edit', $income)
                                ->icon('pencil'),

                            Button::make('Delete')
                                ->method('remove')
                                ->confirm('Are you sure you want to delete this income record?')
                                ->parameters([
                                    'id' => $income->id,
                                ])
                                ->icon('trash')
                        ]);
                })
        ];
    }
}
