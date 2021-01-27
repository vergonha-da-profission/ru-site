<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


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

Route::middleware(['auth:sanctum', 'verified'])->get('/', function () {
    return view('dashboard',  ['user' =>  auth()->user()]);
});

Route::post('/api/validate-user', function (Request $request) {
    $user_id = $request->userId;
    $value = -2.5;
    $base_url = 'http://localhost:3000';

    $url = "$base_url/api/transaction/debt";



    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n    \"name\": \"Ticket RU\",\n    \"description\": \"Débito em conta\",\n    \"value\": $value,\n    \"userId\": $user_id\n}",
        CURLOPT_HTTPHEADER => array(
            "x-api-key: MnjgOO_9n8zwA8@vL2y0Ud%VyKYmSvq4flgha@6YVOqnV8GH@FCs1lkCE%pk6Hd**9S@txKh*l96&B5BOYJf_JvBKEmsvwqG2R6a!lyfij72z8O8HB%6KUNXYes*CA0D8AMXje36cVuEPZHXJ%E2rc%gCWHHbXBfiJqgYNJw*UMSd4*OOjHSSm8QeC%R2JRg3m517&&ROemYpbQ%RzdpP!4MtLaIOiMw3wEe8E%hLdeIH38iojMtmaGjl6ayfNWr@CZZx#1Nd*8kTgM*1BAjb#_98*32Zg#rtz_Wkc4v6TnrzBILcORq%l&7JOcgwaNwu2fr6Rx1UlWVHAnbfnzaRKvR6ca!j74_6tWfoMSAeW7Bn_7vI2elr6VNfuiTT9REnPU1BT6A*zBB9foDQ7KjCv3rmRYit*E2ZijJnR%I0Rp2w*hmt5Ou3@732GCenwzVsS7iJDamH0jSr8GXTPb_M#q4X#23QzEu!w4LJCOGpRELxeTXv4RU3R*aDFti5kkFOf4w723z8jinfd4*wPiOqa#*ompLgTJ27!oAk9GJ*Uqsb7AG%WH%UiImWAezYdvZaRE2X4scj5xB_67aU5_hM3U!OkmrqP9E4O_aLzVPVnaayLZ3E#v5PXyH0Dni%GXXAUZoiYTaZBRXue4tcUp5#@mV1iUnoQ_wMxC#sw*F627a_ZDQqlQH62Kk@xcm6y!6SPk8QVC0w&HTX*PNgz22&8ZZr_j8nCxg!B*#vRE1iDDjwi5%gHg8!WUD8wevq5i1tv!yDq8pwTcTxR7ZVMSxiipRs7vyvI4oToth3FwbsDri#3KOWpP#tB2roWQlzExq9e%SjVd%2x%r5l!_#WKQ*Q8d!ePA*FqvNS#53oJ5VdMokKygoRS__e8JVf@AmYcQzrWO_4xPyd#TIZpZ*I9Txo5oU747EShWJBzpN_svEXRCwROdW0bA9DdD5tFF*1T23jP3xjn5&hkGFmcuJMSB8cWWDGB7rwCSN351g!2IRsvs5QoToUF3qgMEPkt0zAUb",
            "Content-Type: application/json"
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    
    $res = json_decode($response);

    if($res->message !== 'Transaction suscessfully.') {
        return $response;
    }
    
    $res->user->avatar = $base_url . $res->user->avatar;

    return json_encode($res);
})->middleware(['auth']);;

require_once('public/auth.php');
