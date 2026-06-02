# 🏦 Cashless System & AI Financial Assistant — Pesantren Darussalam Al-Qur'ani

An integrated cashless financial management platform tailored for the Pesantren Darussalam Al-Qur'ani ecosystem. This application leverages **Generative AI** to act as a Personalized Financial Advice Planner for students' parents.

## 🚀 Key Features
- **Multi-Role Access Control:** Customized views and workflows for Admin (Database Management & Balances Top Up), Cashier (Canteen Point of Sales Execution), and Parents (Dashboard Monitoring).
- **Budget Tracking:** Dynamic weekly expenditure tracking visualized through interactive line charts powered by Chart.js.
- **AI Financial Assistant:** Automated non-technical financial evaluation summaries parsing students' cashflows via the gemini-3-flash.
- **Interactive Chatbot AI:** A real-time, two-way conversational room for parents to consult individual budgeting strategies and student data analytics.
- **Savings Goal Tracker:** Animated progress bar milestones calculating target achievements and detailed deposit transfer histories.
- **Basic Investment Guidance:** Predictive future simulations converting daily residual savings balances into stable gold asset projections.
- **Full Localization Support:** Seamless on-the-fly session switching between English and Indonesian across all view components.

## 🛠️ Tech Stack
- **Backend:** Laravel 11.x (PHP >= 8.1.10)
- **Local Server Environment:** Laragon Full 6.0 (Apache, MySQL 5.7)
- **Frontend UI Engine:** Bootstrap 5.3 & Bootstrap Icons v1.11.0
- **Visual Chart Library:** Chart.js
- **AI Engine Engine:** Google AI Studio (gemini-3-flash API model)

## 💻 Local Setup & Installation Guide

1. **Clone the Repository & Navigate to Project Directory:**
```bash
   git clone (https://github.com/Virdomf123/darussalam-cashless)(https://github.com/Virdomf123/darussalam-cashless)
   cd darussalam-cashless
```
2. **Install Composer Dependencies:**
   **Salin file .env.example menjadi .env:**
```
 composer install
```

3. **Configure Environment Settings:**

**Duplicate the example environment file:**
cp .env.example .env
     ```
   - Open the newly created `.env` file, configure your local database settings, and insert your unique `GEMINI_API_KEY` obtained from Google AI Studio.
   - Generate the unique Laravel application framework encryption key:
   ```bash
     php artisan key:generate
     ```

4. Run Database Migrations & Seed Dummy Data:

php artisan migrate --seed

5. **Clear Local Configuration Caches & Boot the Server:**
```
php artisan config:clear
php artisan view:clear
php artisan serve
```

**Open your browser and navigate to http://127.0.0.1:8000 to access the running system.**




