# Vortex Config

[![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-blue.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

A lightweight PHP configuration library for **PHP, JSON, YAML, and `.env` files**, supporting nested keys, strict mode, and memory-resident usage.  
Designed to be **Swoole-friendly** for long-running processes.

---

## Features

- Load configuration from **PHP, JSON, YAML, and `.env` files**
- Supports **nested keys** (`database.connections.mysql.host`)
- **Strict mode** to throw exceptions on missing keys
- **Memory-resident**, safe for Swoole long-running processes
- PSR-12 / PHP 8.1+ ready
- Lightweight and framework-agnostic

---

## Requirements

- PHP 8.1 or higher
- Optional extensions:
    - `ext-yaml` for YAML support
    - `ext-json` for JSON support

---

## Installation

Install via Composer:

```bash
composer require vortex-php/config