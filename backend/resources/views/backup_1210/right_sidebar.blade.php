<div class="row rightSideBarToggler d-hidden d-none d-sm-block">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-body text-end">
                <button class="btn" onclick="toggleRightSideBar()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<aside class="sidebar mt-0 sidebarToggle d-hidden" id="sidebarToggle">
    <div class="widget">
        <div class="d-flex align-items-center">
            @php  
            if(Auth()->user()) {
                $tz = auth()->user()->timezone;
                if(!empty($tz)){
                    $timestamp = time();
                    $dt = new DateTime("now", new DateTimeZone($tz)); //first argument "must" be a string
                    $dt->setTimestamp($timestamp);
                    $current_hour = $dt->format('H');
                }else{
                    $current_hour = date('H', time());
                }
            }
            @endphp
            @if(Auth()->user()) 
            @if($current_hour >= 5 && $current_hour < 12)
                <img class="img-fluid" src="{{asset('assets/frontend/images/m-sun.png')}}"  height="30px" width="30px" alt="">
            @elseif($current_hour >= 12 && $current_hour < 17)
                <img class="img-fluid" src="{{asset('storage/images/cloud-sun.png')}}" alt="">
            @else
                <img class="img-fluid" src="{{asset('assets/frontend/images/moon.png')}}" height="30px" width="30px" alt="">
            @endif
            <h3 class="h6 ms-2">{{get_phrase('Hi')}}, {{Auth()->user()->name}}
                @if($current_hour >= 5 && $current_hour < 12)
                    <span class="d-block text-primary">{{get_phrase('Good Morning')}}!</span>
                @elseif($current_hour >= 12 && $current_hour < 17)
                    <span class="d-block text-primary">{{get_phrase('Good Afternoon')}}!</span>
                @else
                    <span class="d-block text-primary">{{get_phrase('Good Evening')}}!</span>
                @endif
            </h3>
            @endif
        </div>
    </div> <!-- Widget End -->
    <div class="widget">
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="widget-title">{{ get_phrase('Sponsored') }} </h3>
            
        </div>
        <div class="sponsors">
            @php

                $sponsorPost = \App\Models\Sponsor::orderBy('id','desc')

                ->where(function($query){
                    $query->where('start_date', '<', date('Y-m-d H:i:s'))
                    ->orWhere(function($query){
                        $query->where('start_date', '=', date('Y-m-d H:i:s'))
                        ->whereTime('start_date', '<=', date('Y-m-d H:i:s'));
                    });
                })
                ->where(function($query){
                    $query->where('end_date', '>', date('Y-m-d H:i:s'))
                    ->orWhere(function($query){
                        $query->where('end_date', '=', date('Y-m-d H:i:s'))
                        ->whereTime('end_date', '>=', date('Y-m-d H:i:s'));
                    });
                })
                ->where('status', 1)
                ->limit('5')->get();
            @endphp
            @foreach ($sponsorPost as $sponsor )
            <a target="_blank" href="{{ $sponsor->ext_url }}">
                <div class="sponsor d-flex d-md-block d-xl-flex align-items-center border mb-1 text-lg-center text-xl-start">
                    <img src="{{ get_sponsor_image($sponsor->image,'thumbnail') }}"  class="sponsor_post_image_size ms-2 ms-lg-0 ms-xl-2 mt-2 mt-xl-0" alt="">
                    <div class="sponsor-txt ms-2 pt-2">
                        <h6>{{ellipsis($sponsor->name,30)}}</h6>
                        <p class="ellipsis-line-3 pe-2 text-dark">{{ellipsis(strip_tags($sponsor->description,100))}}</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div> <!-- Widget End -->
    <div class="widget">
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="widget-title">{{ get_phrase('Active users') }} </h3>
            <div class="d-flex align-items-center widget-controls">
                
            </div>
        </div>
        <div class="contact-lists mt-3">
            @php
            if(Auth()->user()) {
                $friends = \App\Models\Friendships::where(function ($query) {
                                $query->where('accepter', auth()->user()->id)
                                    ->orWhere('requester', auth()->user()->id);
                            })
                    ->where('is_accepted', 1)->get();
                        }
                        else{
                            $friends =[];
                        }
            @endphp
            @foreach ($friends as $friend)
                @if ($friend->requester==auth()->user()->id)
                    @if ($friend->getFriendAccepter->isOnline())
                        @if ($friend->getFriendAccepter->id !=auth()->user()->id)
                        <div class="single-contact d-flex align-items-center justify-content-between">
                            <div class="avatar d-flex">
                                <a href="{{ route('chat',$friend->getFriendAccepter->id) }}" class="d-flex align-items-center">
                                    <div class="avatar me-2">
                                        <img src="{{ get_user_image($friend->getFriendAccepter->photo,'optimized') }}" class="rounded-circle w-45px" alt="">
                                        <span class="online-status active"></span>
                                    </div>
                                    <h4>{{ $friend->getFriendAccepter->name }}</h4>
                                </a>
                            </div>
                            <div class="login-time">

                            </div>
                        </div> 
                        @endif    
                    @endif
                @else
                    @if ($friend->getFriend->isOnline())
                        @if ($friend->getFriend->id !=auth()->user()->id)
                        <div class="single-contact d-flex align-items-center justify-content-between">
                            <div class="avatar d-flex">
                                <a href="{{ route('chat',$friend->getFriend->id) }}" class="d-flex align-items-center">
                                    <div class="avatar me-2">
                                        <img src="{{ get_user_image($friend->getFriend->photo,'optimized') }}" class="rounded-circle w-45px" alt="">
                                        <span class="online-status active"></span>
                                    </div>
                                    <h4>{{ $friend->getFriend->name }}</h4>
                                </a>
                            </div>
                            <div class="login-time">

                            </div>
                        </div> 
                        @endif    
                    @endif
                @endif
            @endforeach
        </div>
    </div> <!-- Widget End -->

   
