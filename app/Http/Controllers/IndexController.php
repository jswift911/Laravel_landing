<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\{Page,People,Portfolio,Service};
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function execute(Request $request) {

        // Везде можно all(), просто для различных примеров разная выборка
        $pages = Page::all();
        $portfolios = Portfolio::get(['name','filter','images']);
        $services = Service::where('id','<',20)->get();
        $peoples = People::take(3)->get();
        // Фильтры
        // lists переименован в pluck в версиях > 5.2
        // distinct - уникальные (неповторяющиеся) данные
        $tags = DB::table('portfolios')->distinct()->pluck('filter');


        // Создаем меню из бд
        $menu = [];
        foreach ($pages as $page) {
            $item = ['title' => $page->name, 'alias' => $page->alias];
            // array_push($menu,$item);

            // Тоже самое что и array_push, но на современный лад
            $menu[] = $item;
        }

        //Добавляем в меню пункты вручную
        $item = ['title' => 'Services', 'alias' => 'service'];
        $menu[] = $item;

        $item = ['title' => 'Portfolio', 'alias' => 'Portfolio'];
        $menu[] = $item;

        $item = ['title' => 'Team', 'alias' => 'team'];
        $menu[] = $item;

        $item = ['title' => 'Contact', 'alias' => 'contact'];
        $menu[] = $item;

        //Добавляем в меню пункты вручную
        //$item = ['title' => 'Test', 'alias' => 'test'];
        //$menu[] = $item;

        // Передаем данные в вид
        return view('site.index', [
            'menu' => $menu,
            'pages' => $pages,
            'services' => $services,
            'portfolios' => $portfolios,
            'peoples' => $peoples,
            'tags' => $tags,
        ]);

    }
}
