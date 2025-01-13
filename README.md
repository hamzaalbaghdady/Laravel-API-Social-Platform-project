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

## Authentication and Authorization:

We use Bearar tokens for authentication in this application, Of course all featuer for authenticated user exapt for register and login.
Authenticated user must have a profile to create post or comment, react on posts or comments, and follow and get followed.
Authenticated user without a profile can only see other posts, comments, reactions, and follows.
Authenticated user can create, update, delete, and view thire user, profile, post, or comment.
Authenticated user can not update or delete others user, profile, post, or comment.
Post creator can delete any comment on thire post.
Authenticated user can react on both posts and comments.
Authenticated user can follow/unfullow other users.

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

-   ::Put:: Domain/api/user/{id}
    -- Request Body: {first_name, last_name, email, password, old_password, date_of_birth}
    -- Response JSON: {message}
    -- Response status code: 200, 422, 401, 403, 404

-   ::delete:: Domain/api/user/{id}
    -- Request Body: {}
    -- Response JSON: {message}
    -- Response status code: 200, 401, 403, 404

-   ::get:: Domain/api/user/{id}
    -- Request Body: {}
    -- Response JSON: {"id", "first_name", "last_name", "email", "date_of_birth", "profile_id"}
    -- Response status code: 200, 401, 403, 404

-   ::get:: Domain/api/user
    -- Request Body: {}
    -- Response JSON: {\*{"id", "first_name", "last_name", "email", "date_of_birth", "profile_id"}}
    -- Response status code: 200, 401, 403, 404

#### Data:

-   first_name: required | string | max: 255
-   last_name: required | string | max: 255
-   email: required | email | unique | max: 255
-   password: required | string | min:8 | max:32| confirmed
-   date_of_birth: required | date

---

### Profile:

-   Store:post:::::: Domain/api/profiles
    -- Request Body: {user_name, about, phone_number, profile_image, location, education}
    -- Response JSON: {message}
    -- Response status code: 201, 422, 401

-   Update:put:::::: Domain/api/profiles/{id}
    -- Request Body: {user_name, about, phone_number, profile_image, location, education}
    -- Response JSON: {id, user_id, user_name, about, phone_number, profile_image, location, education,created_at, updated_at, followers, following}
    -- Response status code: 200, 422, 401, 403, 404

-   Destroy:delete:: Domain/api/profiles/{id}
    -- Request Body: {}
    -- Response JSON: {message}
    -- Response status code: 200, 401, 403, 404

-   Show:get:::::::: Domain/api/profiles/{id}
    -- Request Body: {}
    -- Response JSON: {id, user_id, user_name, about, phone_number, profile_image, location, education,created_at, updated_at, followers, following}
    -- Response status code: 200, 401, 404

-   Index:get::::::: Domain/api/profiles
-   Index:get::::::: Domain/api/profiles?filter[location]=$value
-   Index:get::::::: Domain/api/profiles?sort=['user_name', 'location', 'created_at', 'updated_at']
    -- Request Body: {}
    -- Response JSON: {\*{id, user_id, user_name, about, phone_number, profile_image, location, education,created_at, updated_at, followers, following}}
    -- Response status code: 200, 401

#### Data:

-   user_name: required | string | unique | min:8 | max: 255
-   about: required | text | min:20 | max: 5000
-   phone_number: nullable | string | min:10 | max: 20
-   profile_image: nullable | string | min:8 | max: 255
-   location: nullable | string | max: 255
-   education: nullable | string | min:8 | max: 255

---

### Post:

-   Store:post:::::: Domain/api/posts
    -- Request Body: {content, image}
    -- Response JSON: {id, creator_id, auther, profile_id, content, image, comments_count, reactions_count, created_at, updated_at}
    -- Response status code: 201, 422, 401

-   Update:put:::::: Domain/api/posts/{id}
    -- Request Body: {content, image}
    -- Response JSON: {id, creator_id, auther, profile_id, content, image, comments_count, reactions_count, created_at, updated_at}
    -- Response status code: 200, 422, 401, 404, 403

