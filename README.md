# Project template for the GLA Accelerator (alpha)

This repository serves as a Composer-based installer for the Accelerator and will also provide the core profile/distribution. 

It is based on [drupal/core-recommended]() and will likely also take inspiration from [localgovdrupal/localgov-project](https://github.com/localgovdrupal/localgov_project) and [localgovdrupal/localgov](https://github.com/localgovdrupal/localgov), as well as CTI's Voyager Drupal profile. 

## Creating a new project (site) using this template

To create a new project called `my-project` using this template:

```bash
composer create-project gla/accelerator_core_alpha my-project
cd my-project
# @TODO Lando install steps
# @TODO Drush si steps
```

## Working on the Accelerator directly (draft process)

This workflow is likely to improve with time but for now

## Using Composer scripts

All build pre/post build commands and patches are to be triggered through Composer e.g.

```
    "scripts": {
        "pre-install-cmd": [
            "bash scripts/server/composer-pre-install.sh"
        ],
        "post-install-cmd": [
            "bash scripts/server/composer-post-install.sh"
        ]
    }
```

Note: This project will use some private repos severed through a private Packagist, therefore an authorisation key may be needed to build the project
