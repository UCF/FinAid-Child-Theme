/* global Buffer */

const fs           = require('fs');
const browserSync  = require('browser-sync').create();
const gulp         = require('gulp');
const autoprefixer = require('gulp-autoprefixer');
const cleanCSS     = require('gulp-clean-css');
const include      = require('gulp-include');
const eslint       = require('gulp-eslint');
const isFixed      = require('gulp-eslint-if-fixed');
const babel        = require('gulp-babel');
const rename       = require('gulp-rename');
const sass         = require('gulp-dart-sass');
const sassLint     = require('gulp-sass-lint');
const tap          = require('gulp-tap');
const css          = require('css');
const uglify       = require('gulp-uglify');
const merge        = require('merge');


let config = {
  src: {
    scssPath: './src/scss',
    jsPath: './src/js'
  },
  dist: {
    cssPath: './static/css',
    jsPath: './static/js',
    fontPath: './static/fonts'
  },
  devPath: './dev',
  packagesPath: './node_modules',
  sync: false,
  syncTarget: 'http://localhost/wordpress/'
};

/* eslint-disable no-sync */
if (fs.existsSync('./gulp-config.json')) {
  const overrides = JSON.parse(fs.readFileSync('./gulp-config.json'));
  config = merge(config, overrides);
}
/* eslint-enable no-sync */


//
// Helper functions
//

// Base SCSS linting function
function lintSCSS(src) {
  return gulp.src(src)
    .pipe(sassLint())
    .pipe(sassLint.format())
    .pipe(sassLint.failOnError());
}

// Base SCSS compile function
function buildCSS(src, dest) {
  dest = dest || config.dist.cssPath;

  return gulp.src(src)
    .pipe(sass({
      includePaths: [config.src.scssPath, config.packagesPath]
    })
      .on('error', sass.logError))
    .pipe(cleanCSS())
    .pipe(autoprefixer({
      // Supported browsers added in package.json ("browserslist")
      cascade: false
    }))
    .pipe(rename({
      extname: '.min.css'
    }))
    .pipe(gulp.dest(dest));
}

// Base JS linting function (with eslint). Fixes problems in-place.
function lintJS(src, dest) {
  dest = dest || config.src.jsPath;

  return gulp.src(src)
    .pipe(eslint({
      fix: true
    }))
    .pipe(eslint.format())
    .pipe(isFixed(dest));
}

// Base JS compile function
function buildJS(src, dest) {
  dest = dest || config.dist.jsPath;

  return gulp.src(src)
    .pipe(include({
      includePaths: [config.packagesPath, config.src.jsPath]
    }))
    .on('error', console.log) // eslint-disable-line no-console
    .pipe(babel())
    .pipe(uglify())
    .pipe(rename({
      extname: '.min.js'
    }))
    .pipe(gulp.dest(dest));
}

// BrowserSync reload function
function serverReload(done) {
  if (config.sync) {
    browserSync.reload();
  }
  done();
}

// BrowserSync serve function
function serverServe(done) {
  if (config.sync) {
    browserSync.init({
      proxy: {
        target: config.syncTarget
      }
    });
  }
  done();
}

