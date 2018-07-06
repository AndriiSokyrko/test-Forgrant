<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;


use Validator;
use App\Product;

class ProductAddController extends Controller
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
				return redirect()->route('productsAdd')->withErrors($validator)->withInput();
			}
			
			if($request->hasFile('images')) {
				$file = $request->file('images');
			
				$input['images'] = $file->getClientOriginalName();
				
				$file->move(public_path().'/assets/img',$input['images']);
			
			}
			
			$page = new Product();
			
			
			//$page->unguard();
			
			$page->fill($input);
			
			if($page->save()) {
				return redirect('admin')->with('status','Страница добавлена');
			}
			
		}
    	
    
		if(view()->exists('admin.products_add')) {

            $data = [
                'title' => 'Новая страница'
            ];
			return view('admin.products_add',$data);
			
		}
		
		abort(404);
		
		
	}
}
