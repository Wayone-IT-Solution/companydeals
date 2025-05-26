@extends('layout.master')
@section('content')
<section class="dashboard-wrap">
<div class="container">
    <div class="row">
        @include('layout.seller_nav')
        <div class="col-lg-8 col-xl-9">
            <div class="dashboard-details">
            <button class="navToggle2 cta-primary mb-4"><i class="fa-solid fa-sliders"></i> Open Dashboard Nav</button>
            <div class="form-wrap">
            <header>
            <h2>Edit your property detail</h2>
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
            @php
                $states_option = Config::get('selectoptions.states');
                $ask_price_unit_option = Config::get('selectoptions.ask_price_unit_option');
                $property_type_option = Config::get('selectoptions.property_type_option');
            @endphp  
            <form action="{{ route('user.seller.updateproperty',$property->id) }}" method="post">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="field">
                            URN: {{$property->urn}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field">
                            <label for="state">State</label>
                            <select id="state" class="form-select" name="state" required="">
                                <option value="">-Select-</option>
                                @foreach ($states_option as $key => $stateName)
                                    <option value="{{$key}}" {{ old( 'state', $property->state) == $key ? "selected" : "" }}>{{$stateName}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field">
                            <label for="pincode">Pin code</label>
                            <input id="pincode"  maxlength="6" type="text" class="form-control" name="pincode" placeholder="Pin code" required="" value="{{ old( 'pincode', $property->pincode) }}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="field">
                            <label for="address">Address</label>
                            <input id="address"  type="text" class="form-control" name="address" placeholder="Address" required="" value="{{ old( 'address', $property->address) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group field">
                            <label for="space">Space</label>
                            <input id="space" maxlength="4"  type="text"  class="form-control" name="space" placeholder="Space" required="" value="{{ old( 'space', $property->space) }}"><span class="input-group-text" class="space_unit"  aria-describedby="space_unit">Sq ft.</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field">
                            <label for="space">Type</label>
                            <select id="type" class="form-select" name="type" required="">
                                <option value="">-Select-</option>
                                @foreach ($property_type_option as $key => $eachOption)
                                    <option value="{{$key}}" {{ old( 'type', $property->type)  == $key ? "selected" : "" }}>{{$eachOption}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field">
                            <label for="ask_price">Ask price</label>
                            <input id="ask_price"  type="number" class="form-control" name="ask_price" placeholder="Ask price" required="" value="{{ old( 'ask_price', $property->ask_price) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group field">
                            <label>&nbsp;</label>
                            <select id="ask_price_unit" class="form-select" name="ask_price_unit" required="" aria-describedby="ask_price_time">
                                <option value="">-Select-</option>
                                @foreach ($ask_price_unit_option as $key => $eachOption)
                                    <option value="{{$key}}" {{ old( 'ask_price_unit', $property->ask_price_unit) == $key ? "selected" : "" }}>{{$eachOption}}</option>
                                @endforeach 
                            </select><span class="input-group-text" id="ask_price_time">per month</span>

                    </div>
                </div>
                <div class="col-xl-12">
                    <button class="cta-primary" type="submit">Update</button>
                </div>
            </div>
            </form>
        </div>
        

    </div>
</div>
</div>
</div>
</section>

@endsection