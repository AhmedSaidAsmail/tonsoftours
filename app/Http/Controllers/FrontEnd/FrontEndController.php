<?php

namespace App\Http\Controllers\FrontEnd;

use App\Models\Category;
use App\Models\Item;
use App\Models\MainCategory;
use App\Models\Topic;
use App\Src\WishList\WishList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FrontEndController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function welcomePage()
    {
        visitors('home');
        $topItems = Item::where('recommended', 1)
            ->where('status', 1)
            ->orderBy('arrangement', 'DESC')
            ->limit(12)
            ->get();
        return view('frontEnd.welcome', ['topItems' => $topItems]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \RuntimeException
     */
    public function searchTours(Request $request)
    {
        if ($request->ajax()) {
            $keyword = $request->get('keywords');
            $matching = Item::where('title', 'like', '%' . $keyword . '%')
                ->get();
            return view('frontEnd.layouts._searchTour', ['items' => $matching, 'keyword' => $keyword]);
        }
        throw new \RuntimeException('This is not ajax request');
    }

    /**
     * @param string $name Main Category Name
     * @param int $id Main Category ID
     * @return \Illuminate\Http\Response
     */
    public function mainCategoryShow($name, $id)
    {
        $mainCategory = MainCategory::findOrFail($id);
        visitors($mainCategory->title);
        $topItems = $mainCategory
            ->items()
            ->where('items.recommended', 1)
            ->orderBy('items.arrangement', 'DESC')
            ->limit(3)
            ->get();
        return view('frontEnd.mainCategory',
            ['mainCategory' => $mainCategory, 'name' => $name, 'topItems' => $topItems]);

    }

    /**
     * Displaying category and listing of related items
     *
     * @param string $name
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function categoryShow($name, $id)
    {
        $category = Category::find($id);
        visitors($category->title);
        $otherCategories = Category::where('id', '!=', $id)->get();
        return view('frontEnd.category',
            ['name' => $name, 'category' => $category, 'otherCategories' => $otherCategories]);
    }

    /**
     * Displaying Item Details from storage
     *
     * @param $category
     * @param $name
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function itemShow($category, $name, $id)
    {
        $item = Item::findOrFail($id);
        $wishList = new WishList();
        visitors($item->title, $id);
        return view('frontEnd.item', [
            'categoryName' => $category,
            'name' => $name,
            'item' => $item,
            'is_wish_list' => $wishList->check($id)
        ]);
    }

    public function topicShow($name)
    {
        $topic = Topic::where('name', $name)->first();
        switch (true) {
            case strtolower($name) == "home":
                return redirect()->route('home');
            case !is_null($topic):
                return view('frontEnd.topic', ['topic' => $topic]);
            default:
                throw new NotFoundHttpException();
        }
    }

    public function notFound()
    {
        return view('frontEnd.error404');
    }
}
