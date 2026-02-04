# BIIGLE Annotation Tags
BIIGLE module allowing to add multiple tags to annotations.

Tags can later be used later to generate new labels or any other use.

## Features
* Creating tags
* Importing list of tags
* Add/remove multiple tags from annotation
* Adding a value to a tag
* Export annotations to custom COCO format

## Installation
Follow those steps to install this module to your own BIIGLE instance

### Edit the build.dockerfile

1. Go into the BIIGLE installation directory
2. Edit the `build/build.dockerfile` file

### Add the repository to composer

Add this repository to the composer configuration by adding this line before `RUN COMPOSER_AUTH...`:
```
RUN php composer.phar config repositories.annotation-tags github https://github.com/RobinDanz/annotation-tags.git
```
It should look like this:

```
ARG METADATA_COCO_VERSION ...

RUN php composer.phar config repositories.annotation-tags github https://github.com/RobinDanz/annotation-tags.git

RUN COMPOSER_AUTH ...
```

### Require the module

Install the module by adding this line at the end of the `composer.phar` command:

```
rdnz/annotation-tags:dev-main --prefer-dist --update-no-dev --ignore-platform-reqs
```

It should look like this:
```
composer.phar require \
    biigle/geo:${GEO_VERSION} \
    biigle/color-sort:${COLOR_SORT_VERSION} \
    biigle/laserpoints:${LASERPOINTS_VERSION} \
    biigle/ananas:${ANANAS_VERSION} \
    biigle/metadata-coco:${METADATA_COCO_VERSION} \
    rdnz/annotation-tags:dev-main --prefer-dist --update-no-dev --ignore-platform-reqs # New line
```
Do not forget to insert a `\` at the end of the line above.

### Register the ServiceProvider

Register the `AnnotationTagsServiceProvider` to the BIIGLE config by adding this line after the other `ServiceProvider`.

```
&& sed -i '/Insert Biigle module service providers/i Biigle\\Modules\\AnnotationTags\\AnnotationTagsServiceProvider::class,' config/app.php
```

It should look like this:

```
RUN sed -i '/Insert Biigle module service providers/i Biigle\\Modules\\Geo\\GeoServiceProvider::class,' config/app.php \
    && sed -i '/Insert Biigle module service providers/i Biigle\\Modules\\ColorSort\\ColorSortServiceProvider::class,' config/app.php \
    && sed -i '/Insert Biigle module service providers/i Biigle\\Modules\\Laserpoints\\LaserpointsServiceProvider::class,' config/app.php \
    && sed -i '/Insert Biigle module service providers/i Biigle\\Modules\\Ananas\\AnanasServiceProvider::class,' config/app.php \
    && sed -i '/Insert Biigle module service providers/i Biigle\\Modules\\MetadataCoco\\MetadataCocoServiceProvider::class,' config/app.php \
    && sed -i '/Insert Biigle module service providers/i Biigle\\Modules\\AnnotationTags\\AnnotationTagsServiceProvider::class,' config/app.php
```
Do not forget to insert a `\` at the end of the line above.

### Rebuild and restard the BIIGLE instance

#### !!! DISCLAIMER !!!
This module applies migrations to the database. Before installing it, you may want to check the [official documentation](https://biigle-admin-documentation.readthedocs.io/maintenance/#updating) on the update procedure before the next steps.

1. Build the containers: `cd build && ./build.sh`
2. Put the application in maintenance mode: `./artisan down`
3. [database backup](https://biigle-admin-documentation.readthedocs.io/maintenance/#database-backup)
4. Update the running Docker container: `docker compose up -d`
3. Run the migrations: `./artisan migrate`
4. Turn off the maintenance mode: `./artisan up`
5. Delete old Docker images: `docker image prune`

The module should now be ready to use !




