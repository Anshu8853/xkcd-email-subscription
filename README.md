# 📩 XKCD Email Subscription System (Pure PHP, No PHPMailer)

A lightweight, framework-free PHP application that lets users subscribe or unsubscribe from daily XKCD comics via OTP email verification. Uses native PHP `mail()` function and stores emails in a `.txt` file — no database or external libraries required.

---

## 🚀 Features

- ✅ Subscribe using email + OTP verification
- ✅ Unsubscribe with OTP verification
- ✅ File-based email storage (`registered_emails.txt`)
- ✅ Sends daily XKCD using a cron job (`cron.php`)
- ✅ Windows batch script included (`send_comic.bat`)
- ❌ No PHPMailer or external libraries — just `mail()`

---

## 📁 Project Structure

xkcd-email-subscription/
│
├── src/
│ ├── index.php # Main UI for subscription/unsubscription
│ ├── functions.php # Core logic: register, unsubscribe, mail
│ ├── cron.php # Cron script for daily XKCD email
│ └── unsubscribe.php # Optional endpoint for unsubscribe form
│
├── registered_emails.txt # Stores subscribed emails (one per line)
├── send_comic.bat # Windows batch file to run cron via Task Scheduler
└── README.md # Project documentation (this file)


---

## 🔧 Requirements

- PHP 7.4 or later
- Apache server (XAMPP/WAMP/LAMP)
- A valid SMTP configuration for `mail()` (see below)
- Working internet connection to fetch XKCD API

---

## 🛠 Setup Instructions

### 1. Extract or Clone

Place the folder into your local web server directory:

- Windows (XAMPP):  
  `C:\xampp\htdocs\xkcd-email-subscription\`

- Linux (Apache):  
  `/var/www/html/xkcd-email-subscription/`

---

### 2. Configure PHP `mail()`

This project uses PHP's built-in `mail()` function. If you're using Gmail or an SMTP provider:

#### Windows (XAMPP)
- Edit `php.ini`:  
  Enable `sendmail_path` and configure `sendmail.ini`

- Use tools like [msmtp](https://msmtp.sourceforge.io/) for SMTP relay support

#### Linux (LAMP)
Ensure `postfix` or `ssmtp` is installed and configured properly.

---

### 3. Run the Application

In your browser, open:

http://localhost/xkcd-email-subscription/src/index.php


You can:
- ✅ Enter your email to subscribe
- 🔐 Input 6-digit OTP code from your inbox
- ❌ Unsubscribe with OTP anytime

---

## 🔁 Send Daily XKCD Comics

This feature reads `registered_emails.txt` and sends comics to all subscribers.

### 🐧 Linux: Setup CRON

Add this to crontab:
```bash
0 9 * * * /usr/bin/php /path/to/xkcd-email-subscription/src/cron.php
