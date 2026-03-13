#!/bin/bash
echo "Setting up APK Builder..."

# Create directories
mkdir -p storage/{database,projects,builds}
mkdir -p public/assets/{css,js}

# Set permissions
chmod 755 storage
chmod 755 scripts/*.sh

# Create default admin
php -r "
\$db = new SQLite3('storage/database/apkbuilder.sqlite');
\$db->exec(\"CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, username TEXT, password TEXT)\");
\$db->exec(\"INSERT OR IGNORE INTO users (username, password) VALUES ('admin', '\" . password_hash('admin123', PASSWORD_DEFAULT) . \"')\");
echo \"Default admin: admin / admin123\n\";
"

echo "Setup complete!"
