
@extends('admin.layouts.app') 
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
					<a href="{{ route("admin.dashboard") }}">{{ translate("Dashboard") }}</a>
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
			<div class="card">
			  <div class="card-header pb-0">
				<div class="card-header-left">
				  <h4 class="card-title">{{ translate("Basic Information") }}</h4>
				</div>
			  </div>
			  <div class="card-body">
				<div class="profile-content">
				  <div class="d-flex align-items-center gap-3">
					<span class="customer-img">
					  <img src="{{showImage(config('setting.file_path.admin_profile.path').'/'.auth()->guard('admin')->user()->image, config('setting.file_path.admin_profile.size'))}}" alt="{{ auth()->guard('admin')->user()->username }}">
					</span>
					<div>
					  <h5 class="fs-16 mb-1">{{ auth()->guard('admin')->user()->username }}</h5>
					  <p class="text-muted fs-14">{{ auth()->guard('admin')->user()->created_at?->toDayDateTimeString() ?? translate('N/A')}}</p>
					</div>
				  </div>
				  <ul class="mt-4 d-flex flex-column gap-1">
					<li class="d-flex align-items-center justify-content-between flex-wrap gap-2">
					  <span class="text-dark fs-14">{{ translate("Username") }}</span>
					  <span class="fs-14">{{ auth()->guard('admin')->user()->username }}</span>
					</li>
					<li class="d-flex align-items-center justify-content-between flex-wrap gap-2">
					  <span class="text-dark fs-14">{{ translate("Email") }}</span>
					  <a class="text-break text-muted fs-14" href="mailto:noah@gmail.com">{{ auth()->guard('admin')->user()->email }}</a>
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
					<h4 class="card-title">{{ translate("Admin Details") }}</h4>
				  </div>
				  <div class="col-lg-9 col-md-8">
					<div class="form-tab">
					  <ul class="nav" role="tablist">
						<li class="nav-item" role="presentation">
							
						  <a class="nav-link {{ !$activeTab || $activeTab == 'details' ? 'active' : '' }}" 
							 data-bs-toggle="tab" 
							 href="#details" 
							 role="tab" 
							 aria-selected="{{ !$activeTab || $activeTab == 'details' ? 'true' : 'false' }}" 
							 {{ $activeTab == 'details' ? '' : "tabindex='-1'" }}>

							<i class="bi bi-info-circle"></i> {{ translate("Details") }} </a>
						</li>

						<li class="nav-item" role="presentation">
						  <a class="nav-link {{ $activeTab == 'passwordUpdate' ? 'active' : '' }}" 
						  	 data-bs-toggle="tab" 
							 href="#passwordUpdate" 
							 role="tab" 
							 aria-selected="{{ $activeTab == 'passwordUpdate' ? 'true' : 'false' }}" 
							 {{ $activeTab == 'passwordUpdate' ? '' : "tabindex='-1'" }}>

							<i class="bi bi-person-lock"></i> {{ translate("Password") }} </a>
						</li>
					  </ul>
					</div>
				  </div>
				</div>
			  </div>
			  <div class="card-body pt-0">
				<div class="tab-content">
				  <div class="tab-pane fade {{ !$activeTab || $activeTab == 'details' ? 'active show' : '' }}" id="details" role="tabpanel">
					<form action="{{route('admin.profile.update')}}" method="POST" enctype="multipart/form-data">
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
								  <input type="text" id="name" name="name" class="form-control" placeholder="Enter name" aria-label="name" value="{{ auth()->guard('admin')->user()->name }}" />
								  
								</div>
							  </div>
							  <div class="col-md-6">
								<div class="form-inner">
								  <label for="username" class="form-label">{{ translate("Username") }}</label>
								  <input type="text" id="username" name="username" class="form-control" placeholder="Enter user name" aria-label="username" value="{{ auth()->guard('admin')->user()->username }}" />
								</div>
							  </div>
							  <div class="col-md-6">
								<div class="form-inner">
								  <label for="email" class="form-label">{{ translate("Email") }}</label>
								  <input type="email" id="email" name="email" class="form-control" placeholder="Enter email address" aria-label="email" value="{{ auth()->guard('admin')->user()->email }}" />
								</div>
							  </div>
							  <div class="col-md-6">
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
				  <div class="tab-pane fade {{ !$activeTab || $activeTab == 'passwordUpdate' ? 'active show' : '' }}" id="passwordUpdate" role="tabpanel">
					<form action="{{route('admin.password.update')}}" method="POST" enctype="multipart/form-data">
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