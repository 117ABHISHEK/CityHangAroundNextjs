<div class="page-wrap">

<div class="row">
                    <div class="col-md-12">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb bg-white pl-0 pr-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('pages') }}">
                                        <i class="fas fa-bars"></i>
                                       Home
                                    </a>
                                </li>
                                <li class="breadcrumb-item">Blog</li>
</ol>
</nav>
</div>
</div>
    <div class="blog-header mb-3" style="background-image: url('{{ asset('assets/frontend/images/blog-bg.png') }}')">
        <h1 class="h3">{{get_phrase('Blogs')}}</h1>
        <p>{{ get_phrase('Discover new articles') }}</p>
        <div class="btn-inline">
            <a href="{{ route('create.blog') }}" class="btn btn-primary btn-sm"> <i class="fa fa-circle-plus me-2"></i>{{ get_phrase('Create Blog') }}</a>
            <a href="{{ route('myblog') }}" class="btn bg-white btn-sm ms-2">{{ get_phrase('My Blog') }}</a>
        </div>
    </div>
    <!-- <div class="card blog-tags p-4">
        <div class="tags">
            @foreach ($categories as $category )
                <a href="{{ route('category.blog',['category_slug'=>$category->category_slug]) }}" class="">{{ $category->category_name }}</a>
            @endforeach 
        </div>
    </div>
     -->
    
    <div class="g-3 blog-cards " >
        <div class="row" id="blogdatashow"> 
            @include('frontend.blogs.blog-single')
        </div>
    </div>
</div> <!-- Page Wrap End -->
