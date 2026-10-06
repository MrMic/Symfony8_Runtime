# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A from-scratch Symfony 8.1 learning project ("tutoriel"): a bare skeleton built by hand
with only `framework-bundle`, `runtime`, `dotenv` and `twig-bundle`. There is no
`symfony/flex`, no recipes, no `bin/console`, no `config/` directory and no tests. The
app is four files: `public/index.php`, `src/Kernel.php`, `src/PagesController.php` and
`templates/`.

Do not "fix" the missing skeleton pieces by scaffolding them. Add a directory or a
dependency only when the task actually needs it.

## Commands

```bash
composer install                              # deps
php -S localhost:8000 -t public public/index.php   # dev server
frankenphp php-server --no-compress --listen 0.0.0.0:8001 -r public/   # dev server (FrankenPHP, classic mode)
rm -rf var/cache                              # reset the compiled container after config changes
composer require <pkg>                        # deps (ask before adding, per global CLAUDE.md)
```

No lint, no test runner, no `bin/console` is installed. If a task needs one, propose it
rather than assuming it exists.

## Architecture

**`public/index.php` is the front controller**, and nothing else:

1. `require vendor/autoload_runtime.php` hands control to `symfony/runtime`.
2. The runtime loads `.env` (via `symfony/dotenv`) and builds a `$context` array from it.
3. The file **returns a closure**, not a response. The runtime calls it with `$context`
   and boots the returned `App\Kernel`. `return` at the end of the file is load-bearing —
   removing it breaks the app with no obvious error.

**`src/Kernel.php`** extends `HttpKernel\Kernel` with `MicroKernelTrait` and does all the
wiring, since there is no `config/`:

- `registerBundles()` — FrameworkBundle + TwigBundle.
- `configureContainer()` — autowires/autoconfigures `App\` from `src/*`, excluding the
  Kernel. **Required**: without it controllers are not services, and typed constructor or
  action arguments (`LoggerInterface`, `Twig\Environment`) fail to resolve at runtime with
  `... requires the "$x" argument that could not be resolved`.
- `configureRoutes()` — imports `src/*Controller.php` with the `attribute` loader.

**`src/PagesController.php`** holds the routes as `#[Route]` attributes on public methods.
New pages go here (or a new `*Controller.php` in `src/`, picked up automatically).
`templates/` is TwigBundle's default path; `base.html.twig` is the layout.

## Environment

`.env` (`APP_ENV`, `APP_DEBUG`) **is committed** — deliberate for a tutorial; keep secrets
out of it. `.env.local` is gitignored. `$context['APP_ENV']` / `$context['APP_DEBUG']`
come from the runtime, not from `$_ENV` lookups in the kernel.

`var/cache/<env>/` holds the compiled container, one subtree per env (`dev`, `staging`
have been used). It is gitignored and safe to delete.

## Gotchas

- **Stale container cache**: a deleted or moved `var/cache/dev/Container*/` yields
  `Failed opening required '.../getErrorControllerService.php'`. `rm -rf var/cache`.
- `.env` is read by the runtime *before* the kernel exists, so `APP_ENV` cannot be
  changed from inside the kernel. Override it in the shell: `APP_ENV=prod php -S ...`.
- FrankenPHP (Homebrew build) needs `--no-compress`: without it, startup fails with
  `module not registered: http.encoders.br`. It runs its own PHP, separate from the CLI's.
- The dev server needs `-t public`; without it the docroot is the repo root and `vendor/`,
  `.env` and `composer.json` become web-readable.
