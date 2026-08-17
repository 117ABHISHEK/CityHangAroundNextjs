<div class="row g-4 mt-1">
    @foreach ($groups as $group)
        @php
            $city = DB::table('cities')->where('id', $group->city_id)->first();
            $area = DB::table('areas')->where('city_id', $group->city_id)->where('id', $group->area_id)->first();
            
            $item_categories = DB::table('group_category')->where('group_id', $group->id)->get();
            $item_count = count($item_categories);
            $categoriesss = $item_count > 0 ? DB::table('groupcategories')->where('id', $item_categories[$item_count-1]->category_id)->get() : collect();
            
            $catslug = count($categoriesss) > 0 ? $categoriesss[0]->category_slug : null;
            $catname = count($categoriesss) > 0 ? $categoriesss[0]->category_name : 'General';
            
            $memberCount = DB::table('group_members')->where('group_id', $group->id)->count();
            $discussionRoute = $group->id ? route('single.group.details', $group->id) : '#';
        @endphp

        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="modern-group-card">
                <!-- Cover Image -->
                <div class="card-cover" style="background-image: url('{{ $group->banner ? get_group_cover_photo($group->banner, 'coverphoto') : 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?auto=format&fit=crop&w=600&q=80' }}');">
                    <span class="category-badge">
                        <i class="fas fa-hashtag me-1"></i>{{ $catname }}
                    </span>
                </div>
                
                <!-- Card Content -->
                <div class="card-content">
                    <div class="d-flex align-items-start gap-3">
                        <!-- Group Avatar -->
                        <div class="group-avatar" style="background-image: url('{{ get_group_logo($group->logo, 'logo') }}');"></div>
                        
                        <!-- Title & Meta -->
                        <div class="flex-grow-1 min-w-0">
                            <h3 class="group-title">
                                <a href="{{ $discussionRoute }}" title="{{ $group->title }}">
                                    {{ ellipsis($group->title, 25) }}
                                </a>
                            </h3>
                            <div class="group-meta">
                                <span class="meta-item">
                                    <i class="fas fa-users"></i> {{ $memberCount }} {{ $memberCount === 1 ? 'member' : 'members' }}
                                </span>
                                @if($city)
                                    <span class="meta-item">
                                        <i class="fas fa-map-marker-alt"></i> {{ $city->city_name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <p class="group-description">
                        {{ $group->about ? ellipsis(strip_tags($group->about), 100) : 'Join this community to discuss and share local updates with members.' }}
                    </p>
                </div>

                <!-- Card Footer Actions -->
                <div class="card-actions">
                    <a href="{{ $discussionRoute }}" class="btn btn-view-group w-100">
                        View Discussions <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Pagination -->
    <div class="col-12 mt-4 d-flex justify-content-center">
        {{ $groups->links() }}
    </div>
</div>

<style>
/* Modern premium community group cards styling */
.modern-group-card {
    background: #ffffff;
    border: 1px solid rgba(229, 231, 235, 0.7);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.modern-group-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.08), 0 8px 8px -5px rgba(0, 0, 0, 0.04);
    border-color: rgba(209, 213, 219, 0.9);
}

.card-cover {
    height: 120px;
    background-size: cover;
    background-position: center;
    position: relative;
    background-color: #f3f4f6;
}

.card-cover::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0) 100%);
}

.category-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(4px);
    color: #4b5563;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 9999px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    z-index: 1;
}

.card-content {
    padding: 20px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.group-avatar {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    background-size: cover;
    background-position: center;
    background-color: #ffffff;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border: 2px solid #ffffff;
    margin-top: -38px;
    position: relative;
    z-index: 2;
}

.group-title {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 4px;
    color: #1f2937;
    line-height: 1.4;
}

.group-title a {
    color: #1f2937;
    text-decoration: none;
    transition: color 0.2s ease;
}

.group-title a:hover {
    color: #ef4444; /* Premium Brand Color */
}

.group-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 12px;
}

.meta-item {
    font-size: 0.78rem;
    color: #6b7280;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.meta-item i {
    color: #9ca3af;
}

.group-description {
    font-size: 0.85rem;
    color: #4b5563;
    line-height: 1.5;
    margin-bottom: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.card-actions {
    padding: 0 20px 20px 20px;
}

.btn-view-group {
    background-color: #f9fafb;
    border: 1px solid #e5e7eb;
    color: #374151;
    font-size: 0.85rem;
    font-weight: 600;
    padding: 10px 16px;
    border-radius: 10px;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-view-group:hover {
    background-color: #ef4444; /* Brand red primary */
    border-color: #ef4444;
    color: #ffffff;
}

.btn-view-group i {
    transition: transform 0.2s ease;
}

.btn-view-group:hover i {
    transform: translateX(4px);
}

/* Pagination Custom Styles */
.pagination {
    gap: 4px;
}

.pagination .page-item .page-link {
    border-radius: 8px !important;
    color: #4b5563;
    border-color: #e5e7eb;
    padding: 8px 14px;
    font-weight: 500;
}

.pagination .page-item.active .page-link {
    background-color: #ef4444;
    border-color: #ef4444;
    color: #ffffff;
}

.pagination .page-item .page-link:hover {
    background-color: #f3f4f6;
    color: #ef4444;
}
</style>