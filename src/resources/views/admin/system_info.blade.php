@extends('admin.layouts.app')
@section('panel')
<main class="main-body">
	<div class="container-fluid px-0 main-content">
	  <div class="page-header">
		<div class="page-header-left">
		  <h2>{{ translate("System Information") }}</h2>
		  <div class="breadcrumb-wrapper">
			<nav aria-label="breadcrumb">
			  <ol class="breadcrumb">
				<li class="breadcrumb-item">
				  <a href="{{ route('admin.dashboard') }}">{{ translate("Dashboard") }}</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"> {{ translate("System Information") }} </li>
			  </ol>
			</nav>
		  </div>
		</div>
	  </div>
	  <div class="row">
		<div class="col-xxl-7 col-xl-8">
		  <div class="card h-100">
			<div class="form-header">
			  <h4 class="card-title">{{ translate("System Informations") }}</h4>
			</div>
			<div class="card-body">
			  <div class="ul-list">
				<ul>
				  <li class="fs-15">
					<span class="text-muted">{{ translate('Document Root Folder')}}</span>
					<span class="fw-medium text-break">{{$systemInfo['serverdetail']['DOCUMENT_ROOT']}}</span>
				  </li>
				  <li class="fs-15">
					<span class="text-muted">{{ translate('System Laravel Version')}}</span>
					<span class="fw-medium">{{$systemInfo['laravelversion']}}</span>
				  </li>
				  <li class="fs-14">
					<span class="text-muted">{{ translate('PHP Version')}}</span>
					<span class="fw-medium">{{$systemInfo['phpversion']}}</span>
				  </li>
				  <li class="fs-14">
					<span class="text-muted">{{ translate('IP Address')}}</span>
					<span class="fw-medium">{{$systemInfo['serverdetail']['REMOTE_ADDR']}}</span>
				  </li>
				  <li class="fs-14">
					<span class="text-muted">{{ translate('System Server host')}}</span>
					<span class="fw-medium">{{$systemInfo['serverdetail']['HTTP_HOST']}}</span>
				  </li>
				</ul>
			  </div>
			</div>
		  </div>
		</div>
	  </div>
	</div>
  </main>
@endsection
