# Comprehensive Survey Report: Tests and Configurations

## 1. Executive Summary

This survey report details all occurrences of legacy brand strings ("InDesign", "In Design", "إن ديزاين", and associated legacy email handles like `info@indesign.com`) within the test suites and configuration files of the project located at `c:\xampp\htdocs\islamabd`.

### Core Brand Mapping
- **English Brand**: `Eslam Abdulghani Designs` (replaces `InDesign` / `In Design`)
- **Uppercase Brand (Header Logo)**: `ESLAM ABDULGHANI DESIGNS` (replaces `INDESIGN`)
- **Arabic Brand**: `إسلام عبد الغني ديزاينز` (replaces `إن ديزاين`)
- **Brand Email**: `info@eslamabdulghanidesigns.com` (replaces `info@indesign.com`)

---

## 2. Config Files Survey

The following configuration files were inspected:
- `package.json`: Contains test scripts (`"test": "vitest run"`, `"build": "vite build"`). No brand references.
- `composer.json`: Framework metadata and PHPUnit dependency. No brand references.
- `vite.config.js`: Vitest setup (`test: { environment: 'jsdom', globals: true, setupFiles: 'resources/js/tests/setup.js' }`). No brand references.
- `phpunit.xml`: Defines `Unit` and `Feature` test suites. No brand references.
- `.env` & `.env.example`: APP_NAME set to `Laravel`. No brand references.
- `config/*`: Searched all 14 PHP configuration files under `config/` (`app.php`, `mail.php`, `database.php`, etc.). Zero brand occurrences found.

---

## 3. Test Suites & Affected Test Files Survey

A total of 6 test files (2 PHP backend tests and 4 JS/Vue frontend tests) contain direct occurrences of legacy branding strings.

### 3.1 Backend Test Files (PHPUnit)

1. **`tests/Feature/Api/SettingPublicTest.php`**
   - **Class**: `Tests\Feature\Api\SettingPublicTest`
   - **Test Case**: `test_public_can_get_all_settings()`
   - **Line 22**: `'value' => 'InDesign'`
   - **Line 30**: `->assertJsonPath('data.site_name', 'InDesign')`
   - **Proposed Change**: Replace `'InDesign'` with `'Eslam Abdulghani Designs'`.

2. **`tests/Unit/Models/SettingTest.php`**
   - **Class**: `Tests\Unit\Models\SettingTest`
   - **Test Case**: `test_setting_key_must_be_unique()`
   - **Line 22**: `Setting::create(['key' => 'site_name', 'value' => 'InDesign']);`
   - **Proposed Change**: Replace `'InDesign'` with `'Eslam Abdulghani Designs'`.

---

### 3.2 Frontend Test Files (Vitest / Vue Test Utils)

1. **`resources/js/tests/components/AppFooter.test.js`**
   - **Test Suite**: `AppFooter.vue`
   - **Test Case**: `it('renders phone and email from settings')`
   - **Line 25**: `email_main: 'info@indesign.com'`
   - **Line 39**: `expect(wrapper.text()).toContain('info@indesign.com')`
   - **Proposed Change**: Replace `'info@indesign.com'` with `'info@eslamabdulghanidesigns.com'`.

2. **`resources/js/tests/components/AppHeader.test.js`**
   - **Test Suite**: `AppHeader.vue`
   - **Test Case**: `it('renders nav links for all pages')`
   - **Line 34**: `expect(wrapper.text()).toContain('INDESIGN')`
   - **Proposed Change**: Replace `'INDESIGN'` with `'ESLAM ABDULGHANI DESIGNS'`.

3. **`resources/js/tests/stores/settingStore.test.js`**
   - **Test Suite**: `Setting Store`
   - **Test Case**: `it('fetchSettings populates state')`
   - **Line 21**: `const mockData = { site_name: 'InDesign', phone: '123' }`
   - **Proposed Change**: Replace `'InDesign'` with `'Eslam Abdulghani Designs'`.

4. **`resources/js/tests/views/ContactView.test.js`**
   - **Test Suite**: `ContactView.vue`
   - **Test Case**: `it('renders contact info from settings')`
   - **Line 20**: `email_main: 'info@indesign.com'`
   - **Line 35**: `expect(wrapper.text()).toContain('info@indesign.com')`
   - **Proposed Change**: Replace `'info@indesign.com'` with `'info@eslamabdulghanidesigns.com'`.

---

## 4. Test Commands & Build Scripts Summary

The project repository defines the following test and build commands:

| Command | Tool / Framework | Config File | Purpose |
|---|---|---|---|
| `php artisan test` | PHPUnit 11 | `phpunit.xml` | Runs PHP unit and feature tests |
| `npm test` / `npm run test` | Vitest 4.1 | `package.json`, `vite.config.js` | Runs JS/Vue unit & component tests |
| `npm run test:watch` | Vitest 4.1 | `package.json` | Runs Vitest in watch mode |
| `npm run test:coverage` | Vitest 4.1 | `package.json` | Generates Vitest test coverage |
| `npm run build` | Vite 6.0 | `vite.config.js` | Compiles frontend assets for production |

---

## 5. Matrix of Proposed Code Modifications

| File Path | Line(s) | Existing Content | Proposed Replacement |
|---|---|---|---|
| `tests/Feature/Api/SettingPublicTest.php` | 22 | `'value' => 'InDesign'` | `'value' => 'Eslam Abdulghani Designs'` |
| `tests/Feature/Api/SettingPublicTest.php` | 30 | `->assertJsonPath('data.site_name', 'InDesign')` | `->assertJsonPath('data.site_name', 'Eslam Abdulghani Designs')` |
| `tests/Unit/Models/SettingTest.php` | 22 | `Setting::create(['key' => 'site_name', 'value' => 'InDesign']);` | `Setting::create(['key' => 'site_name', 'value' => 'Eslam Abdulghani Designs']);` |
| `resources/js/tests/components/AppFooter.test.js` | 25 | `email_main: 'info@indesign.com'` | `email_main: 'info@eslamabdulghanidesigns.com'` |
| `resources/js/tests/components/AppFooter.test.js` | 39 | `expect(wrapper.text()).toContain('info@indesign.com')` | `expect(wrapper.text()).toContain('info@eslamabdulghanidesigns.com')` |
| `resources/js/tests/components/AppHeader.test.js` | 34 | `expect(wrapper.text()).toContain('INDESIGN')` | `expect(wrapper.text()).toContain('ESLAM ABDULGHANI DESIGNS')` |
| `resources/js/tests/stores/settingStore.test.js` | 21 | `const mockData = { site_name: 'InDesign', phone: '123' }` | `const mockData = { site_name: 'Eslam Abdulghani Designs', phone: '123' }` |
| `resources/js/tests/views/ContactView.test.js` | 20 | `email_main: 'info@indesign.com'` | `email_main: 'info@eslamabdulghanidesigns.com'` |
| `resources/js/tests/views/ContactView.test.js` | 35 | `expect(wrapper.text()).toContain('info@indesign.com')` | `expect(wrapper.text()).toContain('info@eslamabdulghanidesigns.com')` |
