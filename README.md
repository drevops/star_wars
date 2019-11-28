# Star Wars
Drupal 8 implementation of Star Wars for Star Wars Org

[![CircleCI](https://circleci.com/gh/drevops/star_wars.svg?style=shield)](https://circleci.com/gh/drevops/star_wars)
![Drupal 8](https://img.shields.io/badge/Drupal-8-blue.svg)


[![Dependencies.io](https://img.shields.io/badge/dependencies.io-enabled-green.svg)](https://dependencies.io)


[//]: # (DO NOT REMOVE THE BADGE BELOW. IT IS USED BY DREVOPS TO TRACK INTEGRATION)

[![DrevOps](https://img.shields.io/badge/DrevOps-8.x--1.8-blue.svg)](https://github.com/drevops/drevops/tree/8.x-1.8) 

[//]: # (Remove the section below once onboarding is finished)
## Onboarding
Use [Onboarding checklist](ONBOARDING.md) to track the project onboarding progress. 

## Local environment setup
- Make sure that you have latest versions of all required software installed:
  - [Docker](https://www.docker.com/)
  - [Pygmy](https://pygmy.readthedocs.io/)
  - [Ahoy](https://github.com/ahoy-cli/ahoy)
- Make sure that all local web development services are shut down (Apache/Nginx, Mysql, MAMP etc).
- Checkout project repository (in one of the [supported Docker directories](https://docs.docker.com/docker-for-mac/osxfs/#access-control)).  


- Authenticate with Acquia Cloud API using:
  - Run `drush ac-login`<br/> 
  or 
  - Add Acquia Cloud credentials to `.env` file:

  ```
    # Acquia Cloud UI->Account->Credentials->Cloud API->E-mail
    AC_API_USER_NAME=<YOUR_USERNAME>
    # Acquia Cloud UI->Account->Credentials->Cloud API->Private key
    AC_API_USER_PASS=<YOUR_TOKEN>
  ```


- `ahoy download-db`
  
- `pygmy up`
- `ahoy build`

## Available `ahoy` commands
Run each command as `ahoy <command>`.
  ```  
  build                Build or rebuild project.
  clean                Remove containers and all build files.
  cli                  Start a shell or run a command inside the CLI service container.
  debug                Enable debug configuration.
  deploy               Run remote deployment procedures
  doctor               Find problems with current project setup.
  down                 Stop Docker containers and remove container, images, volumes and networks.
  download-db          Download database.
  drush                Run drush commands in the CLI service container.
  export-code          Export built code.
  export-db            Export database dump.
  fe                   Build front-end assets.
  fed                  Build front-end assets for development.
  few                  Watch front-end assets during development.
  flush-redis          Flush Redis cache.
  info                 Show information about this project.
  install-site         Install a site.
  lint                 Lint back-end and front-end code.
  lint-be              Lint back-end code.
  lint-fe              Lint front-end code.
  login                Login to a website.
  logs                 Show Docker logs for all or specified services.
  pull                 Pull latest docker images.
  reset                Reset environment: remove containers, all build, uncommitted files.
  restart              Restart all or specified stopped and running Docker containers.
  start                Start all or specified existing Docker containers.
  stop                 Stop all or specified running Docker containers.
  test                 Run all tests.
  test-bdd             Run BDD tests.
  test-functional      Run Simpletest functional tests.
  test-kernel          Run Simpletest kernel tests.
  test-unit            Run Simpletest unit tests.
  up                   Build and start all or specified Docker containers.
  update               Update development stack.
  ```

### Updating development stack

Development stack needs to be downloaded for each environment, but some files may be committed to the project repository.
Update process brings new versions of development stack files and may overwrite some of them. The changes in these files 
need to be reviewed and selectively committed. 

1. Start a new branch to make sure that your changes do not affect the main branch   
2. Run `ahoy update` to download the latest version of the development stack 
3. Review and commit changes 
4. Make sure that your CI build passes with updated development stack configuration
5. Merge your changes to the main branch    

## Adding Drupal modules

`composer require drupal/module_name`

## Adding patches for Drupal modules

1. Add `title` and `url` to patch on https://drupal.org to the `patches` array in `extra` section in `composer.json`.

```
    "extra": {
        "patches": {
            "drupal/core": {
                "Contextual links should not be added inside another link - https://www.drupal.org/node/2898875": "https://www.drupal.org/files/issues/contextual_links_should-2898875-3.patch"
            }
        }    
    }
```

2. `composer update --lock`

## Front-end and Livereload
- `ahoy fe` - build SCSS and JS assets.
- `ahoy fed` - build SCSS and JS assets for development.
- `ahoy few` - watch asset changes and reload the browser (using Livereload). To enable Livereload integration with Drupal, add to `settings.php` file (already added to `settings.local.php`): 
  ```
  $settings['livereload'] = TRUE;
  ```

## Coding standards
PHP and JS code linting uses [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) with Drupal rules from [Coder](https://www.drupal.org/project/coder) module and additional local overrides in `phpcs.xml` and `.eslintrc.json`.   

SASS and SCSS code linting use [Sass Lint](https://github.com/sasstools/sass-lint) with additional local overrides in `.sass-lint.yml`.

Set `ALLOW_LINT_FAIL=1` in `.env` to allow lint failures.

## Behat tests
Behat configuration uses multiple extensions: 
- [Drupal Behat Extension](https://github.com/jhedstrom/drupalextension) - Drupal integration layer. Allows to work with Drupal API from within step definitions.
- [Behat Screenshot Extension](https://github.com/integratedexperts/behat-screenshot) - Behat extension and a step definition to create HTML and image screenshots on demand or test fail.
- [Behat Progress Fail Output Extension](https://github.com/integratedexperts/behat-format-progress-fail) - Behat output formatter to show progress as TAP and fail messages inline. Useful to get feedback about failed tests while continuing test run.
- `FeatureContext` - Site-specific context with custom step definitions.

Add `@skipped` tag to failing tests if you would like to skip them.  

## Automated builds (Continuous Integration)
In software engineering, continuous integration (CI) is the practice of merging all developer working copies to a shared mainline several times a day. 
Before feature changes can be merged into a shared mainline, a complete build must run and pass all tests on CI server.

This project uses [Circle CI](https://circleci.com/) as a CI server: it imports production backups into fully built codebase and runs code linting and tests. When tests pass, a deployment process is triggered for nominated branches (usually, `master` and `develop`).

Add `[skip ci]` to the commit subject to skip CI build. Useful for documentation changes.

### SSH
Circle CI supports shell access to the build for 120 minutes after the build is finished when the build is started with SSH support. Use "Rerun job with SSH" button in Circle CI UI to start build with SSH support.

### Cache
Circle CI supports caching between builds. The cache takes care of saving the state of your dependencies between builds, therefore making the builds run faster.
Each branch of your project will have a separate cache. If it is the very first build for a branch, the cache from the default branch on GitHub (normally `master`) will be used. If there is no cache for master, the cache from other branches will be used.
If the build has inconsistent results (build fails in CI but passes locally), try to re-running the build without cache by clicking 'Rebuild without cache' button.

### Test artifacts
Test artifacts (screenshots etc.) are available under "Artifacts" tab in Circle CI UI.


## Deployment
Please refer to [DEPLOYMENT.md](DEPLOYMENT.md)



## Automated patching
[dependencies.io](https://dependencies.io) integration allows to keep the 
project up to date by automatically creating pull requests with updated 
dependencies on a daily basis. 


## FAQs
Please refer to [FAQs](FAQs.md)
