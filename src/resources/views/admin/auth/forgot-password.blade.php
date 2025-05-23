@extends('admin.layouts.auth')

@section('content')
<main class="auth">
    <div class="container-fluid px-0">
        <div class="row gx-0 gy-md-4 gy-0">
        <div class="col-lg-6">
            <div class="auth-left">
            <a href="{{ url("/") }}">
                <img src="{{showImage(config('setting.file_path.site_logo.path').'/'.site_settings('site_logo'),config('setting.file_path.site_logo.size'))}}" class="logo-lg" alt="">
            </a>
            <div class="auth-form-wrapper">
                <form action="{{route('admin.password.email')}}" method="POST" class="auth-form">
                    @csrf
                <h3 class="mb-4">{{ translate("Account Password Recovery") }}</h3>
                <div class="form-inner mb-4">
                    <label for="email" class="form-label">{{ translate("Email Address") }}</label>
                    <div class="auth-input-wrapper">
                    <span class="auth-input-icon">
                        <i class="ri-mail-line"></i>
                    </span>
                    <input type="text" id="email" name="email" class="form-control" placeholder="Enter your email address" aria-label="email" />
                    </div>
                </div>
                <div class="mb-3">
                    <a href="{{route('admin.login')}}" class="fs-15 text-info text-decoration-underline">{{ translate("Login") }}</a>
                </div>
                <button type="submit" class="i-btn btn--primary btn--lg w-100"> {{ translate("Submit") }} <i class="ri-arrow-right-line fs-18"></i>
                </button>
                </form>
            </div>
            <div class="auth-footer">
                <div class="footer-content">
                <p class="copy-write">
                    <a href="https://igensolutionsltd.com/" class="text--primary">{{ site_settings("copyright") }}
                </p>
                <div class="footer-right">
                    <ul>
                    <li>
                        <a href="https://support.igensolutionsltd.com">{{ translate("Support") }}</a>
                    </li>
                    </ul>
                    <span class="i-badge info-solid">{{ translate("Version: ").site_settings("app_version") }}</span>
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="auth-right">
            <div class="auth-content">
                <div class="auth-content-top">
                <h2>
                    <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 79 72" fill="none">
                        <g clip-path="url(#clip0_435_56385)">
                        <path d="M44.0077 71.6158C44.0077 71.6158 40.2725 49.3968 35.6623 44.8719C31.052 40.347 8.66227 37.0387 8.66227 37.0387C8.66227 37.0387 30.8812 33.3036 35.4061 28.6933C39.931 24.083 43.2393 1.69327 43.2393 1.69327C43.2393 1.69327 46.9745 23.9122 51.5848 28.4371C56.1951 32.962 78.5848 36.2703 78.5848 36.2703C78.5848 36.2703 56.3658 40.0055 51.8409 44.6158C47.316 49.2261 44.0077 71.6158 44.0077 71.6158Z" fill="white" />
                        </g>
                        <path d="M10.7444 25.6494C10.7444 25.6494 9.6212 17.5434 8.2348 15.8927C6.8484 14.2419 0.115378 13.0349 0.115378 13.0349C0.115378 13.0349 6.79705 11.6723 8.15778 9.99034C9.5185 8.30842 10.5134 0.140161 10.5134 0.140161C10.5134 0.140161 11.6366 8.24612 13.023 9.8969C14.4094 11.5477 21.1424 12.7546 21.1424 12.7546C21.1424 12.7546 14.4608 14.1173 13.1 15.7992C11.7393 17.4812 10.7444 25.6494 10.7444 25.6494Z" fill="white" />
                        <path d="M60.3284 26.8757C60.3284 26.8757 59.4954 20.8645 58.4672 19.6403C57.4391 18.4161 52.446 17.5211 52.446 17.5211C52.446 17.5211 57.401 16.5105 58.4101 15.2632C59.4192 14.0159 60.157 7.95849 60.157 7.95849C60.157 7.95849 60.99 13.9697 62.0181 15.1939C63.0462 16.4181 68.0393 17.3132 68.0393 17.3132C68.0393 17.3132 63.0843 18.3237 62.0752 19.571C61.0661 20.8183 60.3284 26.8757 60.3284 26.8757Z" fill="white" />
                        <defs>
                        <clipPath id="clip0_435_56385">
                            <rect width="49.986" height="49.986" fill="white" transform="translate(43.6235 1.30908) rotate(45)" />
                        </clipPath>
                        </defs>
                    </svg>
                    </span> {{ site_settings("auth_heading") }}
                </h2>
                </div>
                <div class="auth-content-middle">
                    <img src="{{showImage(config('setting.file_path.authentication_background_inner_image_one.path').'/'.site_settings('authentication_background_inner_image_one'),config('setting.file_path.authentication_background_inner_image_one.size'))}}" alt="">
                    <img src="{{showImage(config('setting.file_path.authentication_background_inner_image_two.path').'/'.site_settings('authentication_background_inner_image_two'),config('setting.file_path.authentication_background_inner_image_two.size'))}}" alt="">
                </div>
                @include('admin.auth.feedback')
                <div class="auth-bg">
                    <img src="{{showImage(config('setting.file_path.authentication_background.path').'/'.site_settings('authentication_background'),config('setting.file_path.authentication_background.size'))}}" alt="">
                    <span class="auth-bg-shape">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1018 902" fill="none">
                        <path d="M1053.06 779.54L1000.84 669.496L1053.06 681.169V680.745L1000.49 668.859H1000.14L1053.06 780.389V779.54Z" fill="white" fill-opacity="0.29" />
                        <path d="M1053.06 680.744L1000.76 668.965L1037.35 520.825L1053.06 547.566V546.718L1037.44 520.188L1037.26 519.764L1000.32 669.071L1000.23 669.283L1053.06 681.169V680.744Z" fill="white" fill-opacity="0.29" />
                        <path d="M924.491 902L916.768 859.022L1053.06 851.275V850.851L916.329 858.491L924.14 902H924.491Z" fill="white" fill-opacity="0.29" />
                        <path d="M1053.06 850.851L916.857 858.492L1000.49 669.603L1053.06 780.389V779.54L1000.67 668.966L1000.49 668.647L916.33 858.598L916.242 859.022L1053.06 851.276V850.851Z" fill="white" fill-opacity="0.29" />
                        <path d="M1053.06 133.177L1027.87 343.609L928.002 307.21L1053.06 130.418V129.782L927.563 307.21L927.3 307.423L1028.14 344.245L1053.06 136.361V133.177Z" fill="white" fill-opacity="0.29" />
                        <path d="M916.418 859.446V858.81L885.877 649.121H886.14L1000.76 668.965V669.283L916.418 859.446ZM886.404 649.652L916.593 858.067L1000.14 669.283L886.316 649.652H886.404Z" fill="white" fill-opacity="0.29" />
                        <path d="M1037.53 520.825L929.67 403.778H929.933L1028.14 343.609V343.927L1037.53 520.825ZM930.372 403.884L1037.09 519.764L1027.87 344.352L930.372 403.884Z" fill="white" fill-opacity="0.29" />
                        <path d="M885.701 649.545L1037.7 519.658L1037.53 520.294L1000.67 669.283H1000.49L885.701 649.439V649.545ZM1037 520.825L886.667 649.227L1000.32 668.859L1036.91 520.825H1037Z" fill="white" fill-opacity="0.29" />
                        <path d="M885.877 649.971V649.44L929.933 403.459L930.196 403.672L1037.7 520.295L1037.53 520.507L885.965 649.971H885.877ZM930.108 404.308L886.404 648.91L1036.91 520.295L930.02 404.308H930.108Z" fill="white" fill-opacity="0.29" />
                        <path d="M858.408 902L916.418 859.129L924.141 902H924.492L916.681 858.704V858.386L857.706 902H858.408Z" fill="white" fill-opacity="0.29" />
                        <path d="M1053.06 127.659L976.447 0H976.008L1053.06 128.402V127.659Z" fill="white" fill-opacity="0.29" />
                        <path d="M1053.06 129.782L927.826 306.786L921.507 140.5L1053.06 129.464V128.933L921.332 139.969L921.068 140.075L927.475 307.954L1053.06 130.419V129.782Z" fill="white" fill-opacity="0.29" />
                        <path d="M916.769 859.234L916.418 858.916L784.777 746.962L784.953 746.75L886.228 648.909V649.334L916.769 859.234ZM785.392 746.962L916.242 858.173L885.965 649.758L785.304 746.962H785.392Z" fill="white" fill-opacity="0.29" />
                        <path d="M929.844 404.202V403.884L927.475 307.104H927.738L1028.49 343.927L1028.05 344.14L929.756 404.202H929.844ZM927.913 307.635L930.195 403.459L1027.52 344.033L927.913 307.741V307.635Z" fill="white" fill-opacity="0.29" />
                        <path d="M1053.06 128.933L921.507 139.969L942.745 0H942.306L921.156 140.181L921.068 140.5L1053.06 129.463V128.933Z" fill="white" fill-opacity="0.29" />
                        <path d="M1053.06 127.659L976.446 0H976.007L1053.06 128.402V127.659Z" fill="white" fill-opacity="0.29" />
                        <path d="M783.461 901.999L785.304 747.492L916.155 858.703L857.707 901.999H858.409L916.594 858.916L916.857 858.81L785.217 746.855L784.866 746.537L783.11 901.999H783.461Z" fill="white" fill-opacity="0.29" />
                        <path d="M785.041 747.386V747.174L725.539 609.433L886.579 649.333L886.228 649.652L784.953 747.386H785.041ZM726.241 610.07L785.128 746.643L885.702 649.546L726.241 610.07Z" fill="white" fill-opacity="0.29" />
                        <path d="M886.228 649.971L885.965 649.546L754.5 427.548H754.851L930.284 403.565V403.884L886.316 649.971H886.228ZM755.202 427.973L886.053 648.909L929.757 404.096L755.202 427.973Z" fill="white" fill-opacity="0.29" />
                        <path d="M725.627 609.964V609.752L754.763 427.229L886.579 649.758L725.627 609.964ZM754.939 428.397L726.066 609.646L885.702 649.121L754.939 428.397Z" fill="white" fill-opacity="0.29" />
                        <path d="M754.852 427.972V427.548L927.827 306.892V307.316L930.197 403.99H930.021L754.852 427.972ZM927.476 307.741L755.729 427.442L929.758 403.671L927.388 307.741H927.476Z" fill="white" fill-opacity="0.29" />
                        <path d="M942.307 0L921.157 139.757L828.921 37.1412L883.683 0H882.894L828.307 37.035L921.157 140.394L921.42 140.712L942.746 0H942.307Z" fill="white" fill-opacity="0.29" />
                        <path d="M927.915 307.741L788.113 229.214L788.464 229.002L921.597 139.863V140.181L928.003 307.635L927.915 307.741ZM788.903 229.214L927.477 306.998L921.158 140.606L788.903 229.214Z" fill="white" fill-opacity="0.29" />
                        <path d="M754.588 428.185V427.654L788.288 228.79H788.463L928.002 307.21L754.5 428.078L754.588 428.185ZM788.551 229.532L755.114 427.336L927.212 307.423L788.551 229.638V229.532Z" fill="white" fill-opacity="0.29" />
                        <path d="M650.417 902L716.325 721.069L784.778 747.068L663.055 902H663.581L785.217 747.174L785.393 746.856L716.238 720.645L716.062 720.539L650.066 902H650.417Z" fill="white" fill-opacity="0.29" />
                        <path d="M663.581 901.999L784.866 747.598L783.111 901.999H783.462L785.305 746.961V746.431L663.055 901.999H663.581Z" fill="white" fill-opacity="0.29" />
                        <path d="M785.481 747.386L716.062 720.963V720.751L725.804 608.903L726.067 609.54L785.481 747.28V747.386ZM716.414 720.645L784.691 746.643L725.979 610.495L716.414 720.645Z" fill="white" fill-opacity="0.29" />
                        <path d="M882.893 0L828.832 36.7167L830.588 0H830.237L828.481 37.1412L828.394 37.5656L883.682 0H882.893Z" fill="white" fill-opacity="0.29" />
                        <path d="M788.201 229.638V229.108L828.571 36.6104L828.834 36.8226L921.685 140.181H921.421L788.201 229.532V229.638ZM828.746 37.5654L788.728 228.789L920.982 140.181L828.746 37.5654Z" fill="white" fill-opacity="0.29" />
                        <path d="M754.94 428.29L754.677 427.972L594.777 195.362H595.304L788.728 229.108V229.32L755.028 428.397L754.94 428.29ZM595.655 195.893L754.677 427.229L788.201 229.32L595.655 195.893Z" fill="white" fill-opacity="0.29" />
                        <path d="M572.135 395.394V395.182L595.128 195.044L595.391 195.468L755.29 428.078L572.047 395.394H572.135ZM595.391 196.211L572.573 395.076L754.413 427.548L595.391 196.211Z" fill="white" fill-opacity="0.29" />
                        <path d="M547.737 544.065V543.64L572.222 394.97L755.554 427.654L754.939 427.972L547.737 544.065ZM572.486 395.5L548.264 543.322L754.237 427.972L572.486 395.5Z" fill="white" fill-opacity="0.29" />
                        <path d="M788.551 229.85L788.287 229.426L694.998 40.218H695.349L828.92 36.8223V37.1406L788.551 229.744V229.85ZM695.612 40.6425L788.375 228.577L828.394 37.3529L695.7 40.6425H695.612Z" fill="white" fill-opacity="0.29" />
                        <path d="M726.066 610.07L547.474 543.747L547.912 543.535L755.114 427.442V427.866L726.066 610.07ZM548.527 543.641L725.803 609.434L754.676 428.185L548.527 543.641Z" fill="white" fill-opacity="0.29" />
                        <path d="M567.044 902L548.702 894.147L715.71 721.6L650.065 902H650.416L716.412 720.963L716.061 720.645L548.176 894.041L548 894.359L565.903 902H567.044Z" fill="white" fill-opacity="0.29" />
                        <path d="M548.175 894.678V894.253L523.427 683.291H523.69L716.675 720.539L716.324 720.857L548.175 894.572V894.678ZM523.866 683.928L548.526 893.829L715.797 720.963L523.866 683.928Z" fill="white" fill-opacity="0.29" />
                        <path d="M523.603 683.928V683.504L726.066 609.434V609.752L716.324 721.069H716.149L523.603 683.928ZM725.627 610.07L524.48 683.61L716.061 720.539L725.714 610.07H725.627Z" fill="white" fill-opacity="0.29" />
                        <path d="M551.598 901.999L548.702 894.677L565.903 901.999H567.044L548.439 894.041L548 893.828L551.159 901.999H551.598Z" fill="white" fill-opacity="0.29" />
                        <path d="M830.237 0L828.482 36.6106L801.189 0H800.662L828.833 37.6717L830.588 0H830.237Z" fill="white" fill-opacity="0.29" />
                        <path d="M523.427 684.034V683.61L547.824 543.322H548L726.504 609.646L725.89 609.858L523.339 683.928L523.427 684.034ZM548.175 543.959L523.953 683.291L725.275 609.752L548.175 543.959Z" fill="white" fill-opacity="0.29" />
                        <path d="M544.49 901.999L548.351 894.783L551.159 901.999H551.598L548.527 894.147L548.351 893.828L544.051 901.999H544.49Z" fill="white" fill-opacity="0.29" />
                        <path d="M800.662 0L828.218 36.9289L695.525 40.2186L699.65 0H699.298L695.086 40.4308V40.643L828.657 37.3534H829.008L801.188 0H800.662Z" fill="white" fill-opacity="0.29" />
                        <path d="M594.864 195.788L595.04 195.469L695.262 40.0068L788.727 229.427H788.376L594.864 195.681V195.788ZM695.262 40.8558L595.566 195.469L788.112 228.896L695.262 40.8558Z" fill="white" fill-opacity="0.29" />
                        <path d="M595.304 195.893H595.128L500.698 121.187H501.049L695.877 40.0067L695.526 40.6434L595.304 196V195.893ZM501.576 121.293L595.216 195.257L694.824 40.8556L501.576 121.293Z" fill="white" fill-opacity="0.29" />
                        <path d="M699.3 0L695.175 40.1125L637.604 0H636.814L695.438 40.8553L699.651 0H699.3Z" fill="white" fill-opacity="0.29" />
                        <path d="M428.733 902L407.671 838.542L548.087 894.36L544.05 902H544.489L548.526 894.36L548.614 894.147L407.408 838.011L407.057 837.799L428.382 902H428.733Z" fill="white" fill-opacity="0.29" />
                        <path d="M548.526 894.572L406.969 838.329L407.144 838.117L523.69 683.185V683.716L548.526 894.678V894.572ZM407.671 838.117L548.087 893.935L523.514 684.14L407.759 838.117H407.671Z" fill="white" fill-opacity="0.29" />
                        <path d="M572.485 395.5L500.083 352.204L595.127 195.362L595.478 195.575L572.485 395.5ZM500.61 352.098L572.134 394.863L594.952 196.53L500.61 352.098Z" fill="white" fill-opacity="0.29" />
                        <path d="M636.815 0L694.824 40.4308L501.4 120.868L535.188 0H534.749L500.786 121.611L695.789 40.5369L637.604 0H636.815Z" fill="white" fill-opacity="0.29" />
                        <path d="M407.144 838.753L408.724 673.847L523.953 683.397L523.69 683.716L407.057 838.753H407.144ZM409.163 674.377L407.583 837.586L523.251 683.822L409.163 674.377Z" fill="white" fill-opacity="0.29" />
                        <path d="M547.825 543.747L500.083 351.78L572.573 395.076V395.288L548.176 543.747H547.825ZM500.697 352.629L548 542.686L572.134 395.288L500.697 352.629Z" fill="white" fill-opacity="0.29" />
                        <path d="M548.264 544.065L547.913 543.853L392.929 469.783L393.192 469.571L500.523 351.78V352.098L548.352 543.959L548.264 544.065ZM393.631 469.677L547.738 543.216L500.26 352.523L393.631 469.571V469.677Z" fill="white" fill-opacity="0.29" />
                        <path d="M408.462 674.377L408.813 674.059L548.176 543.11L523.691 683.928H523.516L408.374 674.377H408.462ZM547.65 544.277L409.427 674.059L523.428 683.503L547.65 544.277Z" fill="white" fill-opacity="0.29" />
                        <path d="M374.497 902L407.32 838.754L428.382 902H428.733L407.583 838.117L407.407 837.692L374.059 902H374.497Z" fill="white" fill-opacity="0.29" />
                        <path d="M408.812 674.589L393.016 469.358H393.279L548.351 543.534L548.088 543.746L408.812 674.589ZM393.454 470.101L409.164 673.74L547.649 543.746L393.542 470.101H393.454Z" fill="white" fill-opacity="0.29" />
                        <path d="M500.171 352.947V352.205L500.961 120.868L501.224 121.08L595.478 195.575V195.787L500.083 352.947H500.171ZM501.312 121.717L500.522 351.568L594.952 195.787L501.312 121.823V121.717Z" fill="white" fill-opacity="0.29" />
                        <path d="M534.748 0L501.048 120.656L452.165 0H451.727L500.96 121.399L501.136 121.823L535.187 0H534.748Z" fill="white" fill-opacity="0.29" />
                        <path d="M244.612 847.136L245.051 846.712L409.251 673.74V674.271L407.671 838.541H407.495L244.7 847.242L244.612 847.136ZM408.812 674.695L245.665 846.606L407.144 838.01L408.724 674.695H408.812Z" fill="white" fill-opacity="0.29" />
                        <path d="M264.622 902L245.403 847.031L407.057 838.436L374.059 902H374.498L407.496 838.329L407.759 837.905L245.139 846.607H244.876L264.183 902H264.622Z" fill="white" fill-opacity="0.29" />
                        <path d="M393.192 470.101V469.782L340.185 334.801H340.536L500.786 351.886L500.435 352.204L393.192 469.995V470.101ZM340.799 335.438L393.28 469.358L499.82 352.31L340.799 335.331V335.438Z" fill="white" fill-opacity="0.29" />
                        <path d="M500.523 352.948L500.172 352.311L371.252 129.146H371.603L501.313 120.975V121.187L500.523 352.842V352.948ZM371.954 129.57L500.172 351.462L500.962 121.505L371.954 129.57Z" fill="white" fill-opacity="0.29" />
                        <path d="M340.271 335.438V335.226L371.426 128.827L371.69 129.252L500.697 352.523H500.258L340.271 335.438ZM371.69 129.994L340.71 335.014L499.907 351.992L371.602 129.994H371.69Z" fill="white" fill-opacity="0.29" />
                        <path d="M409.164 674.589L408.813 674.377L267.87 578.765L393.28 469.358V469.782L409.076 674.589H409.164ZM268.66 578.659L408.725 673.74L393.016 470.101L268.572 578.659H268.66Z" fill="white" fill-opacity="0.29" />
                        <path d="M244.876 847.455V846.925L268.132 578.447L268.396 578.659L409.339 674.271L409.163 674.484L244.964 847.562L244.876 847.455ZM268.483 579.19L245.403 846.394L408.637 674.378L268.483 579.296V579.19Z" fill="white" fill-opacity="0.29" />
                        <path d="M451.728 0L500.786 121.08L371.779 129.145L400.389 0H400.037L371.428 129.251L371.34 129.57L501.137 121.505H501.401L452.167 0H451.728Z" fill="white" fill-opacity="0.29" />
                        <path d="M227.412 902L245.139 847.455L264.183 902H264.622L245.315 846.713L245.139 846.288L227.061 902H227.412Z" fill="white" fill-opacity="0.29" />
                        <path d="M268.132 579.19V578.765L267.255 490.9H267.43L393.191 469.57L393.366 469.995L268.132 579.19ZM267.606 491.324L268.484 578.341L392.489 470.207L267.606 491.431V491.324Z" fill="white" fill-opacity="0.29" />
                        <path d="M267.079 491.43L340.534 334.694L340.71 335.119L393.542 469.994H393.278L267.079 491.43ZM340.447 335.755L267.781 490.899L392.927 469.676L340.447 335.755Z" fill="white" fill-opacity="0.29" />
                        <path d="M186.604 238.552L186.78 238.021L244.175 49.4502H244.351L371.954 129.357L371.603 129.569L186.517 238.552H186.604ZM244.351 50.0869L187.219 237.703L371.076 129.357L244.351 50.0869Z" fill="white" fill-opacity="0.29" />
                        <path d="M306.748 0L371.076 128.827L244.526 49.663L262.867 0H262.429L244.087 49.663L243.999 49.8753L372.041 129.888L307.186 0H306.748Z" fill="white" fill-opacity="0.29" />
                        <path d="M168.438 902L244.79 847.349L227.062 902H227.413L245.492 846.288L167.648 902H168.438Z" fill="white" fill-opacity="0.29" />
                        <path d="M245.053 847.031L57.8604 707.91L58.0359 707.698L122.891 638.616V638.828L245.229 846.712L244.965 847.031H245.053ZM58.5624 707.804L244.526 846.076L122.978 639.252L58.5624 707.804Z" fill="white" fill-opacity="0.29" />
                        <path d="M245.315 847.455L245.052 846.924L122.714 638.722H122.977L268.659 578.341V578.659L245.403 847.349L245.315 847.455ZM123.24 639.04L244.964 846.182L268.045 579.084L123.153 639.04H123.24Z" fill="white" fill-opacity="0.29" />
                        <path d="M340.624 335.544H340.36L186.429 238.128L371.691 128.933L340.536 335.544H340.624ZM187.306 238.128L340.272 334.801L371.252 129.676L187.306 238.022V238.128Z" fill="white" fill-opacity="0.29" />
                        <path d="M267.431 491.643L186.604 237.598L187.042 237.916L340.798 335.12V335.332L267.431 491.643ZM187.306 238.659L267.518 490.582L340.272 335.332L187.306 238.659Z" fill="white" fill-opacity="0.29" />
                        <path d="M400.036 0L371.514 128.827L307.186 0H306.747L371.426 129.464L371.602 129.888L400.387 0H400.036Z" fill="white" fill-opacity="0.29" />
                        <path d="M67.601 902L58.3862 708.229L244.789 846.819L167.648 902H168.438L245.228 847.031L245.491 846.819L58.0352 707.38L67.25 902H67.601Z" fill="white" fill-opacity="0.29" />
                        <path d="M267.781 491.537L111.305 423.622L186.954 237.598L187.13 238.128L267.781 491.537ZM111.831 423.41L267.167 490.794L186.954 238.765L111.831 423.41Z" fill="white" fill-opacity="0.29" />
                        <path d="M122.978 639.146L122.803 638.722L267.607 490.582L268.485 578.871H268.397L122.978 639.146ZM267.256 491.537L123.856 638.297L268.134 578.553L267.256 491.537Z" fill="white" fill-opacity="0.29" />
                        <path d="M122.801 639.359V638.934L111.393 423.091H111.656L267.869 491.006L267.606 491.218L122.801 639.359ZM111.744 423.728L123.065 638.403L266.992 491.112L111.656 423.728H111.744Z" fill="white" fill-opacity="0.29" />
                        <path d="M123.153 639.358L122.802 639.04L6.7832 543.64L6.95872 543.428L111.744 422.984V423.515L123.153 639.464V639.358ZM7.39753 543.64L122.715 638.509L111.393 424.046L7.39753 543.64Z" fill="white" fill-opacity="0.29" />
                        <path d="M67.2496 902H67.6007L58.3858 707.805V707.38L-0.0625 757.786V758.317L58.0348 708.229L67.2496 902Z" fill="white" fill-opacity="0.29" />
                        <path d="M36.8846 902L-0.0625 826.763V827.718L36.4458 902H36.8846Z" fill="white" fill-opacity="0.29" />
                        <path d="M186.956 238.764L112.623 4.35059H112.974L244.527 49.5567V49.7689L186.956 238.764ZM113.237 5.09341L186.956 237.491L244 49.875L113.237 5.09341Z" fill="white" fill-opacity="0.29" />
                        <path d="M262.429 0L244.175 49.4508L113.588 4.77529L127.98 0H126.576L112.886 4.56306L112.184 4.77529L244.263 49.9814H244.351L262.868 0H262.429Z" fill="white" fill-opacity="0.29" />
                        <path d="M126.576 0L113.061 4.45694L113.237 0H112.798L112.71 4.77529V5.09365L127.98 0H126.576Z" fill="white" fill-opacity="0.29" />
                        <path d="M111.569 423.94L111.393 423.622L7.39746 224.969H7.7485L187.218 237.916V238.234L111.569 423.94ZM8.09954 225.5L111.569 422.985L186.692 238.34L8.09954 225.5Z" fill="white" fill-opacity="0.29" />
                        <path d="M36.8846 902L-0.0625 826.868V827.717L36.4458 902H36.8846Z" fill="white" fill-opacity="0.29" />
                        <path d="M7.39746 225.5L7.57298 225.182L112.973 4.245L187.218 238.447H186.955L7.39746 225.5ZM112.885 5.30617L8.01178 225.076L186.604 237.916L112.885 5.30617Z" fill="white" fill-opacity="0.29" />
                        <path d="M58.1228 708.229V707.911L6.69531 543.004L123.241 638.934L123.065 639.146L58.1228 708.229ZM7.48516 544.277L58.2984 707.486L122.627 639.04L7.48516 544.277Z" fill="white" fill-opacity="0.29" />
                        <path d="M7.6604 224.757L-0.0625 253.727V255.319L7.74816 225.818L111.13 423.091L-0.0625 379.689V380.113L111.481 423.727L112.007 423.94L7.6604 224.757Z" fill="white" fill-opacity="0.29" />
                        <path d="M7.2216 543.535L7.04608 542.792L-0.0625 596.275V599.14L7.13384 544.49L57.947 707.805L-0.0625 757.786V758.317L58.2981 708.017L58.4736 707.911L7.2216 543.535Z" fill="white" fill-opacity="0.29" />
                        <path d="M-0.0625 379.689V380.114L111.218 423.622L7.2216 543.216L-0.0625 509.153V511.063L6.87056 543.747L6.95832 544.065L111.656 423.622L111.92 423.41L-0.0625 379.689Z" fill="white" fill-opacity="0.29" />
                        <path d="M-0.0625 31.729V32.1535L112.534 5.09349L7.74816 224.757L-0.0625 208.945V209.794L7.57264 225.394L7.74816 225.712L113.06 4.88126L113.236 4.45679L-0.0625 31.729Z" fill="white" fill-opacity="0.29" />
                        <path d="M112.798 0L112.71 4.2447L108.937 0H108.41L112.798 4.88141L113.061 5.30588L113.237 0H112.798Z" fill="white" fill-opacity="0.29" />
                        <path d="M-0.0625 542.897V543.428L6.87056 543.852L-0.0625 596.275V599.14L7.30936 543.64V543.428L-0.0625 542.897Z" fill="white" fill-opacity="0.29" />
                        <path d="M-0.0625 509.152V511.062L6.87056 543.428L-0.0625 542.897V543.428L7.04608 543.852H7.30936L-0.0625 509.152Z" fill="white" fill-opacity="0.29" />
                        <path d="M108.936 0H108.409L112.534 4.56306L-0.0625 31.7292V32.1536L112.973 4.98753L113.324 4.88141L108.936 0Z" fill="white" fill-opacity="0.29" />
                        <path d="M-0.0625 223.59V224.014L7.48488 225.394L-0.0625 253.727V255.319L7.92368 225.288L8.01144 225.076L-0.0625 223.59Z" fill="white" fill-opacity="0.29" />
                        <path d="M-0.0625 208.946V209.795L7.39712 224.969L-0.0625 223.59V224.014L7.6604 225.5L8.09921 225.606L-0.0625 208.946Z" fill="white" fill-opacity="0.29" />
                        </svg>
                    </span>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
</main>
@endsection
