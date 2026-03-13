#!/bin/bash
PROJECT_PATH=$1
OUTPUT_PATH=$2

cd "$PROJECT_PATH"
chmod +x gradlew
./gradlew clean assembleDebug

APK_FILE=$(find . -name "*.apk" | head -1)
if [ -f "$APK_FILE" ]; then
    cp "$APK_FILE" "$OUTPUT_PATH"
    echo "SUCCESS"
    exit 0
else
    echo "FAILED"
    exit 1
fi
