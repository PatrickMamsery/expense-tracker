<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Expense;
use App\Models\Income;
use App\Orchid\Layouts\ChartsLayout;
use App\Orchid\Layouts\OverviewMetrics;
use App\Orchid\Layouts\RecentActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Expense Tracker';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Welcome to Expense Tracker';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $startDate = Carbon::now()->startOfYear();
        $endDate = Carbon::now();
        $dates = [];
        $expenseAmounts = [];
        $incomeAmounts = [];

        while ($startDate <= $endDate) {
            $formattedDate = $startDate->format('M-Y');
            $dates[] = $formattedDate;

            $expenseAmount = Expense::whereYear('created_at', $startDate->year)
                ->whereMonth('created_at', $startDate->month)
                ->sum('amount');
            $expenseAmounts[] = $expenseAmount;

            $incomeAmount = Income::whereYear('created_at', $startDate->year)
                ->whereMonth('created_at', $startDate->month)
                ->sum('amount');
            $incomeAmounts[] = $incomeAmount;

            $startDate->addMonth();
        }

        $chart_data = [
            [
                'labels' => $dates,
                'name' => 'Expense',
                'values' => $expenseAmounts
            ],
            [
                'labels' => $dates,
                'name' => 'Income',
                'values' => $incomeAmounts
            ]
        ];

        $expenses = floatval(Expense::where('user_id', auth()->id())->sum('amount'));
        $incomes = floatval(Income::where('user_id', auth()->id())->sum('amount'));

        $expense_data = Expense::where('user_id', auth()->user()->id)
                            ->latest('updated_at')
                            ->get()
                            ->map(function ($item) {
                                $item['type'] = 'Expense';
                                return $item;
                            })
                            ->take(5);

                            $income_data = Income::where('user_id', auth()->user()->id)
                            ->latest('updated_at')
                            ->get()
                            ->map(function ($item) {
                                $item['type'] = 'Income';
                                return $item;
                            })
                            ->take(5);
                            // dd(Income::where('user_id', auth()->user()->id)->pluck('amount')->toArray());

        $data = $expense_data->union($income_data);
        // $data = Collection::wrap(Expense::where('user_id', auth()->user()->id)->latest('updated_at')->get()->take(5))->zip(Income::where('user_id', auth()->user()->id)->latest('updated_at')->get()->take(5));

        return [
            'metrics' => [
                [
                    'keyValue' => number_format($incomes, 0),
                    'keyDiff' => 0,
                ],
                [
                    'keyValue' => number_format($expenses, 0),
                    'keyDiff' => 0,
                ],
                [
                    'keyValue' => number_format($incomes - $expenses, 0),
                    'keyDiff' => 0,
                ],
            ],

            'chart_data' => $chart_data,

            'data' => $data
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
            Link::make('Go to Site')
                ->href('https://hype.co.tz')
                ->icon('globe-alt')
                ->target('_blank'),
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): array
    {
        return [
            OverviewMetrics::class,
            ChartsLayout::class,
            RecentActivity::class,
            // Layout::view('home')
        ];
    }
}
