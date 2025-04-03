SASS Watch command

sass --watch assets/theme/global/sass/main.scss:assets/theme/global/css/main.css

<!-- With Prefix -->

sass --watch assets/theme/global/sass/main.scss:assets/theme/global/css/main.css && postcss assets/theme/global/css/main.css --replace --use autoprefixer
