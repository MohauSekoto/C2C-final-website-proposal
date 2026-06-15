# KasiBuy - PHP MVC Refactor

This project has been refactored from Next.js to a lightweight, pure PHP backend (using an MVC pattern) with a vanilla HTML/JS frontend powered by Tailwind CSS and Alpine.js.

## Requirements
- PHP 7.4 or higher
- An internet connection (for Tailwind CSS and Alpine.js CDNs, and to connect to the remote MySQL database).

## Getting Started (No XAMPP Required)

The easiest way to run this application locally is to use PHP's built-in development server. 

1. Open your terminal/command prompt.
2. Navigate to the `public/` directory within this project:
   ```bash
   cd public
   ```
3. Start the built-in PHP server:
   ```bash
   php -S localhost:8000
   ```
4. Open [http://localhost:8000](http://localhost:8000) with your browser to see the result.

## Architecture
- **Frontend**: Tailwind CSS (via CDN) for styling and Alpine.js (via CDN) for interactivity.
- **Backend**: Custom lightweight PHP MVC pattern.
  - `public/index.php`: The main entry point that initializes the router.
  - `app/Core/`: Contains the base Router and Database connection classes.
  - `app/Controllers/`: Handles incoming requests.
  - `app/Models/`: Interacts with the database.
  - `app/Views/`: PHP files containing HTML and Tailwind markup.
- **Database**: The app connects directly to a remote Aiven MySQL database using PHP PDO.

## Responsive Design
The project uses Tailwind's utility classes (`sm:`, `md:`, `lg:`) to ensure all pages scale correctly from mobile phones to desktop displays. You can test this using your browser's developer tools.
