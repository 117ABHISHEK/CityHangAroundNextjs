<div class="profile-wrap">
    @include('frontend.pages.timeline-header')

    <div class="profile-content mt-3">
        <div class="profile-inner-nav-outer">
            @include('frontend.pages.inner-nav')
        </div>

<div class="friends-tab ct-tab bg-white p-3">
	
    <div class="photo-list mt-3">
        <!--<h4 class="h6 mb-3">{{get_phrase('Info')}}</h4>-->
        <div class="flex-wrap" >
        <section class="company-info">
 
     
        @if($page->openingHours->isNotEmpty())
         <div class="info-item w-full max-w-xl mx-auto bg-gradient-to-br from-indigo-100 to-purple-100 p-6 rounded-3xl shadow-2xl mb-8">
          <h3 class="text-3xl font-extrabold text-indigo-800 mb-6 flex items-center gap-2">
            <!--<i class="fas fa-clock text-indigo-500"></i>-->
           🕒 Open Hours
          </h3>
          <ul class="divide-y divide-indigo-200 text-sm font-medium">
            @foreach($page->openingHours as $hour)
              <li class="flex justify-between items-center py-3 px-2 hover:bg-white hover:rounded-xl transition-all duration-200">
                <span class="capitalize text-indigo-900 tracking-wide">{{ ucfirst($hour->day) }}</span>
                @if($hour->closed)
                  <span class="bg-red-100 text-red-600 text-xs font-semibold px-3 py-1 rounded-full shadow-sm">Closed</span>
                @else
                  <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
                    {{ \Carbon\Carbon::parse($hour->open)->format('g:i A') }} - {{ \Carbon\Carbon::parse($hour->close)->format('g:i A') }}
                  </span>
                @endif
              </li>
            @endforeach
          </ul>
        </div>



@endif


@if(!empty($page->why_visit_us))
  <div class="bg-white border border-gray-200 rounded-lg p-5 mb-6 shadow-sm">
    <h2 class="text-xl font-semibold text-gray-800 mb-3 border-b pb-2">Why Visit Us</h2>
    <div class="text-gray-700 leading-relaxed space-y-2">
      {!! $page->why_visit_us !!}
    </div>
  </div>
@endif

@if(!empty($page->our_story))
  <div class="bg-white border border-gray-200 rounded-lg p-5 mb-6 shadow-sm">
    <h2 class="text-xl font-semibold text-gray-800 mb-3 border-b pb-2">Our Story</h2>
    <div class="text-gray-700 leading-relaxed space-y-2">
      {!! $page->our_story !!}
    </div>
  </div>
@endif

@if(!empty($page->policy))
  <div class="bg-white border border-gray-200 rounded-lg p-5 mb-6 shadow-sm">
    <h2 class="text-xl font-semibold text-gray-800 mb-3 border-b pb-2">Policy</h2>
    <div class="text-gray-700 leading-relaxed space-y-2">
      {!! $page->policy !!}
    </div>
  </div>
@endif
@if($page->faqs->isNotEmpty())
  <div class="bg-white border border-gray-200 rounded-lg p-5 mb-6 shadow-sm">
    <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">FAQs</h2>
    <div class="space-y-4">
      @foreach($page->faqs as $faq)
        <div class="border-b pb-3">
          <h3 class="font-medium text-gray-900">{{ $faq->question }}</h3>
          <div class="text-gray-700 mt-1 leading-relaxed">
            {!! $faq->answer !!}
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endif
@if($page->media->isNotEmpty())
  <div class="bg-white border border-gray-200 rounded-lg p-5 mb-6 shadow-sm">
    <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Media Files</h2>
    <div class="space-y-4">
      @foreach($page->media as $media)
        <div class="flex items-center gap-4 border-b pb-3">
          @if($media->file_type === 'image')
            <img src="{{ asset('storage/pages/media/' . $media->file) }}" alt="Image" class="w-32 h-32 object-cover rounded-md">
          @elseif($media->file_type === 'video')
            <video controls class="w-32 h-32 rounded-md">
              <source src="{{ asset('storage/pages/media/' . $media->file) }}" type="video/mp4">
              Your browser does not support the video tag.
            </video>
          @else
            <div class="text-gray-600">{{ $media->file }}</div>
          @endif
        </div>
      @endforeach
    </div>
  </div>
@endif



     </section>
        </div>
    </div>

</div> <!-- Friends Tab End -->
</div>
</div>


