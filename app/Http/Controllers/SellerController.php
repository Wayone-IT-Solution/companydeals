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
        $activeCompanyArr =  Company::seller_companies("active");
        $activePropertyArr = Property::seller_properties('active',);
        $activeTrademarkArr = NocTrademark::seller_noctrademark('active');
        $activeAssignmentArr = Assignment::seller_assignments('active');

        // deal closed section
        $dealClosedCompanyArr =  Company::seller_companies("all", true);
        $dealClosedPropertyArr =  Property::seller_properties("all", true);
        $dealClosedTrademarkArr =  NocTrademark::seller_noctrademark("all", true);
        $dealClosedAssignmentArr = Assignment::seller_assignments('all', true);

        return view('pages.user.seller_dashboard', compact('activePropertyArr', 'activeTrademarkArr', 'activeCompanyArr', 'activeAssignmentArr','dealClosedCompanyArr','dealClosedPropertyArr','dealClosedTrademarkArr','dealClosedAssignmentArr'));
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
        $validated['user_id'] = \Auth::guard('user')->id();
        $validated['ask_price_amount'] = GeneralUtils::calculate_actual_ask_price($request->input('ask_price'), $request->input('ask_price_unit'));

        Property::create($validated);

        return redirect()->route("user.seller.propertylist")->with('status', 'Your property has been saved successfully.');
    }
}
