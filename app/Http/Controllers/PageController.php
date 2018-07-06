<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Page;

class PageController extends Controller
{
    //

    public function execute() {

        if(view()->exists('admin.pages')) {

            $pages = Page::all();

            $data = [
                'title'=>'Страницы',
                'pages'=>$pages
            ];

            return view('admin.pages',$data);
        }

    }
}
