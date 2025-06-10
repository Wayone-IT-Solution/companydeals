@extends('admin.layout.master')
@section('content')
<div class="row ">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h3>Noc Trademarks</h3>
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
                <th>Name</th>
                <th>Ask price</th>
                <th>Is Active?</th>
                <th>Payment</th>
                <th> View </th>
                <th> Date & TimeStamp </th>
              </tr>
            </thead>
            <tbody>

              @foreach ($companys as $company)
              <tr>
                <td>{{ $company->urn }}</td>

                <td class="text-center">{{ $company->name }}</td>
                <td class="text-center">{{ $company->ask_price }} {{ $company->ask_price_unit }}</td>
                <td class="text-center">{{ $company->status }}</td>
                <td><a href="{{ route('admin.company.payment', ['service_id'=>$company->id,'service_type'=>'seller_company']) }}">Seller Payment</a></td>
                <td><a href="{{ route('admin.company.detail', $company->id) }}"><i class="mdi mdi-eye-outline"></i></a> </td>

                <td class="text-center"> {{ $company->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}
                </td>

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