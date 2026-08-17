 <nav class="profile-nav border bg-white mb-3"> 
    <ul class="nav align-items-left">
    @if(!empty($page))
    @php
    $catslug = $page->categories->last()->category_slug ?? null;
    @endphp
    <li class="nav-item @if(Route::currentRouteName() == 'single.page') active @endif"><a href="{{ route('single.page',['city_slug'=>$page->city->city_slug,'area_slug'=>$page->area->area_slug,'category_slug'=>$catslug,'item_slug'=>$page->item_slug]) }}" class="nav-link">{{ get_phrase('Timeline') }}</a></li>
        <li class="nav-item @if(Route::currentRouteName() == 'single.page.photos') active @endif"><a href="{{ route('single.page.photos',['city_slug'=>$page->city->city_slug,'area_slug'=>$page->area->area_slug,'category_slug'=>$catslug,'item_slug'=>$page->item_slug]) }}" class="nav-link">{{ get_phrase('Photo') }}</a></li>
        <li class="nav-item @if(Route::currentRouteName() == 'page.videos') active @endif"><a href="{{ route('page.videos',['city_slug'=>$page->city->city_slug,'area_slug'=>$page->area->area_slug,'category_slug'=>$catslug,'item_slug'=>$page->item_slug]) }}" class="nav-link">{{ get_phrase('Video') }}</a></li>


       
                        <li class="nav-item @if(Route::currentRouteName() == 'pages.events') active @endif"><a href="{{route('pages.events',['city_slug'=>$page->city->city_slug,'area_slug'=>$page->area->area_slug,'category_slug'=>$catslug,'item_slug'=>$page->item_slug])}}" class="nav-link">{{get_phrase('Event')}}</a></li>
                        <li class="nav-item @if(Route::currentRouteName() == 'pages.blogs') active @endif"><a href="{{route('pages.blogs',['city_slug'=>$page->city->city_slug,'area_slug'=>$page->area->area_slug,'category_slug'=>$catslug,'item_slug'=>$page->item_slug])}}" class="nav-link">{{get_phrase('Blog')}}</a></li>
                        <li class="nav-item @if(Route::currentRouteName() == 'pages.groups') active @endif"><a href="{{route('pages.groups',['city_slug'=>$page->city->city_slug,'area_slug'=>$page->area->area_slug,'category_slug'=>$catslug,'item_slug'=>$page->item_slug])}}" class="nav-link">{{get_phrase('Group')}}</a></li>
                        <li class="nav-item @if(Route::currentRouteName() == 'pages.products') active @endif"><a href="{{route('pages.products',['city_slug'=>$page->city->city_slug,'area_slug'=>$page->area->area_slug,'category_slug'=>$catslug,'item_slug'=>$page->item_slug])}}" class="nav-link">{{get_phrase('Offers')}}</a></li>
                        <li class="nav-item @if(Route::currentRouteName() == 'pages.info') active @endif"><a href="{{route('pages.info',['city_slug'=>$page->city->city_slug,'area_slug'=>$page->area->area_slug,'category_slug'=>$catslug,'item_slug'=>$page->item_slug])}}" class="nav-link">{{get_phrase('Info')}}</a></li>
        @endif
    </ul>
</nav>

<style>
  .profile-nav {
    border-radius: 12px;
    background: #ffffff;
    box-shadow: inset 0 1px 0 #eee, 0 5px 20px rgba(0, 0, 0, 0.05);
    padding: 0.5rem 1rem;
  }

  .profile-nav .nav {
    display: flex;
    flex-wrap: wrap;
    justify-content: start;
    gap: 0.6rem;
  }

  .profile-nav .nav-item {
    position: relative;
  }

  .profile-nav .nav-link {
    display: inline-block;
    padding: 10px 18px;
    font-weight: 600;
    color: #333;
    background: #f9f9f9;
    border-radius: 10px;
    border: 1px solid transparent;
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.06);
    transition: all 0.25s ease;
  }

  /* Hover effect (also used for active now) */
  .profile-nav .nav-link:hover,
  .profile-nav .nav-item.active .nav-link {
    background-color: #ffe4df;
    color: #ff4939;
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(255, 73, 57, 0.2);
    border-color: #ff4939;
  }
</style>



