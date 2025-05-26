<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\NocTrademark;
use App\Models\Company;
use App\Models\Assignment;
use App\Models\Testimonial;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use App\Models\Banner;

class IndexController extends Controller
{
    public function index()
    {
        $dashBoardData = array();
        $dashBoardData['no_company'] = \DB::table('companies')
            ->where('status', 'active')
            ->where(function ($query) {
                $query->where('deal_closed', 0)
                    ->orWhereNotNull('deal_closed');
            })
            ->count();

        $dashBoardData['no_users'] = \DB::table('users')->where([['email_verified', 1], ['phone_verified', 1]])->count('*');
        $dashBoardData['no_deal_closed'] =  \DB::table('companies')
            ->where('status', 'active')
            ->where('deal_closed', 1)

            ->count();
        $amount_deal_closed  = \DB::table('users')->sum('amount_deal_closed');
        $dashBoardData['amount_deal_closed'] =  $amount_deal_closed / 1000;
        $dashBoardData['featured_company'] = Company::where([['home_featured', 1], ['status', 'active']])->whereNotIn('deal_closed', [1])->first();
        $dashBoardData['featured_property'] = Property::where([['home_featured', 1], ['status', 'active']])->whereNotIn('deal_closed', [1])->first();
        $dashBoardData['featured_nocTrademark'] = NocTrademark::where([['home_featured', 1], ['is_active', 'active']])->whereNotIn('deal_closed', [1])->first();
        $dashBoardData['featured_assignment'] = Assignment::where([['home_featured', 1], ['is_active', 'active']])->whereNotIn('deal_closed', [1])->first();
        $dashBoardData['testimonial'] = Testimonial::where([['status', 'active']])->orderBy('updated_at', 'desc')->get();
        $dashBoardData['announcement'] = Announcement::where([['status', 'active']])->orderBy('updated_at', 'desc')->limit(3)->get();

        $banners = Banner::all();
        return view('pages.home', compact('dashBoardData', 'banners'));
    }

    public function assignmentlist()
    {
          $assignments = Assignment::where('is_active', 'active')->whereNotIn('deal_closed', [1])->orderBy('updated_at', 'desc')->get();
        return view('pages.assignment', compact('assignments'));
    }

    public function propertylist()
    {
        $properties = Property::where('status', 'active')->where('deal_closed', 0)->orderBy('updated_at', 'desc')->get();
        return view('pages.propertylist', compact('properties'));
    }
    public function treademarklist()
    {
        $nocTrademarks = NocTrademark::where('is_active', 'active')->whereNotIn('deal_closed', [1])->orderBy('updated_at', 'desc')->get();
        return view('pages.trademarklist', compact('nocTrademarks'));
    }
    public function companylist()
    {
         $companys = \DB::table('companies')
            ->where('status', 'active')
            ->where(function ($query) {
                $query->where('deal_closed', 0)
                    ->orWhereNull('deal_closed');
            })->orderBy('created_at', 'desc')->get(); // Convert to array of objects
;
        return view('pages.companylist', compact('companys'));
    }
    public function contact_submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required'
        ]);

        // Send email
        $to = config('mail.contact_us.mail_to');
        Mail::to($to)->send(new ContactMail($request->all()));
        return response()->json(['success' => 'Your message has been sent successfully!']);
    }
}