-   Destroy:delete:: Domain/api/posts/{id}
    -- Request Body: {}
    -- Response JSON: {message}
    -- Response status code: 200, 401, 404, 403

-   Show:get:::::::: Domain/api/posts/{id}
    -- Request Body: {}
    -- Response JSON: {id, creator_id, auther, profile_id, content, image, comments_count, reactions_count, created_at, updated_at}
    -- Response status code: 201, 401, 404

-   Index:get::::::: Domain/api/posts
-   Index:get::::::: Domain/api/posts?filter[creator_id]=$value
-   Index:get::::::: Domain/api/posts?sort=['content', 'created_at', 'updated_at']
    -- Request Body: {}
    -- Response JSON: {\*{id, creator_id, auther, profile_id, content, image, comments_count, reactions_count, created_at, updated_at}}
    -- Response status code: 201, 401

#### Data:

-   content: required | string
-   image: nullable | string

---

### Comment:

-   Store:post:::::: Domain/api/posts/comments
    -- Request Body: {content, attachment, parent_id}
    -- Response JSON: {id, creator_name, post_creator_name, post, content, reactions_count, created_at, updated_at, creator_id, post_id, parent_id, replies}
    -- Response status code: 201, 422, 401
-   Update:put:::::: Domain/api/posts/{id}/comments/{id}
    -- Request Body: {content, attachment, parent_id}
    -- Response JSON: {id, creator_name, post_creator_name, post, content, reactions_count, created_at, updated_at, creator_id, post_id, parent_id, replies}
    -- Response status code: 200, 422, 401, 403, 404
-   Destroy:delete:: Domain/api/posts/{id}/comments/{id}
    -- Request Body: {}
    -- Response JSON: {message}
    -- Response status code: 200, 401, 403, 404
-   Show:get:::::::: Domain/api/posts/{id}/comments/{id}
    -- Request Body: {}
    -- Response JSON: {id, creator_name, post_creator_name, post, content, reactions_count, created_at, updated_at, creator_id, post_id, parent_id, replies}
    -- Response status code: 200, 401, 404
-   Index:get::::::: Domain/api/posts/comments
-   Index:get::::::: Domain/api/posts/comments?filter[parent_id]=$value
-   Index:get::::::: Domain/api/posts/comments?sort=['content', 'created_at', 'updated_at']
    -- Request Body: {}
    -- Response JSON: {\*{id, creator_name, post_creator_name, post, content, reactions_count, created_at, updated_at, creator_id, post_id, parent_id}}
    -- Response status code: 200, 401, 404

#### Data:

-   content: required |string
-   attachment: nullable | string
-   parent_id: nullable | integer | exists:comments,id

---

### Reaction:

-   React:post:::::::::: Domain/api/posts/{id}/reactions
    -- Request Body: {type}
    -- Response JSON: {message}
    -- Response status code: 201, 422, 401
    -- Note: if reaction already exist its update
-   Unreact:delete:::::: Domain/api/posts/{id}/reactions/{id}
    -- Request Body: {}
    -- Response JSON: {message}
    -- Response status code: 200, 401, 404
-   React:post:::::::::: Domain/api/comments/{id}/reactions
    -- Request Body: {type}
    -- Response JSON: {message}
    -- Response status code: 201, 422, 401
    -- Note: if reaction already exist its update
-   Unreact:delete:::::: Domain/api/comments/{id}/reactions/{id}
    -- Request Body: {}
    -- Response JSON: {message}
    -- Response status code: 200, 401, 404

#### Data:

-   type: required | enum | |in:like,love,haha,wow,care,sad,angry

---

### Follows:

-   Follow:post:::::::::: Domain/api/posts/{id}/follow/{id}
    -- Request Body: {}
    -- Response JSON: {message}
    -- Response status code: 201, 401, 404
-   Unfollow:delete:::::: Domain/api/posts/{id}/unfollow/{id}
    -- Request Body: {}
    -- Response JSON: {message}
    -- Response status code: 200, 401, 404

---
