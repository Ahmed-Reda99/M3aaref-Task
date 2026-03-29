# 🚀 YouTube Course Scraper

A Laravel-based web application that uses AI (Gemini) and YouTube Data API to discover and display educational YouTube playlists (courses) based on user-defined categories.

---

## 📌 Features

- 🔍 Generate course ideas using AI (Gemini API)
- ▶️ Fetch related YouTube playlists
- 🗂️ Store playlists with category association
- 🚫 Prevent duplicate entries (based on playlist ID)
- 🎨 Clean UI built with Bootstrap 4
- ⚡ AJAX-based fetching & redirection

---

## 🛠️ Tech Stack

- Laravel 10
- Bootstrap 4 & 5
- Gemini AI API
- YouTube Data API v3
- MySQL

---

## 📦 Installation & Setup

### 1. Clone the Repository

```bash
git clone https://github.com/Ahmed-Reda99/M3aaref-Task
cd to the directory
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Environment Variables

Update your `.env` file:

```env
APP_NAME="YouTube Course Scraper"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Gemini AI API
GEMINI_API_KEY=your_gemini_api_key
GEMINI_REQUEST_TIMEOUT=60

# YouTube Data API
YOUTUBE_API_KEY=your_youtube_api_key
```

### 5. Run Database Migrations

```bash
php artisan migrate
```

### 6. Start the Application

```bash
php artisan serve
```

Visit the app at: http://127.0.0.1:8000

---

## ▶️ How to Use

1. Enter categories (one per line)
2. Click **Start Fetching**
3. The system will:
   - Generate course titles using AI
   - Search for YouTube playlists
   - Store results in the database
4. You will be redirected to the results page displaying playlists grouped by category

---

## 🧠 Project Structure Highlights

- **Services Layer**
  - `AIService` → Handles Gemini API requests
  - `YouTubeService` → Handles YouTube API integration
  - `PlaylistService` → Handles data storage & deduplication

- **Controllers**
  - Keep controllers thin and delegate logic to services

---

## 🚫 Deduplication Strategy

- Enforced using a **unique constraint** on `playlist_id`
- Implemented using `updateOrCreate` in `PlaylistService`

---

## ⚠️ Notes

- API errors are handled and logged
- Empty or invalid categories are ignored
- Designed with clean architecture principles for maintainability

---

## 📌 Future Improvements

- Queue processing for better performance
- Caching API responses
- Pagination & filtering enhancements
- UI/UX improvements

---

## 👨‍💻 Author

Ahmed Reda
