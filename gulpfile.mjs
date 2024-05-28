import gulp from "gulp";
import browserSync from "browser-sync";
import * as sass from "sass";
import gulpSass from "gulp-sass";
import autoprefixer from "gulp-autoprefixer";
import sourcemaps from "gulp-sourcemaps";
import cleanCSS from "gulp-clean-css";
import zip from "gulp-zip";
import sftp from "gulp-sftp-up4";
import { config } from "dotenv";

// Charger les variables d'environnement
config();

const gulpSassInstance = gulpSass(sass);

// Chemins vers vos fichiers
const paths = {
  styles: {
    src: "sass/**/*.scss",
    dest: "./", // Destination des fichiers CSS compilés
  },
  php: {
    src: "**/*.php",
  },
  theme: {
    src: [
      "**/*",
      "!node_modules/**",
      "!dist/**",
      "!gulpfile.mjs",
      "!package.json",
      "!package-lock.json",
    ],
    dest: "dist/",
  },
};

// Tâche pour compiler les fichiers Sass
function style() {
  return gulp
    .src(paths.styles.src)
    .pipe(sourcemaps.init())
    .pipe(gulpSassInstance().on("error", gulpSassInstance.logError))
    .pipe(autoprefixer())
    .pipe(sourcemaps.write("./"))
    .pipe(gulp.dest(paths.styles.dest)) // Génère le fichier main.css
    .pipe(browserSync.stream());
}

// Tâche pour minifier le CSS
function minifyCSS() {
  return gulp
    .src("./main.css")
    .pipe(cleanCSS({ compatibility: "ie8" }))
    .pipe(gulp.dest(paths.styles.dest));
}

// Tâche pour zipper le thème
function zipTheme() {
  return gulp
    .src(paths.theme.src)
    .pipe(zip("theme.zip"))
    .pipe(gulp.dest(paths.theme.dest));
}

// Tâche pour déployer le thème
function deploy() {
  return gulp.src("dist/theme.zip").pipe(
    sftp({
      host: process.env.SFTP_HOST,
      user: process.env.SFTP_USER,
      pass: process.env.SFTP_PASS,
      remotePath: process.env.SFTP_PATH,
    })
  );
}

// Tâche pour démarrer BrowserSync et surveiller les fichiers
function serve() {
  browserSync.init({
    proxy: "http://localhost/fcjmp2024", // Changez 'fcjmp2024' par l'URL locale de votre site WordPress
  });

  gulp.watch(paths.styles.src, gulp.series(style, minifyCSS));
  gulp.watch(paths.php.src).on("change", browserSync.reload);
}

// Tâche par défaut
export default gulp.series(style, minifyCSS, serve);
export const build = gulp.series(style, minifyCSS, zipTheme);
export const deployTheme = gulp.series(build, deploy);
