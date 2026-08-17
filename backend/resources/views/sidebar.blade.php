<div class="sidebar">
      <div class="logo-details mt-4">
        <div class="img_wrapper">
          @php
          $system_light_logo = \App\Models\Setting::where('type', 'system_light_logo')->value('description');
          $system_fav_icon = \App\Models\Setting::where('type', 'system_fav_icon')->value('description');
          @endphp
          <img class="logo-lg" height="34px" src="{{ get_system_logo_favicon($system_light_logo,'light') }}" alt="" />
          <img class="logo-sm" height="34px" src="{{ get_system_logo_favicon($system_fav_icon,'favicon') }}" alt="" />
        </div>
      </div>
      <div class="closeIcon">
        <span><i class="fas fa-close"></i></span>
      </div>
      <ul class="nav-links">
        <!-- sidebar title -->
        <li class="nav-links-li">
          <div class="iocn-link">
            <a href="{{ route('admin.dashboard') }}">
              <div class="sidebar_icon">
                <i class="fa-solid fa-house-user"></i>
              </div>
              <span class="link_name custom_dashboard_color">{{ get_phrase('Dashboard') }} </span>
            </a>
          </div>
        </li>
        <!-- Sidebar menu -->

        <!-- Sidebar menu -->
        <li class="nav-links-li @if(Route::currentRouteName()=='admin.view.category' || Route::currentRouteName()=='admin.users' || Route::currentRouteName()=='admin.user.add' || Route::currentRouteName()=='admin.user.edit')showMenu @endif">
          <div class="iocn-link">
            <a href="#">
              <div class="sidebar_icon">
                <i class="fa-solid fa-users"></i>
              </div>
              <span class="link_name">{{ get_phrase('User') }} </span>
            </a>
            <span class="arrow">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="4.743"
                height="7.773"
                viewBox="0 0 4.743 7.773"
              >
                <path
                  id="navigate_before_FILL0_wght600_GRAD0_opsz24"
                  d="M1.466.247,4.5,3.277a.793.793,0,0,1,.189.288.92.92,0,0,1,0,.643A.793.793,0,0,1,4.5,4.5l-3.03,3.03a.828.828,0,0,1-.609.247.828.828,0,0,1-.609-.247.875.875,0,0,1,0-1.219L2.668,3.886.247,1.466A.828.828,0,0,1,0,.856.828.828,0,0,1,.247.247.828.828,0,0,1,.856,0,.828.828,0,0,1,1.466.247Z"
                  fill="#fff"
                  opacity="1"
                />
              </svg>
            </span>
          </div>
          <ul class="sub-menu">
            <li><a  class="@if(Route::currentRouteName()=='admin.users')Active @endif" href="{{ route('admin.users') }}">{{ get_phrase('Users') }}</a></li>
            <li><a class="@if(Route::currentRouteName()=='admin.user.add')Active @endif" href="{{ route('admin.user.add') }}">{{ get_phrase('Create new user') }}</a></li>
          </ul>
        </li>

        <li class="nav-links-li @if(
    Route::currentRouteName()=='admin.countries' || 
    Route::currentRouteName()=='admin.countries.create' || 
    Route::currentRouteName()=='admin.countries.edit' || 
    Route::currentRouteName()=='admin.countries.show' || 
    Route::currentRouteName()=='admin.state' || 
    Route::currentRouteName()=='admin.cities' || 
    Route::currentRouteName()=='admin.areas' || 
    Route::currentRouteName()=='admin.cities.create' || 
    Route::currentRouteName()=='admin.state.create' || 
    Route::currentRouteName()=='admin.state.edit' || 
    Route::currentRouteName()=='admin.cities.edit' || 
    Route::currentRouteName()=='admin.areas.edit'
) showMenu @endif">
    
    <div class="iocn-link">
        <a href="#">
            <div class="sidebar_icon">
                <i class="fa-solid fa-map-marker-alt"></i>
            </div>
            <span class="link_name"> {{ get_phrase('Location Master') }} </span>
        </a>
        <span class="arrow">
            <svg xmlns="http://www.w3.org/2000/svg" width="4.743" height="7.773" viewBox="0 0 4.743 7.773">
                <path id="navigate_before_FILL0_wght600_GRAD0_opsz24"
                    d="M1.466.247,4.5,3.277a.793.793,0,0,1,.189.288.92.92,0,0,1,0,.643A.793.793,0,0,1,4.5,4.5l-3.03,3.03a.828.828,0,0,1-.609.247.828.828,0,0,1-.609-.247.875.875,0,0,1,0-1.219L2.668,3.886.247,1.466A.828.828,0,0,1,0,.856.828.828,0,0,1,.247.247.828.828,0,0,1,.856,0,.828.828,0,0,1,1.466.247Z"
                    fill="#fff" opacity="1" />
            </svg>
        </span>
    </div>

    <ul class="sub-menu">
        <li>
            <a class="@if(Route::currentRouteName()=='admin.countries') Active @endif" href="{{ route('admin.countries') }}">
                {{ get_phrase('Countries') }} ({{ \App\Models\Country::count() }})
            </a>
        </li>
        <li>
            <a class="@if(Route::currentRouteName()=='admin.state') Active @endif" href="{{ route('admin.state') }}">
                {{ get_phrase('State') }} ({{ \App\Models\State::count() }})
            </a>
        </li>
        <li>
            <a class="@if(Route::currentRouteName()=='admin.cities') Active @endif" href="{{ route('admin.cities') }}">
                {{ get_phrase('City') }} ({{ \App\Models\City::count() }})
            </a>
        </li>
        <li>
            <a class="@if(Route::currentRouteName()=='admin.areas') Active @endif" href="{{ route('admin.areas') }}">
                {{ get_phrase('Area') }} ({{ \App\Models\Area::count() }})
            </a>
        </li>
    </ul>
