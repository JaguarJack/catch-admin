# CatchAdmin V5 - AI Agent Guidelines

> This document provides guidelines for AI coding assistants working on the CatchAdmin V5 project.

## Project Overview

**CatchAdmin** is a frontend-backend separated admin management system built with Laravel 12 + Vue 3, featuring a modular architecture design.

| Layer | Tech Stack | Version |
|-------|------------|---------|
| Backend | PHP / Laravel Framework | 8.2 / 12.x |
| Frontend | Vue / Element Plus / Vite | 3.5 / 2.11 / 7.x |
| Styling | TailwindCSS / SCSS | 3.4 |
| State | Pinia | 3.0 |
| Types | TypeScript | 5.8 |

---

## Directory Structure

```
├── app/                    # Laravel core (avoid modifications)
├── bootstrap/              # Framework bootstrap files
├── catchadmin/             # CatchAdmin core package
├── config/                 # Laravel configuration
├── database/               # Database migrations, seeders
├── lang/                   # Localization files
├── modules/                # ⭐ Business modules (main development area)
│   ├── Common/             # Common module
│   ├── Develop/            # Development tools module
│   ├── Permissions/        # Permissions module
│   ├── System/             # System module
│   └── User/               # User module
├── public/                 # Public assets
├── resources/              # Laravel resources
├── routes/                 # Global routes
├── storage/                # Storage directory
├── tests/                  # Test files
├── vendor/                 # Composer dependencies (DO NOT modify)
└── web/                    # ⭐ Frontend project directory
    └── src/
        ├── components/     # Shared components
        ├── composables/    # Composition functions
        ├── layout/         # Layout components
        ├── router/         # Router configuration
        ├── stores/         # Pinia stores
        ├── styles/         # Style files
        ├── support/        # Utility functions
        ├── types/          # TypeScript types
        └── views/          # Page views
```

---

## Module Structure

Each business module should follow this structure (using `User` module as example):

```
modules/User/
├── Console/                # Artisan commands
├── Events/                 # Event classes
├── Export/                 # Export classes
├── Http/
│   ├── Controllers/        # Controllers
│   └── Requests/           # Form request validation
├── Import/                 # Import classes
├── Listeners/              # Event listeners
├── Middlewares/            # Middleware
├── Models/                 # Eloquent models
├── Providers/              # Service providers
├── Services/               # Business service layer
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/            # Database seeders
└── routes/                 # Module routes
```

---

## Commands

### Backend

```shell
# Start development server (frontend + backend)
composer run dev

# Code formatting (PSR-12)
composer format

# Static analysis (PHPStan)
composer analyse

# Update CatchAdmin core
composer latest
```

## Code Style

### PHP Backend

**Naming conventions:**
- Functions/Methods: `camelCase` (`getUserData`, `calculateTotal`)
- Classes: `PascalCase` (`UserController`, `DataService`)
- Constants: `UPPER_SNAKE_CASE` (`API_KEY`, `MAX_RETRIES`)

**Code example:**

```php
<?php

namespace Modules\User\Services;

use Modules\User\Models\User;

// ✅ Good - complete type declarations, single quotes, descriptive naming
class UserService
{
    public function findById(int $id): ?User
    {
        return User::query()->find($id);
    }

    public function getActiveUsers(): Collection
    {
        return User::query()
            ->where('status', 1)
            ->orderByDesc('created_at')
            ->get();
    }
}

// ❌ Bad - missing type declarations, double quotes, vague naming
class Service
{
    public function get($id)
    {
        return User::find($id);
    }
}
```

### Vue Frontend

**Naming conventions:**
- Component files: `PascalCase.vue` (`UserList.vue`, `DataTable.vue`)
- Composables: `use` prefix (`useTable`, `useForm`)
- Props/Emits: Must have TypeScript type definitions

**Code example:**

```vue
<!-- ✅ Good - script setup, TypeScript, complete type definitions -->
<script setup lang="ts">
import { ref, computed } from 'vue'
import type { User } from '@/types/user'

interface Props {
  userId: number
  readonly?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  readonly: false
})

const emit = defineEmits<{
  update: [user: User]
  delete: [id: number]
}>()

const loading = ref(false)
const user = ref<User | null>(null)

const displayName = computed(() => user.value?.name ?? 'Unknown User')

async function handleSubmit(): Promise<void> {
  if (!user.value) return
  emit('update', user.value)
}
</script>

<template>
  <div class="user-card">
    <span>{{ displayName }}</span>
    <el-button :loading="loading" @click="handleSubmit">
      Save
    </el-button>
  </div>
</template>

<style lang="scss" scoped>
.user-card {
  @apply p-4 rounded-lg bg-white shadow;
}
</style>
```

```vue
<!-- ❌ Bad - Options API, no types, inline styles -->
<script>
export default {
  props: ['userId'],
  data() {
    return { user: null }
  }
}
</script>
```

**Branch naming:**
- Feature: `feature/user-management`
- Fix: `fix/login-error`
- Refactor: `refactor/api-response`

**Commit message format:**
```
feat(user): add user export feature
fix(auth): fix token expiration issue
docs(readme): update installation guide
```

---

## Boundaries

### ✅ Always do
- Develop new features in corresponding modules under `modules/`
- Use migration files to manage database changes
- Run `composer check` before commits
- Follow existing module structure and naming conventions
- Use Element Plus components for frontend

### ⚠️ Ask first
- Adding new Composer/NPM dependencies
- Modifying database table structures
- Modifying `config/` configuration files
- Creating new modules

### 🚫 Never do
- Modify `vendor/` or `node_modules/`
- Commit `.env` files or hardcode secrets
- Delete existing migration files
- Modify `app/` core directory (prefer extending in modules)
- Write test credentials in code

## Resources

- [CatchAdmin Documentation](https://doc.catchadmin.com/)
- [Laravel Documentation](https://laravel-docs.catchadmin.com/)
- [Element Plus Documentation](https://element-plus.org/)
- [Vue 3 Documentation](https://vuejs.org/)
- [TailwindCSS Documentation](https://tailwindcss.com/)
