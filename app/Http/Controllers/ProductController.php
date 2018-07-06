<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Product;

class ProductController extends Controller
{
    //

    public function execute() {

        if(view()->exists('admin.products')) {

            $products = Product::all();

            $data = [
                'title'=>'Страницы',
                'products'=>$products
            ];

            return view('admin.products',$data);
        }

    }
}
