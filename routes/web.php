<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Validator;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('login',function(Request $request){
    $rules = [
        'email'                 => 'required|email',
        'password'              => 'required|string'
    ];

    $messages = [
        'email.required'        => 'Email required',
        'email.email'           => 'Email not valid',
        'password.required'     => 'Password requied',
        'password.string'       => 'Password allwed as string'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if($validator->fails()){
        return redirect()->back()->withErrors($validator)->withInput($request->all);
    }

    $data = [
        'email'     => $request->input('email'),
        'password'  => $request->input('password'),
    ];

    Auth::attempt($data);

    if (Auth::check()) { // true sekalian session field di users nanti bisa dipanggil via Auth
        //Login Success
        Session::flash('success', 'Login succees');
        $user = App\User::find(Auth::user()->id);
        $user->api_token = Str::random(30);
        $user->attemp = 2;
        $user->save();

        return redirect('/');


    } else { // false

        //Login Fail
        Session::flash('error', 'Incorect Email or password');
        return redirect('/');
    }
})->name('login');

Route::get('logout',function(){
    Auth::logout(); // menghapus session yang aktif
    return redirect('/');
})->name('logout');

Route::prefix('api/v1')->group(function(){
   
    Route::get('quotes/{apiToken}',function($apiToken){ 
        if(Auth::user()->api_token != $apiToken){
            Abort(403,'token invalid');
        }
        $index=0;
        $datas =[];
        while ($index<5) {
            $response= Http::get('https://api.kanye.rest/');
            array_push($datas,$response->json());
            $index ++;
        }
        //save how manyrequest to session

        return response()->json([
            'success'=>true,
            'datas' =>$datas
        ]);
    });

})
?>