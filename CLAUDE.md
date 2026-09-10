# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A from-scratch Symfony 8.1 learning project ("tutoriel"): a bare skeleton built by hand
with only `framework-bundle`, `runtime` and `dotenv`. There is no `symfony/flex`, no
recipes, no `bin/console`, no `config/` directory, no `public/` directory and no tests.
Everything currently lives in **`index.php`**.

Do not "fix" the missing skeleton pieces by scaffolding them. Add a directory or a
dependency only when the task actually needs it.

## Commands

```bash
composer install                      # deps
php -S localhost:8000 index.php       # dev server (docroot = repo root, see below)
rm -rf var/cache                      # reset the compiled container after config changes
composer require <pkg>                # deps (ask before adding, per global CLAUDE.md)
```

No lint, no test runner, no `bin/console` is installed. If a task needs one, propose it
rather than assuming it exists.

## Architecture

**`index.php` is both the kernel class and the front controller.** It is the whole app:

1. `require vendor/autoload_runtime.php` hands control to `symfony/runtime`.
2. The runtime loads `.env` (via `symfony/dotenv`) and builds a `$context` array from it.
3. The file **returns a closure**, not a response. The runtime calls it with `$context`
   and boots the returned `Kernel`. `return` at the end of the file is load-bearing —
   removing it breaks the app with no obvious error.

`Kernel` extends `HttpKernel\Kernel` and uses `MicroKernelTrait`, which supplies the
default `registerBundles()` / `configureContainer()` / `configureRoutes()`. Since there is
no `config/` directory, only FrameworkBundle defaults are active.

To add routes, use `MicroKernelTrait`'s two options in `index.php`:
`#[Route]` attributes on public `Kernel` methods (they become controllers), or
`configureRoutes(RoutingConfigurator $routes)`.

## Environment

`.env` (`APP_ENV`, `APP_DEBUG`) **is committed** — deliberate for a tutorial; keep secrets
out of it. `.env.local` is gitignored. `$context['APP_ENV']` / `$context['APP_DEBUG']`
come from the runtime, not from `$_ENV` lookups in the kernel.

`var/cache/<env>/` holds the compiled container, one subtree per env (`dev`, `staging`
have been used). It is gitignored and safe to delete.

## Gotchas

- **Docroot is the repo root**, because there is no `public/`. `vendor/`, `.env` and
  `composer.json` sit next to the front controller and would be web-readable under a real
  web server. Fine for local learning; call it out before any deploy discussion.
- `.env` is read by the runtime *before* the kernel exists, so `APP_ENV` cannot be
  changed from inside `index.php`. Override it in the shell: `APP_ENV=prod php -S ...`.
