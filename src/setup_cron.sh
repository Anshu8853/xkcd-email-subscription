#!/bin/bash

# Get full path to cron.php
SCRIPT_PATH=$(readlink -f "$(dirname "$0")/cron.php")

# Create a CRON line that runs every day at midnight
CRON_JOB="0 0 * * * php $SCRIPT_PATH"

# Add the CRON job
(crontab -l 2>/dev/null; echo "$CRON_JOB") | crontab -

echo "✅ CRON job added successfully!"
