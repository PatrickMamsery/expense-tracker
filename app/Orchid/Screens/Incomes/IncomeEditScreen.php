<?php

namespace App\Orchid\Screens\Incomes;

use Orchid\Screen\Screen;
use App\Models\Income;

use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Alert;

class IncomeEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Income Management';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'Edit Income';

    public $income;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Income $income): array
    {
        $this->exists = $income->exists;

        if ($this->exists) {
            $this->income = $income;
            $this->name = 'Edit Income';
            $this->description = 'Update income details';
        }

        return [
            'income' => $income,
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
            Button::make('Create')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee(!$this->exists),

            Button::make('Update')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee($this->exists),

            Button::make('Delete')
                ->icon('trash')
                ->method('delete')
                ->canSee($this->exists),
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
            Layout::rows([
                Group::make([
                    Input::make('income.title')
                        ->title('Title')
                        ->required()
                        ->placeholder('Income Title')
                        ->help('Enter the name of the income'),

                    Input::make('income.amount')
                        ->title('Amount')
                        ->type('number')
                        ->placeholder('5000')
                        ->help('Enter the amount of the income'),
                ]),

                Group::make([
                    DateTimer::make('income.entry_date')
                        ->title('Entry Date')
                        ->placeholder('Income Date')
                        ->help('Enter the date of the income'),

                    Select::make('income.category_id')
                        ->title('Category')
                        ->fromModel(\App\Models\Category::class, 'name')
                        ->help('Select the category of the income'),
                ]),


                TextArea::make('income.description')
                    ->title('Description')
                    ->placeholder('Income Description')
                    ->help('Enter the description of the income'),
            ])
        ];
    }

    /**
     * @param Income $income
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Income $income, Request $request)
    {
        $income->user_id = auth()->user()->id;
        $income->fill($request->get('income'))->save();

        Alert::info('You have successfully created an income.');

        return redirect()->route('platform.incomes');
    }

    /**
     * @param Income $income
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(Income $income)
    {
        $income->delete();

        Alert::info('You have successfully deleted the income.');

        return redirect()->route('platform.incomes');
    }
}
