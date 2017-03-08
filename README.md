# Laravel vue-admin integration example

The web admin frontend uses the [vue-admin](https://github.com/vue-bulma/vue-admin) package. 
If the frontend symlinking fails on windows you have to run it as Administrator or [set the symlink permission](https://superuser.com/questions/124679/how-do-i-create-a-link-in-windows-7-home-premium-as-a-regular-user)

## Building

Before you run build:watch you have to run build:dev or build:prod to create the symlinks. If you are using windows, you have to run it as administrator.

Frontend build commands:
  - ```php artisan build:install```  install the frontend dependencies
  - ```php artisan build:dev```  builds the frontend in dev mode
  - ```php artisan build:watch``` builds the frontend in dev mode, and watches the filesystem for changes
  - ```php artisan build:prod```  builds the frontend in production mode

## Developing the frontend

The frontend root folder is ```resources/assets/frontend```. In this folder you can find the complete vue-admin package. If you want to make changes to the frontend
you have to work in this folder.

The frontend is buildable alone. You can find more info in the ```resources/assets/frontend/package.json```.
