SASS Watch command

sass --watch assets/theme/frontend/sass/main.scss:assets/theme/frontend/css/main.css

<!-- With Prefix -->

sass --watch assets/theme/frontend/sass/main.scss:assets/theme/frontend/css/main.css && postcss assets/theme/frontend/css/main.css --replace --use autoprefixer
