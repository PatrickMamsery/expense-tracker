<?php

namespace App\Orchid\Screens\Expenses;

use Orchid\Screen\Screen;
use App\Models\Expense;
use Orchid\Screen\Actions\Link;
use App\Orchid\Layouts\Expenses\ExpenseListLayout;

class ExpenseListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Expenses';

    public $description = 'List of expenses';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'expenses' => Expense::with('user')->latest('updated_at')->paginate(),
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            Link::make('Add')
                ->icon('plus')
                ->route('platform.expenses.edit', null),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            ExpenseListLayout::class,
        ];
    }
}
