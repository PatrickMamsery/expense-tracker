<?php

namespace App\Orchid\Screens\Expenses;

use Orchid\Screen\Screen;
use App\Models\Expense;

use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Alert;

class ExpenseEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Expense Management';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'Edit Expense';

    public $expense;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Expense $expense): array
    {
        $this->exists = $expense->exists;

        if ($this->exists) {
            $this->expense = $expense;
            $this->name = 'Edit Expense';
            $this->description = 'Update expense details';
        }

        return [
            'expense' => $expense,
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
                    Input::make('expense.title')
                        ->title('Title')
                        ->placeholder('Title')
                        ->help('Enter the title of the expense')
                        ->required(),

                    Input::make('expense.amount')
                        ->title('Amount')
                        ->type('number')
                        ->placeholder('1500')
                        ->help('Enter the amount of the expense in TZS')
                        ->required(),
                ]),

                Group::make([
                    DateTimer::make('expense.entry_date')
                        ->title('Entry Date')
                        ->placeholder('Entry Date')
                        ->help('Enter the entry date of the expense')
                        ->required(),

                    Select::make('expense.category_id')
                        ->title('Category')
                        ->fromModel(\App\Models\Category::class, 'name')
                        ->help('Select the category of the expense')
                        ->required(),
                ]),


                TextArea::make('expense.description')
                    ->title('Description')
                    ->placeholder('Description')
                    ->help('Enter the description of the expense')
                    ->rows(5),
            ])
        ];
    }

    /**
     * @param Expense $expense
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Expense $expense, Request $request)
    {
        $expense->user_id = auth()->user()->id;
        $expense->fill($request->get('expense'))->save();

        Alert::info('You have successfully created an expense.');

        return redirect()->route('platform.expenses');
    }

    /**
     * @param Expense $expense
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(Expense $expense)
    {
        $expense->delete();

        Alert::info('You have successfully deleted the expense.');

        return redirect()->route('platform.expenses');
    }
}
