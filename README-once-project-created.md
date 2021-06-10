# Add your project name here

This is a project created by the
[GLA Accelerator](https://github.com/GreaterLondonAuthority/accelerator_core_alpha).

## Local development

```bash
lando start
# @TODO include theme build commands, via Lando
```

## Initial post-project creation steps

1. Edit `.lando.dist.yml` and replace instances of `accelerator` with a unique
   key for your project, such as `myproject`. This will influence the local
   development URL of your project and prevent clashes with other GLA projects.

   @TODO This step would be a good thing to automate - we could pick a project
   key based on the directory passed to the Composer create-project command,
   perhaps?

2. Start Lando, install a site based on the `gla_core` profile, export the
   config, then commit the results:

```bash
lando start
lando drush si -y gla_core --site-name='My project' --account-name=root --account-pass=password install_configure_form.enable_update_status_emails=NULL --verbose
lando drush cex -y
git commit .
git commit -m "Initial commit"
```
