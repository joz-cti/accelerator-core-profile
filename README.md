# Accelerator core (alpha)
This project contains all the core components used by the GLA drupal accelerator and all drupal sites within the GLA estate
# installation
This project is built using composer
```
composer install
```


all build pre/post build commands and patches are to be triggered through composer e.g.
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

Note: This project will use some private repos severed through a private packgist therefore an authorisation key may be needed to build the project

