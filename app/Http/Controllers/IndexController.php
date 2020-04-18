<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\{Page,People,Portfolio,Service};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class IndexController extends Controller
{
    public function execute(Request $request) {


        if ($request->isMethod('post')) {

            // Форма, валидация
            $messages = [
                'required' => "Поле :attribute обязательно к заполнению",
                'email' => "Поле :attribute должно соответствовать email адресу",
            ];

            $this->validate($request, [
                'name' => 'required|max:255',
                'email' => 'required|email',
                'text' => 'required',
            ], $messages);

            // Сохраняем $request в виде массива, в переменной $data уже массив
            $data = $request->all();

            // Отправка почты (параметры: шаблон письма, данные передаваемые в шаблон, функция с доп. параметрами
            // - куда, кому отправить письмо)
            // use $data - передача переменной $data в функцию
            Mail::send('site.email', ['data' => $data], function ($message) use ($data) {

                $mail_admin = env('MAIL_ADMIN');

                $message->from($data['email'], $data['name']); // от кого письмо
                $message->to($mail_admin, 'Mr. Admin')->subject('Question'); // кому отпарвить письмо. subject() - тема письма
                //return redirect()->route('home')->with('status','Сообщение отправлено');
            });


            // Если ошибок отправки нет
            if (count(Mail::failures()) == 0) {
                return redirect()->route('home')->with('status','Сообщение отправлено');
            }
        }


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
