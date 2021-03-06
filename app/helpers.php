<?php

use App\Models\MainCategory;
use App\Models\Category;
use App\Models\Item;
use App\Http\Controllers\Admin\VarsController;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Src\Sync\Sync;
use App\Src\SocialMedia\FacebookLogin\FacebookSdk;
use App\Src\WishList\WishList;
use App\Http\Controllers\Admin\VisitorsController;
use Illuminate\Support\Facades\App;

if (!function_exists('activeMainCategories')) {
    function activeMainCategories($limit = 4)
    {
        return MainCategory::where('home', '=', '1')
            ->limit($limit)
            ->orderBy('arrangement', 'desc')
            ->get();
    }
}
if (!function_exists('mainCategoriesAll')) {
    function mainCategoriesAll()
    {
        return MainCategory::all();
    }
}
if (!function_exists('categoriesAll')) {
    function categoriesAll()
    {
        return Category::all();
    }
}
if (!function_exists('topTours')) {
    function topTours()
    {
        return Item::where('recommended', 1)
            ->where('status', 1)
            ->orderBy('arrangement')
            ->limit(6)
            ->get();
    }
}
if (!function_exists('getItem')) {
    function getItem($id)
    {
        return Item::find($id);
    }
}
if (!function_exists('translate')) {
    function translate($word)
    {
        return VarsController::translate(str_replace(' ', '_', $word));
    }
}
if (!function_exists('getValue')) {
    function getValue($attr, Item $item = null, $hasOne = null)
    {
        $return = null;
        if (!is_null($item)) {
            $return = $item;
            if (!is_null($hasOne)) {
                $return = $return->$hasOne;
            }
            return $return->$attr;
        }

    }
}
if (!function_exists('itemValueResolve')) {
    function itemValueResolve($attr, Item $item = null, $hasOne = null)
    {
        if (!is_null($item)) {
            return !is_null($hasOne) && !is_null($item->{$attr}) ? $item->{$attr}->{$hasOne} : $item->{$attr};
        }
        return null;

    }
}
if (!function_exists('syncHasMany')) {
    function syncHasMany(HasMany $hasMany, array $data, $key)
    {
        return new Sync($hasMany, $data, $key);
    }
}
if (!function_exists('facebookLink')) {
    function facebookLink()
    {
        return FacebookSdk::linkGeneration();
    }
}
if (!function_exists('wishListsCount')) {
    function wishListsCount()
    {
        $wishList = new WishList();
        return count($wishList->all());
    }
}
if (!function_exists('visitors')) {
    function visitors($title, $item_id = null)
    {
        $url = \Illuminate\Support\Facades\URL::current();
        VisitorsController::store(['url' => $url, 'item_id' => $item_id, 'title' => $title]);
    }
}
if (!function_exists('checkOutSettings')) {
    function checkOutSettings($key)
    {
        $checkoutSetting = \App\Models\TwoCheckOut::first();
        if (!is_null($checkoutSetting)) {
            return $checkoutSetting->{$key};
        }
        return null;
    }
}
if (!function_exists('payment')) {
    /**
     * @return \Payment\Payment
     */
    function payment()
    {
        return App::make('payment');
    }
}

if (!function_exists('shoppingCart')) {
    /**
     * @return \Shopping\Cart\CartManager
     */
    function shoppingCart()
    {
        return App::make('cart');
    }
}
if (!function_exists('upload')) {
    /**
     * @return \Files\Upload\Upload
     */
    function upload()
    {
        return App::make('upload');
    }
}