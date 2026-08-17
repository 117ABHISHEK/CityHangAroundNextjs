<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.js" defer></script>
<div class="row gx-3">
    <div class="col-lg-12">
        <div class="group-inner">

            <div class="page-suggest mt-4">
                <h1 class="h1">Categories</h1>

          <div class="pill-container">

    <button id="scrollLeftBtn"  class="scroll-btn left" onclick="scrollPills(-200)">
        &lt;
    </button>

    <div class="pill-scroll" id="pillScroll">

                      @foreach ($groupCategories as $category)
            <div>
                <a href="{{ route('category.group', $category->category_slug) }}"
                   class="btn  pill
                   {{ request()->route('category_slug') == $category->category_slug
                        ?     'pill-active'
                        : 'btn-outline-secondary' }}">
                    {{ $category->category_name }}
                </a>
            </div>
        @endforeach
</div>
<button class="scroll-btn right" id="scrollRightBtn" onclick="scrollPills(200)">
        &gt;
    </button>

</div>
<!-- </div> -->

                {{-- Open groups ONLY when category clicked --}}
                @if (request()->route('category_slug'))
                    <div class="mt-5">
                        @include('frontend.groups.custom_single_group')
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
<style>
.pill-container {
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Scroll area */
.pill-scroll {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    scroll-behavior: smooth;
    white-space: nowrap;
    padding: 6px 0;
    flex: 1;
}

.pill-scroll::-webkit-scrollbar {
    display: none;
}

/* Pills */
.pill {
    padding: 8px 18px;
    border-radius: 10px;
    background: #f1f2f3;
    color: #111;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    border: 1px solid #ddd;
    white-space: nowrap;
    transition: all .2s ease;
}

.pill:hover {
    background: #e4e6e7;
}

.pill.active {
    background: #c42121ff;
    color: #fff;
    border-color: #b11414ff;
}

/* Arrow buttons */
.scroll-btn {
    border: none;
    background: #fff;
    font-size: 18px;
    font-weight: bold;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    box-shadow: 0 0 5px rgba(0,0,0,.15);
    cursor: pointer;
}

.scroll-btn:hover {
    background: #f1f1f1;
}


</style>


<script>
document.addEventListener("DOMContentLoaded", function () {

    const container = document.getElementById("pillScroll");

    function scrollByItems(count) {
        const pill = container.querySelector(".pill");
        if (!pill) return;

        const pillWidth = pill.offsetWidth + 10; // 10 = gap
        container.scrollBy({
            left: pillWidth * count,
            behavior: "smooth"
        });
    }

    document.getElementById("scrollLeftBtn").addEventListener("click", function () {
        scrollByItems(-3); // ⬅️ scroll 3 pills
    });

    document.getElementById("scrollRightBtn").addEventListener("click", function () {
        scrollByItems(3); // ➡️ scroll 3 pills
    });

});
</script>