// Removes Athena-specific styles.  Leaves only selectors and media queries
// with selectors that begin with the provided selector string in
// `allowedSelector`.
//
// NOTE: This function will strip out any at-rules besides @media--if custom
// @import, @keyframe, etc rules ever need to be added to a minified css file
// created with this function, the function will need to be updated!
function filterAthenaCSS(file, allowedSelector) {
  const cssObj = css.parse(file.contents.toString());

  if (cssObj) {
    const rules = cssObj.stylesheet.rules;
    const filteredRules = [];

    // Loop through every rule. Store valid rules in filteredRules.
    for (let i = 0; i < rules.length; i++) {
      const rule = rules[i];
      let filteredSelectors = [];

      if (rule.type === 'rule') {
        // Check each selector in the rule for selectors we want to keep.
        // If a selector in the rule's selector list matches, add it to
        // filteredSelectors.
        filteredSelectors = getFilteredSelectors(rule, filteredSelectors, allowedSelector);

        // Check the rule's filteredSelectors array; if it's not empty, add
        // the rule to filteredRules.
        if (filteredSelectors.length) {
          rule.selectors = filteredSelectors;
          filteredRules.push(rule);
        }
      } else if (rule.type === 'media') {
        const filteredSubnodes = [];

        for (let k = 0; k < rule.rules.length; k++) {
          const subnode = rule.rules[k];
          let filteredSubnodeSelectors = [];

          if (subnode.type === 'rule') {
            // Check each selector in the rule for selectors we want to keep.
            // If a selector in the rule's selector list matches, add it to
            // filteredSubnodeSelectors.
            filteredSubnodeSelectors = getFilteredSelectors(subnode, filteredSubnodeSelectors, allowedSelector);

            // Check the rule's filteredSubnodeSelectors array; if it's not empty, add
            // the rule to filteredSubnodes.
            if (filteredSubnodeSelectors.length) {
              subnode.selectors = filteredSubnodeSelectors;
              filteredSubnodes.push(subnode);
            }
          }
        }

        if (filteredSubnodes.length) {
          rule.rules = filteredSubnodes;
          filteredRules.push(rule);
        }
      }
    }

    // Finally, replace cssObj's old ruleset with our filtered one:
    cssObj.stylesheet.rules = filteredRules;

    // Return a buffer for gulp to continue with
    file.contents = Buffer.from(css.stringify(cssObj));
  } else {
    console.log('Couldn\'t parse CSS--skipping'); // eslint-disable-line no-console
  }
}

// Returns an array of filtered selectors present in a given node.
function getFilteredSelectors(node, filteredSelectors, allowedSelector) {
  for (let i = 0; i < node.selectors.length; i++) {
    const selector = node.selectors[i];
    if (selector.startsWith(allowedSelector)) {
      filteredSelectors.push(selector);
    }
  }
  return filteredSelectors;
}


//
// CSS
//

// Lint all theme scss files
gulp.task('scss-lint-theme', () => {
  return lintSCSS(`${config.src.scssPath}/*.scss`);
});

// Compile theme stylesheet
gulp.task('scss-build-theme', () => {
  return buildCSS(`${config.src.scssPath}/style.scss`);
});

// Compile admin stylesheet
gulp.task('scss-build-admin', () => {
  return buildCSS(`${config.src.scssPath}/admin.scss`);
});

gulp.task('scss-build-npc', () => {
  return gulp.src(`${config.devPath}/custom-pages/net-price-calculator.scss`)
    .pipe(sass({
      includePaths: [config.src.scssPath, config.packagesPath]
    })
      .on('error', sass.logError))
    .pipe(tap((file) => {
      return filterAthenaCSS(file, '.npc-app');
    }))
    .pipe(cleanCSS())
    .pipe(autoprefixer({
      // Supported browsers added in package.json ("browserslist")
      cascade: false
    }))
    .pipe(rename({
      extname: '.min.css'
    }))
    .pipe(gulp.dest(`${config.devPath}/custom-pages/`));
});

// Compile global editor stylesheet
gulp.task('scss-build-editor', () => {
  return buildCSS(`${config.src.scssPath}/editor.scss`);
});

// All theme css-related tasks
gulp.task('css', gulp.series('scss-lint-theme', 'scss-build-theme', 'scss-build-admin', 'scss-build-editor'));


//
// JavaScript
//

// Run eslint on js files in src.jsPath
gulp.task('es-lint-theme', () => {
  return lintJS([`${config.src.jsPath}/*.js`], config.src.jsPath);
});

// Concat and uglify js files through babel
gulp.task('js-build-theme', () => {
  return buildJS(`${config.src.jsPath}/script.js`, config.dist.jsPath);
});

// All js-related tasks
gulp.task('js', gulp.series('es-lint-theme', 'js-build-theme'));


//
// Rerun tasks when files change
//
gulp.task('watch', (done) => {
  serverServe(done);

  gulp.watch(`${config.devPath}/custom-pages/net-price-calculator.scss`, gulp.series('scss-build-npc'));
  gulp.watch(`${config.src.scssPath}/**/*.scss`, gulp.series('css', serverReload));
  gulp.watch(`${config.src.jsPath}/**/*.js`, gulp.series('js', serverReload));
  gulp.watch('./**/*.php', gulp.series(serverReload));
});


//
// Default task
//
gulp.task('default', gulp.series('css', 'js'));
