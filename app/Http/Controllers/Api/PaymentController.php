<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Transaction;
use App\User;
class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $find_user = User::where('email',  $request[1]['email'])->firstOrFail();
        if($find_user){
            $transaction = new Transaction;
            $transaction->user_id = $find_user->id ?? null;
            $transaction->course_id = $request[1]['course_id'] ?? null;
            $transaction->fundraiser_id = $request[1]['fundraiser_id'] ?? null;
            $transaction->amount = $request[1]['amount'] ?? null;
            $transaction->currency = "MYR";
            $transaction->save();
        }
        \Stripe\Stripe::setApiKey("sk_test_MxvgJA9hh1fjV3ZrDe6hZ4ih");

        // Token is created using Checkout or Elements!
        // Get the payment token ID submitted by the form:
        $token = $request[0]['id'];
        $amount = $request[1]['amount']*100;
        $description = $request[1]['selectedCourse']['name'];
        $customer_email = $request[1]['email'];
        $charge = \Stripe\Charge::create([
            'amount' => $amount,
            'currency' => 'myr',
            'description' => $description,
            'source' => $token,
            'receipt_email' => $customer_email,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
