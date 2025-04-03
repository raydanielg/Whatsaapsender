
@extends('user.layouts.app')
@section('panel')
<section>
	<main class="main-body">
	  <div class="container-fluid px-0 main-content">
		<div class="page-header">
		  <div class="page-header-left">
			<h2>{{ $title }}</h2>
			<div class="breadcrumb-wrapper">
			  <nav aria-label="breadcrumb">
				<ol class="breadcrumb">
				  <li class="breadcrumb-item">
					<a href="{{ route("user.dashboard") }}">{{ translate("Dashboard") }}</a>
				  </li>
				  <li class="breadcrumb-item active" aria-current="page">
					{{ translate("Profile") }}
				  </li>
				</ol>
			  </nav>
			</div>
		  </div>
		</div>
		<div class="row g-4">
		  <div class="col-xxl-3 col-xl-4 col-lg-8">
			<div class="card sticky-side-div">
			  <div class="card-header pb-0">
				<div class="card-header-left">
				  <h4 class="card-title">{{ translate("Basic Information") }}</h4>
				</div>
			  </div>
			  <div class="card-body">
				<div class="profile-content">
				  <div class="d-flex align-items-center gap-3">
					<span class="customer-img">
						<img src="{{showImage(filePath()['profile']['user']['path'].'/'.auth()->user()->image, filePath()['profile']['user']['size'])}}" alt="{{ auth()->user()->username }}">
					</span>
					<div>
					  <h5 class="fs-16 mb-1">{{ auth()->user()->name }}</h5>
					  <p class="text-muted fs-14">{{ auth()->user()->created_at->toDayDateTimeString()}}</p>
					</div>
				  </div>
				  <ul class="mt-4 d-flex flex-column gap-1">
					<li class="d-flex align-items-center justify-content-between flex-wrap gap-2">
					  <span class="text-dark fs-14">{{ translate("Email Address") }}</span>
					  <span class="fs-14">{{ auth()->user()->email }}</span>
					</li>
				  </ul>
				</div>
			  </div>
			</div>
		  </div>

		  <div class="col-xxl-9 col-xl-8">
			<div class="card">
			  <div class="form-header">
				<div class="row g-3 align-items-center">
				  <div class="col-lg-3 col-md-4">
					<h4 class="card-title">{{ translate("Details") }}</h4>
				  </div>
				  <div class="col-lg-9 col-md-8">
					<div class="form-tab">
					  <ul class="nav" role="tablist">
						<li class="nav-item" role="presentation">

						  <a class="nav-link active"
							 data-bs-toggle="tab"
							 href="#details"
							 role="tab"
							 aria-selected="true"
							 tabindex='-1'>

							<i class="bi bi-info-circle"></i> {{ translate("Details") }} </a>
						</li>

						<li class="nav-item" role="presentation">
						  <a class="nav-link"
						  	 data-bs-toggle="tab"
							 href="#passwordUpdate"
							 role="tab"
							 aria-selected="false"
							 tabindex='-1'>

							<i class="bi bi-person-lock"></i> {{ translate("Password") }} </a>
						</li>
					  </ul>
					</div>
				  </div>
				</div>
			  </div>
			  <div class="card-body pt-0">
				<div class="tab-content">
				  <div class="tab-pane fade active show" id="details" role="tabpanel">
					<form action="{{route('user.profile.update')}}" method="POST" enctype="multipart/form-data">
					@csrf
					  <div class="form-element">
						<div class="row gy-4">
						  <div class="col-xxl-2 col-xl-3">
							<h5 class="form-element-title">{{ translate("Update details") }}</h5>
						  </div>
						  <div class="col-xxl-8 col-xl-9">
							<div class="row g-4">
							  <div class="col-md-6">
								<div class="form-inner">
								  <label for="name" class="form-label">{{ translate("Name") }}</label>
								  <input type="text" id="name" name="name" class="form-control" placeholder="Enter your name" aria-label="name" value="{{$user->name}}"/>
								</div>
							  </div>
							  <div class="col-md-6">
								<div class="form-inner">
								  <label for="email" class="form-label">{{ translate("Email Address") }}</label>
								  <input type="email" id="email" name="email" class="form-control" placeholder="Enter email address" aria-label="email" value="{{$user->email}}" />
								</div>
							  </div>
							  <div class="col-md-6">
								<div class="form-inner">
								  <label for="address" class="form-label">{{ translate("Address") }}</label>
								  <input type="text" id="address" name="address" class="form-control" placeholder="Enter your address" aria-label="address" value="{{@$user->address->address}}"/>
								</div>
							  </div>
							  <div class="col-md-6">
								<div class="form-inner">
								  <label for="city" class="form-label">{{ translate("City") }}</label>
								  <input type="text" id="city" name="city" class="form-control" placeholder="Enter your city" aria-label="city" value="{{@$user->address->city}}"/>
								</div>
							  </div>
							  <div class="col-md-6">
								<div class="form-inner">
								  <label for="state" class="form-label">{{ translate("State") }}</label>
								  <input type="text" id="state" name="state" class="form-control" placeholder="Enter your state" aria-label="state" value="{{@$user->address->state}}"/>
								</div>
							  </div>
							  <div class="col-md-6">
								<div class="form-inner">
								  <label for="zip" class="form-label">{{ translate("Zip Code") }}</label>
								  <input type="text" id="zip" name="zip" class="form-control" placeholder="Enter your zip code" aria-label="zip" value="{{@$user->address->zip}}"/>
								</div>
							  </div>
							  <div class="col-md-12">
								<div class="form-inner">
								  <label for="image" class="form-label">{{ translate("Profile Image") }}</label>
								  <input type="file" name="image" id="image" class="form-control" aria-label="image" />
								  <p class="form-element-note">{{ translate("Accepted Image Type: ").implode(', ', json_decode(site_settings("mime_types"), true)) }}</p>
								</div>
							  </div>
							</div>
						  </div>
						</div>
					  </div>
					  <div class="form-action">
						<button type="submit" class="i-btn btn--primary btn--md">{{ translate("Submit") }}</button>
					  </div>
					</form>
				  </div>
				  <div class="tab-pane fade" id="passwordUpdate" role="tabpanel">
					<form action="{{route('user.password.update')}}" method="POST" enctype="multipart/form-data">
					  @csrf
					  <div class="form-element">
						<div class="row gy-4">
						  <div class="col-xxl-2 col-xl-3">
							<h5 class="form-element-title">{{ translate("Update password") }}</h5>
						  </div>
						  <div class="col-xxl-8 col-xl-9">
							<div class="row g-4">
							  <div class="col-md-12">
								<div class="form-inner">
								  <label for="current_password" class="form-label">{{ translate("Current Password") }}</label>
								  <input type="password" id="current_password" class="form-control" name="current_password" placeholder="{{ translate("Enter your current password") }}" aria-label="current_password" />
								</div>
							  </div>
							  <div class="col-md-6">
								<div class="form-inner">
								  <label for="new_password" class="form-label">{{ translate("New Password") }}</label>
								  <input type="password" id="new_password" class="form-control" name="password" placeholder="{{ translate("Enter your new password") }}" aria-label="new_password" />
								</div>
							  </div>
							  <div class="col-md-6">
								<div class="form-inner">
								  <label for="password_confirmation" class="form-label">{{ translate("Confirm password ") }}</label>
								  <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="{{ translate("Re-enter your new password") }}" aria-label="password_confirmation" />
								</div>
							  </div>
							</div>
						  </div>
						</div>
					  </div>
					  <div class="form-action">
						<button type="submit" class="i-btn btn--primary btn--md"> {{ translate("Submit") }} </button>
					  </div>
					</form>
				  </div>
				</div>
			  </div>
			</div>
		  </div>
		</div>
	  </div>
	</main>
  </section>

  @endsection
