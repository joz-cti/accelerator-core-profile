# Project template for the GLA Accelerator (alpha)

This repository serves as a Composer-based installer for the GLA Accelerator and
will also provide the core profile/distribution.

It is based on
[drupal/core-recommended](https://github.com/drupal/core-recommended) and takes
inspiration from
[localgovdrupal/localgov-project](https://github.com/localgovdrupal/localgov_project)
and [localgovdrupal/localgov](https://github.com/localgovdrupal/localgov), as
well as CTI's Voyager Drupal profile.

## Creating a new GLA site using this template

To create a new project called `my-project` using this template:

```bash
composer create-project gla/accelerator_core_alpha my-project
cd my-project
# Then follow the 'Initial post-project creation steps'
# instructions in the new README.md which will have been
# created for you automatically.
```

## Working on the Accelerator directly (draft process)

This workflow is likely to improve with time but for now we have included a
script to use the Composer project locally in order to create a Drupal project
that can then be used as a local development environment.

```bash
# This will take all committed changes to the `main` branch and use it
# to create a new Drupal project in the ~/Sites/my-project directory.
./scripts/composer-create-project-locally.sh main ~/Sites/my-project
```

Once you have a working Drupal site in `my-project` then you may wish to set up
symlinks to use local copies of some packages that Composer will (once we build
them) have installed for you (e.g. the theme or a feature module). This will
allow you to test local changes more easily.

We can do that by following this process:
https://fetzi.dev/developing-composer-packages-locally (but don't commit the changes). In the future, we can
add [JQ](https://stedolan.github.io/jq) inside Lando, then use this script to
automate the process:
https://substrakt.com/journal/easy-peasy-composer-local-package-symlink-composer-link.

## Using Composer scripts

All build pre/post build commands and patches are to be triggered through
[Composer scripts](https://getcomposer.org/doc/articles/scripts.md#scripts) e.g.

```json
"scripts": {
    "pre-install-cmd": [
        "bash scripts/server/composer-pre-install.sh"
    ],
    "post-install-cmd": [
        "bash scripts/server/composer-post-install.sh"
    ]
}
```
