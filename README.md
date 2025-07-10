# rumble WPPD plugin

This plugin is part of the **Rumble WordPress plugin dashboard** project.\
\
It automatically collects information about the WordPress instance and sends it to the configured [collector service API](https://github.com/rumble-tech/wppd-collector).

## Installation

1. Checkout the repository to your local machine.
2. Run `composer install` to install the dependencies.
3. Create a `docker/compose.dev.override.yml` file based on the example provided in `docker/compose.dev.override.example.yml`.

## Starting development environment

1. Make sure that all dependencies are installed and that the `docker/compose.dev.override.yml` file is created and valid.
2. Execute the command `composer dev` to start the development environment.
3. Wait for the containers to start and open your browser to `http://localhost:YOUR_PORT_CONFIGURED_IN_COMPOSE_OVERRIDE_FILE` and setup the WordPress instance.
4. After the setup is complete, you can access the WordPress admin panel and start developing the plugin.

**Note**: The projects root directory is mounted into the `wordpress` container, so that you can edit the code directly in your local environment and see the changes reflected in the WordPress instance.

## Composer scripts

| Script  | Description |
|---------| --- |
| `build`  | Extracts the version attribute from the `composer.json` and builds the plugin zip file with the `build.xml` file |
| `dev`     | Starts the development docker containers |
| `cs:check` | Runs the PHP CS Fixer on the codebase |
| `cs:fix`  | Runs the PHP CS Fixer on the codebase and fixes issues |

