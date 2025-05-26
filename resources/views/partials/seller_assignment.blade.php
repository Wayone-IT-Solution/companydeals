<div class="cards">
    <div class="card-featured">
        <article>
            <ul class="feature-list">
                @if($assignment['no_interested_buyer']>0)
                <li>No. of interested buyers: {{$assignment['no_interested_buyer']}}</li>

                @endif
                
                @if(count($assignment['buyers'])>0 && $assignment['deal_closed'] !=1)
                    <li>Buyers</li>
                    <ul>
                    @foreach ($assignment['buyers'] as $eachBuyer)
                        <ol>
                            {!!$eachBuyer['buyerDetail']!!}
                            <div class="close_deal_btn">
                                <a class="cta-primary mt-4" href="{{ route('user.seller.assignment.closedeal',['id'=>$assignment['id'],'buyer_id'=>$eachBuyer['buyer_id']]) }}" >Close deal</a>
                            </div>
                        </ol>
                    @endforeach
                    </ul>
                @endif
             
                <li><strong>Status: {{$assignment['is_active']}}</strong></li>
                <li>Category: {{$assignment['category']}}</li>
                <li>Subject: {{$assignment['subject']}}</li>
                <li>Brief of the work: {{$assignment['description']}}</li>
                <li>Minimum Deal Value: {{$assignment['deal_price']}} {{$assignment['deal_price_unit']}}</li>         
                <li>Status: {{$assignment['is_active']}}</li>
                @if($assignment['deal_closed'] ==1 && $assignment['buyer_id'] > 0 )
                    <li>Buyer Details: <br> {!!$assignment['finalBuyer']!!}</li>
                 @endif
            </ul>
            @if($assignment['is_active'] == 'inactive')
                <div class="col-md-6">
                    <a class="cta-primary mt-4" href="#" >Payment Pending </a>
                </div>
            @endif
             @if($assignment['deal_closed']!=1)
            <div class="col-md-6">
                <a class="cta-primary mt-4"  href="{{ route('user.seller.editassignment', $assignment['id'])}}" type="submit">Edit</a>
            </div>
            @endif
        </article>
    </div>
</div>