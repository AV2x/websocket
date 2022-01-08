<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/test', function () {
    return view('test');
});

Route::get('/order', function () {
    return view('order');
});
Route::get('/orders', function () {
    return view('orders');
});
Route::get('/rooms', function () {
    return view('rooms');
});
Route::get('/room', function (\Illuminate\Http\Request $request) {
    if($request->input('id') == 1){
        $room_name = 'one';
    }
    elseif($request->input('id') == 2){
        $room_name = 'two';
    }
    else{
        $room_name = 'three';
    }
    return view('room', ['id' => $request->input('id'), 'room_name' => $room_name, 'name' => $request->input('name')]);
});
Route::post('/send_message', function (\Illuminate\Http\Request $request) {

    return response()->json(true);
});
Route::post('/order', function (\Illuminate\Http\Request $request) {
    $data = [
        'message' => 'new order',
        'value' => [
            'product' => $request->input('product'),
            'name' => $request->input('name')
        ]
    ];
    $order = json_encode($data);
    $client = new WebSocket\Client("wss://websocket.dmitry-povyshev.ru/wss");
    $client->text('{"message": "new room", "value": "one"}');
    $client->text($order);
    $client->receive();
    $client->close();
    return response()->redirectTo('/order');
})->name('order.store');

