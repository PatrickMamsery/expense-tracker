<?php

namespace App\Orchid\Layouts\Expenses;

use App\Models\Expense;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ExpenseListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'expenses';

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
                ->render(function (Expense $expense) {
                    return Link::make($expense->title)
                        ->route('platform.expenses.edit', $expense);
                }),

            TD::make('category', 'Category')
                ->render(function (Expense $expense) {
                    return ucfirst($expense->category->name);
                }),

            TD::make('amount', 'Amount')
                ->render(function (Expense $expense) {
                    return number_format($expense->amount);
                }),

            TD::make('entry_date', 'Entry Date')
                ->render(function (Expense $expense) {
                    return $expense->entry_date->toFormattedDateString();
                }),

            TD::make('actions', 'Actions')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Expense $expense) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            Link::make('Edit')
                                ->route('platform.expenses.edit', $expense)
                                ->icon('pencil'),

                            Button::make('Delete')
                                ->method('remove')
                                ->confirm('Are you sure you want to delete this expense?')
                                ->parameters([
                                    'id' => $expense->id,
                                ])
                                ->icon('trash'),
                        ]);
                }),
        ];
    }
}
