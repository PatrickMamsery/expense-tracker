<?php

namespace App\Orchid\Screens\Category;

use App\Models\Category;
use App\Orchid\Layouts\Category\CategoryListLayout;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Toast;

class CategoryListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Categories';

    public $description = 'List of all categories';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'categories' => Category::with('user')->latest('updated_at')->paginate()
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
            ModalToggle::make(__('Create Category'))
                ->icon('plus')
                ->modal('editCategoryModal')
                ->method('createOrUpdate')
                ->modalTitle('Create Category')
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
            Layout::modal('editCategoryModal', [
                Layout::rows([
                    Input::make('category.name')
                        ->title('Name')
                        ->required(),
                ])
            ])->async('asyncGetCategory'),

            CategoryListLayout::class
        ];
    }

    public function asyncGetCategory(Category $category = null)
    {
        return [
            'category' => $category
        ];
    }

    public function createOrUpdate(Category $category, Request $request)
    {
        $category->user_id = auth()->user()->id;

        $category->fill($request->get('category'))->save();

        Alert::info('You have successfully created an category.');

        return redirect()->route('platform.categories');
    }

    public function editCategory(Request $request, Category $category)
    {
        $category = Category::findOrFail($request->get('id'));

        dd($category);

        $category->update($request->get('category')['name']);

        Toast::info('You have successfully updated the category.');
    }

    public function remove(Category $category)
    {
        $category->delete();

        Alert::info('You have successfully deleted the category.');

        return redirect()->route('platform.categories');
    }
}
