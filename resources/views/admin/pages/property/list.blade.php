@extends('admin.layout.master')
@section('content')
<div class="row ">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h3>Properties</h3>
        @if(session('message'))
        <div class="alert alert-success">
              <div> {{ session('message') }}</div>
        </div>
        @endif
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>URN</th>
                <th>Address</th>
                <th>Space</th>
                <th>Type</th>
                <th>Ask price</th>
                <th>Status</th>
                <th>Payment</th>
                <th>View</th>
              </tr>
            </thead>
            <tbody>
              
              @foreach ($properties as $property)

              <tr>
                <td>{{ $property->urn }}</td>
                <td> {{ $property->address }} <br/> Pin:{{ $property->pincode }} State: {{ $property->state }}  </td>
                <td> {{ $property->space }} sq ft.</td>
                <td class="text-center">
                  {{ $property->type }}</td>
                <td class="text-center">
                  {{ $property->ask_price }} {{ $property->ask_price_unit }} per month

                </td>
                <td>{{$property->status}}</td>
                <td><a href="{{ route('admin.property.payment', ['service_id'=>$property->id,'service_type'=>'seller_property']) }}">Seller Payment</a></td>
                <td><a href="{{ route('admin.property.detail', $property->id) }}"><i class="mdi mdi-eye-outline"></i></a> </td>
                   
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@stop