</li>




        <li class="nav-links-li @if(Route::currentRouteName()=='admin.user.page.category' || Route::currentRouteName()=='admin.user.product.category' || Route::currentRouteName()=='admin.user.event.category' || Route::currentRouteName()=='admin.user.city' || Route::currentRouteName()=='admin.user.area')showMenu @endif">
          <div class="iocn-link">
            <a href="#">
              <div class="sidebar_icon">
                <i class="fa-solid fa-store"></i>
              </div>
              <span class="link_name"> {{ get_phrase('User Suggestion') }} </span>
            </a>
            <span class="arrow">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="4.743"
                height="7.773"
                viewBox="0 0 4.743 7.773"
              >
                <path
                  id="navigate_before_FILL0_wght600_GRAD0_opsz24"
                  d="M1.466.247,4.5,3.277a.793.793,0,0,1,.189.288.92.92,0,0,1,0,.643A.793.793,0,0,1,4.5,4.5l-3.03,3.03a.828.828,0,0,1-.609.247.828.828,0,0,1-.609-.247.875.875,0,0,1,0-1.219L2.668,3.886.247,1.466A.828.828,0,0,1,0,.856.828.828,0,0,1,.247.247.828.828,0,0,1,.856,0,.828.828,0,0,1,1.466.247Z"
                  fill="#fff"
                  opacity="1"
                />
              </svg>
            </span>
          </div>
          <ul class="sub-menu">

          <li><a  class="@if(Route::currentRouteName()=='admin.user.page.category' )Active @endif" href="{{ route('admin.user.page.category') }}">{{ get_phrase('Page Category') }}</a></li>
            <li><a class="@if(Route::currentRouteName()=='admin.user.product.category')Active @endif" href="{{ route('admin.user.product.category') }}">{{ get_phrase('Product Category') }}</a></li>
            <li><a class="@if(Route::currentRouteName()=='admin.user.event.category')Active @endif" href="{{ route('admin.user.event.category') }}">{{ get_phrase('Event Category') }}</a></li>
            <li><a class="@if(Route::currentRouteName()=='admin.user.city')Active @endif" href="{{ route('admin.user.city') }}">{{ get_phrase('City') }}</a></li>
            <li><a class="@if(Route::currentRouteName()=='admin.user.area')Active @endif" href="{{ route('admin.user.area') }}">{{ get_phrase('Area') }}</a></li>
            <li><a class="@if(Route::currentRouteName()=='spam.index')Active @endif" href="{{ route('spam.index') }}">{{ get_phrase('Spam Words') }}</a></li>
          </ul>
        </li>
        
        <li class="nav-links-li @if(Route::currentRouteName()=='admin.claim_listings' || Route::currentRouteName()=='admin.view.category' || Route::currentRouteName()=='admin.page' || Route::currentRouteName()=='admin.create.category' || Route::currentRouteName()=='admin.page.create' || Route::currentRouteName()=='admin.page.edit' ||  Route::currentRouteName() =='admin.listings.incomplete')showMenu @endif">
          <div class="iocn-link">
            <a href="#">
              <div class="sidebar_icon">
                <i class="fa-solid fa-file-lines"></i>
              </div>
              <span class="link_name">{{ get_phrase('Page') }} </span>
            </a>
            <span class="arrow">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="4.743"
                height="7.773"
                viewBox="0 0 4.743 7.773"
              >
                <path
                  id="navigate_before_FILL0_wght600_GRAD0_opsz24"
                  d="M1.466.247,4.5,3.277a.793.793,0,0,1,.189.288.92.92,0,0,1,0,.643A.793.793,0,0,1,4.5,4.5l-3.03,3.03a.828.828,0,0,1-.609.247.828.828,0,0,1-.609-.247.875.875,0,0,1,0-1.219L2.668,3.886.247,1.466A.828.828,0,0,1,0,.856.828.828,0,0,1,.247.247.828.828,0,0,1,.856,0,.828.828,0,0,1,1.466.247Z"
                  fill="#fff"
                  opacity="1"
                />
              </svg>
            </span>
          </div>
          <ul class="sub-menu">
            <li><a  class="@if(Route::currentRouteName()=='admin.page')Active @endif" href="{{ route('admin.page') }}">{{ get_phrase('Pages') }} ({{ \App\Models\Page::count() }})</a></li>
            <li><a  class="@if(Route::currentRouteName()=='admin.listings.incomplete')Active @endif" href="{{ route('admin.listings.incomplete') }}">{{ get_phrase('Incomplete Pages') }} ({{ \App\Models\IncompleteListing::count() }})</a></li>
            <li><a  class="@if(Route::currentRouteName()=='admin.page.pending')Active @endif" href="{{ route('admin.page') }}">{{ get_phrase('Pending Pages') }} ({{ \App\Models\Page::count() }})</a></li>
            <li><a class="@if(Route::currentRouteName()=='admin.page.create')Active @endif" href="{{ route('admin.page.create') }}">{{ get_phrase('Create Page') }}</a></li>
            <li>
          <a class="{{ Route::currentRouteName() == 'admin.category.bulk-update' ? 'Active' : '' }}" 
            href="{{ route('admin.category.bulk-update') }}">
              {{ get_phrase('Bulk Category Update') }}
          </a>
      </li>

            <li><a  class="@if(Route::currentRouteName()=='admin.view.category')Active @endif" href="{{ route('admin.view.category') }}">{{ get_phrase('Category') }} ({{ \App\Models\Pagecategory::count() }})</a></li>
            <li><a class="@if(Route::currentRouteName()=='admin.create.category')Active @endif" href="{{ route('admin.create.category') }}">{{ get_phrase('Create Category') }}</a></li>
            <li><a class="@if(Route::currentRouteName()==' admin.claim_listings')Active @endif" href="{{ route('admin.claim_listings') }}">{{ get_phrase('Claim Listing') }}</a></li>
           
          </ul>
        </li>

        <li class="nav-links-li @if(Route::currentRouteName()=='admin.product' || Route::currentRouteName()=='admin.product.create' || Route::currentRouteName()=='admin.product.edit' || Route::currentRouteName()=='admin.view.product.category' || Route::currentRouteName()=='admin.create.product.category' || Route::currentRouteName()=='admin.view.product.brand' || Route::currentRouteName()=='admin.create.product.brand' || Route::currentRouteName()=='admin.view.product.enquiry' || Route::currentRouteName()=='categories.index' || Route::currentRouteName()=='admin.view.attributes.index')showMenu @endif">
          <div class="iocn-link">
            <a href="#">
              <div class="sidebar_icon">
                <i class="fa-solid fa-store"></i>
              </div>
              <span class="link_name"> {{ get_phrase('Marketplace') }} </span>
            </a>
            <span class="arrow">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="4.743"
                height="7.773"
                viewBox="0 0 4.743 7.773"
              >
                <path
                  id="navigate_before_FILL0_wght600_GRAD0_opsz24"
                  d="M1.466.247,4.5,3.277a.793.793,0,0,1,.189.288.92.92,0,0,1,0,.643A.793.793,0,0,1,4.5,4.5l-3.03,3.03a.828.828,0,0,1-.609.247.828.828,0,0,1-.609-.247.875.875,0,0,1,0-1.219L2.668,3.886.247,1.466A.828.828,0,0,1,0,.856.828.828,0,0,1,.247.247.828.828,0,0,1,.856,0,.828.828,0,0,1,1.466.247Z"
                  fill="#fff"
                  opacity="1"
                />
              </svg>
            </span>
          </div>
          <ul class="sub-menu">

          <li><a  class="@if(Route::currentRouteName()=='admin.product' || Route::currentRouteName()=='admin.product.edit')Active @endif" href="{{ route('admin.product') }}">{{ get_phrase('Product') }} ({{ \App\Models\Marketplace::count() }})</a></li>
            <li><a class="@if(Route::currentRouteName()=='admin.product.create')Active @endif" href="{{ route('admin.product.create') }}">{{ get_phrase('Create Product') }}</a></li>
            <li><a class="@if(Route::currentRouteName()=='admin.view.product.category' || Route::currentRouteName()=='admin.create.product.category')Active @endif" href="{{ route('admin.view.product.category') }}">{{ get_phrase('Category') }} ({{ \App\Models\Category::count() }})</a></li>

            <li><a class="@if(Route::currentRouteName()=='admin.view.attributes.index' || Route::currentRouteName()=='admin.view.attributes.index')Active @endif" href="{{ route('admin.view.attributes.index') }}">{{ get_phrase('Attribute') }} </a></li>

            <li><a class="@if(Route::currentRouteName()=='admin.view.product.brand' || Route::currentRouteName()=='admin.create.product.brand')Active @endif" href="{{ route('admin.view.product.brand') }}">{{ get_phrase('Brand') }}</a></li>
            <li><a class="@if(Route::currentRouteName()=='admin.view.product.enquiry')Active @endif" href="{{ route('admin.view.product.enquiry') }}">{{ get_phrase('Enquiry') }}</a></li>
            <li>
              <a class="@if(Route::currentRouteName()=='categories.index') Active @endif" 
                href="{{ route('categories.index') }}">
                {{ get_phrase('Categories') }}
              </a>
          </li>
          </ul>
        </li>

        <li class="nav-links-li  @if(Route::currentRouteName()=='admin.blog' || Route::currentRouteName()=='admin.view.blog.category' || Route::currentRouteName()=='admin.create.blog.category' || Route::currentRouteName()=='admin.blog.create' || Route::currentRouteName()=='admin.blog.edit')showMenu @endif">
          <div class="iocn-link">
            <a href="#">
              <div class="sidebar_icon">
                <i class="fa-solid fa-blog"></i>
              </div>
              <span class="link_name">{{ get_phrase('Blog') }}</span>
            </a>
            <span class="arrow">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="4.743"
                height="7.773"
                viewBox="0 0 4.743 7.773"
              >
                <path
                  id="navigate_before_FILL0_wght600_GRAD0_opsz24"
                  d="M1.466.247,4.5,3.277a.793.793,0,0,1,.189.288.92.92,0,0,1,0,.643A.793.793,0,0,1,4.5,4.5l-3.03,3.03a.828.828,0,0,1-.609.247.828.828,0,0,1-.609-.247.875.875,0,0,1,0-1.219L2.668,3.886.247,1.466A.828.828,0,0,1,0,.856.828.828,0,0,1,.247.247.828.828,0,0,1,.856,0,.828.828,0,0,1,1.466.247Z"
                  fill="#fff"
                  opacity="1"
                />
              </svg>
            </span>
          </div>
          <ul class="sub-menu">
            <li><a  class="@if(Route::currentRouteName()=='admin.blog')Active @endif" href="{{ route('admin.blog') }}">{{ get_phrase('Blogs') }} ({{ \App\Models\Blog::count() }})</a></li>
            <li><a class="@if(Route::currentRouteName()=='admin.blog.create')Active @endif" href="{{ route('admin.blog.create') }}">{{ get_phrase('Create Blog') }}</a></li>
            <li><a class="@if(Route::currentRouteName()=='admin.view.blog.category')Active @endif" href="{{ route('admin.view.blog.category') }}">{{ get_phrase('Category') }} ({{ \App\Models\Blogcategory::count() }})</a></li>
            <li><a class="@if(Route::currentRouteName()=='admin.create.blog.category')Active @endif" href="{{ route('admin.create.blog.category') }}">{{ get_phrase('Create Category') }}</a></li>
          </ul>
        </li>
     

        <li class="nav-links-li  @if(Route::currentRouteName()=='admin.view.event' || Route::currentRouteName()=='admin.previous.event' || Route::currentRouteName()=='admin.upcoming.event' || Route::currentRouteName()=='admin.event.create' || Route::currentRouteName()=='admin.edit.event'   || Route::currentRouteName()=='admin.view.event.category' || Route::currentRouteName()=='admin.create.event.category' )showMenu @endif">
          <div class="iocn-link">
            <a href="#">
              <div class="sidebar_icon">
                <i class="fa-solid fa-blog"></i>
              </div>
              <span class="link_name">{{ get_phrase('Event') }}</span>
            </a>
            <span class="arrow">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="4.743"
                height="7.773"
                viewBox="0 0 4.743 7.773"
              >
                <path
                  id="navigate_before_FILL0_wght600_GRAD0_opsz24"
                  d="M1.466.247,4.5,3.277a.793.793,0,0,1,.189.288.92.92,0,0,1,0,.643A.793.793,0,0,1,4.5,4.5l-3.03,3.03a.828.828,0,0,1-.609.247.828.828,0,0,1-.609-.247.875.875,0,0,1,0-1.219L2.668,3.886.247,1.466A.828.828,0,0,1,0,.856.828.828,0,0,1,.247.247.828.828,0,0,1,.856,0,.828.828,0,0,1,1.466.247Z"
                  fill="#fff"
                  opacity="1"
                />
              </svg>
            </span>
          </div>
          <ul class="sub-menu">
          <li><a class="@if(Route::currentRouteName()=='admin.view.event' || Route::currentRouteName()=='admin.event.create' || Route::currentRouteName()=='admin.edit.event'  )Active @endif" href="{{ route('admin.view.event') }}">{{ get_phrase('Event') }} ({{ \App\Models\Event::count() }})</a></li>
          <li><a class="@if(Route::currentRouteName()=='admin.upcoming.event')Active @endif" href="{{ route('admin.upcoming.event') }}">{{ get_phrase('Upcoming') }} ({{ \App\Models\Event::where('event_date', '>', now())->count() }})</a></li>
          <li><a class="@if(Route::currentRouteName()=='admin.previous.event')Active @endif" href="{{ route('admin.previous.event') }}">{{ get_phrase('Previous') }} ({{ \App\Models\Event::where('event_date', '<', now())->count() }})</a></li>


            <li><a class="@if(Route::currentRouteName()=='admin.view.event.category')Active @endif" href="{{ route('admin.view.event.category') }}">{{ get_phrase('Category') }}  ({{ \App\Models\Eventcategory::count() }})</a></li>
            <li><a class="@if(Route::currentRouteName()=='admin.create.event.category')Active @endif" href="{{ route('admin.create.event.category') }}">{{ get_phrase('Create Category') }}</a></li>
          </ul>
        </li>


        <li class="nav-links-li  @if(Route::currentRouteName()=='admin.group' || Route::currentRouteName()=='admin.group.create' || Route::currentRouteName()=='admin.group.edit' )showMenu @endif">
          <div class="iocn-link">
            <a href="#">
              <div class="sidebar_icon">
                <i class="fa-solid fa-users"></i>
              </div>
              <span class="link_name">{{ get_phrase('Groups') }}</span>
            </a>
            <span class="arrow">
            <span class="arrow">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="4.743"
                height="7.773"
                viewBox="0 0 4.743 7.773"
              >
                <path
                  id="navigate_before_FILL0_wght600_GRAD0_opsz24"
                  d="M1.466.247,4.5,3.277a.793.793,0,0,1,.189.288.92.92,0,0,1,0,.643A.793.793,0,0,1,4.5,4.5l-3.03,3.03a.828.828,0,0,1-.609.247.828.828,0,0,1-.609-.247.875.875,0,0,1,0-1.219L2.668,3.886.247,1.466A.828.828,0,0,1,0,.856.828.828,0,0,1,.247.247.828.828,0,0,1,.856,0,.828.828,0,0,1,1.466.247Z"
                  fill="#fff"
                  opacity="1"
                />
              </svg>
            </span>
          </div>
          <ul class="sub-menu">
          <li><a  class="@if(Route::currentRouteName()=='admin.group.categories' || Route::currentRouteName()=='admin.group.edit')Active @endif" href="{{ route('admin.group.categories') }}">{{ get_phrase('Groups Category') }} ({{ \App\Models\Groupcategory::count() }})</a></li>
          <li><a  class="@if(Route::currentRouteName()=='admin.group' || Route::currentRouteName()=='admin.group.edit')Active @endif" href="{{ route('admin.group') }}">{{ get_phrase('Groups') }} ({{ \App\Models\Group::count() }})</a></li>
            <li><a class="@if(Route::currentRouteName()=='admin.group.create')Active @endif" href="{{ route('admin.group.create') }}">{{ get_phrase('Create Group') }}</a></li>
          </ul>
        </li>


        <li class="nav-links-li @if(
    Route::currentRouteName()=='admin.subscriptions.index' || 
    Route::currentRouteName()=='admin.subscriptions.create' || 
    Route::currentRouteName()=='admin.subscriptions.edit' ||
    Route::currentRouteName()=='admin.features.index' ||
    Route::currentRouteName()=='admin.features.create' ||
    Route::currentRouteName()=='admin.features.edit' ||
    Route::currentRouteName()=='admin.mappings.index' ||
    Route::currentRouteName()=='admin.mappings.create' ||
    Route::currentRouteName()=='admin.mappings.edit' ||
    Route::currentRouteName()=='admin.transactions.report' 
) showMenu @endif">
    <div class="iocn-link">
        <a href="#">
            <div class="sidebar_icon">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <span class="link_name">{{ get_phrase('Subscriptions') }}</span>
        </a>
        <span class="arrow">
            <svg xmlns="http://www.w3.org/2000/svg" width="4.743" height="7.773" viewBox="0 0 4.743 7.773">
                <path
                    id="navigate_before_FILL0_wght600_GRAD0_opsz24"
                    d="M1.466.247,4.5,3.277a.793.793,0,0,1,.189.288.92.92,0,0,1,0,.643A.793.793,0,0,1,4.5,4.5l-3.03,3.03a.828.828,0,0,1-.609.247.828.828,0,0,1-.609-.247.875.875,0,0,1,0-1.219L2.668,3.886.247,1.466A.828.828,0,0,1,0,.856.828.828,0,0,1,.247.247.828.828,0,0,1,.856,0,.828.828,0,0,1,1.466.247Z"
                    fill="#fff"
                    opacity="1"
                />
            </svg>
        </span>
    </div>
    <ul class="sub-menu">
        <li>
            <a class="@if(Route::currentRouteName()=='admin.subscriptions.index' || Route::currentRouteName()=='admin.subscriptions.edit') Active @endif" 
               href="{{ route('admin.subscriptions.index') }}">
               {{ get_phrase('Manage Subscriptions') }} ({{ \App\Models\Subscription::count() }})
            </a>
        </li>
        <li>
            <a class="@if(Route::currentRouteName()=='admin.features.index' || Route::currentRouteName()=='admin.features.edit') Active @endif" 
               href="{{ route('admin.features.index') }}">
               {{ get_phrase('Subscription Features') }} ({{ \App\Models\SubscriptionFeature::count() }})
            </a>
        </li>
        <li>
            <a class="@if(Route::currentRouteName()=='admin.mappings.index' || Route::currentRouteName()=='admin.mappings.edit') Active @endif" 
               href="{{ route('admin.mappings.index') }}">
               {{ get_phrase('Feature Mappings') }} ({{ \App\Models\SubscriptionFeatureMapping::count() }})
            </a>
        </li>

        <li>
            <a class="@if(Route::currentRouteName()=='admin.transactions.report' ) Active @endif" 
               href="{{ route('admin.transactions.report') }}">
               {{ get_phrase('Report') }} 
            </a>
        </li>
    </ul>
