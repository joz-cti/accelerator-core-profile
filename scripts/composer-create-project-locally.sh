#!/usr/bin/env bash
#
# Source: https://gist.github.com/beporter/31e7d1f5beeffda0da94
#
# Allows you to test the create-project process using your local
# checked-out copy of the skeleton as the source. You MUST commit the
# changes you want to test to a git branch! You MUST name that branch
# as the first argument and the destination path to set up the fresh
# copy into as the second.
#
# Usage:
#  - Place this script in your package's root directory and make it executable.
#  - Set the PACKAGE_NAME variable below to match your composer.json's `name`.
#  - Make changes to your package and commit them to a git branch.
#  - Run `./composer-create-project-test.sh your-branch-name /target/dir`
#  - The new copy of your project will be in /target/dir.


# Capture command line args, or set defaults.
BRANCH_NAME=${1:-develop}
DEST_DIR=${2:-~/Sites/example-accelerator-site}
PACKAGE_NAME="gla/accelerator-core-alpha"

# Warn the dev if they have uncommitted changes since they won't
# be included in the spawned project.
if ! git diff-index --quiet --cached HEAD; then
  echo "## There are local uncommitted changes to the repo."
  echo "## Running create-project now will NOT include these changes."
  while true; do
    read -p "## Are you sure you want to continue? [Y/n]: " yn
    case $yn in
      [Nn]* ) exit;;
      [Yy]* ) break;;
      * ) break;;
    esac
  done
fi

# Warn if the destination dir is non-empty, and offer to delete it.
# Composer will fail if the directory is not empty.
if [ -d "${DEST_DIR}" ] && [ "$(ls -A "${DEST_DIR}")" ]; then
  echo "## The destination directory \`${DEST_DIR}\` is not empty."
  while true; do
    read -p "## Do you want to delete it and continue? [Y/n]: " yn
    case $yn in
      [Nn]* ) exit;;
      [Yy]* ) chmod -R 777 "${DEST_DIR}"; rm -rf "${DEST_DIR}"; break;;
    esac
  done
fi

# Set up a packages.json file to use with create-project.
cat <<EOD > packages.json
{
  "packages": {
    "${PACKAGE_NAME}": {
      "dev-$BRANCH_NAME": {
        "name": "${PACKAGE_NAME}",
        "version": "dev-$BRANCH_NAME",
        "source": {
          "url": "./",
          "type": "git",
          "reference": "${BRANCH_NAME}"
        }
      }
    }
  }
}

EOD

# Execute the proper command.
COMPOSER_MEMORY_LIMIT=-1 composer clear
COMPOSER_MEMORY_LIMIT=-1 composer create-project --repository-url=./packages.json ${PACKAGE_NAME} "${DEST_DIR}" dev-${BRANCH_NAME} --no-interaction

# Clean up after ourselves.
rm -f packages.json
