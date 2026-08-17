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
            <a href="{{ route('user.dashboard') }}">
              <div class="sidebar_icon">
                <i class="fa-solid fa-house-user"></i>
              </div>
              <span class="link_name custom_dashboard_color">{{ get_phrase('Dashboard') }} </span>
            </a>
          </div>
        </li>
        <!-- Sidebar menu -->

 <li class="nav-links-li @if(Route::currentRouteName() == 'user.activity') showMenu @endif">
          <div class="iocn-link">
            <a class="w-100" href="{{ route('user.activity') }}">
              <div class="sidebar_icon">
                <i class="fa-solid fa-chart-line"></i>
              </div>
              <span class="link_name">
                {{ get_phrase('User Activity') }}
              </span>
            </a>
          </div>
        </li>
        <li class="nav-links-li  @if(Route::currentRouteName()=='user.ads' || Route::currentRouteName()=='user.ad.create' || Route::currentRouteName()=='user.ad.edit')showMenu @endif">
          <div class="iocn-link">
            <a href="#">
              <div class="sidebar_icon">
                <i class="fa-solid fa-rectangle-ad"></i>
              </div>
              <span class="link_name">{{ get_phrase('Ads') }}</span>
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
            <li><a class="@if(Route::currentRouteName()=='user.ads')Active @endif" href="{{ route('user.ads') }}">{{ get_phrase('Ad List') }}</a></li>
            <li><a class="@if(Route::currentRouteName()=='user.ad.create')Active @endif" href="{{ route('user.ad.create') }}">{{ get_phrase('Create Ad') }}</a></li>
          </ul>
        </li>

        <li class="nav-links-li @if(Route::currentRouteName() == 'user.pages' ||  Route::currentRouteName() =='user.listings.incomplete') showMenu @endif">
          <div class="iocn-link">
            <a class="w-100" href="{{ route('user.pages') }}">
              <div class="sidebar_icon">
                <i class="fa-solid fa-file-lines"></i>
              </div>
              <span class="link_name">
                {{ get_phrase('Pages') }}
              </span>
            </a>
          </div>
        </li>

        <li class="nav-links-li @if(Route::currentRouteName() == 'user.listings.incomplete') showMenu @endif">
          <div class="iocn-link">
            <a class="w-100" href="{{ route('user.listings.incomplete') }}">
              <div class="sidebar_icon">
                <i class="fa-solid fa-file-lines"></i>
              </div>
              <span class="link_name">
                {{ get_phrase('Incomplete Pages') }}
              </span>
            </a>
          </div>
        </li>

        <li class="nav-links-li @if(Route::currentRouteName() == 'admin.conversations.index') showMenu @endif">
  <div class="iocn-link">
    <a class="w-100" href="{{ route('admin.conversations.index') }}">
      <div class="sidebar_icon">
        <i class="fa-solid fa-comments"></i>
      </div>
      <span class="link_name">
        {{ get_phrase('Page Enquiry') }}
      </span>
    </a>
  </div>
</li>

        
<li class="nav-links-li @if(Route::currentRouteName() == 'admin.market.conversations.index') showMenu @endif">
  <div class="iocn-link">
    <a class="w-100" href="{{ route('admin.market.conversations.index') }}">
      <div class="sidebar_icon">
        <i class="fa-solid fa-store"></i>
      </div>
      <span class="link_name">
        {{ get_phrase('Marketplace Enquiry') }}
      </span>
    </a>
  </div>
