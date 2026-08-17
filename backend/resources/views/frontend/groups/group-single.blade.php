 @foreach ($groups as $key => $group)
        <?php

        //print_r($group);exit;

        $city= DB::table('cities')
        ->where('id', $group->city_id)
        ->first();

        $area= DB::table('areas')
        ->where('city_id', $group->city_id)
        ->where('id', $group->area_id)
        ->first();

        // Skip this group if city or area is not found
        if (!$city || !$area) {
            continue;
        }

        $item_categories = DB::table('group_category')
        ->where('group_id',$group->id)
        ->get();


        $item_count=count($item_categories);
        $categoriesss = DB::table('groupcategories')
            ->where('id', $item_categories[$item_count-1]->category_id)
            ->get();
                                
        $catslug = !is_null($categoriesss) ? $categoriesss[0]->category_slug:null; 
        ?>
        <div class="col-md-4 col-lg-4 col-sm-6 single-item-countable" id="group-{{ $group->id }}">
            <div class="card p-2 rounded">
                <div class="mb-2"> <img class="img-fluid img-radisu" src="{{ get_group_logo($group->logo,'logo') }}" ></div>
                <a href="{{ route('single.group',['city_slug'=>$city->city_slug,'area_slug'=>$area->area_slug,'category_slug'=>$catslug,'group_slug'=>$group->group_slug]) }}"><h4>{{ ellipsis($group->title,20) }}</h4></a>
                @php $joined = \App\Models\Group_member::where('group_id',$group->id)->where('is_accepted','1')->count(); @endphp
                
                <span class="small text-muted">{{ get_phrase("{$joined} Members") }}</span>
                @php $join = \App\Models\Group_member::where('group_id',$group->id)->where('user_id',auth()->user()->id)->count(); @endphp
                @if ($join>0)
                <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('group.rjoin',$group->id); ?>')" class="btn btn-primary">{{ get_phrase('Joined')}}</a>
                @else
                    <a href="javascript:void(0)" onclick="ajaxAction('<?php echo route('group.join',$group->id); ?>')" class="btn btn-primary">{{ get_phrase('Join')}}</a>
                @endif
            </div>
        </div>
        @if (isset($search)&&!empty($search))
            @if ($key==2)
                @break
            @endif
        @endif
@endforeach     