</li>


<li class="nav-links-li @if(
    Route::currentRouteName()=='admin.campaigns.index' || 
    Route::currentRouteName()=='admin.campaigns.create' || 
    Route::currentRouteName()=='admin.campaigns.edit' ||
    Route::currentRouteName()=='admin.campaign_templates.index' ||
    Route::currentRouteName()=='admin.campaign_templates.create' ||
    Route::currentRouteName()=='admin.campaign_templates.edit' ||
    Route::currentRouteName()=='admin.mailing_lists.index' ||
    Route::currentRouteName()=='admin.mailing_lists.create' ||
    Route::currentRouteName()=='admin.mailing_lists.edit' 
) showMenu @endif">
    <div class="iocn-link">
        <a href="#">
            <div class="sidebar_icon">
                <i class="fa-solid fa-envelope"></i>
            </div>
            <span class="link_name">{{ get_phrase('Email Campaign') }}</span>
        </a>
        <span class="arrow">
            <svg xmlns="http://www.w3.org/2000/svg" width="4.743" height="7.773" viewBox="0 0 4.743 7.773">
                <path
                    id="navigate_before_FILL0_wght600_GRAD0_opsz24"
                    d="M1.466.247,4.5,3.277a.793.793,0,0,1,.189.288.92.92,0,0,1,0,.643A.793.793,0,0,1,4.5,4.5l-3.03,3.03a.828.828,0,0,1-.609.247.828.828,0,0,1-.609-.247.875.875,0,0,1,0-1.219L2.668,3.886.247,1.466A.828.828,0,0,1,0,.856.828.828,0,0,1,.247.247.828.828,0,0,1,.856,0,.828.828,0,0,1,1.466.247Z"
                    fill="#fff"
                    opacity="1"
                />
            </svg>
        </span>
    </div>
    <ul class="sub-menu">

    <li>
            <a class="@if(Route::currentRouteName()=='admin.campaign_templates.index' || Route::currentRouteName()=='admin.campaign_templates.create' || Route::currentRouteName()=='admin.campaign_templates.edit') Active @endif" 
               href="{{ route('admin.campaign_templates.index') }}">
               {{ get_phrase('Campaign Templates') }} ({{ \App\Models\CampaignTemplate::count() }})
            </a>
        </li>

        <li>
            <a class="@if(Route::currentRouteName()=='admin.mailing_lists.index' || Route::currentRouteName()=='admin.mailing_lists.create' || Route::currentRouteName()=='admin.mailing_lists.edit') Active @endif" 
               href="{{ route('admin.mailing_lists.index') }}">
               {{ get_phrase('Mailing Lists') }} ({{ \App\Models\MailingList::count() }})
            </a>
        </li>
        <li>
            <a class="@if(Route::currentRouteName()=='admin.campaigns.index' || Route::currentRouteName()=='admin.campaigns.create' || Route::currentRouteName()=='admin.campaigns.edit') Active @endif" 
               href="{{ route('admin.campaigns.index') }}">
               {{ get_phrase('Manage Campaigns') }} ({{ \App\Models\Campaign::count() }})
            </a>
        </li>
    </ul>