@php
use App\Models\Marketplace;

$featuredProducts = Marketplace::with(['page.city', 'page.area', 'productCategories', 'getCurrency'])
    ->where('item_featured', 1)
    ->where('product_status', 2)
    ->limit(5)
    ->get();

$count = $featuredProducts->count();

if ($count < 5) {
    $additional = Marketplace::with(['page.city', 'page.area', 'productCategories', 'getCurrency'])
        ->where('item_featured', 0)
        ->where('product_status', 2)
        ->orderByDesc('id') // ya 'created_at'
        ->limit(5 - $count)
        ->get();

    $featuredProducts = $featuredProducts->merge($additional);
}


@endphp
<div class="widget">
    <div class="d-flex align-items-center justify-content-between">
        <h3 class="widget-title">{{ get_phrase('Featured Products') }}</h3>
    </div>
    <div class="featured-products mt-3">
        @foreach($featuredProducts as $product)
            @php
                $page = $product->page;
                $city = $page->city ?? null;
                $area = $page->area ?? null;
                $productCategory = $product->productCategories->last();
                $productRoute = route('single.product', [
                    'city_slug' => $city->city_slug ?? 'city',
                    'area_slug' => $area->area_slug ?? 'area',
                    'category_slug' => $page->categories->last()->category_slug ?? 'category',
                    'item_slug' => $page->item_slug ?? 'item',
                    'product_category_slug' => $productCategory->product_category_slug ?? 'subcategory',
                    'product_slug' => $product->product_slug ?? 'product'
                ]);
            @endphp

            <a href="{{ $productRoute }}" class="text-decoration-none">
                <div class="sponsor d-flex align-items-center border mb-2 p-2">
                    <img src="{{ get_product_image($product->image, 'thumbnail') }}" class="rounded me-2" width="50" height="50" alt="{{ $product->title }}">
                    <div class="text-truncate">
                        <h6 class="mb-1 text-dark">{{ ellipsis($product->title, 40) }}</h6>
                        <small class="text-muted">{{ $city->city_name ?? '' }}, {{ $area->area_name ?? '' }}</small><br>
                        <strong class="text-primary">
    {{ $product->getCurrency->symbol ?? '₹' }}{{ $product->product_selling_price }}
</strong>

@php
    $rating = round($product->averageRating() ?? 0);
@endphp
<div class="rating text-warning small mt-1">
    @for ($i = 1; $i <= 5; $i++)
        @if ($i <= $rating)
            ★
        @else
            ☆
        @endif
    @endfor
</div>

                    </div>
                    
                </div>
                
            </a>
        @endforeach
    </div>
</div>



</aside>



