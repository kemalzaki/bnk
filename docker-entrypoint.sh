#!/bin/bash
set -e

# Disable conflicting Apache MPMs to prevent startup crashes
a2dismod mpm_event mpm_worker || true
a2enmod mpm_prefork

# Execute the default container command (apache2-foreground)
exec "$@"