</li>



        <li class="nav-links-li  @if(Route::currentRouteName()=='admin.view.sponsor' || Route::currentRouteName()=='admin.create.sponsor')showMenu @endif">
          <div class="iocn-link">
            <a href="#">
              <div class="sidebar_icon">
                <i class="fa-solid fa-rectangle-ad"></i>
              </div>
              <span class="link_name">{{ get_phrase('Sponsored Post') }}</span>
            </a>
            <span class="arrow">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="4.743"
                height="7.773"
                viewBox="0 0 4.743 7.773"
              >
                <path
                  id="navigate_before_FILL0_wght600_GRAD0_opsz24"
                  d="M1.466.247,4.5,3.277a.793.793,0,0,1,.189.288.92.92,0,0,1,0,.643A.793.793,0,0,1,4.5,4.5l-3.03,3.03a.828.828,0,0,1-.609.247.828.828,0,0,1-.609-.247.875.875,0,0,1,0-1.219L2.668,3.886.247,1.466A.828.828,0,0,1,0,.856.828.828,0,0,1,.247.247.828.828,0,0,1,.856,0,.828.828,0,0,1,1.466.247Z"
                  fill="#fff"
                  opacity="1"
                />
              </svg>
            </span>
          </div>
          <ul class="sub-menu">
            <li><a class="@if(Route::currentRouteName()=='admin.view.sponsor')Active @endif" href="{{ route('admin.view.sponsor') }}">{{ get_phrase('Ads') }}</a></li>
            <li><a class="@if(Route::currentRouteName()=='admin.create.sponsor')Active @endif" href="{{ route('admin.create.sponsor') }}">{{ get_phrase('Create Ad') }}</a></li>
          </ul>
        </li>

        <li class="nav-links-li @if(Route::currentRouteName() == 'admin.wallet.report') showMenu @endif">
          <div class="iocn-link">
              <a class="w-100" href="{{ route('admin.wallet.report') }}">
                  <div class="sidebar_icon">
                      <i class="fa-solid fa-wallet"></i> <!-- Wallet Icon -->
                  </div>
                  <span class="link_name">
                      {{ get_phrase('Wallet Report') }}
                  </span>
              </a>
          </div>
      </li>

      <li class="nav-links-li @if(Route::currentRouteName() == 'custom_pages.list') showMenu @endif">
          <div class="iocn-link">
              <a class="w-100" href="{{ route('custom_pages.list') }}">
                  <div class="sidebar_icon">
                      <i class="fa-solid fa-wallet"></i> <!-- Wallet Icon -->
                  </div>
                  <span class="link_name">
                      {{ get_phrase('Custom Pages') }}
                  </span>
              </a>
          </div>
      </li>

      <li class="nav-links-li @if(Route::currentRouteName() == 'enquiry-lead-stages.index') showMenu @endif">
    <div class="iocn-link">
        <a class="w-100" href="{{ route('enquiry-lead-stages.index') }}">
            <div class="sidebar_icon">
                <i class="fa-solid fa-layer-group"></i> <!-- Lead Stages Icon -->
            </div>
            <span class="link_name">
                {{ get_phrase('Enquiry Lead Stages') }}
            </span>
        </a>
    </div>
