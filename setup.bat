@echo off
echo ============================================
echo  RecipeBook - Sosa Project Setup
echo ============================================
echo.

echo [1/3] Running Laravel migrations...
php artisan migrate --force
echo.

echo [2/3] Creating storage symlink...
php artisan storage:link
echo.

echo [3/3] Clearing caches...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
echo.

echo ============================================
echo  Setup complete!
echo  Visit: http://localhost/Sosa/public
echo  Login: admin@sosa.com / password123
echo ============================================
pause
