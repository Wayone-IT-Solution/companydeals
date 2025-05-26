@extends('layout.master')
@section('content')
<section class="dashboard-wrap">
   <div class="container">
      <div class="form-wrap login-signup-form">
        <header>
            <h2>Email and WhatsApp no verification</h2>
         </header>
    		<form action="{{ route('user.password.email') }}" method="POST">
          @csrf
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
          <div class="col-md-12">
               <div class="field">
                  @if($user->email_verified == 1)
                    <p><strong>Email verified</strong></p>
                  @else
                  To verify email click on link send to your registered email.
                  <p><a href="{{ route('user.send_verify_email',$user->id) }}" class="cta-primary med with-shadow">Resend email</a></p>
                  @endif
               </div>
            </div>
            <div class="col-md-12">
               <div class="field">
                @if($user->phone_verified == 1)
                    <p><strong>WhatsApp no verified</strong></p>
                @else
                    <p> To verify WhatsApp no send the code "COMDEL" to 43323235. Admin will verify you WhatsApp no.</p>
                @endif
                @if($user->email_verified == 1 && $user->phone_verified == 1)
                    <a href="{{ route('user.login') }}" class="cta-primary with-shadow">Login</a>
                @endif
               </div>
            </div>
        </form>     
      </div>
    </div>
    </div>
</section>
@endsection