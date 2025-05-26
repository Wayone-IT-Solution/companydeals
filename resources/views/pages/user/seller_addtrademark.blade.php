@extends('layout.master')
@section('content')
@php
    use App\Http\Controllers\Utils\GeneralUtils;
    $selected_class = old('class_no');
    $class_option = GeneralUtils::get_class_option($selected_class);
    $ask_price_unit_option = Config::get('selectoptions.ask_price_unit_option');
@endphp
<section class="dashboard-wrap">
    <div class="container">
        <div class="row">
        @include('layout.seller_nav')
            <div class="col-lg-8 col-xl-9">
            <div class="dashboard-details">
            <button class="navToggle2 cta-primary mb-4"><i class="fa-solid fa-sliders"></i> Open Dashboard Nav</button>
            <div class="form-wrap">
            <header>
                <h2>Submit the following form for NOC Trademark</h2>
            </header>
            
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif  
            <form action="{{ route('user.seller.savetrademark') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="field">
                            <label for="state">Word Mark</label>
                            <input id="wordmark" type="text" class="form-control" name="wordmark" placeholder="Word Mark" required="" value="{{ old('wordmark') }}">
                        </div>
                    </div>
                
                    <div class="col-md-6">
                        <div class="field">
                            <label for="application_no">Application Number</label>
                            <input id="application_no"  maxlength="7" type="text" class="form-control" name="application_no" placeholder="Application Number" required="" value="{{ old('application_no') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field">
                            <label for="class_no">Class</label>
                            <select id="class_no" class="form-select" name="class_no" required="">
                                <option value="">-Select-</option>
                                {!!$class_option!!}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="field">
                            <label for="proprietor">Proprietor</label>
                            <input id="proprietor"   type="text"  class="form-control" name="proprietor" placeholder="Proprietor" required="" value="{{ old('proprietor') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field">
                            <label for="status">Status</label>
                                <select id="status" class="form-select" name="status" required="" value="{{ old('status') }}">
                                    <option value="">--Select--</option>
                                    <option value="VALID" {{ old("status") == 'VALID' ? "selected" : "" }}>VALID</option>
                                    <option value="PROTECTION GRANTED" {{ old("status") == 'PROTECTION GRANTED' ? "selected" : "" }}>PROTECTION GRANTED</option>
                                </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field">
                            <label for="valid_upto">Valid Upto</label>
                            <div class="controls bootstrap-timepicker flex">
                                <input id="valid_upto"   type="text" readonly class="datetime  form-control datepicker" name="valid_upto" placeholder="Valid Upto" value="{{ old('valid_upto') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="field">
                            <label for="description">Description</label>
                            <textarea id="description" class="form-control" name="description" placeholder="Description" required="">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field">
                            <label for="ask_price">Ask price</label>
                            <input id="ask_price"  type="number" class="form-control" name="ask_price" placeholder="Ask price" required="" value="{{ old('ask_price') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field input-group">
                            <label>&nbsp;</label>
                            <select id="ask_price_unit" class="form-select" name="ask_price_unit" required="" value="{{ old('ask_price_unit') }}" aria-describedby="ask_price_time">
                                <option value="">-Select-</option>
                                @foreach ($ask_price_unit_option as $key => $eachOption)
                                    <option value="{{$key}}" {{ old("ask_price_unit") == $key ? "selected" : "" }}>{{$eachOption}}</option>
                                @endforeach  
                                </select><span class="input-group-text" id="ask_price_time">per month</span>
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <button class="cta-primary" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
</div>
</div>
</div>
</div>
</div>
</section>
@push('plugin-styles')
    <link rel="stylesheet" href="{{asset('adminassets/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}">
@endpush
@push('plugin-scripts')
    <script src="{{asset('adminassets/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript">
      $('.datepicker').datepicker({
    format: 'yyyy-mm-dd', startDate: '-0m'});
    </script>
@endpush
@endsection