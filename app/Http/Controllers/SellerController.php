<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Company;
use App\Models\Buyer;
use App\Models\NocTrademark;
use App\Models\Assignment;
use App\Rules\MaxDigits;
use App\Rules\NoEmailNoMobile;
use App\Http\Controllers\Utils\GeneralUtils;

class SellerController extends Controller
{
    public function addAssignment()
    {
        return view('pages.user.seller_addassignment');
    }
    public function seller_assignments()
    {
        $arrAssignment = Assignment::seller_assignments('inactive');
        return view('pages.user.seller_assignment', compact('arrAssignment'));
    }
    public function saveAssignment(Request $request)
    {

        $validated = $request->validate([
            'category' => ['required'],
            'subject' => 'required',
            'description' => ['required', new NoEmailNoMobile()],
            'deal_price' => ['required', 'numeric'],
            'deal_price_unit' => 'required',
        ]);
        $validated['deal_price_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('deal_price'), $request->input('deal_price_unit'));
        $validated['urn'] = uniqid();
        $validated['is_active'] = 'inactive';
        $validated['user_id'] = \Auth::guard('user')->id();
        Assignment::create($validated);
        return redirect()->route("user.seller.assignments")->with('status', 'Your assignment has been saved successfully.');
    }
    public function editAssignment($id)
    {
        $assignment = Assignment::findOrFail($id);
        return view('pages.user.seller_editassignment', compact('assignment'));
    }
    public function UpdateAssignment(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);
        $validated = $request->validate([
            'category' => ['required'],
            'subject' => 'required',
            'description' => ['required', new NoEmailNoMobile()],
            'deal_price' => ['required', 'numeric'],
            'deal_price_unit' => 'required',

        ]);
        $validated['deal_price_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('deal_price'), $request->input('deal_price_unit'));
        $assignment->update($validated);
        return redirect()->route("user.seller.assignments")->with('status', 'Your assignment has been updated successfully.');
    }

    public function editNocTrademark($id)
    {
        $trademark = NocTrademark::findOrFail($id);
        return view('pages.user.seller_editnoctrademark', compact('trademark'));
    }

    public function UpdateNocTrademark(Request $request, $id)
    {
        $trademark = NocTrademark::findOrFail($id);
        $validated = $request->validate(
            [
                'wordmark' => ['required', new MaxDigits(7)],
                'application_no' => ['required', 'max_digits:9999999'],
                'class_no' => ['required'],
                'proprietor' => ['required', 'regex:/^[a-zA-Z\s]+$/'],
                'description' => ['required', new NoEmailNoMobile()],

                'status' => 'required',
                'ask_price' => ['required', 'numeric', 'max:9999'],
                'ask_price_unit' => 'required',

            ],
            [
                'proprietor.regex' => 'Proprietor should contain letters and spaces.',
            ]
        );
        $validated['valid_upto'] = $request->input('valid_upto');
        $validated['ask_price_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('ask_price'), $request->input('ask_price_unit'));
        $trademark->update($validated);
        return redirect()->route("user.seller.noctrademark")->with('status', 'Your data for NOC trademark has been updated successfully.');
    }

    public function editProperty($id)
    {
        $property = Property::findOrFail($id);
        return view('pages.user.seller_editproperty', compact('property'));
    }
    public function closedealProperty($id, $buyer_id)
    {
        $property = Property::findOrFail($id);
        $seller = \Auth::guard('user')->user();

        $deal_close_amount = intval($seller->amount_deal_closed) + intval($property->ask_price_amount);
        $deal_close_no = 1 + intval($seller->no_deal_closed);
        $seller->update(['no_deal_closed' => $deal_close_no, 'amount_deal_closed' => $deal_close_amount]);
        $buyer = Buyer::findOrFail($buyer_id);
        $buyer_amount_deal_closed = intval($buyer->buyer_amount_deal_closed) + intval($property->ask_price_amount);
        $buyer_no_deal_closed = 1 + intval($buyer->buyer_no_deal_closed);
        $buyer->update(['buyer_no_deal_closed' => $buyer_no_deal_closed, 'buyer_amount_deal_closed' => $buyer_amount_deal_closed]);


        $property->update(['deal_closed' => 1, "buyer_id" => $buyer_id]);
        return redirect()->route("user.seller.dashboard")->with('status', 'Your property is closed successfully.');
    }

    public function closedealAssignment($id, $buyer_id)
    {
        $assignment = Assignment::findOrFail($id);
        $seller = \Auth::guard('user')->user();
        $deal_close_amount = intval($seller->amount_deal_closed) + intval($assignment->deal_price);
        $deal_close_no = 1 + intval($seller->no_deal_closed);
        $seller->update(['no_deal_closed' => $deal_close_no, 'amount_deal_closed' => $deal_close_amount]);

        $buyer = Buyer::findOrFail($buyer_id);
        $buyer_amount_deal_closed = intval($buyer->buyer_amount_deal_closed) + intval($assignment->ask_price_amount);
        $buyer_no_deal_closed = 1 + intval($buyer->buyer_no_deal_closed);
        $buyer->update(['buyer_no_deal_closed' => $buyer_no_deal_closed, 'buyer_amount_deal_closed' => $buyer_amount_deal_closed]);
        $assignment->update(['deal_closed' => 1, "buyer_id" => $buyer_id]);
        return redirect()->route("user.seller.dashboard")->with('status', 'Your assignment is closed successfully.');
    }
    public function closedealNoc($id, $buyer_id)
    {
        $trademark = NocTrademark::findOrFail($id);
        $seller = \Auth::guard('user')->user();
        $deal_close_amount = intval($seller->amount_deal_closed) + intval($trademark->ask_price_amount);
        $deal_close_no = 1 + intval($seller->no_deal_closed);
        $seller->update(['no_deal_closed' => $deal_close_no, 'amount_deal_closed' => $deal_close_amount]);
        $buyer = Buyer::findOrFail($buyer_id);
        $buyer_amount_deal_closed = intval($buyer->buyer_amount_deal_closed) + intval($trademark->ask_price_amount);
        $buyer_no_deal_closed = 1 + intval($buyer->buyer_no_deal_closed);
        $buyer->update(['buyer_no_deal_closed' => $buyer_no_deal_closed, 'buyer_amount_deal_closed' => $buyer_amount_deal_closed]);
        $trademark->update(['deal_closed' => 1, "buyer_id" => $buyer_id]);
        return redirect()->route("user.seller.dashboard")->with('status', 'Your Trademark is closed successfully.');
    }

    public function updateproperty(Request $request, $id)
    {
        $property = Property::findOrFail($id);
        $validated = $request->validate([
            'state' => 'required',
            'pincode' => ['required', 'digits:6'],
            'address' => 'required',
            'space' => ['required', 'numeric', 'max:9999'],
            'type' => 'required',
            'ask_price' => ['required', 'numeric', 'max:9999'],
            'ask_price_unit' => 'required',

        ]);
        $validated['ask_price_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('ask_price'), $request->input('ask_price_unit'));
        $property->update($validated);
        return redirect()->route("user.seller.propertylist")->with('status', 'Your property has been updated successfully.');
    }
    public function saveNocTrademark(Request $request)
    {

        $validated = $request->validate(
            [
                'wordmark' => ['required', new MaxDigits(7)],
                'application_no' => ['required', 'max_digits:9999999'],
                'class_no' => ['required'],
                'proprietor' => ['required', 'regex:/^[a-zA-Z\s]+$/'],
                'description' => ['required', new NoEmailNoMobile()],
                'status' => 'required',
                'ask_price' => ['required', 'numeric', 'max:9999'],
                'ask_price_unit' => 'required',

            ],
            [
                'proprietor.regex' => 'Proprietor should contain letters and spaces.',
            ]
        );

        $validated['valid_upto'] = $request->input('valid_upto');
        $validated['urn'] = uniqid();
        $validated['is_active'] = 'inactive';
        $validated['user_id'] = \Auth::guard('user')->id();
        $validated['ask_price_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('ask_price'), $request->input('ask_price_unit'));
        NocTrademark::create($validated);
        return redirect()->route("user.seller.noctrademark")->with('status', 'Your trademark has been saved successfully.');
    }
    public function seller_addtrademark()
    {
        return view('pages.user.seller_addtrademark');
    }
    public function seller_noctrademark()
    {
        $arrTrademark = NocTrademark::seller_noctrademark('inactive');
        return view('pages.user.seller_noctrademark', compact('arrTrademark'));
    }
    public function addProperty()
    {
        return view('pages.user.seller_addprpperty');
    }



    public function seller_dashboard()
    {
        $activeCompanyArr = array();
        $dealClosedCompanyArr = array();
        $activePropertyArr = array();
        $dealClosedPropertyArr = array();
        $activeTrademarkArr = array();
        $dealClosedTrademarkArr = array();
        $activeAssignmentArr = array();
        $dealClosedAssignmentArr = array();
        $activeCompanyArr =  Company::seller_companies("active", false, true);
        $activePropertyArr = Property::seller_properties('active',);
        $activeTrademarkArr = NocTrademark::seller_noctrademark('active');
        $activeAssignmentArr = Assignment::seller_assignments('active');

        // deal closed section
        $dealClosedCompanyArr =  Company::seller_companies("all", true);
        $dealClosedPropertyArr =  Property::seller_properties("all", true);
        $dealClosedTrademarkArr =  NocTrademark::seller_noctrademark("all", true);
        $dealClosedAssignmentArr = Assignment::seller_assignments('all', true);

        return view('pages.user.seller_dashboard', compact('activePropertyArr', 'activeTrademarkArr', 'activeCompanyArr', 'activeAssignmentArr', 'dealClosedCompanyArr', 'dealClosedPropertyArr', 'dealClosedTrademarkArr', 'dealClosedAssignmentArr'));
    }
    public function seller_properties()
    {

        $arrPrperty = Property::seller_properties('inactive');
        return view('pages.user.seller_propertylist', compact('arrPrperty'));
    }

    public function saveProperty(Request $request)
    {

        $validated = $request->validate([
            'state' => 'required',
            'pincode' => ['required', 'digits:6'],
            'address' => 'required',
            'space' => ['required', 'numeric', 'max:9999'],
            'type' => 'required',
            'ask_price' => ['required', 'numeric', 'max:9999'],
            'ask_price_unit' => 'required',

        ]);
        $validated['urn'] = uniqid();
        $validated['status'] = 'inactive';
        $validated['user_id'] = \Auth::guard('user')->id();
        $validated['ask_price_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('ask_price'), $request->input('ask_price_unit'));

        Property::create($validated);

        return redirect()->route("user.seller.propertylist")->with('status', 'Your property has been saved successfully.');
    }

    public function showPaymentForm()
    {
        // You can pass amount or other details as needed
        return view('pages.user.seller_payment');
    }

   public function processPayment(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric|min:1',
    ]);

    // ✅ Sandbox credentials – make sure these are correct from your dashboard
    $appId = 'TEST10673109e5c4908e2942e0c65ea490137601';
    $secretKey =  env('CASHFREE_SECRET_KEY');

    $user = \Auth::guard('user')->user();
    $orderId = 'SELLER_' . uniqid();
    $orderAmount = $request->amount;
    $orderCurrency = 'INR';

    $orderData = [
        "order_id" => $orderId,
        "order_amount" => $orderAmount,
        "order_currency" => $orderCurrency,
        "customer_details" => [
            "customer_id" => (string) $user->id,
            "customer_email" => $user->email,
            "customer_phone" => $user->phone,
        ],
        "order_note" => "Seller Payment",
        "order_meta" => [
            // ✅ Avoid deprecated tokens in return_url
            "return_url" => route('user.seller.dashboard') . "?order_id={$orderId}"
        ]
    ];

    try {
        $client = new \GuzzleHttp\Client();
        $response = $client->post('https://sandbox.cashfree.com/pg/orders', [
            'headers' => [
                'x-client-id' => $appId,
                'x-client-secret' => $secretKey,
                'x-api-version' => '2022-09-01',
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($orderData),
        ]);

        $body = json_decode($response->getBody(), true);

        return $body;
        if (isset($body['payments']['url'])) {
            return redirect($body['payments']['url']);
        } else {
            return back()->with('error', 'Unable to initiate payment. Please try again.');
        }

    } catch (\GuzzleHttp\Exception\RequestException $e) {
        // ✅ Parse and show better API error message
        if ($e->hasResponse()) {
            $error = json_decode($e->getResponse()->getBody(), true);
            return back()->with('error', 'Payment initiation failed: ' . ($error['message'] ?? 'Unknown error'));
        }

        return back()->with('error', 'Payment initiation failed: ' . $e->getMessage());
    } catch (\Exception $e) {
        return back()->with('error', 'Unexpected error: ' . $e->getMessage());
    }
}


    public function paymentReturn(Request $request)
    {
        $orderId = $request->query('order_id');
        $orderToken = $request->query('order_token');
        $appId = 'TEST10673109e5c4908e2942e0c65ea490137601';
        $secretKey =  env('CASHFREE_SECRET_KEY');

        if (!$orderId || !$orderToken) {
            return redirect()->route('user.seller.dashboard')->with('error', 'Invalid payment return.');
        }

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get("https://sandbox.cashfree.com/pg/orders/{$orderId}", [
                'headers' => [
                    'x-client-id' => $appId,
                    'x-client-secret' => $secretKey,
                    'x-api-version' => '2022-09-01',
                    'Content-Type' => 'application/json',
                ],
            ]);
            $body = json_decode($response->getBody(), true);
            // Save/update payment in DB (assume Payment model exists)
            $payment = \App\Models\Payment::updateOrCreate(
                ['order_id' => $orderId],
                [
                    'user_id' => auth()->id(),
                    'amount' => $body['order_amount'] ?? 0,
                    'status' => $body['order_status'] ?? 'unknown',
                    'payment_method' => $body['payment_method'] ?? null,
                    'transaction_id' => $body['payment_id'] ?? null,
                    'notes' => json_encode($body),
                ]
            );
            if (($body['order_status'] ?? '') === 'PAID') {
                return redirect()->route('user.seller.payment.history')->with('status', 'Payment successful!');
            } else {
                return redirect()->route('user.seller.payment.history')->with('error', 'Payment not successful.');
            }
        } catch (\Exception $e) {
            return redirect()->route('user.seller.payment.history')->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }

    public function paymentHistory(Request $request)
    {
        $query = \App\Models\Payment::where('user_id', auth()->id());
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }
        $payments = $query->orderBy('created_at', 'desc')->get();
        return view('pages.user.seller_payment_history', compact('payments'));
    }
}
