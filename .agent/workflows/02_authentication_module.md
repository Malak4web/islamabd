---
description: w3
---

# WORKFLOW 02 — Authentication Module
# Gate: ALL auth tests green before WF-03
# Prerequisite: WF-01 gate fully green
════════════════════════════════════════════════════════

## Purpose
Implement admin authentication using Laravel Sanctum SPA mode.
Cookie-based sessions, CSRF protection, Vue Router guards, Pinia
authStore, and AdminLogin.vue with full validation and UX.

## Why Sanctum SPA Mode?
  Cookie-based (no token in localStorage = more secure)
  CSRF protection built-in via double-submit cookie
  Perfect for Vue SPA on same domain as Laravel
  axios withCredentials:true handles session automatically

════════════════════════════════════════════════════════
## STEP 1 — Sanctum Configuration
════════════════════════════════════════════════════════
  bootstrap/app.php: $middleware->statefulApi();

  config/cors.php:
    'allowed_origins'     => [env('APP_URL')]
    'allowed_methods'     => ['*']
    'allowed_headers'     => ['*']
    'supports_credentials'=> true

  config/sanctum.php stateful:
    'localhost','localhost:3000','localhost:5173',
    '127.0.0.1', parse_url(env('APP_URL'))['host']

════════════════════════════════════════════════════════
## STEP 2 — TDD: Write Tests FIRST (all must be RED)
════════════════════════════════════════════════════════
  FILE: tests/Feature/Admin/AuthTest.php

  test_admin_can_login_with_valid_credentials
    POST /api/admin/login { email, password }
    assertOk()
    assertJsonStructure([data=>[id,name,email]])
    assertAuthenticatedAs(admin,'sanctum')

  test_admin_cannot_login_with_wrong_password
    POST /api/admin/login { email, wrong_password }
    assertUnauthorized()
    assertJson(['message'=>'Invalid credentials.'])

  test_admin_cannot_login_with_nonexistent_email
    POST /api/admin/login { fake@email.com, pass }
    assertUnauthorized()

  test_login_requires_email_field
    POST /api/admin/login { password:'pass' }
    assertUnprocessable()
    assertJsonValidationErrors(['email'])

  test_login_requires_password_field
    POST /api/admin/login { email:'e@e.com' }
    assertUnprocessable()
    assertJsonValidationErrors(['password'])

  test_login_email_must_be_valid_format
    POST /api/admin/login { email:'not-email', password:'pass' }
    assertUnprocessable()
    assertJsonValidationErrors(['email'])

  test_authenticated_admin_can_get_user_profile
    actingAs(admin,'sanctum')
    GET /api/admin/user
    assertOk()
    assertJsonStructure([data=>[id,name,email]])

  test_unauthenticated_request_returns_401
    GET /api/admin/user  ← no auth
    assertUnauthorized()

  test_admin_can_logout
    actingAs(admin,'sanctum')
    POST /api/admin/logout
    assertOk()
    assertJson(['message'=>'Logged out successfully.'])

  test_rate_limiting_blocks_after_5_failed_attempts
    6× POST /api/admin/login { wrong }
    last response: assertTooManyRequests() (429)

  Run: php artisan test tests/Feature/Admin/AuthTest.php
  EXPECTED: ALL RED ❌ — proceed to implementation

════════════════════════════════════════════════════════
## STEP 3 — Implement AuthController
════════════════════════════════════════════════════════
  FILE: app/Http/Controllers/Admin/AuthController.php

  login(LoginRequest $request):
    if !Auth::attempt(['email'=>email,'password'=>password]):
      return response()->json(['message'=>'Invalid credentials.'],401)
    $request->session()->regenerate()
    return 200 { data: $request->user(), message: 'Logged in.' }

  logout(Request $request):
    Auth::guard('web')->logout()
    $request->session()->invalidate()
    $request->session()->regenerateToken()
    return 200 { message: 'Logged out successfully.' }

  user(Request $request):
    return 200 { data: $request->user() }

  FILE: app/Http/Requests/Admin/LoginRequest.php
    rules: email(required|email), password(required|string)

════════════════════════════════════════════════════════
## STEP 4 — Register Routes
════════════════════════════════════════════════════════
  routes/api.php:
    Route::prefix('admin')->group(function() {
      Route::post('/login', [Admin\AuthController::class,'login'])
           ->middleware('throttle:5,1');
      Route::middleware('auth:sanctum')->group(function() {
        Route::post('/logout', [Admin\AuthController::class,'logout']);
        Route::get('/user',    [Admin\AuthController::class,'user']);
      });
    });

  Run: php artisan test tests/Feature/Admin/AuthTest.php
  EXPECTED: ALL GREEN ✅

════════════════════════════════════════════════════════
## STEP 5 — Axios Instance
════════════════════════════════════════════════════════
  FILE: resources/js/api/axios.js
    import axios from 'axios'
    const api = axios.create({
      baseURL:'/api',
      withCredentials:true,
      headers:{ 'Accept':'application/json',
                'X-Requested-With':'XMLHttpRequest' }
    })
    api.interceptors.request.use(config => {
      config.headers['Accept-Language'] = localStorage.getItem('locale')||'en'
      return config
    })
    api.interceptors.response.use(
      r => r,
      error => {
        if (error.response?.status === 401) {
          useAuthStore().clearUser()
          router.push({ name:'admin.login' })
        }
        return Promise.reject(error)
      }
    )
    export default api

