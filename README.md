# Authentication
I put together this project while introducing a friend of mine to PHP. I decided to clean it up a bit and put it on Github so anyone new to PHP can have a taste of a **very simple and minimal** website built with PHP.

## Installation

There are only two steps to run this website:

1. Download the project to the desired directory on your computer
2. Run  `php -S localhost:8080` on your terminal. Navigate to http://localhost:8080 to see the site.

By defaut, the page URLs use query strings (*?page=about*). You need to have Apache installed for pretly URLs (*/about*) to work. To activate pretty urls, update config value of `pretty_uri` to `true`.

## Concepts

The project covers these programming concepts:

 * Variables
 * components
 * Arrays
 * Functions
 * Pretty links *(/about) with fallback to query string (?page=about)*
 * Basic example of separation of concerns *(functionality, template, content)*

## License

MIT
