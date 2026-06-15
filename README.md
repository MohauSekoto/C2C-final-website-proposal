# KasiBuy - PHP MVC 
KasiBuy is a Consumer-to-Consumer (C2C) e-commerce marketplace built for local South African entrepreneurs. Its role is to connect entrepreneurs with buyers across the country, and give small businesses a practical entry into the digital economy. Whether you're selling handmade crafts or second-hand electronics, KasiBuy lets you list, sell, and buy products with safety tools.
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
