# Cineverse API Documentation

Base URL: `http://your-domain.com/api` (e.g., `http://127.0.0.1:8000/api`)

## Authentication

### 1. Register
Create a new user account.

- **Endpoint:** `POST /register`
- **Headers:** `Content-Type: application/json`, `Accept: application/json`
- **Body:**
  ```json
  {
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
  }
  ```
- **Response (201 Created):**
  ```json
  {
    "access_token": "1|laravel_sanctum_token...",
    "token_type": "Bearer",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      ...
    }
  }
  ```

### 2. Login
Authenticate a user and get an access token.

- **Endpoint:** `POST /login`
- **Headers:** `Content-Type: application/json`, `Accept: application/json`
- **Body:**
  ```json
  {
    "email": "john@example.com",
    "password": "password123"
  }
  ```
- **Response (200 OK):**
  ```json
  {
    "access_token": "2|laravel_sanctum_token...",
    "token_type": "Bearer",
    "user": { ... }
  }
  ```

### 3. Logout
Invalidate the current access token.

- **Endpoint:** `POST /logout`
- **Headers:** `Authorization: Bearer <token>`, `Accept: application/json`
- **Response (200 OK):**
  ```json
  {
    "message": "Logged out successfully"
  }
  ```

### 4. Get Profile
Get the authenticated user's details.

- **Endpoint:** `GET /profile`
- **Headers:** `Authorization: Bearer <token>`, `Accept: application/json`
- **Response (200 OK):**
  ```json
  {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    ...
  }
  ```

---

## User Preferences

### 5. Get Preferences
Fetch the user's discovery preferences.

- **Endpoint:** `GET /preferences`
- **Headers:** `Authorization: Bearer <token>`, `Accept: application/json`
- **Response (200 OK):**
  ```json
  {
    "id": 1,
    "user_id": 1,
    "genres": [28, 12],
    "languages": ["en", "es"],
    "min_rating": 7.5,
    "release_year_start": 2000,
    "release_year_end": 2025,
    ...
  }
  ```

### 6. Update Preferences
Update the user's discovery preferences.

- **Endpoint:** `POST /preferences`
- **Headers:** `Authorization: Bearer <token>`, `Content-Type: application/json`, `Accept: application/json`
- **Body:**
  ```json
  {
    "genres": [28, 12, 35],
    "languages": ["en"],
    "min_rating": 6.0,
    "release_year_start": 2010,
    "release_year_end": 2026
  }
  ```
- **Response (200 OK):**
  ```json
  {
    "id": 1,
    "user_id": 1,
    "genres": [28, 12, 35],
    ...
  }
  ```

---

## Discovery Feed

### 7. Get Feed
Get a paginated list of media recommendations based on preferences.

- **Endpoint:** `GET /feed`
- **Headers:** `Authorization: Bearer <token>`, `Accept: application/json`
- **Query Parameters:** `page=1` (optional)
- **Response (200 OK):**
  ```json
  {
    "current_page": 1,
    "data": [
      {
        "id": 101,
        "tmdb_id": 550,
        "title": "Fight Club",
        "poster_path": "/pB8BM7pdSp6B6Ih7QZ4DrQ3PmJK.jpg",
        "rating": 8.4,
        ...
      },
      ...
    ],
    "next_page_url": "http://.../api/feed?page=2",
    ...
  }
  ```

---

## Interactions

### 8. Record Interaction
Record a user's action on a media item (Like, Dislike, Watched, Skipped).
*Note: 'like' automatically adds to watchlist.*

- **Endpoint:** `POST /interactions`
- **Headers:** `Authorization: Bearer <token>`, `Content-Type: application/json`, `Accept: application/json`
- **Body:**
  ```json
  {
    "media_id": 101,
    "type": "like" 
  }
  ```
  *Type options: `like`, `dislike`, `watched`, `skipped`*

- **Response (200 OK):**
  ```json
  {
    "message": "Interaction recorded",
    "interaction": { ... }
  }
  ```

---

## Watchlist

### 9. Get Watchlist
Get the user's watchlist.

- **Endpoint:** `GET /watchlist`
- **Headers:** `Authorization: Bearer <token>`, `Accept: application/json`
- **Response (200 OK):**
  ```json
  {
    "current_page": 1,
    "data": [
      {
        "id": 5,
        "media_id": 101,
        "watched_at": null,
        "media": {
            "id": 101,
            "title": "Fight Club",
            ...
        }
      }
    ],
    ...
  }
  ```

### 10. Add to Watchlist
Manually add an item to the watchlist.

- **Endpoint:** `POST /watchlist`
- **Headers:** `Authorization: Bearer <token>`, `Content-Type: application/json`, `Accept: application/json`
- **Body:**
  ```json
  {
    "media_id": 101
  }
  ```
- **Response (201 Created):**
  ```json
  {
    "id": 5,
    "media_id": 101,
    ...
  }
  ```

### 11. Remove from Watchlist
Remove an item from the watchlist.

- **Endpoint:** `DELETE /watchlist/{mediaId}`
- **Headers:** `Authorization: Bearer <token>`, `Accept: application/json`
- **Response (200 OK):**
  ```json
  {
    "message": "Removed from watchlist"
  }
  ```

### 12. Mark as Watched
Mark an item in the watchlist as watched.

- **Endpoint:** `PATCH /watchlist/{mediaId}/watched`
- **Headers:** `Authorization: Bearer <token>`, `Accept: application/json`
- **Response (200 OK):**
  ```json
  {
    "message": "Marked as watched"
  }
  ```

---

## Comments

### 13. Get Comments
Get comments for a specific media item.

- **Endpoint:** `GET /media/{mediaId}/comments`
- **Headers:** `Authorization: Bearer <token>`, `Accept: application/json`
- **Response (200 OK):**
  ```json
  {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "content": "Great movie!",
        "user": { "name": "Jane Doe" },
        "created_at": "..."
      }
    ],
    ...
  }
  ```

### 14. Add Comment
Add a comment to a media item.

- **Endpoint:** `POST /media/{mediaId}/comments`
- **Headers:** `Authorization: Bearer <token>`, `Content-Type: application/json`, `Accept: application/json`
- **Body:**
  ```json
  {
    "content": "This is a must watch!"
  }
  ```
- **Response (201 Created):**
  ```json
  {
    "id": 2,
    "content": "This is a must watch!",
    ...
  }
  ```

### 15. Delete Comment
Delete a user's own comment.

- **Endpoint:** `DELETE /comments/{id}`
- **Headers:** `Authorization: Bearer <token>`, `Accept: application/json`
- **Response (200 OK):**
  ```json
  {
    "message": "Comment deleted"
  }
  ```


Email: test@cineverse.app
Password: password
