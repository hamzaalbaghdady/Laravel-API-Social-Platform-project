# Laravel API Documentation for Social Media Application

## Overview

This API is the backend for a social media application inspired by platforms like Instagram and Facebook. It supports the following features:

-   User registration, login, and logout.
-   Profile creation, update, deletion, and retrieval.
-   Post creation, update, deletion, and retrieval.
-   Comment creation, update, deletion, retrieval, and reply.
-   Reactions (likes, loves, etc.) on posts and comments.
-   Follower system for users.

---

## Authentication and Authorization

The application uses **Bearer Tokens** for authentication. All features require authenticated users except for registration and login.

**Key Rules**:

-   Authenticated users must have a profile to create posts, comments, react, or follow others.
-   Authenticated users without a profile can view posts, comments, reactions, and follows but cannot create or interact.
-   Users can only modify or delete their own resources (users, profiles, posts, comments, etc.).
-   Post creators can delete any comment on their posts.

---

## Endpoints

### 1. **User Endpoints**

#### Register a User

**POST** `/api/register`  
**Request Body**:

```json
{
    "first_name": "string",
    "last_name": "string",
    "email": "string",
    "password": "string",
    "password_confirmation": "string",
    "date_of_birth": "date"
}
```

**Response**:

-   **201**: Success
-   **422**: Validation error

```json
{
    "id": "integer",
    "first_name": "string",
    "last_name": "string",
    "email": "string",
    "date_of_birth": "date",
    "profile_id": "integer"
}
```

#### Login a User

**POST** `/api/login`  
**Request Body**:

```json
{
    "email": "string",
    "password": "string"
}
```

**Response**:

-   **200**: Success
-   **422**: Validation error
-   **401**: Unauthorized

```json
{
    "id": "integer",
    "first_name": "string",
    "last_name": "string",
    "email": "string",
    "date_of_birth": "date",
    "profile_id": "integer",
    "access_token": "string",
    "token_type": "string"
}
```

#### Logout a User

**POST** `/api/logout`  
**Response**:

-   **200**: Success
-   **401**: Unauthorized

```json
{
    "message": "string"
}
```

#### Get User Information

**GET** `/api/user/{id}`  
**Response**:

-   **200**: Success
-   **401**: Unauthorized
-   **404**: Not found

```json
{
    "id": "integer",
    "first_name": "string",
    "last_name": "string",
    "email": "string",
    "date_of_birth": "date",
    "profile_id": "integer"
}
```

#### Update User Information

**PUT** `/api/user/{id}`  
**Request Body**:

```json
{
    "first_name": "string",
    "last_name": "string",
    "email": "string",
    "password": "string",
    "old_password": "string",
    "date_of_birth": "date"
}
```

**Response**:

-   **200**: Success
-   **422**: Validation error
-   **401**: Unauthorized
-   **403**: Forbidden
-   **404**: Not found

```json
{
    "message": "string"
}
```

#### Delete User

**DELETE** `/api/user/{id}`  
**Response**:

-   **200**: Success
-   **401**: Unauthorized
-   **403**: Forbidden
-   **404**: Not found

```json
{
    "message": "string"
}
```

---

### 2. **Profile Endpoints**

#### Create a Profile

**POST** `/api/profiles`  
**Request Body**:

```json
{
    "user_name": "string",
    "about": "text",
    "phone_number": "string",
    "profile_image": "string",
    "location": "string",
    "education": "string"
}
```

**Response**:

-   **201**: Success
-   **422**: Validation error
-   **401**: Unauthorized

```json
{
    "message": "string"
}
```

#### Update a Profile

**PUT** `/api/profiles/{id}`  
**Request Body**:

```json
{
    "user_name": "string",
    "about": "text",
    "phone_number": "string",
    "profile_image": "string",
    "location": "string",
    "education": "string"
}
```

**Response**:

-   **200**: Success
-   **422**: Validation error
-   **401**: Unauthorized
-   **403**: Forbidden
-   **404**: Not found

```json
{
    "id": "integer",
    "user_id": "integer",
    "user_name": "string",
    "about": "text",
    "phone_number": "string",
    "profile_image": "string",
    "location": "string",
    "education": "string",
    "created_at": "datetime",
    "updated_at": "datetime",
    "followers": "integer",
    "following": "integer"
}
```

#### Delete a Profile

**DELETE** `/api/profiles/{id}`  
**Response**:

-   **200**: Success
-   **401**: Unauthorized
-   **403**: Forbidden
-   **404**: Not found

```json
{
    "message": "string"
}
```

#### Get Profile Information

**GET** `/api/profiles/{id}`  
**Response**:

-   **200**: Success
-   **401**: Unauthorized
-   **404**: Not found

