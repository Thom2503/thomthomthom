# THOMTHOMTHOM
Inspired by WikiWikiWeb, DocuWIKI, Wikipedia, Ratfactors HTML only wiki, I
wanted to create my own wiki. This is because I want to take more notes and
tidbits, but not fully for the public to see. So I'm going to create an free
open-source wiki, named THOMTHOMTHOM.

(Thom is my name btw.)

It will use SQLITE as a small database and .txt files nothing more. I want to
be able to upload images, but I don't know how and maybe I don't want to add it
eitherway.

PHP is the only and best language to use for this, so well it's going to use simple PHP.
HTML and JS and CSS are a must so these are included as well.

Oh and the goal is to reach a max of [512KiB](https://512kb.club/) :)

Now on to the fun part.

## How does it work?
I am going to have two DB schema's, one with users (me) and the other with the pages.
```sql
PRAGMA integrity_check;
PRAGMA foreign_keys = ON;
CREATE TABLE IF NOT EXISTS `users` (
    `id` INTEGER NOT NULL PRIMARY KEY,
    `name` VARCHAR(63) NOT NULL UNIQUE,
    `admin` INTEGER NOT NULL CHECK (`admin` IN (0, 1)),
    `password` TEXT NOT NULL DEFAULT ''
);
CREATE TABLE IF NOT EXISTS `pages` (
    `id` INTEGER NOT NULL PRIMARY KEY,
    `pagename` VARCHAR(255) NOT NULL,
    `madeby` INTEGER NOT NULL,
    `public` INTEGER NOT NULL CHECK (`public` IN (0, 1)),
    `ts` INTEGER,
    FOREIGN KEY (`madeby`) REFERENCES `users`(`id`)
);
```
Wow big and beautiful tables, now onto the directories.
```
THOMTHOMTHOM/
| index.php
| login.php
| page.php
| common.php
| pages/
  | page1.txt
| archive/
  | page1_1239299.diff
| style/
  | style.css
| static/
  | icon32x32.png
| db.sqlite
| settings.php
| main.html # my main site
```
It is quite simple and it will not use a lot of KiB hopefully.
(images are still not kept in consideration)

## `<pre> </pre>`
Because the pages are gonna be .txt files, they are easily imported in.
```php
<?php
echo "<pre>". include "pages/12345.txt" ."</pre>\n";
```
I want to be able to use links and images so `<a>` and `<img>` are the only
accepted tags. The rest is all `plain/text` goodness.

## Fallback
If the user is not authenticated, or I don't want the pages to be public, others will be redirected to my main page.

## $GLOBALS $GLOBALS $GLOBALS
PHP has a wonderful array called `$GLOBALS` this will become the main tool to
use in the settings. Other PHP arrays that are userful, are `$_REQUEST`,
`$_SERVER`, `$_COOKIE` and `$_SESSION`.

But I hear you saying, are globals not evil? My answer: no. You just need to use them properly.

## Securiy
Well security is a thing that needs to be considered, so yeah the database is
gonna be encrypted (i think), I don't want to have any grievers or something. I
want to really have no big pages so it isn't be much work to secure. But try me!

## Diffs?
It works with diffs to follow tracked changes?

# Other languages
I admire a lot of other languages, but PHP is such a great tool for writing
webpages like this it's almost intriguing to know why it works so well. PHP is
not the greatest in all terms, but nothing comes close to this power for this
kind of application.

Other languages that I considered, Ruby, Perl, Python, Common LISP. But these
are much more tedious to work for this kind of application than PHP.

If you disagree mail to me: [thomveldhuis [at] gmail [dot] com](mailto:thomveldhuis03@gmail.com)

# Usage
How to actually use it?
## `settings.php`
Here the settings are defined in `$GLOBALS`
## Run it
Have any sort of PHP servable server like apache, nginx or your own implementation server `index.php`.
Login, make edits and watch this beautiful wiki.

# LICENSE
GNU GPL as always