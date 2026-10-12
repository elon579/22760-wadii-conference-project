# Student Technology & Innovation Conference Registration

A beginner-friendly Web Application Development II CAT project built with HTML, CSS, JavaScript, and PHP.

## Features

- Home page and registration page with connected navigation.
- Client-side validation for required fields, email, 10-digit phone number, and conference duration.
- Dynamic workshop selection.
- Live conference fee calculator.
- PHP server-side validation and independent fee calculation.
- Personalized confirmation page.
- Admission-number individual requirement for registration numbers ending in **0**: a 10% discount on registration plus attendance fees when the participant attends all 3 days.

## Fee rules

- Registration: KSh 1,000
- Attendance: KSh 500 per day
- Workshop: KSh 750
- Lunch: KSh 300 per day
- 3-day discount: 10% of registration + attendance fees

## Project structure

```text
22760_WADII_Project/
├── index.html
├── register.html
├── process.php
├── css/
│   └── style.css
├── js/
│   └── script.js
├── README.md
└── .gitignore
```

## Run locally on Linux

PHP must run through a web server; opening `register.html` directly will not execute `process.php`.

1. Open a terminal in this project folder.
2. Check whether PHP is installed:

   ```bash
   php -v
   ```

3. If PHP is installed, start PHP's development server from this folder:

   ```bash
   php -S localhost:8000
   ```

4. Open `http://localhost:8000` in your browser.
5. Click **Register Now**, fill in the form, and submit it to test the PHP confirmation page.
6. Stop the server by returning to the terminal and pressing `Ctrl+C`.

If PHP is not installed on a Debian-based Linux distribution, you can install it with:

```bash
sudo apt update
sudo apt install php-cli
```

## Publish to GitHub

1. Sign in to GitHub and create a new repository, for example `22760-wadii-conference-project`.
2. Do not initialize the repository with a README if you plan to push this existing folder as-is.
3. Open a terminal in this folder and run:

   ```bash
   git init
   git add .
   git commit -m "Build conference registration CAT project"
   git branch -M main
   git remote add origin https://github.com/YOUR-USERNAME/22760-wadii-conference-project.git
   git push -u origin main
   ```

4. Replace `YOUR-USERNAME` with your actual GitHub username and use the repository URL GitHub provides.
5. If Git asks you to identify yourself, set your name and email with `git config --global user.name "Your Name"` and `git config --global user.email "you@example.com"`.

**Important:** GitHub Pages serves static files and does not execute PHP. Use the local PHP server steps above to demonstrate form processing, or deploy the project to a PHP-capable host.

## Before submission

- Replace the sample conference date/venue with details your lecturer expects, if provided.
- Test invalid form inputs and the 3-day discount.
- Take your own screenshots of the home page, validation, fee calculation, and PHP confirmation page. The CAT instructions require demonstration snapshots.
- Read and understand the code so you can explain it during your practical demonstration.
