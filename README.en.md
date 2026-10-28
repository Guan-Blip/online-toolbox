<div align="center">

**[🇨🇳 简体中文](./README.md) | 🇬🇧 English (Current)**

# 🧰 Online Toolbox · 在线工具箱

> **100% Local Execution · Data Never Touches the Cloud · Zero-Config, Upload & Go**

![Version](https://img.shields.io/badge/Version-v1.0-1677FF?style=flat-square)
![PHP](https://img.shields.io/badge/PHP-%E2%89%A57.4-777BB4?style=flat-square&logo=php&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-brightgreen?style=flat-square)
![Dependencies](https://img.shields.io/badge/Dependencies-0-success?style=flat-square)
![Database](https://img.shields.io/badge/Database-None-orange?style=flat-square)
![i18n](https://img.shields.io/badge/Languages-4-9B59B6?style=flat-square)
![Themes](https://img.shields.io/badge/Themes-30%2B-FF6B6B?style=flat-square)
![PRs Welcome](https://img.shields.io/badge/PRs-welcome-FF69B4?style=flat-square)

**11 high-frequency developer tools · runs on any PHP-capable host or NAS · upload and go**

</div>

---

## 📖 About

**Online Toolbox** is a collection of developer utilities written in **pure PHP** — no frameworks, no database, no Composer, not even a Node.js build chain.

It does exactly one thing: **ship the 11 most frequently used developer tools at the lowest possible deployment cost.** Drop the code onto any PHP-capable web host or NAS, and it just runs.

We firmly believe: **tools should stay tools.**

- 🔌 **Zero dependencies**: no database, no third-party PHP packages. Flat file structure — backing up = copying a folder
- 🛫 **Up and running instantly**: upload and go. No install wizard, no environment probing, zero configuration
- 🕵️ **Privacy first**: every conversion and computation happens locally. Your data never touches the cloud, never leaves the server, never leaves a trace
- 🎨 **Good looks built in**: 30+ polished themes — dark, light, and a rainbow of color schemes, one click away

> 📸 **<img width="1884" height="903" alt="image" src="https://github.com/user-attachments/assets/a4e32d11-9b72-4d51-996a-ba25b866c616" />
**

---

## ✨ Features

### 🛠 11 Essential Developer Tools

| # | Tool | Description |
|:-:|:-----|:------------|
| 1 | 🧾 JSON Formatter | One-click JSON beautify / minify with real-time syntax error location |
| 2 | ⏱️ Timestamp Converter | Bidirectional conversion between Unix timestamps and human-readable time |
| 3 | 🔗 URL Encoder/Decoder | URL encode / decode made trivial — query strings included |
| 4 | 🔄 Base64 | Text ↔ Base64 in a single step |
| 5 | 🔐 Hash Calculator | MD5 / SHA family digest computation |
| 6 | 💄 Code Beautifier/Minifier | Code formatting & minification (HTML/CSS/JS/PHP) |
| 7 | 🧪 Regex Tester | A regex sandbox with live matching, highlighting, and capture-group preview |
| 8 | 🎨 Color Converter | Convert between HEX / RGB / HSL |
| 9 | 🔤 Word Counter | Multi-dimensional stats: characters, words, lines, and more |
| 10 | 📝 Text Diff | Side-by-side diff to pinpoint text differences fast |
| 11 | 📐 Unit Converter | Length unit conversion (mm / cm / m / km / in / ft / yd / mi) |

### 🌟 More Highlights

- 🛡️ **Defense in depth**: strict CSP, CSRF protection, XSS filtering, and request rate limiting
- 🌍 **Multilingual UI**: Chinese, English, 日本語, and Русский out of the box
- 🎭 **30+ UI themes**: one click to switch — there's always one that suits you
- 🌈 **Local syntax highlighting**: powered by highlight.js, rendered 100% locally
- 📦 **Featherweight**: no Node.js, no build step — the source code *is* the product

---

## 🚀 Quick Start

### Requirements

| Item | Requirement |
|:-----|:------------|
| PHP | ≥ 7.4 (8.0+ recommended) |
| Database | ❌ Not required |
| Composer | ❌ Not required |
| Node.js | ❌ Not required |
| Web server | Any PHP-capable environment (Apache / Nginx / IIS / built-in server) |

### Option 1: Direct Upload to a Web Host ⚡

The simplest way — **upload and it works**:

```bash
# 1. Get the source code (either one)
git clone https://github.com/Guan-Blip/online-toolbox.git
# or download the zip from the Releases page and extract it

# 2. Upload the entire directory to your host's web root
#    Common paths: public_html / wwwroot / htdocs / www

# 3. Open it in your browser
#    http://your-domain.com/
```

Quick local preview (PHP built-in server):

```bash
cd online-toolbox
php -S localhost:8000
# open http://localhost:8000
```

### Option 2: Docker Deployment 🐳

**① One command with a bind mount (no build — recommended for NAS users)**

```bash
docker run -d \
  --name online-toolbox \
  -p 8080:80 \
  -v /path/to/online-toolbox:/var/www/html \
  --restart unless-stopped \
  php:8.2-apache
```

> 💡 On Windows PowerShell, replace `/path/to/online-toolbox` with `${PWD}\online-toolbox`.
> Once deployed, visit `http://SERVER-IP:8080`.

**② Build an image with a Dockerfile**

Create a `Dockerfile` in the project root:

```dockerfile
FROM php:8.2-apache

COPY . /var/www/html/

EXPOSE 80
CMD ["apache2-foreground"]
```

Build and run:

```bash
docker build -t online-toolbox .
docker run -d --name online-toolbox \
  -p 8080:80 \
  --restart unless-stopped \
  online-toolbox
```

**③ Docker Compose (also works on Synology / QNAP NAS)**

```yaml
services:
  toolbox:
    image: php:8.2-apache
    container_name: online-toolbox
    ports:
      - "8080:80"
    volumes:
      - ./:/var/www/html
    restart: unless-stopped
```

```bash
docker compose up -d
```

---

## 🛠️ Tech Stack

| Layer | Technology |
|:------|:-----------|
| Backend | Pure vanilla PHP (no framework, no ORM, no Composer) |
| Frontend | HTML5 + CSS3 + vanilla JavaScript (zero build) |
| Syntax highlighting | highlight.js (self-hosted, zero external requests) |
| i18n | Lightweight in-house i18n (zh-CN / en / ja / ru) |
| Theming | 30+ pure-CSS themes, hot-swappable at runtime |

```
online-toolbox/
├── index.php          # Main entry & routing
├── config.php         # Global config (themes, tool whitelist, etc.)
├── functions.php      # Core library (sanitization, algorithms, i18n)
├── lang.php           # Translation pack
├── style.css          # Global stylesheet
├── assets/            # Static assets (e.g. self-hosted highlight.js)
├── data/              # Runtime data (cache, rate-limit files; auto-generated)
└── LICENSE
```

---

## 🔒 Security

Security is not a bolt-on feature — it's a design floor. Even without a database or any external dependency, Online Toolbox sticks to Defense in Depth: from the inbound request to the outbound response, every layer has its own line of defense:

| Mechanism | Description |
|:----------|:------------|
| 🛡️ **CSP** | Strict Content-Security-Policy whitelist — kills script injection and data exfiltration at the HTTP response header level |
| 🔐 **CSRF protection** | One-time tokens enforced on forms and sensitive actions; forged cross-site requests go nowhere |
| 🧼 **XSS filtering** | Input filtering + output escaping — user data is always rendered as plain text |
| ⏱️ **Rate limiting** | Built-in lightweight rate limiter (file-based); brute-force requests and abuse get throttled immediately |

> ⚠️ We recommend enabling HTTPS after deployment and placing the site behind a reverse proxy or CDN for an even stronger security boundary.

---

## 🤝 Contributing

Every contribution is welcome — a bug fix, a new language, or a brand-new theme 🎉

1. 🍴 Fork this repository
2. 🌿 Create a feature branch: `git checkout -b feature/your-feature`
3. 💾 Commit your changes: `git commit -m "feat: add your-feature"`
4. 📤 Push the branch: `git push origin feature/your-feature`
5. 🔃 Open a Pull Request

**Ways to contribute**: new tools / new translations / new UI themes / bug fixes / docs

> When filing an issue, please include your PHP version, server environment, and reproduction steps for faster triage.

---

## 📄 License

Released under the [MIT License](./LICENSE).

You are free to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of this software, provided the original copyright and permission notices are included.

---

<div align="center">
Parts of the code and documentation in this project were generated with the assistance of AI.

**If this project helps you, please give it a ⭐ Star!**

Made with ❤️ by A Violet

</div>