```json
{
    "id": "integer",
    "user_id": "integer",
    "user_name": "string",
    "about": "text",
    "phone_number": "string",
    "profile_image": "string",
    "location": "string",
    "education": "string",
    "created_at": "datetime",
    "updated_at": "datetime",
    "followers": "integer",
    "following": "integer"
}
```

#### Get All Profiles

**GET** `/api/profiles`  
Supports filtering, sorting, and pagination.

**Query Parameters**:

-   `filter[location]=$value`
-   `sort=['user_name', 'location', 'created_at', 'updated_at']`
-   `page=$page_number`

**Response**:

-   **200**: Success
-   **401**: Unauthorized

```json
[
    {
        "id": "integer",
        "user_id": "integer",
        "user_name": "string",
        "about": "text",
        "phone_number": "string",
        "profile_image": "string",
        "location": "string",
        "education": "string",
        "created_at": "datetime",
        "updated_at": "datetime",
        "followers": "integer",
        "following": "integer"
    }
]
```

---

### 3. **Post Endpoints**

#### Create Post

-   **POST** `/api/posts`
-   **Request Body**:
    ```json
    {
        "content": "string",
        "image": "string"
    }
    ```
-   **Response**:
    -   **201 Created**: Post data.

#### Update Post

-   **PUT** `/api/posts/{id}`
-   **Request Body**:
    ```json
    {
        "content": "string",
        "image": "string"
    }
    ```
-   **Response**:
    -   **200 OK**: Updated post data.

#### Delete Post

-   **DELETE** `/api/posts/{id}`
-   **Response**:
    -   **200 OK**: `{ "message": "Post deleted successfully." }`

#### Show Post

-   **GET** `/api/posts/{id}`
-   **Response**:
    -   **200 OK**: Post data.

#### List Posts

-   **GET** `/api/posts`
    -   Supports filters (e.g., creator_id) and sorting.
    -   Pagination is available.

---

### 4. **Comment Endpoints**

#### Create Comment

-   **POST** `/api/posts/comments`
-   **Request Body**:
    ```json
    {
        "content": "string",
        "attachment": "string",
        "parent_id": "integer"
    }
    ```
-   **Response**:
    -   **201 Created**: Comment data.

#### Update Comment

-   **PUT** `/api/posts/{post_id}/comments/{id}`
-   **Request Body**:
    ```json
    {
        "content": "string",
        "attachment": "string"
    }
    ```
-   **Response**:
    -   **200 OK**: Updated comment data.

#### Delete Comment

-   **DELETE** `/api/posts/{post_id}/comments/{id}`
-   **Response**:
    -   **200 OK**: `{ "message": "Comment deleted successfully." }`

#### Show Comment

-   **GET** `/api/posts/{post_id}/comments/{id}`
-   **Response**:
    -   **200 OK**: Comment data.

#### List Comments

-   **GET** `/api/posts/comments`
    -   Supports filters (e.g., parent_id) and sorting.
    -   Pagination is available.

---

### 5. **Reaction Endpoints**

#### React to Post

-   **POST** `/api/posts/{id}/reactions`
-   **Request Body**:
    ```json
    {
        "type": "string" // like, love, haha, wow, etc.
    }
    ```
-   **Response**:
    -   **201 Created**: `{ "message": "Reaction added/updated successfully." }`

#### Remove Reaction from Post

-   **DELETE** `/api/posts/{id}/reactions/{reaction_id}`
-   **Response**:
    -   **200 OK**: `{ "message": "Reaction removed successfully." }`

#### React to Comment

-   **POST** `/api/comments/{id}/reactions`
-   **Request Body**:

### 6. **Follow Endpoints**

#### Follow User

-   **POST** `/api/users/{id}/follow`
-   **Response**:
    -   **201 Created**: `{ "message": "Followed user successfully." }`

#### Unfollow User

-   **DELETE** `/api/users/{id}/unfollow`
-   **Response**:
    -   **200 OK**: `{ "message": "Unfollowed user successfully." }`

#### List Followers

-   **GET** `/api/users/{id}/followers`
-   **Response**:
    -   **200 OK**: List of followers.

#### List Following

-   **GET** `/api/users/{id}/following`
-   **Response**:
    -   **200 OK**: List of users being followed.

---

---

---

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
-   Index:get::::::: Domain/api/profiles?page=$page_number
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
-   Index:get::::::: Domain/api/posts?page=$page_number
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
-   Index:get::::::: Domain/api/posts/comments?page=$page_number
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

-   Follow:post:::::::::: Domain/api/follow/{id}
    -- Request Body: {}
    -- Response JSON: {message}
    -- Response status code: 201, 401, 404
-   Unfollow:delete:::::: Domain/api/unfollow/{id}
    -- Request Body: {}
    -- Response JSON: {message}
    -- Response status code: 200, 401, 404

---
