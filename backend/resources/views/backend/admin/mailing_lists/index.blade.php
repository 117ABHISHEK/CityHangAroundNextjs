<style>
.table-responsive {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}
.table1 {
  min-width: 760px; 
}
.row-number {
  color:black;
}
.table1 th, .table1 td {
 border: none !important;
}
.table1 thead tr {
  border-bottom: 2px solid black !important;
}
.table1 thead th {
  font-weight: 600;
  padding: 0.75rem 0.75rem;
}
.btn-logo{
    background-color: #ff4939;
}
</style>
<form method="GET" action="{{ route('admin.mailing_lists.index') }}">
    <div class="container my-4">
        <div class="row mb-4">

            {{-- City Dropdown --}}
            <div class="col-md-4">
                <label for="city_id">City</label>
                <select name="city_id[]" id="city_id" multiple
                    class="form-control eForm-control select2 @error('city_id') is-invalid @enderror">
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" {{ collect(request('city_id'))->contains($city->id) ? 'selected' : '' }}>
                            {{ $city->city_name }}
                        </option>
                    @endforeach
                </select>
                @error('city_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Area Dropdown --}}
            <div class="col-md-4">
                <label for="area_id">Area</label>
                <select name="area_id[]" id="area_id" multiple
                    class="form-control eForm-control select2 @error('area_id') is-invalid @enderror">
                    @foreach($areas as $area)
                        <option value="{{ $area->id }}" {{ collect(request('area_id'))->contains($area->id) ? 'selected' : '' }}>
                            {{ $area->area_name }}
                        </option>
                    @endforeach
                </select>
                @error('area_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Category Dropdown --}}
            <div class="col-md-4">
                <label for="category_id">Category</label>
                <select name="category_id[]" id="category_id" multiple
                    class="form-control eForm-control select2 @error('category_id') is-invalid @enderror">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ collect(request('category_id'))->contains($category->id) ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary" hidden>Apply Filters</button>
    </div>
</form>


@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.mailing_lists.bulk_action') }}" method="POST">
    @csrf
    <div class="container my-4">
       <div class="row mb-4 align-items-end g-3">
          <div class="col-12 col-md-6">
            <h3 class="text-dark">Manage Mailing Lists</h3>
            <p class="text-muted">Select pages and choose an action below</p>
          </div>
        
          <div class="col-12 col-sm-6 col-md-3">
            <label for="action" class="h6">Select Action</label>
            <select name="action" id="action" class="form-control custom-select" required>
              <option value="">Select</option>
              <option value="create">Create New Mailing List</option>
              <option value="edit">Edit Existing Mailing List</option>
              <option value="transfer">Transfer to Another List</option>
              <option value="delete">Delete Mailing List</option>
            </select>
          </div>
        
          <div class="col-12 col-sm-6 col-md-3">
            <label for="tags" class="h6">Listing Status</label>
            <select name="tags" id="tags" class="form-control custom-select">
              <option value="">Select</option>
              <option value="incomplete">Incomplete Listing</option>
              <option value="banner_missing">Banner Image Missing</option>
              <option value="logo_missing">Logo Missing</option>
              <option value="no_product">No Product/Service Added</option>
            </select>
          </div>
        </div>
        <!-- Add other form elements here -->
    </div>



        <!-- Create Section -->
        <div id="create_div" class="form-group mb-4" style="display: none;">
            <label for="new_list_name" class="h6">New Mailing List Name</label>
            <input type="text" name="new_list_name" id="new_list_name" class="form-control" placeholder="Enter new list name" />
        </div>

        <!-- Edit Section -->
        <div id="edit_div" class="form-group mb-4" style="display: none;">
            <label for="existing_list" class="h6">Select Mailing List to Edit</label>
            <select name="existing_list" id="existing_list" class="form-control custom-select">
                <option value="">Select</option>
                @foreach($availableLists as $list)
                    <option value="{{ $list->id }}">{{ $list->name }}</option>
                @endforeach
            </select>

            <label for="updated_name" class="h6 mt-3">Update Mailing List Name</label>
            <input type="text" name="updated_name" id="updated_name" class="form-control" placeholder="Enter updated list name" />
        </div>

        <!-- Transfer Section -->
        <div id="transfer_div" class="form-group mb-4" style="display: none;">
            <label for="source_list" class="h6">Select Mailing List to Transfer From</label>
            <select name="source_list" id="source_list" class="form-control custom-select">
                <option value="">Select</option>
                @foreach($availableLists as $list)
                    <option value="{{ $list->id }}">{{ $list->name }}</option>
                @endforeach
            </select>
        </div>

        <div id="transfer_to_div" class="form-group mb-4" style="display: none;">
            <label for="transfer_list" class="h6">Select Mailing List to Transfer To</label>
            <select name="transfer_list" id="transfer_list" class="form-control custom-select">
                <option value="">Select</option>
                @foreach($availableLists as $list)
                    <option value="{{ $list->id }}">{{ $list->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Delete Section -->
        <div id="delete_div" class="form-group mb-4" style="display: none;">
            <label for="delete_list" class="h6">Select Mailing List to Delete Pages From</label>
            <select name="delete_list" id="delete_list" class="form-control custom-select">
                <option value="">Select</option>
                @foreach($availableLists as $list)
                    <option value="{{ $list->id }}">{{ $list->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Pages Table -->
        <div class="table-responsive mb-4">
    <table class="table table1 table-hover">
        <thead>
            <tr>
                <th><input type="checkbox" id="select_all_header" name="pages" /></th>
               
                <th>Page Name</th>
                <th>Location</th>
                <th>User</th>
                <th>Categories</th>
            </tr>
        </thead>
        <tbody id="pages_table_body">
            @forelse($pages as $page)
                <tr class="page_row" data-page-id="{{ $page->id }}">
                    <td>
                        <input type="checkbox"  value="{{ $page->id }}" class="page-checkbox"
                            @if(isset($page->selected) && $page->selected) checked @endif />
                    </td>
                    <td>{{ $page->title }}</td>
                    <td> {{ optional($page->city)->city_name ?? 'N/A' }},
                         {{ optional($page->area)->area_name ?? 'N/A' }}</td>
                    <td></br>
                    {{ $page->item_email }}
                     </td>
                    <td>
                    {{$page->categories->pluck('category_name')->implode(', ')}}
                    </td>
                </tr>
            @empty
                <tr><td colspan="3">No pages found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination_links">
    {!! $pages->links() !!}

    </div>

     <div id="selectedPagesContainer">
        <input hidden name="pages_json" id="pages_json" value="">
    </div>

   <div class="text-end d-flex justify-content-center">
  <button type="submit" class="btn btn-logo text-white  w-25 w-md-auto mb-4">Submit</button>
</div>


        
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let selectAllMode = false;
let selectedPages = new Set();
let currentPage = 1;
let totalPages = 0;

// DOM references
const actionSelect = document.getElementById('action');
const actiontags = document.getElementById('tags');
const createDiv = document.getElementById('create_div');
const editDiv = document.getElementById('edit_div');
const transferDiv = document.getElementById('transfer_div');
const transferToDiv = document.getElementById('transfer_to_div');
const deleteDiv = document.getElementById('delete_div');
const selectAllHeader = document.getElementById('select_all_header');



hideAllSections();

// Utility
function hideAllSections() {
    createDiv.style.display = 'none';
    editDiv.style.display = 'none';
    transferDiv.style.display = 'none';
    transferToDiv.style.display = 'none';
    deleteDiv.style.display = 'none';
}

function updateSelectAllCheckboxState() {
    
    const checkboxes = document.querySelectorAll('.page-checkbox');
    const checked = document.querySelectorAll('.page-checkbox:checked');
    selectAllHeader.checked = checkboxes.length > 0 && checked.length === checkboxes.length;
}

function resetTable(message = 'Select an action or mailing list') {
    selectedPages.clear();
    document.getElementById('pages_table_body').innerHTML = `<tr><td colspan="6">${message}</td></tr>`;
    document.querySelector('.pagination_links').innerHTML = '';
}

// Handle action change
function handleActionChange(actionValue) {
    resetTable();
    hideAllSections();
   

    switch (actionValue) {
        case 'create':
            createDiv.style.display = 'block';
            fetchPages('create', currentPage);
            break;
        case 'edit':
            editDiv.style.display = 'block';
            const selectedEditOption = document.querySelector('#existing_list option:checked');
            if (selectedEditOption) {
                document.getElementById('updated_name').value = selectedEditOption.textContent.trim();
                fetchPages('edit', currentPage);
            }
            break;
        case 'transfer':
            transferDiv.style.display = 'block';
            transferToDiv.style.display = 'block';
            break;
        case 'delete':
            deleteDiv.style.display = 'block';
            break;
    }
}

actionSelect.addEventListener('change', function () {
    handleActionChange(this.value);
});



// Fetch pages
function fetchPages(actionType, page = 1) {
    currentPage = page;
    let listId = null;

    if (actionType !== 'create') {
        if (actionType === 'edit') listId = document.getElementById('existing_list').value;
        if (actionType === 'transfer') listId = document.getElementById('source_list').value;
        if (actionType === 'delete') listId = document.getElementById('delete_list').value;

        if (!listId) return resetTable('Select a mailing list first');
    }

    const params = new URLSearchParams();
    params.append('page', page);
    ['city_id', 'area_id', 'category_id','tags'].forEach(id => {
    const element = document.getElementById(id);
    if (element) {
        const selectedValues = Array.from(element.selectedOptions || []).map(opt => opt.value).filter(v => v);
        if (selectedValues.length > 0) {
            params.append(id, selectedValues.join(','));
        }
    }
});



    let url = actionType === 'create'
        ? `/admin/mailing-lists/page-all?${params.toString()}`
        : `/admin/mailing-lists/pages-${listId}?action=${actionType}&${params.toString()}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            const tableBody = document.getElementById('pages_table_body');
            const paginationDiv = document.querySelector('.pagination_links');
            tableBody.innerHTML = '';
            paginationDiv.innerHTML = '';
            totalPages = data.meta.last_page;
            if (data.pages && data.pages.length > 0) {
                totalPages = data.totalPages;
                const sortedPages = [...data.pages.filter(p => p.selected), ...data.pages.filter(p => !p.selected)];

                sortedPages.forEach(page => {
                    const row = document.createElement('tr');
                    row.classList.add('page_row');
                    row.setAttribute('data-page-id', page.id);

                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.value = page.id;
                    checkbox.classList.add('page-checkbox');
                    // Ensure the checkbox reflects the global selectedPages state
                    if (selectAllMode || selectedPages.has(page.id.toString()) || page.selected) {
                        checkbox.checked = true;
                    }

                    if (selectAllMode || checkbox.checked) {
                        selectedPages.add(page.id.toString());
                    }

                    checkbox.addEventListener('change', function () {
                        this.checked ? selectedPages.add(this.value) : selectedPages.delete(this.value);
                        updateSelectAllCheckboxState();
                    });

                    row.innerHTML = `
                        <td></td>
                        <td>${page.title}</td>
                        <td>${page.city_name}, ${page.area_name ?? ''}</td>
                        <td>${page.item_email}</td>
                        <td>${page.categories.join(', ')}</td>
                    `;
                    row.children[0].appendChild(checkbox);
                    tableBody.appendChild(row);
                });

                paginationDiv.innerHTML = data.pagination;
                bindPaginationLinks(actionType);
            } else {
                resetTable('No pages found');
            }

            updateSelectAllCheckboxState();
        })
        .catch(error => {
            console.error('Error fetching pages:', error);
        });
}


// Bind pagination links
function bindPaginationLinks(actionType) {
    document.querySelectorAll('.pagination a').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const url = new URL(link.href);
            const page = url.searchParams.get('page');
            fetchPages(actionType, page);
        });
    });

    const fetchPromises = [];
    if (actionType !== 'create') {
    for (let page = 1; page <= totalPages; page++) {
        const params = new URLSearchParams();
        params.set('page', page);

        ['city_id', 'area_id', 'category_id','tags'].forEach(id => {
            const val = document.getElementById(id)?.value;
            if (val) params.set(id, val);
        });

        const actionSelect = document.getElementById('action');
        const actionType = actionSelect.value;

        let listId = null;

        if (actionType !== 'create') {
            if (actionType === 'edit') listId = document.getElementById('existing_list').value;
            if (actionType === 'transfer') listId = document.getElementById('source_list').value;
            if (actionType === 'delete') listId = document.getElementById('delete_list').value;

            if (!listId) return resetTable('Select a mailing list first');
        }

       

        let url;
        if (actionType === 'create') {
            url = `/admin/mailing-lists/page-all?${params.toString()}`;
        } else {
            url = `/admin/mailing-lists/pages-${listId}?action=${actionType}&${params.toString()}`;
        }

        fetchPromises.push(
            fetch(url)
                .then(res => res.json())
                .then(data => {
                    if (data.pages) {
                        data.pages.forEach(p => selectedPages.add(p.id.toString()));
                        if (page === currentPage) {
                            
                        }
                    }
                })
                .catch(err => console.error(`Failed to fetch page ${page}`, err))
        );
    }

    // Wait for all fetches to complete
    Promise.all(fetchPromises).then(() => {
        updateSelectAllCheckboxState();
        console.log('All pages selected:', selectedPages.size);
    });
}
}


let loadingPages = false;
// Checkbox logic
document.getElementById('select_all_header').addEventListener('click', function () {
    const isChecked = this.checked;
    selectAllMode = isChecked;
    selectedPages.clear();

    if (!isChecked) {
        document.querySelectorAll('.page-checkbox').forEach(cb => cb.checked = false);
        updateSelectAllCheckboxState();
        return;
    }

    const fetchPromises = [];

    for (let page = 1; page <= totalPages; page++) {
        const params = new URLSearchParams();
        params.set('page', page);

        ['city_id', 'area_id', 'category_id','tags'].forEach(id => {
            const val = document.getElementById(id)?.value;
            if (val) params.set(id, val);
        });

        const actionSelect = document.getElementById('action');
        const actionType = actionSelect.value;

        let listId = null;

        if (actionType !== 'create') {
            if (actionType === 'edit') listId = document.getElementById('existing_list').value;
            if (actionType === 'transfer') listId = document.getElementById('source_list').value;
            if (actionType === 'delete') listId = document.getElementById('delete_list').value;

            if (!listId) return resetTable('Select a mailing list first');
        }

       

        let url;
        if (actionType === 'create') {
            url = `/admin/mailing-lists/page-all?${params.toString()}`;
        } else {
            url = `/admin/mailing-lists/pages-${listId}?action=${actionType}&${params.toString()}`;
        }

        fetchPromises.push(
            fetch(url)
                .then(res => res.json())
                .then(data => {
                    if (data.pages) {
                        data.pages.forEach(p => selectedPages.add(p.id.toString()));
                        if (page === currentPage) {
                            document.querySelectorAll('.page-checkbox').forEach(cb => cb.checked = true);
                        }
                    }
                })
                .catch(err => console.error(`Failed to fetch page ${page}`, err))
        );
    }
loadingPages = true;

    Promise.all(fetchPromises).then(() => {
        updateSelectAllCheckboxState();
        loadingPages = false;
        console.log('All pages selected:', selectedPages.size);
    });
    // Wait for all fetches to complete
    // Promise.all(fetchPromises).then(() => {
    //     updateSelectAllCheckboxState();
    //     console.log('All pages selected:', selectedPages.size);
    // });
});




document.addEventListener('DOMContentLoaded', function () {
  
    // Initialize fetchPages for the first load with default filters, if needed
    //fetchPages('create'); // Or you can use an action you want as default when the page loads.

    // Form submit: append hidden inputs when the form is submitted
            document.querySelector('form[action="{{ route('admin.mailing_lists.bulk_action') }}"]').addEventListener('submit', function (e) {
            e.preventDefault(); // 🛑 Prevent default form submission
//alert( JSON.stringify(Array.from(selectedPages)));
            if (loadingPages) {
                alert('Please wait until all pages are selected...');
                return;
            }
            const container = document.getElementById('selectedPagesContainer');
            //container.innerHTML = '';

            // const input = document.createElement('input');
            // input.type = 'hidden';
            // input.name = 'pages_json';
            // input.value = JSON.stringify(Array.from(selectedPages)); // Convert Set to Array
            const input = document.getElementById('pages_json');
        if (!input) {
            alert('Hidden input pages_json not found!');
            return;
        }

        input.value = JSON.stringify(Array.from(selectedPages));
            //container.appendChild(input);


            // ✅ Now safely submit the form
            this.submit();
        });

});

// Existing list change (edit)
document.getElementById('existing_list')?.addEventListener('change', function () {
    selectedPages.clear();
    const name = this.options[this.selectedIndex].textContent.trim();
    document.getElementById('updated_name').value = name;
    fetchPages('edit', currentPage);
});

// Source list change (transfer)
document.getElementById('source_list')?.addEventListener('change', function () {
    selectedPages.clear();
    fetchPages('transfer', currentPage);
});

// Delete list change
document.getElementById('delete_list')?.addEventListener('change', function () {
    selectedPages.clear();
    fetchPages('delete', currentPage);
});

actiontags.addEventListener('change', function () {
    selectedPages.clear();
    fetchPages('create');

     handleActionChange(actionSelect.value);
});

// City change – load areas
$('#city_id').on('change', function () {
    selectedPages.clear();
    fetchPages('create');
    const cityId = this.value;
    

    if (cityId > 0) {
        $.ajax({
            url: '/ajax/areas/' + cityId,
            method: 'get',
            success: function (result) {
                const areas = JSON.parse(result);
                areas.forEach(area => {
                    $('#area_id').append(`<option value="${area.id}">${area.area_name}</option>`);
                });
            }
        });
    }

    handleActionChange(actionSelect.value);
});

// Area/category change
$('#area_id, #category_id').on('change', function () {
    selectedPages.clear();
    fetchPages('create')
    handleActionChange(actionSelect.value);
});
</script>


