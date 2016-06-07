var gulp         = require('gulp');
var clean        = require('del');
var concat       = require('gulp-concat');
var less         = require('gulp-less');
var minifycss    = require('gulp-clean-css');
var autoprefixer = require('gulp-autoprefixer');
var uglify       = require('gulp-uglify');
var notify		 = require('gulp-notify');

/* DIRs */
var bowerDir     = 'bower_components';
var mediaDir     = 'code/media/mediaupload';
var assetsDir    = mediaDir + '/assets';
var lessDir      = assetsDir + '/less';
var jsDir        = assetsDir + '/js';
var targetCss    = mediaDir + '/css';
var targetJs     = mediaDir + '/js';
var targetFont   = mediaDir + '/fonts';
/***************/

/* INIT */

gulp.task('clean', function() {
	gulp.src([assetsDir + '/css'], {read:false})
		.pipe(clean());
});

/* copy files */
gulp.task('copyFiles', function() {

	gulp.src(assetsDir + '/images/*')
		.pipe(gulp.dest(mediaDir + '/images'));
	gulp.src(bowerDir + '/blueimp-file-upload/img/*')
		.pipe(gulp.dest(mediaDir + '/images'));

	gulp.src(bowerDir + '/blueimp-file-upload/css/jquery.fileupload.css')
		.pipe(gulp.dest(assetsDir + '/css'));

});
/***************/

/* PRODUCTION */

var scripts = [
    bowerDir + '/blueimp-file-upload/js/vendor/jquery.ui.widget.js',
    bowerDir + '/blueimp-file-upload/js/jquery.iframe-transport.js',
    bowerDir + '/blueimp-file-upload/js/jquery.fileupload.js',
    assetsDir + '/js/upload.js'
];

gulp.task('mergeScripts', function() {
	gulp.src(scripts)
		.pipe(concat('mediaupload.js'))
		.pipe(uglify())
		.pipe(gulp.dest(targetJs));
});



gulp.task('css', function(){
	return gulp.src(assetsDir + '/less/main.less')
			.pipe(less())
			.pipe(minifycss())
			.pipe(autoprefixer('last 20 version'))
			.pipe(gulp.dest(targetCss));
});
/***************/

/* DEVELOPMENT */

gulp.task('cssdev', function(){
	return gulp.src('assets/less/main.less')
			.pipe(less())
			.on('error',function (err) {
				console.log(err.toString());
				this.emit('end');
			})	
			.pipe(autoprefixer('last 20 version'))
			.pipe(notify('cssdev done'))
			.pipe(gulp.dest(targetCss));
});

gulp.task('mergeScriptsDev', function() {
	gulp.src(scripts)
		.pipe(concat('mediaupload.js'))
		.pipe(notify('mergejsdev done'))
		.pipe(gulp.dest(targetJs));
});

gulp.task('dev', function(){
	gulp.start(['cssdev', 'mergeScriptsDev']);
});

gulp.task('watch',function (){
    gulp.watch(assetsDir + '/less/**/*.less', ['cssdev']);
    gulp.watch(assetsDir + '/js/**/*.js', ['mergeScriptsDev']);
});

gulp.task('default', function(){
	gulp.start(['css', 'mergeScripts']);
});