════════════════════════════════════════════════════════
## STEP 6 — Pinia authStore
════════════════════════════════════════════════════════
  FILE: resources/js/stores/authStore.js
    state:   user=ref(null), isLoading=ref(false), error=ref(null)
    getters: isLoggedIn = computed(() => !!user.value)
    actions:
      login(email, password):
        GET /sanctum/csrf-cookie  ← MUST be first
        POST /api/admin/login { email, password }
        state.user = response.data.data
        router.push({ name:'admin.dashboard' })
      logout():
        POST /api/admin/logout
        state.user = null
        router.push({ name:'admin.login' })
      fetchUser():
        GET /api/admin/user → state.user = response.data.data
      clearUser():
        state.user = null

════════════════════════════════════════════════════════
## STEP 7 — Vue Router with Auth Guard
════════════════════════════════════════════════════════
  FILE: resources/js/router/index.js
    routes:
      { path:'/admin/login',  name:'admin.login',
        component:AdminLogin,    meta:{ requiresGuest:true } }
      { path:'/admin',        name:'admin.dashboard',
        component:AdminDashboard, meta:{ requiresAuth:true } }

    router.beforeEach(async (to) => {
      const auth = useAuthStore()
      if (to.meta.requiresAuth && !auth.isLoggedIn) {
        try { await auth.fetchUser() }
        catch { return { name:'admin.login', query:{ redirect:to.fullPath } } }
      }
      if (to.meta.requiresGuest && auth.isLoggedIn) {
        return { name:'admin.dashboard' }
      }
    })

════════════════════════════════════════════════════════
## STEP 8 — AdminLogin.vue Component
════════════════════════════════════════════════════════
  FILE: resources/js/views/admin/AdminLogin.vue
  Features:
    Branded login card (gold + dark theme)
    id="admin-email"    → email input
    id="admin-password" → password input with show/hide toggle
    id="admin-login-submit" → button, disabled + spinner while loading
    data-error → error message on 401
    Auto-redirect to /admin after success
    Enter key submits form

════════════════════════════════════════════════════════
## STEP 9 — Frontend Tests (Vitest)
════════════════════════════════════════════════════════
  Write FIRST → RED ❌ → implement → GREEN ✅

  tests/stores/authStore.test.js:
    test_initial_state_has_null_user
    test_isLoggedIn_false_when_user_null
    test_isLoggedIn_true_when_user_set
    test_clearUser_sets_user_to_null
    test_login_calls_csrf_then_login_endpoint
    test_login_sets_user_on_success
    test_login_throws_on_401

  tests/views/AdminLogin.test.js:
    test_renders_email_and_password_fields
    test_renders_submit_button
    test_shows_error_on_failed_login
    test_submit_disabled_while_loading
    test_calls_authStore_login_on_submit

  Run: npm run test → RED ❌ → implement → GREEN ✅

════════════════════════════════════════════════════════
## 🔴 TDD GATE 02 — ALL MUST GREEN BEFORE WF-03
════════════════════════════════════════════════════════

  ⛔ DO NOT START WF-03 UNTIL ALL CHECKS ARE GREEN ⛔

  [ ] php artisan test tests/Feature/Admin/AuthTest.php
        EXPECTED: 10 tests GREEN

  [ ] npm run test -- tests/stores/authStore.test.js
        EXPECTED: 7 tests GREEN

  [ ] npm run test -- tests/views/AdminLogin.test.js
        EXPECTED: 5 tests GREEN

  [ ] REGRESSION: php artisan test --env=testing && npm run test
        EXPECTED: ALL previous tests still green (WF-00 + WF-01)

  MANUAL:
  [ ] Visit /admin/login → login with seeded credentials → redirects to /admin
  [ ] Wrong password → error message shown
  [ ] Logout → redirects to /admin/login
  [ ] Refresh after login → stays logged in (cookie)
  [ ] Direct access to /admin without auth → redirected to login

  ALL GREEN → ✅ PROCEED TO WF-03
  ANY RED   → ❌ SEE REVISION FLOW

════════════════════════════════════════════════════════
## 🔄 REVISION FLOW
════════════════════════════════════════════════════════

  419 CSRF mismatch on login:
    Verify GET /sanctum/csrf-cookie before POST /login
    Check SANCTUM_STATEFUL_DOMAINS in .env matches browser host
    Check cors.php supports_credentials: true

  401 on valid credentials:
    Check Auth::attempt() uses correct guard
    Check password hashed with Hash::make in seeder
    Email case-sensitive match

  401 on protected routes after login:
    Check axios withCredentials:true
    Check route uses auth:sanctum middleware
    Check SANCTUM stateful domains config

  Router redirect loop:
    Check fetchUser() catches errors (doesn't rethrow)
    Check isLoggedIn is computed (reactive)
    Check requiresAuth vs requiresGuest meta flags

  Frontend test "store not found":
    setActivePinia(createPinia()) in beforeEach
    Import useAuthStore AFTER setActivePinia

════════════════════════════════════════════════════════
## ✅ SIGN-OFF — Before Moving to WF-03
════════════════════════════════════════════════════════
  [ ] AuthTest.php GREEN (10 tests)
  [ ] authStore + AdminLogin Vitest GREEN
  [ ] Regression suite GREEN
  [ ] Manual browser verification done
  [ ] git commit -m "feat: admin authentication with Sanctum SPA"
  [ ] NEXT → 03_settings_module.md
