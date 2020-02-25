# Studio

<a href="https://packagist.org/packages/cnvs/studio"><img src="https://poser.pugx.org/cnvs/studio/downloads"></a>
<a href="https://packagist.org/packages/cnvs/studio"><img src="https://poser.pugx.org/cnvs/studio/v/stable"></a>
<a href="https://packagist.org/packages/cnvs/studio"><img src="https://poser.pugx.org/cnvs/studio/license"></a>

## Introduction

While Canvas does not dictate a specific design for your frontend, it does provide a basic starting point using [Bootstrap](https://getbootstrap.com) and [Vue](https://vuejs.org) that will be helpful for many applications.

## Installation

You may use composer to install Studio into your Laravel project:

```bash
composer require cnvs/studio
```

Once the `cnvs/studio` package has been installed, you may install the frontend scaffolding using the `studio:install` Artisan command:

```bash
php artisan studio:install
```

After installing the `cnvs/studio` Composer package and generating the frontend scaffolding, your `package.json` file will include the necessary dependencies to install and compile:

```bash
# Using NPM
npm install
npm run dev

# Using Yarn
yarn
yarn dev
```

## License

Studio is open-sourced software licensed under the [MIT license](license).
