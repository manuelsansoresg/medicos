# Copilot Instructions for medicos

## Overview
This project is a Laravel-based medical management system. It uses Livewire for dynamic interfaces and follows Laravel's MVC structure. Key customizations and workflows are described below to help AI agents be productive quickly.

## Architecture & Key Components
- **app/Http/Controllers/**: Main controllers for handling HTTP requests.
- **app/Models/**: Eloquent models for database tables.
- **app/Exports/**: Custom export logic (e.g., Excel/PDF exports).
- **app/Lib/**: Service classes for business logic (e.g., `PayClipService.php`).
- **app/Mail/**: Custom email notifications.
- **resources/views/livewire/**: Blade views for Livewire components (dynamic forms, wizards, etc.).
- **routes/web.php** and **routes/api.php**: Route definitions for web and API endpoints.
- **config/enums.php**: Project-specific enums (e.g., status, interval_hours) used in forms and logic.

## Developer Workflows
- **Start local server:**
  ```
  php artisan serve
  ```
- **Run tests:**
  ```
  php artisan test
  ```
- **Compile assets:**
  ```
  npm run dev
  ```
- **Livewire usage:**
  - Use `wire:model` and `wire:click` for dynamic form fields and actions.
  - JavaScript interop: Use `window` functions and `setTimeout` to coordinate scroll/UX after Livewire updates.

## Project Conventions
- **Consultorios Wizard:**
  - Each consultorio step-pane has a unique anchor (`scroll-anchor-consultorio-<N>`) for scroll targeting.
  - Navigation buttons use `wire:click` for Livewire actions and custom JS for smooth scrolling.
- **Enums:**
  - Use `config('enums.<key>')` for select options and logic.
- **Blade/Livewire:**
  - Use `@foreach` and `@for` for dynamic form generation.
  - Use `@csrf` in all forms.
- **Custom JS:**
  - Place helper functions at the bottom of Blade files for scroll and UI effects.

## Integration Points
- **External Services:**
  - Payment integration via `app/Lib/PayClipService.php`.
  - Exports via `app/Exports/` and `maatwebsite/excel`.
- **Notifications:**
  - Custom emails in `app/Mail/`.

## Examples
- To add a new consultorio step, update the `@for` loop in `panel-configuration-live-wire.blade.php` and ensure a matching anchor div for scroll.
- To add a new enum, update `config/enums.php` and reference via `config('enums.<key>')` in Blade or PHP.

## References
- See `README.md` for general Laravel info.
- See `resources/views/livewire/panel-configuration-live-wire.blade.php` for advanced Livewire/JS integration patterns.

---
For questions about project-specific patterns, review the relevant directory or ask for examples from the codebase.
