<div class="marketplace-wrap">
  
    <div
        class="d-flex pagetab-head  border align-items-center justify-content-between mb-3 py-2 px-3 rounded bg-white">
        <h3 class="h5 pt-3"><span><i class="fa-solid fa-calendar-days"></i></span> {{ get_phrase('Marketplace') }}</h3>
        <div class="">
            <!-- <a href="javascript:void(0)" onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.marketplace.create_product'])}}', '{{get_phrase('Create Product')}}');" data-bs-toggle="modal"
                data-bs-target="#createProduct" class="btn btn-primary py-2"> <i class="fa fa-plus-circle"></i> {{get_phrase('Create Product')}}</a> -->
                <a href="{{route('pages.create.product')}}" onclick="" class="btn btn-primary"  class="btn btn-primary"> <i class="fa fa-plus-circle"></i>{{get_phrase('Create Product')}}</a>
        </div>
    </div>
    <!-- Product Listing Start -->
    <div class="product-listing"> 
        <div class="row g-3">
            @foreach ($products as $product)
            <?php
 $pages=DB::table('pages')->select('pages.id','pages.item_slug','cities.city_slug','areas.area_slug')
 ->join('cities','cities.id','=','pages.city_id')
 ->join('areas','areas.id','=','pages.area_id')
 ->join('page_category','page_category.page_id','pages.id')
 ->where('page_id', $product->page_id)
 ->distinct()
 ->get();

 //print_r($city);exit;
 $item_categories = DB::table('page_category')
 ->where('page_id', $product->page_id)
 ->get();

 
 $item_count=count($item_categories);
 $categoriesss = DB::table('pagecategories')
     ->where('id', $item_categories[$item_count-1]->category_id)
     ->get();
                         
 $catslug = !is_null($categoriesss) ? $categoriesss[0]->category_slug:null; 

 //Product Category


 $product_categories = DB::table('category_product')
 ->where('product_id', $product->id)
 ->get();

 
 $product_count=count($product_categories);
 $productcategoriesss = DB::table('categories')
     ->where('id', $product_categories[$product_count-1]->product_category_id)
     ->get();
                         
 $productcatslug = !is_null($productcategoriesss) ? $productcategoriesss[0]->product_category_slug:null; 
 ?>
                <div class="col-6 col-md-4 col-lg-6 col-xl-4" id="product-{{ $product->id }}">
                    <div class="card product p-3">
                        <div class="product-figure position-relative">
                            <a href="{{ route('single.product',['city_slug'=>$pages[0]->city_slug,'area_slug'=>$pages[0]->area_slug,'category_slug'=>$catslug,'item_slug'=>$pages[0]->item_slug,'product_category_slug'=>$productcatslug,'product_slug'=>$product->product_slug]) }}"><img src="{{ get_product_image($product->image,"thumbnail") }}" alt="" class="img-fluid"></a>
                            
                        </div>
                        <h3 class="h6"><a href="{{ route('single.product',['city_slug'=>$pages[0]->city_slug,'area_slug'=>$pages[0]->area_slug,'category_slug'=>$catslug,'item_slug'=>$pages[0]->item_slug,'product_category_slug'=>$productcatslug,'product_slug'=>$product->product_slug]) }}"> {{ ellipsis($product->title, 30) }} </a></h3>
                        <span class="location">{{ $product->location }}</span>
                        <div class="prodoct-footer">
                        @php
                        if(is_null($product->product_selling_price) || empty($product->product_selling_price)){
                            $product_selling_price=0;
                        }
                        else{
                            $product_selling_price=$product->product_selling_price;
                        }
                    
                        @endphp
                            <a href="{{ route('single.product',['city_slug'=>$pages[0]->city_slug,'area_slug'=>$pages[0]->area_slug,'category_slug'=>$catslug,'item_slug'=>$pages[0]->item_slug,'product_category_slug'=>$productcatslug,'product_slug'=>$product->product_slug]) }}" class="btn btn-primary">{{ $product->getCurrency->symbol }} {{ $product_selling_price }}</a>
                            <!-- <a href="javascript:void(0)"  onclick="showCustomModal('{{route('load_modal_content', ['view_path' => 'frontend.marketplace.edit_product', 'product_id' => $product->id] )}}', '{{get_phrase('Edit Product')}}');" class="" data-bs-toggle="modal"
                                data-bs-target="#editEvent"><i class="fa fa-edit"></i></button> -->

                                <a href="{{route('product.edit',['product_id'=>$product->id])}}" onclick="" class="" 
                                data-bs-target="#editEvent"><i class="fa fa-edit"></i></button>
                                <!-- <a href="{{route('product.edit',['product_id'=>$product->id])}}" onclick="" class="btn btn-primary"  class="btn btn-primary"> <i class="fa fa-plus-circle"></i>{{get_phrase('Create Product')}}</a> -->
                            <a href="javascript:void(0)" onclick="confirmAction('<?php echo route('product.delete', ['product_id' => $product->id]); ?>', true)" class=""><i class="fa fa-trash-can me-1"></i> </a>
                        </div>
                    </div>
                </div><!--  Single Product End -->
            @endforeach
        </div>
    </div>
</div>