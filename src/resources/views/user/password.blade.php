@extends('user.layouts.app')
@section('panel')
<section class="mt-3 rounded_box">
	<div class="container-fluid p-0 mb-3 pb-2">
		<div class="row d-flex align--center rounded">
			<div class="col-xl-12">
				<div class="card">
					<div class="card-header text-center">
						<h6 class="card-title text-dark">{{@$title}}</h6>
					</div>
					<div class="card-body">
						<form action="{{route('user.password.update')}}" method="POST">
							@csrf

                            @if(auth()->user()->password)
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">{{ translate('Current Password')}} <sup class="text--danger">*</sup></label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" placeholder="{{ translate('Enter Current Password')}}" required>
                                </div>
                            @endif

							<div class="mb-3">
								<label for="new_password" class="form-label">{{ translate('New Password')}} <sup class="text--danger">*</sup></label>
								<input type="password" class="form-control" id="new_password" name="password" placeholder="{{ translate('Enter New Password')}}" required>
							</div>

							<div class="mb-3">
								<label for="password_confirmation" class="form-label">{{ translate('Confirm Password')}} <sup class="text--danger">*</sup></label>
								<input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="{{ translate('Enter Confirm Password')}}" required>
							</div>

							<button type="submit" class="i-btn primary--btn btn--md">{{ translate('Submit')}}</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection
