# IAM Admin Panel - Design System Enforcement

Before making any modifications to the UI, creating new views, or refactoring existing UI components, you MUST read the `design-system.md` file located at the root of this project.

The application strictly adheres to the design constraints specified in that document (e.g., exclusively dark mode, specific Tailwind color palettes, and VyrnForge UI component usage). Ensure all your proposed UI changes comply with those guidelines.

# Custom CodeIgniter 3 Limitations

This project uses a custom, stripped-down version of CodeIgniter 3. Many standard CI3 functions and helpers (e.g., `redirect()`, `config_item()`, `is_ajax_request()`) are either missing or not automatically loaded.
When modifying controllers or core classes, ALWAYS prefer native PHP functions (e.g., `header('Location: ...')`, `$_SERVER` checks) over relying on CI3 globals unless you have explicitly verified their existence.