</li>



<li class="nav-links-li @if(Route::currentRouteName() == 'admin.lead.purchase.report') showMenu @endif">
    <div class="iocn-link">
        <a class="w-100" href="{{ route('admin.lead.purchase.report') }}">
            <div class="sidebar_icon">
                <i class="fa-solid fa-wallet"></i> <!-- Wallet Icon -->
            </div>
            <span class="link_name">
                {{ get_phrase('Lead Purchase') }}
            </span>
        </a>
    </div>
</li>


<li class="nav-links-li @if(Route::currentRouteName() == 'admin.help-articles.index') showMenu @endif">
    <div class="iocn-link">
        <a class="w-100" href="{{ route('admin.help-articles.index') }}">
            <div class="sidebar_icon">
                <i class="fa-solid fa-circle-question"></i> <!-- Help Article Icon -->
            </div>
            <span class="link_name">
                {{ get_phrase('Help Articles') }}
            </span>
        </a>
    </div>
</li>



        <li class="nav-links-li @if(Route::currentRouteName() == 'admin.videos') showMenu @endif">
          <div class="iocn-link">
            <a class="w-100" href="{{ route('admin.videos') }}">
              <div class="sidebar_icon">
                <i class="fa-solid fa-credit-card"></i>
              </div>
              <span class="link_name">
                {{ get_phrase('Videos') }}
              </span>
            </a>
          </div>
        </li>

         <!-- menu starts here -->
         <li class="nav-links-li @if(Route::currentRouteName() == 'admin.reports.list') showMenu @endif">
          <div class="iocn-link">
            <a class="w-100" href="{{ route('admin.reports.list') }}">
              <div class="sidebar_icon">
                <i class="fa-solid fa-ban"></i>
              </div>
              <span class="link_name">
                {{ get_phrase('Reported') }}
              </span>
            </a>
          </div>
        </li>

        <!-- menu starts here -->
        <li class="nav-links-li @if(Route::currentRouteName() == 'admin.reported.post.view') showMenu @endif">
          <div class="iocn-link">
            <a class="w-100" href="{{ route('admin.reported.post.view') }}">
              <div class="sidebar_icon">
                <i class="fa-solid fa-ban"></i>
              </div>
              <span class="link_name">
                {{ get_phrase('Reported Post') }}
              </span>
            </a>
          </div>
        </li>

        <!-- Event Master Menu -->
      <li class="nav-links-li @if(Route::currentRouteName() == 'admin.event.index') showMenu @endif">
          <div class="iocn-link">
              <a class="w-100" href="{{ route('admin.event.index') }}">
                  <div class="sidebar_icon">
                      <i class="fa-solid fa-list"></i> <!-- Choose an appropriate icon -->
                  </div>
                  <span class="link_name">
                      {{ get_phrase('Event Master') }}
                  </span>
              </a>
          </div>
      </li>





        <!-- menu starts here -->
        <li class="nav-links-li @if(Route::currentRouteName() == 'admin.payment_histories') showMenu @endif">
          <div class="iocn-link">
            <a class="w-100" href="{{ route('admin.payment_histories') }}">
              <div class="sidebar_icon">
                <i class="fa-solid fa-credit-card"></i>
              </div>
              <span class="link_name">
                {{ get_phrase('Payment history') }}
              </span>
            </a>
          </div>
        </li>

        <li class="nav-links-li @if(Route::currentRouteName()=='admin.tickets.list' || Route::currentRouteName()=='admin.tickets.show') showMenu @endif">
    <div class="iocn-link">
        <a href="#">
            <div class="sidebar_icon">
                <i class="fa-solid fa-ticket"></i>
            </div>
            <span class="link_name">{{ get_phrase('Support') }}</span>
        </a>
        <span class="arrow">
            <svg xmlns="http://www.w3.org/2000/svg" width="4.743" height="7.773" viewBox="0 0 4.743 7.773">
                <path id="navigate_before_FILL0_wght600_GRAD0_opsz24"
                      d="M1.466.247,4.5,3.277a.793.793,0,0,1,.189.288.92.92,0,0,1,0,.643A.793.793,0,0,1,4.5,4.5l-3.03,3.03a.828.828,0,0,1-.609.247.828.828,0,0,1-.609-.247.875.875,0,0,1,0-1.219L2.668,3.886.247,1.466A.828.828,0,0,1,0,.856.828.828,0,0,1,.247.247.828.828,0,0,1,.856,0,.828.828,0,0,1,1.466.247Z"
                      fill="#fff" opacity="1" />
            </svg>
        </span>
    </div>
    <ul class="sub-menu">
        <li><a class="@if(Route::currentRouteName()=='admin.tickets.list') Active @endif" href="{{ route('admin.tickets.list') }}">{{ get_phrase('View Tickets') }}</a></li>
    </ul>
