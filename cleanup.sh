#!/bin/bash
# HatanHack Custom Cleanup script for CamPhish
# Removes all unnecessary files and logs (HatanHack & Original)

echo "Starting HatanHack cleanup of unnecessary files and logs..."

# 1. Remove ALL log files (HatanHack Custom Logs)
echo "Removing HatanHack custom log files..."
rm -f *.log
rm -f .cloudflared.log

# 2. Remove HatanHack custom location files
echo "Removing HatanHack custom location files..."
rm -f hatan_location_*.txt
rm -f hatan_current_location.txt
rm -f hatan_location_marker.log
rm -f hatan_saved_locations.txt

# 3. Remove captured images (HatanHack Custom Images)
echo "Removing HatanHack captured images..."
rm -f shot_*.png
rm -f cam*.png

# 4. Clean saved locations directory (HatanHack Custom Directory)
echo "Cleaning HatanHack saved locations directory..."
if [ -d "hatan_saved_locations" ]; then
    rm -f hatan_saved_locations/*
    # Optionally: remove the directory itself
    # rmdir hatan_saved_locations
fi

# 5. Remove Other old and temporary files (just in case)
echo "Removing old/original temporary files..."
rm -f location_*.txt
rm -f current_location.bak
rm -f LocationLog.log
rm -f Log.log

echo "HatanHack Cleanup completed successfully!"
