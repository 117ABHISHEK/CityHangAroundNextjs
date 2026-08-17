
<div class="main_content">
	<!-- Main section header -->
	<div class="mainSection-title">
		<div class="row">
			<div class="col-12">
				<div class="d-flex justify-content-between align-items-center flex-wrap gr-15">
					<div class="d-flex flex-column">
						<h4>{{ get_phrase('Add New Payment Gateway') }}</h4>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Start Admin area -->
	<div class="row">
		<div class="col-md-7">
			<div class="eSection-wrap-2">
	            <div class="col-md-12">
                    <h4 class="header-title mb-3">{{ get_phrase('Payment Gateway Settings') }}</h4>
                    
                    <form action="{{ route('admin.payment_gateway.store') }}" method="POST" enctype="multipart/form-data">
                    	@csrf

                        <!-- Payment Gateway Name -->
                        <div class="form-group mb-3">
                            <label class="eForm-label">{{ get_phrase('Gateway Title') }}</label>
                            <input type="text" name="title" class="form-control eForm-control" required />
                        </div>

                        <!-- Select Currency -->
                        <div class="form-group mb-3">
                            <label class="eForm-label">{{ get_phrase('Select Currency') }}</label>
                            <select class="form-control eForm-control select2" data-toggle="select2" name="currency" required>
                                <option value="">{{ get_phrase('Select Currency') }}</option>
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency->code }}">{{ $currency->code }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Keys (API Keys, Secret Keys, etc.) -->
                        <div class="form-group mb-3">
                            <label class="eForm-label">{{ get_phrase('API Key') }}</label>
                            <input type="text" name="api_key" class="form-control eForm-control" required />
                        </div>

                        <div class="form-group mb-3">
                            <label class="eForm-label">{{ get_phrase('Secret Key') }}</label>
                            <input type="text" name="secret_key" class="form-control eForm-control" required />
                        </div>

                        <!-- Environment Mode -->
                        <div class="form-group mb-3">
                            <label class="eForm-label">{{ get_phrase('Environment') }}</label>
                            <select name="test_mode" class="form-control eForm-control">
                                <option value="1">{{ get_phrase('Test Mode') }}</option>
                                <option value="0">{{ get_phrase('Live Mode') }}</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="form-group mb-3">
                            <label class="eForm-label">{{ get_phrase('Status') }}</label>
                            <select name="status" class="form-control eForm-control">
                                <option value="1">{{ get_phrase('Active') }}</option>
                                <option value="0">{{ get_phrase('Inactive') }}</option>
                            </select>
                        </div>

                        <!-- Save Button -->
                        <div class="row">
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit">{{ get_phrase('Save Payment Gateway') }}</button>
                            </div>
                        </div>

                    </form>
	            </div>
			</div>
		</div>
	</div>
</div>
