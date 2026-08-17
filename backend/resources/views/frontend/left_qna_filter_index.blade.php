<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropdown with Select2</title>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" media="print" onload="this.media='all'" />

    <!-- Include Select2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    </noscript>
</head>
<style>
 .left_size_custom_section .form-control{
  background-color: unset !important;
  border: unset !important;
  padding-left: 0 !important;
  padding-right: 0 !important;
}
#mobileSidebar {
    z-index: 9999; /* Ensure the sidebar appears on top of all elements */
    opacity: 1; /* Ensure no opacity issue */
     max-height: 100vh; /* Ensure sidebar does not exceed viewport height */
    overflow-y:auto; /* Allow scrolling if content overflows */
    padding-bottom: 4rem;
}

#overlay {
    z-index: 9998; /* Just below the sidebar */
    opacity: 0.5; /* Adjust opacity to dim the background */
}
.widget_top_filter{
    background: #fff;
  padding: 9px;
}
.owl-item {
  margin-bottom: 8px;
}
.select2 {width: 100% !important;}

.category-title{
    margin-top: 10px;
  font-size: 18px;
  font-weight: bold;
}

.category-list {
    list-style: none;
    margin: 0;
    padding: 0;
}

.category-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px;
    font-size: 14px;
}

.hidden-category {
    display: none; /* hide extra categories by default */
}

.show-more {
    display: inline-block;
    margin-top: 6px;
    font-size: 14px;
    color: #e60050;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
}

.show-more:hover {
    text-decoration: underline;
}
    </style>
</head>
<body>


<!-- <div class="row rightSideBarToggler d-hidden">
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-body text-end">
                <button class="btn" onclick="toggleRightSideBar()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
</div> -->
<button id="burgerBtn" onclick="var m=document.getElementById('mobileSidebar'); var o=document.getElementById('overlay'); if(m){m.classList.remove('-translate-x-full'); m.classList.add('active');} if(o){o.classList.remove('hidden'); o.style.display='block';} document.body.style.overflow='hidden';" class="lg:hidden p-2 border rounded mb-2">
    <i class="fas fa-bars"></i> Filters
</button>   

<div class="widget_top_filter hidden  lg:block"> 
    <div class="col-12 "> 
      
        
        <strong>Total Results Found: 0</strong>
       
    </div>
    
     <form method="GET" action="{{ route('pages') }}" id="filterForm">
            <div class="form-row left_size_custom_section">
                <div class="form-control col-md-12">
                <select id="category_filter" name="city" style="width: 100%;" class="  selectpicker form-control 2">
                    <option value="">Category</option>
                   @foreach($all_categories ?? [] as $category)
                                    @if($category->category_parent_id == 0 || $category->category_parent_id == null)
                                        <option value="{{ $category->id }} {{ request('category_filter') == $category->id ? 'selected' : '' }}">
                                            {{ $category->product_category_name }}
                                        </option>
                                    @endif
                                @endforeach
                </select>
            </div>

             <div class="form-control col-md-12">
                <select id="category_filter" name="city" style="width: 100%;" class="  selectpicker form-control 2">
                    <option value="">location</option>
                   @foreach($all_categories ?? [] as $category)
                                    @if($category->category_parent_id == 0 || $category->category_parent_id == null)
                                        <option value="{{ $category->id }} {{ request('category_filter') == $category->id ? 'selected' : '' }}">
                                            {{ $category->product_category_name }}
                                        </option>
                                    @endif
                                @endforeach
                </select>
            </div>

  
             


      

        <!-- Sort By -->
         <div class="form-control col-md-12">
                <select id="filter_sort_by" name="filter_sort_by" class="selectpicker form-control">
                     <option value="newest" {{ request('filter_sort_by') == "newest" ? 'selected' : '' }}>Newest</option>
                    <option value="oldest" {{ request('filter_sort_by') == "oldest" ? 'selected' : '' }}>Oldest</option>
                    <option value="highest-rated"  {{ request('filter_sort_by') == "highest-rated" ? 'selected' : '' }}>highest-rated</option>

                    <option value="lowest-rated" {{ request('filter_sort_by') == "lowest-rated" ? 'selected' : '' }}>lowest-rated</option>
                </select>
            </div>
       
        <!-- Buttons -->

        <div class="form-control col-md-12">
                <button type="submit" class="btn btn-primary" style="width: 49%;">Submit</button>
                <a class="reset-btn btn btn-outline-primary rounded" style="width: 48%;" href="{{ route('allproducts') }}">
                    Reset
                </a>
            </div>
          
        </div>
    </form>
   


</div>