</li>

        <li class="nav-links-li @if(Route::currentRouteName() == 'user.help.search') showMenu @endif">
          <div class="iocn-link">
            <a class="w-100" href="{{ route('user.help.search') }}">
              <div class="sidebar_icon">
                <i class="fa-solid fa-file-lines"></i>
              </div>
              <span class="link_name">
                {{ get_phrase('Help Center') }}
              </span>
            </a>
          </div>
        </li>

        <li class="nav-links-li @if(Route::currentRouteName() == 'user.products') showMenu @endif">
          <div class="iocn-link">
            <a class="w-100" href="{{ route('user.products') }}">
              <div class="sidebar_icon">
                <i class="fa-solid fa-store"></i>
              </div>
              <span class="link_name">
                {{ get_phrase('products') }}
              </span>
            </a>
          </div>
        </li>


        <li class="nav-links-li @if(Route::currentRouteName() == 'user.events') showMenu @endif">
          <div class="iocn-link">
            <a class="w-100" href="{{ route('user.events') }}">
              <div class="sidebar_icon">
                <i class="fa-solid fa-blog"></i>
              </div>
              <span class="link_name">
                {{ get_phrase('events') }}
              </span>
            </a>
          </div>
        </li>

        <li class="nav-links-li @if(Route::currentRouteName() == 'user.product.enquiry') showMenu @endif">
          <div class="iocn-link">
            <a class="w-100" href="{{ route('user.product.enquiry') }}">
              <div class="sidebar_icon">
                <i class="fa-solid fa-question-circle"></i>
              </div>
              <span class="link_name">
                {{ get_phrase('Enquiry') }}
              </span>
            </a>
          </div>
        </li>

        <!-- menu starts here -->
        <li class="nav-links-li @if(Route::currentRouteName() == 'user.payment_histories') showMenu @endif">
          <div class="iocn-link">
            <a class="w-100" href="{{ route('user.payment_histories') }}">
              <div class="sidebar_icon">
                <i class="fa-solid fa-credit-card"></i>
              </div>
              <span class="link_name">
                {{ get_phrase('Payment history') }}
              </span>
            </a>
          </div>
        </li>

        <li class="nav-links-li  @if(Route::currentRouteName()=='user.tickets' || Route::currentRouteName()=='admin.group.create' || Route::currentRouteName()=='admin.group.edit' )showMenu @endif">
          <div class="iocn-link">
            <a href="#">
              <div class="sidebar_icon">
                <i class="fa-solid fa-ticket"></i>
              </div>
              <span class="link_name">{{ get_phrase('Support') }}</span>
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
          <li><a  class="@if(Route::currentRouteName()=='user.tickets')Active @endif" href="{{ route('user.tickets') }}">{{ get_phrase('View Ticket') }}</a></li>
          </ul>
        </li>


        <li class="nav-links-li @if(Route::currentRouteName()=='user.subscriptions' || Route::currentRouteName()=='transactions.report') showMenu @endif">
          <div class="iocn-link">
              <a href="#">
                  <div class="sidebar_icon">
                      <i class="fa-solid fa-layer-group"></i>
                  </div>
                  <span class="link_name">{{ get_phrase('Subscriptions') }}</span>
              </a>
              <span class="arrow">
                  <svg xmlns="http://www.w3.org/2000/svg" width="4.743" height="7.773" viewBox="0 0 4.743 7.773">
                      <path id="navigate_before_FILL0_wght600_GRAD0_opsz24"
                            d="M1.466.247,4.5,3.277a.793.793,0,0,1,.189.288.92.92,0,0,1,0,.643A.793.793,0,0,1,4.5,4.5l-3.03,3.03a.828.828,0,0,1-.609.247.828.828,0,0,1-.609-.247.875.875,0,0,1,0-1.219L2.668,3.886.247,1.466A.828.828,0,0,1,0,.856.828.828,0,0,1,.247.247.828.828,0,0,1,.856,0,.828.828,0,0,1,1.466.247Z"
                            fill="#fff"
                            opacity="1"/>
                  </svg>
              </span>
          </div>
          <ul class="sub-menu">
              <li><a class="@if(Route::currentRouteName()=='user.subscriptions') Active @endif" href="{{ route('user.subscriptions') }}">{{ get_phrase('View Subscriptions') }}</a></li>
              <li><a href="{{ route('user.subscriptions') }}#my-subscriptions">{{ get_phrase('My Subscriptions') }}</a></li>
              <li><a class="@if(Route::currentRouteName()=='transactions.report') Active @endif" href="{{ route('transactions.report') }}">{{ get_phrase('Report') }}</a></li>
          </ul>
      </li>


        <li class="nav-links-li  @if(Route::currentRouteName()=='wallet.index' )showMenu @endif">
          <div class="iocn-link">
            <a href="#">
              <div class="sidebar_icon">
                <i class="fa-solid fa-ticket"></i>
              </div>
              <span class="link_name">{{ get_phrase('Wallet') }}</span>
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
          <li><a  class="@if(Route::currentRouteName()=='wallet.index')Active @endif" href="{{ route('wallet.index') }}">{{ get_phrase('Wallet') }}</a></li>
          </ul>
        </li>

        <li class="nav-links-li @if(Route::currentRouteName()=='leads.index' || Route::currentRouteName()=='user.lead.purchase.report') showMenu @endif">
    <div class="iocn-link">
        <a href="#">
            <div class="sidebar_icon">
                <i class="fa-solid fa-shopping-cart"></i>
            </div>
            <span class="link_name">{{ get_phrase('Buy Leads') }}</span>
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
            <a class="@if(Route::currentRouteName()=='leads.index') Active @endif" 
               href="{{ route('leads.index') }}">
                {{ get_phrase('Buy Leads') }}
            </a>
        </li>
        <li>
    <a class="@if(Route::currentRouteName()=='user.lead.purchase.report') Active @endif" 
       href="{{ route('user.lead.purchase.report') }}">
        {{ get_phrase('My Leads') }}
    </a>
</li>

    </ul>
</li>


      </ul>
    </div>