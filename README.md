# Trigger ZF documentation builds

This repository provides tooling for triggering builds of the ZF documentation,
and should only be used by documentation maintainers.

It requires a `ZFBOT_TOKEN` environment variable with a valid API token. To
obtain one, please ping user mwop on the Zend Framework slack.

## Installation

Install your dependencies using [Composer](https://getcomposer.org):

```bash
$ composer install
```

Next, create a `.env` file in your application with the following contents:

```bash
ZFBOT_TOKEN=token-value
```

## Usage

```bash
$ php build.php
```

> ### Lag time
>
> The script pauses for 15s between calls in order to allow the bot time to
> catch up with the queue.

## Customization of build list

You can reduce the build list by providing a substitute `component-list.txt`
file.
