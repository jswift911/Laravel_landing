<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Validator;

class PagesAddController extends Controller
{
    //

    public function execute(Request $request) {

        if($request->isMethod('post')) {
            $input = $request->except('_token');

            $massages = [

                'required'=>'Поле :attribute обязательно к заполнению',
                'unique'=>'Поле :attribute должно быть уникальным'

            ];


            $validator = Validator::make($input,[

                'name' => 'required|max:255',
                'alias' => 'required|unique:pages|max:255',
                'text'=> 'required'

            ], $massages);

            if($validator->fails()) {
                // withInput сохраняет данные в сессию, и будет работать метод old
                return redirect()->route('pagesAdd')->withErrors($validator)->withInput();
            }

            if($request->hasFile('images')) {
                $file = $request->file('images'); // имя поля загрузки на сервер

                $input['images'] = $file->getClientOriginalName(); // Получить оригинальное имя файла, без пути

                $file->move(public_path().'/assets/img',$input['images']); // сохраняем в каталог

            }


            $page = new Page();


            //$page->unguard(); - позволяет снять ограничения на запись полей, чтобы не прописывать $fillable

            $page->fill($input); // Заполняет поля модели данными

            if($page->save()) {
                return redirect('admin')->with('status','Страница добавлена');
            }

        }


        if(view()->exists('admin.pages_add')) {

            $data = [

                'title' => 'Новая страница'

            ];
            return view('admin.pages_add',$data);

        }

        abort(404);


    }
}
