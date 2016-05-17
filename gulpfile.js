/* -- INCLUSION DES MODULES NPM -- */
/**
 * Inclus Gulp (la base sans quoi rien ne marche)
 */
var gulp = require('gulp');
/**
 * Ajoute les préfixes constructeur qui vont bien sur les propriétés CSS
 * https://www.npmjs.com/package/gulp-autoprefixer
 */
var autoprefixer = require('gulp-autoprefixer');
/**
 * Compile les fichiers .scss en .css (utilise libSASS)
 * https://www.npmjs.com/package/gulp-sass
 */
var rubySass = require('gulp-ruby-sass');
/**
 * Permet de lier le code des CSS à leur fichier source SCSS
 * https://www.npmjs.com/package/gulp-sourcemaps
 */
var sourcemaps = require('gulp-sourcemaps');
/**
 * Permet de surveiller la modification des fichiers
 * https://www.npmjs.com/package/gulp-watch
 */
var watch = require('gulp-watch');

/**
 * Parcours les pages pour remplacer les scripts/styles par des versions minifiées
 * https://www.npmjs.com/package/gulp-useref
 */
var useref = require('gulp-useref');
/**
 * Permet de compresser l'HTML
 * https://www.npmjs.com/package/gulp-minify-html
 */
var minifyHtml = require('gulp-minify-html');
/**
 * Permet de compresser les CSS
 * https://www.npmjs.com/package/gulp-minify-css
 */
var minifyCss = require('gulp-minify-css');
/**
 * Détecte d'éventuelles erreurs dans les JS
 * https://www.npmjs.com/package/gulp-jshint
 */
var jshint = require('gulp-jshint');
/**
 * Permet de compresser les JS
 * https://www.npmjs.com/package/gulp-uglify
 */
var uglify = require('gulp-uglify');
/**
 * Permet de renommer des fichiers
 * https://www.npmjs.com/package/gulp-rename
 */
var rename = require('gulp-rename');
/**
 * Permet la mise en cache de fichiers
 * https://www.npmjs.com/package/gulp-cache
 */
var cache = require('gulp-cache');
/**
 * Optimise les PNG, JPEG, GIF et SVG
 * https://www.npmjs.com/package/gulp-imagemin
 */
var imagemin = require('gulp-imagemin');
/**
 * Ecrit la taille du projet
 * https://www.npmjs.com/package/gulp-size
 */
var size = require('gulp-size');
/**
 * Création des sprites
 * https://www.npmjs.com/package/gulp.spritesmith
 */
var spritesmith = require('gulp.spritesmith');
/**
 * Permet l'actualisation automatique du navigateur
 * https://www.npmjs.com/package/browser-sync
 */
var browserSync = require('browser-sync');
var reload = browserSync.reload;

/* -- CONFIGURATION -- */
var siteUrl = 'http://briiicks.dev';
var pathScss = 'public/assets/scss';
var pathCss = 'public/assets/css';
var pathJs = 'public/assets/js';
var pathLibrairies = 'public/assets/librairies';
var pathImg = 'public/assets/img';
// Navigateurs que l'on veut supporter
var autoprefixerBrowsers = [
  'ie >= 8',
  'ie_mob >= 10',
  'ff >= 30',
  'chrome >= 34',
  'safari >= 7',
  'opera >= 23',
  'ios >= 7',
  'android >= 4.4',
  'bb >= 10'
];

/* -- TACHES -- */

/**
 * Tâche par défaut, exécute la tâche qui permet l'actualisation automatique du navigateur
 */
gulp.task('default', ['serve']);

/**
 * Actualisation auto du navigateur
 */
gulp.task('serve', ['sass'], function() {
  // Lance le serveur
  browserSync({
      // server: "./" // Lance un serveur statique (html, pas de php)
      proxy: siteUrl // Redirige vers un serveur existant (php ok)
  });
  // Compile les fichier SCSS quand ils sont modifiés
  gulp.watch('dev/' + pathScss + '/**/*.scss', ['sass']);
  // Traque les erreurs JS
  // gulp.watch(['dev/' + pathJs + '/**/*.js'], ['jshint']);
  // Actualise la page quand on modifie des fichier HTML/PHP
  gulp.watch(['dev/*.html', 'dev/*.php']).on('change', reload);
});

