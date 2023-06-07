<?php

namespace App\Orchid\Screens\Incomes;

use Orchid\Screen\Screen;
use App\Models\Income;
use Orchid\Screen\Actions\Link;
use App\Orchid\Layouts\Incomes\IncomeListLayout;
use Orchid\Support\Facades\Toast;

class IncomeListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Incomes';

    public $description = 'List of sources of income';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'incomes' => Income::with('user')->latest('updated_at')->paginate()
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
                ->route('platform.incomes.edit', null),
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
            IncomeListLayout::class
        ];
    }

    public function remove(Income $income)
    {
        // detach category and user and delete income
        $income->category()->dissociate();
        $income->user()->dissociate();
        
        $income->delete();

        Toast::info(__('Income record was removed'));

        return redirect()->route('platform.incomes');
    }
}
