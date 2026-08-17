<div class="page-content">
  <div class="page-tab bg-white border rounded p-3 pb-1">
    <div class="edit-profile__picture">
      <h5 class="pm-title">{{ get_phrase('Profile Picture') }}</h5>
      <div class="profile-pic mx-auto">
        <img class="uploaded_place_here img-fluid rounded-circle" width="100%" src="{{ get_user_image(Auth()->user()->photo, 'optimized') }}">
      </div>
    </div>

    <form action="{{ route('profile.update_profile') }}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="mb-3 mt-4 text-center">
        <input type="file" id="profile_photo" class="form-control w-50 ms-auto me-auto" name="profile_photo" accept="image/*">
      </div>

      <div class="row mb-3">
        <div class="col">
          <label for="name">{{ get_phrase('Name') }}</label>
          <input value="{{ auth()->user()->name }}" id="name" name="name" type="text" class="form-control" placeholder="{{ get_phrase('Enter your name') }}" required>
        </div>
        <div class="col">
          <label for="nickname">{{ get_phrase('Nickname') }}</label>
          <input value="{{ auth()->user()->nickname }}" id="nickname" name="nickname" type="text" class="form-control" placeholder="{{ get_phrase('Enter your nickname') }}">
        </div>
      </div>

      <div class="mb-3">
        <label for="marital_status">{{ get_phrase('Marital status') }}</label>
        <select id="marital_status" name="marital_status" class="form-control">
          <option value="">{{ get_phrase('Select') }}</option>
          <option value="married" {{ auth()->user()->marital_status == 'married' ? 'selected' : '' }}>{{ get_phrase('Married') }}</option>
          <option value="unmarried" {{ auth()->user()->marital_status == 'unmarried' ? 'selected' : '' }}>{{ get_phrase('Unmarried') }}</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="phone">{{ get_phrase('Phone') }}</label>
        <input value="{{ auth()->user()->phone }}" id="phone" name="phone" type="text" class="form-control" placeholder="{{ get_phrase('Enter your phone number') }}">
      </div>

      <div class="mb-3">
        <label for="date_of_birth">{{ get_phrase('Date of birth') }}</label>
        <input value="{{ date('Y-m-d', auth()->user()->date_of_birth) }}" id="date_of_birth" name="date_of_birth" type="date" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">{{ get_phrase('State') }}</label>
        <select name="state" id="state" class="selectpicker form-control">
          @if(isset($state))
            <option value="{{ $state->id }}" selected>{{ $state->state_name }}</option>
          @endif
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">{{ get_phrase('City') }}</label>
        <select name="city" id="city" class="selectpicker form-control">
          @if(isset($city))
            <option value="{{ $city->id }}" selected>{{ $city->city_name }}</option>
          @endif
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">{{ get_phrase('Area') }}</label>
        <select name="area" id="area" class="selectpicker form-control">
          @if(isset($area))
            <option value="{{ $area->id }}" selected>{{ $area->area_name }}</option>
          @endif
        </select>
      </div>

      <div class="mb-3 mt-5">
        <button class="btn btn-primary w-100" type="submit">{{ get_phrase('Update Profile') }}</button>
      </div>
    </form>
  </div>
</div>

@include('frontend.initialize')

<script>
$(document).ready(function () {
  $('.selectpicker').select2();

  // State dropdown with AJAX
  $('#state').select2({
    placeholder: 'Type State',
    ajax: {
      url: '/states-autocomplete-ajax',
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        return {
          results: $.map(data, function (item) {
            return { text: item.state_name, id: item.id }
          })
        };
      },
      cache: true
    }
  });

  // City Load on State Change
  $('#state').on('change', function () {
    $('#city').html('<option selected value="0">Select City</option>');
    $('#area').html('<option selected value="0">Select Area</option>');

    let stateID = $(this).val();
    if (stateID > 0) {
      $.ajax({
        url: '/ajax/cities/' + stateID,
        method: 'get',
        success: function (result) {
          let cities = JSON.parse(result);
          $.each(cities, function (key, value) {
            $('#city').append(`<option value="${value.id}">${value.city_name}</option>`);
          });
        }
      });
    }
  });

  // Area Load on City Change
  $('#city').on('change', function () {
    $('#area').html('<option selected value="0">Select Area</option>');

    let cityID = $(this).val();
    if (cityID > 0) {
      $.ajax({
        url: '/ajax/areas/' + cityID,
        method: 'get',
        success: function (result) {
          let areas = JSON.parse(result);
          $.each(areas, function (key, value) {
            $('#area').append(`<option value="${value.id}">${value.area_name}</option>`);
          });
        }
      });
    }
  });

  // Pre-fill cities and areas if selected
  const selectedState = '{{ auth()->user()->state_id }}';
  const selectedCity = '{{ auth()->user()->city_id }}';
  const selectedArea = '{{ auth()->user()->area_id }}';

  if (selectedState) {
    $('#state').val(selectedState).trigger('change');

    // Delay loading cities
    setTimeout(() => {
      $.ajax({
        url: '/ajax/cities/' + selectedState,
        method: 'get',
        success: function (result) {
          let cities = JSON.parse(result);
          $.each(cities, function (key, value) {
            const selected = value.id == selectedCity ? 'selected' : '';
            $('#city').append(`<option value="${value.id}" ${selected}>${value.city_name}</option>`);
          });

          $('#city').val(selectedCity).trigger('change');

          // Delay loading areas
          setTimeout(() => {
            $.ajax({
              url: '/ajax/areas/' + selectedCity,
              method: 'get',
              success: function (result) {
                let areas = JSON.parse(result);
                $.each(areas, function (key, value) {
                  const selected = value.id == selectedArea ? 'selected' : '';
                  $('#area').append(`<option value="${value.id}" ${selected}>${value.area_name}</option>`);
                });
              }
            });
          }, 500);
        }
      });
    }, 500);
  }
});
</script>
