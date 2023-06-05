<?php

namespace App\Orchid\Layouts\Category;

use App\Models\Category;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CategoryListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'categories';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('name', 'Name')
                ->sort()
                ->filter(TD::FILTER_TEXT)
                ->render(function (Category $category) {
                    return ModalToggle::make($category->name)
                        ->modal('editCategoryModal')
                        ->method('createOrUpdate')
                        ->modalTitle('Edit Category')
                        ->asyncParameters([
                            'category' => $category->id,
                        ]);
                }),

            TD::make('actions', 'Actions')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Category $category) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            ModalToggle::make('Edit')
                                ->icon('pencil')
                                ->modal('editCategoryModal')
                                ->method('createOrUpdate'),

                            Button::make('Delete')
                                ->icon('trash')
                                ->method('remove')
                                ->confirm('Are you sure you want to delete this category?')
                                ->parameters([
                                    'id' => $category->id,
                                ]),
                        ]);
                }),
        ];
    }
}
