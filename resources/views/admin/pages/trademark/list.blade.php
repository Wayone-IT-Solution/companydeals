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
                <th>Trademarks Detail</th>
                <th>Proprietor</th>
                <th>Description</th>
                <th>Ask price</th>
                <th>Is Active?</th>
                <th>Admin Approval</th>
                <th>Payment</th>
                <th> View </th>
                <th> Date & Time stamp </th>
              </tr>
            </thead>
            <tbody>

              @foreach ($nocTrademarks as $trademark)

              <tr>
                <td>{{ $trademark->urn }}</td>
                <td>
                  Word Mark: {{ $trademark->wordmark }}<br>
                  Application Number: {{$trademark->application_no}}<br>
                  Class: {{ $trademark->class_no }}<br>
                  Status: {{ $trademark->status }}<br>
                  Valid Upto: {{date('Y-m-d', strtotime($trademark->valid_upto))}}
                </td>
                <td class="text-center">{{ $trademark->proprietor }}</td>
                <td class="text-center">{{ $trademark->description }}</td>
                <td class="text-center">
                  {{ $trademark->ask_price }} {{ $trademark->ask_price_unit }} per month

                </td>
                <td>{{$trademark->is_active}}</td>

                <td>
                  <form action="{{ route('admin.trademark.toggleApproval', $trademark->id) }}" method="POST">
                    @csrf
                    @if ($trademark->approved == 1)
                    <button type="submit" class="btn btn-sm btn-danger">Disapprove</button>
                    <span class="badge badge-success mt-1 d-block">Approved</span>
                    @else
                    <button type="submit" class="btn btn-sm btn-success">Approve</button>
                    <span class="badge badge-danger mt-1 d-block">Not Approved</span>
                    @endif
                  </form>
                </td>
                <td><a href="{{ route('admin.trademark.payment', ['service_id'=>$trademark->id,'service_type'=>'seller_trademark']) }}">Seller Payment</a></td>
                <td><a href="{{ route('admin.trademark.detail', $trademark->id) }}"><i class="mdi mdi-eye-outline"></i></a> </td>

                <td class="text-center"> {{ $trademark->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}
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