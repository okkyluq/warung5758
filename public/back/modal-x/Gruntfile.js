module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    jshint: {
      files: ['Gruntfile.js', 'src/**/*.js'],
      options: {
        globals: {
          jQuery: true
        }
      }
    },

    watch: {
      scripts: {
        files: ['src/js/<%= pkg.name %>.js', 'src/js/refactor.js'],
        tasks: ['jshint', 'uglify'],
        options: {
          spawn: false,
        },
      },

      css: {
        files: ['src/scss/*.scss', 'src/scss/**/*.scss'],
        tasks: ['sass']
      }
    },
    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> by Vasco Gaspar */\n/*! Last Updated: <%= grunt.template.today("yyyy-mm-dd") %> */\n'
      },
      build: {
        src: 'src/js/<%= pkg.name %>.js',
        dest: 'dist/js/<%= pkg.name %>.min.js'
      }
    },

    sass: {
      options: {
        style: 'compressed',
        precision: 5
      },
      dist: {
        files: {
          'dist/css/<%= pkg.name %>.css': 'src/scss/<%= pkg.name %>.scss'
        }
      }
    }
  });

  // Load the plugin that provides the "uglify" task.
  grunt.loadNpmTasks('grunt-contrib-uglify');

  // Compile SCSS Files to CSS
  grunt.loadNpmTasks('grunt-contrib-sass');

  // Check for JS code quality
  grunt.loadNpmTasks('grunt-contrib-jshint');

  // Whatch for file changes and run default tasks
  grunt.loadNpmTasks('grunt-contrib-watch');

  // Default task(s).
  grunt.registerTask('default', ['uglify', 'sass', 'jshint']);

};
