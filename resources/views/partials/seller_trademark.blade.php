@php
    if(is_null($trademark['valid_upto'])){
        $valid_upto = "";
    }else{
        $valid_upto = date('j F, Y', strtotime($trademark['valid_upto']));
    }

@endphp
<div class="cards">
    <div class="card-featured">
        <article>
            <ul class="feature-list">
                 @if($trademark['no_interested_buyer']>0)
                <li>No. of interested buyers: {{$trademark['no_interested_buyer']}}</li>

                @endif
                
                @if(count($trademark['buyers'])>0 && $trademark['deal_closed'] !=1)
                    <li>Buyers</li>
                    <ul>
                    @foreach ($trademark['buyers'] as $eachBuyer)
                        <ol>
                            {!!$eachBuyer['buyerDetail']!!}
                            <div class="close_deal_btn">
                                <a class="cta-primary mt-4" href="{{ route('user.seller.closedealnoc',['id'=>$trademark['id'],'buyer_id'=>$eachBuyer['buyer_id']]) }}" >Close deal</a>
                            </div>
                        </ol>
                    @endforeach
                    </ul>
                @endif
             <!--   <li>URN: {{$trademark['urn']}} </li> -->
                <li><strong>Is Active?: {{$trademark['is_active']}}</strong></li>
                <li>Word Mark: {{$trademark['wordmark']}}</li>
                <li> Application Number: {{$trademark['application_no']}}</li>
                <li>Class: {{$trademark['class_no']}}</li>
                <li>Proprietor: {{$trademark['proprietor']}}</li>
                <li>Status: {{$trademark['status']}}</li>
                <li>Valid Upto: {{$valid_upto}}</li>
                <li> Description: {{$trademark['description']}}</li>
                <li>Ask Description: {{$trademark['ask_price']}} {{$trademark['ask_price_unit']}} per month</li>
                @if($trademark['deal_closed'] ==1 && $trademark['buyer_id'] > 0 )
                    <li>Buyer Details: <br> {!!$trademark['finalBuyer']!!}</li>
                 @endif
                
            </ul>
         @if($trademark['is_active'] == 'inactive')
                <div class="col-md-6">
                    <a class="cta-primary mt-4" href="#" >Payment Pending </a>
                </div>
            @endif
            @if($trademark['deal_closed']!=1)
            <div class="col-md-6">
                <a class="cta-primary mt-4" href="{{ route('user.seller.editnoctrademark', $trademark['id'])}}" type="submit">Edit</a>
            </div>
            @endif
        </article>
    </div>
</div>