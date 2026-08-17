<style>
    .select2-container {
        width: 100% !important;
    }

    .select2-dropdown {
        z-index: 9999 !important;
    }

    .select2-container--default .select2-selection--single {
        height: 50px;
        padding: 0 12px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 16px;
        display: flex;
        align-items: center;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.5;
        color: #999;
    }

    /*.select2-selection__arrow {*/
    /*    height: 30px;*/
    /*    width: 30px;*/
    /*    right: 10px;*/
    /*    top: 50%;*/
    /*    transform: translateY(-50%);*/
    /*}*/

    .select2-dropdown {
        border-radius: 5px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .select2-results__option {
        padding: 10px;
        font-size: 16px;
    }

    .select2-results__option--highlighted {
        background-color: #007bff;
        color: white;
    }

    button {
        background-color: #ff4939;
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-top: 20px;
    }

    button:hover {
        background-color: #9c2d21;
    }

    form {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
        max-width: 600px;
        width: 100%;
    }

    label {
        font-size: 16px;
        margin-bottom: 5px;
        display: block;
    }

    .form-select {
        margin-bottom: 15px;
    }

    .container {
        padding: 20px;
        max-width: 700px;
        margin: auto;
    }

    h2 {
        font-size: 24px;
        margin-bottom: 20px;
        text-align: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 100%;
    width: 34px;
    top: 13;
    right: 0;
    position: absolute;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
}

    /* ✅ RESPONSIVE STYLING */
    @media screen and (max-width: 600px) {
        form {
            padding: 15px;
        }

        label,
        .select2-selection--single {
            font-size: 14px;
        }

        .select2-results__option {
            font-size: 14px;
        }

        button {
            font-size: 14px;
            padding: 8px 16px;
        }

        h2 {
            font-size: 20px;
        }
    }

    @media screen and (max-width: 400px) {
        .select2-selection--single {
            height: 45px;
        }
    }
</style>



<div class="container">
    <h2>Bulk Update Page Categories</h2>

    @if(session('success'))
        <div style="color: green">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="color: red">{{ session('error') }}</div>
    @endif

    <form action="{{ url('/admin/category/bulk-update') }}" method="POST">
        @csrf

        <label for="old_category_id">Old Category:</label>
        <select id="old_category_id" name="old_category_id" class="select2" required>
        </select>

        <label for="new_category_id">New Category:</label>
        <select id="new_category_id" name="new_category_id" class="select2" required>
        </select>

        <button type="submit">Update</button>
    </form>
</div>

<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Then, load Select2 CSS and JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<!-- Your custom script to initialize Select2 -->
<script>
$(document).ready(function() {
    setTimeout(function() {
        function initSelect2(selector) {
            $(selector).select2({
                placeholder: 'Type Category',
                allowClear: true,
                minimumInputLength: 0,
                ajax: {
                    url: '/admin/ajax/page-categories',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.category_name,
                                    id: item.id
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
        }

        if ($.fn.select2) {
            initSelect2('#old_category_id');
            initSelect2('#new_category_id');
        } else {
            console.error('Select2 is not available.');
        }
    }, 500);  // Delay for 500ms (can adjust based on need)
});


</script>