<!-- <div id="mobileSidebar" class="fixed top-0 left-0 h-full w-72 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 z-50 overflow-y-auto lg:hidden"> -->

  <!-- Sidebar Header -->
  <!-- <div class="flex justify-between items-center p-4 border-b">
    <h3 class="text-lg font-semibold">Filters</h3>
    <button id="closeFilterSidebar" onclick="var m=document.getElementById('mobileSidebar'); var o=document.getElementById('overlay'); if(m){m.classList.add('-translate-x-full'); m.classList.remove('active');} if(o){o.classList.add('hidden'); o.style.display='none';} document.body.style.overflow='';" class="text-gray-600 text-xl">
      &times;
    </button>
  </div> -->

  <!-- Sidebar Content (your existing filter form) -->
  <!-- <div class="p-4">
    <div class="widget_top_filter">
      <div class="col-12 mb-3">
        
      </div>
      
      <form method="GET" action="{{ route('pages') }}" id="filterForm">
            <div class="form-row left_size_custom_section">
                <div class="form-control col-md-12">
                <select id="category_filter" name="city" style="width: 100%;" class="  selectpicker form-control 2">
                    <option value="">Category</option>
                   @foreach($all_categories ?? [] as $category)
                                    @if($category->category_parent_id == 0 || $category->category_parent_id == null)
                                        <option value="{{ $category->id }} {{ request('category_filter') == $category->id ? 'selected' : '' }}">
                                            {{ $category->product_category_name }} -->
                                        <!-- </option>
                                    @endif
                                @endforeach
                </select>
            </div>

             <div class="form-control col-md-12">
                <select id="category_filter" name="city" style="width: 100%;" class="  selectpicker form-control 2">
                    <option value="">location</option> -->
                   <!-- @foreach($all_categories ?? [] as $category)
                                    @if($category->category_parent_id == 0 || $category->category_parent_id == null)
                                        <option value="{{ $category->id }} {{ request('category_filter') == $category->id ? 'selected' : '' }}">
                                            {{ $category->product_category_name }}
                                        </option>
                                    @endif
                                @endforeach
                </select>
            </div>

   -->


             <!-- <div class="form-control col-md-12">
                <select id="filter_sort_by" name="filter_sort_by" class="selectpicker form-control">
                     <option value="newest" {{ request('filter_sort_by') == "newest" ? 'selected' : '' }}>Newest</option>
                    <option value="oldest" {{ request('filter_sort_by') == "oldest" ? 'selected' : '' }}>Oldest</option>
                    <option value="highest-rated"  {{ request('filter_sort_by') == "highest-rated" ? 'selected' : '' }}>highest-rated</option>

                    <option value="lowest-rated" {{ request('filter_sort_by') == "lowest-rated" ? 'selected' : '' }}>lowest-rated</option>
                </select>
            </div>

             <div class="form-control col-md-12">
                <button type="submit" class="btn btn-primary" style="width: 49%;">Submit</button>
                <a class="reset-btn btn btn-outline-primary rounded" style="width: 48%;" href="{{ route('allproducts') }}">
                    Reset
                </a>
            </div>
          
        </div>
    </form>

   

</div>
</div> -->


<div id="overlay" onclick="var m=document.getElementById('mobileSidebar'); if(m){m.classList.add('-translate-x-full'); m.classList.remove('active');} this.classList.add('hidden'); this.style.display='none'; document.body.style.overflow='';" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40 lg:hidden"></div>
<script>
// Sidebar Toggles using robust event delegation and direct binding
        const $msidebar = $('#mobileSidebar');
        const $overlay = $('#overlay');
        
        function openSidebar() {
            $msidebar.addClass('active').removeClass('-translate-x-full');
            $overlay.addClass('active').removeClass('hidden').show();
            $('body').css('overflow', 'hidden');
        }
        
        function closeSidebar() {
            $msidebar.removeClass('active').addClass('-translate-x-full');
            $overlay.removeClass('active').addClass('hidden').hide();
            $('body').css('overflow', '');
        }

        // Direct bindings
        $('#burgerBtn').off('click').on('click', openSidebar);
        $('#closeFilterSidebar, #closeSidebar, #overlay').off('click').on('click', closeSidebar);

        // Delegated bindings as backup
        $(document).off('click', '#burgerBtn').on('click', '#burgerBtn', openSidebar);
        $(document).off('click', '#closeFilterSidebar, #closeSidebar, #overlay').on('click', '#closeFilterSidebar, #closeSidebar, #overlay', closeSidebar);

  // Click overlay to close
  overlay.addEventListener("click", () => {
      msidebar.classList.add("-translate-x-full");
      overlay.classList.add("hidden");
  });
</script>

    
    
    







<script>
    $(document).ready(function() {


        $(document).on('click', '.show-more', function () {
    $('.hidden-category').slideDown(); // show all hidden
    $(this).hide(); // hide the "View More" button
});
        // Initialize Select2 on the dropdowns
        $(' #filter_sort_by').select2({
            // placeholder: function() {
            //     return $(this).data('placeholder');
            // }
        });


         $('.tag-carousel').owlCarousel({
        margin: 10,
        nav: true,
        dots: false,
        autoWidth: true,
        loop: false,
        responsive: {
            0: { items: 1 },
            600: { items: 3 },
            1000: { items: 4 }
        }
    });

       
        // Sorting functionality
        $('#filter_sort_by').on('change', function() {
            const sortType = $(this).val();
            let sortedItems;

            if (sortType === "newest") {
                sortedItems = items.sort((a, b) => new Date(b.added) - new Date(a.added));
            } else if (sortType === "oldest") {
                sortedItems = items.sort((a, b) => new Date(a.added) - new Date(b.added));
            } else if (sortType === "highest-rated") {
                // Example sort for highest rated (you can add ratings data)
                sortedItems = items.sort((a, b) => b.id - a.id);
            } else if (sortType === "lowest-rated") {
                // Example sort for lowest rated (you can add ratings data)
                sortedItems = items.sort((a, b) => a.id - b.id);
            }

         
        
    });
});




  

</script>
</body>
</html>
