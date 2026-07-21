# AksharLab

A lightweight browser-based Hindi/English transliteration and typing practice platform.

## Features

- Standalone transliteration module is currently disabled in the UI.
- Shared English roman Hindi to Devanagari conversion remains active for Hindi typing practice.
- English and Hindi typing practice.
- Timer-based tests for 1, 2, and 5 minutes.
- Live WPM, accuracy, mistake count, and progress.
- Hindi practice using roman input with Devanagari scoring preview.
- Character-level mistake highlighting.
- Result summary after each test.
- Local progress history using browser localStorage.
- Responsive UI with no external dependencies.

## How to Run

Open `index.html` directly in a browser.

No package install, build step, database, or server is required.

## Re-enable Transliteration Module

1. Open `index.html`.
2. Uncomment the `Transliteration Module` navigation and panel blocks.
3. Open `app.js`.
4. Set `TRANSLITERATION_MODULE_ENABLED` to `true`.
