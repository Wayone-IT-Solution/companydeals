@extends('admin.layout.master')
@section('content')
<div class="row ">
  <div class="col-12 grid-margin">
    <div class="card">
      <div class="card-body">
        <h3>Assignments</h3>
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
                <th>Category</th>
                <th>Subject</th>
                <th>Brief of the work</th>
                <th>Minimum Deal Value</th>
                <th>Is Active?</th>
                <th>Admin Approval</th>
                <th>Payment</th>
                <th> View </th>
                <th> Date & TimeStamp </th>
              </tr>
            </thead>
            <tbody>

              @foreach ($assignments as $assignment)

              <tr>
                <td>{{ $assignment->urn }}</td>
                <td>{{ $assignment->category }}</td>
                <td>{{ $assignment->subject }}</td>
                <td>{{ $assignment->description }}</td>
                <td>{{ $assignment->deal_price }}</td>
                <td>{{$assignment->is_active}}</td>

                <td>
                  <form action="{{ route('admin.assignment.toggleApproval', $assignment->id) }}" method="POST">
                    @csrf
                    @if ($assignment->approved == 1)
                    <button type="submit" class="btn btn-sm btn-danger">Disapprove</button>
                    <span class="badge badge-success mt-1 d-block">Approved</span>
                    @else
                    <button type="submit" class="btn btn-sm btn-success">Approve</button>
                    <span class="badge badge-danger mt-1 d-block">Not Approved</span>
                    @endif
                  </form>
                </td>
                <td><a href="{{ route('admin.assignment.payment', ['service_id'=>$assignment->id,'service_type'=>'seller_assignment']) }}">Seller Payment</a></td>
                <td><a href="{{ route('admin.assignment.detail', $assignment->id) }}"><i class="mdi mdi-eye-outline"></i></a> </td>

                <td class="text-center"> {{ $assignment->created_at->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}
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