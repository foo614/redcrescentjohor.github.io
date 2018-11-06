<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Auth;
use App\Transaction;
use App\User;
class PaymentController extends Controller
{
    // global variable
    private $stripeKey = 'sk_test_MxvgJA9hh1fjV3ZrDe6hZ4ih';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        \Stripe\Stripe::setApiKey($this->stripeKey);
        $list = \Stripe\Charge::all(["limit" => 100]);
        return $list['data'];
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
        \Stripe\Stripe::setApiKey($this->stripeKey);
        // Token is created using Checkout or Elements!
        // Get the payment token ID submitted by the form:
        $token = $request[0]['id'];
        $amount = $request[1]['amount']*100;
        $description = $request[1]['selectedCourse']['name'] ?? $request[1]['fundraiser_title'];
        $customer_email = $request[1]['email'];

        $customer = \Stripe\Customer::create(array(
            "email" => $customer_email,
            "source" => $token
        ));

        $charge = \Stripe\Charge::create([
            'amount' => $amount,
            'currency' => 'myr',
            'description' => $description,
            // 'source' => $token,
            'receipt_email' => $customer_email,
            "customer" => $customer->id
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

    public function totalDonation($id)
    {
        $transaction = Transaction::where('fundraiser_id', $id)->sum('amount');
        return $transaction;
    }
}
