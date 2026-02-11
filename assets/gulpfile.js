var gulp	 	 = require('gulp'),
	fs 			 = require('fs'),
	pkg 		 = JSON.parse(fs.readFileSync('package.json')),
	sass		 = require('gulp-sass'),				
	concat		 = require('gulp-concat'),
	uglify		 = require('gulp-uglify'),			
	cleanCSS 	 = require('gulp-clean-css'),	
	rename	 	 = require('gulp-rename'),			
	plumber  	 = require('gulp-plumber'),	
	postcss  	 = require('gulp-postcss'),
	pxtorem  	 = require('postcss-pxtorem'),	
	autoprefixer = require('autoprefixer'),	
	babel   	 = require('gulp-babel'),
	replace = require('gulp-replace'),
	prName   	 = pkg.name;

gulp.task('css', function() {
	gulp.src('scss/style.scss')
		.pipe(sass().on('error', sass.logError))
		.pipe(plumber())		
		.pipe(postcss([
			autoprefixer({
				overrideBrowserslist: ['last 10 versions', '> 5%', 'Firefox ESR']
			}),
			pxtorem({
			    rootValue: 10,
			    //unitPrecision: 5,
			    propList: ['*', '!letter-spacing', '!max-width', '!min-width', '!max-height', '!min-height', '!border-radius', '!box-shadow'],
			    selectorBlackList: ['html', 'col-', '-grid-', '/.container$/', '/.row$/', 'add-gutter-bottom'],
			    replace: false,
    			mediaQuery: false,
			    minPixelValue: 3,		
			}),
		]))
	    .pipe(replace('PX', 'px'))
		.pipe(cleanCSS({debug: true}, function(details) {
	      console.log(details.name + ': ' + details.stats.originalSize + ' b');
	      console.log(details.name + ': ' + details.stats.minifiedSize + ' b');
	    }))	
	    .pipe(rename(prName + '.min.css'))
		.pipe(gulp.dest('css'));
});


gulp.task('js', function () {
  return gulp.src(['js/source/*.js', 'js/source//**/*.js'])
    .pipe(babel())
    .pipe(uglify())
	.pipe(plumber())
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('js'));
});

gulp.task('js_concat', function () {
  return gulp.src([
	    'js/vendor/popper.min.js',
	    'js/lib/bootstrap/util.js',
	    'js/lib/bootstrap/dropdown.js',
	    'js/lib/bootstrap/modal.js',   
  		'js/lib/bootstrap/tab.js', 
	    //'js/lib/bootstrap/collapse.js', 
	    //'js/lib/bootstrap/bootstrap-modal-ios.js',   
	    'bower_components/slick-carousel/slick/slick.js',
	    'bower_components/fancyBox/source/jquery.fancybox.js',
	    'bower_components/jquery-placeholder/jquery.placeholder.min.js', 
	    'bower_components/jquery-slinky/dist/slinky.min.js',
	    'bower_components/inputmask/dist/jquery.inputmask.bundle.js',
	    'bower_components/blockUI/jquery.blockUI.js',
	    'bower_components/jquery-validation/dist/jquery.validate.min.js',  
	    'bower_components/jQueryFormStyler/dist/jquery.formstyler.js',
	    'bower_components/sticky-kit/jquery.sticky-kit.min.js'
	  ])
    .pipe(concat(pkg.name + '.lib.js'))
	.pipe(plumber())
    .pipe(uglify())
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('js'));
});



// Наблюдение
gulp.task('watch', function() {
	// SCSS
	gulp.watch(['scss/**/*.sass', 'scss/**/*.scss','scss/*.sass', 'scss/*.scss'], function() {
		gulp.start('css');
	});

	// js
	gulp.watch(['js/source/*', 'js/source//**/*'], function () {
		gulp.start('js');
	});
});

// Дефолтный таск
gulp.task('default', ['watch', 'css', 'js', 'js_concat']);
