<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Page;
use App\Service;
use App\Portfolio;
use App\People;
use App\Product;
use Mail;
use Faker\Provider\DateTime;

use DB;


class IndexController extends Controller
{
    //
    
    public function execute(Request $request) {
    	
    	
    	
    	if($request->isMethod('post')) {

			$messages = [
			
			'required' => "Поле :attribute обязательно к заполнению",
			'email' => "Поле :attribute должно соответствовать email адресу"
			
			];
			
			$this->validate($request,[
			
			'name' => 'required|max:255',
			'email' => 'required|email',
			'text' => 'required'	
					
			], $messages);
			
			
			$data = $request->all();
			
			$result = Mail::send('site.email',['data'=>$data], function($message) use ($data) {
				
				$mail_admin = env('MAIL_ADMIN');
				
				$message->from($data['email'],$data['name']);
				$message->to($mail_admin,'Mr. Admin')->subject('Question');
				
				
			});
			
			if($result) {
				return redirect()->route('home')->with('status', 'Email is send');
			}
			
			//mail

		}
    	
    	$product = Product::whereDate('date_start','<',Carbon::create('2016','05','05'))
            ->whereDate('date_end','>',Carbon::create('2016','05','05'))
            ->get();


        $dif=3650;
        $current_product=[];
        $date_START_old = date('Y-m-d');
        $date_END_old = date('Y-m-d');
    	foreach ($product as $item){
            $formatted_dt1=Carbon::parse($item['date_start']);
            $formatted_dt2=Carbon::parse($item['date_end']);
            $date_dif=$formatted_dt1->diffInDays($formatted_dt2);

            /** check date what is shorter */
            if($dif>$date_dif){
                $date_START_old = $item['date_start'];
                $date_END_old = $item['date_end'];

                $dif=$date_dif;
//                echo $date_dif;

                    $current_product= $item;
            }

            /** check what date end is latter */
            if($dif==$date_dif){
                if($item['date_end'] > $date_END_old){
                    $current_product= $item;
                }
            }
        }

//        $product = Product::all();
//    	print_r($current_product);
    	$pages = Page::all();
    	$portfolios = Portfolio::get(array('name','filter','images'));
    	$services = Service::where('id','<',20)->get();
    	$peoples = People::take(3)->get();
    	
    	$tags = DB::table('portfolios')->distinct()->lists('filter');
    	
    	
    	$menu = array();
    	foreach($pages as $page) {
			$item = array('title' =>$page->name,'alias'=>$page->alias);
			array_push($menu,$item);
		}
		
//		$item = array('title'=>'Services','alias'=>'service');
//		array_push($menu,$item);
//
//		$item = array('title'=>'Portfolio','alias'=>'Portfolio');
//		array_push($menu,$item);
//
//		$item = array('title'=>'Team','alias'=>'team');
//		array_push($menu,$item);
//
//		$item = array('title'=>'Contact','alias'=>'contact');
//		array_push($menu,$item);
		return view('site.index',array(
									'menu'=> $menu,
									'pages' => $pages,
									'services' => $services,
									'portfolios' => $portfolios,
									'peoples' => $peoples,
									'tags'=>$tags,
                                    'product'=>$current_product
									));
	}
    
}