/**
 * Compile le SCSS en CSS et actualise le navigateur
 */
gulp.task('sass', function() {
  // Chemin vers les fichiers SCSS
  return rubySass('dev/' + pathScss + '/', { sourcemap: true })
    .on('error', function (err) {
      console.error('Error!', err.message);
    })
    // Préfixe les propriétés CSS
    .pipe(autoprefixer({browsers: autoprefixerBrowsers, map: true}))
    // Ecrit les sources SCSS
    .pipe(sourcemaps.write())
    // Ecrit les fichiers CSS
    .pipe(gulp.dest('dev/' + pathCss + '/'))
    // Actualise la page
    .pipe(reload({stream: true}));
});

/**
 * Traque les erreurs JS
 */
gulp.task('jshint', function () {
  return gulp.src('dev/' + pathJs + '/**/*.js')
    .pipe(reload({stream: true, once: true}))
    .pipe(jshint())
    .pipe(jshint.reporter('default'));
});

/**
 * Regroupe les images dans un sprite
 */
gulp.task('sprite', function() {
  // Chemin vers les images
  var spriteData = gulp.src('dev/' + pathImg + '/**/*.png')
	.pipe(spritesmith({
		imgName: 'sprite.png',
		cssName: '_sprite.scss',
		cssTemplate: 'public/assets/tools/_sprite.scss.handlebars'
	}));

	spriteData.img
	.pipe(gulp.dest('dev/' + pathImg))

	spriteData.css
	.pipe(gulp.dest('dev/' + pathScss))

});

/**
 * Prépare les fichiers avant mise en prod
 * - Minifie les fichiers CSS
 * - Minifie les fichiers JS
 */
gulp.task('prod', ['minify-css', 'minify-js'], function() {});

/**
 * Minifie les fichiers HTML
 */
gulp.task('minify-html', function () {
    gulp.src(['dev/app/views/**/*.php']) // path to your files
    .pipe(useref()) // remplace les appels aux scripts/styles par les versions .min
    .pipe(minifyHtml())
    .pipe(gulp.dest('prod/app/views'));
});

/**
 * Minifie les fichiers CSS
 */
gulp.task('minify-css', function() {
  // Chemin vers les fichiers SCSS
  return gulp.src('dev/' + pathCss + '/**/*.css')
  	// Crée des nouveaux fichiers avec un suffixe
		// .pipe(rename({suffix: '.min'}))
		// Minifie ces CSS
		.pipe(minifyCss())
		// Ecrit les fichiers CSS
		.pipe(gulp.dest('prod/' + pathCss))
});

/**
 * Minifie les fichiers JS
 */
gulp.task('minify-js', function() {
  // Chemin vers les fichiers JS
  return gulp.src('dev/' + pathJs + '/**/*.js')
    // Crée des nouveaux fichiers avec un suffixe
    // .pipe(rename({suffix: '.min'}))
    // Minifie ces JS
    .pipe(uglify({preserveComments: 'some'}))
    // Ecrit les fichiers JS
    .pipe(gulp.dest('prod/' + pathJs))
});

/**
 * Minifie les fichiers JS des librairies
 */
gulp.task('minify-librairies', function() {
  // Chemin vers les fichiers JS des librairies
  return gulp.src('dev/' + pathLibrairies + '/*.js')
    // Crée des nouveaux fichiers avec un suffixe
    // .pipe(rename({suffix: '.min'}))
    // Minifie ces JS
    .pipe(uglify({preserveComments: 'some'}))
    // Ecrit les fichiers JS
    .pipe(gulp.dest('prod/' + pathLibrairies))
});

/**
 * Optimise les images
 */
gulp.task('minify-img', function () {
	// Chemin vers les images
  return gulp.src('dev/' + pathImg + '/**/*')
  	// Ecrit la taille des images dans la console
    .pipe(size({title: 'Images avant optimisation :'}))
  	// Optimisation des images
    .pipe(cache(imagemin({
    	// JPG : Rendu progressif
      progressive: true,
      // GIF : Interlacement pour rendu progressif
      interlaced: true
    })))
    // Ecrit les images
    .pipe(gulp.dest('prod/' + pathImg))
    // Ecrit la taille des images dans la console
    .pipe(size({title: 'Images après optimisation :'}));
});