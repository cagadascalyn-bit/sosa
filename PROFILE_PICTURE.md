# Profile Picture — Base64 Database Storage

## Why This Approach?

Platforms like **Railway, Render, Heroku, and Fly.io** use ephemeral (temporary) containers.
Any file uploaded to the local filesystem (`storage/app/public/`) is **permanently deleted**
every time the container restarts or redeploys.

Storing the image as a **base64 string in the database** solves this because the database
is persistent — it survives restarts, redeploys, and scaling events.

---

## How It Works

### Upload Flow
```
User selects image → ProfileController validates it
  → reads raw binary with file_get_contents()
  → encodes to base64 with base64_encode()
  → prepends MIME type: "data:image/jpeg;base64,..."
  → saves the full data URI string into users.profile_picture_base64
  → sets users.profile_picture = null (old file path, no longer used)
```

### Display Flow
```
View calls $user->avatar  (Eloquent accessor on User model)
  → returns profile_picture_base64 if it exists
  → returns null if no image is set
View: @if($user->avatar) <img src="{{ $user->avatar }}"> @else show initials @endif
```

The `src` attribute of `<img>` accepts a data URI directly — no file path needed.

---

## Files Changed

| File | What Changed |
|------|-------------|
| `app/Models/User.php` | Added `profile_picture_base64` to `$fillable`, added `getAvatarAttribute()` accessor |
| `app/Http/Controllers/ProfileController.php` | Converts upload to base64 data URI, stores in DB |
| `database/migrations/0001_..._create_users_table.php` | Added `profile_picture_base64 LONGTEXT` column |
| `database/migrations/2024_01_01_000004_add_profile_picture_base64...php` | Alter migration for existing databases |
| `resources/views/layouts/app.blade.php` | Topbar avatar uses `$user->avatar` |
| `resources/views/users/index.blade.php` | User table avatar uses `$user->avatar` |
| `resources/views/profile/show.blade.php` | Profile card + form preview use `$user->avatar` |
| `sosa_recipes.sql` | Added `profile_picture_base64` column to SQL dump |

---

## Validation Rules

```php
'picture' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
```

- Only `jpeg`, `jpg`, `png` accepted
- Maximum **2MB** file size
- Enforced server-side in `ProfileController`
- File input restricted client-side: `accept="image/jpeg,image/jpg,image/png"`

---

## Deployment Steps

### Fresh Installation (new database)

1. Import `sosa_recipes.sql` into phpMyAdmin / Railway MySQL
2. Run `php artisan storage:link` (still useful for recipe images)
3. Done — `profile_picture_base64` column is already in the SQL

### Existing Installation (already has users table)

Run the alter migration:
```bash
php artisan migrate
```
This runs `2024_01_01_000004_add_profile_picture_base64_to_users_table.php`
which adds the `profile_picture_base64` column without touching existing data.

### Railway / Production Deployment

No extra configuration needed. The solution requires:
- ✅ A persistent MySQL/PostgreSQL database (Railway provides this)
- ✅ No filesystem writes for profile pictures
- ✅ No external services (S3, Cloudinary, etc.)
- ✅ No API keys

---

## Database Column

```sql
`profile_picture_base64` LONGTEXT DEFAULT NULL
```

`LONGTEXT` supports up to **4GB** — more than enough for a base64-encoded image.
A 2MB image becomes ~2.7MB as base64, well within this limit.

---

## Backward Compatibility

The old `profile_picture` column (file path) is kept in the database and model.
- Existing users with file-based pictures: their `profile_picture_base64` will be `null`,
  so they will see the initials placeholder until they re-upload their picture.
- New uploads always go to `profile_picture_base64` and set `profile_picture = null`.

---

## Trade-offs

| Pro | Con |
|-----|-----|
| Works on any platform, no config | Slightly larger DB size per user |
| No external services needed | Not suitable for very large images |
| Images survive redeploys | Can't serve images via CDN |
| Simple — pure PHP, no packages | DB backups include image data |

For a small-to-medium app this is the simplest production-ready solution.
For large-scale apps, consider **AWS S3** or **Cloudinary** instead.
