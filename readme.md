# Multiple File Management Management App v1.1
This is an example and a great tutorial on how to build a web app that supports multiple file management. 

Files will belong to a specific user account, and will be private when listing.
You will notice that despite the upload page being public, the upload itself is server restricted, and that was on purpose, to demonstrate such behaviour. 

In this version, we added the following functionalities:
- Create User/Login
- Upload multiple files
- Tag each file with a category (These categories have been seeded inside the app)
- Deleting a file from the server and database
- Counting total attachments size (in MB)
- Showing upload percentage of all files
- Showing loader when sending via AJAX

[![N|Jing](https://content.screencast.com/users/shabany/folders/Jing/media/f8c21a46-adfe-4c38-aa00-739f6efe6fc9/00000170.png)](https://content.screencast.com/users/shabany/folders/Jing/media/f8c21a46-adfe-4c38-aa00-739f6efe6fc9/00000170.png)


## TO Install this project

## Clone the repo

`git clone https://github.com/webdevmatics/multi-file-upload-vue-laravel.git`

### Then change directiory with

`cd Multiple File Management Management

## Run
 `composer install`

## Copy
 .env.example and save as .env and put your database info there

## Run
 `php artisan key:generate`

 `php artisan migrate`

 `php artisan make:auth`

## Run
 `npm install`

## Then 
`npm run watch`
Keep it running on terminal (dont close)
