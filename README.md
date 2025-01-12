# Laravel API Social Media Application:

This project is a copy of sites like Instagram and Facebook, I do implement only the backend.
Project features:

-   User register, login, and logout,
-   Profile create, update, delete, and retrive,
-   Post create, update, delete, and retrive,
-   Comment create, update, delete, retrive, and reply,
-   Reaction on both Post and Comment,
-   Followers System,
-
-
-

## API endpoints:

### User:

-   ::post:: Domain/api/register
    -- Request Body: {first_name, last_name, email, password, password_confirmation, date_of_birth}
    -- Response JSON: {"id", "first_name", "last_name", "email", "date_of_birth", "profile_id"}
    -- Response status code: 201, 422

-   ::post:: Domain/api/login
    -- Request Body: {email, password}
    -- Response JSON: {"id", "first_name", "last_name", "email", "date_of_birth", "profile_id"}+{"access_token", "token_type"}
    -- Response status code: 200, 422, 401

-   ::post:: Domain/api/logout
    -- Request Body: {}
    -- Response JSON: {message}
    -- Response status code: 200, 401

#### Data:

-   first_name: required | string | max: 255
-   last_name: required | string | max: 255
-   email: required | email | unique | max: 255
-   password: required | string | min:8 | max:32| confirmed
-   date_of_birth: required | date

### Profile:

-   Store:post:::::: Domain/api/profiles
    -- Request Body: {user_name, about, phone_number, profile_image, location, education}
    -- Response JSON: {message}
    -- Response status code: 201, 422, 401

-   Update:put:::::: Domain/api/profiles/{id}
    -- Request Body: {user_name, about, phone_number, profile_image, location, education}
    -- Response JSON: {id, user_id, user_name, about, phone_number, profile_image, location, education,created_at, updated_at}
    -- Response status code: 200, 422, 401, 403, 404

-   Destroy:delete:: Domain/api/profiles/{id}
    -- Request Body: {}
    -- Response JSON: {message}
    -- Response status code: 200, 422, 401, 403, 404

-   Show:get:::::::: Domain/api/profiles/{id}
    -- Request Body: {}
    -- Response JSON: {id, user_id, user_name, about, phone_number, profile_image, location, education,created_at, updated_at}
    -- Response status code: 200, 422, 401, 404

-   Index:get::::::: Domain/api/profiles
    -- Request Body: {}
    -- Response JSON: {\*{id, user_id, user_name, about, phone_number, profile_image, location, education,created_at, updated_at}}
    -- Response status code: 200, 422, 401

#### Data:

-   user_name: required | string | unique | min:8 | max: 255
-   about: required | text | min:20 | max: 5000
-   phone_number: nullable | string | min:10 | max: 20
-   profile_image: nullable | string | min:8 | max: 255
-   location: nullable | string | max: 255
-   education: nullable | string | min:8 | max: 255

### Post:

-   Store:post:::::: Domain/api/posts
-   Update:put:::::: Domain/api/posts/{id}
-   Destroy:delete:: Domain/api/posts/{id}
-   Show:get:::::::: Domain/api/posts/{id}
-   Index:get::::::: Domain/api/posts

### Comment:

-   Store:post:::::: Domain/api/posts/comments
-   Update:put:::::: Domain/api/posts/{id}/comments/{id}
-   Destroy:delete:: Domain/api/posts/{id}/comments/{id}
-   Show:get:::::::: Domain/api/posts/{id}/comments/{id}
-   Index:get::::::: Domain/api/posts/comments

### Reaction:

-   React:post:::::::::: Domain/api/posts/{id}/reactions
-   Unreact:delete:::::: Domain/api/posts/{id}/reactions/{id}
-   React:post:::::::::: Domain/api/comments/{id}/reactions
-   Unreact:delete:::::: Domain/api/comments/{id}/reactions/{id}
