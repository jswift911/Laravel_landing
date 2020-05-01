<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Page;

class PageController extends Controller
{
    public function execute($alias) {

        if (!$alias) {
            abort(404); // abort создает исключение, в нашем случае страничку 404
        }

        if (view()->exists('site.page')) {

            // Нужная нам страничка
            $page = Page::where('alias', strip_tags($alias))->first(); // strip_tags для безопасности

            $data = [
                'title' => $page->name,
                'page' => $page,
            ];

            return view('site.page', $data);

        } else {
            abort(404);
        }

    }
}