</li>


        <li class="nav-links-li @if(Route::currentRouteName()=='admin.about.page.data.view' || Route::currentRouteName()=='admin.live-video.view' || Route::currentRouteName()=='admin.privacy.page.data.view'|| Route::currentRouteName()=='admin.term.page.data.view' || Route::currentRouteName()=='admin.smtp.settings.view'|| Route::currentRouteName()=='admin.system.settings.view' || 
        Route::currentRouteName()=='admin.settings.payment' || 
        Route::currentRouteName()=='admin.language.settings' || Route::currentRouteName()=='admin.about' || Route::currentRouteName()=='admin.settings.amazon_s3' || 'admin.languages.edit.phrase' == Route::currentRouteName())showMenu @endif">
          <div class="iocn-link">
            <a href="#">
              <div class="sidebar_icon">
                <i class="fa-solid fa-gear"></i>
              </div>
              <span class="link_name">{{ get_phrase('Settings') }}</span>
            </a>
            <span class="arrow">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="4.743"
                height="7.773"
                viewBox="0 0 4.743 7.773"
              >
                <path
                  id="navigate_before_FILL0_wght600_GRAD0_opsz24"
                  d="M1.466.247,4.5,3.277a.793.793,0,0,1,.189.288.92.92,0,0,1,0,.643A.793.793,0,0,1,4.5,4.5l-3.03,3.03a.828.828,0,0,1-.609.247.828.828,0,0,1-.609-.247.875.875,0,0,1,0-1.219L2.668,3.886.247,1.466A.828.828,0,0,1,0,.856.828.828,0,0,1,.247.247.828.828,0,0,1,.856,0,.828.828,0,0,1,1.466.247Z"
                  fill="#fff"
                  opacity="1"
                />
              </svg>
            </span>
          </div>
          <ul class="sub-menu">
            <li><a class="@if(Route::currentRouteName()=='admin.system.settings.view')Active @endif" href="{{ route('admin.system.settings.view') }}">{{ get_phrase('System Setting') }}</a></li>
            <li><a class="@if(Route::currentRouteName()=='admin.settings.amazon_s3')Active @endif" href="{{ route('admin.settings.amazon_s3') }}">{{ get_phrase('Amazon s3 settings') }} </a></li>
            <li><a class="@if(Route::currentRouteName()=='admin.about.page.data.view')Active @endif" href="{{ route('admin.about.page.data.view') }}">{{ get_phrase('Custom Pages') }} </a></li>
            <li><a class="@if(Route::currentRouteName()=='admin.live-video.view')Active @endif" href="{{ route('admin.live-video.view') }}">{{ get_phrase('Live video') }} </a></li>
            <li><a class="@if(Route::currentRouteName()=='admin.settings.payment')Active @endif" href="{{ route('admin.settings.payment') }}">{{ get_phrase('Payment Setting') }}</a></li>
            <li><a class="@if(Route::currentRouteName()=='admin.language.settings' || 'admin.languages.edit.phrase' == Route::currentRouteName())Active @endif" href="{{ route('admin.language.settings') }}">{{ get_phrase('Language Setting') }}</a></li>
            <li><a class="@if(Route::currentRouteName()=='admin.smtp.settings.view')Active @endif" href="{{ route('admin.smtp.settings.view') }}">{{ get_phrase('SMTP Setting') }}</a></li>
            <li><a class="@if(Route::currentRouteName()=='admin.about')Active @endif" href="{{ route('admin.about') }}">{{ get_phrase('About') }}</a></li>
          </ul>
        </li>
      </ul>
    </div>