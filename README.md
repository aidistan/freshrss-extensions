# FreshRSS extensions

> **NOTE for edge users**: please use the versions in [edge branch](https://github.com/aidistan/freshrss-extensions/tree/edge) instead.

This repository contains all my custom FreshRSS extensions.

## How to install?

```sh
# Clone this repository
cd /var/www/FreshRSS/extensions
git clone --depth=1 https://github.com/aidistan/freshrss-extensions.git aidistan-extensions

# Checkout the edge branch (optional, only for edge users)
cd aidistan-extensions
git checkout edge
cd ..

# Copy the desired extension
cp -r aidistan-extensions/xExtension-XXX .
```

See also: https://freshrss.github.io/FreshRSS/en/admins/15_extensions.html#how-to-install

## How to upgrade?

```sh
# Update the repository
cd /var/www/FreshRSS/extensions/aidistan-extensions
git pull
cd ..

# Remove the old version
rm -rf xExtension-XXX

# Copy the new version
cp -r aidistan-extensions/xExtension-XXX .
```

## Quick glance

### Theme Mode Synchronizer

Synchronize the theme with your system light/dark mode

https://user-images.githubusercontent.com/3037661/185191808-af1e375c-e9e5-41ca-942a-8b714f50a774.mp4

### Feed Priority Shortcut

Set up visibilities/priorities of your feeds easily

![](https://user-images.githubusercontent.com/3037661/186879158-5349e59f-54db-47cc-bd40-b21325d91672.png)
